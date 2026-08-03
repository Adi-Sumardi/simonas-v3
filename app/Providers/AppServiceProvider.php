<?php

namespace App\Providers;

use App\Mail\Transport\SendagoTransport;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Mail::extend('sendago', function (array $config) {
            return new SendagoTransport($config['member_id'], $config['secret']);
        });

        VerifyEmail::toMailUsing(function ($notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email Kamu - SIMONAS')
                ->view('emails.action', [
                    'subject' => 'Verifikasi Alamat Email Kamu - SIMONAS',
                    'greeting' => 'Halo, ' . $notifiable->name . '!',
                    'introLines' => [
                        'Terima kasih sudah mendaftar di SIMONAS - Digital Asrama YAPI.',
                        'Klik tombol di bawah ini untuk memverifikasi alamat email kamu.',
                    ],
                    'actionText' => 'Verifikasi Email',
                    'actionUrl' => $url,
                    'outroLines' => [
                        'Jika kamu tidak merasa membuat akun ini, abaikan email ini.',
                    ],
                ]);
        });

        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset Password - SIMONAS')
                ->view('emails.action', [
                    'subject' => 'Reset Password - SIMONAS',
                    'greeting' => 'Halo, ' . $notifiable->name . '!',
                    'introLines' => [
                        'Kami menerima permintaan untuk mereset password akun SIMONAS kamu.',
                    ],
                    'actionText' => 'Reset Password',
                    'actionUrl' => $url,
                    'outroLines' => [
                        'Link reset password ini akan kedaluwarsa dalam 60 menit.',
                        'Jika kamu tidak meminta reset password, abaikan email ini, password kamu tidak akan berubah.',
                    ],
                ]);
        });
    }
}
