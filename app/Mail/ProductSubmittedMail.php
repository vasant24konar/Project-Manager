<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Product $product,
        public readonly User $submittedBy
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Product Pending Approval — ' . $this->product->title);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.product-submitted');
    }
}
