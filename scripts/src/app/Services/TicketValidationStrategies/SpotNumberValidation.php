<?php

namespace App\Services\TicketValidationStrategies;

use App\Http\Requests\CreateTicketRequest;
use App\Models\ParkingSpot;

class SpotNumberValidation implements ValidationStrategy
{
    public function validate(CreateTicketRequest $request): array
    {
        $parkingSpot = ParkingSpot::where('spot_number', $request->spot_number)->first();
        if (!$parkingSpot) {
            return ['error' => 'Parking spot not found.', 'status' => 404];
        }
        return [];
    }
}
