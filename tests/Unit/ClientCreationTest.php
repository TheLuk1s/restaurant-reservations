<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ClientCreationService;
use App\Models\Client;
use App\Dto\ClientDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ClientCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateClient_SingleClientCreatedSuccessfully()
    {
        $mockedClient = Mockery::mock(Client::class);
        $mockedClient->shouldReceive('firstOrCreate')->andReturnUsing(function ($attributes) {
            return new Client($attributes);
        });
        $this->app->instance(Client::class, $mockedClient);

        $clientDto = new ClientDto('John Doe', 'john@example.com', '123456789');
        $service = new ClientCreationService();
        $clientId = $service->createClient($clientDto);

        $this->assertNotNull($clientId);
    }

    public function testCreateClient_MultipleClientsCreatedSuccessfully()
    {
        // Mock the Client model
        $mockedClient = Mockery::mock(Client::class);
        $mockedClient->shouldReceive('firstOrCreate')->andReturnUsing(function ($attributes) {
            return new Client($attributes);
        });
        $this->app->instance(Client::class, $mockedClient);

        // Prepare client data
        $clients = [
            new ClientDto('John Doe', 'john@example.com', '123456789'),
            new ClientDto('Jane Doe', 'jane@example.com', '987654321'),
        ];

        // Create clients
        $service = new ClientCreationService();
        $clientIds = $service->createClients($clients);

        // Assert that the number of created clients matches
        $this->assertCount(2, $clientIds);
    }
}
