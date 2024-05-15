<?php

namespace Database\Factories;

use App\Models\Categories;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'category_id' => Categories::factory()->create()->id,
            'name' => $this->faker->sentence(),
            'slug' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'price' => rand(1, 3),
        ];
    }
}
