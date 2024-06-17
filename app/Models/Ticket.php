<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'NIK',
        'cust_name',
        'price_each',
        'transactions_id',
        'cars_id',
        'seats_id',
        'is_chose_seat',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function seat()
    {
        return $this->hasOne(Seat::class);
    }
}
