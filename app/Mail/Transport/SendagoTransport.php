<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

/**
 * Kirim email lewat API sendagomail.adilabs.id (mail engine internal),
 * bukan SMTP. Dipakai untuk verifikasi email registrasi & reset password.
 */
class SendagoTransport extends AbstractTransport
{
    public function __construct(
        private readonly string $memberId,
        private readonly string $secret,
        private readonly string $endpoint = 'https://sendagomail.adilabs.id/emails/api-send',
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $to = $email->getTo();
        if (empty($to)) {
            throw new \RuntimeException('Sendago: email tidak punya alamat tujuan (to).');
        }

        $response = Http::asJson()->timeout(15)->post($this->endpoint, [
            'memberId' => $this->memberId,
            'secret'   => $this->secret,
            'toAddr'   => $to[0]->getAddress(),
            'subject'  => (string) $email->getSubject(),
            'body'     => $email->getHtmlBody() ?? $email->getTextBody() ?? '',
        ]);

        if ($response->failed()) {
            throw new \RuntimeException('Sendago: gagal kirim email — ' . $response->status() . ' ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'sendago';
    }
}
