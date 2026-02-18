@extends('layouts.bootstrap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">{{ isset($user) ? 'Editar Usuario' : 'Nuevo Usuario' }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}"
                        method="POST">
                        @csrf
                        @if(isset($user))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="ADMIN" {{ old('role', $user->role ?? '') === 'ADMIN' ? 'selected' : '' }}>ADMIN
                                </option>
                                <option value="MEDICO" {{ old('role', $user->role ?? '') === 'MEDICO' ? 'selected' : '' }}>
                                    MEDICO</option>
                                <option value="FARMACIA" {{ old('role', $user->role ?? '') === 'FARMACIA' ? 'selected' : '' }}>FARMACIA</option>
                                <option value="BODEGA" {{ old('role', $user->role ?? '') === 'BODEGA' ? 'selected' : '' }}>
                                    BODEGA</option>
                            </select>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Contraseña
                                {{ isset($user) ? '(dejar en blanco para no cambiar)' : '' }}</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }}>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit"
                                class="btn btn-primary">{{ isset($user) ? 'Actualizar' : 'Crear' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection