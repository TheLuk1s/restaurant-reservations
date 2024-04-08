<?php

namespace App\Services;

use App\Models\Table;
use App\Dto\ReservationDto;
use Carbon\Carbon;

class ReservationTablesFinderService
{
    const RESERVATION_TIME_IN_MINS = 119;

    public function findTablesForReservation($clients, $dateTime, $restaurantId) : array
    {
        $reservationTables = [];

        $clientsToSit = count($clients) + 1;

        $availableTables = $this->getAvailableTables($restaurantId, $dateTime);

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
        }

        return $reservationTables;
    }

    protected function getAvailableTables($restaurantId, $reservationDateTime) {
        return Table::where('restaurant_id', $restaurantId)
        ->whereDoesntHave('reservations', function ($query) use ($reservationDateTime) {
            $twoHoursBefore = $reservationDateTime->copy()->subMinutes(self::RESERVATION_TIME_IN_MINS);
            $twoHoursAfter = $reservationDateTime->copy()->addMinutes(self::RESERVATION_TIME_IN_MINS);
    
            $query->whereBetween('reservation_time', [$twoHoursBefore, $twoHoursAfter]);
        })
        ->orderBy('capacity', 'asc') 
        ->get();
    }
}