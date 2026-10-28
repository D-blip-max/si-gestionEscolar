@extends('adminlte::page')

@section('title', 'Dashboard - Instituto CEI')

@section('content_header')
    @php
        $rolPrincipal = optional(auth()->user()->roles->first())->name ?? 'ADMINISTRATIVO';
    @endphp
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-8 d-flex align-items-center">
                <img src="{{ asset('img/logo_cei.png') }}" alt="Logo CEI UAGRM" class="img-circle elevation-2 mr-3 bg-white p-1" style="width: 55px; height: 55px; object-fit: contain;">
                <div>
                    <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.45rem;">Centro de Extensión de Idiomas - UAGRM</h1>
                    <small class="text-muted font-weight-bold">
                        Sistema Integral de Gestión Académica y Modular CEI —
                        <span class="badge badge-dark px-2 py-1 ml-1"><i class="fas fa-user-shield mr-1"></i> {{ $rolPrincipal }}</span>
                    </small>
                </div>
            </div>
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Banner de Ciclo Activo y Filtro por Gestión Anual -->
    <div class="alert alert-primary shadow-sm mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="mb-2 mb-md-0">
                <h5 class="mb-0 font-weight-bold">
                    <i class="fas fa-calendar-check mr-2"></i>Ciclo Bimestral Vigente: {{ optional($cicloActivo)->nombre ?? 'Bimestre III - 2026 (May-Jun)' }}
                </h5>
                <small class="text-white-50">
                    Mostrando indicadores financieros y académicos de:
                    <strong class="text-white">
                        {{ $gestionSeleccionada === 'TODAS' ? 'Todas las Gestiones (Acumulado Histórico)' : 'Gestión Anual ' . $gestionSeleccionada }}
                    </strong>
                </small>
            </div>
            <div class="d-flex align-items-center">
                <form action="{{ route('home') }}" method="GET" class="form-inline mr-2">
                    <label for="select-gestion" class="text-white font-weight-bold mr-2 mb-0 small">
                        <i class="fas fa-filter mr-1"></i>Ver Gestión:
                    </label>
                    <select name="gestion" id="select-gestion" class="form-control form-control-sm font-weight-bold text-primary border-0 shadow-sm" onchange="this.form.submit()">
                        @foreach ($gestionesDisponibles as $g)
                            <option value="{{ $g }}" {{ (string)$gestionSeleccionada === (string)$g ? 'selected' : '' }}>
                                Gestión {{ $g }}
                            </option>
                        @endforeach
                        <option value="TODAS" {{ $gestionSeleccionada === 'TODAS' ? 'selected' : '' }}>
                            Todas (2025 - 2026)
                        </option>
                    </select>
                </form>
                <span class="badge badge-light text-primary font-weight-bold px-3 py-2">ESTADO ACTIVO</span>
            </div>
        </div>
    </div>

    <!-- Info-Boxes Superiores -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3 class="kpi-number">{{ $totalEstudiantes }}</h3>
                    <p>Estudiantes {{ $gestionSeleccionada === 'TODAS' ? 'Autónomos' : 'Activos (' . $gestionSeleccionada . ')' }}</p>
                </div>
                <div class="icon"><i class="fas fa-user-graduate"></i></div>
                @can('admin.estudiantes.index')
                    <a href="{{ route('admin.estudiantes.index') }}" class="small-box-footer">Padrón de Estudiantes <i class="fas fa-arrow-circle-right"></i></a>
                @elseif(auth()->user()->can('admin.estudiantes.kardex'))
                    <a href="{{ route('admin.estudiantes.kardex.general') }}" class="small-box-footer">Kárdex Estudiantil <i class="fas fa-arrow-circle-right"></i></a>
                @else
                    <span class="small-box-footer">Padrón Estudiantil CEI</span>
                @endcan
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3 class="kpi-number">Bs. {{ number_format($totalRecaudado, 2) }}</h3>
                    <p>
                        Recaudación {{ $gestionSeleccionada === 'TODAS' ? 'Total' : 'Gestión ' . $gestionSeleccionada }}
                        @if(isset($boletasDisponibles) && $boletasDisponibles > 0)
                            ({{ $boletasDisponibles }} disp.)
                        @endif
                    </p>
                </div>
                <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                @can('admin.boletas.index')
                    <a href="{{ route('admin.boletas.index') }}" class="small-box-footer">Conciliación de Boletas <i class="fas fa-arrow-circle-right"></i></a>
                @elseif(auth()->user()->can('admin.reportes.conciliacion'))
                    <a href="{{ route('admin.reportes.conciliacion') }}" class="small-box-footer">Reporte de Recaudación <i class="fas fa-arrow-circle-right"></i></a>
                @else
                    <span class="small-box-footer">Tesorería Fiscalizada CEI</span>
                @endcan
            </div>
        </div>

        <div class="col-lg-3 col-6">
            @can('admin.personal.index')
                <div class="small-box bg-warning shadow-sm">
                    <div class="inner">
                        <h3 class="kpi-number">{{ $totalDocentes }}</h3>
                        <p>Docentes Titulares</p>
                    </div>
                    <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <a href="{{ route('admin.personal.index', ['tipo' => 'docente']) }}" class="small-box-footer">Padrón de Personal <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            @else
                <div class="small-box bg-warning shadow-sm">
                    <div class="inner">
                        <h3 class="kpi-number">{{ $totalParalelos }}</h3>
                        <p>Grupos / Paralelos ({{ $gestionSeleccionada === 'TODAS' ? 'Total' : $gestionSeleccionada }})</p>
                    </div>
                    <div class="icon"><i class="fas fa-chalkboard"></i></div>
                    @can('admin.paralelos.index')
                        <a href="{{ route('admin.paralelos.index') }}" class="small-box-footer">Ver Grupos y Cupos <i class="fas fa-arrow-circle-right"></i></a>
                    @else
                        <span class="small-box-footer">Oferta Modular CEI</span>
                    @endcan
                </div>
            @endcan
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3 class="kpi-number">{{ $totalInscripciones }}</h3>
                    <p>Matrículas {{ $gestionSeleccionada === 'TODAS' ? 'Registradas' : 'Gestión ' . $gestionSeleccionada }}</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                @can('admin.inscripciones.index')
                    <a href="{{ route('admin.inscripciones.index') }}" class="small-box-footer">Listado de Matrículas <i class="fas fa-arrow-circle-right"></i></a>
                @elseif(auth()->user()->can('admin.reportes.inscritos'))
                    <a href="{{ route('admin.reportes.inscritos') }}" class="small-box-footer">Reporte de Inscritos <i class="fas fa-arrow-circle-right"></i></a>
                @else
                    <span class="small-box-footer">Registro Académico CEI</span>
                @endcan
            </div>
        </div>
    </div>

    <!-- Módulos de Acceso Rápido (Adaptados según los permisos del Rol) -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title text-bold mb-0">
                <i class="fas fa-th-large text-primary mr-1"></i>
                Módulos Habilitados para {{ $rolPrincipal }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @canany(['admin.usuarios.index', 'admin.roles.index', 'admin.configuracion.index'])
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                        <h6 class="font-weight-bold">Seguridad y Configuración</h6>
                        <small class="text-muted d-block mb-2">Configuración, Usuarios y Roles</small>
                        @can('admin.usuarios.index')
                            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-primary btn-xs">Administrar</a>
                        @else
                            <a href="{{ route('admin.configuracion.index') }}" class="btn btn-outline-primary btn-xs">Configuración</a>
                        @endcan
                    </div>
                </div>
                @endcanany

                @canany(['admin.idiomas.index', 'admin.modulos.index', 'admin.paralelos.index'])
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-book-open fa-2x text-success mb-2"></i>
                        <h6 class="font-weight-bold">Catálogos Académicos</h6>
                        <small class="text-muted d-block mb-2">Idiomas, Módulos y Paralelos</small>
                        @can('admin.idiomas.index')
                            <a href="{{ route('admin.idiomas.index') }}" class="btn btn-outline-success btn-xs">Catálogos</a>
                        @else
                            <a href="{{ route('admin.paralelos.index') }}" class="btn btn-outline-success btn-xs">Grupos y Cupos</a>
                        @endcan
                    </div>
                </div>
                @endcanany

                @canany(['admin.personal.index', 'admin.asignaciones.index', 'admin.personal.formaciones.index'])
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-chalkboard-teacher fa-2x text-info mb-2"></i>
                        <h6 class="font-weight-bold">Personal y Docentes</h6>
                        <small class="text-muted d-block mb-2">Padrón Docente y Asignaciones</small>
                        @can('admin.asignaciones.index')
                            <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-outline-info btn-xs">Asignaciones</a>
                        @else
                            <a href="{{ route('admin.personal.index', ['tipo' => 'docente']) }}" class="btn btn-outline-info btn-xs">Padrón Docente</a>
                        @endcan
                    </div>
                </div>
                @endcanany

                @canany(['admin.estudiantes.index', 'admin.boletas.index', 'admin.estudiantes.kardex'])
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-user-graduate fa-2x text-warning mb-2"></i>
                        <h6 class="font-weight-bold">Estudiantes y Boletas</h6>
                        <small class="text-muted d-block mb-2">Padrón, Boletas (Bs. 250) y Kárdex</small>
                        @can('admin.boletas.index')
                            <a href="{{ route('admin.boletas.index') }}" class="btn btn-outline-warning btn-xs mr-1">Boletas</a>
                        @endcan
                        @can('admin.estudiantes.index')
                            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-outline-dark btn-xs">Padrón</a>
                        @elseif(auth()->user()->can('admin.estudiantes.kardex'))
                            <a href="{{ route('admin.estudiantes.kardex.general') }}" class="btn btn-outline-warning btn-xs">Kárdex</a>
                        @endcan
                    </div>
                </div>
                @endcanany

                @canany(['admin.inscripciones.create', 'admin.inscripciones.index'])
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-clipboard-check fa-2x text-danger mb-2"></i>
                        <h6 class="font-weight-bold">Inscripciones y Matrícula</h6>
                        <small class="text-muted d-block mb-2">Inscripción Modular y Ficha PDF</small>
                        @can('admin.inscripciones.create')
                            <a href="{{ route('admin.inscripciones.create') }}" class="btn btn-outline-danger btn-xs">Nueva Matrícula</a>
                        @else
                            <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-outline-danger btn-xs">Matrículas</a>
                        @endcan
                    </div>
                </div>
                @endcanany

                @canany(['docente.grupos.index', 'docente.actas.index'])
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-marker fa-2x text-purple mb-2" style="color: #6f42c1;"></i>
                        <h6 class="font-weight-bold">Control de Notas y Actas</h6>
                        <small class="text-muted d-block mb-2">Planilla Docente y Actas PDF</small>
                        @can('docente.grupos.index')
                            <a href="{{ route('docente.grupos.index') }}" class="btn btn-outline-dark btn-xs">Calificaciones</a>
                        @else
                            <a href="{{ route('docente.actas.index') }}" class="btn btn-outline-dark btn-xs">Actas PDF</a>
                        @endcan
                    </div>
                </div>
                @endcanany

                @canany(['admin.reportes.conciliacion', 'admin.reportes.inscritos', 'admin.reportes.graduados'])
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-chart-line fa-2x text-teal mb-2" style="color: #20c997;"></i>
                        <h6 class="font-weight-bold">Reportes y Finanzas</h6>
                        <small class="text-muted d-block mb-2">Conciliación, Modalidad y Graduados</small>
                        @can('admin.reportes.conciliacion')
                            <a href="{{ route('admin.reportes.conciliacion') }}" class="btn btn-outline-success btn-xs">Conciliación</a>
                        @elseif(auth()->user()->can('admin.reportes.inscritos'))
                            <a href="{{ route('admin.reportes.inscritos') }}" class="btn btn-outline-success btn-xs">Inscritos</a>
                        @else
                            <a href="{{ route('admin.reportes.graduados') }}" class="btn btn-outline-success btn-xs">Graduados</a>
                        @endcan
                    </div>
                </div>
                @endcanany
            </div>
        </div>
    </div>

    <!-- Paneles Operativos del Ciclo Vigente (Para Superadministrador y Personal Administrativo) -->
    <div class="row">
        <!-- Columna Izquierda: Estado de Cupos de Grupos del Ciclo Vigente -->
        <div class="col-lg-6 mb-4">
            <div class="card card-outline card-primary shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-chalkboard text-primary mr-1"></i>
                        Ocupación de Grupos — Ciclo Vigente
                    </h5>
                    @can('admin.paralelos.index')
                        <a href="{{ route('admin.paralelos.index') }}" class="btn btn-outline-primary btn-xs font-weight-bold ml-auto">
                            Ver Todos
                        </a>
                    @endcan
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Idioma / Nivel</th>
                                <th>Grupo</th>
                                <th>Modalidad / Turno</th>
                                <th class="text-center">Ocupación de Cupo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paralelosCicloActivo ?? [] as $par)
                                @php
                                    $porcCupo = $par->cupo_maximo > 0 ? round(($par->cupo_ocupado / $par->cupo_maximo) * 100) : 0;
                                    $colorBarra = $porcCupo >= 90 ? 'bg-danger' : ($porcCupo >= 60 ? 'bg-warning' : 'bg-success');
                                @endphp
                                <tr>
                                    <td>
                                        <span class="badge badge-dark">{{ optional(optional($par->modulo)->idioma)->codigo }}</span>
                                        <strong class="text-dark">{{ optional($par->modulo)->nombre }}</strong>
                                    </td>
                                    <td class="font-weight-bold text-primary">{{ $par->nombre }}</td>
                                    <td>
                                        <small class="font-weight-bold">{{ $par->modalidad }}</small>
                                        <small class="text-muted">({{ $par->turno }})</small>
                                    </td>
                                    <td class="text-center" style="min-width: 120px;">
                                        <small class="font-weight-bold">{{ $par->cupo_ocupado }} / {{ $par->cupo_maximo }}</small>
                                        <div class="progress progress-xs mt-1">
                                            <div class="progress-bar {{ $colorBarra }}" style="width: {{ min(100, $porcCupo) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Sin grupos registrados en el ciclo activo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Últimas Matrículas y Boletas Registradas -->
        <div class="col-lg-6 mb-4">
            <div class="card card-outline card-success shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0">
                        <i class="fas fa-clipboard-check text-success mr-1"></i>
                        Últimas Matrículas y Boletas Conciliadas
                    </h5>
                    @can('admin.inscripciones.index')
                        <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-outline-success btn-xs font-weight-bold ml-auto">
                            Ver Historial
                        </a>
                    @endcan
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Estudiante</th>
                                <th>Módulo / Grupo</th>
                                <th>Boleta Bancaria</th>
                                <th class="text-right">Ficha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasInscripciones ?? [] as $ins)
                                <tr>
                                    <td>
                                        <strong class="text-dark">{{ optional($ins->estudiante)->nombres }} {{ optional($ins->estudiante)->apellidos }}</strong>
                                        <small class="text-muted d-block">CI: {{ optional($ins->estudiante)->ci }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ optional(optional($ins->modulo)->idioma)->codigo }}</span>
                                        {{ optional($ins->modulo)->nombre }}
                                        <small class="text-muted d-block">{{ optional($ins->paralelo)->nombre }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-dark">{{ optional($ins->boletaPago)->numero_boleta }}</span>
                                        <small class="text-success font-weight-bold d-block">Bs. {{ number_format(optional($ins->boletaPago)->monto ?? 250, 2) }}</small>
                                    </td>
                                    <td class="text-right">
                                        @can('admin.inscripciones.index')
                                            <a href="{{ route('admin.inscripciones.pdf', $ins->id) }}" target="_blank" class="btn btn-outline-danger btn-xs" title="Descargar Ficha PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @else
                                            <span class="badge badge-success">ACTIVA</span>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Sin matrículas recientes registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .small-box .inner h3.kpi-number {
        font-size: 1.85rem;
        white-space: nowrap;
    }
    .hover-card:hover {
        background-color: #ffffff !important;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        border-color: #007bff !important;
        transition: all 0.2s ease-in-out;
    }
</style>
@stop
