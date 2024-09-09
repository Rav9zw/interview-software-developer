<?php

namespace app\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use App\Models\Vehicle;
use App\Notifications\ParkingSessionEnding;
use app\Services\ParkingSpotService;
use App\Services\TicketValidatorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class ParkingLotController extends Controller
{
    private ParkingSpotService $parkingSpotService;
    private TicketValidatorService $ticketValidatorService;


    public function __construct(ParkingSpotService $parkingSpotService, TicketValidatorService $ticketValidatorService
    )
    {
        $this->parkingSpotService = $parkingSpotService;
        $this->ticketValidatorService = $ticketValidatorService;


    }

    public function getParkingBoard(): JsonResponse
    {
        return response()->json($this->parkingSpotService->getBoard(), 200);
    }


    public function createTicket(CreateTicketRequest $request): JsonResponse
    {
        $result =$this->ticketValidatorService->validateTicket($request);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }
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


        } catch (\Exception $e) {
            Log::error('Session Create error: ' . $e->getMessage());
            return response()->json(['error' => 'Error creating parking session'], 500);
        }

        $response = [
            'id' => $ticket->id,
            'to_pay' => 0
        ];
        return response()->json($response);
    }

    public function notifyEndingSessions(): JsonResponse
    {
        $endingSessions = ParkingSession::where('end_time', '<', now()->addMinutes(15))
            ->where('end_time', '<', now())
            ->get();

        foreach ($endingSessions as $session) {
            $session->notify(new ParkingSessionEnding($session));
        }

        return response()->json(['message' => 'Notifications sent']);
    }

}
