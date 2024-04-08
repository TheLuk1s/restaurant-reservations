<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public static $validationRules = [
        'restaurant_id' => 'required|exists:restaurants,id',
        'reservation_date' => 'required|date',
        'reservation_time' => 'required|date_format:H:i',
        'reserver_name' => 'required|string|max:255',
        'reserver_email' => 'required|string|email|max:255',
        'reserver_phone' => 'required|string|max:255',
        'clients' => 'nullable|array',
        'clients.*.name' => 'required|string|max:255',
        'clients.*.email' => 'required|string|email|max:255',
        'clients.*.phone' => 'nullable|string|max:255'
    ];

    protected $fillable = [
        'restaurant_id',
        'table_id',
        'reserver_client_id',
        'reservation_time',
    ];

    protected $dates = [
        'reservation_time',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tables()
    {
        return $this->belongsToMany(Table::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'reservation_client');
    }

    public function reserver()
    {
        return $this->belongsTo(Client::class, 'reserver_client_id');
    }
}
