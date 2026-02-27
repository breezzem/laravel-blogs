<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;

/**
 * WHAT YOU NEED TO DO:
 * 
 * Create two Artisan commands:
 * - users:all - Display a table listing all users with columns: ID, Email
 * - blogs:delete {id} - Delete a blog by ID with confirmation prompt ("Do you really want to delete Blog {id}?")
 *   On confirmation, delete the blog and output "Blog {id} deleted successfully"
 */

test('list users via artisan command', function () {
    User::factory(2)->create();

    $this->artisan('users:all')
        ->expectsTable([
            'ID',
            'Email',
        ], User::all(['id', 'email'])->toArray());
});

test('delete blog via artisan command', function () {
    $user = User::factory()->create();
    Blog::factory(3)->create(['owner_id' => $user->id]);

    $blog = Blog::first();

    $this->artisan('blogs:delete ' . $blog->id)
        ->expectsConfirmation("Do you really want to delete Blog {$blog->id}?", "yes")
        ->expectsOutput("Blog {$blog->id} deleted successfully")
        ->assertExitCode(0);

    $this->assertCount(2, Blog::all());
});