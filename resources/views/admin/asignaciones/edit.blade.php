@extends('adminlte::page')

@section('title', 'Editar Asignación Docente')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-edit text-warning mr-2"></i>Modificar Asignación Docente</h1>
                <small class="text-muted">Actualización de Asignación Académica</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.asignaciones.index') }}">Asignaciones</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if (session('mensaje'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '{{ session('icono') ?? 'info' }}',
                    title: '{{ session('mensaje') }}',
                    showConfirmButton: true
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Hubo errores en el formulario:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.asignaciones.update', $asignacion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-7">
                <div class="card card-outline card-warning shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title text-bold"><i class="fas fa-chalkboard-teacher mr-1"></i> Docente Asignado</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="personal_id">Docente Titular: <b class="text-danger">*</b></label>
                            <select name="personal_id" id="personal_id" class="form-control select2" required style="width: 100%;">
                                @foreach ($docentes as $docente)
                                    <option value="{{ $docente->id }}" {{ (old('personal_id', $asignacion->personal_id) == $docente->id) ? 'selected' : '' }}>
                                        {{ $docente->apellidos }} {{ $docente->nombres }} (CI: {{ $docente->ci }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row align-items-center mt-3">
                            <div class="col-md-4 text-center">
                                @php
                                    $foto = ($asignacion->personal && $asignacion->personal->foto && file_exists(public_path($asignacion->personal->foto)))
                                        ? asset($asignacion->personal->foto)
                                        : asset('img/default-avatar.png');
                                @endphp
                                <img src="{{ $foto }}" class="img-thumbnail rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;" alt="Docente">
                            </div>
                            <div class="col-md-8">
                                <h5 class="text-primary font-weight-bold mb-1">{{ optional($asignacion->personal)->apellidos }} {{ optional($asignacion->personal)->nombres }}</h5>
                                <p class="text-muted mb-2"><i class="fas fa-briefcase mr-1"></i> {{ optional($asignacion->personal)->profesion ?? 'Docente' }}</p>
                                <p class="text-sm mb-1"><strong>CI:</strong> {{ optional($asignacion->personal)->ci }}</p>
                                <p class="text-sm mb-0"><strong>Contacto:</strong> {{ optional($asignacion->personal)->celular ?? optional($asignacion->personal)->telefono }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title text-bold"><i class="fas fa-layer-group mr-1"></i> Grupo y Condiciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="paralelo_id">Grupo Modular / Paralelo: <b class="text-danger">*</b></label>
                            <select name="paralelo_id" id="paralelo_id" class="form-control select2" required style="width: 100%;">
                                @foreach ($paralelos as $paralelo)
                                    @php
                                        $esVirtual = strtoupper($paralelo->modalidad) === 'VIRTUAL';
                                        $iconoMod = $esVirtual ? 'Virtual' : 'Presencial';
                                        $label = optional($paralelo->ciclo)->nombre . ' | ' . 
                                                 optional($paralelo->modulo->idioma)->nombre . ' - ' . 
                                                 optional($paralelo->modulo)->nombre . ' [' . $paralelo->nombre . '] (' . 
                                                 $iconoMod . ' - ' . $paralelo->turno . ' ' . substr($paralelo->hora_inicio,0,5) . '-' . substr($paralelo->hora_fin,0,5) . ')';
                                    @endphp
                                    <option value="{{ $paralelo->id }}" {{ (old('paralelo_id', $asignacion->paralelo_id) == $paralelo->id) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_asignacion">Fecha de Asignación: <b class="text-danger">*</b></label>
                            <input type="date" name="fecha_asignacion" id="fecha_asignacion" class="form-control" value="{{ old('fecha_asignacion', $asignacion->fecha_asignacion) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado de la Asignación: <b class="text-danger">*</b></label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="activo" {{ old('estado', strtolower($asignacion->estado)) == 'activo' ? 'selected' : '' }}>ACTIVO</option>
                                <option value="inactivo" {{ old('estado', strtolower($asignacion->estado)) == 'inactivo' ? 'selected' : '' }}>INACTIVO / FINALIZADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-right">
                        <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-warning font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@stop
