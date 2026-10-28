@extends('adminlte::page')

@section('title', 'Mis Grupos Asignados')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-chalkboard-teacher text-primary mr-2"></i>Mis Grupos Asignados</h1>
                <small class="text-muted">Separación de Grupos Actuales en Curso y Grupos Dictados en Ciclos Anteriores (Actas Cerradas)</small>
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

    @php
        $gruposActuales = $asignaciones->filter(function ($asig) {
            return optional(optional($asig->paralelo)->ciclo)->estado === 'ACTIVO';
        });

        $gruposAnteriores = $asignaciones->filter(function ($asig) {
            return optional(optional($asig->paralelo)->ciclo)->estado !== 'ACTIVO';
        });
    @endphp

    <!-- Pestañas de Navegación: Grupos Actuales vs Historial de Actas Cerradas -->
    <div class="card card-primary card-outline card-outline-tabs shadow-sm">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="docenteGruposTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" id="actuales-tab" data-toggle="pill" href="#tab-actuales" role="tab">
                        <i class="fas fa-bolt text-primary mr-1"></i>
                        Grupos Actuales en Curso (Ciclo Vigente)
                        <span class="badge badge-primary ml-1">{{ $gruposActuales->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="anteriores-tab" data-toggle="pill" href="#tab-anteriores" role="tab">
                        <i class="fas fa-history text-secondary mr-1"></i>
                        Grupos Dictados Tiempo Atrás (Actas Cerradas)
                        <span class="badge badge-secondary ml-1">{{ $gruposAnteriores->count() }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="docenteGruposTabContent">
                <!-- PESTAÑA 1: GRUPOS ACTUALES EN CURSO -->
                <div class="tab-pane fade show active" id="tab-actuales" role="tabpanel">
                    <div class="row">
                        @forelse ($gruposActuales as $asig)
                            @php
                                $paralelo = $asig->paralelo;
                                $esVirtual = strtoupper(optional($paralelo)->modalidad) === 'VIRTUAL';
                                $inscritos = optional($paralelo)->inscripciones ?? collect();
                                $alumnosCount = $inscritos->count();
                                $aprobadosCount = $inscritos->filter(fn($i) => optional($i->calificacion)->estado === 'APROBADO')->count();
                                $reprobadosCount = $inscritos->filter(fn($i) => optional($i->calificacion)->estado === 'REPROBADO')->count();
                                $pendientesCount = $alumnosCount - ($aprobadosCount + $reprobadosCount);
                                $actaCompleta = ($alumnosCount > 0 && $pendientesCount === 0);
                            @endphp
                            <div class="col-12 col-md-6 col-lg-4 mb-4">
                                <div class="card card-outline {{ $esVirtual ? 'card-info' : 'card-primary' }} shadow-sm h-100">
                                    <div class="card-header bg-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if($esVirtual)
                                                <span class="badge badge-info px-2 py-1"><i class="fas fa-laptop mr-1"></i> VIRTUAL</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-building mr-1"></i> PRESENCIAL</span>
                                            @endif

                                            @if($actaCompleta)
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-double mr-1"></i> NOTAS COMPLETAS</span>
                                            @else
                                                <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold"><i class="fas fa-unlock mr-1"></i> ACTA ABIERTA ({{ $pendientesCount }} pend.)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <h4 class="text-bold text-primary mb-1">
                                            {{ optional(optional($paralelo)->modulo)->idioma->nombre }} - {{ optional(optional($paralelo)->modulo)->nombre }}
                                        </h4>
                                        <div class="text-muted text-sm mb-3">
                                            <i class="far fa-calendar-check text-success mr-1"></i>
                                            <strong class="text-success">{{ optional(optional($paralelo)->ciclo)->nombre ?? 'Ciclo Vigente' }}</strong> |
                                            <strong>Grupo {{ optional($paralelo)->nombre }}</strong>
                                        </div>

                                        <ul class="list-group list-group-unbordered text-sm mb-3">
                                            <li class="list-group-item py-1">
                                                <b><i class="far fa-clock text-muted mr-1"></i> Turno / Horario:</b>
                                                <span class="float-right text-dark font-weight-bold">
                                                    {{ optional($paralelo)->turno }} ({{ substr(optional($paralelo)->hora_inicio, 0, 5) }} - {{ substr(optional($paralelo)->hora_fin, 0, 5) }})
                                                </span>
                                            </li>
                                            <li class="list-group-item py-1">
                                                <b><i class="fas {{ $esVirtual ? 'fa-video' : 'fa-door-open' }} text-muted mr-1"></i> {{ $esVirtual ? 'Sala Virtual:' : 'Aula Asignada:' }}</b>
                                                <span class="float-right text-truncate" style="max-width: 170px;">
                                                    @if($esVirtual && optional($paralelo)->aula_o_enlace)
                                                        <a href="{{ optional($paralelo)->aula_o_enlace }}" target="_blank" class="text-info font-weight-bold">
                                                            <i class="fas fa-external-link-alt"></i> Entrar a Meet
                                                        </a>
                                                    @else
                                                        {{ optional($paralelo)->aula_o_enlace ?? 'Aula General' }}
                                                    @endif
                                                </span>
                                            </li>
                                            <li class="list-group-item py-1">
                                                <b><i class="fas fa-users text-muted mr-1"></i> Inscritos / Calificados:</b>
                                                <span class="float-right">
                                                    <span class="badge badge-primary">{{ $alumnosCount }} Alumnos</span>
                                                    <span class="badge badge-success" title="Aprobados">{{ $aprobadosCount }} Aprob.</span>
                                                    <span class="badge badge-danger" title="Reprobados">{{ $reprobadosCount }} Reprob.</span>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="card-footer bg-white border-top-0 pt-0">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('docente.calificaciones.cargar', $paralelo->id) }}" class="btn btn-primary btn-sm flex-fill mr-1 font-weight-bold">
                                                <i class="fas fa-edit mr-1"></i> Cargar Notas
                                            </a>
                                            <a href="{{ route('docente.grupos.show', $paralelo->id) }}" class="btn btn-outline-info btn-sm flex-fill mx-1">
                                                <i class="fas fa-users mr-1"></i> Alumnos
                                            </a>
                                            <a href="{{ route('docente.actas.pdf', $paralelo->id) }}" target="_blank" class="btn btn-outline-danger btn-sm" title="Descargar Acta PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info py-4 text-center">
                                    <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-secondary"></i>
                                    <h5>No tiene grupos asignados en el ciclo bimestral vigente.</h5>
                                    <p class="mb-0 text-muted">Revise la pestaña de "Grupos Dictados Tiempo Atrás" para consultar sus actas anteriores.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- PESTAÑA 2: GRUPOS DICTADOS TIEMPO ATRÁS (ACTAS CERRADAS) -->
                <div class="tab-pane fade" id="tab-anteriores" role="tabpanel">
                    <div class="row">
                        @forelse ($gruposAnteriores as $asig)
                            @php
                                $paralelo = $asig->paralelo;
                                $esVirtual = strtoupper(optional($paralelo)->modalidad) === 'VIRTUAL';
                                $inscritos = optional($paralelo)->inscripciones ?? collect();
                                $alumnosCount = $inscritos->count();
                                $aprobadosCount = $inscritos->filter(fn($i) => optional($i->calificacion)->estado === 'APROBADO')->count();
                                $reprobadosCount = $inscritos->filter(fn($i) => optional($i->calificacion)->estado === 'REPROBADO')->count();
                            @endphp
                            <div class="col-12 col-md-6 col-lg-4 mb-4">
                                <div class="card card-outline card-secondary shadow-sm h-100 bg-light">
                                    <div class="card-header bg-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge badge-secondary px-2 py-1">
                                                <i class="fas fa-lock mr-1"></i> ACTA CERRADA / HISTÓRICO
                                            </span>
                                            <span class="badge badge-light border text-dark font-weight-bold">
                                                {{ optional(optional($paralelo)->ciclo)->nombre }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <h4 class="text-bold text-dark mb-1">
                                            {{ optional(optional($paralelo)->modulo)->idioma->nombre }} - {{ optional(optional($paralelo)->modulo)->nombre }}
                                        </h4>
                                        <div class="text-muted text-sm mb-3">
                                            <strong>Grupo {{ optional($paralelo)->nombre }}</strong> |
                                            Modalidad: {{ optional($paralelo)->modalidad }} ({{ optional($paralelo)->turno }})
                                        </div>

                                        <ul class="list-group list-group-unbordered text-sm mb-3">
                                            <li class="list-group-item bg-transparent py-1">
                                                <b><i class="fas fa-users text-muted mr-1"></i> Total Alumnos:</b>
                                                <span class="float-right font-weight-bold">{{ $alumnosCount }} Alumnos</span>
                                            </li>
                                            <li class="list-group-item bg-transparent py-1">
                                                <b><i class="fas fa-check-circle text-success mr-1"></i> Aprobados (>= 51):</b>
                                                <span class="float-right badge badge-success px-2">{{ $aprobadosCount }} Aprobados</span>
                                            </li>
                                            <li class="list-group-item bg-transparent py-1">
                                                <b><i class="fas fa-times-circle text-danger mr-1"></i> Reprobados (< 51):</b>
                                                <span class="float-right badge badge-danger px-2">{{ $reprobadosCount }} Reprobados</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="card-footer bg-white border-top-0 pt-0">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('docente.actas.pdf', $paralelo->id) }}" target="_blank" class="btn btn-danger btn-sm flex-fill mr-1 font-weight-bold">
                                                <i class="fas fa-file-pdf mr-1"></i> Imprimir Acta Cerrada
                                            </a>
                                            <a href="{{ route('docente.grupos.show', $paralelo->id) }}" class="btn btn-outline-secondary btn-sm flex-fill ml-1">
                                                <i class="fas fa-eye mr-1"></i> Ver Nómina
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-secondary py-4 text-center">
                                    <i class="fas fa-history fa-2x mb-2"></i>
                                    <h6>No registra grupos dictados en ciclos anteriores.</h6>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
