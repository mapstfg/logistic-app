<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'prescription_id',
        'item_type',
        'item_id',
        'quantity',
        'delivered_quantity',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function item()
    {
        return $this->morphTo();
    }
}
