<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Cases>
 */
class CasesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_name' => fake()->company(),
            'writer_name' => fake()->name(),
            'guest_name' => fake()->name(),
            'guest_count' => fake()->numberBetween(1, 10),
            'request_detail' => fake()->sentence(),
            'dispatch_location' => fake()->city(),
            'service_start' => fake()->dateTime(),
            'service_end' => fake()->dateTime(),
            'service_hours' => fake()->numberBetween(1, 12),
            'guide_language' => fake()->languageCode(),
            'vehicle_type' => fake()->word(),
            'desired_areas' => fake()->sentence(),
            'extra_requests' => fake()->optional()->sentence(),
            'multi_day' => fake()->boolean(),
            'day2_start' => fake()->optional()->dateTime(),
            'day2_end' => fake()->optional()->dateTime(),
            'day3_start' => fake()->optional()->dateTime(),
            'day3_end' => fake()->optional()->dateTime(),
            'others_schedule' => fake()->optional()->sentence(),
        ];
    }
}
