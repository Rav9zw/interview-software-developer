<?php

namespace tests\Unit;


use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use app\Services\ParkingSpotService;
use Database\Seeders\VehicleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ParkingSpotServiceTest extends TestCase
{
    use RefreshDatabase;

    private ParkingSpotService $parkingSpotService;

    protected function setUp(): void
    {
        parent::setUp();

        $motorcycleStrategy = Mockery::mock('App\Services\ParkingStrategies\MotorcycleParkingSpotStrategy');
        $carStrategy = Mockery::mock('App\Services\ParkingStrategies\CarParkingSpotStrategy');
        $busStrategy = Mockery::mock('App\Services\ParkingStrategies\BusParkingSpotStrategy');

        $this->parkingSpotService = new ParkingSpotService($motorcycleStrategy, $carStrategy, $busStrategy);

    }

    public function testCheckAvailabilityByNumberSessionExist()
    {
        $this->seed(VehicleSeeder::class);
        $parkingSpot = ParkingSpot::factory()->create([
            'spot_number' => '0.01',
            'spot_size' => 1,
        ]);


        ParkingSession::factory()->create([
            'parking_spot_id' => $parkingSpot->id,
            'start_time' => now()->sub('30 minutes'),
            'end_time' => now()->add('30 minutes'),
        ]);

        $this->assertFalse($this->parkingSpotService->checkAvailabilityByNumber('0.01'));

    }

    public function testCheckAvailabilityByNumberSessionDoesntExist()
    {
        $this->seed(VehicleSeeder::class);
        ParkingSpot::factory()->create([
            'spot_number' => '0.02',
            'spot_size' => 1,
        ]);

        $this->assertTrue($this->parkingSpotService->checkAvailabilityByNumber('0.02'));
    }

}
