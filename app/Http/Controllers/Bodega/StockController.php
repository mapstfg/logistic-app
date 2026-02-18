<?php

namespace App\Http\Controllers\Bodega;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $movements = \App\Models\StockMovement::with(['user'])->latest()->paginate(20);
        return view('bodega.stock.index', compact('movements'));
    }

    public function audit()
    {
        $medicines = \App\Models\Medicine::all();
        $supplies = \App\Models\Supply::all();
        return view('bodega.stock.audit', compact('medicines', 'supplies'));
    }
}
