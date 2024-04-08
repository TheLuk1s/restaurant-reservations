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
    Route::put('restaurants/{restaurant}', 'update');
    Route::delete('restaurants/{restaurant}', 'destroy');
});

Route::controller(RestaurantTableController::class)->group(function () {
    Route::get('restaurants/{restaurant}/tables', 'index');
    Route::get('restaurants/{restaurant}/tables/{table}', 'show');
    Route::post('restaurants/{restaurant}/tables', 'store');
    Route::put('restaurants/{restaurant}/tables/{table}', 'update');
    Route::delete('restaurants/{restaurant}/tables/{table}', 'destroy');
});

Route::controller(RestaurantReservationController::class)->group(function () {
    Route::get('restaurants/{restaurant}/reservations', 'index');
    Route::get('restaurants/{restaurant}/reservations/{reservation}', 'show');
    Route::post('restaurants/{restaurant}/reservations', 'store');
    Route::delete('restaurants/{restaurant}/reservations/{reservation}', 'destroy');
});