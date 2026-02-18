<?php

namespace App\Http\Controllers\Farmacia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Prescription::with('patient')->where('status', 'PENDIENTE');

        if ($request->filled('search')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('document_id', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $prescriptions = $query->latest()->paginate(15);
        return view('farmacia.deliveries.index', compact('prescriptions'));
    }

    public function show(\App\Models\Prescription $prescription)
    {
        if ($prescription->status !== 'PENDIENTE') {
            return redirect()->route('farmacia.deliveries.index')->with('error', 'Esta receta ya fue procesada.');
        }

        $prescription->load('patient', 'items.medicine', 'doctor');
        return view('farmacia.deliveries.process', compact('prescription'));
    }

    public function store(\Illuminate\Http\Request $request, \App\Models\Prescription $prescription)
    {
        if ($prescription->status !== 'PENDIENTE') {
            return back()->with('error', 'Receta no válida.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($prescription) {
                foreach ($prescription->items as $item) {
                    // Resolve model based on item_type string 'MEDICAMENTO' or 'INSUMO'
                    $modelClass = $item->item_type === 'MEDICAMENTO' ? \App\Models\Medicine::class : \App\Models\Supply::class;
                    $product = $modelClass::find($item->item_id);

                    if (!$product) {
                        continue; // Skip if item not found
                    }

                    if ($product->stock_farmacia < $item->quantity) {
                        throw new \Exception("Stock insuficiente en farmacia para: " . $product->name);
                    }

                    $product->decrement('stock_farmacia', $item->quantity);

                    \App\Models\StockMovement::create([
                        'item_id' => $product->id,
                        'item_type' => $item->item_type,
                        'movement_type' => 'OUT',
                        'quantity' => $item->quantity,
                        'happened_at' => now(),
                        'user_id' => auth()->id(),
                        'notes' => 'Entrega de receta #' . $prescription->id,
                    ]);
                }

                $prescription->update(['status' => 'ENTREGADA']);
            });

            return redirect()->route('farmacia.deliveries.index')->with('success', 'Medicamentos entregados y stock actualizado.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
