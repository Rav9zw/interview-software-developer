<?php

namespace app\Services\ParkingStrategies;

use App\Models\Vehicle;

class BusParkingSpotStrategy implements ParkingSpotStrategy
{

    protected $vehicle;

    public function __construct()
    {
        $this->vehicle = Vehicle::where('type', 'bus')->first();
    }

    public function getAvailableSpotsForVehicleType($availableSpots): array
    {
        return array_values(array_filter($availableSpots, fn($spot) => $spot['spot_size'] == $this->vehicle->size));
    }
}
