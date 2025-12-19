<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PostEditAccess;

//& Post edit policy


class PostEditAccessPolicy
{
    public function grant(User $user): bool
    {
        return $user->can('grant post edit access');
    }

    public function revoke(User $user, PostEditAccess $access): bool
    {
        return $user->can('grant post edit access')
            && $access->granted_by === $user->id;
    }
}
