<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ReservationTablesFinderService;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Mockery;

class ReservationTablesFinderServiceTest extends TestCase
{
    public function testFindTablesForReservation_ReturnsCorrectTable()
    {
        $service = Mockery::mock(ReservationTablesFinderService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $service->shouldReceive('getAvailableTables')
                ->once()
                ->andReturn(collect([
                    ['id' => 1, 'capacity' => 4],
                    ['id' => 2, 'capacity' => 6]
                ]));

        // Mock data
        $clients = [1, 2]; // 4 clients + reserver
        $dateTime = Carbon::now();
        $restaurantId = 1;

        // Call the method being tested
        $result = $service->findTablesForReservation($clients, $dateTime, $restaurantId);

        // Assert the result
        // Expecting table with id 2 to be selected since it can accommodate all clients in one go
        $this->assertEquals([1], $result);
    }

    public function testFindTablesForReservation_ReturnsCorrectTables()
    {
        $service = Mockery::mock(ReservationTablesFinderService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $service->shouldReceive('getAvailableTables')
                ->once()
                ->andReturn(collect([
                    ['id' => 1, 'capacity' => 4],
                    ['id' => 2, 'capacity' => 6]
                ]));

        // Mock data
        $clients = [1, 2, 3, 4, 5, 6, 7, 8]; // 4 clients + reserver
        $dateTime = Carbon::now();
        $restaurantId = 1;

        // Call the method being tested
        $result = $service->findTablesForReservation($clients, $dateTime, $restaurantId);

        // Assert the result
        // Expecting table with id 2 to be selected since it can accommodate all clients in one go
        $this->assertEquals([2, 1], $result);
    }

    public function testFindTablesForReservation_ReturnsNotFoundTables()
    {
        $service = Mockery::mock(ReservationTablesFinderService::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $service->shouldReceive('getAvailableTables')
                ->once()
                ->andReturn(collect([
                    ['id' => 1, 'capacity' => 1]
                ]));

        // Mock data
        $clients = [1, 2, 3, 4, 5, 6, 7, 8]; // 4 clients + reserver
        $dateTime = Carbon::now();
        $restaurantId = 1;

        // Call the method being tested
        $result = $service->findTablesForReservation($clients, $dateTime, $restaurantId);

        // Assert the result
        // Expecting table with id 2 to be selected since it can accommodate all clients in one go
        $this->assertEmpty($result);
    }
}
