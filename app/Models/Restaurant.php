<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
