<?php


namespace App\Services;

use App\Models\Reservation;
use App\Models\Restaurant;
use App\Dto\ReservationDto;
use App\Dto\ClientDto;
use Carbon\Carbon;
use App\Services\ReservationTablesFinderService;

class ReservationCreationService
{
    public function createReservation(
        ReservationDto $reservation,
        $restaurantId,
    ) {
        $restaurant = Restaurant::findOrFail($restaurantId);

        // Handle date > now
        $reservationDateTime = Carbon::parse($reservation->reservationDate . ' ' . $reservation->reservationTime);

        $reservationTablesIds = app(ReservationTablesFinderService::class)->findTablesForReservation(
            $reservation->clients, 
            $reservationDateTime,
            $restaurantId
        );

        if (!empty($reservationTablesIds)) {
            $reserver = new ClientDto(
                $reservation->reserverName,
                $reservation->reserverEmail,
                $reservation->reserverPhone
            );
            
            $reserverId = app(ClientCreationService::class)->createClient($reserver, $restaurantId);
            $reservationClientIds = app(ClientCreationService::class)->createClients($reservation->clients, $restaurantId);
    
            $reservation = $restaurant->reservations()->create([
                'reservation_time' => $reservationDateTime,
                'reserver_client_id' => $reserverId
            ]);
    
            $reservation->tables()->attach($reservationTablesIds);
            $reservation->clients()->attach($reservationClientIds);

            return $reservation;
        }

        return false;
    }
}