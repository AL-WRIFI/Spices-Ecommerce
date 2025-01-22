<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    
    public function run(): void
    {
        $units = [
            ['name' => 'Piece'],
            ['name' => 'Kilogram'],
            ['name' => 'Gram'],
            ['name' => 'Liter'],
            ['name' => 'Milliliter'],
            ['name' => 'Meter'],
            ['name' => 'Centimeter'],
            ['name' => 'Dozen'],
            ['name' => 'Pack'],
            ['name' => 'Box'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        $this->command->info('Units seeded successfully.');
    }
}