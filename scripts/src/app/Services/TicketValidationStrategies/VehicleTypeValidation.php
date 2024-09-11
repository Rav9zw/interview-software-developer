<?php

namespace App\Services\TicketValidationStrategies;


use App\Http\Requests\CreateTicketRequest;
use App\Models\Vehicle;
use App\Helpers\MessageHelper;

class VehicleTypeValidation implements ValidationStrategy
{
    public function validate(CreateTicketRequest $request): array
    {
        $vehicle = Vehicle::where('type', $request->vehicle_type)->first();
        if (!$vehicle) {
            return ['error' => MessageHelper::ERROR_VEHICLE_TYPE_NOT_FOUND, 'status' => 404];
        }
        return [];

    }
}
