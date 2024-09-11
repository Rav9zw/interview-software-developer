<?php

namespace app\Services\TicketValidationStrategies;

use App\Http\Requests\CreateTicketRequest;
use App\Models\ParkingSpot;
use App\Models\Vehicle;
use App\Helpers\MessageHelper;

class SizeMatchValidation implements ValidationStrategy
{
    public function validate(CreateTicketRequest $request): array
    {
        $parkingSpot = ParkingSpot::where('spot_number', $request->spot_number)->firstOrFail();
        $vehicle = Vehicle::where('type', $request->vehicle_type)->firstOrFail();

        if ($parkingSpot->spot_size !== $vehicle->size) {
            return ['error' => MessageHelper::ERROR_PARKING_SPOT_DOES_NOT_MATCH_VEHICLE_TYPE, 'status' => 422];
        }
        return [];
    }
}
