<?php

namespace App\Mail;

use Facade\FlareClient\View;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;

class MyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private string $name)
    {
        //
    }

    /**
     * Build the message envelope.
     *
     * @return $this
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'My Email',
        );
    }
    
    public function content(): Content
    {
        return new Content(
            View: 'mail.auth.reset-password',
            with: ['name' => $this->name]
        );
    }

    public function build()
    {
        return $this->view('view.name');
    }
}
