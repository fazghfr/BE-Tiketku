<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_position',
        'is_taken',
        'cars_id',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}
