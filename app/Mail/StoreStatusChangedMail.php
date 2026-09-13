<?php

namespace App\Mail;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StoreStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Store $store;
    public string $status;
    public ?string $reason;
    public ?string $adminName;

    /**
     * Create a new message instance.
     */
    public function __construct(Store $store, string $status, ?string $reason = null, ?string $adminName = null)
    {
        $this->store = $store;
        $this->status = $status;
        $this->reason = $reason;
        $this->adminName = $adminName ?: 'Tim Moderasi Platform';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->status) {
            'banned' => "⚠️ [PEMBERITAHUAN PENTING] Toko Anda Diblokir Permanen - {$this->store->name}",
            'suspended' => "⚠️ [PERINGATAN] Toko Anda Ditangguhkan Sementara - {$this->store->name}",
            'active' => "✅ [INFORMASI] Status Toko Anda Telah Dipulihkan / Aktif Kembali - {$this->store->name}",
            default => "Pemberitahuan Status Toko: {$this->store->name}",
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.store_status_changed',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
