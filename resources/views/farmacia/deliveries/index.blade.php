@extends('layouts.bootstrap')

@section('content')
    <div class="mb-4">
        <h1>Entregar Medicamentos</h1>
        <p class="text-muted">Busque una receta pendiente por documento de paciente para procesar la entrega.</p>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('farmacia.deliveries.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control form-control-lg"
                        placeholder="Documento o nombre del paciente..." value="{{ request('search') }}" autofocus>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">Buscar Receta</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Recetas Pendientes de Entrega</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                            <tr>
                                <td><code>#{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                <td><strong>{{ $prescription->patient->name }}</strong><br><small
                                        class="text-muted">{{ $prescription->patient->document_id }}</small></td>
                                <td>Dr. {{ $prescription->doctor->name }}</td>
                                <td>{{ $prescription->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('farmacia.deliveries.show', $prescription) }}"
                                        class="btn btn-sm btn-success">Procesar Entrega</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-search" style="font-size: 2rem;"></i>
                                    <p class="mt-2">No se encontraron recetas pendientes.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $prescriptions->links() }}
        </div>
    </div>
@endsection