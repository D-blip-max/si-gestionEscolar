@extends('adminlte::page')

@section('title', 'Dashboard - Instituto CEI')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-8 d-flex align-items-center">
                <img src="{{ asset('img/logo_cei.png') }}" alt="Logo CEI UAGRM" class="img-circle elevation-2 mr-3 bg-white p-1" style="width: 55px; height: 55px; object-fit: contain;">
                <div>
                    <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.45rem;">Centro de Extensión de Idiomas - UAGRM</h1>
                    <small class="text-muted font-weight-bold">Sistema Integral de Gestión Académica y Modular CEI</small>
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
                <a href="{{ route('admin.estudiantes.index') }}" class="small-box-footer">Padrón de Estudiantes <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3 class="kpi-number">Bs. {{ number_format($totalRecaudado, 2) }}</h3>
                    <p>Recaudación {{ $gestionSeleccionada === 'TODAS' ? 'Total' : 'Gestión ' . $gestionSeleccionada }}</p>
                </div>
                <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <a href="{{ route('admin.boletas.index') }}" class="small-box-footer">Conciliación de Boletas <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3 class="kpi-number">{{ $totalDocentes }}</h3>
                    <p>Docentes Titulares</p>
                </div>
                <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                <a href="{{ route('admin.personal.index', ['tipo' => 'docente']) }}" class="small-box-footer">Padrón de Personal <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3 class="kpi-number">{{ $totalInscripciones }}</h3>
                    <p>Matrículas {{ $gestionSeleccionada === 'TODAS' ? 'Registradas' : 'Gestión ' . $gestionSeleccionada }}</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                <a href="{{ route('admin.inscripciones.index') }}" class="small-box-footer">Listado de Matrículas <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Módulos de Acceso Rápido -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title text-bold mb-0"><i class="fas fa-th-large text-primary mr-1"></i> Módulos Principales del Sistema</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                        <h6 class="font-weight-bold">Seguridad y Usuarios</h6>
                        <small class="text-muted d-block mb-2">Configuración, Usuarios y Roles</small>
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-primary btn-xs">Administrar</a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-book-open fa-2x text-success mb-2"></i>
                        <h6 class="font-weight-bold">Catálogos Académicos</h6>
                        <small class="text-muted d-block mb-2">Idiomas, Módulos 1-9 y Paralelos</small>
                        <a href="{{ route('admin.idiomas.index') }}" class="btn btn-outline-success btn-xs">Catálogos</a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-chalkboard-teacher fa-2x text-info mb-2"></i>
                        <h6 class="font-weight-bold">Personal y Docentes</h6>
                        <small class="text-muted d-block mb-2">Padrón Docente y Asignaciones</small>
                        <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-outline-info btn-xs">Asignaciones</a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-user-graduate fa-2x text-warning mb-2"></i>
                        <h6 class="font-weight-bold">Padrón de Estudiantes</h6>
                        <small class="text-muted d-block mb-2">Padrón, Boletas y Kárdex</small>
                        <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-outline-warning btn-xs">Estudiantes</a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-clipboard-check fa-2x text-danger mb-2"></i>
                        <h6 class="font-weight-bold">Inscripciones y Matrícula</h6>
                        <small class="text-muted d-block mb-2">Inscripción Modular y Ficha PDF</small>
                        <a href="{{ route('admin.inscripciones.create') }}" class="btn btn-outline-danger btn-xs">Nueva Matrícula</a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-marker fa-2x text-purple mb-2" style="color: #6f42c1;"></i>
                        <h6 class="font-weight-bold">Control de Notas</h6>
                        <small class="text-muted d-block mb-2">Planilla Docente y Actas PDF</small>
                        <a href="{{ route('docente.grupos.index') }}" class="btn btn-outline-dark btn-xs">Calificaciones</a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-laptop-code fa-2x text-indigo mb-2" style="color: #6610f2;"></i>
                        <h6 class="font-weight-bold">Portal del Estudiante</h6>
                        <small class="text-muted d-block mb-2">Mi Aula, Avance y Certificados</small>
                        <a href="{{ route('estudiante.clases.index') }}" class="btn btn-outline-info btn-xs">Mi Aula</a>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="border rounded p-3 text-center h-100 hover-card bg-light">
                        <i class="fas fa-chart-line fa-2x text-teal mb-2" style="color: #20c997;"></i>
                        <h6 class="font-weight-bold">Reportes y Finanzas</h6>
                        <small class="text-muted d-block mb-2">Conciliación, Modalidad y Graduados</small>
                        <a href="{{ route('admin.reportes.conciliacion') }}" class="btn btn-outline-success btn-xs">Reportes</a>
                    </div>
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
