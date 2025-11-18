<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Backlink>
 */
class BacklinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'source_url' => fake()->url(),
            'target_url' => fake()->url(),
            'anchor_text' => fake()->words(rand(2, 5), true),
            'link_type' => fake()->randomElement(['dofollow', 'nofollow']),
            'da' => fake()->numberBetween(1, 100),
            'pa' => fake()->numberBetween(1, 100),
            'tf' => fake()->numberBetween(1, 100),
            'cf' => fake()->numberBetween(1, 100),
            'spam_score' => fake()->numberBetween(0, 100),
            'is_toxic' => fake()->boolean(10), // 10% toxic
            'status' => fake()->randomElement(['active', 'lost', 'new']),
            'first_seen' => now()->subDays(rand(1, 365)),
            'last_checked' => now()->subDays(rand(0, 7)),
            'disavowed' => false,
        ];
    }

    /**
     * Indicate that the backlink is toxic.
     */
    public function toxic(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_toxic' => true,
            'spam_score' => rand(70, 100),
        ]);
    }

    /**
     * Indicate that the backlink is high quality.
     */
    public function highQuality(): static
    {
        return $this->state(fn (array $attributes) => [
            'da' => rand(70, 100),
            'pa' => rand(60, 100),
            'tf' => rand(60, 100),
            'spam_score' => rand(0, 10),
            'link_type' => 'dofollow',
        ]);
    }

    /**
     * Indicate that the backlink was lost.
     */
    public function lost(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'lost',
        ]);
    }
}
