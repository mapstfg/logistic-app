@extends('layouts.bootstrap')

@section('content')
    <div class="mb-4">
        <h1>Historial de Movimientos de Stock</h1>
        <p class="text-muted">Registro completo de transferencias, consumos e ingresos manuales.</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Item</th>
                            <th>Tipo Mov.</th>
                            <th>Cant.</th>
                            <th>Usuario</th>
                            <th>Notas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $m)
                            <tr>
                                <td><code>#{{ $m->id }}</code></td>
                                <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @php
                                        $item = $m->item_type === 'MEDICAMENTO' ? \App\Models\Medicine::find($m->item_id) : \App\Models\Supply::find($m->item_id);
                                    @endphp
                                    <strong>{{ $item->name ?? 'Eliminado' }}</strong><br>
                                    <span class="badge bg-secondary small">{{ $m->item_type }}</span>
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $m->type === 'CONSUMO' ? 'bg-danger' : ($m->type === 'TRANSFERENCIA' ? 'bg-info' : 'bg-primary') }}">
                                        {{ $m->type }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ $m->quantity }}</td>
                                <td>{{ $m->user->name }}</td>
                                <td><small>{{ $m->notes }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No hay movimientos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $movements->links() }}
        </div>
    </div>
@endsection