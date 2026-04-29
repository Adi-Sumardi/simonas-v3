<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\AlumniController;
use App\Http\Controllers\Api\KegiatanController;
use App\Http\Controllers\Api\TemanSeangkatanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AlumniPostController;
use App\Http\Controllers\Api\AlumniCommentController;
// use App\Http\Controllers\Api\AlumniCategoryController; // TODO: controller belum ada
// use App\Http\Controllers\Api\AlumniJobApplicationController; // TODO: controller belum ada
use App\Http\Controllers\Api\V2\ReportController;
use App\Http\Controllers\Api\AlumniBusinessController;
use Illuminate\Support\Facades\Route;


// API (untuk aplikasi alumni)

Route::post('/login', [AuthController::class, 'login']);
Route::post('/check-phone', [AuthController::class, 'checkPhone']);
Route::post('/login-with-phone', [AuthController::class, 'loginWithPhone']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/check-existing-user', [AuthController::class, 'checkExistingUser']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
});

Route::middleware('auth:sanctum')->prefix('alumni')->group(function () {
    Route::get('/', [AlumniController::class, 'index']);
    Route::get('/{id}', [AlumniController::class, 'show']);
    Route::put('/{id}', [AlumniController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/kegiatan', [KegiatanController::class, 'index']);
    Route::get('/teman-seangkatan/{userId}', [TemanSeangkatanController::class, 'getTemanSeangkatan']);
    Route::get('/alumni/statistics', [AlumniController::class, 'getStatistics']);
    Route::get('alumni/statistics/{asrama}', [AlumniController::class, 'getAsramaStatistics']);
    Route::get('/random-alumni', [AlumniController::class, 'getRandomAlumni']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('alumni/{id}', [AlumniController::class, 'update']);
    Route::post('alumni/{id}', [AlumniController::class, 'update']);
});

// Alumni Post Routes
Route::middleware('auth:sanctum')->prefix('alumni-posts')->group(function () {
    Route::get('/', [AlumniPostController::class, 'index']);
    Route::post('/', [AlumniPostController::class, 'store']);
    Route::get('/{id}', [AlumniPostController::class, 'show']);
    Route::put('/{id}', [AlumniPostController::class, 'update']);
    Route::delete('/{id}', [AlumniPostController::class, 'destroy']);
    
    // Comments
    Route::post('/{id}/comments', [AlumniCommentController::class, 'store']);
    Route::delete('/comments/{id}', [AlumniCommentController::class, 'destroy']);
    
    // Categories
    // Route::get('/categories', [AlumniCategoryController::class, 'index']); // TODO: controller belum ada

    // Job Applications (untuk lowongan)
    // Route::post('/{id}/apply', [AlumniJobApplicationController::class, 'store']); // TODO: controller belum ada
    Route::post('/upload-thumbnail', [AlumniPostController::class, 'uploadThumbnail']);
});

// Public routes
Route::get('/alumni-posts/public', [AlumniPostController::class, 'index']);
Route::get('/alumni-posts/public/{id}', [AlumniPostController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/alumni-comments/{postId}', [AlumniCommentController::class, 'index']);
    Route::post('/alumni-comments', [AlumniCommentController::class, 'store']);
    Route::delete('/alumni-comments/{id}', [AlumniCommentController::class, 'destroy']);
});

Route::post('alumni-posts/{id}/view', [AlumniPostController::class, 'recordView'])
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/update-avatar', [AuthController::class, 'updateAvatar']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
});

// API v2 (untuk aplikasi simonas)
Route::prefix('v2')->group(function () {
    // Public routes
    Route::post('/login', [App\Http\Controllers\Api\V2\AuthController::class, 'login']);
    Route::post('/check-phone', [App\Http\Controllers\Api\V2\AuthController::class, 'checkPhone']);
    Route::post('/login-with-phone', [App\Http\Controllers\Api\V2\AuthController::class, 'loginWithPhone']);
    
    // Posts routes (pengumuman/berita/artikel)
    Route::get('/posts', [App\Http\Controllers\Api\V2\PostController::class, 'index']);
    Route::get('/posts/{slug}', [App\Http\Controllers\Api\V2\PostController::class, 'show']);
    Route::get('/posts/type/{type}', [App\Http\Controllers\Api\V2\PostController::class, 'getByType']);
    
    // Protected routes (memerlukan authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Profile routes
        Route::get('/profile', [App\Http\Controllers\Api\V2\AuthController::class, 'profile']);
        Route::get('/profile/full', [App\Http\Controllers\Api\V2\AuthController::class, 'fullProfile']);
        Route::post('/profile/update', [App\Http\Controllers\Api\V2\AuthController::class, 'updateProfile']);
        Route::post('/profile/avatar', [App\Http\Controllers\Api\V2\AuthController::class, 'updateAvatar']);
        Route::post('/logout', [App\Http\Controllers\Api\V2\AuthController::class, 'logout']);
        
        // Mahasiswa routes — TODO: V2/MahasiswaController belum ada
        // Route::get('/mahasiswa', [App\Http\Controllers\Api\V2\MahasiswaController::class, 'index']);
        // Route::get('/mahasiswa/detail/{id}', [App\Http\Controllers\Api\V2\MahasiswaController::class, 'show']);
        // Route::get('/mahasiswa/search', [App\Http\Controllers\Api\V2\MahasiswaController::class, 'search']);
        
        // Kegiatan routes
        Route::get('/kegiatan', [App\Http\Controllers\Api\V2\KegiatanAsramaController::class, 'index']);
        Route::get('/kegiatan/penyelenggara', [App\Http\Controllers\Api\V2\KegiatanAsramaController::class, 'getPenyelenggara']);
        
        // Komponen routes
        Route::get('/komponen/{aspek}', [App\Http\Controllers\Api\V2\KomponenController::class, 'getByAspek']);
        
        // Activity routes
        Route::prefix('aktivitas')->group(function () {
            Route::post('/akademik', [App\Http\Controllers\Api\V2\AktivitasController::class, 'storeAkademik']);
            Route::post('/leadership', [App\Http\Controllers\Api\V2\AktivitasController::class, 'storeLeadership']);
            Route::post('/karakter', [App\Http\Controllers\Api\V2\AktivitasController::class, 'storeKarakter']);
            Route::post('/kreatif', [App\Http\Controllers\Api\V2\AktivitasController::class, 'storeKreatif']);
        });

        // Report routes (pindahkan ke dalam group yang sama)
        Route::prefix('reports')->group(function () {
            Route::get('/summary', [App\Http\Controllers\Api\V2\ReportController::class, 'getSummary']);
            Route::get('/activities/{aspek}', [App\Http\Controllers\Api\V2\ReportController::class, 'getActivitiesByAspek']);
            // Tambahkan route baru untuk recent activities
            Route::get('/recent', [App\Http\Controllers\Api\V2\ReportController::class, 'getRecentActivities']);
        });
        
        // Protected Posts routes
        Route::post('/posts', [App\Http\Controllers\Api\V2\PostController::class, 'store']);
        Route::put('/posts/{id}', [App\Http\Controllers\Api\V2\PostController::class, 'update']);
        Route::delete('/posts/{id}', [App\Http\Controllers\Api\V2\PostController::class, 'destroy']);
        Route::post('/posts/{id}/publish', [App\Http\Controllers\Api\V2\PostController::class, 'publish']);
        Route::post('/posts/{id}/archive', [App\Http\Controllers\Api\V2\PostController::class, 'archive']);
        Route::post('/posts/upload-image', [App\Http\Controllers\Api\V2\PostController::class, 'uploadImage']);
    });
});

// Alumni Business Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/alumni-businesses', [AlumniBusinessController::class, 'index']);
    Route::post('/alumni-businesses', [AlumniBusinessController::class, 'store']);
    Route::get('/alumni-businesses/{id}', [AlumniBusinessController::class, 'show']);
    Route::put('/alumni-businesses/{id}', [AlumniBusinessController::class, 'update']);
    Route::delete('/alumni-businesses/{id}', [AlumniBusinessController::class, 'destroy']);
});
