<?php

namespace Tests\Feature;

use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use App\Models\Vehicle;
use Database\Seeders\VehicleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisplayBoardTest extends TestCase
{
    use RefreshDatabase;

    public function testCanDisplayParkingBoard(): void
    {
        $this->seed();
        $response = $this->get('/api/parking_lot/board');

        $response->assertStatus(200);
    }

    public function testDisplayParkingBoardStructure()
    {
        $this->seed();
        $response = $this->getJson('/api/parking_lot/board');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'motorcycle',
                    'car',
                    'bus'
                ]
            ]);
    }

    public function testItReturnsCorrectBoardOfAvailableSpots()
    {
        $this->seed(VehicleSeeder::class);
        $this->prepareDataBaseForBoardCheck();
        $expectedBoard = [
            'Floor: 0' => [
                'motorcycle' => 9,
                'car' => 20,
                'bus' => 10
            ],
            'Floor: 1' => [
                'motorcycle' => 10,
                'car' => 20,
                'bus' => 10
            ],
        ];
        $response = $this->getJson('/api/parking_lot/board');

        $this->assertEquals($expectedBoard, $response->json());
    }


    private function prepareDataBaseForBoardCheck(): void
    {

        $floorCount = 2;
        $spotsPerFloor = 50;
        $spotsPerBatch = 50;

        for ($floor = 0; $floor < $floorCount; $floor++) {
            for ($i = 0; $i < $spotsPerBatch; $i++) {
                $spotNumber = ($i % $spotsPerFloor) + 1;

                $spot_size = 0;

                switch (true) {
                    case $i < 10:
                        $spot_size = 1;
                        break;
                    case $i < 30;
                        $spot_size = 2;
                        break;
                    case $i < 40;
                        $spot_size = 3;
                }
                ParkingSpot::factory()->create([
                    'spot_number' => sprintf('%d.%02d', $floor, $spotNumber),
                    'floor' => $floor,
                    'spot_size' => $spot_size
                ]);
            }
        }


        $parkingSpot = ParkingSpot::where('spot_size', 1)->inRandomOrder()->first();
        $vehicle = Vehicle::where('size', 1)->first();
        $start = now();
        $end = clone $start;
        $end->modify('+1 hour');

        ParkingSession::factory()->create([
            'vehicle_id' => $vehicle->id,
            'parking_spot_id' => $parkingSpot->id,
            'email' => 'test@test.com',
            'start_time' => $start,
            'end_time' => $end,
        ]);


    }


}
