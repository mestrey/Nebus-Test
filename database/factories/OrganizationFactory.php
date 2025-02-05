<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'building_id' => \App\Models\Building::inRandomOrder()->first()->id ?? null,
            'phone_numbers' => [$this->faker->phoneNumber, $this->faker->phoneNumber],
        ];
    }
}
