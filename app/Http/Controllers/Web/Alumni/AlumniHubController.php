<?php

namespace App\Http\Controllers\Web\Alumni;

use App\Http\Controllers\Controller;
use App\Models\AlumniPost;
use App\Models\AlumniComment;
use App\Models\AlumniLike;
use App\Models\AlumniJob;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumniHubController extends Controller
{
    // ─── Alumni Hub Feed ────────────────────────────────────────
    public function hub(Request $request)
    {
        $user = $request->user();

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
            'posts' => $posts,
        ]);
    }

    // ─── Store Post ─────────────────────────────────────────────
    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'type'    => 'required|in:story,achievement,event,question',
            'title'   => 'nullable|string|max:200',
            'content' => 'required|string|max:3000',
        ]);

        AlumniPost::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Post berhasil dibagikan!');
    }

    // ─── Delete Post ────────────────────────────────────────────
    public function destroyPost(Request $request, AlumniPost $post)
    {
        $this->authorize('delete', $post); // only owner
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
