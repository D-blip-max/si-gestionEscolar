@extends('adminlte::page')

@section('title', 'Panel Docente - Instituto CEI')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-8 d-flex align-items-center">
                <img src="{{ asset(optional($docente)->foto ?: 'img/default-avatar.png') }}"
                     onerror="this.onerror=null;this.src='{{ asset('img/default-avatar.png') }}';"
                     alt="Foto Docente"
                     class="img-circle elevation-2 mr-3 bg-white p-1"
                     style="width: 58px; height: 58px; object-fit: cover;">
                <div>
                    <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.45rem;">
                        <i class="fas fa-chalkboard-teacher text-primary mr-1"></i>
                        {{ $docente ? $docente->nombres . ' ' . $docente->apellidos : auth()->user()->name }}
                    </h1>
                    <small class="text-muted font-weight-bold">
                        {{ optional($docente)->profesion ?? 'Docente Titular CEI - UAGRM' }}
                        @if(optional($docente)->ci)
                            | CI: {{ $docente->ci }}
                        @endif
                    </small>
                </div>
            </div>
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Dashboard Docente</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Banner de Ciclo Vigente -->
    <div class="alert alert-primary shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-calendar-check mr-2"></i>Ciclo Bimestral Vigente: {{ optional($cicloActivo)->nombre ?? 'Bimestre III - 2026 (May-Jun)' }}
                </h5>
                <small class="text-white-50">
                    Panel de seguimiento académico de grupos asignados, nómina de alumnos y registro de calificaciones modulares.
                </small>
            </div>
            <div class="mt-2 mt-md-0">
                <span class="badge badge-light text-primary font-weight-bold px-3 py-2">
                    <i class="fas fa-user-tie mr-1"></i> PERFIL DOCENTE TITULAR
                </span>
            </div>
        </div>
    </div>

    <!-- 4 KPIs Académicos del Docente -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $gruposActivos->count() }} <small style="font-size: 1rem;">/ {{ $totalGrupos }} total</small></h3>
                    <p>Grupos Asignados (Vigente / Hist.)</p>
                </div>
                <div class="icon"><i class="fas fa-chalkboard"></i></div>
                <a href="{{ route('docente.grupos.index') }}" class="small-box-footer">
                    Ver Mis Grupos <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary shadow-sm">
                <div class="inner">
                    <h3>{{ $totalAlumnos }} <small style="font-size: 1rem;">({{ $alumnosCicloActivo }} hoy)</small></h3>
                    <p>Total Alumnos en Mis Grupos</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('docente.grupos.index') }}" class="small-box-footer">
                    Nómina de Estudiantes <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $totalAprobados }} <small style="font-size: 1rem;">({{ $porcentajeAprobacion }}%)</small></h3>
                    <p>Alumnos Aprobados (Nota >= 51)</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
                <a href="{{ route('docente.actas.index') }}" class="small-box-footer">
                    Actas Oficiales PDF <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>{{ $totalReprobados }} <small style="font-size: 1rem;">| {{ $totalPendientes }} pend.</small></h3>
                    <p>Reprobados (< 51) / En Curso</p>
                </div>
                <div class="icon"><i class="fas fa-user-clock"></i></div>
                <a href="{{ route('docente.grupos.index') }}" class="small-box-footer">
                    Cargar Calificaciones <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tabla 1: Mis Grupos en el Ciclo Bimestral Vigente -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-bolt text-primary mr-1"></i>
                Mis Grupos en el Ciclo Vigente ({{ optional($cicloActivo)->nombre ?? 'Bimestre Activo' }})
            </h5>
            <a href="{{ route('docente.grupos.index') }}" class="btn btn-outline-primary btn-xs font-weight-bold ml-auto">
                <i class="fas fa-list mr-1"></i> Ver Todos Mis Grupos
            </a>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Idioma / Módulo</th>
                        <th>Paralelo</th>
                        <th>Modalidad / Aula o Enlace</th>
                        <th>Turno y Horario</th>
                        <th class="text-center">Alumnos</th>
                        <th class="text-center">Aprobados / Reprobados / Pend.</th>
                        <th class="text-center">Promedio</th>
                        <th class="text-right">Acciones Directas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gruposActivos as $asig)
                        @php
                            $p = $asig->paralelo;
                            $inscritos = $p->inscripciones ?? collect();
                            $cantInscritos = $inscritos->count();
                            $aprobados = $inscritos->filter(fn($i) => optional($i->calificacion)->estado === 'APROBADO')->count();
                            $reprobados = $inscritos->filter(fn($i) => optional($i->calificacion)->estado === 'REPROBADO')->count();
                            $pendientes = $cantInscritos - ($aprobados + $reprobados);
                            $evaluados = $inscritos->filter(fn($i) => optional($i->calificacion)->estado && optional($i->calificacion)->estado !== 'PENDIENTE');
                            $promedioGrupo = $evaluados->count() > 0
                                ? round($evaluados->avg(fn($i) => optional($i->calificacion)->nota_final), 1)
                                : null;
                        @endphp
                        <tr>
                            <td>
                                <span class="badge badge-dark">{{ optional(optional($p->modulo)->idioma)->nombre }}</span>
                                <div class="font-weight-bold text-dark">{{ optional($p->modulo)->nombre }}</div>
                            </td>
                            <td class="font-weight-bold text-primary">{{ $p->nombre }}</td>
                            <td>
                                @if($p->modalidad === 'VIRTUAL')
                                    <span class="badge badge-info"><i class="fas fa-video mr-1"></i> VIRTUAL</span>
                                    @if($p->aula_o_enlace)
                                        <div class="small mt-1">
                                            <a href="{{ $p->aula_o_enlace }}" target="_blank" class="text-primary font-weight-bold">
                                                <i class="fas fa-external-link-alt mr-1"></i> Abrir Sala Meet
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-building mr-1"></i> PRESENCIAL</span>
                                    <div class="small text-muted mt-1">{{ $p->aula_o_enlace }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="font-weight-bold">{{ $p->turno }}</span>
                                <div class="small text-muted">
                                    <i class="far fa-clock mr-1"></i>{{ substr($p->hora_inicio, 0, 5) }} - {{ substr($p->hora_fin, 0, 5) }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-primary px-2 py-1" style="font-size: 0.9rem;">
                                    {{ $cantInscritos }} / {{ $p->cupo_maximo }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success mr-1" title="Aprobados"><i class="fas fa-check mr-1"></i>{{ $aprobados }}</span>
                                <span class="badge badge-danger mr-1" title="Reprobados"><i class="fas fa-times mr-1"></i>{{ $reprobados }}</span>
                                <span class="badge badge-warning text-dark" title="Pendientes de Nota"><i class="fas fa-hourglass-half mr-1"></i>{{ $pendientes }}</span>
                            </td>
                            <td class="text-center font-weight-bold">
                                @if($promedioGrupo !== null)
                                    <span class="{{ $promedioGrupo >= 51 ? 'text-success' : 'text-danger' }}">{{ $promedioGrupo }} pts</span>
                                @else
                                    <span class="text-muted small">En evaluación</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('docente.calificaciones.cargar', $p->id) }}" class="btn btn-primary font-weight-bold" title="Cargar o Editar Notas">
                                        <i class="fas fa-edit mr-1"></i> Calificar
                                    </a>
                                    <a href="{{ route('docente.grupos.show', $p->id) }}" class="btn btn-info" title="Ver Nómina de Alumnos">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <a href="{{ route('docente.actas.pdf', $p->id) }}" target="_blank" class="btn btn-danger" title="Descargar Acta Oficial PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle mr-1"></i> No tiene grupos asignados en el ciclo bimestral vigente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla 2: Historial de Grupos Dictados en Bimestres Anteriores -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title font-weight-bold mb-0">
                <i class="fas fa-history text-secondary mr-1"></i>
                Historial de Grupos Dictados y Resultados de Aprobación / Reprobación
            </h5>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-sm table-hover table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Ciclo Bimestral</th>
                        <th>Idioma y Módulo</th>
                        <th>Grupo / Modalidad</th>
                        <th class="text-center">Total Alumnos</th>
                        <th class="text-center">Aprobados (>= 51)</th>
                        <th class="text-center">Reprobados (< 51)</th>
                        <th class="text-center">Promedio Final</th>
                        <th class="text-center">Acta PDF</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gruposHistoricos as $asig)
                        @php
                            $p = $asig->paralelo;
                            $inscritos = $p->inscripciones ?? collect();
                            $cant = $inscritos->count();
                            $aprob = $inscritos->filter(fn($i) => optional($i->calificacion)->estado === 'APROBADO')->count();
                            $reprob = $inscritos->filter(fn($i) => optional($i->calificacion)->estado === 'REPROBADO')->count();
                            $prom = $cant > 0 ? round($inscritos->avg(fn($i) => optional($i->calificacion)->nota_final ?? 0), 1) : 0;
                        @endphp
                        <tr>
                            <td>
                                <span class="font-weight-bold">{{ optional($p->ciclo)->nombre }}</span>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ optional(optional($p->modulo)->idioma)->nombre }}</span>
                                {{ optional($p->modulo)->nombre }}
                            </td>
                            <td>
                                <strong>{{ $p->nombre }}</strong>
                                <small class="text-muted">({{ $p->modalidad }} - {{ $p->turno }})</small>
                            </td>
                            <td class="text-center font-weight-bold">{{ $cant }}</td>
                            <td class="text-center">
                                <span class="badge badge-success px-2">{{ $aprob }} aprobados</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $reprob > 0 ? 'badge-danger' : 'badge-light border text-muted' }} px-2">
                                    {{ $reprob }} reprobados
                                </span>
                            </td>
                            <td class="text-center font-weight-bold {{ $prom >= 51 ? 'text-success' : 'text-danger' }}">
                                {{ $prom }} pts
                            </td>
                            <td class="text-center">
                                <a href="{{ route('docente.actas.pdf', $p->id) }}" target="_blank" class="btn btn-outline-danger btn-xs font-weight-bold">
                                    <i class="fas fa-file-pdf mr-1"></i> Acta
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                Sin registros de ciclos bimestrales anteriores.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

