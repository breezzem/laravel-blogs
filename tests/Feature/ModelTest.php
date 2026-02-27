<?php

use App\Models\Blog;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordChanged;

/**
 * WHAT YOU NEED TO DO:
 * 
 * Set up models, relationships, factories, and model features:
 * - Create Blog model with owner() belongsTo relationship
 * - Add blogs() hasMany relationship to User model
 * - Create factories for User and Blog models
 * - Add name mutator to User: capitalize first letter of each word (e.g., "john doe" -> "John Doe")
 * - Add password mutator to User: hash passwords automatically
 * - Send PasswordChanged notification when user password changes
 */

test('blog model exists', function () {
    $this->assertTrue(class_exists(Blog::class));
});

test('blog model has a relationship with the user model', function () {
    $blog = new Blog();
    $relationship = $blog->owner();

    $this->assertEquals(BelongsTo::class, get_class($relationship), 'blogs->owner()');

    $user = new User();
    $relationship = $user->blogs();

    $this->assertEquals(HasMany::class, get_class($relationship), 'user->blogs()');
});

test('create factories for User and Blog', function () {
    $user = User::factory()->create();
    Blog::factory()->create(['owner_id' => $user->id]);

    $this->assertCount(1, Blog::all());
});

test('use mutator on User model', function () {
    $user = User::factory()->make();

    $user->name = 'john doe';

    $this->assertEquals('John Doe', $user->name);
});

test('use set mutator on User model', function () {
    $user = User::factory()->make();

    $user->password = 'secret';

    $this->assertTrue(Hash::check('secret', $user->password));
});

test('the user should be notified after a password change', function () {
    Notification::fake();

    $user = User::factory()->create();

    $user->password = 'secret';
    $user->save();

    Notification::assertSentTo($user, PasswordChanged::class);
});
