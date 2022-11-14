<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "nis" => random_int(10000000, 99999999),
            "nisn" => random_int(1000000000, 9999999999),
            "fullname" => fake()->name(),
            "date_of_birth" => fake()->date(),
            "place_of_birth" => "Kab. Semarang",
            "gender" => "Laki-laki",
            "religion" => "Islam",
            "address" => fake()->address(),
            "major_class_id" => 1,
            "major_id" => 1,
            "email" => fake()->email(),
            "phone_number" => fake()->phoneNumber()
        ];
    }
}
