<?php

namespace App\Services;

use Carbon\Carbon;
use App\Dto\ClientDto;
use App\Models\Restaurant;
use App\Dto\ReservationDto;
use App\Interfaces\ClientCreationServiceInterface;
use App\Interfaces\ReservationCreationServiceInterface;
use App\Interfaces\ReservationTablesFinderServiceInterface;

class ReservationCreationService implements ReservationCreationServiceInterface
{
    protected $clientCreationService;
    protected $reservationTablesFinderService;

    public function __construct(
        ClientCreationServiceInterface $clientCreationService,
        ReservationTablesFinderServiceInterface $reservationTablesFinderService
    ) {
        $this->clientCreationService = $clientCreationService;
        $this->reservationTablesFinderService = $reservationTablesFinderService;
    }

    public function createReservation(
        ReservationDto $reservation,
        $restaurantId
    ): mixed {
        $restaurant = Restaurant::findOrFail($restaurantId);

        // Handle date > now
        $reservationDateTime = Carbon::parse($reservation->reservationDate . ' ' . $reservation->reservationTime);

        $reservationTablesIds = $this->reservationTablesFinderService->findTablesForReservation(
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

            $reserverId = $this->clientCreationService->createClient($reserver, $restaurantId);
            $reservationClientIds = $this->clientCreationService->createClients(
                $reservation->clients,
                $restaurantId
            );

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
