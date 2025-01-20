<?php

namespace Database\Seeders;

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
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Devices and gadgets for everyday use.',
                'image' => 'electronics.jpg', // Placeholder image filename
                'status' => 1,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Clothing and accessories for all seasons.',
                'image' => 'fashion.jpg', // Placeholder image filename
                'status' => 1,
            ],
            [
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
                'description' => 'Appliances to make your home more comfortable.',
                'image' => 'home-appliances.jpg', // Placeholder image filename
                'status' => 1,
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'A wide range of books for every reader.',
                'image' => 'books.jpg', // Placeholder image filename
                'status' => 1,
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'description' => 'Accessories and parts for your vehicle.',
                'image' => 'automotive.jpg', // Placeholder image filename
                'status' => 1,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
