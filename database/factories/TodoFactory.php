<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Todo>
 */
class TodoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = fake()->dateTimeBetween('+1 week', '+2 months');

        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement(['筋力トレーニング', 'ストレッチ', 'プログラミング']).' '.fake()->unique()->numberBetween(1, 100000),
            'memo' => '自身を向上させるタスクです',
            'category' => fake()->randomElement(['仕事','勉強','買い物']),
            'start_at' => $startAt,
            'due_at' => (clone $startAt)->modify('+1 hours'),
        ];
    }
}
