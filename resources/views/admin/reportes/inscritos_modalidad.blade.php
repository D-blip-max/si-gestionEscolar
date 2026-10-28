@extends('adminlte::page')

@section('title', 'Inscritos por Modalidad')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-chart-pie text-primary mr-2"></i>Reporte de Inscritos por Modalidad</h1>
                <small class="text-muted">Análisis Estadístico Comparativo: Presencial 🏫 vs Virtual 💻</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Inscritos por Modalidad</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Filtros -->
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.reportes.inscritos') }}" class="form-inline">
                <label class="mr-2 font-weight-bold text-secondary"><i class="fas fa-filter mr-1"></i> Ciclo:</label>
                <select name="ciclo_id" class="form-control form-control-sm mr-3" onchange="this.form.submit()">
                    <option value="">-- Todos los Ciclos --</option>
                    @foreach($ciclos as $c)
                        <option value="{{ $c->id }}" {{ $cicloId == $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                    @endforeach
                </select>

                <label class="mr-2 font-weight-bold text-secondary">Idioma:</label>
                <select name="idioma_id" class="form-control form-control-sm mr-3" onchange="this.form.submit()">
                    <option value="">-- Todos los Idiomas --</option>
                    @foreach($idiomas as $idioma)
                        <option value="{{ $idioma->id }}" {{ $idiomaId == $idioma->id ? 'selected' : '' }}>{{ $idioma->nombre }}</option>
                    @endforeach
                </select>

                <div class="ml-auto">
                    <a href="{{ route('admin.reportes.inscritos_pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm font-weight-bold">
                        <i class="fas fa-file-pdf mr-1"></i> Imprimir Reporte Estadístico (PDF)
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Indicadores Numéricos -->
    <div class="row">
        <div class="col-md-4 col-12">
            <div class="small-box bg-secondary shadow-sm">
                <div class="inner">
                    <h3>{{ $totalPresencial }}</h3>
                    <p>Modalidad Presencial (Campus)</p>
                </div>
                <div class="icon"><i class="fas fa-building"></i></div>
                <span class="small-box-footer">
                    {{ $totalGeneral > 0 ? round(($totalPresencial / $totalGeneral) * 100, 1) : 0 }}% del total
                </span>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $totalVirtual }}</h3>
                    <p>Modalidad Virtual (Meet/Zoom)</p>
                </div>
                <div class="icon"><i class="fas fa-laptop"></i></div>
                <span class="small-box-footer">
                    {{ $totalGeneral > 0 ? round(($totalVirtual / $totalGeneral) * 100, 1) : 0 }}% del total
                </span>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="small-box bg-primary shadow-sm">
                <div class="inner">
                    <h3>{{ $totalGeneral }}</h3>
                    <p>Total Estudiantes Matriculados</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <span class="small-box-footer">100% Cobertura Activa</span>
            </div>
        </div>
    </div>

    <!-- Gráficos Estadísticos -->
    <div class="row mb-3">
        <div class="col-md-6 col-12">
            <div class="card card-outline card-info shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title text-bold mb-0"><i class="fas fa-chart-pie mr-1"></i> Distribución de Modalidades</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartModalidad" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="card card-outline card-success shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title text-bold mb-0"><i class="fas fa-chart-bar mr-1"></i> Inscritos por Turno</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartTurnos" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Nómina -->
    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title text-bold mb-0"><i class="fas fa-list text-primary mr-1"></i> Padrón de Estudiantes por Modalidad</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-modalidad" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Código CEI</th>
                            <th>Estudiante</th>
                            <th>CI</th>
                            <th>Idioma / Módulo</th>
                            <th>Modalidad</th>
                            <th>Turno</th>
                            <th>Aula / Enlace</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inscripciones as $ins)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle">{{ optional($ins->estudiante)->codigo_estudiante ?? 'CEI-'.$ins->estudiante_id }}</td>
                                <td class="align-middle font-weight-bold">{{ optional($ins->estudiante)->apellidos }} {{ optional($ins->estudiante)->nombres }}</td>
                                <td class="text-center align-middle">{{ optional($ins->estudiante)->ci }}</td>
                                <td class="align-middle">{{ optional($ins->modulo->idioma)->nombre }} - {{ optional($ins->modulo)->nombre }}</td>
                                <td class="text-center align-middle">
                                    <span class="badge {{ strtoupper(optional($ins->paralelo)->modalidad) === 'VIRTUAL' ? 'badge-info' : 'badge-secondary' }}">
                                        {{ optional($ins->paralelo)->modalidad }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">{{ optional($ins->paralelo)->turno }}</td>
                                <td class="text-center align-middle text-sm">{{ optional($ins->paralelo)->aula_o_enlace ?? 'Aula General' }}</td>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-modalidad').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });

            // Gráfico de Modalidades
            var ctxModalidad = document.getElementById('chartModalidad').getContext('2d');
            new Chart(ctxModalidad, {
                type: 'doughnut',
                data: {
                    labels: ['Presencial (Campus)', 'Virtual (Meet/Zoom)'],
                    datasets: [{
                        data: [{{ $totalPresencial }}, {{ $totalVirtual }}],
                        backgroundColor: ['#6c757d', '#17a2b8']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Gráfico de Turnos
            var ctxTurnos = document.getElementById('chartTurnos').getContext('2d');
            new Chart(ctxTurnos, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($porTurno->keys()) !!},
                    datasets: [{
                        label: 'Cantidad de Inscritos',
                        data: {!! json_encode($porTurno->values()) !!},
                        backgroundColor: '#28a745'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        });
    </script>
@stop

