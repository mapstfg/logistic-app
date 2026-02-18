@extends('layouts.bootstrap')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Auditoría de Inventario Real</h1>
        <button onclick="window.print()" class="btn btn-outline-secondary d-print-none">Imprimir Reporte</button>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">Medicamentos</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Bodega</th>
                                <th>Farmacia</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicines as $m)
                                <tr>
                                    <td>{{ $m->name }}</td>
                                    <td>{{ $m->stock_bodega }}</td>
                                    <td>{{ $m->stock_farmacia }}</td>
                                    <td class="fw-bold">{{ $m->stock_bodega + $m->stock_farmacia }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">Insumos</div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Bodega</th>
                                <th>Farmacia</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplies as $s)
                                <tr>
                                    <td>{{ $s->name }}</td>
                                    <td>{{ $s->stock_bodega }}</td>
                                    <td>{{ $s->stock_farmacia }}</td>
                                    <td class="fw-bold">{{ $s->stock_bodega + $s->stock_farmacia }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection