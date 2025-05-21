<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
          CountriesTableSeeder::class,
          RegionsTableSeeder::class,
          CitiesTableSeeder::class,
          DistrictsTableSeeder::class,
        ]);

        $this->call([
            UnitSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            ProductSeeder::class,
            UserSeeder::class,
            DriverSeeder::class,
            OrderSeeder::class,
        ]);

        $this->command->info('All specified database tables seeded successfully with exact counts!');
    }
}