@extends('adminlte::page')

@section('title', 'Reporte de Estudiantes Graduados')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user-graduate text-success mr-2"></i>Graduados, Aprobados y Reprobados</h1>
                <small class="text-muted">Estadísticas de Rendimiento Académico y Nómina de Titulación</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Graduados y Rendimiento</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Filtros de Graduación y Rendimiento -->
    <div class="card card-outline card-success shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.reportes.graduados') }}" class="form-inline">
                <label class="mr-2 font-weight-bold text-secondary"><i class="fas fa-filter text-primary mr-1"></i> Condición Académica:</label>
                <select name="tipo" class="form-control form-control-sm mr-3 font-weight-bold" onchange="this.form.submit()">
                    <option value="INTERMEDIO" {{ $tipoGraduacion === 'INTERMEDIO' ? 'selected' : '' }}>GRADUADOS - TÍTULO INTERMEDIO (Módulo 8)</option>
                    <option value="AVANZADO" {{ $tipoGraduacion === 'AVANZADO' ? 'selected' : '' }}>GRADUADOS - CERTIFICACIÓN AVANZADA (Módulo 9)</option>
                    <option value="APROBADOS" {{ $tipoGraduacion === 'APROBADOS' ? 'selected' : '' }}>ESTUDIANTES APROBADOS (Nota >= 51)</option>
                    <option value="REPROBADOS" {{ $tipoGraduacion === 'REPROBADOS' ? 'selected' : '' }}>ESTUDIANTES REPROBADOS (Nota < 51)</option>
                </select>

                <label class="mr-2 font-weight-bold text-secondary">Idioma:</label>
                <select name="idioma_id" class="form-control form-control-sm mr-3" onchange="this.form.submit()">
                    <option value="">-- Todos los Idiomas --</option>
                    @foreach($idiomas as $idioma)
                        <option value="{{ $idioma->id }}" {{ $idiomaId == $idioma->id ? 'selected' : '' }}>{{ $idioma->nombre }}</option>
                    @endforeach
                </select>

                <div class="ml-auto">
                    <a href="{{ route('admin.reportes.graduados_pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm font-weight-bold">
                        <i class="fas fa-file-pdf mr-1"></i> Exportar Reporte Oficial (PDF)
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- 4 Indicadores Globales: Graduados, Aprobados, Reprobados y Cursando -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm bg-warning">
                <span class="info-box-icon"><i class="fas fa-user-graduate"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Total Graduados</span>
                    <span class="info-box-number" style="font-size: 22px;">{{ $totalGraduados }} Alumnos</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm bg-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Módulos Aprobados</span>
                    <span class="info-box-number" style="font-size: 22px;">{{ $totalAprobados }} Aprobados</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm bg-danger">
                <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Módulos Reprobados</span>
                    <span class="info-box-number" style="font-size: 22px;">{{ $totalReprobados }} Reprobados</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-sm bg-info">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">En Curso (Pendientes)</span>
                    <span class="info-box-number" style="font-size: 22px;">{{ $totalCursando }} Cursando</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Nómina Oficial -->
    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title text-bold mb-0">
                <i class="fas fa-list-alt text-success mr-1"></i>
                Nómina Oficial - {{ $tipoGraduacion }} ({{ count($estudiantes) }} registros)
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-graduados" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Código CEI</th>
                            <th>Cédula (CI)</th>
                            <th>Estudiante</th>
                            <th>Carrera / Origen</th>
                            <th>Aprobados / Reprobados</th>
                            <th>Promedio</th>
                            <th>Condición</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estudiantes as $est)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle font-weight-bold">
                                    <span class="badge badge-light border">{{ $est->codigo_estudiante ?? 'CEI-'.$est->id }}</span>
                                </td>
                                <td class="text-center align-middle">{{ $est->ci }}</td>
                                <td class="align-middle font-weight-bold text-dark">{{ $est->apellidos }} {{ $est->nombres }}</td>
                                <td class="align-middle text-sm">{{ $est->carrera_facultad ?? 'Particular' }}</td>
                                <td class="text-center align-middle font-weight-bold">
                                    <span class="badge badge-success mr-1">{{ $est->total_modulos_aprobados }} Aprob.</span>
                                    @if($est->total_modulos_reprobados > 0)
                                        <span class="badge badge-danger">{{ $est->total_modulos_reprobados }} Reprob.</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle font-weight-bold {{ $tipoGraduacion === 'REPROBADOS' ? 'text-danger' : 'text-success' }}" style="font-size: 14px;">
                                    {{ $est->promedio_egreso }} pts
                                </td>
                                <td class="text-center align-middle">
                                    @if($tipoGraduacion === 'REPROBADOS')
                                        <span class="badge badge-danger px-2 py-1 font-weight-bold">
                                            <i class="fas fa-times-circle"></i> REPROBADO
                                        </span>
                                    @elseif($tipoGraduacion === 'APROBADOS')
                                        <span class="badge badge-success px-2 py-1 font-weight-bold">
                                            <i class="fas fa-check-circle"></i> APROBADO
                                        </span>
                                    @else
                                        <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
                                            <i class="fas fa-award"></i> APTO PARA DIPLOMA
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-graduados').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });
    </script>
@stop

