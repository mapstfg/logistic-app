@extends('layouts.bootstrap')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Mis Recetas Emitidas</h1>
        <a href="{{ route('medico.prescriptions.create') }}" class="btn btn-primary">Nueva Receta</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                            <tr>
                                <td><code>#{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                <td>{{ $prescription->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $prescription->patient->name }}</td>
                                <td>
                                    <span
                                        class="badge {{ $prescription->status === 'ENTREGADA' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $prescription->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('medico.prescriptions.show', $prescription) }}"
                                        class="btn btn-sm btn-outline-info">Ver Detalle</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Aún no has emitido recetas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $prescriptions->links() }}
        </div>
    </div>
@endsection