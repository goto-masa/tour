<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'tel' => fake()->numberBetween(1000000000, 9999999999),
            'contact' => fake()->numberBetween(1000000000, 9999999999),
            'lang' => fake()->languageCode(),
            'note' => fake()->sentence(),
        ];
    }
}
