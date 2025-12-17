<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SubscriptionPackage;

class SubscriptionPackagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, SubscriptionPackage $subscriptionPackage): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, SubscriptionPackage $subscriptionPackage): bool
    {
        return $user->hasRole('admin');
    }
}
