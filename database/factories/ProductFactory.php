<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id,
            'name' => $this->faker->randomElement(['শার্ট', 'প্যান্ট', 'জুতা', 'ঘড়ি', 'মোবাইল', 'ল্যাপটপ', 'বই', 'খেলনা', 'টি-শার্ট', 'শাড়ি']),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => 'https://picsum.photos/seed/' . $this->faker->unique()->word . '/640/480',
            'is_featured' => $this->faker->boolean,
            'discount_price' => $this->faker->optional()->randomFloat(2, 5, 500),
        ];
    }
}
