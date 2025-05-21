<?php

namespace Database\Seeders;

use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        Driver::create([
            'name' => 'ALWRIFI',
            'phone' => '770252634',
            'password' => Hash::make('123123123'), // Default password
            'salary' => fake()->randomFloat(2, 1000, 5000),
            'image' => 'https://picsum.photos/400/300?random=1', // Default image path
            'identity_image' => 'https://picsum.photos/400/300?random=1',
            'identity_number' => fake()->bothify('??########'),
            'iban' => fake()->iban('SA'), // Saudi IBAN format
            'has_order' => fake()->boolean(),
            'address' => fake()->address(),
            'status' => 'active',
        ]);
    }
}