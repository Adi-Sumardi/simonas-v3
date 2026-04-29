<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlumniPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AlumniPostController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AlumniPost::with([
                'author:id,name,avatar',
                'categories',
                'jobDetail'
            ])->where('alumni_post_status', 'published');

            // Filter berdasarkan tipe post
            if ($request->has('type')) {
                $query->where('alumni_post_type', $request->type);
            }

            // Filter berdasarkan kategori
            if ($request->has('category')) {
                $query->whereHas('categories', function($q) use ($request) {
                    $q->where('alumni_category_slug', $request->category);
                });
            }

            // Search
            if ($request->has('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('alumni_post_title', 'like', '%' . $request->search . '%')
                      ->orWhere('alumni_post_content', 'like', '%' . $request->search . '%');
                });
            }

            // Sort berdasarkan parameter
            if ($request->has('sort') && $request->sort === 'popular') {
                $query->orderBy('alumni_post_views', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $posts = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $posts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:191',
                'content' => 'required',
                'type' => 'required|in:berita,artikel,lowongan',
                'thumbnail' => 'nullable|image|max:2048',
                'categories' => 'array',
                'categories.*' => 'exists:alumni_categories,alumni_category_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            $post = new AlumniPost();
            $post->alumni_user_id = auth()->id();
            $post->alumni_post_title = $request->title;
            $post->alumni_post_slug = Str::slug($request->title);
            $post->alumni_post_content = $request->content;
            $post->alumni_post_type = $request->type;
            $post->alumni_post_status = $request->status ?? 'draft';

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = $file->getClientOriginalName();
                
                // Simpan di folder data_photo
                $file->move('../public_html/data_photo', $filename);
                
                // Simpan path relatif
                $post->alumni_post_thumbnail = $filename;
            }

            $post->save();

            // Sync categories
            if ($request->has('categories')) {
                $post->categories()->sync($request->categories);
            }

            // Handle job details if type is lowongan
            if ($request->type === 'lowongan' && $request->has('job_details')) {
                $post->jobDetail()->create($request->job_details);
            }

            return response()->json([
                'success' => true,
                'message' => 'Post berhasil dibuat',
                'data' => $post->load(['author', 'categories', 'jobDetail'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $post = AlumniPost::findOrFail($id);
            
            // Cek authorization
            if (auth()->user()->id !== $post->alumni_user_id && auth()->user()->role !== 'super') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus postingan ini'
                ], 403);
            }

            // Update thumbnail deletion
            if ($post->alumni_post_thumbnail) {
                $oldPath = '../public_html/data_photo/' . $post->alumni_post_thumbnail;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Hapus job detail jika tipe lowongan
            if ($post->alumni_post_type === 'lowongan') {
                $post->jobDetail()->delete();
            }

            // Hapus relasi kategori
            $post->categories()->detach();

            // Hapus post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $post = AlumniPost::with([
                'author:id,name,avatar',
                'categories',
                'jobDetail'
            ])->findOrFail($id);

            // Hitung ulang jumlah komentar
            $commentsCount = $post->comments()->count();
            $post->alumni_post_comments_count = $commentsCount;
            $post->save();

            // Increment view count
            $post->increment('alumni_post_views');

            return response()->json([
                'success' => true,
                'data' => $post
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in show method:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function recordView($id)
    {
        try {
            $post = AlumniPost::findOrFail($id);
            $post->increment('alumni_post_views');
            
            return response()->json([
                'success' => true,
                'views_count' => $post->alumni_post_views,
                'message' => 'View berhasil dicatat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat view: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $post = AlumniPost::findOrFail($id);
            
            // Cek authorization
            if (auth()->user()->id !== $post->alumni_user_id && auth()->user()->role !== 'super') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengubah postingan ini'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'alumni_post_title' => 'required|max:191',
                'alumni_post_content' => 'required',
                'alumni_post_type' => 'required|in:berita,artikel,lowongan',
                'alumni_post_thumbnail' => 'nullable|image|max:2048',
                'alumni_post_status' => 'required|in:draft,published'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            // Update data dasar
            $post->alumni_post_title = $request->alumni_post_title;
            $post->alumni_post_slug = Str::slug($request->alumni_post_title);
            $post->alumni_post_content = $request->alumni_post_content;
            $post->alumni_post_type = $request->alumni_post_type;
            $post->alumni_post_status = $request->alumni_post_status;

            // Handle thumbnail jika ada
            if ($request->hasFile('alumni_post_thumbnail')) {
                // Hapus thumbnail lama jika ada
                if ($post->alumni_post_thumbnail) {
                    $oldPath = '../public_html/data_photo/' . $post->alumni_post_thumbnail;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $file = $request->file('alumni_post_thumbnail');
                $filename = $file->getClientOriginalName();
                $file->move('../public_html/data_photo', $filename);
                $post->alumni_post_thumbnail = $filename;
            }

            $post->save();

            return response()->json([
                'success' => true,
                'message' => 'Post berhasil diperbarui',
                'data' => $post->load(['author', 'categories'])
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating post:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadThumbnail(Request $request)
    {
        try {
            $request->validate([
                'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $filename = $thumbnail->getClientOriginalName();
                
                // Simpan di public_html/data_photo
                $thumbnail->move('../public_html/data_photo', $filename);

                return response()->json([
                    'success' => true,
                    'filename' => $filename,
                    'message' => 'Thumbnail uploaded successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No file uploaded'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload thumbnail: ' . $e->getMessage()
            ], 500);
        }
    }

    // ... other methods (show, update, delete) ...
} 