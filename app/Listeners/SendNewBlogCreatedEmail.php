<?php

namespace App\Listeners;

use App\Events\BlogCreated;
use App\Mail\NewBlogCreated;
use Illuminate\Support\Facades\Mail;

class SendNewBlogCreatedEmail
{
    /**
     * Handle the event.
     */
    public function handle(BlogCreated $event): void
    {
        $event->blog->load('owner');
        
        Mail::to($event->blog->owner->email)->send(
            new NewBlogCreated($event->blog)
        );
    }
}
