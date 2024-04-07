<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

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
