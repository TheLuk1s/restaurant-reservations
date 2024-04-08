<?php

namespace App\Dto;

use Illuminate\Http\Request;
use App\Dto\ClientDto;

class ReservationDto {
    public function __construct(
        public readonly string $reservationDate,
        public readonly string $reservationTime,
        public readonly string $reserverName,
        public readonly string $reserverEmail,
        public readonly string $reserverPhone,
        public readonly array $clients
    ) {}

    public static function fromRequest(Request $request): ReservationDto
    {
        return new self(
            $request->reservation_date,
            $request->reservation_time,
            $request->reserver_name,
            $request->reserver_email,
            $request->reserver_phone,
            array_map(function ($client) {
                return new ClientDto(
                    $client['name'],
                    $client['email'],
                    $client['phone']
                );
            }, $request->clients)
        );
    }
}