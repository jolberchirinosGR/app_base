<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'company' => $this->faker->company,
            'category' => $this->faker->word,
            'bus' => $this->faker->word,
            'line' => $this->faker->word,
            'driver' => $this->faker->name,
            'employee' => $this->faker->randomNumber(5),
            'hour' => $this->faker->time('H:i'),
            'action' => $this->faker->word,
            'notice_time' => $this->faker->time('H:i'),
            'change_bus' => $this->faker->word,
            'solution_time' => $this->faker->time('H:i'),
            'description' => $this->faker->sentence,
        ];
    }
}
