<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'trains_id',
    ];

    public function train()
    {
        return $this->belongsTo(Train::class);
    }
}
