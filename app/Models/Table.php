<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    public static $validationRules = [
        'restaurant_id' => 'required|exists:restaurants,id',
        'capacity' => 'required|integer',
    ];

    protected $fillable = ['restaurant_id', 'capacity'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }
}
