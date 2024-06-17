<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'src_station',
        'dst_station',
        'dpt_sched',
        'arv_sched',
        'trains_id',
    ];

    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
