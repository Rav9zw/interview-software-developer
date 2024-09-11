<?php

namespace App\Services\TicketValidationStrategies;

use App\Http\Requests\CreateTicketRequest;
use App\Services\ParkingSpotService;
use App\Helpers\MessageHelper;

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
            return ['error' => MessageHelper::ERROR_PARKING_SPOT_OCCUPIED, 'status' => 400];
        }
        return [];
    }
}
