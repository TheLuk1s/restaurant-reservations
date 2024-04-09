<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ClientCreationService;
use App\Services\ReservationCreationService;
use App\Services\ReservationTablesFinderService;
use App\Interfaces\ClientCreationServiceInterface;
use App\Interfaces\ReservationCreationServiceInterface;
use App\Interfaces\ReservationTablesFinderServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ReservationCreationServiceInterface::class, ReservationCreationService::class);
        $this->app->bind(ClientCreationServiceInterface::class, ClientCreationService::class);
        $this->app->bind(ReservationTablesFinderServiceInterface::class, ReservationTablesFinderService::class);
    }
}
