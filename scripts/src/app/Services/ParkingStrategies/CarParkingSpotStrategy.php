<?php

namespace app\Services\ParkingStrategies;

use App\Models\Vehicle;

class CarParkingSpotStrategy implements ParkingSpotStrategy
{
    protected $vehicle;
    const NAME = 'car';
    public function __construct()
    {
        $this->vehicle = Vehicle::where('type', self::NAME)->first();
    }

    public function getAvailableSpotsForVehicleType($availableSpots): array
    {
        return array_values(array_filter($availableSpots, fn($spot) => $spot['spot_size'] == $this->vehicle->size));
    }

    public function getName(): string
    {
        return self::NAME;
    }

}
