@extends('layouts.bootstrap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-5">
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h2 class="text-primary fw-bold">ORDEN DE TRANSFERENCIA</h2>
                            <h5 class="text-muted">#TR-{{ str_pad($transfer->id, 5, '0', STR_PAD_LEFT) }}</h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0"><strong>Fecha:</strong> {{ $transfer->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Estado:</strong> <span class="badge bg-success">{{ $transfer->status }}</span></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p><strong>Realizado por:</strong> {{ $transfer->user->name }}</p>
                        <p><strong>Origen:</strong> Bodega Central</p>
                        <p><strong>Destino:</strong> Farmacia Institucional</p>
                    </div>

                    <h5 class="mb-3">Items Transferidos</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Item</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transfer->items as $item)
                                    <tr>
                                        <td>{{ $item->type }}</td>
                                        <td>
                                            @php
                                                $pitem = $item->type === 'MEDICAMENTO' ? \App\Models\Medicine::find($item->item_id) : \App\Models\Supply::find($item->item_id);
                                            @endphp
                                            {{ $pitem->name ?? 'N/A' }}
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5 text-center d-print-none">
                        <button onclick="window.print()" class="btn btn-outline-secondary me-2">Imprimir Orden</button>
                        <a href="{{ route('bodega.transfers.index') }}" class="btn btn-primary">Volver al Listado</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection