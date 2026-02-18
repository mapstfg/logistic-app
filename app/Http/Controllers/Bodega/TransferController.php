<?php

namespace App\Http\Controllers\Bodega;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $transfers = \App\Models\Transfer::with('user')->latest()->paginate(15);
        return view('bodega.transfers.index', compact('transfers'));
    }

    public function create()
    {
        $medicines = \App\Models\Medicine::where('stock_bodega', '>', 0)->get();
        $supplies = \App\Models\Supply::where('stock_bodega', '>', 0)->get();
        return view('bodega.transfers.create', compact('medicines', 'supplies'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required',
            'items.*.type' => 'required|in:MEDICAMENTO,INSUMO',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $transfer = \App\Models\Transfer::create([
                'created_by' => auth()->id(),
                'from_location' => 'BODEGA',
                'to_location' => 'FARMACIA',
                'status' => 'COMPLETADO',
                'transfer_date' => now(),
            ]);

            foreach ($request->items as $itemData) {
                // Determine model
                $modelClass = $itemData['type'] === 'MEDICAMENTO' ? \App\Models\Medicine::class : \App\Models\Supply::class;
                $item = $modelClass::findOrFail($itemData['item_id']);

                // Validate stock
                if ($item->stock_bodega < $itemData['quantity']) {
                    throw new \Exception("Stock insuficiente en bodega para: " . $item->name);
                }

                // Update stocks
                $item->decrement('stock_bodega', $itemData['quantity']);
                $item->increment('stock_farmacia', $itemData['quantity']);

                // Create transfer item
                $transfer->items()->create([
                    'item_id' => $itemData['item_id'],
                    'item_type' => $itemData['type'],
                    'quantity' => $itemData['quantity'],
                ]);

                // Log movement
                \App\Models\StockMovement::create([
                    'item_id' => $itemData['item_id'],
                    'item_type' => $itemData['type'],
                    'movement_type' => 'TRANSFER',
                    'quantity' => $itemData['quantity'],
                    'happened_at' => now(),
                    'user_id' => auth()->id(),
                    'notes' => 'Transferencia a farmacia via #' . $transfer->id,
                ]);
            }
        });

        return redirect()->route('bodega.transfers.index')->with('success', 'Transferencia realizada con éxito.');
    }

    public function show(\App\Models\Transfer $transfer)
    {
        $transfer->load('items', 'user');
        return view('bodega.transfers.show', compact('transfer'));
    }
}
