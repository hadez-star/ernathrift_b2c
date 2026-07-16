<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $product;

    /**
     * Kirim notifikasi ke pelanggan yang pernah membeli dari kategori yang sama
     * ketika ada produk baru/update yang relevan dengan riwayat pembelian mereka.
     */
    public function __construct(User $user, Product $product)
    {
        $this->user = $user;
        $this->product = $product;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ada Koleksi Baru untuk Kamu di ' . ($this->product->kategori ?? 'Thrift Store') . ' 🛍️ - ERNA THRIFTING',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.product-update',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
