<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Tag;
use App\Policies\TagPolicy;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Models\SubscriptionPackage;
use App\Policies\SubscriptionPackagePolicy;
use App\Models\PostEditAccess;
use App\Policies\PostEditAccessPolicy;
use App\Models\UserNotification;
use App\Policies\UserNotificationPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\AuthorizationLog;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Tag::class => TagPolicy::class, // if you add tag policy later
        Comment::class => CommentPolicy::class,
        SubscriptionPackage::class => SubscriptionPackagePolicy::class,
        PostEditAccess::class => PostEditAccessPolicy::class,
        UserNotification::class => UserNotificationPolicy::class, // optional
    ];

    public function boot()
    {
        $this->registerPolicies();

        Gate::after(function ($user, $ability, $result, $arguments) {
            AuthorizationLog::create([
                'user_id' => $user?->id,
                'ability' => $ability,
                'resource_type' => $arguments[0] ?? null,
                'resource_id' => $arguments[0]->id ?? null,
                'allowed' => (bool) $result,
                'reason' => $result ? null : 'Policy denied',
            ]);
        });
    }
}
