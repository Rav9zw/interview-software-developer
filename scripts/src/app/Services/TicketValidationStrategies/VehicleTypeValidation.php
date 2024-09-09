<?php

namespace App\Services\TicketValidationStrategies;


use App\Http\Requests\CreateTicketRequest;
use App\Models\Vehicle;

class VehicleTypeValidation implements ValidationStrategy
{
    public function validate(CreateTicketRequest $request): array
    {
        $vehicle = Vehicle::where('type', $request->vehicle_type)->first();
        if (!$vehicle) {
            return ['error' => 'Vehicle type not found.', 'status' => 404];
        }
        return [];

    }
}
