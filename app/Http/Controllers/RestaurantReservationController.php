<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Reservation;
use App\Dto\ReservationDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ReservationCreationService;
use App\Interfaces\ReservationCreationServiceInterface;

class RestaurantReservationController extends Controller
{
    protected $reservationCreationService;

    public function __construct(
        ReservationCreationServiceInterface $reservationCreationService
    ) {
        $this->reservationCreationService = $reservationCreationService;
    }

    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

        $reservations = $restaurant->reservations()->with(['clients', 'tables'])->get();

        return response()->json($reservations);
    }

    public function show($restaurantId, $reservationId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

        $reservation = $restaurant->reservations()->with(['clients', 'tables'])->findOrFail($reservationId);

        return response()->json($reservation);
    }

    public function store(Request $request, $restaurantId)
    {
        $validator = Validator::make(
            array_merge(
                $request->all(),
                ['restaurant_id' => $restaurantId]
            ),
            Reservation::$validationRules
        );

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $reservation = ReservationDto::fromRequest($request);
        $reservation = app(ReservationCreationService::class)->createReservation($reservation, $restaurantId);

        if ($reservation) {
            return response()->json($reservation, 201);
        }

        return response()->json([
            'message' => 'No tables available for specified time',
        ], 422);
    }

    public function destroy($restaurantId, $reservationId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $reservation = $restaurant->reservations()->findOrFail($reservationId);

        $reservation->delete();

        return response()->json(null, 204);
    }
}
