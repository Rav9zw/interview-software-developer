<?php

namespace Tests\Feature;

use App\Helpers\MessageHelper;
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
        ])->assertStatus(400)
            ->assertJson([
                'error' => MessageHelper::ERROR_PARKING_SPOT_OCCUPIED
            ]);;


        $sessions = ParkingSession::whereHas('parkingSpot', function ($query) use ($parkingSpot) {
            $query->where('spot_number', $parkingSpot->spot_number);
        })->get();

        $this->assertCount(1, $sessions);
    }


    public function testCanCreateSessionOnNotMatchingSize()
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
        ])->assertStatus(422)
            ->assertJson([
                'error' => MessageHelper::ERROR_PARKING_SPOT_DOES_NOT_MATCH_VEHICLE_TYPE
            ]);;;

        $sessions = ParkingSession::whereHas('parkingSpot', function ($query) use ($parkingSpot) {
            $query->where('spot_number', $parkingSpot->spot_number);
        })->get();
        $this->assertEmpty($sessions);

    }

    public function testCanCreateSessionWhenSpotDoesntExit()
    {
        $this->seed(VehicleSeeder::class);

        $vehicle = Vehicle::where('size', 2)->first();
        $this->postJson('/api/parking_lot/ticket', [
            'vehicle_type' => $vehicle->type,
            'spot_number' => '1.01',
            'email' => 'test@gmail.com'
        ])->assertStatus(404)
            ->assertJson([
                'error' => MessageHelper::ERROR_PARKING_SPOT_NOT_FOUND
            ]);;;

        $sessions = ParkingSession::whereHas('parkingSpot', function ($query) {
            $query->where('spot_number', '1.01');
        })->get();

        $this->assertEmpty($sessions);

    }


}
