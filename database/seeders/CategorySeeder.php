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
                'name' => 'VAR1',
                'slug' => 'var1',
                'image' => 'https://picsum.photos/400/300?random=1',
                'description' => $faker->sentence(6),
            ],
            [
                'name' => 'VAR2',
                'slug' => 'var2',
                'image' => 'https://picsum.photos/400/300?random=2',
                'description' => $faker->sentence(6),
            ],
            [
                'name' => 'VAR3',
                'slug' => 'var3',
                'image' => 'https://picsum.photos/400/300?random=3',
                'description' => $faker->sentence(6),
            ],
            [
                'name' => 'VAR4',
                'slug' => 'var4',
                'image' => 'https://picsum.photos/400/300?random=4',
                'description' => $faker->sentence(6),
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('5 categories seeded successfully.');
    }
}