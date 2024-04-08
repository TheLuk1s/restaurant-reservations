<?php

namespace App\Services;

use App\Models\Client;
use App\Dto\ClientDto;

class ClientCreationService
{
    public function createClients(
        array $clients
    ) {
        $clientIds = [];

        foreach ($clients as $client) {
            $clientIds[] = self::createClient($client);
        }

        return $clientIds;
    }

    public static function createClient(
        ClientDto $client
    ) {
        $client = Client::firstOrCreate(
            [
                'email' => $client->email
            ],
            [
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
            ]
        );

        return $client->id;
    }
}