<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = FakerFactory::create('ar_SA'); 

        User::create([
            'name' => 'Admin User',
            'phone' => '0501234567',
            'email' => 'admin@spiceshop.com',
            'password' => Hash::make('password'), 
            'status' => 1, 
            'address' => '123 Main Street, Riyadh, Saudi Arabia',
            'latitude' => $faker->latitude(24.5, 24.9),
            'longitude' => $faker->longitude(46.5, 46.9),
            'email_verified_at' => now(),
        ]);

        for ($i = 0; $i < 25; $i++) {
            User::create([
                'name' => $faker->name,
                'phone' => $faker->unique()->numerify('05########'),
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('userpass'), 
                'status' => $faker->boolean(90), 
                'address' => $faker->address,
                'latitude' => $faker->latitude(24.0, 25.0), 
                'longitude' => $faker->longitude(46.0, 47.0),
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('1 admin and 25 general users seeded successfully.');
    }
}