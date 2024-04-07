<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RestaurantReservationController extends Controller
{
    const RESERVATION_TIME_IN_MINS = 199;

    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

        $reservations = $restaurant->reservations()->with(['clients', 'tables'])->get();

        return response()->json([
            'reservations' => $reservations
        ]);
    }


    public function store(Request $request, $restaurantId)
    {
        $validator = Validator::make($request->all(), [
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i',
            'reserver_name' => 'required|string|max:255',
            'reserver_email' => 'required|string|email|max:255',
            'reserver_phone' => 'required|string|max:255',
            'clients' => 'nullable|array',
            'clients.*.name' => 'required|string|max:255',
            'clients.*.email' => 'required|string|email|max:255',
            'clients.*.phone' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $restaurant = Restaurant::findOrFail($restaurantId);
        $reservationDateTime = Carbon::parse($request->reservation_time . ' ' . $request->reservation_date);

        $clients = $request->input('clients', []);

        // + reserver
        $clientsToSit = count($request->clients) + 1;

        $availableTables = Table::where('restaurant_id', $restaurantId)
        ->whereDoesntHave('reservations', function ($query) use ($reservationDateTime) {
            $twoHoursBefore = $reservationDateTime->copy()->subMinutes(self::RESERVATION_TIME_IN_MINS);
            $twoHoursAfter = $reservationDateTime->copy()->addMinutes(self::RESERVATION_TIME_IN_MINS);
    
            $query->whereBetween('reservation_time', [$twoHoursBefore, $twoHoursAfter]);
        })
        ->orderBy('capacity', 'asc') 
        ->get();

        if ($availableTables->sum('capacity') >= $clientsToSit) {
            $availableTables = $availableTables->toArray();

            while ($clientsToSit > 0) {
                $perfectTableKey = false;

                foreach ($availableTables as $availableTableKey => $availableTable) {
                    $perfectTableKey = $availableTableKey;

                    if ($availableTable['capacity'] >= $clientsToSit) {
                        break;
                    }
                }

                $reservationTables[] = $availableTables[$perfectTableKey]['id'];
                $clientsToSit -= $availableTables[$perfectTableKey]['capacity'];

                unset($availableTables[$perfectTableKey]);
            }

            // Create reserver as client
            $reserver = Client::firstOrCreate(
                [
                    'email' => $request['reserver_email']
                ],
                [
                    'name' => $request['reserver_name'],
                    'email' => $request['reserver_email'],
                    'phone' => $request['reserver_phone'],
                ]
            );
            
            // Associate clients with the reservation
            foreach ($clients as $clientData) {
                $client = Client::firstOrCreate(['email' => $clientData['email']], $clientData);
                $reservationClients[] = $client->id;
            }

            // Create reservation
            $reservation = new Reservation();
            $reservation->reservation_time = $reservationDateTime;
            $reservation->reserver_client_id = $reserver->id;

            $restaurant->reservations()->save($reservation);

            $reservation->tables()->attach($reservationTables);
            $reservation->clients()->attach($reservationClients);

            return response()->json([
                'reservation' => $reservation
            ], 201);
        } else {
            return response()->json([
               'message' => 'Not enough tables available',
            ], 422);
        }
    }
}