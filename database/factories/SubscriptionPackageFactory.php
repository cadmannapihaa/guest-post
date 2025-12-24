<?php

namespace Database\Factories;

use App\Models\SubscriptionPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionPackageFactory extends Factory
{
    protected $model = SubscriptionPackage::class;

    public function definition(): array
    {
        return [
            'name' => 'Pro Plan',
            'type' => 'paid',
            'price' => 999,
            'features' => [
                'can_publish' => true,
                'max_posts_per_day' => 10,
            ],
            'is_active' => true,
            'is_visible' => true,
        ];
    }
}
