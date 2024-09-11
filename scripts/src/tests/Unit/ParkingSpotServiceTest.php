<?php

namespace tests\Unit;


use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use app\Services\ParkingSpotService;
use app\Services\ParkingStrategies\BusParkingSpotStrategy;
use app\Services\ParkingStrategies\CarParkingSpotStrategy;
use App\Services\ParkingStrategies\MotorcycleParkingSpotStrategy;
use Database\Seeders\VehicleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ParkingSpotServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $motorcycleStrategy;
    protected $carStrategy;
    protected $busStrategy;
    protected ParkingSpotService $parkingSpotService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->motorcycleStrategy = Mockery::mock(MotorcycleParkingSpotStrategy::class);
        $this->carStrategy = Mockery::mock(CarParkingSpotStrategy::class);
        $this->busStrategy = Mockery::mock(BusParkingSpotStrategy::class);

        $this->parkingSpotService = new ParkingSpotService(
            $this->motorcycleStrategy,
            $this->carStrategy,
            $this->busStrategy
        );
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

    public function testGetBoardWhenNoEmptySpots()
    {
        $this->motorcycleStrategy
            ->shouldReceive('getAvailableSpotsForVehicleType')
            ->andReturn([]);

        $this->carStrategy
            ->shouldReceive('getAvailableSpotsForVehicleType')
            ->andReturn([]);

        $this->busStrategy
            ->shouldReceive('getAvailableSpotsForVehicleType')
            ->andReturn([]);


        $board = $this->parkingSpotService->getBoard();
        $this->assertEmpty($board);
    }


}
