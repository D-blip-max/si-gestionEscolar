@extends('adminlte::page')

@section('title', 'Cargar Calificaciones')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-edit text-primary mr-2"></i>Asentar Notas Finales del Módulo</h1>
                <small class="text-muted">Planilla de Evaluación Modular [0 - 100 Puntos]</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('docente.grupos.index') }}">Mis Grupos</a></li>
                    <li class="breadcrumb-item active">Calificaciones</li>
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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Error en la planilla:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Cabecera del Grupo -->
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="text-bold text-primary mb-1">
                        {{ optional($paralelo->modulo->idioma)->nombre }} - {{ optional($paralelo->modulo)->nombre }}
                    </h3>
                    <p class="text-muted mb-0">
                        <span class="badge badge-primary mr-2">Grupo {{ $paralelo->nombre }}</span>
                        <span class="mr-2"><strong>Ciclo:</strong> {{ optional($paralelo->ciclo)->nombre }}</span> | 
                        <span class="ml-2"><strong>Turno:</strong> {{ $paralelo->turno }} ({{ substr($paralelo->hora_inicio,0,5) }} - {{ substr($paralelo->hora_fin,0,5) }})</span> | 
                        <span class="ml-2"><strong>Modalidad:</strong> <span class="badge badge-info">{{ $paralelo->modalidad }}</span></span>
                    </p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('docente.actas.pdf', $paralelo->id) }}" target="_blank" class="btn btn-danger btn-sm shadow-sm font-weight-bold">
                        <i class="fas fa-file-pdf mr-1"></i> Generar Acta Oficial (PDF)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de Calificaciones en Lote -->
    <form action="{{ route('docente.calificaciones.guardar_lote') }}" method="POST">
        @csrf
        <input type="hidden" name="paralelo_id" value="{{ $paralelo->id }}">

        <div class="card card-outline card-success shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title text-bold mb-0"><i class="fas fa-list-ol text-success mr-1"></i> Nómina de Evaluación y Calificación</h5>
                <div class="card-tools text-sm text-muted">
                    <span class="mr-3"><i class="fas fa-check-circle text-success"></i> Aprobado: <strong>&ge; 51 pts</strong></span>
                    <span><i class="fas fa-times-circle text-danger"></i> Reprobado: <strong>&lt; 51 pts</strong></span>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover mb-0 text-center">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th style="width: 110px;">Código CEI</th>
                                <th class="text-left">Estudiante</th>
                                <th style="width: 100px;">CI</th>
                                <th style="width: 140px;">Nota Final [0 - 100]</th>
                                <th style="width: 140px;">Estado</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paralelo->inscripciones as $ins)
                                @php
                                    $calificacion = $ins->calificacion;
                                    $nota = $calificacion ? $calificacion->nota_final : 0;
                                    $esAprobado = $nota >= 51;
                                @endphp
                                <tr>
                                    <td class="align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                    <td class="align-middle">
                                        <span class="badge badge-light border">{{ optional($ins->estudiante)->codigo_estudiante ?? 'CEI-'.$ins->estudiante_id }}</span>
                                    </td>
                                    <td class="align-middle text-left font-weight-bold text-dark">
                                        {{ optional($ins->estudiante)->apellidos }} {{ optional($ins->estudiante)->nombres }}
                                    </td>
                                    <td class="align-middle">{{ optional($ins->estudiante)->ci }}</td>
                                    <td class="align-middle">
                                        <input type="hidden" name="notas[{{ $loop->index }}][inscripcion_id]" value="{{ $ins->id }}">
                                        <input type="number" 
                                               name="notas[{{ $loop->index }}][nota_final]" 
                                               id="nota_{{ $loop->index }}" 
                                               class="form-control text-center font-weight-bold input-nota" 
                                               value="{{ old('notas.'.$loop->index.'.nota_final', $nota) }}" 
                                               min="0" 
                                               max="100" 
                                               step="0.5" 
                                               required
                                               oninput="actualizarEstado({{ $loop->index }})">
                                    </td>
                                    <td class="align-middle">
                                        <span id="badge_{{ $loop->index }}" class="badge {{ $esAprobado ? 'badge-success' : 'badge-danger' }} px-3 py-2 font-weight-bold">
                                            <i class="fas {{ $esAprobado ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            <span id="txt_{{ $loop->index }}">{{ $esAprobado ? 'APROBADO' : 'REPROBADO' }}</span>
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <input type="text" 
                                               name="notas[{{ $loop->index }}][observaciones]" 
                                               class="form-control form-control-sm" 
                                               value="{{ old('notas.'.$loop->index.'.observaciones', optional($calificacion)->observaciones) }}" 
                                               placeholder="Opcional">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No existen estudiantes inscritos en este grupo para calificar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white text-right">
                <a href="{{ route('docente.grupos.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                @if(count($paralelo->inscripciones) > 0)
                    <button type="submit" class="btn btn-success font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Guardar Calificaciones Definitivas
                    </button>
                @endif
            </div>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    function actualizarEstado(index) {
        var input = document.getElementById('nota_' + index);
        var badge = document.getElementById('badge_' + index);
        var txt = document.getElementById('txt_' + index);
        var val = parseFloat(input.value);

        if (!isNaN(val) && val >= 51) {
            badge.className = 'badge badge-success px-3 py-2 font-weight-bold';
            badge.innerHTML = '<i class="fas fa-check-circle"></i> APROBADO';
        } else {
            badge.className = 'badge badge-danger px-3 py-2 font-weight-bold';
            badge.innerHTML = '<i class="fas fa-times-circle"></i> REPROBADO';
        }
    }
</script>
@stop

