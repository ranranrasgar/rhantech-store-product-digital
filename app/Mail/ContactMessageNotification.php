<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $contactMessage;
    public bool $isAdminNotification;

    /**
     * Create a new message instance.
     */
    public function __construct($contactMessage, bool $isAdminNotification = false)
    {
        $this->contactMessage = $contactMessage;
        $this->isAdminNotification = $isAdminNotification;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isAdminNotification
                ? '[Pesan Baru Website] ' . ($this->contactMessage->subject ?? 'Pesan Kontak')
                : 'Terima kasih telah menghubungi kami - Rhantech',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact_notification',
            with: [
                'isAdmin' => $this->isAdminNotification,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
