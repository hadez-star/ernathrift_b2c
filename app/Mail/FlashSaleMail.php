<?php

namespace App\Mail;

use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlashSaleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $flashSale;
    public $flashSaleItem;

    public function __construct(User $user, FlashSale $flashSale, FlashSaleItem $flashSaleItem)
    {
        $this->user = $user;
        $this->flashSale = $flashSale;
        $this->flashSaleItem = $flashSaleItem;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚡ Flash Sale: ' . ($this->flashSaleItem->product->nama_produk ?? 'Produk Baru') . ' — ERNA THRIFTING',
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
