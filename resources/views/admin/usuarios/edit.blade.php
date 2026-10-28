@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1><i class="fas fa-user-edit"></i> Editar Usuario</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Modificar Datos de: <b>{{ $user->name }}</b></h3>
                </div>
                <form action="{{ route('admin.usuarios.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nombre Completo</label> <span class="text-danger">(*)</span>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    id="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico (Login)</label> <span class="text-danger">(*)</span>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="callout callout-info">
                            <p class="text-muted mb-0"><i class="fas fa-info-circle"></i> Deje los campos de contraseña vacíos si no desea cambiar la clave actual del usuario.</p>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Nueva Contraseña (Opcional)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" placeholder="Mínimo 8 caracteres">
                                    </div>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            id="password_confirmation" placeholder="Repita la nueva contraseña">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Rol en el Sistema</label> <span class="text-danger">(*)</span>
                            <div class="row pl-2">
                                @php
                                    $userRole = $user->roles->pluck('name')->first();
                                @endphp
                                @foreach ($roles as $rol)
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio mb-2">
                                            <input class="custom-control-input" type="radio" id="rol_{{ $rol->id }}" name="roles[]" value="{{ $rol->name }}"
                                                {{ (is_array(old('roles')) && in_array($rol->name, old('roles'))) || (!old('roles') && $userRole === $rol->name) ? 'checked' : '' }}>
                                            <label for="rol_{{ $rol->id }}" class="custom-control-label font-weight-normal">
                                                <b>{{ $rol->name }}</b>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer text-right bg-white">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

