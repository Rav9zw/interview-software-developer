<?php

namespace app\Services;

use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use App\Models\Vehicle;
use App\Services\ParkingStrategies\BusParkingSpotStrategy;
use App\Services\ParkingStrategies\CarParkingSpotStrategy;
use App\Services\ParkingStrategies\MotorcycleParkingSpotStrategy;
use Illuminate\Support\Facades\Log;

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


    public function createSession($request): array
    {
        $parkingSpot = ParkingSpot::where('spot_number', $request->spot_number)->firstOrFail();
        $vehicle = Vehicle::where('type', $request->vehicle_type)->firstOrFail();

        try {
            $ticket = ParkingSession::create([
                'vehicle_id' => $vehicle->id,
                'parking_spot_id' => $parkingSpot->id,
                'email' => $request->get('email'),
                'start_time' => now(),
                'end_time' => now()->modify('+1 hour'),
            ]);

            return ['id' => $ticket->id, 'to_pay' => 0];
        } catch (\Exception $e) {
            Log::error('Session Create error: ' . $e->getMessage());
            return ['error' => 'Error creating parking session', 'status' => 500];
        }
    }

    public function getAvailableByType(): array
    {
        $availableSpots = $this->getAllAvailableSpots()->toArray();
        $board = [];

        foreach ($this->vehicleStrategies as $vehicleStrategy) {
            $board[$vehicleStrategy->getName()] = $vehicleStrategy->getAvailableSpotsForVehicleType($availableSpots);
        }

        return $board;
    }

    public function getAllAvailableSpots()
    {
        return ParkingSpot::whereDoesntHave('parkingSession', function ($query) {
            $query->where('end_time', '>', now());
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

