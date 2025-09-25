<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['পোশাক', 'ইলেক্ট্রনিক্স', 'বই', 'খেলনা', 'জুতা', 'ঘড়ি', ' আসবাবপত্র', 'স্বাস্থ্য ও সৌন্দর্য', 'খেলাধুলা', 'অন্যান্য']),
        ];
    }
}
