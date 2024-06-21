<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->sentence(3),
            'description' => fake()->text(150),
            'period' => fake()->randomElement(['week', '2week', 'month', 'year']), // Changed to randomElement
            'repeat' => fake()->boolean(),
            'date' => fake()->date(),
            'hour' => fake()->time(),
            'days' => implode(',', fake()->randomElements(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], rand(1, 7))),
            'status' => 1,
        ];
    }
}
