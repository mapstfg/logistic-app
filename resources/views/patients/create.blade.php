@extends('layouts.bootstrap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">{{ isset($patient) ? 'Editar Paciente' : 'Registrar Nuevo Paciente' }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($patient) ? route('patients.update', $patient) : route('patients.store') }}"
                        method="POST">
                        @csrf
                        @if(isset($patient))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $patient->name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Número de Documento (ID)</label>
                            <input type="text" name="document_id"
                                class="form-control @error('document_id') is-invalid @enderror"
                                value="{{ old('document_id', $patient->document_id ?? '') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $patient->email ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $patient->phone ?? '') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('patients.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit"
                                class="btn btn-primary">{{ isset($patient) ? 'Guardar' : 'Registrar' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection