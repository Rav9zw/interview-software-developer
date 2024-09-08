<?php

namespace app\Http\Controllers;

use App\Models\ParkingSession;
use App\Models\ParkingSpot;
use App\Models\Vehicle;
use App\Notifications\ParkingSessionEnding;
use app\Services\ParkingSpotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ParkingLotController extends Controller
{
    private ParkingSpotService $parkingSpotService;

    public function __construct(ParkingSpotService $parkingSpotService)
    {
        $this->parkingSpotService = $parkingSpotService;
    }

    public function getParkingBoard(): JsonResponse
    {
        return response()->json($this->parkingSpotService->getBoard(), 200);
    }

    public function createTicket(Request $request): JsonResponse
    {

        $validTypes = Vehicle::pluck('type')->toArray();

        $validator = Validator::make($request->all(), [
            'vehicle_type' => ['required', Rule::in($validTypes)],
            'spot_number' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $parkingSpot = ParkingSpot::where('spot_number', $request->spot_number)
            ->first();

        if (!$parkingSpot) {
            return response()->json(['error' => 'Parking spot not found.'], 404);
        }

        $isAvailable = $this->parkingSpotService->checkAvailabilityByNumber($request->spot_number);

        if (!$isAvailable) {
            return response()->json(['error' => 'Parking spot is already occupied.'], 400);
        }

        $vehicle = Vehicle::where('type', $request->vehicle_type)->first();

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle type not found.'], 404);
        }

        $ticket = ParkingSession::create([
            'vehicle_id' => $vehicle->id,
            'parking_spot_id' => $parkingSpot->id,
            'start_time' => now(),
            'end_time' => now()->addHour(),
        ]);

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
