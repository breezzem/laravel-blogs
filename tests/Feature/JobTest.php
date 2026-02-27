<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;
use App\Jobs\CheckBlogsHealth;
use App\Models\Blog;
use App\Notifications\BlogIsOffline;

/**
 * WHAT YOU NEED TO DO:
 * 
 * Create a job that checks blog health:
 * - Create CheckBlogsHealth job that accepts a User
 * - Job should check HTTP status of all blogs owned by the user
 * - If any blog returns a non-200 status, send BlogIsOffline notification to the user
 * - Use Http facade to make requests (tests will fake the responses)
 */

test('create job', function () {
    Queue::fake();

    $user = User::factory()->create();
    Blog::factory()->count(2)->create(['owner_id' => $user->id]);

    CheckBlogsHealth::dispatch($user);

    Queue::assertPushed(CheckBlogsHealth::class);
});

test('make sure that the job worked', function () {
    Notification::fake();

    $user = User::factory()->create();
    Blog::factory()->create(['owner_id' => $user->id, 'domain' => 'https://blog.example.com']);
    Blog::factory()->create(['owner_id' => $user->id, 'domain' => 'https://invalid.example.com']);

    Http::fake([
        'https://blog.example.com' => Http::response('Hello World', 200),
        'https://invalid.example.com' => Http::response('', 500),
    ]);

    CheckBlogsHealth::dispatch($user);

    Notification::assertSentTo($user, BlogIsOffline::class);
});
