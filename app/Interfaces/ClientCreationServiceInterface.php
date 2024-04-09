<?php

namespace App\Interfaces;

use App\Dto\ClientDto;

interface ClientCreationServiceInterface
{
    public function createClients(array $clients): array;
    public function createClient(ClientDto $client): int;
}
