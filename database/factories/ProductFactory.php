<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? 1, // যদি category না থাকে
            'name' => $this->faker->randomElement([
                'শার্ট', 'প্যান্ট', 'জুতা', 'ঘড়ি', 'মোবাইল', 
                'ল্যাপটপ', 'বই', 'খেলনা', 'টি-শার্ট', 'শাড়ি'
            ]),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),

            // External URL থেকে random image generate
            'image' => $this->faker->randomElement([
                'https://picsum.photos/seed/' . $this->faker->unique()->word . '/640/480',
                'https://via.placeholder.com/640x480.png/' . substr(md5($this->faker->unique()->word), 0, 6) . '?text=' . $this->faker->word
            ]),

            'is_featured' => $this->faker->boolean,
            'discount_price' => $this->faker->optional()->randomFloat(2, 5, 500),
        ];
    }
}
