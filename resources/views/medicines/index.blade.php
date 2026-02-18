@extends('layouts.bootstrap')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Inventario de Medicamentos</h1>
        @if(auth()->user()->role === 'ADMIN' || auth()->user()->role === 'BODEGA')
            <a href="{{ route('medicines.create') }}" class="btn btn-primary">Registrar Medicamento</a>
        @endif
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('medicines.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, genérico o SKU..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-secondary w-100">Buscar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Nombre</th>
                            <th>Genérico</th>
                            <th>Presentación</th>
                            <th>Bodega</th>
                            <th>Farmacia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                            <tr
                                class="{{ ($medicine->stock_bodega + $medicine->stock_farmacia) <= $medicine->min_stock ? 'table-warning' : '' }}">
                                <td><code>{{ $medicine->sku }}</code></td>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ $medicine->generic_name }}</td>
                                <td>{{ $medicine->dosage_form }} ({{ $medicine->strength }})</td>
                                <td>{{ $medicine->stock_bodega }}</td>
                                <td>{{ $medicine->stock_farmacia }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('medicines.edit', $medicine) }}"
                                            class="btn btn-outline-primary">Editar</a>
                                        @if(auth()->user()->role === 'ADMIN')
                                            <form action="{{ route('medicines.destroy', $medicine) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                    onclick="return confirm('¿Eliminar?')">Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No se encontraron medicamentos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $medicines->links() }}
        </div>
    </div>
@endsection