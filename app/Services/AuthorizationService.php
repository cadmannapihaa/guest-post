<?php

namespace App\Services;

use App\Models\PermissionDeny;
use App\Models\User;

class AuthorizationService
{
    public static function isDenied(
        User $user,
        string $permission,
        ?string $resourceType = null,
        ?int $resourceId = null
    ): bool {
        return PermissionDeny::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('role_name', optional($user->roles->first())->name);
            })
            ->where('permission', $permission)
            ->where(function ($q) use ($resourceType, $resourceId) {
                $q->whereNull('resource_type')
                  ->orWhere(function ($q2) use ($resourceType, $resourceId) {
                      $q2->where('resource_type', $resourceType)
                         ->where(function ($q3) use ($resourceId) {
                             $q3->whereNull('resource_id')
                                ->orWhere('resource_id', $resourceId);
                         });
                  });
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    public static function subscriptionAllows(User $user, string $feature): bool
    {
        if (!$user->subscriptionPackage) return false;

        return data_get(
            $user->subscriptionPackage->features,
            $feature,
            false
        ) === true;
    }
}
