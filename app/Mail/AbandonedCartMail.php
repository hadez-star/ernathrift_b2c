<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class AbandonedCartMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $cartItems;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Collection $cartItems)
    {
        $this->user = $user;
        $this->cartItems = $cartItems;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Barang impianmu masih menunggumu di keranjang! 🛒 - ERNA THRIFTING',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.abandoned-cart',
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
