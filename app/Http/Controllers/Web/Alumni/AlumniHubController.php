<?php

namespace App\Http\Controllers\Web\Alumni;

use App\Http\Controllers\Controller;
use App\Models\AlumniPost;
use App\Models\AlumniComment;
use App\Models\AlumniLike;
use App\Models\AlumniJob;
use App\Models\AlumniStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AlumniHubController extends Controller
{
    // ─── Alumni Hub Feed ────────────────────────────────────────
    public function hub(Request $request)
    {
        $user = $request->user();

        // Stories aktif (24h), grouped per user. Story milik user current selalu di depan.
        $stories = AlumniStory::active()
            ->with('user:id,name,avatar')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('user_id')
            ->map(function ($userStories) use ($user) {
                $owner = $userStories->first()->user;
                $viewedIds = \Illuminate\Support\Facades\DB::table('alumni_story_views')
                    ->where('user_id', $user->id)
                    ->whereIn('story_id', $userStories->pluck('id'))
                    ->pluck('story_id')
                    ->all();

                return [
                    'user_id'   => $owner->id,
                    'name'      => $owner->name,
                    'avatar'    => $owner->avatar,
                    'is_self'   => $owner->id === $user->id,
                    'all_seen'  => $userStories->pluck('id')->diff($viewedIds)->isEmpty(),
                    'items'     => $userStories->sortBy('created_at')->values()->map(fn ($s) => [
                        'id'         => $s->id,
                        'image_url'  => $s->image_url,
                        'caption'    => $s->caption,
                        'created_at' => $s->created_at->diffForHumans(),
                        'expires_at' => $s->expires_at->toIso8601String(),
                    ])->all(),
                ];
            })
            ->sortByDesc(fn ($g) => $g['is_self'])  // self first
            ->values();

        $posts = AlumniPost::with(['user:id,name,avatar,angkatan', 'comments.user:id,name,avatar'])
            ->withCount('likes')
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->through(function ($post) use ($user) {
                return [
                    'id'             => $post->id,
                    'type'           => $post->type,
                    'title'          => $post->title,
                    'content'        => $post->content,
                    'image_url'      => $post->image_url,
                    'likes_count'    => $post->likes_count,
                    'comments_count' => $post->comments_count,
                    'is_pinned'      => $post->is_pinned,
                    'is_liked'       => $post->likes()->where('user_id', $user->id)->exists(),
                    'author'         => [
                        'id'       => $post->user->id,
                        'name'     => $post->user->name,
                        'avatar'   => $post->user->avatar,
                        'angkatan' => $post->user->angkatan ?? '-',
                    ],
                    'comments'       => $post->comments->map(fn($c) => [
                        'id'      => $c->id,
                        'content' => $c->content,
                        'created_at' => $c->created_at->diffForHumans(),
                        'author'  => ['id' => $c->user->id, 'name' => $c->user->name, 'avatar' => $c->user->avatar],
                    ]),
                    'created_at' => $post->created_at->diffForHumans(),
                ];
            });

        return Inertia::render('Alumni/Hub', [
            'posts'   => $posts,
            'stories' => $stories,
        ]);
    }

    // ─── Stories CRUD (24h auto-expire) ─────────────────────────
    public function storeStory(Request $request)
    {
        $validated = $request->validate([
            'image'   => 'required|image|max:5120', // 5 MB
            'caption' => 'nullable|string|max:280',
        ]);

        $path = $request->file('image')->store('alumni_stories', 'public');

        AlumniStory::create([
            'user_id'    => $request->user()->id,
            'image_path' => $path,
            'caption'    => $validated['caption'] ?? null,
            'expires_at' => now()->addHours(24),
        ]);

        return back()->with('success', 'Story dibagikan! Akan hilang dalam 24 jam.');
    }

    public function destroyStory(Request $request, AlumniStory $story)
    {
        abort_if($story->user_id !== $request->user()->id, 403);

        if (Storage::disk('public')->exists($story->image_path)) {
            Storage::disk('public')->delete($story->image_path);
        }
        $story->delete();

        return back()->with('success', 'Story dihapus.');
    }

    public function viewStory(Request $request, AlumniStory $story)
    {
        // Auto-record view (deduplikasi by unique constraint)
        $story->viewers()->syncWithoutDetaching([$request->user()->id => ['viewed_at' => now()]]);

        return response()->noContent();
    }

    // ─── Store Post ─────────────────────────────────────────────
    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'type'    => 'required|in:story,achievement,event,question',
            'title'   => 'nullable|string|max:200',
            'content' => 'required|string|max:3000',
            'image'   => 'nullable|image|max:5120',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('alumni_posts', 'public');
            $imageUrl = \Illuminate\Support\Facades\Storage::url($path);
        }

        \App\Models\AlumniPost::create([
            'type'      => $validated['type'],
            'title'     => $validated['title'] ?? null,
            'content'   => $validated['content'],
            'user_id'   => $request->user()->id,
            'image_url' => $imageUrl,
        ]);

        return back()->with('success', 'Post berhasil dibagikan!');
    }

    // ─── Delete Post ────────────────────────────────────────────
    public function destroyPost(Request $request, AlumniPost $post)
    {
        abort_if($post->user_id !== $request->user()->id, 403);
        $post->delete();
        return back()->with('success', 'Post dihapus.');
    }

    // ─── Store Comment ──────────────────────────────────────────
    public function storeComment(Request $request, AlumniPost $post)
    {
        $validated = $request->validate(['content' => 'required|string|max:1000']);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        $post->increment('comments_count');

        return back()->with('success', 'Komentar ditambahkan.');
    }

    // ─── Toggle Like ────────────────────────────────────────────
    public function toggleLike(Request $request, AlumniPost $post)
    {
        $user = $request->user();
        $existing = AlumniLike::where('alumni_post_id', $post->id)->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            AlumniLike::create(['alumni_post_id' => $post->id, 'user_id' => $user->id]);
            $post->increment('likes_count');
            $liked = true;
        }

        return back()->with('liked', $liked);
    }

    // ─── Jobs Board ─────────────────────────────────────────────
    public function jobs(Request $request)
    {
        $jobs = AlumniJob::with('user:id,name,avatar,angkatan')
            ->where('is_active', true)
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('title', 'like', "%$s%")
                  ->orWhere('company', 'like', "%$s%");
            }))
            ->orderByDesc('created_at')
            ->paginate(12)
            ->through(fn($j) => [
                'id'          => $j->id,
                'type'        => $j->type,
                'title'       => $j->title,
                'company'     => $j->company,
                'location'    => $j->location,
                'work_type'   => $j->work_type,
                'description' => $j->description,
                'salary_range'=> $j->salary_range,
                'contact_info'=> $j->contact_info,
                'deadline'    => $j->deadline?->format('d M Y'),
                'posted_by'   => ['name' => $j->user->name, 'angkatan' => $j->user->angkatan ?? '-'],
                'created_at'  => $j->created_at->diffForHumans(),
            ]);

        return Inertia::render('Alumni/Jobs', [
            'jobs'   => $jobs,
            'filter' => ['type' => $request->type, 'search' => $request->search],
        ]);
    }

    // ─── Store Job ──────────────────────────────────────────────
    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'type'         => 'required|in:job,internship,freelance',
            'title'        => 'required|string|max:200',
            'company'      => 'required|string|max:200',
            'location'     => 'required|string|max:200',
            'work_type'    => 'required|in:onsite,remote,hybrid',
            'description'  => 'required|string|max:3000',
            'requirements' => 'nullable|string|max:1000',
            'salary_range' => 'nullable|string|max:100',
            'contact_info' => 'required|string|max:200',
            'deadline'     => 'nullable|date|after:today',
        ]);

        AlumniJob::create([...$validated, 'user_id' => $request->user()->id]);

        return back()->with('success', 'Lowongan berhasil diposting!');
    }
}
