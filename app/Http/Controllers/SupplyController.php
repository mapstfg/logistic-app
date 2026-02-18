<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Supply::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        $supplies = $query->paginate(15);
        return view('supplies.index', compact('supplies'));
    }

    public function create()
    {
        return view('supplies.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:supplies',
            'stock_bodega' => 'integer|min:0',
            'stock_farmacia' => 'integer|min:0',
            'min_stock' => 'integer|min:0',
        ]);

        \App\Models\Supply::create($request->all());

        return redirect()->route('supplies.index')->with('success', 'Insumo registrado correctamente.');
    }

    public function edit(\App\Models\Supply $supply)
    {
        return view('supplies.edit', compact('supply'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Supply $supply)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:supplies,sku,' . $supply->id,
            'stock_bodega' => 'integer|min:0',
            'stock_farmacia' => 'integer|min:0',
        ]);

        $supply->update($request->all());

        return redirect()->route('supplies.index')->with('success', 'Insumo actualizado correctamente.');
    }

    public function destroy(\App\Models\Supply $supply)
    {
        $supply->delete();
        return redirect()->route('supplies.index')->with('success', 'Insumo eliminado.');
    }
}
