<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = \App\Models\Prescription::with('patient')->where('doctor_id', auth()->id())->latest()->paginate(15);
        return view('medico.prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $patients = \App\Models\Patient::all();
        $medicines = \App\Models\Medicine::all();
        return view('medico.prescriptions.create', compact('patients', 'medicines'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.dosage' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.duration' => 'required|string',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $prescription = \App\Models\Prescription::create([
                'doctor_id' => auth()->id(),
                'patient_id' => $request->patient_id,
                'status' => 'PENDIENTE',
            ]);

            foreach ($request->items as $item) {
                $prescription->items()->create([
                    'item_type' => 'MEDICAMENTO',
                    'item_id' => $item['medicine_id'],
                    'quantity' => $item['quantity'],
                    'delivered_quantity' => 0,
                ]);
            }
        });

        return redirect()->route('medico.prescriptions.index')->with('success', 'Receta emitida correctamente.');
    }

    public function show(\App\Models\Prescription $prescription)
    {
        $prescription->load('patient', 'items.medicine');
        return view('medico.prescriptions.show', compact('prescription'));
    }
}
