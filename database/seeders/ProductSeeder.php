<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory; // Renamed to avoid conflict

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create(); // Use the renamed factory
        $units = Unit::all();
        if ($units->isEmpty()) {
            $this->command->error('No units found. Please seed units first.');
            return;
        }

        $products = [
            // 1
            ['name' => 'مسحوق كركم عضوي فاخر', 'price' => 18.50, 'sale_price' => 15.00, 'category_name' => 'بهارات مطحونة', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/2338020/pexels-photo-2338020.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'كركم عضوي مطحون ناعم.', 'description' => 'مسحوق كركم عضوي فاخر، مثالي لإضافة لون ونكهة غنية للأطباق المختلفة، ومعروف بفوائده الصحية.', 'unit_name' => 'Gram', 'package_quantity' => 100, 'stock_level' => $faker->numberBetween(20, 100)],
            // 2
            ['name' => 'أوراق زعتر بري فلسطيني', 'price' => 22.00, 'sale_price' => null, 'category_name' => 'أعشاب مجففة', 'sub_category_name' => 'زعتر بري مجفف', 'image' => 'https://images.pexels.com/photos/11468086/pexels-photo-11468086.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'زعتر فلسطيني أصيل.', 'description' => 'أوراق زعتر بري فلسطيني مجففة، ذات رائحة عطرية قوية ونكهة لا مثيل لها، مثالية للفطور والمناقيش.', 'unit_name' => 'Pack', 'package_quantity' => 80, 'stock_level' => $faker->numberBetween(15, 70)],
            // 3
            ['name' => 'حبوب فلفل أسود تيلشيري', 'price' => 30.00, 'sale_price' => 25.00, 'category_name' => 'بهارات حب كاملة', 'sub_category_name' => 'فلفل أسود حب', 'image' => 'https://images.pexels.com/photos/775023/pexels-photo-775023.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'أفضل أنواع الفلفل الأسود.', 'description' => 'حبوب فلفل أسود تيلشيري كاملة، تتميز بحجمها الكبير ونكهتها الحارة والقوية، تطحن طازجة لأفضل نتيجة.', 'unit_name' => 'Gram', 'package_quantity' => 50, 'stock_level' => $faker->numberBetween(30, 80)],
            // 4
            ['name' => 'بابريكا مدخنة إسبانية', 'price' => 19.75, 'sale_price' => null, 'category_name' => 'فلفل وبابريكا', 'sub_category_name' => 'بابريكا حلوة', 'image' => 'https://images.pexels.com/photos/5945755/pexels-photo-5945755.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'بابريكا بنكهة تدخين فريدة.', 'description' => 'بابريكا مدخنة إسبانية حلوة، تضفي نكهة تدخين عميقة ولونًا جذابًا على أطباق اللحوم والأسماك والبطاطس.', 'unit_name' => 'Gram', 'package_quantity' => 75, 'stock_level' => $faker->numberBetween(25, 90)],
            // 5
            ['name' => 'بذور الكتان الذهبية العضوية', 'price' => 25.00, 'sale_price' => 20.00, 'category_name' => 'بذور صحية', 'sub_category_name' => 'بذور الكتان', 'image' => 'https://images.pexels.com/photos/725991/pexels-photo-725991.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'بذور كتان غنية بالأوميغا 3.', 'description' => 'بذور الكتان الذهبية العضوية، مصدر ممتاز للألياف وأحماض أوميغا 3 الدهنية، يمكن إضافتها للزبادي أو السلطات.', 'unit_name' => 'Pack', 'package_quantity' => 250, 'stock_level' => $faker->numberBetween(40, 120)],
            // 6
            ['name' => 'خلطة بهارات الشاورما السحرية', 'price' => 28.00, 'sale_price' => null, 'category_name' => 'خلطات بهارات جاهزة', 'sub_category_name' => null, 'image' => 'https://img.freepik.com/free-photo/top-view-spices-powder-form-small-plates-brown-table_140725-11357.jpg?w=740', 'summary' => 'لتتبيلة شاورما لا تقاوم.', 'description' => 'خلطة بهارات الشاورما السحرية، مزيج سري من أجود أنواع البهارات لتحضير شاورما منزلية بنكهة المطاعم.', 'unit_name' => 'Pack', 'package_quantity' => 100, 'stock_level' => $faker->numberBetween(10, 60)],
            // 7
            ['name' => 'شعيرات زعفران سوبر نقيل', 'price' => 120.00, 'sale_price' => 100.00, 'category_name' => 'زعفران وكركم', 'sub_category_name' => 'خيوط الزعفران الأصلي', 'image' => 'https://images.pexels.com/photos/52600/saffron-spice-threads-crocus-52600.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'أجود أنواع الزعفران.', 'description' => 'شعيرات زعفران سوبر نقيل، منتقاة بعناية فائقة، تضفي لونًا ذهبيًا ورائحة عطرية ونكهة فاخرة على الأرز والحلويات والمشروبات.', 'unit_name' => 'Gram', 'package_quantity' => 1, 'stock_level' => $faker->numberBetween(5, 20)], // 1 gram pack
            // 8
            ['name' => 'شاي أخضر بالنعناع المغربي', 'price' => 17.00, 'sale_price' => null, 'category_name' => 'شاي وأعشاب للشرب', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/904832/pexels-photo-904832.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'شاي مغربي أصيل.', 'description' => 'شاي أخضر فاخر ممزوج بأوراق النعناع الطازجة، لتحضير الشاي المغربي التقليدي المنعش.', 'unit_name' => 'Pack', 'package_quantity' => 20, 'stock_level' => $faker->numberBetween(30, 90)], // 20 tea bags
            // 9
            ['name' => 'عسل سدر حضرمي طبيعي', 'price' => 180.00, 'sale_price' => 160.00, 'category_name' => 'محليات طبيعية', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/2637922/pexels-photo-2637922.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'عسل سدر يمني فاخر.', 'description' => 'عسل سدر حضرمي طبيعي 100%، يتميز بقوامه الكثيف وطعمه الغني وفوائده الصحية العديدة.', 'unit_name' => 'Bottle', 'package_quantity' => 500, 'stock_level' => $faker->numberBetween(10, 40)], // 500 gram bottle
            // 10
            ['name' => 'ملح الهيمالايا الوردي الخشن', 'price' => 14.00, 'sale_price' => null, 'category_name' => 'منتجات الملح والفانيليا', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/1290139/pexels-photo-1290139.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'ملح صخري طبيعي.', 'description' => 'ملح الهيمالايا الوردي الخشن، غني بالمعادن، يستخدم في الطهي أو كملح للطاولة، يطحن قبل الاستخدام.', 'unit_name' => 'Pack', 'package_quantity' => 400, 'stock_level' => $faker->numberBetween(20, 70)],
            // 11
            ['name' => 'مسحوق القرفة السيلانية', 'price' => 25.00, 'sale_price' => 22.00, 'category_name' => 'بهارات مطحونة', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/3296279/pexels-photo-3296279.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'قرفة سيلانية أصلية.', 'description' => 'مسحوق القرفة السيلانية، تتميز بنكهتها الحلوة والرقيقة ورائحتها العطرية، مثالية للحلويات والمشروبات.', 'unit_name' => 'Gram', 'package_quantity' => 80, 'stock_level' => $faker->numberBetween(15, 50)],
            // 12
            ['name' => 'أوراق إكليل الجبل (روزماري) مجففة', 'price' => 16.00, 'sale_price' => null, 'category_name' => 'أعشاب مجففة', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/5696001/pexels-photo-5696001.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'روزماري عطري.', 'description' => 'أوراق إكليل الجبل المجففة، تضفي نكهة صنوبرية مميزة على أطباق اللحوم والدواجن والبطاطس المشوية.', 'unit_name' => 'Pack', 'package_quantity' => 40, 'stock_level' => $faker->numberBetween(20, 60)],
            // 13
            ['name' => 'قرنفل (مسمار) كامل', 'price' => 35.00, 'sale_price' => 30.00, 'category_name' => 'بهارات حب كاملة', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/47312/cloves-spice-seasoning-food-47312.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'قرنفل عطري للطهي.', 'description' => 'قرنفل كامل عالي الجودة، يستخدم في تتبيل اللحوم وتحضير المرق والحلويات والمشروبات الساخنة.', 'unit_name' => 'Gram', 'package_quantity' => 50, 'stock_level' => $faker->numberBetween(25, 75)],
            // 14
            ['name' => 'فلفل أحمر كايين حار بودرة', 'price' => 20.00, 'sale_price' => null, 'category_name' => 'فلفل وبابريكا', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/6716622/pexels-photo-6716622.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'فلفل كايين لمسة حارة.', 'description' => 'مسحوق فلفل أحمر كايين حار، يضيف حرارة متوسطة إلى قوية للأطباق، يستخدم بحذر.', 'unit_name' => 'Gram', 'package_quantity' => 60, 'stock_level' => $faker->numberBetween(10, 40)],
            // 15
            ['name' => 'بذور السمسم الأبيض المحمصة', 'price' => 18.00, 'sale_price' => 15.00, 'category_name' => 'بذور صحية', 'sub_category_name' => 'بذور السمسم', 'image' => 'https://images.pexels.com/photos/5703303/pexels-photo-5703303.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'سمسم محمص مقرمش.', 'description' => 'بذور السمسم الأبيض المحمصة، تضفي نكهة جوزية وقوامًا مقرمشًا على السلطات والمخبوزات والأطباق الآسيوية.', 'unit_name' => 'Pack', 'package_quantity' => 200, 'stock_level' => $faker->numberBetween(30, 100)],
            // 16
            ['name' => 'بهارات مشكلة للبرياني', 'price' => 32.00, 'sale_price' => null, 'category_name' => 'خلطات بهارات جاهزة', 'sub_category_name' => null, 'image' => 'https://img.freepik.com/free-photo/top-view-indian-spices-arrangement_23-2148748098.jpg?w=740', 'summary' => 'خلطة البرياني الهندي.', 'description' => 'خلطة بهارات مشكلة خاصة لتحضير أرز البرياني الهندي التقليدي بنكهته الغنية والمعقدة.', 'unit_name' => 'Pack', 'package_quantity' => 120, 'stock_level' => $faker->numberBetween(15, 55)],
            // 17
            ['name' => 'مسحوق الزنجبيل العطري', 'price' => 17.50, 'sale_price' => null, 'category_name' => 'بهارات مطحونة', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/7231693/pexels-photo-7231693.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'زنجبيل مطحون بنكهة قوية.', 'description' => 'مسحوق الزنجبيل العطري، يضيف نكهة حارة ودافئة للمأكولات والمشروبات والحلويات.', 'unit_name' => 'Gram', 'package_quantity' => 70, 'stock_level' => $faker->numberBetween(20, 70)],
            // 18
            ['name' => 'أزهار البابونج الكاملة', 'price' => 23.00, 'sale_price' => 20.00, 'category_name' => 'شاي وأعشاب للشرب', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/71313/chamomile-flowers-chamomile-meadow-of-chamomile-71313.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'بابونج للاسترخاء والنوم.', 'description' => 'أزهار البابونج الكاملة المجففة، مثالية لتحضير شاي مهدئ يساعد على الاسترخاء وتحسين جودة النوم.', 'unit_name' => 'Pack', 'package_quantity' => 30, 'stock_level' => $faker->numberBetween(25, 80)], // 30 gram pack
            // 19
            ['name' => 'دبس الرمان الطبيعي المركز', 'price' => 40.00, 'sale_price' => null, 'category_name' => 'محليات طبيعية', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/5945560/pexels-photo-5945560.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'دبس رمان حامض حلو.', 'description' => 'دبس الرمان الطبيعي المركز، يضيف نكهة حامضة حلوة مميزة للسلطات والمقبلات وتتبيلات اللحوم.', 'unit_name' => 'Bottle', 'package_quantity' => 300, 'stock_level' => $faker->numberBetween(10, 35)], // 300 ml bottle
            // 20
            ['name' => 'أعواد الفانيليا من مدغشقر', 'price' => 90.00, 'sale_price' => 75.00, 'category_name' => 'منتجات الملح والفانيليا', 'sub_category_name' => null, 'image' => 'https://images.pexels.com/photos/4110008/pexels-photo-4110008.jpeg?auto=compress&cs=tinysrgb&w=600', 'summary' => 'فانيليا طبيعية فاخرة.', 'description' => 'أعواد الفانيليا الطبيعية من مدغشقر، تتميز برائحتها الغنية ونكهتها الحلوة والكريمية، مثالية للحلويات والآيس كريم.', 'unit_name' => 'Piece', 'package_quantity' => 2, 'stock_level' => $faker->numberBetween(5, 25)], // Pack of 2 vanilla beans
        ];


        $createdCount = 0;
        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category_name'])->first();
            $subCategory = $productData['sub_category_name'] ? SubCategory::where('name', $productData['sub_category_name'])->where('category_id', optional($category)->id)->first() : null;
            $unit = Unit::where('name', $productData['unit_name'])->first();

            if (!$category) {
                $this->command->warn("Category '{$productData['category_name']}' not found for product '{$productData['name']}'. Skipping.");
                continue;
            }
            if ($productData['sub_category_name'] && !$subCategory) {
                $this->command->warn("SubCategory '{$productData['sub_category_name']}' under Category '{$productData['category_name']}' not found for product '{$productData['name']}'. Assigning to category only.");
                 // $subCategory = null; // Keep it null if not found
            }
            if (!$unit) {
                $this->command->warn("Unit '{$productData['unit_name']}' not found for product '{$productData['name']}'. Skipping.");
                continue;
            }

            Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'],
                'category_id' => $category->id,
                'sub_category_id' => optional($subCategory)->id,
                'image' => $productData['image'],
                'summary' => $productData['summary'],
                'description' => $productData['description'],
                'unit_id' => $unit->id,
                'quantity' => $productData['package_quantity'], // Represents the size/quantity of the package (e.g., 100g, 1 pack)
                'stock' => $productData['stock_level'],         // Represents how many such packages are in stock
                'status' => 1, // 1 for active
            ]);
            $createdCount++;
        }

        $this->command->info($createdCount . ' products seeded successfully.');
    }
}