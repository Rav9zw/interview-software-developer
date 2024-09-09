<?php

namespace App\Services\TicketValidationStrategies;

use App\Http\Requests\CreateTicketRequest;
use App\Services\ParkingSpotService;

class AvailabilityValidation implements ValidationStrategy
{
    private ParkingSpotService $parkingSpotService;

    public function __construct(ParkingSpotService $parkingSpotService)
    {
        $this->parkingSpotService = $parkingSpotService;
    }

    public function validate(CreateTicketRequest $request): array
    {
        $isAvailable = $this->parkingSpotService->checkAvailabilityByNumber($request->spot_number);
        if (!$isAvailable) {
            return ['error' => 'Parking spot is already occupied.', 'status' => 400];
        }
        return [];
    }
}
