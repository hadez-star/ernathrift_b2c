<?php

namespace App\Mail;

use App\Models\FlashSale;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class FlashSaleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $flashSale;
    public $flashSaleItems;

    public function __construct(User $user, FlashSale $flashSale, Collection $flashSaleItems)
    {
        $this->user = $user;
        $this->flashSale = $flashSale;
        $this->flashSaleItems = $flashSaleItems;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚡ Flash Sale: ' . $this->flashSale->nama_kampanye . ' — ERNA THRIFTING',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.flash-sale',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
