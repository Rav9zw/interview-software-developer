<?php

namespace App\Services\TicketValidationStrategies;

use App\Http\Requests\CreateTicketRequest;
use App\Models\ParkingSpot;
use App\Helpers\MessageHelper;
class SpotNumberValidation implements ValidationStrategy
{
    public function validate(CreateTicketRequest $request): array
    {
        $parkingSpot = ParkingSpot::where('spot_number', $request->spot_number)->first();
        if (!$parkingSpot) {
            return ['error' => MessageHelper::ERROR_PARKING_SPOT_NOT_FOUND, 'status' => 404];
        }
        return [];
    }
}
