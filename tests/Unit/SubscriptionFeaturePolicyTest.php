<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\SubscriptionPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionFeaturePolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedPermissions();
    }

    private function createUserWithFeatures(array $features, string $role = 'author'): User
    {
        $package = SubscriptionPackage::factory()->create([
            'features' => $features,
        ]);

        $user = User::factory()->create([
            'subscription_package_id' => $package->id,
            'subscription_expires_at' => now()->addDay(),
        ]);

        $user->assignRole($role);

        return $user;
    }

    /** @test */
    public function user_cannot_edit_post_if_can_publish_feature_is_false()
    {
        $user = $this->createUserWithFeatures([
            'can_publish' => false,
        ]);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertFalse(
            $user->can('update', $post),
            'User should not edit post if can_publish is false'
        );
    }

    /** @test */
    public function user_can_edit_post_if_can_publish_feature_is_true()
    {
        $user = $this->createUserWithFeatures([
            'can_publish' => true,
        ]);

        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->assertTrue(
            $user->can('update', $post),
            'User should edit post if can_publish is true'
        );
    }

    /** @test */
    public function expired_subscription_blocks_edit_even_if_feature_enabled()
    {
        $package = SubscriptionPackage::factory()->create([
            'features' => [
                'can_publish' => true,
            ],
        ]);

        $user = User::factory()->create([
            'subscription_package_id' => $package->id,
            'subscription_expires_at' => now()->subMinute(),
        ]);

        $user->assignRole('editor');

        $post = Post::factory()->create();

        $this->assertFalse(
            $user->can('update', $post),
            'Expired subscription must block edit'
        );
    }
}
