<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('author')
                    ->where('status', 'published')
                    ->orderBy('published_at', 'desc');
                    
        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
        }
        
        $posts = $query->paginate(10);
        
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    public function show($slug)
    {
        $post = Post::with('author')->where('slug', $slug)->firstOrFail();
        
        // Increment view counter
        $post->increment('views');
        
        return response()->json([
            'status' => 'success',
            'data' => $post
        ]);
    }
    
    public function getByType($type)
    {
        $posts = Post::with('author')
                    ->where('type', $type)
                    ->where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->paginate(10);
                    
        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
            'type' => 'required|in:announcement,news,article',
            'featured_image' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->excerpt = Str::limit(strip_tags($request->content), 200);
        $post->type = $request->type;
        $post->author_id = auth()->id();

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $post->featured_image = $path;
        }

        $post->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'data' => $post
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required',
            'type' => 'required|in:announcement,news,article',
            'featured_image' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->excerpt = Str::limit(strip_tags($request->content), 200);
        $post->type = $request->type;

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            $path = $request->file('featured_image')->store('posts', 'public');
            $post->featured_image = $path;
        }

        $post->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Post updated successfully',
            'data' => $post
        ]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        // Delete featured image if exists
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Post deleted successfully'
        ]);
    }

    public function publish($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'published';
        $post->published_at = now();
        $post->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Post published successfully'
        ]);
    }

    public function archive($id)
    {
        $post = Post::findOrFail($id);
        $post->status = 'archived';
        $post->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Post archived successfully'
        ]);
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $path = $request->file('image')->store('posts/content', 'public');

        return response()->json([
            'status' => 'success',
            'url' => Storage::url($path)
        ]);
    }
}
