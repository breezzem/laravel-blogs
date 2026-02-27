<?php

namespace App\Mail;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBlogCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message.
     */
    public function __construct(
        public Blog $blog
    ) {
    }

    /**
     * Get the message in form of envlope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Blog Created',
        );
    }

    /**
     * Get the message definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-blog-created',
        );
    }
}
