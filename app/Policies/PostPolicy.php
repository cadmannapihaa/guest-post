<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Models\PostEditAccess;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Everyone authenticated can see listing
        return $user->can('view posts');
    }

    public function view(User $user, Post $post): bool
    {
        // If published, anyone with view permission can see
        if ($post->is_published && $user->can('view posts')) {
            return true;
        }

        // Owners or users with special permission can view unpublished
        return $user->id === $post->user_id
            || $user->can('view unpublished posts');
    }

    public function create(User $user): bool
    {
        return $user->can('create posts');
    }

    public function update(User $user, Post $post): bool
    {
        // Super permission to edit any
        if ($user->can('edit any post')) {
            return true;
        }

        // Permission + owner (own post)
        if ($user->id === $post->user_id && $user->can('edit own post')) {
            return true;
        }

        // Time-limited grant (ABAC)
        $grant = PostEditAccess::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->exists();

        if ($grant && $user->can('edit granted post')) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Post $post): bool
    {
        // Owner with permission or global delete
        return ($user->id === $post->user_id && $user->can('delete own post'))
            || $user->can('delete any post');
    }

    public function publish(User $user, Post $post): bool
    {
        return $user->can('publish posts');
    }
}
