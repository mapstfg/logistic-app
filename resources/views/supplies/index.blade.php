@extends('layouts.bootstrap')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Inventario de Insumos</h1>
        @if(auth()->user()->role === 'ADMIN' || auth()->user()->role === 'BODEGA')
            <a href="{{ route('supplies.create') }}" class="btn btn-primary">Registrar Insumo</a>
        @endif
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('supplies.index') }}" method="GET" class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o SKU..."
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
                            <th>Bodega</th>
                            <th>Farmacia</th>
                            <th>Min. Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplies as $supply)
                            <tr
                                class="{{ ($supply->stock_bodega + $supply->stock_farmacia) <= $supply->min_stock ? 'table-warning' : '' }}">
                                <td><code>{{ $supply->sku }}</code></td>
                                <td>{{ $supply->name }}</td>
                                <td>{{ $supply->stock_bodega }}</td>
                                <td>{{ $supply->stock_farmacia }}</td>
                                <td>{{ $supply->min_stock }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('supplies.edit', $supply) }}"
                                            class="btn btn-outline-primary">Editar</a>
                                        @if(auth()->user()->role === 'ADMIN')
                                            <form action="{{ route('supplies.destroy', $supply) }}" method="POST" class="d-inline">
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
                                <td colspan="6" class="text-center py-4 text-muted">No se encontraron insumos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $supplies->links() }}
        </div>
    </div>
@endsection