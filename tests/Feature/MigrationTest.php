<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * WHAT YOU NEED TO DO:
 * 
 * Create a migration for the blogs table:
 * - Table name: blogs
 * - Columns: id, owner_id (foreign key to users), name, domain, created_at, updated_at, deleted_at
 * - Add foreign key constraint on owner_id referencing users.id
 * - Use soft deletes (deleted_at column)
 */

test('blogs table exists', function () {
    $this->assertTrue(
        Schema::hasTable('blogs')
    );
});


test('blogs table has the correct columns', function () {
    $this->assertTrue(
        Schema::hasColumns('blogs', [
            'id',
            'owner_id',
            'name',
            'domain',
            'created_at',
            'updated_at',
            'deleted_at'
        ])
    );
});

test('blogs table has a foreign key to users table', function () {
    $indexes = DB::select("PRAGMA index_list('blogs')");
    $hasIndex = collect($indexes)->contains(function ($index) {
        return str_contains($index->name ?? '', 'owner_id');
    });
    
    $this->assertTrue($hasIndex || Schema::hasColumn('blogs', 'owner_id'));
});
