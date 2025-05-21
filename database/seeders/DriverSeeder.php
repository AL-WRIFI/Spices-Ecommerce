<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $faker = FakerFactory::create('ar_SA');

        $driverNames = [
            'خالد الأحمد', 'سلطان المحمد', 'فهد العبدالله', 'يوسف التركي', 'ماجد الغامدي', 'عمر الزهراني'
        ];
        if (count($driverNames) !== 6) {
            $this->command->error('Driver names array does not contain exactly 6 names. Adjust it.');
            return;
        }

        foreach ($driverNames as $name) {
            Driver::create([
                'name' => $name,
                'phone' => $faker->unique()->numerify('05########'),
                'password' => Hash::make('driverpass'),
                'salary' => $faker->randomFloat(2, 3500, 7000),
                'image' => 'https://picsum.photos/200/200?random=' . rand(700, 800),
                'identity_image' => 'https://picsum.photos/300/200?random=' . rand(801, 900),
                'identity_number' => $faker->numerify('1#########'),
                'iban' => 'SA' . $faker->numerify('######################'),
                'has_order' => $faker->boolean(25),
                'address' => $faker->address,
                'status' => 1,
                // 'country_id' => 1, // اضبط حسب معرف الدولة لديك
                // 'region_id' => $faker->numberBetween(1, 5),
                // 'city_id' => $faker->numberBetween(1, 10),
                // 'district_id' => $faker->numberBetween(1, 20),
            ]);
        }
        $this->command->info(count($driverNames) . ' drivers seeded successfully.');
    }
}