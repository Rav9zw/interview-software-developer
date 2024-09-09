<?php

namespace app\Services;

use App\Models\ParkingSpot;
use App\Services\ParkingStrategies\BusParkingSpotStrategy;
use App\Services\ParkingStrategies\CarParkingSpotStrategy;
use App\Services\ParkingStrategies\MotorcycleParkingSpotStrategy;

class ParkingSpotService
{
    private array $vehicleStrategies;

    public function __construct(MotorcycleParkingSpotStrategy $motorcycleStrategy,
                                CarParkingSpotStrategy        $carStrategy,
                                BusParkingSpotStrategy        $busStrategy)
    {
        $this->vehicleStrategies = [
            'motorcycle' => $motorcycleStrategy,
            'car' => $carStrategy,
            'bus' => $busStrategy,
        ];
    }

    public function getBoard(): array
    {
        $board = [];

        foreach ($this->getAvailableByType() as $type => $spots) {
            foreach ($spots as $spot) {
                $floor = 'Floor: ' . $spot['floor'];
                if (!isset($board[$floor])) {
                    $vehicleTypes = array_keys($this->vehicleStrategies);
                    $board[$floor] = array_fill_keys($vehicleTypes, 0);
                }

                $board[$floor][$type]++;
            }
        }

        return $board;
    }

    private function getAvailableByType(): array
    {
        $availableSpots = $this->getAllAvailableSpots()->toArray();
        $board = [];

        foreach ($this->vehicleStrategies as $vehicleType => $vehicleStrategy) {
            $board[$vehicleType] = $vehicleStrategy->getAvailableSpotsForVehicleType($availableSpots);
        }

        return $board;
    }

    private function getAllAvailableSpots()
    {
        return ParkingSpot::whereDoesntHave('parkingSession', function ($query) {
            $query->whereNull('end_time');
        })->get();
    }


    public function checkAvailabilityByNumber($spotNumber)
    {
        return ParkingSpot::where('spot_number', $spotNumber)
            ->whereDoesntHave('parkingSession', function ($query) {
                $query->where('end_time', '>', now());
            })->exists();
    }

}

