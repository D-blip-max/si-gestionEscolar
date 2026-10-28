@extends('adminlte::page')

@section('title', 'Lista de Estudiantes del Grupo')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-users text-info mr-2"></i>Nómina de Estudiantes</h1>
                <small class="text-muted">Lista Oficial de Alumnos Inscritos en el Grupo Modular</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('docente.grupos.index') }}">Mis Grupos</a></li>
                    <li class="breadcrumb-item active">Nómina</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-info shadow-sm mb-3">
        <div class="card-header bg-white">
            <h4 class="card-title text-bold mb-0">
                <i class="fas fa-layer-group text-primary mr-1"></i>
                {{ optional($paralelo->modulo->idioma)->nombre }} - {{ optional($paralelo->modulo)->nombre }} [Grupo {{ $paralelo->nombre }}]
            </h4>
            <div class="card-tools">
                <a href="{{ route('docente.calificaciones.cargar', $paralelo->id) }}" class="btn btn-primary btn-sm font-weight-bold">
                    <i class="fas fa-edit mr-1"></i> Cargar Calificaciones
                </a>
                <a href="{{ route('docente.actas.pdf', $paralelo->id) }}" target="_blank" class="btn btn-danger btn-sm ml-2">
                    <i class="fas fa-file-pdf mr-1"></i> Acta de Notas (PDF)
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row text-sm">
                <div class="col-md-3"><strong>Ciclo:</strong> {{ optional($paralelo->ciclo)->nombre }}</div>
                <div class="col-md-3"><strong>Modalidad:</strong> <span class="badge badge-info">{{ $paralelo->modalidad }}</span></div>
                <div class="col-md-3"><strong>Horario:</strong> {{ $paralelo->turno }} ({{ substr($paralelo->hora_inicio,0,5) }} - {{ substr($paralelo->hora_fin,0,5) }})</div>
                <div class="col-md-3"><strong>{{ strtoupper($paralelo->modalidad) === 'VIRTUAL' ? 'Enlace:' : 'Aula:' }}</strong> {{ $paralelo->aula_o_enlace ?? 'Aula General' }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 text-center">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Código CEI</th>
                            <th class="text-left">Estudiante</th>
                            <th>Cédula (CI)</th>
                            <th>Contacto / WhatsApp</th>
                            <th>Nota Final</th>
                            <th>Condición</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paralelo->inscripciones as $ins)
                            @php
                                $nota = optional($ins->calificacion)->nota_final;
                                $foto = ($ins->estudiante && $ins->estudiante->foto && file_exists(public_path($ins->estudiante->foto)))
                                    ? asset($ins->estudiante->foto)
                                    : asset('img/default-avatar.png');
                            @endphp
                            <tr>
                                <td class="align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-light border font-weight-bold">{{ optional($ins->estudiante)->codigo_estudiante ?? 'CEI-'.$ins->estudiante_id }}</span>
                                </td>
                                <td class="align-middle text-left">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $foto }}" class="img-circle elevation-1 mr-2" style="width: 35px; height: 35px; object-fit: cover;" alt="Avatar">
                                        <div>
                                            <strong class="text-dark">{{ optional($ins->estudiante)->apellidos }} {{ optional($ins->estudiante)->nombres }}</strong><br>
                                            <small class="text-muted"><i class="fas fa-university mr-1"></i> {{ optional($ins->estudiante)->carrera_facultad ?? 'Particular' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle font-weight-bold">{{ optional($ins->estudiante)->ci }}</td>
                                <td class="align-middle text-sm"><i class="fab fa-whatsapp text-success mr-1"></i> {{ optional($ins->estudiante)->telefono }}</td>
                                <td class="align-middle font-weight-bold">
                                    @if($nota !== null)
                                        <span class="{{ $nota >= 51 ? 'text-success' : 'text-danger' }}">{{ $nota }} pts</span>
                                    @else
                                        <span class="text-muted">Pendiente</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($nota !== null)
                                        @if($nota >= 51)
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> APROBADO</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> REPROBADO</span>
                                        @endif
                                    @else
                                        <span class="badge badge-primary px-2 py-1"><i class="fas fa-clock"></i> CURSANDO</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No hay estudiantes activos matriculados en este grupo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-right">
            <a href="{{ route('docente.grupos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Volver a Mis Grupos
            </a>
        </div>
    </div>
</div>
@stop

