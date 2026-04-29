<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AlumniComment;
use App\Models\AlumniPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AlumniCommentController extends Controller
{
    public function index($postId)
    {
        try {
            $comments = AlumniComment::where('alumni_post_id', $postId)
                ->whereNull('alumni_parent_id') // Get only parent comments
                ->with(['replies.author', 'author'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $comments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'alumni_post_id' => 'required|exists:alumni_posts,alumni_post_id',
                'alumni_comment_content' => 'required|string',
                'alumni_parent_id' => 'nullable|exists:alumni_comments,alumni_comment_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if post exists and is published
            $post = AlumniPost::findOrFail($request->alumni_post_id);
            if ($post->alumni_post_status !== 'published') {
                return response()->json([
                    'success' => false,
                    'message' => 'Post is not available for comments'
                ], 403);
            }

            $comment = new AlumniComment();
            $comment->alumni_post_id = $request->alumni_post_id;
            $comment->alumni_user_id = Auth::id();
            $comment->alumni_comment_content = $request->alumni_comment_content;
            $comment->alumni_parent_id = $request->alumni_parent_id;
            $comment->save();

            // Load the author relationship
            $comment->load('author');

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'data' => $comment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $comment = AlumniComment::findOrFail($id);
            $user = auth()->user();

            // Cek apakah user adalah pemilik komentar atau super admin
            if ($comment->alumni_user_id !== $user->id && $user->role !== 'super') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
} 