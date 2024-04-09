<?php

namespace App\Interfaces;

use App\Dto\ReservationDto;

interface ReservationCreationServiceInterface
{
    public function createReservation(ReservationDto $reservation, int $restaurantId): mixed;
}
