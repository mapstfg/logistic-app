@extends('layouts.bootstrap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">{{ isset($supply) ? 'Editar Insumo' : 'Registrar Nuevo Insumo' }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($supply) ? route('supplies.update', $supply) : route('supplies.store') }}"
                        method="POST">
                        @csrf
                        @if(isset($supply))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Nombre del Insumo</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $supply->name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">SKU / Código</label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                                value="{{ old('sku', $supply->sku ?? '') }}" required>
                        </div>

                        <hr>
                        <h5>Control de Stock</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock en Bodega</label>
                                <input type="number" name="stock_bodega" class="form-control"
                                    value="{{ old('stock_bodega', $supply->stock_bodega ?? 0) }}" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock en Farmacia</label>
                                <input type="number" name="stock_farmacia" class="form-control"
                                    value="{{ old('stock_farmacia', $supply->stock_farmacia ?? 0) }}" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock Mínimo</label>
                                <input type="number" name="min_stock" class="form-control"
                                    value="{{ old('min_stock', $supply->min_stock ?? 0) }}" min="0" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('supplies.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit"
                                class="btn btn-primary">{{ isset($supply) ? 'Actualizar' : 'Registrar' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection