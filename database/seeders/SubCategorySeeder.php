<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = [
            ['category_name' => 'بهارات مطحونة', 'name' => 'كمون ناعم', 'image' => 'https://images.pexels.com/photos/13202005/pexels-photo-13202005.jpeg?auto=compress&cs=tinysrgb&w=600', 'description' => 'كمون مطحون بجودة عالية.'],
            ['category_name' => 'بهارات مطحونة', 'name' => 'كزبرة ناعمة', 'image' => 'https://images.pexels.com/photos/6792330/pexels-photo-6792330.jpeg?auto=compress&cs=tinysrgb&w=600', 'description' => 'كزبرة مطحونة طازجة.'],
            ['category_name' => 'أعشاب مجففة', 'name' => 'زعتر بري مجفف', 'image' => 'https://images.pexels.com/photos/760281/pexels-photo-760281.jpeg?auto=compress&cs=tinysrgb&w=600', 'description' => 'زعتر بري مجفف للطهي والشاي.'],
            ['category_name' => 'أعشاب مجففة', 'name' => 'نعناع مجفف', 'image' => 'https://images.pexels.com/photos/10199870/pexels-photo-10199870.jpeg?auto=compress&cs=tinysrgb&w=600', 'description' => 'نعناع مجفف منعش.'],
            ['category_name' => 'بهارات حب كاملة', 'name' => 'فلفل أسود حب', 'image' => 'https://images.pexels.com/photos/4198157/pexels-photo-4198157.jpeg?auto=compress&cs=tinysrgb&w=600', 'description' => 'حبوب فلفل أسود كاملة.'],
            ['category_name' => 'بهارات حب كاملة', 'name' => 'هيل أخضر حب', 'image' => 'https://images.pexels.com/photos/6111614/pexels-photo-6111614.jpeg?auto=compress&cs=tinysrgb&w=600', 'description' => 'هيل حب فاخر للقهوة والحلويات.'],
            ['category_name' => 'فلفل وبابريكا', 'name' => 'بابريكا حلوة', 'image' => 'https://images.pexels.com/photos/8472456/pexels-photo-8472456.jpeg?auto=compress&cs=tinysrgb&w=600', 'description' => 'مسحوق بابريكا حلوة لإضافة لون ونكهة.'],
            ['category_name' => 'بذور صحية', 'name' => 'بذور الشيا', 'image' => 'https://images.pexels.com/photos/4061397/pexels-photo-4061397.jpeg?auto=compress&cs=tinysrgb&w=600', 'description' => 'بذور الشيا العضوية الغنية بالفوائد.'],
            ['category_name' => 'خلطات بهارات جاهزة', 'name' => 'بهارات الكبسة', 'image' => 'https://img.freepik.com/free-photo/top-view-different-condiments-with-salt-red-pepper-black-pepper-dark-desk_140725-65456.jpg?w=740', 'description' => 'خلطة بهارات خاصة للكبسة السعودية.'],
            ['category_name' => 'زعفران وكركم', 'name' => 'خيوط الزعفران الأصلي', 'image' => 'https://images.pexels.com/photos/713300/pexels-photo-713300.jpeg?auto=compress&cs=tinysrgb&w=600', 'description' => 'زعفران نقيل فاخر درجة أولى.'],
        ];

        $count = 0;
        foreach ($subCategories as $subCategoryData) {
            $category = Category::where('name', $subCategoryData['category_name'])->first();
            if ($category) {
                SubCategory::create([
                    'name' => $subCategoryData['name'],
                    'slug' => Str::slug($subCategoryData['name']),
                    'image' => $subCategoryData['image'],
                    'description' => $subCategoryData['description'],
                    'category_id' => $category->id,
                ]);
                $count++;
            } else {
                $this->command->warn("Category '{$subCategoryData['category_name']}' not found for subcategory '{$subCategoryData['name']}'. Skipping.");
            }
        }
        $this->command->info($count . ' subcategories seeded successfully.');
    }
}