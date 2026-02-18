<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    protected $fillable = [
        'name',
        'description',
        'stock_bodega',
        'stock_farmacia',
        'min_stock',
        'expires_at',
        'location',
        'active',
    ];
}
