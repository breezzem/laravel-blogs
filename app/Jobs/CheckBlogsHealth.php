<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\BlogIsOffline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckBlogsHealth implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job .
     */
    public function __construct(
        public User $user
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $blogs = $this->user->blogs;

        foreach ($blogs as $blog) {
            try {
                $response = Http::get($blog->domain);
                
                if ($response->status() !== 200) {
                    $this->user->notify(new BlogIsOffline($blog));
                }
            } catch (\Exception $e) {
                // If request fails, consider it offline
                $this->user->notify(new BlogIsOffline($blog));
            }
        }
    }
}
