<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'movement_type',
        'item_type',
        'item_id',
        'quantity',
        'from_location',
        'to_location',
        'user_id',
        'patient_id',
        'prescription_id',
        'transfer_id',
        'happened_at',
        'meta',
    ];

    protected $casts = [
        'meta' => 'json',
        'happened_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->morphTo();
    }
}
