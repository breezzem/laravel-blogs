<?php

namespace App\Notifications;

use App\Models\Blog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BlogIsOffline extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification .
     */
    public function __construct(
        public Blog $blog
    ) {
    }

    /**
     * Get the notification's delivery.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation .
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Blog Is Offline')
            ->line("Your blog '{$this->blog->name}' ({$this->blog->domain}) is currently offline.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'blog_id' => $this->blog->id,
            'blog_name' => $this->blog->name,
            'blog_domain' => $this->blog->domain,
        ];
    }
}
