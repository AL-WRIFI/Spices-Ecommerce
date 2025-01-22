<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
      // User::factory(3)->create();

      User::factory()->create([
        'name' => 'Test User',
        'phone' => '770252634',
        'email' => 'test@example.com',
      ]);

      $this->call([
        CategorySeeder::class,
        SubCategorySeeder::class,
        UnitSeeder::class,
      ]);

      $this->call(ProductSeeder::class);

  }
}
