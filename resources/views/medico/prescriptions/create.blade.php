@extends('layouts.bootstrap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Emitir Nueva Receta</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('medico.prescriptions.store') }}" method="POST" id="prescription-form">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">1. Seleccionar Paciente</label>
                            <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror"
                                required>
                                <option value="">Seleccione un paciente...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->document_id }} - {{ $patient->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">2. Medicamentos y Dosificación</label>
                            <div id="items-container">
                                <div class="item-row card p-3 mb-3 border-light bg-light">
                                    <div class="row">
                                        <div class="col-md-5 mb-2">
                                            <label class="small text-muted">Medicamento</label>
                                            <select name="items[0][medicine_id]" class="form-select select-medicine"
                                                required>
                                                <option value="">Seleccione...</option>
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}
                                                        ({{ $medicine->generic_name }}) - {{ $medicine->stock_farmacia }} en
                                                        stock</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-7 mb-2">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="small text-muted">Dosis</label>
                                                    <input type="text" name="items[0][dosage]" class="form-control"
                                                        placeholder="Ej: 1 cada 8h" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="small text-muted">Freq.</label>
                                                    <input type="text" name="items[0][frequency]" class="form-control"
                                                        placeholder="Ej: Diaria" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="small text-muted">Duración</label>
                                                    <input type="text" name="items[0][duration]" class="form-control"
                                                        placeholder="Ej: 7 días" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-item">+ Agregar otro
                                medicamento</button>
                        </div>

                        <div class="d-flex justify-content-between mt-5 border-top pt-3">
                            <a href="{{ route('medico.prescriptions.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success btn-lg">Emitir Receta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = 1;
        document.getElementById('add-item').addEventListener('click', function () {
            const container = document.getElementById('items-container');
            const firstRow = container.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);

            // Update names
            newRow.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace('[0]', '[' + itemIndex + ']'));
                input.value = '';
            });

            // Add remove button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-outline-danger btn-sm mt-2';
            removeBtn.innerText = 'Eliminar este item';
            removeBtn.onclick = function () { newRow.remove(); };
            newRow.appendChild(removeBtn);

            container.appendChild(newRow);
            itemIndex++;
        });
    </script>
@endsection