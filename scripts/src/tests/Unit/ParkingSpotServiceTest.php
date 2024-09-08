<?php

namespace tests\Unit;

use App\Models\ParkingSpot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParkingSpotServiceTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }


    public function testSpotAvailableWhenNoSessionExists()
    {
        // Tworzymy miejsce parkingowe
        $spot = ParkingSpot::create([
            'spot_number' => '1.01',
            'spot_size' => 2,
            'floor' => 1,
        ]);

        $service = new \App\Services\ParkingSpotService(); // Zmień na właściwą ścieżkę do Twojego serwisu

        $this->assertTrue($service->checkAvailabilityByNumber('1.01'));
    }
}
