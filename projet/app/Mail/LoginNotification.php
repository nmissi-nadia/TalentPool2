<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $ip;
    public $time;

    /**
     * Create a new message instance.
     */
    public function __construct($user,$ip)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->time = now();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Login Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function build()
    {
        return $this->subject('Notification de connexion')
                    ->markdown('emails.login-notification')
                    ->with([
                        'user' => $this->user,
                        'ip' => $this->ip,
                        'time' => $this->time->format('d/m/Y H:i:s')
                    ]);
    }
}
