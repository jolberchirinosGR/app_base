<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class LostObjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Generar un número aleatorio entre 100 y 999
        $randomNumber = mt_rand(1, 999);
        // Obtener el año actual
        $currentYear = date('Y');
        // Combinar el número aleatorio y el año actual con un guión
        $code = $randomNumber . '-' . $currentYear;

        return [
            'date' => now(),
            'code' => $code,
            'register' => $this->faker->name,
            'bus' => $this->faker->word,
            'line' => $this->faker->word,
            'driver' => $this->faker->name,
            'description' => $this->faker->sentence,
            'delivered_by' => $this->faker->name,
            'reception_by' => $this->faker->name,
            'reception_date' => $this->faker->date(),
            'destination' => $this->faker->city,
            'destination_date' => $this->faker->date(),
        ];
    }
}
