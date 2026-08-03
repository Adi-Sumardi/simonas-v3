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
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Terima kasih sudah mendaftar di SIMONAS - Digital Asrama YAPI.')
                ->line('Klik tombol di bawah ini untuk memverifikasi alamat email kamu.')
                ->action('Verifikasi Email', $url)
                ->line('Jika kamu tidak merasa membuat akun ini, abaikan email ini.')
                ->salutation('Salam, Tim IT YAPI');
        });

        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset Password - SIMONAS')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Kami menerima permintaan untuk mereset password akun SIMONAS kamu.')
                ->action('Reset Password', $url)
                ->line('Link reset password ini akan kedaluwarsa dalam 60 menit.')
                ->line('Jika kamu tidak meminta reset password, abaikan email ini, password kamu tidak akan berubah.')
                ->salutation('Salam, Tim IT YAPI');
        });
    }
}
