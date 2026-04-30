<?php

use App\Http\Controllers\CaptchaController;
use App\Mail\MyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function () {
    $name = "SIMONAS.ID";

    Mail::to('info.simonas.id')->send(new MyEmail($name));
});

Route::get('redirects', 'HomeController@index')->name('redirects');

Route::get('/lupa-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('lupa.password');

Route::post('/lupa-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? back()->with('message', 'sukses kirim link')
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('email.password');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('reset.password');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('redirects')->with('status', 'berhasil update password')
        : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('update.password');

Route::get('/password-success', function () {
    return view('auth.success');
})->name('auth.success');

Route::get('/', function () {
    return Inertia\Inertia::render('Welcome', [
        'appName' => config('app.name'),
        'version' => app()->version(),
    ]);
})->name('home');

Route::post('/change-password', 'UserController@changePassword')->name('user.changePassword');

Route::get('/reload-captcha', 'CaptchaController@reloadCaptcha');
Route::get('/reload-captcha-login', 'CaptchaController@reloadCaptchaLogin');

Auth::routes();

// Google OAuth (hybrid login)
Route::get('/auth/{provider}', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirect'])
    ->where('provider', 'google')
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'callback'])
    ->where('provider', 'google')
    ->name('socialite.callback');

Route::middleware(['auth', 'super'])->group(function () {
    Route::get('/super', 'SuperController@index');
    Route::get('/super-dashboard', 'SuperController@dashboard');

    Route::get('/super-pekerjaan', 'AlumniController@IndexPekerjaan')->name('pekerjaan');

    Route::get('/super-akun', 'UserController@akun')->name('super-akun');
    Route::get('/super-akun-edit/{id}', 'UserController@akunEdit')->name('super-akun-edit');
    Route::delete('/super-akun-delete/{id}', 'UserController@akunDelete')->name('super-akun-delete');
    Route::patch('/super-akun-update/{id}', 'UserController@akunUpdate')->name('super-akun-update');

    Route::get('/super-direktur-asrama', 'AsramaController@index')->name('super-direktur-asrama');
    Route::get('/super-direktur-asrama-detail/{id}', 'AsramaController@detail')->name('super-direktur-asrama-detail');
    Route::get('/super-direktur-asrama-create', 'AsramaController@create')->name('super-direktur-asrama-create');
    Route::get('/super-direktur-asrama-edit/{id}', 'AsramaController@edit')->name('super-direktur-asrama-edit');
    Route::post('/super-direktur-asrama-store', 'AsramaController@store')->name('super-direktur-asrama-store');
    Route::patch('/super-direktur-asrama-update/{id}', 'AsramaController@update')->name('super-direktur-asrama-update');
    Route::delete('/super-direktur-asrama-delete/{id}', 'AsramaController@delete')->name('super-direktur-asrama-delete');

    Route::get('/super-warga-asrama', 'WargaController@index')->name('super-warga-asrama');
    Route::get('/get-data-by-date/{userId}', 'WargaController@getDataByDateFilter')->name('getDataByDateFilter');
    Route::get('/super-warga-asgj', 'WargaController@asgj')->name('super-warga-asgj');
    Route::get('/super-warga-asg', 'WargaController@asg')->name('super-warga-asg');
    Route::get('/super-warga-aws', 'WargaController@aws')->name('super-warga-aws');
    Route::get('/super-warga-aspuri', 'WargaController@aspuri')->name('super-warga-aspuri');
    Route::get('/super-warga-asrama-detail/{id}', 'WargaController@detail')->name('super-warga-asrama-detail');

    Route::get('/super-mentor-asrama', 'WargaController@indexMentor')->name('super-mentor-asrama');
    Route::get('/super-mentor-asrama-detail/{id}', 'WargaController@detailMentor')->name('super-mentor-asrama-detail');

    Route::get('/super-eksekutif-summary', 'SuperController@eksekutifSummary')->name('super-eksekutif-summary');
    Route::get('/super-kegiatan-reporting-filterDate', 'SuperController@reportingFilterDate')->name('super-kegiatan-reporting-filterDate');
    Route::get('/super-kegiatan-reporting', 'SuperController@reporting')->name('super-kegiatan-reporting');

    Route::get('/super-kegiatan-asrama', 'KegiatanController@index')->name('super-kegiatan-asrama');
    Route::get('/super-kegiatan-asrama-detail/{id}', 'KegiatanController@detail')->name('super-kegiatan-asrama-detail');
    Route::get('/super-kegiatan-asrama-create', 'KegiatanController@create')->name('super-kegiatan-asrama-create');
    Route::get('/super-kegiatan-asrama-edit/{id}', 'KegiatanController@edit')->name('super-kegiatan-asrama-edit');
    Route::post('/super-kegiatan-asrama-store', 'KegiatanController@store')->name('super-kegiatan-asrama-store');
    Route::patch('/super-kegiatan-asrama-update/{id}', 'KegiatanController@update')->name('super-kegiatan-asrama-update');
    Route::delete('/super-kegiatan-asrama-delete/{id}', 'KegiatanController@delete')->name('super-kegiatan-asrama-delete');

    // Route::get('/super-attendance-kegiatan', 'AttendanceController@index')->name('super-attendance-kegiatan');
    // Route::get('/super-attendance-kegiatan-detail/{id}', 'AttendanceController@detail')->name('super-attendance-kegiatan-detail');
    // Route::get('/super-attendance-kegiatan-create', 'AttendanceController@create')->name('super-attendance-kegiatan-create');
    // Route::get('/super-attendance-kegiatan-edit/{id}', 'AttendanceController@edit')->name('super-attendance-kegiatan-edit');
    // Route::post('/super-attendance-kegiatan-store', 'AttendanceController@store')->name('super-attendance-kegiatan-store');
    // Route::patch('/super-attendance-kegiatan-update/{id}', 'AttendanceController@update')->name('super-attendance-kegiatan-update');
    // Route::delete('/super-attendance-kegiatan-delete/{id}', 'AttendanceController@delete')->name('super-attendance-kegiatan-delete');

    Route::get('/super-alumni-asrama', 'AlumniController@index')->name('super-alumni-asrama');
    Route::get('/super-alumni-asrama-detail/{id}', 'AlumniController@detail')->name('super-alumni-asrama-detail');
    Route::get('/super-alumni-asrama-create', 'AlumniController@create')->name('super-alumni-asrama-create');
    Route::get('/super-alumni-asrama-edit/{id}', 'AlumniController@edit')->name('super-alumni-asrama-edit');
    Route::post('/super-alumni-asrama-store', 'AlumniController@store')->name('super-alumni-asrama-store');
    Route::patch('/super-alumni-asrama-update/{id}', 'AlumniController@update')->name('super-alumni-asrama-update');
    Route::delete('/super-alumni-asrama-delete/{id}', 'AlumniController@delete')->name('super-alumni-asrama-delete');

    Route::get('/super-komponen', 'KomponenController@index')->name('super-komponen');
    Route::get('/super-komponen-create', 'KomponenController@create')->name('super-komponen-create');
    Route::get('/super-komponen-edit/{id}', 'KomponenController@edit')->name('super-komponen-edit');
    Route::post('/super-komponen-store', 'KomponenController@store')->name('super-komponen-store');
    Route::patch('/super-komponen-update/{id}', 'KomponenController@update')->name('super-komponen-update');
    Route::delete('/super-komponen-delete/{id}', 'KomponenController@delete')->name('super-komponen-delete');

    Route::get('/super-alumni-asrama-export', 'AlumniController@export')->name('super-alumni-asrama-export');
    Route::post('/super-alumni-asrama-import', 'AlumniController@import')->name('super-alumni-asrama-import');

    Route::get('/super-kegiatan-akademik', 'AkademikController@index')->name('super-kegiatan-akademik');
    Route::get('/super-kegiatan-akademik-create', 'AkademikController@create')->name('super-kegiatan-akademik-create');
    Route::get('/super-kegiatan-akademik-edit/{id}', 'AkademikController@edit')->name('super-kegiatan-akademik-edit');
    Route::get('/super-kegiatan-akademik-detail/{id}', 'AkademikController@detail')->name('super-kegiatan-akademik-detail');
    Route::post('/super-kegiatan-akademik-store', 'AkademikController@store')->name('super-kegiatan-akademik-store');
    Route::patch('/super-kegiatan-akademik-update/{id}', 'AkademikController@update')->name('super-kegiatan-akademik-update');
    Route::delete('/super-kegiatan-akademik-delete/{id}', 'AkademikController@delete')->name('super-kegiatan-akademik-delete');

    Route::get('/super-kegiatan-leadership', 'LeadershipController@index')->name('super-kegiatan-leadership');
    Route::get('/super-kegiatan-leadership-create', 'LeadershipController@create')->name('super-kegiatan-leadership-create');
    Route::get('/super-kegiatan-leadership-edit/{id}', 'LeadershipController@edit')->name('super-kegiatan-leadership-edit');
    Route::get('/super-kegiatan-leadership-detail/{id}', 'LeadershipController@detail')->name('super-kegiatan-leadership-detail');
    Route::post('/super-kegiatan-leadership-store', 'LeadershipController@store')->name('super-kegiatan-leadership-store');
    Route::patch('/super-kegiatan-leadership-update/{id}', 'LeadershipController@update')->name('super-kegiatan-leadership-update');
    Route::delete('/super-kegiatan-leadership-delete/{id}', 'LeadershipController@delete')->name('super-kegiatan-leadership-delete');

    Route::get('/super-kegiatan-karakter', 'KarakterController@index')->name('super-kegiatan-karakter');
    Route::get('/super-kegiatan-karakter-create', 'KarakterController@create')->name('super-kegiatan-karakter-create');
    Route::get('/super-kegiatan-karakter-edit/{id}', 'KarakterController@edit')->name('super-kegiatan-karakter-edit');
    Route::get('/super-kegiatan-karakter-detail/{id}', 'KarakterController@detail')->name('super-kegiatan-karakter-detail');
    Route::post('/super-kegiatan-karakter-store', 'KarakterController@store')->name('super-kegiatan-karakter-store');
    Route::patch('/super-kegiatan-karakter-update/{id}', 'KarakterController@update')->name('super-kegiatan-karakter-update');
    Route::delete('/super-kegiatan-karakter-delete/{id}', 'KarakterController@delete')->name('super-kegiatan-karakter-delete');

    Route::get('/super-kegiatan-kreatif', 'KreatifController@index')->name('super-kegiatan-kreatif');
    Route::get('/super-kegiatan-kreatif-create', 'KreatifController@create')->name('super-kegiatan-kreatif-create');
    Route::get('/super-kegiatan-kreatif-edit/{id}', 'KreatifController@edit')->name('super-kegiatan-kreatif-edit');
    Route::get('/super-kegiatan-kreatif-detail/{id}', 'KreatifController@detail')->name('super-kegiatan-kreatif-detail');
    Route::post('/super-kegiatan-kreatif-store', 'KreatifController@store')->name('super-kegiatan-kreatif-store');
    Route::patch('/super-kegiatan-kreatif-update/{id}', 'KreatifController@update')->name('super-kegiatan-kreatif-update');
    Route::delete('/super-kegiatan-kreatif-delete/{id}', 'KreatifController@delete')->name('super-kegiatan-kreatif-delete');

    Route::get('/super-profile', 'ProfileController@index')->name('super-profile');
    Route::get('/super-profile-foto-edit/{id}', 'ProfileController@editFoto')->name('super-profile-foto-edit');
    Route::patch('/super-profile-foto-update/{id}', 'ProfileController@updateFoto')->name('super-profile-foto-update');
    Route::get('/super-profile-foto-ips/{id}', 'ProfileController@editIps')->name('super-profile-ips-edit');
    Route::patch('/super-profile-ips-update/{id}', 'ProfileController@updateIps')->name('super-profile-ips-update');
    Route::get('/super-profile-foto-info/{id}', 'ProfileController@editInfo')->name('super-profile-info-edit');
    Route::patch('/super-profile-info-update/{id}', 'ProfileController@updateInfo')->name('super-profile-info-update');
    Route::get('/super-profile-status-edit/{id}', 'ProfileController@editStatus')->name('super-profile-status-edit');
    Route::patch('/super-profile-status-update/{id}', 'ProfileController@updateStatus')->name('super-profile-status-update');

    Route::get('/super-profile-akademik-create', 'ProfileController@akademikCreate')->name('super-profile-akademik-create');
    Route::post('/super-profile-akademik-store', 'ProfileController@akademikStore')->name('super-profile-akademik-store');
    Route::get('/super-profile-leadership-create', 'ProfileController@leadershipCreate')->name('super-profile-leadership-create');
    Route::post('/super-profile-leadership-store', 'ProfileController@leadershipStore')->name('super-profile-leadership-store');
    Route::get('/super-profile-karakter-create', 'ProfileController@karakterCreate')->name('super-profile-karakter-create');
    Route::post('/super-profile-karakter-store', 'ProfileController@karakterStore')->name('super-profile-karakter-store');
    Route::get('/super-profile-kreatif-create', 'ProfileController@kreatifCreate')->name('super-profile-kreatif-create');
    Route::post('/super-profile-kreatif-store', 'ProfileController@kreatifStore')->name('super-profile-kreatif-store');

    Route::get('/super-ipk', 'IpkController@index')->name('super-ipk');
    Route::get('/super-ipk-create', 'IpkController@create')->name('super-ipk-create');
    Route::get('/super-ipk-edit/{id}', 'IpkController@edit')->name('super-ipk-edit');
    Route::get('/super-ipk-khs-edit/{id}', 'IpkController@editKhs')->name('super-ipk-khs-edit');
    Route::get('/super-ipk-detail/{id}', 'IpkController@detail')->name('super-ipk-detail');
    Route::post('/super-ipk-store', 'IpkController@store')->name('super-ipk-store');
    Route::patch('/super-ipk-update/{id}', 'IpkController@update')->name('super-ipk-update');
    Route::patch('/super-ipk-khs-update/{id}', 'IpkController@updateKhs')->name('super-ipk-khs-update');
    Route::delete('/super-ipk-delete/{id}', 'IpkController@delete')->name('super-ipk-delete');

    Route::get('/super-form-create', 'FormController@create');
    Route::get('/super-form', 'FormController@index');
    Route::get('/super-form-view', 'FormController@view');
    Route::post('/super-form-store', 'FormController@store');
    Route::delete('/super-form-delete/{id}', 'FormController@delete');

    //fullcalender
    Route::get('/fullcalendar', 'FullCalendarController@index');
    Route::get('/eventsSuper1', 'EventController@listEventSuper1')->name('event.listsuper1');
    Route::post('/fullcalendar/action', 'FullCalendarController@action');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', 'AdminController@index')->name('admin');
    Route::get('/admin-dashboard', 'AdminController@dashboard')->name('admin-dashboard');
    Route::get('/admin-kegiatan', 'AdminController@viewKegiatan')->name('admin-kegiatan');
    Route::get('/admin-kegiatan-create', 'AdminController@createKegiatan')->name('admin-kegiatan-create');
    Route::get('/admin-kegiatan-edit/{id}', 'AdminController@editKegiatan')->name('admin-kegiatan-edit');
    Route::post('/admin-kegiatan-store', 'AdminController@storeKegiatan')->name('admin-kegiatan-store');
    Route::patch('/admin-kegiatan-update/{id}', 'AdminController@updateKegiatan')->name('admin-kegiatan-update');
    Route::delete('/admin-kegiatan-delete/{id}', 'AdminController@deleteKegiatan')->name('admin-kegiatan-delete');

    Route::get('/admin-asrama', 'AdminController@viewAsrama')->name('admin-asrama');
    Route::get('/admin-asrama-create', 'AdminController@createAsrama')->name('admin-asrama-create');
    Route::get('/admin-asrama-edit/{id}', 'AdminController@editAsrama')->name('admin-asrama-edit');
    Route::post('/admin-asrama-store', 'AdminController@storeAsrama')->name('admin-asrama-store');
    Route::patch('/admin-asrama-update/{id}', 'AdminController@updateAsrama')->name('admin-asrama-update');
    Route::delete('/admin-asrama-delete/{id}', 'AdminController@deleteAsrama')->name('admin-asrama-delete');

    Route::get('/admin-alumni', 'AdminController@viewAlumni')->name('admin-alumni');
    Route::get('/admin-alumni-create', 'AdminController@createAlumni')->name('admin-alumni-create');
    Route::get('/admin-alumni-edit/{id}', 'AdminController@editAlumni')->name('admin-alumni-edit');
    Route::get('/admin-alumni-detail/{id}', 'AdminController@detailAlumni')->name('admin-alumni-detail');
    Route::post('/admin-alumni-store', 'AdminController@storeAlumni')->name('admin-alumni-store');
    Route::patch('/admin-alumni-update/{id}', 'AdminController@updateAlumni')->name('admin-alumni-update');
    Route::delete('/admin-alumni-delete/{id}', 'AdminController@deleteAlumni')->name('admin-alumni-delete');

    Route::get('/admin-warga', 'AdminController@viewWarga')->name('admin-warga');
    Route::get('/admin-warga-detail/{id}', 'AdminController@detailWarga')->name('admin-warga-detail');

    Route::get('/admin-profile', 'AdminController@viewAdminProfile')->name('admin-profile');
    Route::get('/admin-profile-foto', 'AdminController@profile')->name('admin-profile-foto');
    Route::patch('/admin-profile-foto/{id}', 'AdminController@update_avatar')->name('admin-profile-foto');
    Route::get('/admin-profile-edit/{id}', 'AdminController@editAdminProfile')->name('admin-profile-edit');
    Route::patch('/admin-profile-update/{id}', 'AdminController@updateAdminProfile')->name('admin-profile-update');

    Route::get('/admin-akademik', 'AdminController@indexAkademik')->name('admin-akademik');
    Route::get('/admin-akademik-create', 'AdminController@createAkademik')->name('admin-akademik-create');
    Route::get('/admin-akademik-detail/{id}', 'AdminController@detailAkademik')->name('admin-akademik-detail');
    Route::post('/admin-akademik-store', 'AdminController@storeAkademik')->name('admin-akademik-store');
    Route::patch('/admin-akademik-update/{id}', 'AdminController@updateAkademik')->name('admin-akademik-update');
    Route::delete('/admin-akademik-delete/{id}', 'AdminController@deleteAkademik')->name('admin-akademik-delete');

    Route::get('/admin-leadership', 'AdminController@indexLeadership')->name('admin-leadership');
    Route::get('/admin-leadership-create', 'AdminController@createLeadership')->name('admin-leadership-create');
    Route::get('/admin-leadership-detail/{id}', 'AdminController@detailLeadership')->name('admin-leadership-detail');
    Route::get('/admin-leadership-edit/{id}', 'AdminController@editLeadership')->name('admin-leadership-edit');
    Route::post('/admin-leadership-store', 'AdminController@storeLeadership')->name('admin-leadership-store');
    Route::patch('/admin-leadership-update/{id}', 'AdminController@updateLeadership')->name('admin-leadership-update');
    Route::delete('/admin-leadership-delete/{id}', 'AdminController@deleteLeadership')->name('admin-leadership-delete');

    Route::get('/admin-karakter', 'AdminController@indexKarakter')->name('admin-karakter');
    Route::get('/admin-karakter-create', 'AdminController@createKarakter')->name('admin-karakter-create');
    Route::get('/admin-karakter-detail/{id}', 'AdminController@detailKarakter')->name('admin-karakter-detail');
    Route::get('/admin-karakter-edit/{id}', 'AdminController@editKarakter')->name('admin-karakter-edit');
    Route::post('/admin-karakter-store', 'AdminController@storeKarakter')->name('admin-karakter-store');
    Route::patch('/admin-karakter-update/{id}', 'AdminController@updateKarakter')->name('admin-karakter-update');
    Route::delete('/admin-karakter-delete/{id}', 'AdminController@deleteKarakter')->name('admin-karakter-delete');

    Route::get('/admin-kreatif', 'AdminController@indexKreatif')->name('admin-kreatif');
    Route::get('/admin-kreatif-create', 'AdminController@createKreatif')->name('admin-kreatif-create');
    Route::get('/admin-kreatif-detail/{id}', 'AdminController@detailKreatif')->name('admin-kreatif-detail');
    Route::get('/admin-kreatif-edit/{id}', 'AdminController@editKreatif')->name('admin-kreatif-edit');
    Route::post('/admin-kreatif-store', 'AdminController@storeKreatif')->name('admin-kreatif-store');
    Route::patch('/admin-kreatif-update/{id}', 'AdminController@updateKreatif')->name('admin-kreatif-update');
    Route::delete('/admin-kreatif-delete/{id}', 'AdminController@deleteKreatif')->name('admin-kreatif-delete');

    Route::get('/admin-penilaian-akademik', 'AdminController@viewAkademikPenilaian')->name('admin-penilaian-akademik');
    Route::get('/admin-penilaian-akademik-edit/{id}', 'AdminController@editAkademikPenilaian')->name('admin-penilaian-akademik-edit');
    Route::get('/admin-penilaian-akademik-detail/{id}', 'AdminController@detailAkademikPenilaian')->name('admin-penilaian-akademik-detail');
    Route::patch('/admin-penilaian-akademik-update/{id}', 'AdminController@updateAkademikPenilaian')->name('admin-penilaian-akademik-update');

    Route::get('/admin-penilaian-leadership', 'AdminController@viewLeadershipPenilaian')->name('admin-penilaian-leadership');
    Route::get('/admin-penilaian-leadership-edit/{id}', 'AdminController@editLeadershipPenilaian')->name('admin-penilaian-leadership-edit');
    Route::get('/admin-penilaian-leadership-detail/{id}', 'AdminController@detailLeadershipPenilaian')->name('admin-penilaian-leadership-detail');
    Route::patch('/admin-penilaian-leadership-update/{id}', 'AdminController@updateLeadershipPenilaian')->name('admin-penilaian-leadership-update');

    Route::get('/admin-penilaian-karakter', 'AdminController@viewKarakterPenilaian')->name('admin-penilaian-karakter');
    Route::get('/admin-penilaian-karakter-edit/{id}', 'AdminController@editKarakterPenilaian')->name('admin-penilaian-karakter-edit');
    Route::get('/admin-penilaian-karakter-detail/{id}', 'AdminController@detailKarakterPenilaian')->name('admin-penilaian-karakter-detail');
    Route::patch('/admin-penilaian-karakter-update/{id}', 'AdminController@updateKarakterPenilaian')->name('admin-penilaian-karakter-update');

    Route::get('/admin-penilaian-kreatif', 'AdminController@viewKreatifPenilaian')->name('admin-penilaian-kreatif');
    Route::get('/admin-penilaian-kreatif-edit/{id}', 'AdminController@editKreatifPenilaian')->name('admin-penilaian-kreatif-edit');
    Route::get('/admin-penilaian-kreatif-detail/{id}', 'AdminController@detailKreatifPenilaian')->name('admin-penilaian-kreatif-detail');
    Route::patch('/admin-penilaian-kreatif-update/{id}', 'AdminController@updateKreatifPenilaian')->name('admin-penilaian-kreatif-update');

    Route::get('/admin-history', 'AdminController@indexHistory')->name('admin-history');
    Route::get('/admin-history-akademik', 'AdminController@viewAkademikHistory')->name('admin-history-akademik');
    Route::get('/admin-history-leadership', 'AdminController@viewLeadershipHistory')->name('admin-history-leadership');
    Route::get('/admin-history-karakter', 'AdminController@viewKarakterHistory')->name('admin-history-karakter');
    Route::get('/admin-history-kreatif', 'AdminController@viewKreatifHistory')->name('admin-history-kreatif');

    Route::get('/admin-kegiatan', 'AdminController@viewKegiatan')->name('admin-kegiatan');
    Route::get('/admin-kegiatan-create', 'AdminController@createKegiatan')->name('admin-kegiatan-create');
    Route::get('/admin-kegiatan-detail/{id}', 'AdminController@detailKegiatan')->name('admin-kegiatan-detail');
    Route::get('/admin-kegiatan-edit/{id}', 'AdminController@editKegiatan')->name('admin-kegiatan-edit');
    Route::post('/admin-kegiatan-store', 'AdminController@storeKegiatan')->name('admin-kegiatan-store');
    Route::patch('/admin-kegiatan-update/{id}', 'AdminController@updateKegiatan')->name('admin-kegiatan-update');
    Route::delete('/admin-kegiatan-delete/{id}', 'AdminController@deleteKegiatan')->name('admin-kegiatan-delete');
});

Route::middleware(['auth', 'mentor'])->group(function () {
    Route::get('/mentor', 'MentorController@index');
    Route::get('/mentor-dashboard', 'MentorController@dashboard');
    Route::get('/mentor-kegiatan', 'MentorController@viewKegiatan');
    Route::get('/mentor-kegiatan-asgj', 'MentorController@kegiatanAsgj');
    Route::get('/mentor-kegiatan-asg', 'MentorController@kegiatanAsg');
    Route::get('/mentor-kegiatan-aws', 'MentorController@kegiatanAws');
    Route::get('/mentor-kegiatan-aspuri', 'MentorController@kegiatanaspuri');

    Route::get('/mentor-asrama', 'MentorController@viewAsrama');
    Route::get('/mentor-asrama-asgj', 'MentorController@asgj');
    Route::get('/mentor-asrama-asg', 'MentorController@asg');
    Route::get('/mentor-asrama-aws', 'MentorController@aws');
    Route::get('/mentor-asrama-aspuri', 'MentorController@aspuri');

    Route::get('/mentor-warga', 'MentorController@viewWarga');
    Route::get('/mentor-warga-asgj', 'MentorController@wargaAsgj');
    Route::get('/mentor-warga-asg', 'MentorController@wargaAsg');
    Route::get('/mentor-warga-aws', 'MentorController@wargaAws');
    Route::get('/mentor-warga-aspuri', 'MentorController@wargaaspuri');
    Route::get('/mentor-warga-detail/{id}', 'MentorController@detailWarga');

    Route::get('/mentor-alumni', 'MentorController@viewAlumni');
    Route::get('/mentor-alumni-detail/{id}', 'MentorController@detailAlumni');

    Route::get('/mentor-akademik', 'MentorController@indexAkademik');
    Route::get('/mentor-akademik-create', 'MentorController@createAkademik');
    Route::get('/mentor-akademik-detail/{id}', 'MentorController@detailAkademik');
    Route::post('/mentor-akademik-store', 'MentorController@storeAkademik');
    Route::patch('/mentor-akademik-update/{id}', 'MentorController@updateAkademik');
    Route::delete('/mentor-akademik-delete/{id}', 'MentorController@deleteAkademik');

    Route::get('/mentor-leadership', 'MentorController@indexLeadership');
    Route::get('/mentor-leadership-create', 'MentorController@createLeadership');
    Route::get('/mentor-leadership-detail/{id}', 'MentorController@detailLeadership');
    Route::get('/mentor-leadership-edit/{id}', 'MentorController@editLeadership');
    Route::post('/mentor-leadership-store', 'MentorController@storeLeadership');
    Route::patch('/mentor-leadership-update/{id}', 'MentorController@updateLeadership');
    Route::delete('/mentor-leadership-delete/{id}', 'MentorController@deleteLeadership');

    Route::get('/mentor-karakter', 'MentorController@indexKarakter');
    Route::get('/mentor-karakter-create', 'MentorController@createKarakter');
    Route::get('/mentor-karakter-detail/{id}', 'MentorController@detailKarakter');
    Route::get('/mentor-karakter-edit/{id}', 'MentorController@editKarakter');
    Route::post('/mentor-karakter-store', 'MentorController@storeKarakter');
    Route::patch('/mentor-karakter-update/{id}', 'MentorController@updateKarakter');
    Route::delete('/mentor-karakter-delete/{id}', 'MentorController@deleteKarakter');

    Route::get('/mentor-kreatif', 'MentorController@indexKreatif');
    Route::get('/mentor-kreatif-create', 'MentorController@createKreatif');
    Route::get('/mentor-kreatif-detail/{id}', 'MentorController@detailKreatif');
    Route::get('/mentor-kreatif-edit/{id}', 'MentorController@editKreatif');
    Route::post('/mentor-kreatif-store', 'MentorController@storeKreatif');
    Route::patch('/mentor-kreatif-update/{id}', 'MentorController@updateKreatif');
    Route::delete('/mentor-kreatif-delete/{id}', 'MentorController@deleteKreatif');


    Route::get('/mentor-penilaian-akademik', 'MentorController@viewAkademikPenilaian');
    Route::get('/mentor-penilaian-akademik-asgj', 'MentorController@akademikAsgj');
    Route::get('/mentor-penilaian-akademik-asg', 'MentorController@akademikAsg');
    Route::get('/mentor-penilaian-akademik-aws', 'MentorController@akademikAws');
    Route::get('/mentor-penilaian-akademik-aspuri', 'MentorController@akademikaspuri');
    Route::get('/mentor-penilaian-akademik-detail/{id}', 'MentorController@detailNilaiAkademik');
    Route::get('/mentor-penilaian-akademik-edit/{id}', 'MentorController@editAkademikPenilaian');
    Route::patch('/mentor-penilaian-akademik-update/{id}', 'MentorController@updateAkademikPenilaian');

    Route::get('/mentor-penilaian-leadership', 'MentorController@viewLeadershipPenilaian');
    Route::get('/mentor-penilaian-leadership-asgj', 'MentorController@leadershipAsgj');
    Route::get('/mentor-penilaian-leadership-asg', 'MentorController@leadershipAsg');
    Route::get('/mentor-penilaian-leadership-aws', 'MentorController@leadershipAws');
    Route::get('/mentor-penilaian-leadership-aspuri', 'MentorController@leadershipaspuri');
    Route::get('/mentor-penilaian-leadership-detail/{id}', 'MentorController@detailNilaiLeadership');
    Route::get('/mentor-penilaian-leadership-edit/{id}', 'MentorController@editLeadershipPenilaian');
    Route::patch('/mentor-penilaian-leadership-update/{id}', 'MentorController@updateLeadershipPenilaian');

    Route::get('/mentor-penilaian-karakter', 'MentorController@viewKarakterPenilaian');
    Route::get('/mentor-penilaian-karakter-asgj', 'MentorController@karakterAsgj');
    Route::get('/mentor-penilaian-karakter-asg', 'MentorController@karakterAsg');
    Route::get('/mentor-penilaian-karakter-aws', 'MentorController@karakterAws');
    Route::get('/mentor-penilaian-karakter-aws', 'MentorController@karakteraspuri');
    Route::get('/mentor-penilaian-karakter-detail/{id}', 'MentorController@detailNilaiKarakter');
    Route::get('/mentor-penilaian-karakter-edit/{id}', 'MentorController@editKarakterPenilaian');
    Route::patch('/mentor-penilaian-karakter/update/{id}', 'MentorController@updateKarakterPenilaian');

    Route::get('/mentor-penilaian-kreatif', 'MentorController@viewKreatifPenilaian');
    Route::get('/mentor-penilaian-kreatif-asgj', 'MentorController@kreatifAsgj');
    Route::get('/mentor-penilaian-kreatif-asg', 'MentorController@kreatifAsg');
    Route::get('/mentor-penilaian-kreatif-aws', 'MentorController@kreatifAws');
    Route::get('/mentor-penilaian-kreatif-aspuri', 'MentorController@kreatifaspuri');
    Route::get('/mentor-penilaian-kreatif-detail/{id}', 'MentorController@detailNilaiKreatif');
    Route::get('/mentor-penilaian-kreatif-edit/{id}', 'MentorController@editKreatifPenilaian');
    Route::patch('/mentor-penilaian-kreatif-update/{id}', 'MentorController@updateKreatifPenilaian');

    Route::get('/mentor-history', 'MentorController@indexHistory');
    Route::get('/mentor-history-akademik', 'MentorController@viewAkademikHistory');
    Route::get('/mentor-history-leadership', 'MentorController@viewLeadershipHistory');
    Route::get('/mentor-history-karakter', 'MentorController@viewKarakterHistory');
    Route::get('/mentor-history-kreatif', 'MentorController@viewKreatifHistory');

    Route::get('/mentor-profile', 'MentorController@viewMentorProfile');
    Route::get('/mentor-profile-create', 'MentorController@createMentorProfile');
    Route::get('/mentor-profile-foto', 'MentorController@profile');
    Route::patch('/mentor-profile-foto/{id}', 'MentorController@update_avatar');
    Route::get('/mentor-profile-edit/{id}', 'MentorController@editMentorProfile');
    Route::patch('/mentor-profile-update/{id}', 'MentorController@updateMentorProfile');
});

Route::middleware(['auth', 'mahasiswa'])->group(function () {
    Route::get('/mahasiswa-biodata', 'MahasiswaController@biodata')->name('mahasiswa-biodata');
    Route::get('/mahasiswa', 'MahasiswaController@index')->name('mahasiswa');
    Route::get('/mahasiswa-new', 'MahasiswaController@indexNew')->name('mahasiswa-new');

    Route::get('/mahasiswa-data-profile', 'MahasiswaController@dataProfile')->name('mahasiswa-data-profile');
    Route::get('/mahasiswa-data-foto-edit/{id}', 'MahasiswaController@editFoto')->name('mahasiswa-data-foto-edit');
    Route::patch('/mahasiswa-data-foto-update/{id}', 'MahasiswaController@updateFoto')->name('mahasiswa-data-foto-update');
    Route::get('/mahasiswa-data-foto-ips/{id}', 'MahasiswaController@editIps')->name('mahasiswa-data-ips-edit');
    Route::patch('/mahasiswa-data-ips-update/{id}', 'MahasiswaController@updateIps')->name('mahasiswa-data-ips-update');
    Route::get('/mahasiswa-data-foto-info/{id}', 'MahasiswaController@editInfo')->name('mahasiswa-data-info-edit');
    Route::patch('/mahasiswa-data-info-update/{id}', 'MahasiswaController@updateInfo')->name('mahasiswa-data-info-update');
    Route::get('/mahasiswa-data-status-edit/{id}', 'MahasiswaController@editStatus')->name('mahasiswa-data-status-edit');
    Route::patch('/mahasiswa-data-nama-update/{id}', 'MahasiswaController@updateNama')->name('mahasiswa-data-nama-update');
    Route::patch('/mahasiswa-data-status-update/{id}', 'MahasiswaController@updateStatus')->name('mahasiswa-data-status-update');

    Route::get('/mahasiswa-filterDate', 'MahasiswaController@filterDate')->name('mahasiswa-filterDate');
    Route::get('/mahasiswa-attendance/{id}', 'MahasiswaController@attendance')->name('mahasiswa-attendance');
    Route::post('/mahasiswa-attendance-store/{id}', 'MahasiswaController@attendanceStore')->name('mahasiswa-attendance-store');

    Route::get('/mahasiswa-data-akademik-create', 'MahasiswaController@akademikCreate')->name('mahasiswa-data-akademik-create');
    Route::post('/mahasiswa-data-akademik-store', 'MahasiswaController@akademikStore')->name('mahasiswa-data-akademik-store');
    Route::get('/mahasiswa-data-leadership-create', 'MahasiswaController@leadershipCreate')->name('mahasiswa-data-leadership-create');
    Route::post('/mahasiswa-data-leadership-store', 'MahasiswaController@leadershipStore')->name('mahasiswa-data-leadership-store');
    Route::get('/mahasiswa-data-karakter-create', 'MahasiswaController@karakterCreate')->name('mahasiswa-data-karakter-create');
    Route::post('/mahasiswa-data-karakter-store', 'MahasiswaController@karakterStore')->name('mahasiswa-data-karakter-store');
    Route::get('/mahasiswa-data-kreatif-create', 'MahasiswaController@kreatifCreate')->name('mahasiswa-data-kreatif-create');
    Route::post('/mahasiswa-data-kreatif-store', 'MahasiswaController@kreatifStore')->name('mahasiswa-data-kreatif-store');

    Route::get('/mahasiswa-data-ipk', 'MahasiswaController@dataIpk')->name('mahasiswa-data-ipk');
    Route::get('/mahasiswa-ipk-create', 'MahasiswaController@createIPK')->name('mahasiswa-ipk-create');
    Route::get('/mahasiswa-ipk-edit/{id}', 'MahasiswaController@editIPK')->name('mahasiswa-ipk-edit');
    Route::get('/mahasiswa-ipk-khs-edit/{id}', 'MahasiswaController@editKhs')->name('mahasiswa-ipk-khs-edit');
    Route::get('/mahasiswa-ipk-detail/{id}', 'MahasiswaController@detailIPK')->name('mahasiswa-ipk-detail');
    Route::post('/mahasiswa-ipk-store', 'MahasiswaController@storeIPK')->name('mahasiswa-ipk-store');
    Route::patch('/mahasiswa-ipk-update/{id}', 'MahasiswaController@updateIPK')->name('mahasiswa-ipk-update');
    Route::patch('/mahasiswa-ipk-khs-update/{id}', 'MahasiswaController@updateKhs')->name('mahasiswa-ipk-khs-update');
    Route::delete('/mahasiswa-ipk-delete/{id}', 'MahasiswaController@deleteIPK')->name('mahasiswa-ipk-delete');

    Route::get('/mahasiswa-kegiatan-asrama', 'MahasiswaController@indexKegiatan')->name('mahasiswa-kegiatan-asrama');
    Route::get('/mahasiswa-kegiatan-asrama-detail/{id}', 'MahasiswaController@detailKegiatan')->name('mahasiswa-kegiatan-asrama-detail');
    Route::get('/mahasiswa-kegiatan-asrama-create', 'MahasiswaController@createKegiatan')->name('mahasiswa-kegiatan-asrama-create');
    Route::get('/mahasiswa-kegiatan-asrama-edit/{id}', 'MahasiswaController@editKegiatan')->name('mahasiswa-kegiatan-asrama-edit');
    Route::post('/mahasiswa-kegiatan-asrama-store', 'MahasiswaController@storeKegiatan')->name('mahasiswa-kegiatan-asrama-store');
    Route::patch('/mahasiswa-kegiatan-asrama-update/{id}', 'MahasiswaController@updateKegiatan')->name('mahasiswa-kegiatan-asrama-update');
    Route::delete('/mahasiswa-kegiatan-asrama-delete/{id}', 'MahasiswaController@deleteKegiatan')->name('mahasiswa-kegiatan-asrama-delete');

    Route::get('/listEventSuperMahasiswa', 'EventController@listEventSuperMahasiswa')->name('event.mahasiswa');

    Route::get('/mahasiswa-warga-asgj', 'MahasiswaController@asgj')->name('mahasiswa-warga-asgj');
    Route::get('/mahasiswa-warga-asg', 'MahasiswaController@asg')->name('mahasiswa-warga-asg');
    Route::get('/mahasiswa-warga-aws', 'MahasiswaController@aws')->name('mahasiswa-warga-aws');
    Route::get('/mahasiswa-warga-aspuri', 'MahasiswaController@aspuri')->name('mahasiswa-warga-aspuri');
    Route::get('/mahasiswa-warga-asrama-detail/{id}', 'MahasiswaController@detailWarga')->name('mahasiswa-warga-asrama-detail');
});

Route::middleware(['auth', 'alumni'])->group(function () {
    Route::get('/alumni-kegiatan', 'AlumniController@viewKegiatan');
    Route::get('/alumni-kegiatan-asgj', 'AlumniController@kegiatanAsgj');
    Route::get('/alumni-kegiatan-asg', 'AlumniController@kegiatanAsg');
    Route::get('/alumni-kegiatan-aws', 'AlumniController@kegiatanAws');
    Route::get('/alumni-kegiatan-aspuri', 'AlumniController@kegiatanAspuri');
    Route::get('/alumni-kegiatan-asrama', 'AlumniController@kegiatanAsrama');
    Route::get('/alumni-kegiatan-yapi', 'AlumniController@kegiatanYapi');
    Route::get('/alumni-asrama', 'AlumniController@viewAsrama');
    Route::get('/alumni-asrama-asgj', 'AlumniController@asgj');
    Route::get('/alumni-asrama-asg', 'AlumniController@asg');
    Route::get('/alumni-asrama-aws', 'AlumniController@aws');
    Route::get('/alumni-asrama-aspuri', 'AlumniController@aspuri');
    Route::get('/alumni-warga', 'AlumniController@viewWarga');
    Route::get('/alumni-warga-asgj', 'AlumniController@wargaAsgj');
    Route::get('/alumni-warga-asg', 'AlumniController@wargaAsg');
    Route::get('/alumni-warga-aws', 'AlumniController@wargaAws');
    Route::get('/alumni-warga-aspuri', 'AlumniController@wargaAspuri');
    Route::get('/alumni-warga-detail/{id}', 'AlumniController@detailWarga');
    Route::get('/alumni-index', 'AlumniController@viewAlumni');
    Route::get('/alumni-kegiatan-detail/{id}', 'AlumniController@detailKegiatan');
    Route::patch('/alumni-kegiatan-update/{id}', 'KegiatanController@update');
    Route::get('/alumni', 'AlumniController@index');

    Route::get('/alumni-profile-foto', 'AlumniController@profile');
    Route::patch('/alumni-profile-foto/{id}', 'AlumniController@update_avatar');
    Route::get('/alumni-profile-data', 'AlumniController@viewAlumniProfile');
    Route::get('/alumni-profile-data-edit/{id}', 'AlumniController@editAlumniProfile');
    Route::patch('/alumni-profile-data-update/{id}', 'AlumniController@updateAlumniProfile');

    Route::get('/alumni', 'AlumniController@indexAlumni');
    Route::get('/alumni-data', 'AlumniController@viewAlumni');
    Route::get('/alumni-create', 'AlumniController@create');
    Route::get('/alumni-edit/{id}', 'AlumniController@edit');
    Route::get('/alumni-detail/{id}', 'AlumniController@detail');
    Route::get('/alumni-detail/{id}', 'AlumniController@alumniDetail');
    Route::post('/alumni-store', 'AlumniController@store');
    Route::patch('/alumni-update/{id}', 'AlumniController@update');
    Route::delete('/alumni-delete/{id}', 'AlumniController@delete');

    Route::post('/events/store', 'EventController@store1')->name('events.store');
    Route::get('/events', 'EventController@index')->name('events');
    Route::get('/eventsSuper', 'EventController@listEventSuper')->name('event.listsuper');
    Route::get('/events/list', 'EventController@listEvent')->name('events.list');
    Route::get('/events/create', 'EventController@create')->name('events.create');

    Route::get('/events/{event}/edit', 'EventController@edit')->name('events.edit');
    Route::put('/events1/{event}', 'EventController@update1')->name('events.update');
    Route::delete('/events/{event}', 'EventController@destroy')->name('events.delete');
});

// web.php atau file rute Anda

Route::middleware(['auth'])->group(function () {
    Route::get('/eventCalender', 'EventController@indexUNI')->name('eventCalender');
    Route::get('get-districts', 'AlumniController@getDistricts');
});

// Route::get('logout', 'QovexController@logout');

// Route::get('pages-login', 'QovexController@index');
// Route::get('pages-login-2', 'QovexController@index');
// Route::get('pages-register', 'QovexController@index');
// Route::get('pages-register-2', 'QovexController@index');
// Route::get('pages-recoverpw', 'QovexController@index');
// Route::get('pages-recoverpw-2', 'QovexController@index');
// Route::get('pages-lock-screen', 'QovexController@index');
// Route::get('pages-lock-screen-2', 'QovexController@index');
// Route::get('pages-404', 'QovexController@index');
// Route::get('pages-500', 'QovexController@index');
// Route::get('pages-maintenance', 'QovexController@index');
// Route::get('pages-comingsoon', 'QovexController@index');
// Route::post('login-status', 'QovexController@checkStatus');


// You can also use auth middleware to prevent unauthenticated users
// Route::group(['middleware' => 'auth'], function () {
//     Route::get('/home', 'HomeController@index')->name('home');
//     Route::get('{any}', 'QovexController@index');
// });

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');
