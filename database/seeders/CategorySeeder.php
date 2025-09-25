<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'পোশাক'],
            ['name' => 'ইলেক্ট্রনিক্স'],
            ['name' => 'বই'],
            ['name' => 'খেলনা'],
            ['name' => 'জুতা'],
            ['name' => 'ঘড়ি'],
            ['name' => 'আসবাবপত্র'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
