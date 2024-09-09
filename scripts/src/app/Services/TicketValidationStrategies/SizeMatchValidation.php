<?php

namespace app\Services\TicketValidationStrategies;

use App\Http\Requests\CreateTicketRequest;
use App\Models\ParkingSpot;
use App\Models\Vehicle;


class SizeMatchValidation implements ValidationStrategy
{
    public function validate(CreateTicketRequest $request): array
    {
        $parkingSpot = ParkingSpot::where('spot_number', $request->spot_number)->firstOrFail();
        $vehicle = Vehicle::where('type', $request->vehicle_type)->firstOrFail();

        if ($parkingSpot->spot_size !== $vehicle->size) {
            return ['error' => 'Parking spot does not match the Vehicle type.', 'status' => 422];
        }
        return [];
    }
}
