@extends('layouts.bootstrap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-5">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h2 class="text-primary fw-bold">RECETA MÉDICA</h2>
                            <h5 class="text-muted">#{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0"><strong>Fecha de Emisión:</strong>
                                {{ $prescription->created_at->format('d/m/Y') }}</p>
                            <p><strong>Estado:</strong> <span
                                    class="badge {{ $prescription->status === 'ENTREGADA' ? 'bg-success' : 'bg-warning' }}">{{ $prescription->status }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-5 py-3 border-top border-bottom bg-light">
                        <div class="col-md-6">
                            <p class="mb-1 fw-bold text-uppercase small text-muted">Información del Paciente</p>
                            <h4 class="mb-0">{{ $prescription->patient->name }}</h4>
                            <p class="text-muted">Documento: {{ $prescription->patient->document_id }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1 fw-bold text-uppercase small text-muted">Médico Tratante</p>
                            <h4 class="mb-0">Dr. {{ $prescription->doctor->name }}</h4>
                        </div>
                    </div>

                    <h5 class="mb-3">Indicaciones de Medicamentos</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Medicamento</th>
                                    <th>Genérico</th>
                                    <th>Dosificación</th>
                                    <th>Frecuencia</th>
                                    <th>Duración</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prescription->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->medicine->name }}</strong></td>
                                        <td>{{ $item->medicine->generic_name }}</td>
                                        <td>{{ $item->dosage }}</td>
                                        <td>{{ $item->frequency }}</td>
                                        <td>{{ $item->duration }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 text-center d-print-none">
                        <button onclick="window.print()" class="btn btn-outline-secondary me-2">Imprimir Receta</button>
                        <a href="{{ route('medico.prescriptions.index') }}" class="btn btn-primary">Volver al Listado</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection