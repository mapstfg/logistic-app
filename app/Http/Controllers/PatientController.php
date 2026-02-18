<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Patient::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('document_id', 'like', '%' . $request->search . '%');
        }

        $patients = $query->paginate(15);
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document_id' => 'required|string|unique:patients',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        \App\Models\Patient::create($request->all());

        return redirect()->route('patients.index')->with('success', 'Paciente registrado correctamente.');
    }

    public function edit(\App\Models\Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document_id' => 'required|string|unique:patients,document_id,' . $patient->id,
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.index')->with('success', 'Paciente actualizado correctamente.');
    }
}
