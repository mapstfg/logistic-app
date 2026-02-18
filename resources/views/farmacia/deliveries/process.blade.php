@extends('layouts.bootstrap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Procesar Entrega de Receta #{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</h4>
                    <span class="badge bg-dark">PENDIENTE</span>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6 border-end">
                            <label class="small text-muted text-uppercase fw-bold">Paciente</label>
                            <h5>{{ $prescription->patient->name }}</h5>
                            <p class="mb-0">{{ $prescription->patient->document_id }}</p>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <label class="small text-muted text-uppercase fw-bold">Médico</label>
                            <h5>Dr. {{ $prescription->doctor->name }}</h5>
                            <p class="mb-0">Fecha: {{ $prescription->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <h5 class="mb-3 border-bottom pb-2">Verificación de Inventario</h5>
                    <form action="{{ route('farmacia.deliveries.store', $prescription) }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Medicamento</th>
                                        <th>Indicación</th>
                                        <th>Stock Farmacia</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $canDeliver = true; @endphp
                                    @foreach($prescription->items as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->medicine->name }}</strong><br>
                                                <small>{{ $item->medicine->generic_name }}</small>
                                            </td>
                                            <td>{{ $item->dosage }} ({{ $item->frequency }})</td>
                                            <td>
                                                <span
                                                    class="fw-bold {{ $item->medicine->stock_farmacia < 1 ? 'text-danger' : 'text-success' }}">
                                                    {{ $item->medicine->stock_farmacia }} unidades
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($item->medicine->stock_farmacia >= 1)
                                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                                                @else
                                                    @php $canDeliver = false; @endphp
                                                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 1.5rem;"></i>
                                                    <div class="small text-danger">Sin Stock</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(!$canDeliver)
                            <div class="alert alert-danger mt-3">
                                <strong>Atención:</strong> No se puede completar la entrega porque uno o más medicamentos no
                                tienen stock suficiente en Farmacia. Solicite una transferencia a Bodega.
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                            <a href="{{ route('farmacia.deliveries.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success btn-lg" {{ !$canDeliver ? 'disabled' : '' }}>
                                Confirmar Entrega y Rebajar Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection