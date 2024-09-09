<?php

namespace Tests\Feature;

use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use App\Models\Vehicle;
use Database\Seeders\VehicleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testCanCreateSessionForVehicle()
    {
        $this->seed(VehicleSeeder::class);
        $parkingSpot = ParkingSpot::factory()->create([
            'spot_number' => '0.01',
            'spot_size' => 1,
        ]);


        $vehicle = Vehicle::first();
        $this->postJson('/api/parking_lot/ticket', [
            'vehicle_type' => $vehicle->type,
            'spot_number' => $parkingSpot->spot_number,
            'email' => 'test@gmail.com'
        ])->assertStatus(200);

        $sessions = ParkingSession::whereHas('parkingSpot', function ($query) use ($parkingSpot) {
            $query->where('spot_number', $parkingSpot->spot_number);
        })->get();

        $this->assertNotEmpty($sessions);

    }

    public function testCanCreateMultipleSessionForSameSpot()
    {
        $this->seed(VehicleSeeder::class);
        $parkingSpot = ParkingSpot::factory()->create([
            'spot_number' => '0.01',
            'spot_size' => 1,
        ]);

        $vehicle = Vehicle::where('size', 1)->first();

        $this->postJson('/api/parking_lot/ticket', [
            'vehicle_type' => $vehicle->type,
            'spot_number' => $parkingSpot->spot_number,
            'email' => 'test@gmail.com'
        ])->assertStatus(200);

        $this->postJson('/api/parking_lot/ticket', [
            'vehicle_type' => $vehicle->type,
            'spot_number' => $parkingSpot->spot_number,
            'email' => 'test@gmail.com'
        ])->assertStatus(400);


        $sessions = ParkingSession::whereHas('parkingSpot', function ($query) use ($parkingSpot) {
            $query->where('spot_number', $parkingSpot->spot_number);
        })->get();

        $this->assertCount(1, $sessions);
    }


    public function testCanCreateSessionForVehicleOnNotMatchingSize()
    {
        $this->seed(VehicleSeeder::class);
        $parkingSpot = ParkingSpot::factory()->create([
            'spot_number' => '0.01',
            'spot_size' => 1,
        ]);


        $vehicle = Vehicle::where('size', 2)->first();
        $this->postJson('/api/parking_lot/ticket', [
            'vehicle_type' => $vehicle->type,
            'spot_number' => $parkingSpot->spot_number,
            'email' => 'test@gmail.com'
        ])->assertStatus(422);

        $sessions = ParkingSession::whereHas('parkingSpot', function ($query) use ($parkingSpot) {
            $query->where('spot_number', $parkingSpot->spot_number);
        })->get();

        $this->assertEmpty($sessions);

    }

}
