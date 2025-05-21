<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'بهارات مطحونة',
                'image' => 'https://images.pexels.com/photos/5945749/pexels-photo-5945749.jpeg?auto=compress&cs=tinysrgb&w=600',
                'description' => 'مجموعة متنوعة من البهارات المطحونة الطازجة.',
            ],
            [
                'name' => 'أعشاب مجففة',
                'image' => 'https://images.pexels.com/photos/139259/pexels-photo-139259.jpeg?auto=compress&cs=tinysrgb&w=600',
                'description' => 'أعشاب عطرية مجففة بعناية للحفاظ على نكهتها.',
            ],
            [
                'name' => 'بهارات حب كاملة',
                'image' => 'https://images.pexels.com/photos/262969/pexels-photo-262969.jpeg?auto=compress&cs=tinysrgb&w=600',
                'description' => 'حبوب بهارات كاملة لطحنها طازجة أو استخدامها كما هي.',
            ],
            [
                'name' => 'فلفل وبابريكا',
                'image' => 'https://images.pexels.com/photos/1367243/pexels-photo-1367243.jpeg?auto=compress&cs=tinysrgb&w=600',
                'description' => 'أنواع مختلفة من الفلفل الحار والحلو والبابريكا.',
            ],
            [
                'name' => 'بذور صحية',
                'image' => 'https://images.pexels.com/photos/8951404/pexels-photo-8951404.jpeg?auto=compress&cs=tinysrgb&w=600',
                'description' => 'مجموعة من البذور الغنية بالفوائد الصحية.',
            ],
            [
                'name' => 'خلطات بهارات جاهزة',
                'image' => 'https://img.freepik.com/free-photo/top-view-different-condiments-with-salt-red-pepper-black-pepper-dark-desk_140725-65456.jpg?w=740',
                'description' => 'خلطات مبتكرة وجاهزة للاستخدام لأطباق متنوعة.',
            ],
            [
                'name' => 'زعفران وكركم', 
                'image' => 'https://images.pexels.com/photos/60088/saffron-crocus-threads-spice-60088.jpeg?auto=compress&cs=tinysrgb&w=600',
                'description' => 'زعفران فاخر وكركم عالي الجودة.',
            ],
            [
                'name' => 'شاي وأعشاب للشرب',
                'image' => 'https://images.pexels.com/photos/230491/pexels-photo-230491.jpeg?auto=compress&cs=tinysrgb&w=600',
                'description' => 'أوراق شاي وأعشاب مختارة لتحضير مشروبات لذيذة.',
            ],
            [
                'name' => 'محليات طبيعية',
                'image' => 'https://images.pexels.com/photos/209304/pexels-photo-209304.jpeg?auto=compress&cs=tinysrgb&w=600',
                'description' => 'بدائل صحية للسكر مثل العسل ودبس التمر.',
            ],
            [
                'name' => 'منتجات الملح والفانيليا',
                'image' => 'https://images.pexels.com/photos/2788792/pexels-photo-2788792.jpeg?auto=compress&cs=tinysrgb&w=600', // صورة ملح، يمكن إضافة صورة فانيليا إذا دمجت
                'description' => 'أنواع مختلفة من الملح الطبيعي وأعواد وقرون الفانيليا.',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'image' => $categoryData['image'],
                'description' => $categoryData['description'],
            ]);
        }
        $this->command->info(count($categories) . ' categories seeded successfully.');
    }
}