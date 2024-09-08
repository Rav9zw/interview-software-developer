<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      Vehicle::insert([
            ['type' => 'motorcycle', 'size' => 1],
            ['type' => 'car', 'size' => 2],
            ['type' => 'bus', 'size' => 3],
        ]);
    }
}
