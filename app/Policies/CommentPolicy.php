<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Comment $comment): bool
    {
        // Owner can update or permission
        return $user->id === $comment->user_id
            || $user->can('edit any comment');
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id
            || $user->can('delete any comment');
    }

    public function approve(User $user): bool
    {
        return $user->can('approve comments');
    }
}
