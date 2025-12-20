<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Models\PostEditAccess;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Services\AuthorizationService;

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
        // 0. Explicit deny rule (ABAC)
        if (AuthorizationService::isDenied($user, 'post.edit', Post::class, $post->id)) {
            return false;
        }

        // 1. Subscription expired
        if (!$user->subscriptionPackage || $user->subscription_expires_at < now()) {
            return false;
        }

        // 2. Subscription feature
        if (!AuthorizationService::subscriptionAllows($user, 'can_publish')) {
            return false;
        }

        // 3. RBAC
        if ($user->can('edit any post')) {
            return true;
        }

        if ($post->user_id === $user->id && $user->can('edit own post')) {
            return true;
        }

        // 4. Time-bound ABAC grant
        return $post->editAccess()
            ->active()
            ->where('user_id', $user->id)
            ->exists();
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
