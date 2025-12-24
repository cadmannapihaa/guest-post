<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\SubscriptionPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionFeatureFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedPermissions();
    }

    /** @test */
    public function user_with_can_publish_false_gets_403_on_post_update()
    {
        $package = SubscriptionPackage::factory()->create([
            'features' => [
                'can_publish' => false,
            ],
        ]);

        $user = User::factory()->create([
            'subscription_package_id' => $package->id,
            'subscription_expires_at' => now()->addDay(),
        ]);

        $user->assignRole('author');

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->put("/posts/{$post->id}", [
                'title' => 'Blocked',
                'slug' => 'blocked',
                'content' => '<p>Blocked</p>',
            ])
            ->assertStatus(403);
    }

    /** @test */
    public function user_with_valid_subscription_can_update_post()
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

        $user->assignRole('editor');

        $post = Post::factory()->create();

        $this->actingAs($user)
            ->put("/posts/{$post->id}", [
                'title' => 'Updated',
                'slug' => 'updated',
                'content' => '<p>Updated</p>',
            ])
            ->assertStatus(302); // redirect after success
    }
}
