<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

/**
 * WHAT YOU NEED TO DO:
 * 
 * Install and configure the Spatie Activity Log package:
 * - Install spatie/laravel-activitylog
 * - Configure the User model to log activity 
 * - The package should automatically log changes when User model is updated
 */

test('activitylog package is installed', function () {
    $output = null;
    exec('composer show', $output);

    $this->assertCount(1, array_filter($output, function ($item) {
        return Str::contains($item, 'spatie/laravel-activitylog');
    }));
});

test('activitylog package is working', function () {
    $user = User::factory()->create(['name' => 'Jane Doe']);

    $user->name = "John Doe";
    $user->save();

    $activity = Activity::all()->last();
    $changes = $activity->changes;

    $this->assertEquals('updated', $activity->description);
    $this->assertEquals($user->id, $activity->subject_id);

    $this->assertEquals('Jane Doe', $changes['old']['name']);
    $this->assertEquals('John Doe', $changes['attributes']['name']);
});
