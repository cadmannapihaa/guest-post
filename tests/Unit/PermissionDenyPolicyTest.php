<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\PermissionDeny;
use App\Models\SubscriptionPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionDenyPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedPermissions();
    }

    private function createActiveUserWithPackage(string $role): User
    {
        $package = SubscriptionPackage::factory()->create([
            'features' => [
                'can_publish' => true,
            ],
        ]);

        $user = User::factory()->create([
            'subscription_package_id' => $package->id,
            'subscription_expires_at' => now()->addDay(),
        ]);

        $user->assignRole($role);

        return $user;
    }

    /** @test */
    public function explicit_deny_blocks_edit_even_with_permission()
    {
        $user = $this->createActiveUserWithPackage('editor');

        $post = Post::factory()->create();

        PermissionDeny::create([
            'user_id' => $user->id,
            'permission' => 'post.edit',
            'resource_type' => Post::class,
            'resource_id' => $post->id,
            'reason' => 'Legal hold',
        ]);

        $this->assertFalse(
            $user->can('update', $post),
            'Explicit deny should override role permissions'
        );
    }

    /** @test */
    public function explicit_deny_blocks_owner_edit()
    {
        $user = $this->createActiveUserWithPackage('author');

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        PermissionDeny::create([
            'user_id' => $user->id,
            'permission' => 'post.edit',
            'resource_type' => Post::class,
            'resource_id' => $post->id,
            'reason' => 'Under review',
        ]);

        $this->assertFalse(
            $user->can('update', $post),
            'Owner should not edit if explicitly denied'
        );
    }

    /** @test */
    public function role_based_deny_blocks_all_users_with_that_role()
    {
        $editor = $this->createActiveUserWithPackage('editor');

        $post = Post::factory()->create();

        PermissionDeny::create([
            'role_name' => 'editor',
            'permission' => 'post.edit',
            'resource_type' => Post::class,
            'reason' => 'System freeze',
        ]);

        $this->assertFalse(
            $editor->can('update', $post),
            'Role-based deny should block editor'
        );
    }

    /** @test */
    public function expired_deny_allows_edit_again()
    {
        $user = $this->createActiveUserWithPackage('editor');

        $post = Post::factory()->create();

        PermissionDeny::create([
            'user_id' => $user->id,
            'permission' => 'post.edit',
            'resource_type' => Post::class,
            'resource_id' => $post->id,
            'expires_at' => now()->subMinute(),
        ]);

        $this->assertTrue(
            $user->can('update', $post),
            'Expired deny must not block access'
        );
    }

    /** @test */
    public function deny_overrides_time_bound_edit_access()
    {
        $user = $this->createActiveUserWithPackage('author');

        $post = Post::factory()->create();

        // Grant temporary access
        $post->editAccess()->create([
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
        ]);

        // Explicit deny
        PermissionDeny::create([
            'user_id' => $user->id,
            'permission' => 'post.edit',
            'resource_type' => Post::class,
            'resource_id' => $post->id,
        ]);

        $this->assertFalse(
            $user->can('update', $post),
            'Deny must override granted access'
        );
    }
}
