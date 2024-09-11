<?php

namespace app\Http\Controllers;

use App\Helpers\MessageHelper;
use App\Http\Requests\CreateTicketRequest;
use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use App\Models\Vehicle;
use App\Notifications\ParkingSessionEnding;
use app\Services\ParkingSpotService;
use App\Services\TicketValidatorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


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
        $result = $this->ticketValidatorService->validateTicket($request);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        $response = $this->parkingSpotService->createSession($request);

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], $response['status']);
        }

        return response()->json($response);
    }

    public function notifyEndingSessions(): JsonResponse
    {
        $endingSessions = ParkingSession::where('end_time', '>', now())
            ->where('end_time', '<=', now()->addMinutes(15))
            ->where('notification_sent', false)
            ->get();

        foreach ($endingSessions as $session) {
            $session->notify(new ParkingSessionEnding($session));

            $session->notification_sent = true;
            $session->save();
        }

        return response()->json(['message' => MessageHelper::NOTIFICATION_SEND]);
    }

}
