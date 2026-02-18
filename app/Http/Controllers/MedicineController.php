<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Medicine::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('generic_name', 'like', '%' . $request->search . '%')
                ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        $medicines = $query->paginate(15);
        return view('medicines.index', compact('medicines'));
    }

    public function create()
    {
        return view('medicines.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:medicines',
            'stock_bodega' => 'integer|min:0',
            'stock_farmacia' => 'integer|min:0',
            'min_stock' => 'integer|min:0',
        ]);

        \App\Models\Medicine::create($request->all());

        return redirect()->route('medicines.index')->with('success', 'Medicamento registrado correctamente.');
    }

    public function edit(\App\Models\Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:medicines,sku,' . $medicine->id,
            'stock_bodega' => 'integer|min:0',
            'stock_farmacia' => 'integer|min:0',
        ]);

        $medicine->update($request->all());

        return redirect()->route('medicines.index')->with('success', 'Medicamento actualizado correctamente.');
    }

    public function destroy(\App\Models\Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Medicamento eliminado.');
    }
}
