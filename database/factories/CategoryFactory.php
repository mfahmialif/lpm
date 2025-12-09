<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word, // Nama kategori
            'description' => $this->faker->slug, // Slug unik
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
