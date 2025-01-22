<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = SubCategory::all();
        $units = Unit::all();

        if ($subCategories->isEmpty() || $units->isEmpty()) {
            $this->command->error('No subcategories or units found. Please seed them first.');
            return;
        }

        $faker = Faker::create();

        for ($i = 1; $i <= 50; $i++) {
            Product::create([
                'name' => $faker->word,
                'slug' => $faker->slug,
                'price' => $faker->randomFloat(2, 10, 1000),
                'sale_price' => $faker->randomFloat(2, 5, 900),
                'sub_category_id' => $subCategories->random()->id,
                'image_url' => 'https://picsum.photos/400/300?random=' . rand(10, 100),
                'summary' => $faker->sentence(4),
                'description' => $faker->sentence(8),
                'unit_id' => $units->random()->id,
                'quantity' => $faker->numberBetween(10, 100),
                'stock' => 1,
                'status' => 1,
            ]);
        }

        $this->command->info('50 products seeded successfully.');
    }
}