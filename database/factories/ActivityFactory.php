<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'parent_id' => null,
            'level' => 1
        ];
    }

    public function child($parentId)
    {
        return $this->state([
            'parent_id' => $parentId,
            'level' => 2,
        ]);
    }

    public function grandchild($parentId)
    {
        return $this->state([
            'parent_id' => $parentId,
            'level' => 3,
        ]);
    }
}
