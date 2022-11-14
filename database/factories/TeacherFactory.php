<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "nuptk" => random_int(1000000000000000, 9999999999999999),
            "fullname" => fake()->name(),
            "date_of_birth" => fake()->date(),
        ];
    }
}
