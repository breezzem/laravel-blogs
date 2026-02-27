<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Validation\ValidationException;

/**
 * WHAT YOU NEED TO DO:
 * 
 * Build a complete CRUD API for blogs with the following features:
 * - Routes: store, update, show, destroy (all under 'blogs' resource)
 * - Validation: name (required), domain (required, valid URL), owner_id cannot be set manually
 * - Policy: Only blog owners can view/update/delete their blogs
 * - Soft deletes: Use soft delete when destroying blogs
 * - Middleware: Block users named "Blocked User" from creating blogs
 * - Event: Dispatch BlogCreated event when a blog is created
 * - Listener & Mailable: Send NewBlogCreated email to the blog creator via a listener
 */
test('create a blog', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('blogs.store'), [
        'name' => 'Laravel Blog',
        'domain' => 'https://blog.example.com'
    ]);

    $response->assertStatus(201);

    $response->assertJson([
        'name' => 'Laravel Blog',
        'domain' => 'https://blog.example.com',
        'owner_id' => $user->id,
    ]);

    $this->assertDatabaseHas('blogs', [
        'name' => 'Laravel Blog',
        'domain' => 'https://blog.example.com',
        'owner_id' => $user->id,
    ]);
});

/**
 * Validates the payload
 * - name: should be required
 * - domain : should be required and have a valid url
 *
 * @test
 */
test('validate the payload', function () {
    $user = User::factory()->create();
    $user2 = User::factory()->create();

    $this->actingAs($user)->post(route('blogs.store'), [
        'name' => '',
        'domain' => 'https://blog.example.com'
    ])->assertSessionHasErrors('name');

    $this->actingAs($user)->post(route('blogs.store'), [
        'name' => 'Laravel Blog',
        'domain' => 'blogdomaincom',
    ])->assertSessionHasErrors('domain');

    $this->actingAs($user)->post(route('blogs.store'), [
        'name' => 'Laravel Blog',
        'domain' => 'https://blog.example.com',
        'owner_id' => $user2->id,
    ])->assertStatus(422);
});

/**
 * Update a blog
 * @test
 */
test('update a blog', function () {

    $this->withoutExceptionHandling();

    $user = User::factory()->create();

    $blog = Blog::factory()->create(['owner_id' => $user->id]);
    $this->actingAs($user);

    $response = $this->put(route('blogs.update', $blog), [
        'name' => 'Laravel Blog',
        'domain' => 'https://new.example.com'
    ]);

    $response->assertStatus(200);

    $response->assertJson([
        'name' => 'Laravel Blog',
        'domain' => 'https://new.example.com',
        'owner_id' => $user->id,
    ]);

    $this->assertDatabaseHas('blogs', [
        'id'  => $blog->id,
        'name' => 'Laravel Blog',
        'domain' => 'https://new.example.com',
        'owner_id' => $user->id
    ]);
});

/**
 * Create a Policy to authorize only the owner
 * of the blog to be able to perform actions on the blog.
 *
 * @test
 */
test('use policy to authorize actions', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $blog = Blog::factory()->create([
        'owner_id' => $user2->id,
    ]);

    $this->actingAs($user1)->delete(route('blogs.destroy', $blog))->assertForbidden();
    $this->actingAs($user1)->get(route('blogs.show', $blog))->assertForbidden();
    $this->actingAs($user1)->put(route('blogs.update', $blog))->assertForbidden();
});

/**
 * Apply Soft Delete
 *
 * @test
 */
test('apply soft delete', function () {
    $user = User::factory()->create();

    $blog = Blog::factory()->create([
        'owner_id' => $user->id,
    ]);

    $response = $this->actingAs($user)->delete(route('blogs.destroy', $blog));
    $response->assertStatus(204);

    $this->assertSoftDeleted('blogs', ['id' => $blog->id]);
});

/**
 * Create a middleware that will block a user with
 * a name "Blocked User" to create Blogs
 *
 * @test
 */
test('use middleware to block access', function () {
    $user = User::factory()->create([
        'name' => 'Blocked User',
    ]);

    $this->actingAs($user)->post(route('blogs.store'), [
        'name' => 'Laravel Blog',
        'domain' => 'https://blog.example.com'
    ])->assertUnauthorized();
});

/**
 * Dispatch an event after a creation of a Blog
 *
 * @test
 */
test('dispatch an event', function () {
    $this->withoutExceptionHandling();
    Event::fake();

    $user = User::factory()->create();

    $this->actingAs($user)->post(route('blogs.store'), [
        'name' => 'Laravel Blog',
        'domain' => 'https://blog.example.com'
    ]);

    Event::assertDispatched(\App\Events\BlogCreated::class);
});

/**
 * When a Blog is created an event will be dispatched.
 * A listener should be created that will be used to
 * email the creator with Blog details
 *
 * @test
 */
test('create a listener that will send an email to user', function () {
    $this->withoutExceptionHandling();
    Mail::fake();

    $user = User::factory()->create();

    $this->actingAs($user)->post(route('blogs.store'), [
        'name' => 'Laravel Blog',
        'domain' => 'https://blog.example.com'
    ]);

    Mail::assertSent(\App\Mail\NewBlogCreated::class);
});
