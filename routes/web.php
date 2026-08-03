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
use App\Http\Controllers\Web\WelcomeController;
use Illuminate\Support\Facades\Auth;

// ─── Public / Guest ───────────────────────────────────────────
Route::get('/', WelcomeController::class)->name('home');
Route::get('/privacy-policy', fn () => inertia('PrivacyPolicy'))->name('privacy-policy');
Route::get('/terms', fn () => inertia('Terms'))->name('terms');
Route::get('/help-center', fn () => inertia('HelpCenter'))->name('help-center');
Route::get('/reload-captcha',       [CaptchaController::class, 'reloadCaptcha']);
Route::get('/reload-captcha-login', [CaptchaController::class, 'reloadCaptchaLogin']);

// Google OAuth
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->where('provider', 'google')->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->where('provider', 'google')->name('socialite.callback');

// Laravel built-in auth (login, register, logout, password reset, email verification)
Auth::routes(['verify' => true]);

// ─── Authenticated ────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // ── Onboarding wizard (warga mahasiswa baru) ──────────────
    Route::get('/onboarding',  [\App\Http\Controllers\Web\OnboardingController::class, 'show']) ->name('onboarding.show');
    Route::post('/onboarding', [\App\Http\Controllers\Web\OnboardingController::class, 'store'])->name('onboarding.store');
});

Route::middleware(['auth', 'verified', 'onboarding.complete'])->group(function () {

    // ── Notifications ─────────────────────────────────────────
    Route::get('/notifications', [\App\Http\Controllers\Web\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/read-all', [\App\Http\Controllers\Web\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::patch('/notifications/{notification}/read', [\App\Http\Controllers\Web\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\Web\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // ── Unified Dashboard (all roles) ─────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Profile ───────────────────────────────────────────────
    Route::post('/change-password', [\App\Http\Controllers\UserController::class, 'changePassword'])
        ->name('user.changePassword');

    // ── File (bukti kegiatan, disimpan sebagai BLOB di database) ──
    Route::get('/files/{table}/{id}', [\App\Http\Controllers\Web\FileController::class, 'show'])
        ->where(['table' => 'akademiks|leaderships|karakters|kreatifs', 'id' => '[0-9]+'])
        ->name('files.show');

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

        // ── Log Book Mentoring (read-only) ────────────────────────
        Route::get('/logbook', [\App\Http\Controllers\Web\Mahasiswa\LogbookController::class, 'index'])->name('logbook.index');

        // ── Leaderboard ──────────────────────────────────────────
        Route::get('/leaderboard',              [LeaderboardController::class, 'index'])->name('leaderboard.index');

        // ── Profil + Riwayat CRUD ────────────────────────────────
        Route::get('/profil',                   [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'index'])         ->name('profil.index');
        Route::get('/portfolio',                [\App\Http\Controllers\Web\Mahasiswa\ProfileController::class, 'portfolio'])     ->name('portfolio');
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
        Route::get('/live-meet/status', [\App\Http\Controllers\Web\Mahasiswa\LiveMeetController::class, 'checkStatus'])->name('live-meet.status');
        Route::get('/live-meet/join',   [\App\Http\Controllers\Web\Mahasiswa\LiveMeetController::class, 'join'])->name('live-meet.join');
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

        // Log Book Mentoring
        Route::get('/logbook',           [\App\Http\Controllers\Web\Mentor\LogbookController::class, 'index'])  ->name('logbook.index');
        Route::post('/logbook',          [\App\Http\Controllers\Web\Mentor\LogbookController::class, 'store'])  ->name('logbook.store');
        Route::put('/logbook/{log}',     [\App\Http\Controllers\Web\Mentor\LogbookController::class, 'update']) ->name('logbook.update');
        Route::delete('/logbook/{log}',  [\App\Http\Controllers\Web\Mentor\LogbookController::class, 'destroy'])->name('logbook.destroy');

        // Live Meet
        Route::post('/live-meet/start', [\App\Http\Controllers\Web\Mentor\LiveMeetController::class, 'start'])->name('live-meet.start');
        Route::get('/live-meet',        [\App\Http\Controllers\Web\Mentor\LiveMeetController::class, 'view'])->name('live-meet.view');
        Route::post('/live-meet/stop',  [\App\Http\Controllers\Web\Mentor\LiveMeetController::class, 'stop'])->name('live-meet.stop');
    });

    // ── Pengurus Asrama (permission-gated) ───────────────────
    Route::middleware('can:access-pengurus-asrama')->prefix('pengurus-asrama')->name('pengurus-asrama.')->group(function () {
        Route::get('/kegiatan',                    [\App\Http\Controllers\Web\PengurusAsrama\PengurusAsramaController::class, 'kegiatanIndex'])    ->name('kegiatan.index');
        Route::post('/kegiatan',                   [\App\Http\Controllers\Web\PengurusAsrama\PengurusAsramaController::class, 'kegiatanStore'])    ->name('kegiatan.store');
        Route::put('/kegiatan/{kegiatan}',         [\App\Http\Controllers\Web\PengurusAsrama\PengurusAsramaController::class, 'kegiatanUpdate'])   ->name('kegiatan.update');
        Route::delete('/kegiatan/{kegiatan}',      [\App\Http\Controllers\Web\PengurusAsrama\PengurusAsramaController::class, 'kegiatanDestroy'])  ->name('kegiatan.destroy');

        Route::get('/program-kerja',               [\App\Http\Controllers\Web\PengurusAsrama\PengurusAsramaController::class, 'programKerjaIndex'])   ->name('program-kerja.index');
        Route::post('/program-kerja',              [\App\Http\Controllers\Web\PengurusAsrama\PengurusAsramaController::class, 'programKerjaStore'])   ->name('program-kerja.store');
        Route::put('/program-kerja/{programKerja}',[\App\Http\Controllers\Web\PengurusAsrama\PengurusAsramaController::class, 'programKerjaUpdate'])  ->name('program-kerja.update');
        Route::delete('/program-kerja/{programKerja}',[\App\Http\Controllers\Web\PengurusAsrama\PengurusAsramaController::class, 'programKerjaDestroy'])->name('program-kerja.destroy');
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
        Route::delete('/users/{user}',             [App\Http\Controllers\Web\Super\RolePermissionController::class, 'destroyUser'])->name('users.destroy');

        // Super Admin pages (Phase 5)
        Route::get('/warga',      [App\Http\Controllers\Web\Super\SuperController::class, 'warga'])->name('warga.index');
        Route::post('/warga',     [App\Http\Controllers\Web\Super\SuperController::class, 'storeWarga'])->name('warga.store');
        Route::get('/mentor',     [App\Http\Controllers\Web\Super\SuperController::class, 'mentor'])->name('mentor.index');
        Route::get('/mentor/{id}/analysis', [App\Http\Controllers\Web\Super\SuperController::class, 'mentorAnalysis'])->name('mentor.analysis');
        Route::get('/alumni',     [App\Http\Controllers\Web\Super\SuperController::class, 'alumni'])->name('alumni.index');
        Route::get('/kegiatan',   [App\Http\Controllers\Web\Super\SuperController::class, 'kegiatan'])->name('kegiatan.index');
        Route::post('/kegiatan',  [App\Http\Controllers\Web\Super\SuperController::class, 'storeKegiatan'])->name('kegiatan.store');
        Route::get('/hafalan',    [App\Http\Controllers\Web\Super\SuperController::class, 'hafalan'])->name('hafalan.index');
        Route::get('/leaderboard',[App\Http\Controllers\Web\Super\SuperController::class, 'leaderboard'])->name('leaderboard.index');
        Route::get('/laporan',    [App\Http\Controllers\Web\Super\SuperController::class, 'laporan'])->name('laporan.index');
        Route::get('/pengaturan', [App\Http\Controllers\Web\Super\SuperController::class, 'pengaturan'])->name('pengaturan.index');
        Route::post('/pengaturan', [App\Http\Controllers\Web\Super\SuperController::class, 'updatePengaturan'])->name('pengaturan.update');

        // Point Rules CRUD
        Route::post('/point-rules',                     [App\Http\Controllers\Web\Super\SuperController::class, 'storePointRule'])  ->name('point-rules.store');
        Route::put('/point-rules/{rule}',               [App\Http\Controllers\Web\Super\SuperController::class, 'updatePointRule']) ->name('point-rules.update');
        Route::delete('/point-rules/{rule}',            [App\Http\Controllers\Web\Super\SuperController::class, 'destroyPointRule'])->name('point-rules.destroy');
        Route::patch('/point-rules/{rule}/toggle',      [App\Http\Controllers\Web\Super\SuperController::class, 'togglePointRule']) ->name('point-rules.toggle');

        // Daily Targets CRUD
        Route::post('/daily-targets',                   [App\Http\Controllers\Web\Super\SuperController::class, 'storeDailyTarget'])  ->name('daily-targets.store');
        Route::put('/daily-targets/{target}',           [App\Http\Controllers\Web\Super\SuperController::class, 'updateDailyTarget']) ->name('daily-targets.update');
        Route::delete('/daily-targets/{target}',        [App\Http\Controllers\Web\Super\SuperController::class, 'destroyDailyTarget'])->name('daily-targets.destroy');
        Route::patch('/daily-targets/{target}/toggle',  [App\Http\Controllers\Web\Super\SuperController::class, 'toggleDailyTarget']) ->name('daily-targets.toggle');
        Route::post('/asrama',                          [App\Http\Controllers\Web\Super\SuperController::class, 'storeAsrama'])   ->name('asrama.store');
        Route::put('/asrama/{asrama}',                  [App\Http\Controllers\Web\Super\SuperController::class, 'updateAsrama'])  ->name('asrama.update');
        Route::delete('/asrama/{asrama}',               [App\Http\Controllers\Web\Super\SuperController::class, 'destroyAsrama']) ->name('asrama.destroy');
        Route::post('/asrama/{asrama}/jabatan',          [App\Http\Controllers\Web\Super\SuperController::class, 'storeJabatan'])  ->name('asrama.jabatan.store');
        Route::put('/asrama/{asrama}/jabatan/{jabatan}', [App\Http\Controllers\Web\Super\SuperController::class, 'updateJabatan']) ->name('asrama.jabatan.update');
        Route::delete('/asrama/{asrama}/jabatan/{jabatan}',[App\Http\Controllers\Web\Super\SuperController::class, 'destroyJabatan'])->name('asrama.jabatan.destroy');

        // Komponen CRUD
        Route::post('/komponen',              [App\Http\Controllers\Web\Super\SuperController::class, 'storeKomponen'])  ->name('komponen.store');
        Route::put('/komponen/{komponen}',    [App\Http\Controllers\Web\Super\SuperController::class, 'updateKomponen']) ->name('komponen.update');
        Route::delete('/komponen/{komponen}', [App\Http\Controllers\Web\Super\SuperController::class, 'destroyKomponen'])->name('komponen.destroy');

        // Warga detail/edit
        Route::get('/warga/{id}',        [App\Http\Controllers\Web\Super\SuperController::class, 'wargaDetail']) ->name('warga.detail');
        Route::get('/warga/{id}/edit',   [App\Http\Controllers\Web\Super\SuperController::class, 'wargaEdit'])   ->name('warga.edit');
        Route::put('/warga/{id}',        [App\Http\Controllers\Web\Super\SuperController::class, 'wargaUpdate'])->name('warga.update');
    });

});
