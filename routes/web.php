<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\Mahasiswa\AktivitasController;
use App\Http\Controllers\Web\Mahasiswa\HafalanController    as MahasiswaHafalan;
use App\Http\Controllers\Web\Mahasiswa\LeaderboardController;
use App\Http\Controllers\Web\Mentor\MenteesController;
use App\Http\Controllers\Web\Mentor\HafalanController      as MentorHafalan;
use App\Http\Controllers\Web\Mentor\DashboardController    as MentorDash;
use Illuminate\Support\Facades\Auth;

// ─── Public / Guest ───────────────────────────────────────────
Route::get('/', fn () => inertia('Welcome'))->name('home');
Route::get('/privacy-policy', fn () => view('privacy-policy'))->name('privacy-policy');
Route::get('/reload-captcha',       [CaptchaController::class, 'reloadCaptcha']);
Route::get('/reload-captcha-login', [CaptchaController::class, 'reloadCaptchaLogin']);

// Google OAuth
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->where('provider', 'google')->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->where('provider', 'google')->name('socialite.callback');

// Laravel built-in auth (login, register, logout, password reset)
Auth::routes();

// ─── Authenticated ────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ── Unified Dashboard (all roles) ─────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Profile ───────────────────────────────────────────────
    Route::post('/change-password', [\App\Http\Controllers\UserController::class, 'changePassword'])
        ->name('user.changePassword');

    // ── Mahasiswa (permission-gated) ──────────────────────────
    Route::middleware('can:access-mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

        // ── Aktivitas CRUD ──────────────────────────────────────
        Route::get('/aktivitas',                [AktivitasController::class,  'index']) ->name('aktivitas.index');
        Route::post('/aktivitas',               [AktivitasController::class,  'store']) ->name('aktivitas.store');
        Route::put('/aktivitas/{id}',           [AktivitasController::class,  'update'])->name('aktivitas.update');
        Route::delete('/aktivitas/{id}',        [AktivitasController::class,  'destroy'])->name('aktivitas.destroy');

        // ── Hafalan CRUD ─────────────────────────────────────────
        Route::get('/hafalan',                  [MahasiswaHafalan::class, 'index'])     ->name('hafalan.index');
        Route::post('/hafalan/log',             [MahasiswaHafalan::class, 'storeLog'])  ->name('hafalan.log.store');
        Route::put('/hafalan/log/{log}',        [MahasiswaHafalan::class, 'updateLog']) ->name('hafalan.log.update');
        Route::delete('/hafalan/log/{log}',     [MahasiswaHafalan::class, 'destroyLog'])->name('hafalan.log.destroy');
        Route::post('/hafalan/bookmark',        [MahasiswaHafalan::class, 'bookmark'])  ->name('hafalan.bookmark');

        // ── Leaderboard ──────────────────────────────────────────
        Route::get('/leaderboard',              [LeaderboardController::class, 'index'])->name('leaderboard.index');

        // ── Profil + Riwayat CRUD ────────────────────────────────
        Route::get('/profil',                   [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'index'])         ->name('profil.index');
        Route::put('/profil',                   [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'update'])        ->name('profil.update');
        Route::post('/profil/avatar',           [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'updateAvatar'])  ->name('profil.avatar');
        Route::post('/profil/riwayat',          [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'storeRiwayat']) ->name('profil.riwayat.store');
        Route::put('/profil/riwayat/{riwayat}', [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'updateRiwayat'])->name('profil.riwayat.update');
        Route::delete('/profil/riwayat/{riwayat}',[\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'destroyRiwayat'])->name('profil.riwayat.destroy');

        // ── Kalender CRUD ────────────────────────────────────────
        Route::get('/kalender',                 [\App\Http\Controllers\Web\Mahasiswa\MahasiswaKalenderController::class, 'index']) ->name('kalender.index');
        Route::post('/kalender',                [\App\Http\Controllers\Web\Mahasiswa\MahasiswaKalenderController::class, 'store']) ->name('kalender.store');
        Route::put('/kalender/{event}',         [\App\Http\Controllers\Web\Mahasiswa\MahasiswaKalenderController::class, 'update'])->name('kalender.update');
        Route::delete('/kalender/{event}',      [\App\Http\Controllers\Web\Mahasiswa\MahasiswaKalenderController::class, 'destroy'])->name('kalender.destroy');
        Route::patch('/kalender/{event}/toggle', [\App\Http\Controllers\Web\Mahasiswa\MahasiswaKalenderController::class, 'toggleComplete'])->name('kalender.toggle');
    });

    // ── Alumni (permission-gated) ─────────────────────────────
    Route::middleware('can:access-alumni')->prefix('alumni')->name('alumni.')->group(function () {
        // Hub — social feed
        Route::get('/hub',                [App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'hub'])         ->name('hub');
        Route::post('/hub/posts',         [App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'storePost'])   ->name('posts.store');
        Route::delete('/hub/posts/{post}',[App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'destroyPost']) ->name('posts.destroy');
        Route::post('/hub/posts/{post}/comments', [App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'storeComment'])->name('comments.store');
        Route::post('/hub/posts/{post}/like',     [App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'toggleLike'])  ->name('posts.like');

        // Stories (24h auto-expire)
        Route::post('/hub/stories',                  [App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'storeStory'])  ->name('stories.store');
        Route::delete('/hub/stories/{story}',        [App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'destroyStory'])->name('stories.destroy');
        Route::post('/hub/stories/{story}/view',     [App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'viewStory'])   ->name('stories.view');

        // Jobs board
        Route::get('/jobs',        [App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'jobs'])    ->name('jobs.index');
        Route::post('/jobs',       [App\Http\Controllers\Web\Alumni\AlumniHubController::class, 'storeJob'])->name('jobs.store');

        // Profil — unified pattern (sama dengan Mahasiswa)
        Route::get('/profil',                    [App\Http\Controllers\Web\Alumni\AlumniProfileController::class, 'index'])         ->name('profil.index');
        Route::put('/profil',                    [App\Http\Controllers\Web\Alumni\AlumniProfileController::class, 'update'])        ->name('profil.update');
        Route::post('/profil/avatar',           [App\Http\Controllers\Web\Alumni\AlumniProfileController::class, 'updateAvatar'])  ->name('profil.avatar');
        Route::post('/profil/riwayat',           [App\Http\Controllers\Web\Alumni\AlumniProfileController::class, 'storeRiwayat']) ->name('profil.riwayat.store');
        Route::put('/profil/riwayat/{riwayat}',  [App\Http\Controllers\Web\Alumni\AlumniProfileController::class, 'updateRiwayat'])->name('profil.riwayat.update');
        Route::delete('/profil/riwayat/{riwayat}',[App\Http\Controllers\Web\Alumni\AlumniProfileController::class, 'destroyRiwayat'])->name('profil.riwayat.destroy');

        // Database Alumni
        Route::get('/database', [App\Http\Controllers\Web\Alumni\AlumniDatabaseController::class, 'index'])->name('database.index');
        Route::get('/database/{user}', [App\Http\Controllers\Web\Alumni\AlumniDatabaseController::class, 'details'])->name('database.details');

        // Bisnis directory
        Route::get('/bisnis',  [App\Http\Controllers\Web\Alumni\AlumniBusinessController::class, 'index']) ->name('bisnis.index');
        Route::post('/bisnis', [App\Http\Controllers\Web\Alumni\AlumniBusinessController::class, 'store']) ->name('bisnis.store');
    });

    // ── Mentor (permission-gated) ─────────────────────────────
    Route::middleware('can:access-mentor')->prefix('mentor')->name('mentor.')->group(function () {
        // Dashboard
        Route::get('/',           [MentorDash::class, 'index'])->name('dashboard');
        Route::post('/eval/{id}', [MentorDash::class, 'submitEval'])->name('eval.submit');

        // Mentees
        Route::get('/mentees',           [MenteesController::class, 'index'])->name('mentees.index');
        Route::post('/mentees',          [MenteesController::class, 'addMentee'])->name('mentees.add');
        Route::get('/mentees/{id}',      [MenteesController::class, 'show'])->name('mentees.show');
        Route::delete('/mentees/{id}',   [MenteesController::class, 'removeMentee'])->name('mentees.remove');

        // Hafalan queue
        Route::get('/hafalan/pending',    [MentorHafalan::class, 'pending'])->name('hafalan.pending');
        Route::patch('/hafalan/log/{id}', [MentorHafalan::class, 'score'])->name('hafalan.score');

        // Penilaian (hafalan scoring from list view)
        Route::get('/penilaian',       [\App\Http\Controllers\Web\Mentor\PenilaianController::class,      'index'])->name('penilaian.index');
        Route::post('/penilaian/{id}', [\App\Http\Controllers\Web\Mentor\PenilaianController::class,      'store'])->name('penilaian.store');

        // Kalender
        Route::get('/kalender', [\App\Http\Controllers\Web\Mentor\MentorKalenderController::class, 'index'])->name('kalender.index');
    });

    // ── Super Admin (permission-gated) ────────────────────────
    Route::middleware('can:access-super')->prefix('super')->name('super.')->group(function () {
        // Role & Permission management
        Route::get('/role-permission', [App\Http\Controllers\Web\Super\RolePermissionController::class, 'index'])
            ->name('role-permission');
        Route::post('/roles',              [App\Http\Controllers\Web\Super\RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::patch('/roles/{role}',      [App\Http\Controllers\Web\Super\RolePermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{role}',     [App\Http\Controllers\Web\Super\RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
        Route::post('/permissions',              [App\Http\Controllers\Web\Super\RolePermissionController::class, 'storePermission'])->name('permissions.store');
        Route::delete('/permissions/{permission}',[App\Http\Controllers\Web\Super\RolePermissionController::class, 'destroyPermission'])->name('permissions.destroy');
        Route::post('/roles/{role}/permissions', [App\Http\Controllers\Web\Super\RolePermissionController::class, 'syncRolePermissions'])->name('roles.permissions.sync');
        Route::post('/users',                      [App\Http\Controllers\Web\Super\RolePermissionController::class, 'storeUser'])->name('users.store');
        Route::post('/users/{user}/role',          [App\Http\Controllers\Web\Super\RolePermissionController::class, 'assignUserRole'])->name('users.role.assign');
        Route::post('/users/{user}/password',      [App\Http\Controllers\Web\Super\RolePermissionController::class, 'changePassword'])->name('users.password.change');

        // Super Admin pages (Phase 5)
        Route::get('/warga',      [App\Http\Controllers\Web\Super\SuperController::class, 'warga'])->name('warga.index');
        Route::get('/mentor',     [App\Http\Controllers\Web\Super\SuperController::class, 'mentor'])->name('mentor.index');
        Route::get('/mentor/{id}/analysis', [App\Http\Controllers\Web\Super\SuperController::class, 'mentorAnalysis'])->name('mentor.analysis');
        Route::get('/alumni',     [App\Http\Controllers\Web\Super\SuperController::class, 'alumni'])->name('alumni.index');
        Route::get('/kegiatan',   [App\Http\Controllers\Web\Super\SuperController::class, 'kegiatan'])->name('kegiatan.index');
        Route::post('/kegiatan',  [App\Http\Controllers\Web\Super\SuperController::class, 'storeKegiatan'])->name('kegiatan.store');
        Route::get('/hafalan',    [App\Http\Controllers\Web\Super\SuperController::class, 'hafalan'])->name('hafalan.index');
        Route::get('/leaderboard',[App\Http\Controllers\Web\Super\SuperController::class, 'leaderboard'])->name('leaderboard.index');
        Route::get('/laporan',    [App\Http\Controllers\Web\Super\SuperController::class, 'laporan'])->name('laporan.index');
        Route::get('/pengaturan', [App\Http\Controllers\Web\Super\SuperController::class, 'pengaturan'])->name('pengaturan.index');
    });

});
