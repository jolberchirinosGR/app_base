<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        $daysOfWeek = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        // Shuffle the days randomly
        shuffle($daysOfWeek);
        
        // Select random days to be true
        $selectedDays = array_fill_keys(array_slice($daysOfWeek, 0, mt_rand(1, count($daysOfWeek))), true);
        $days = json_encode($selectedDays, JSON_UNESCAPED_UNICODE);

        $repeat = $this->faker->boolean();

        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->text(150),
            'period' => $this->faker->randomElement(['week', '2week', 'month', 'year']),
            'repeat' => $repeat,
            'date' => $this->faker->date(),
            'hour' => $this->faker->time(),
            'days' => $repeat ? $days : null,
            'status' => $this->faker->randomElement([0, 1, 2, 3]),
        ];
    }
}