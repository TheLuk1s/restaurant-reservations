<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ClientCreationService;
use App\Models\Client;
use App\Dto\ClientDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ClientCreationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateClient_SingleClientCreatedSuccessfully()
    {
        // Mock service
        $mockedClient = Mockery::mock(Client::class);
        $mockedClient->shouldReceive('firstOrCreate')->andReturnUsing(function ($attributes) {
            return new Client($attributes);
        });

        $this->app->instance(Client::class, $mockedClient);

        // Mock data
        $clientDto = new ClientDto('John Doe', 'john@example.com', '123456789');
        $service = new ClientCreationService();

        // Call the method being tested
        $clientId = $service->createClient($clientDto);

        // Assert the result
        $this->assertNotNull($clientId);
    }

    public function testCreateClient_MultipleClientsCreatedSuccessfully()
    {
        // Mock service
        $mockedClient = Mockery::mock(Client::class);
        $mockedClient->shouldReceive('firstOrCreate')->andReturnUsing(function ($attributes) {
            return new Client($attributes);
        });

        $this->app->instance(Client::class, $mockedClient);

        // Mock data
        $clients = [
            new ClientDto('John Doe', 'john@example.com', '123456789'),
            new ClientDto('Jane Doe', 'jane@example.com', '987654321'),
        ];

        $service = new ClientCreationService();

        // Call the method being tested
        $clientIds = $service->createClients($clients);

        // Assert the result
        $this->assertCount(2, $clientIds);
    }
}
