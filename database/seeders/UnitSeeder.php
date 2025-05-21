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
            ['name' => 'Pack'],
            ['name' => 'Bottle'],
            ['name' => 'Box'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        $this->command->info('Units seeded successfully.');
    }
}