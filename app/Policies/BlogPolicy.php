<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;

class BlogPolicy
{
    /**
     * to see if the user can view the blog.
     */
    public function view(User $user, Blog $blog): bool
    {
        return $user->id === $blog->owner_id;
    }

    /**
     *  to see if the user can update the blog.
     */
    public function update(User $user, Blog $blog): bool
    {
        return $user->id === $blog->owner_id;
    }

    /**
     * to see if the user can delete the blog.
     */
    public function delete(User $user, Blog $blog): bool
    {
        return $user->id === $blog->owner_id;
    }
}
