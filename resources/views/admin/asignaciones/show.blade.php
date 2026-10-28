@extends('adminlte::page')

@section('title', 'Detalle de Asignación Docente')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-id-badge text-info mr-2"></i>Detalle de Asignación Académica</h1>
                <small class="text-muted">Ficha Informativa de Docente y Grupo Modular</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.asignaciones.index') }}">Asignaciones</a></li>
                    <li class="breadcrumb-item active">Detalle</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Perfil del Docente -->
        <div class="col-md-5">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title text-bold"><i class="fas fa-user mr-1"></i> Docente Titular</h3>
                </div>
                <div class="card-body box-profile text-center">
                    @php
                        $foto = ($asignacion->personal && $asignacion->personal->foto && file_exists(public_path($asignacion->personal->foto)))
                            ? asset($asignacion->personal->foto)
                            : asset('img/default-avatar.png');
                    @endphp
                    <img class="profile-user-img img-fluid img-circle shadow-sm mb-3" style="width: 130px; height: 130px; object-fit: cover;" src="{{ $foto }}" alt="Docente">

                    <h4 class="profile-username font-weight-bold mb-0">{{ optional($asignacion->personal)->apellidos }} {{ optional($asignacion->personal)->nombres }}</h4>
                    <p class="text-muted">{{ optional($asignacion->personal)->profesion ?? 'Docente de Idiomas' }}</p>

                    <ul class="list-group list-group-unbordered text-left mb-3">
                        <li class="list-group-item">
                            <b><i class="fas fa-id-card text-muted mr-1"></i> CI:</b> <span class="float-right text-dark font-weight-bold">{{ optional($asignacion->personal)->ci }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-phone text-muted mr-1"></i> Contacto:</b> <span class="float-right text-dark">{{ optional($asignacion->personal)->celular ?? optional($asignacion->personal)->telefono ?? 'S/N' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-envelope text-muted mr-1"></i> Correo:</b> <span class="float-right text-dark">{{ optional($asignacion->personal->usuario)->email ?? 'S/N' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-map-marker-alt text-muted mr-1"></i> Dirección:</b> <span class="float-right text-dark">{{ optional($asignacion->personal)->direccion ?? 'S/N' }}</span>
                        </li>
                    </ul>

                    <h6 class="text-bold text-secondary text-left mt-3"><i class="fas fa-graduation-cap mr-1"></i> Formación Académica:</h6>
                    <div class="table-responsive text-left">
                        @if($asignacion->personal && $asignacion->personal->formaciones && count($asignacion->personal->formaciones) > 0)
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Nivel</th>
                                        <th>Institución</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asignacion->personal->formaciones as $f)
                                        <tr>
                                            <td>{{ $f->titulo ?? $f->nombre }}</td>
                                            <td><span class="badge badge-info">{{ $f->nivel }}</span></td>
                                            <td>{{ $f->institucion }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted small">No registra formaciones añadidas.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Grupo y Estado -->
        <div class="col-md-7">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title text-bold"><i class="fas fa-layer-group mr-1"></i> Datos del Grupo Modular Asignado</h3>
                    <div class="card-tools">
                        @if(strtolower($asignacion->estado) === 'activo')
                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> ACTIVO</span>
                        @else
                            <span class="badge badge-secondary px-2 py-1"><i class="fas fa-clock"></i> INACTIVO</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="text-muted mb-0">Ciclo Bimestral</label>
                            <h5 class="font-weight-bold text-primary">{{ optional($asignacion->paralelo->ciclo)->nombre ?? ($asignacion->gestion->nombre ?? 'N/A') }}</h5>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted mb-0">Idioma y Módulo</label>
                            <h5 class="font-weight-bold">{{ optional(optional($asignacion->paralelo)->modulo)->idioma->nombre ?? 'Idioma' }} - {{ optional(optional($asignacion->paralelo)->modulo)->nombre ?? ($asignacion->materia->nombre ?? 'N/A') }}</h5>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="text-muted mb-0">Modalidad</label>
                            <div>
                                @php
                                    $esVirtual = strtoupper(optional($asignacion->paralelo)->modalidad) === 'VIRTUAL';
                                @endphp
                                @if($esVirtual)
                                    <span class="badge badge-info px-2 py-1"><i class="fas fa-laptop mr-1"></i> Virtual</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1"><i class="fas fa-building mr-1"></i> Presencial</span>
                                @endif
                                <strong class="ml-2 font-weight-bold">Grupo {{ optional($asignacion->paralelo)->nombre }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted mb-0">Turno y Horario</label>
                            <div>
                                <span class="badge badge-warning text-dark font-weight-bold">{{ optional($asignacion->paralelo)->turno }}</span>
                                <span class="ml-2">{{ optional($asignacion->paralelo)->hora_inicio ? substr($asignacion->paralelo->hora_inicio,0,5) : '00:00' }} a {{ optional($asignacion->paralelo)->hora_fin ? substr($asignacion->paralelo->hora_fin,0,5) : '00:00' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="text-muted mb-0">Aula Física o Enlace Virtual</label>
                            <div>
                                @if($esVirtual)
                                    @if(optional($asignacion->paralelo)->aula_o_enlace)
                                        <a href="{{ optional($asignacion->paralelo)->aula_o_enlace }}" target="_blank" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-video mr-1"></i> Ir a la Clase Virtual
                                        </a>
                                    @else
                                        <span class="text-muted">Sin enlace configurado</span>
                                    @endif
                                @else
                                    <span class="badge badge-light border p-2"><i class="fas fa-door-open text-primary mr-1"></i> {{ optional($asignacion->paralelo)->aula_o_enlace ?? 'Aula General' }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="text-muted mb-0">Fecha de Asignación</label>
                            <div class="font-weight-bold">
                                <i class="far fa-calendar-alt text-muted mr-1"></i> {{ $asignacion->fecha_asignacion }}
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="text-muted mb-0">Cupo del Grupo</label>
                            <div class="progress progress-sm mt-1">
                                @php
                                    $cupoMax = optional($asignacion->paralelo)->cupo_maximo ?? 30;
                                    $cupoOcup = optional($asignacion->paralelo)->cupo_ocupado ?? 0;
                                    $porcentaje = $cupoMax > 0 ? round(($cupoOcup / $cupoMax) * 100) : 0;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $porcentaje }}%"></div>
                            </div>
                            <small class="text-muted">{{ $cupoOcup }} de {{ $cupoMax }} plazas ocupadas ({{ $porcentaje }}%)</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver al Listado
                    </a>
                    <a href="{{ route('admin.asignaciones.edit', $asignacion->id) }}" class="btn btn-warning font-weight-bold">
                        <i class="fas fa-pencil-alt mr-1"></i> Editar Asignación
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
