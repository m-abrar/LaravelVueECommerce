<?php

namespace Database\Factories;

use App\Models\PropertyTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class PropertiesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'property_type_id' => PropertyTypes::factory()->create()->id,
            'name' => $this->faker->sentence(),
            'slug' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'price' => rand(1, 3),
        ];
    }
}
