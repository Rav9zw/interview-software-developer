<?php

namespace app\Services\ParkingStrategies;

interface ParkingSpotStrategy
{
    public function getAvailableSpotsForVehicleType($availableSpots);

    public function getName(): string;
}
