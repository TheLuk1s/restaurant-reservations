<?php

namespace App\Interfaces;

interface ReservationTablesFinderServiceInterface
{
    public function findTablesForReservation(array $clients, \Carbon\Carbon $dateTime, int $restaurantId): array;
}
