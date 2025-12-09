<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\Category;
use App\Models\User;  // Add the User model
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,  // Generates a random sentence as the title
            'slug' => $this->faker->slug,  // Generates a random slug based on the title
            'body' => $this->faker->paragraph,  // Generates a random paragraph as the content
            'image' => $this->faker->imageUrl(),  // Generates a random image URL for the image field
            'author_id' => User::inRandomOrder()->first()->id,  // Select a random user as the author
            'created_at' => now(),  // Sets the current timestamp for created_at
            'updated_at' => now(),  // Sets the current timestamp for updated_at
        ];
    }

    /**
     * Attach a random category to the news article.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withRandomCategory()
    {
        return $this->afterCreating(function (News $news) {
            // Assign a random category to the news article through the news_categories pivot table
            $category = Category::inRandomOrder()->first();  // Fetch a random category
            $news->categories()->attach($category->id);  // Attach the category to the news via the pivot table
        });
    }
}
