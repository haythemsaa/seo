<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company() . ' Website',
            'url' => fake()->url(),
            'country' => fake()->randomElement(['FR', 'US', 'GB', 'DE', 'ES', 'IT']),
            'language' => fake()->randomElement(['fr', 'en', 'de', 'es', 'it']),
            'search_engine' => 'google',
            'device' => 'desktop',
            'status' => 'active',
            'last_crawled_at' => now()->subDays(rand(1, 30)),
            'created_at' => now()->subDays(rand(1, 365)),
        ];
    }

    /**
     * Indicate that the project is paused.
     */
    public function paused(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paused',
        ]);
    }

    /**
     * Indicate that the project is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }
}
