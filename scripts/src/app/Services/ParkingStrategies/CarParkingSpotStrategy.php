<?php

namespace app\Services\ParkingStrategies;

use App\Models\Vehicle;

class CarParkingSpotStrategy implements ParkingSpotStrategy
{
    protected $vehicle;

    public function __construct()
    {
        $this->vehicle = Vehicle::where('type', 'car')->first();
    }

    public function getAvailableSpotsForVehicleType($availableSpots): array
    {
        return array_values(array_filter($availableSpots, fn($spot) => $spot['spot_size'] == $this->vehicle->size));
    }
}
