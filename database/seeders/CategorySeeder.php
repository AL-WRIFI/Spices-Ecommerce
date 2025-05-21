<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'image' => 'https://picsum.photos/400/300?random=1',
                'description' => $faker->sentence(6),
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'image' => 'https://picsum.photos/400/300?random=2',
                'description' => $faker->sentence(6),
            ],
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'image' => 'https://picsum.photos/400/300?random=3',
                'description' => $faker->sentence(6),
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'image' => 'https://picsum.photos/400/300?random=4',
                'description' => $faker->sentence(6),
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'image' => 'https://picsum.photos/400/300?random=5',
                'description' => $faker->sentence(6),
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('5 categories seeded successfully.');
    }
}