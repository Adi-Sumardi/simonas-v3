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
        Route::get('/aktivitas',          [AktivitasController::class,  'index'])->name('aktivitas.index');
        Route::get('/aktivitas/create',   [AktivitasController::class,  'create'])->name('aktivitas.create');
        Route::post('/aktivitas',         [AktivitasController::class,  'store'])->name('aktivitas.store');

        Route::get('/hafalan',            [MahasiswaHafalan::class,     'index'])->name('hafalan.index');
        Route::get('/hafalan/log/create', [MahasiswaHafalan::class,     'createLog'])->name('hafalan.log.create');
        Route::post('/hafalan/log',       [MahasiswaHafalan::class,     'storeLog'])->name('hafalan.log.store');

        Route::get('/leaderboard',        [LeaderboardController::class,'index'])->name('leaderboard.index');

        // Profile & Calendar
        Route::get('/profil',             [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'index'])->name('profil.index');
        Route::put('/profil',             [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'update'])->name('profil.update');
        Route::post('/profil/avatar',     [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'updateAvatar'])->name('profil.avatar');
        Route::get('/kalender',           [\App\Http\Controllers\Web\Mahasiswa\KalenderController::class, 'index'])->name('kalender.index');
    });

    // ── Mentor (permission-gated) ─────────────────────────────
    Route::middleware('can:access-mentor')->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/mentees',            [MenteesController::class, 'index'])->name('mentees.index');
        Route::get('/mentees/{id}',       [MenteesController::class, 'show'])->name('mentees.show');

        Route::get('/hafalan/pending',    [MentorHafalan::class,     'pending'])->name('hafalan.pending');
        Route::patch('/hafalan/log/{id}', [MentorHafalan::class,     'score'])->name('hafalan.score');

        Route::post('/penilaian/{id}',    [MentorDash::class,        'submitEval'])->name('penilaian.submit');

        // Penilaian & Kalender
        Route::get('/penilaian',          [\App\Http\Controllers\Web\Mentor\PenilaianController::class,      'index'])->name('penilaian.index');
        Route::post('/penilaian/{id}',    [\App\Http\Controllers\Web\Mentor\PenilaianController::class,      'store'])->name('penilaian.store');
        Route::get('/kalender',           [\App\Http\Controllers\Web\Mentor\MentorKalenderController::class, 'index'])->name('kalender.index');
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
        Route::post('/users/{user}/role',          [App\Http\Controllers\Web\Super\RolePermissionController::class, 'assignUserRole'])->name('users.role.assign');
        Route::post('/users/{user}/password',      [App\Http\Controllers\Web\Super\RolePermissionController::class, 'changePassword'])->name('users.password.change');

        // Super Admin pages (Phase 5)
        Route::get('/warga',      [App\Http\Controllers\Web\Super\SuperController::class, 'warga'])->name('warga.index');
        Route::get('/alumni',     [App\Http\Controllers\Web\Super\SuperController::class, 'alumni'])->name('alumni.index');
        Route::get('/kegiatan',   [App\Http\Controllers\Web\Super\SuperController::class, 'kegiatan'])->name('kegiatan.index');
        Route::get('/hafalan',    [App\Http\Controllers\Web\Super\SuperController::class, 'hafalan'])->name('hafalan.index');
        Route::get('/leaderboard',[App\Http\Controllers\Web\Super\SuperController::class, 'leaderboard'])->name('leaderboard.index');
        Route::get('/laporan',    [App\Http\Controllers\Web\Super\SuperController::class, 'laporan'])->name('laporan.index');
        Route::get('/pengaturan', [App\Http\Controllers\Web\Super\SuperController::class, 'pengaturan'])->name('pengaturan.index');
    });

});
