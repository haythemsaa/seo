<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Keyword>
 */
class KeywordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $position = rand(1, 100);
        $previousPosition = $position + rand(-10, 10);

        return [
            'project_id' => Project::factory(),
            'keyword' => fake()->words(rand(2, 4), true),
            'search_volume' => fake()->numberBetween(100, 100000),
            'difficulty' => fake()->numberBetween(1, 100),
            'cpc' => fake()->randomFloat(2, 0.10, 50.00),
            'current_position' => $position,
            'previous_position' => max(1, $previousPosition),
            'best_position' => min($position, rand(1, $position)),
            'worst_position' => max($position, rand($position, 100)),
            'url' => fake()->url(),
            'search_volume_trend' => fake()->randomElement(['up', 'down', 'stable']),
            'created_at' => now()->subDays(rand(1, 90)),
        ];
    }

    /**
     * Indicate that the keyword is in top 10.
     */
    public function topTen(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_position' => rand(1, 10),
            'best_position' => rand(1, 5),
        ]);
    }

    /**
     * Indicate that the keyword position improved.
     */
    public function improved(): static
    {
        $position = rand(5, 20);
        return $this->state(fn (array $attributes) => [
            'current_position' => $position,
            'previous_position' => $position + rand(5, 15),
        ]);
    }
}
