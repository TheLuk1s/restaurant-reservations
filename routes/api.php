<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantReservationController;
use App\Http\Controllers\RestaurantTableController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(RestaurantController::class)->group(function () {
    Route::get('restaurants', 'index');
    Route::get('restaurants/{restaurant}', 'show');
    Route::post('restaurants', 'store');
    Route::put('restaurants/{restaurant}', 'store');
    Route::delete('restaurants/{restaurant}', 'destroy');
});

Route::controller(RestaurantTableController::class)->group(function () {
    Route::get('restaurants/{restaurant}/tables', 'index');
    Route::post('restaurants/{restaurant}/tables', 'store');
    Route::get('restaurants/{restaurant}/tables/{table}', 'show');
    Route::put('restaurants/{restaurant}/tables/{table}', 'update');
    Route::delete('restaurants/{restaurant}/tables/{table}', 'destroy');
});

Route::controller(RestaurantReservationController::class)->group(function () {
    Route::get('restaurants/{restaurant}/reservations', 'index');
    Route::post('restaurants/{restaurant}/reservations', 'store');
    // Route::get('restaurants/{restaurant}/tables/{table}', 'show');
    // Route::put('restaurants/{restaurant}/tables/{table}', 'update');
    // Route::delete('restaurants/{restaurant}/tables/{table}', 'destroy');
});