<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Shuffle the days randomly
        shuffle($daysOfWeek);
        
        // Select random days to be true
        $selectedDays = array_fill_keys(array_slice($daysOfWeek, 0, mt_rand(1, count($daysOfWeek))), true);
        $days = json_encode($selectedDays, JSON_UNESCAPED_UNICODE);

        $repeat = $this->faker->boolean();

        // Generate start date in 2024
        $start_date = $this->faker->dateTimeBetween('2024-01-01', '2024-12-31');

        // Ensure end_date is after start_date
        $end_date = $this->faker->dateTimeBetween($start_date, '2024-12-31');

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->text(150),
            'period' => $this->faker->randomElement(['week', '2weeks', 'month', 'year']),
            'repeat' => $repeat,
            'start_date' => $start_date->format('Y-m-d'),
            'end_date' => $end_date->format('Y-m-d'),
            'hour' => $this->faker->time(),
            'days' => $repeat ? $days : null,
            'status' => $this->faker->randomElement([0, 1, 2, 3]),
        ];
    }
}