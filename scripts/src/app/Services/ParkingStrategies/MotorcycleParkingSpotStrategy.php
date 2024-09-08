<?php

namespace App\Services\ParkingStrategies;

use App\Models\Vehicle;

class MotorcycleParkingSpotStrategy implements ParkingSpotStrategy
{

    protected $vehicle;

    public function __construct()
    {
        $this->vehicle = Vehicle::where('type', 'motorcycle')->first();
    }

    public function getAvailableSpotsForVehicleType($availableSpots): array
    {
        return array_values(array_filter($availableSpots, fn($spot) => $spot['spot_size'] == $this->vehicle->size));
    }
}
