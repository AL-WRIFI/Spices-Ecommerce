<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Please seed categories first.');
            return;
        }
        $faker = Faker::create();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 3; $i++) {
                SubCategory::create([
                    'name' => $category->name . ' SubCategory ' . $i,
                    'slug' => $category->slug . '-subcategory-' . $i,
                    'image' => 'https://picsum.photos/400/300?random=' . rand(10, 100),
                    'description' => $faker->sentence(6),
                    'category_id' => $category->id,
                ]);
            }
        }

        $this->command->info('Subcategories seeded successfully.');
    }
}