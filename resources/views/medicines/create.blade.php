@extends('layouts.bootstrap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">{{ isset($medicine) ? 'Editar Medicamento' : 'Registrar Nuevo Medicamento' }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($medicine) ? route('medicines.update', $medicine) : route('medicines.store') }}"
                        method="POST">
                        @csrf
                        @if(isset($medicine))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre Comercial</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $medicine->name ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre Genérico</label>
                                <input type="text" name="generic_name"
                                    class="form-control @error('generic_name') is-invalid @enderror"
                                    value="{{ old('generic_name', $medicine->generic_name ?? '') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">SKU / Código</label>
                                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
                                    value="{{ old('sku', $medicine->sku ?? '') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Forma Farmacéutica</label>
                                <input type="text" name="dosage_form" class="form-control"
                                    value="{{ old('dosage_form', $medicine->dosage_form ?? '') }}"
                                    placeholder="Ej: Tabletas, Jarabe" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Concentración</label>
                                <input type="text" name="strength" class="form-control"
                                    value="{{ old('strength', $medicine->strength ?? '') }}" placeholder="Ej: 500mg"
                                    required>
                            </div>
                        </div>

                        <hr>
                        <h5>Control de Stock</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock en Bodega</label>
                                <input type="number" name="stock_bodega" class="form-control"
                                    value="{{ old('stock_bodega', $medicine->stock_bodega ?? 0) }}" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock en Farmacia</label>
                                <input type="number" name="stock_farmacia" class="form-control"
                                    value="{{ old('stock_farmacia', $medicine->stock_farmacia ?? 0) }}" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stock Mínimo (Alerta)</label>
                                <input type="number" name="min_stock" class="form-control"
                                    value="{{ old('min_stock', $medicine->min_stock ?? 0) }}" min="0" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('medicines.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit"
                                class="btn btn-primary">{{ isset($medicine) ? 'Actualizar' : 'Registrar' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection