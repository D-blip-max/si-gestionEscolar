@extends('adminlte::page')

@section('title', 'Reporte de Estudiantes Graduados')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user-graduate text-success mr-2"></i>Padrón de Estudiantes Graduados</h1>
                <small class="text-muted">CU-24: Nómina Oficial de Egresados (Nivel Intermedio y Avanzado)</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Graduados</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Filtros de Graduación -->
    <div class="card card-outline card-success shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.reportes.graduados') }}" class="form-inline">
                <label class="mr-2 font-weight-bold text-secondary"><i class="fas fa-medal text-warning mr-1"></i> Tipo de Titulación:</label>
                <select name="tipo" class="form-control form-control-sm mr-3 font-weight-bold" onchange="this.form.submit()">
                    <option value="INTERMEDIO" {{ $tipoGraduacion === 'INTERMEDIO' ? 'selected' : '' }}>TÍTULO INTERMEDIO (Módulo 8 Aprobado)</option>
                    <option value="AVANZADO" {{ $tipoGraduacion === 'AVANZADO' ? 'selected' : '' }}>CERTIFICACIÓN AVANZADA (Módulo 9 Aprobado)</option>
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
                        <i class="fas fa-file-pdf mr-1"></i> Exportar Padrón Oficial (PDF)
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info-Box Resumen -->
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="info-box shadow-sm bg-success">
                <span class="info-box-icon"><i class="fas fa-award"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Estudiantes Graduados</span>
                    <span class="info-box-number" style="font-size: 24px;">{{ count($estudiantes) }} Alumnos</span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="info-box shadow-sm bg-info">
                <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Promedio General Ponderado de la Cohorte</span>
                    @php
                        $promedioGeneral = count($estudiantes) > 0 ? round($estudiantes->avg('promedio_egreso'), 1) : 0;
                    @endphp
                    <span class="info-box-number" style="font-size: 24px;">{{ $promedioGeneral }} pts</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Nómina Oficial -->
    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title text-bold mb-0">
                <i class="fas fa-list-alt text-success mr-1"></i>
                Nómina Oficial de Egresados - Nivel {{ $tipoGraduacion }}
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
                            <th>Estudiante Graduado</th>
                            <th>Carrera / Origen</th>
                            <th>Módulos Aprobados</th>
                            <th>Promedio de Egreso</th>
                            <th>Estado de Titulación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($estudiantes as $est)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle font-weight-bold">
                                    <span class="badge badge-light border">{{ $est->codigo_estudiante ?? 'CEI-'.$est->id }}</span>
                                </td>
                                <td class="text-center align-middle">{{ $est->ci }}</td>
                                <td class="align-middle font-weight-bold text-dark">{{ $est->apellidos }} {{ $est->nombres }}</td>
                                <td class="align-middle text-sm">{{ $est->carrera_facultad ?? 'Particular' }}</td>
                                <td class="text-center align-middle font-weight-bold text-primary">{{ $est->total_modulos_aprobados }} de 9</td>
                                <td class="text-center align-middle font-weight-bold text-success" style="font-size: 14px;">
                                    {{ $est->promedio_egreso }} pts
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success px-2 py-1 font-weight-bold">
                                        <i class="fas fa-check-circle"></i> APTO PARA DIPLOMA
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No se encontraron estudiantes que cumplan con todos los requisitos para el nivel {{ $tipoGraduacion }}.
                                </td>
                            </tr>
                        @endforelse
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

