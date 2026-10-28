@extends('adminlte::page')

@section('title', 'Kárdex Académico - ' . $estudiante->apellidos)

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-graduation-cap text-primary mr-2"></i>Kárdex Académico Modular</h1>
                <small class="text-muted">Historial Académico, Trazabilidad Modular y Ruta de Graduación</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.estudiantes.index') }}">Estudiantes</a></li>
                    <li class="breadcrumb-item active">Kárdex</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Ficha Encabezado Estudiante -->
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    @php
                        $foto = ($estudiante->foto && file_exists(public_path($estudiante->foto)))
                            ? asset($estudiante->foto)
                            : asset('img/default-avatar.png');
                    @endphp
                    <img src="{{ $foto }}" class="img-thumbnail rounded-circle shadow-sm" style="width: 110px; height: 110px; object-fit: cover;" alt="Avatar">
                </div>
                <div class="col-md-7">
                    <h3 class="text-primary font-weight-bold mb-1">{{ $estudiante->apellidos }} {{ $estudiante->nombres }}</h3>
                    <p class="text-muted mb-2">
                        <span class="badge badge-primary px-3 py-1 mr-2"><i class="fas fa-id-card"></i> Código: {{ $estudiante->codigo_estudiante ?? 'CEI-'.$estudiante->id }}</span>
                        <span class="mr-2"><strong>CI:</strong> {{ $estudiante->ci }}</span> | 
                        <span class="ml-2"><strong>Carrera / Origen:</strong> {{ $estudiante->carrera_facultad ?? 'Particular' }}</span>
                    </p>
                    <p class="text-sm text-secondary mb-0">
                        <i class="fab fa-whatsapp text-success mr-1"></i> {{ $estudiante->telefono }} | 
                        <i class="fas fa-envelope text-muted ml-2 mr-1"></i> {{ optional($estudiante->usuario)->email ?? 'S/C' }}
                    </p>
                </div>
                <div class="col-md-3 text-right">
                    <a href="{{ route('admin.estudiantes.kardex_pdf', $estudiante->id) }}" target="_blank" class="btn btn-danger btn-lg shadow-sm">
                        <i class="fas fa-file-pdf mr-1"></i> Imprimir Kárdex (PDF)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicador de Ruta de Graduación Modular (1 al 9) -->
    @php
        // Calcular módulos aprobados y convalidados por examen de nivel
        $aprobadosCount = 0;
        $tieneGraduacionIntermedia = false;
        $tieneGraduacionAvanzada = false;
        $primerModuloOrden = $estudiante->inscripciones->where('estado', '!=', 'ANULADA')->min(function($ins) {
            return optional($ins->modulo)->numero_orden;
        });

        foreach($estudiante->inscripciones as $ins) {
            $nota = optional($ins->calificacion)->nota_final ?? null;
            $estadoCal = optional($ins->calificacion)->estado ?? null;
            if ($nota !== null && $estadoCal !== 'PENDIENTE' && $nota >= 51) {
                $aprobadosCount++;
                if (optional($ins->modulo)->numero_orden == 8) {
                    $tieneGraduacionIntermedia = true;
                }
                if (optional($ins->modulo)->numero_orden == 9) {
                    $tieneGraduacionAvanzada = true;
                }
            }
        }
        $convalidadosCount = ($primerModuloOrden && $primerModuloOrden > 1) ? ($primerModuloOrden - 1) : 0;
        $totalSuperados = min(9, $aprobadosCount + $convalidadosCount);
        $porcentajeAvance = min(100, round(($totalSuperados / 9) * 100));
    @endphp

    <div class="card shadow-sm mb-3">
        <div class="card-header bg-white">
            <h5 class="card-title text-bold mb-0"><i class="fas fa-tasks text-primary mr-1"></i> Ruta Modular hacia la Graduación (Módulos 1 al 9)</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-8">
                    <label class="text-muted mb-1">
                        Progreso General del Programa: <strong>{{ $totalSuperados }} de 9 Módulos Superados ({{ $porcentajeAvance }}%)</strong>
                        @if($convalidadosCount > 0)
                            <span class="badge badge-info ml-2"><i class="fas fa-fast-forward mr-1"></i> {{ $convalidadosCount }} Convalidado(s) por Examen de Nivel</span>
                        @endif
                    </label>
                    <div class="progress" style="height: 22px;">
                        <div class="progress-bar bg-success font-weight-bold" style="width: {{ $porcentajeAvance }}%">
                            {{ $porcentajeAvance }}%
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="d-inline-block p-2 border rounded mr-2 {{ $tieneGraduacionIntermedia ? 'bg-success text-white' : 'bg-light text-muted' }}">
                        <i class="fas fa-medal fa-2x mb-1"></i><br>
                        <small class="font-weight-bold">TÍTULO INTERMEDIO (Módulo 8)</small><br>
                        <span class="badge {{ $tieneGraduacionIntermedia ? 'badge-light text-success' : 'badge-secondary' }}">
                            {{ $tieneGraduacionIntermedia ? 'ALCANZADO' : 'PENDIENTE' }}
                        </span>
                    </div>
                    <div class="d-inline-block p-2 border rounded {{ $tieneGraduacionAvanzada ? 'bg-warning text-dark' : 'bg-light text-muted' }}">
                        <i class="fas fa-award fa-2x mb-1"></i><br>
                        <small class="font-weight-bold">TÍTULO AVANZADO (Módulo 9)</small><br>
                        <span class="badge {{ $tieneGraduacionAvanzada ? 'badge-dark' : 'badge-secondary' }}">
                            {{ $tieneGraduacionAvanzada ? 'CERTIFICADO' : 'PENDIENTE' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Paso a Paso Visual de Módulos 1 al 9 -->
            <div class="d-flex flex-wrap justify-content-between text-center mt-2">
                @for($i = 1; $i <= 9; $i++)
                    @php
                        $inscripcionModulo = $estudiante->inscripciones->first(function($ins) use ($i) {
                            return optional($ins->modulo)->numero_orden == $i;
                        });
                        $nota = optional(optional($inscripcionModulo)->calificacion)->nota_final;
                        $estadoCal = optional(optional($inscripcionModulo)->calificacion)->estado;
                        $colorClase = 'border-secondary text-muted bg-light';
                        $icono = 'fa-circle';
                        $estadoTexto = 'Pendiente';

                        if ($inscripcionModulo) {
                            if ($nota !== null && $estadoCal !== 'PENDIENTE') {
                                if ($nota >= 51) {
                                    $colorClase = 'border-success bg-success text-white';
                                    $icono = 'fa-check-circle';
                                    $estadoTexto = $nota . ' pts';
                                } else {
                                    $colorClase = 'border-danger bg-danger text-white';
                                    $icono = 'fa-times-circle';
                                    $estadoTexto = $nota . ' pts';
                                }
                            } else {
                                $colorClase = 'border-primary bg-primary text-white';
                                $icono = 'fa-spinner fa-spin';
                                $estadoTexto = 'Cursando';
                            }
                        } elseif ($primerModuloOrden && $i < $primerModuloOrden) {
                            $colorClase = 'border-info bg-info text-white';
                            $icono = 'fa-fast-forward';
                            $estadoTexto = 'Ex. Nivel';
                        }
                    @endphp
                    <div class="p-2 border rounded shadow-xs mb-2 {{ $colorClase }}" style="flex: 1 1 10%; min-width: 95px; margin: 3px;">
                        <small class="text-uppercase font-weight-bold">
                            @if($i <= 4)
                                Básico {{ $i }}
                            @elseif($i <= 8)
                                Inter. {{ $i }}
                            @else
                                Avanzado 9
                            @endif
                        </small><br>
                        <i class="fas {{ $icono }} my-1"></i><br>
                        <small class="font-weight-bold">{{ $estadoTexto }}</small>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Historial Detallado de Módulos Cursados -->
    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title text-bold mb-0"><i class="fas fa-list-alt text-primary mr-1"></i> Registro Histórico Detallado de Calificaciones</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 text-center">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Ciclo Bimestral</th>
                            <th>Idioma / Módulo</th>
                            <th>Nivel Curricular</th>
                            <th>Modalidad / Aula / Enlace</th>
                            <th>Docente Titular</th>
                            <th>Nro. Boleta</th>
                            <th>Nota Final [0-100]</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estudiante->inscripciones as $ins)
                            @php
                                $nota = optional($ins->calificacion)->nota_final;
                                $estadoCal = optional($ins->calificacion)->estado;
                                $esVirtual = strtoupper(optional($ins->paralelo)->modalidad) === 'VIRTUAL';
                            @endphp
                            <tr>
                                <td class="align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-light border px-2 py-1">
                                        <i class="far fa-calendar-alt text-primary mr-1"></i> {{ optional(optional($ins->paralelo)->ciclo)->nombre ?? 'Ciclo' }}
                                    </span>
                                </td>
                                <td class="align-middle text-left">
                                    <strong>{{ optional(optional($ins->modulo)->idioma)->nombre }}</strong><br>
                                    <span class="text-muted">{{ optional($ins->modulo)->nombre }} (Orden: {{ optional($ins->modulo)->numero_orden }})</span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-info text-uppercase">{{ optional($ins->modulo)->nivel ?? 'General' }}</span>
                                </td>
                                <td class="align-middle">
                                    @if($esVirtual)
                                        <span class="badge badge-info"><i class="fas fa-laptop mr-1"></i> Virtual</span><br>
                                        <small class="text-truncate text-muted" style="max-width: 130px;" title="{{ optional($ins->paralelo)->aula_o_enlace }}">
                                            {{ optional($ins->paralelo)->aula_o_enlace ?? 'Enlace Meet' }}
                                        </small>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-building mr-1"></i> Presencial</span><br>
                                        <small class="text-muted"><i class="fas fa-door-open mr-1"></i> {{ optional($ins->paralelo)->aula_o_enlace ?? 'Aula General' }}</small>
                                    @endif
                                </td>
                                <td class="align-middle text-left">
                                    {{ optional(optional(optional($ins->paralelo)->asignacion)->personal)->apellidos }} {{ optional(optional(optional($ins->paralelo)->asignacion)->personal)->nombres ?? 'Docente Asignado' }}
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-light border font-weight-bold">
                                        <i class="fas fa-ticket-alt text-success mr-1"></i> {{ optional($ins->boletaPago)->numero_boleta ?? 'S/B' }}
                                    </span>
                                </td>
                                <td class="align-middle font-weight-bold" style="font-size: 1.1rem;">
                                    @if($nota !== null && $estadoCal !== 'PENDIENTE')
                                        <span class="{{ $nota >= 51 ? 'text-success' : 'text-danger' }}">{{ $nota }}</span>
                                    @else
                                        <span class="text-muted font-italic font-weight-normal text-sm">En Curso</span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($nota !== null && $estadoCal !== 'PENDIENTE')
                                        @if($nota >= 51)
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> APROBADO</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> REPROBADO</span>
                                        @endif
                                    @else
                                        <span class="badge badge-primary px-2 py-1"><i class="fas fa-clock mr-1"></i> CURSANDO</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2 text-secondary"></i><br>
                                    El estudiante aún no tiene registros de inscripciones o materias cursadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-right">
            <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Volver al Padrón de Estudiantes
            </a>
        </div>
    </div>
</div>
@stop

