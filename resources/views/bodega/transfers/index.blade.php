@extends('layouts.bootstrap')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Transferencias a Farmacia</h1>
        <a href="{{ route('bodega.transfers.create') }}" class="btn btn-primary">Nueva Transferencia</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Operador</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                            <tr>
                                <td><code>#TR-{{ str_pad($transfer->id, 5, '0', STR_PAD_LEFT) }}</code></td>
                                <td>{{ $transfer->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $transfer->user->name }}</td>
                                <td>
                                    <span class="badge {{ $transfer->status === 'COMPLETADO' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $transfer->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('bodega.transfers.show', $transfer) }}"
                                        class="btn btn-sm btn-outline-info">Ver Detalle</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No se han registrado transferencias aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $transfers->links() }}
        </div>
    </div>
@endsection