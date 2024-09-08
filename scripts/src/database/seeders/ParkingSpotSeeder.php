<?php

namespace Database\Seeders;

use App\Models\ParkingSpot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingSpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       ParkingSpot::factory()->count(200)->create();
    }
}
