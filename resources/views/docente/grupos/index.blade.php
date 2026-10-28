@extends('adminlte::page')

@section('title', 'Mis Grupos Asignados')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-chalkboard-teacher text-primary mr-2"></i>Mis Grupos Asignados</h1>
                <small class="text-muted">CU-16: Panel Docente de Grupos Presenciales y Virtuales</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Mis Grupos</li>
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
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        </script>
    @endif

    <div class="row">
        @forelse ($asignaciones as $asig)
            @php
                $paralelo = $asig->paralelo;
                $esVirtual = strtoupper(optional($paralelo)->modalidad) === 'VIRTUAL';
                $alumnosCount = optional($paralelo->inscripciones)->count() ?? 0;
            @endphp
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card card-outline {{ $esVirtual ? 'card-info' : 'card-primary' }} shadow-sm h-100">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            @if($esVirtual)
                                <span class="badge badge-info px-2 py-1"><i class="fas fa-laptop mr-1"></i> MODALIDAD VIRTUAL</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-building mr-1"></i> MODALIDAD PRESENCIAL</span>
                            @endif
                            <span class="badge badge-warning text-dark font-weight-bold">{{ optional($paralelo)->turno }}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <h4 class="text-bold text-primary mb-1">
                            {{ optional(optional($paralelo)->modulo)->idioma->nombre }} - {{ optional(optional($paralelo)->modulo)->nombre }}
                        </h4>
                        <div class="text-muted text-sm mb-3">
                            <i class="far fa-calendar-alt mr-1"></i> {{ optional(optional($paralelo)->ciclo)->nombre ?? 'Ciclo Bimestral' }} | 
                            <strong>Grupo {{ optional($paralelo)->nombre }}</strong>
                        </div>

                        <ul class="list-group list-group-unbordered text-sm mb-3">
                            <li class="list-group-item py-1">
                                <b><i class="far fa-clock text-muted mr-1"></i> Horario:</b>
                                <span class="float-right text-dark">
                                    {{ substr(optional($paralelo)->hora_inicio, 0, 5) }} a {{ substr(optional($paralelo)->hora_fin, 0, 5) }}
                                </span>
                            </li>
                            <li class="list-group-item py-1">
                                <b><i class="fas {{ $esVirtual ? 'fa-video' : 'fa-door-open' }} text-muted mr-1"></i> {{ $esVirtual ? 'Plataforma / Enlace:' : 'Aula Asignada:' }}</b>
                                <span class="float-right text-truncate" style="max-width: 170px;">
                                    @if($esVirtual && optional($paralelo)->aula_o_enlace)
                                        <a href="{{ optional($paralelo)->aula_o_enlace }}" target="_blank" class="text-info font-weight-bold">
                                            <i class="fas fa-external-link-alt"></i> Ir a Meet / Zoom
                                        </a>
                                    @else
                                        {{ optional($paralelo)->aula_o_enlace ?? 'Aula General' }}
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item py-1">
                                <b><i class="fas fa-users text-muted mr-1"></i> Estudiantes Inscritos:</b>
                                <span class="float-right badge badge-success px-2 py-1 font-weight-bold">{{ $alumnosCount }} Alumnos</span>
                            </li>
                        </ul>
                    </div>

                    <div class="card-footer bg-white border-top-0 pt-0">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('docente.calificaciones.cargar', $paralelo->id) }}" class="btn btn-primary btn-sm flex-fill mr-1 font-weight-bold">
                                <i class="fas fa-edit mr-1"></i> Cargar Notas
                            </a>
                            <a href="{{ route('docente.grupos.show', $paralelo->id) }}" class="btn btn-outline-info btn-sm flex-fill ml-1">
                                <i class="fas fa-users mr-1"></i> Ver Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info py-4 text-center">
                    <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-secondary"></i>
                    <h5>No tiene grupos asignados en el ciclo activo.</h5>
                    <p class="mb-0 text-muted">Comuníquese con la Dirección Académica para verificar la programación docente.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@stop

