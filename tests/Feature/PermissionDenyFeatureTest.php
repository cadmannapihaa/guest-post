<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\PermissionDeny;
use App\Models\SubscriptionPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionDenyFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedPermissions();
    }

    /** @test */
    public function denied_user_gets_403_on_post_update()
    {
        $package = SubscriptionPackage::factory()->create([
            'features' => ['can_publish' => true],
        ]);

        $user = User::factory()->create([
            'subscription_package_id' => $package->id,
            'subscription_expires_at' => now()->addDay(),
        ]);
        $user->assignRole('editor');

        $post = Post::factory()->create();

        PermissionDeny::create([
            'user_id' => $user->id,
            'permission' => 'post.edit',
            'resource_type' => Post::class,
            'resource_id' => $post->id,
        ]);

        $this->actingAs($user)
            ->put("/posts/{$post->id}", [
                'title' => 'Blocked update',
                'slug' => 'blocked-update',
                'content' => '<p>Blocked</p>',
            ])
            ->assertStatus(403);
    }
}
