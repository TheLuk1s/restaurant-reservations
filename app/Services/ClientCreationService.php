<?php

namespace App\Services;

use App\Models\Client;
use App\Dto\ClientDto;
use App\Interfaces\ClientCreationServiceInterface;

class ClientCreationService implements ClientCreationServiceInterface
{
    public function createClients(
        array $clients
    ) : array {
        $clientIds = [];

        foreach ($clients as $client) {
            $clientIds[] = $this->createClient($client);
        }

        return $clientIds;
    }

    public function createClient(
        ClientDto $client
    ) : int {
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
