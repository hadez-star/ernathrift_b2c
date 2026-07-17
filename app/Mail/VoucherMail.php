<?php

namespace App\Mail;

use App\Models\Voucher;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoucherMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $voucher;

    public function __construct(User $user, Voucher $voucher)
    {
        $this->user = $user;
        $this->voucher = $voucher;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎁 Voucher Diskon Khusus Untukmu — ERNA THRIFTING',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.voucher',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
