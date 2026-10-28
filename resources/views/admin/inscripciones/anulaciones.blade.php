@extends('adminlte::page')

@section('title', 'Historial de Anulaciones de Matrícula')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-history text-danger mr-2"></i>Historial de Anulaciones de Matrícula</h1>
                <small class="text-muted">Auditoría y Trazabilidad de Reversiones de Inscripción Modular</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.inscripciones.index') }}">Inscripciones</a></li>
                    <li class="breadcrumb-item active">Anulaciones</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-danger shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title text-bold"><i class="fas fa-undo-alt mr-1"></i> Registro de Matrículas Anuladas y Reversiones</h3>
            <div class="card-tools">
                <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a Inscripciones Activas
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-anulaciones" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Ficha Nro.</th>
                            <th>Estudiante</th>
                            <th>Ciclo Bimestral</th>
                            <th>Idioma / Módulo</th>
                            <th>Boleta Bancaria Liberada</th>
                            <th>Fecha de Anulación</th>
                            <th>Motivo / Justificación Registrada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inscripciones as $ins)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-secondary px-2 py-1 font-weight-bold">
                                        #{{ str_pad($ins->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ optional($ins->estudiante)->apellidos }} {{ optional($ins->estudiante)->nombres }}</strong><br>
                                    <small class="text-muted">CI: {{ optional($ins->estudiante)->ci }} | {{ optional($ins->estudiante)->codigo_estudiante }}</small>
                                </td>
                                <td class="text-center align-middle text-sm">
                                    {{ optional(optional($ins->paralelo)->ciclo)->nombre ?? 'Ciclo' }}
                                </td>
                                <td class="align-middle">
                                    <strong>{{ optional($ins->modulo->idioma)->nombre }}</strong><br>
                                    <span class="text-muted text-sm">{{ optional($ins->modulo)->nombre }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success px-2 py-1">
                                        <i class="fas fa-receipt mr-1"></i> {{ optional($ins->boletaPago)->numero_boleta ?? 'S/B' }}
                                    </span><br>
                                    <small class="text-muted">Estado Actual: {{ optional($ins->boletaPago)->estado }}</small>
                                </td>
                                <td class="text-center align-middle text-sm">
                                    <i class="far fa-calendar-alt text-muted mr-1"></i> {{ $ins->updated_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="align-middle text-sm font-italic">
                                    <i class="fas fa-comment-alt text-secondary mr-1"></i> "{{ $ins->motivo_anulacion ?? 'Sin motivo especificado' }}"
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2 text-secondary"></i><br>
                                    No se registran inscripciones anuladas en el sistema.
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
            $('#tabla-anulaciones').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });
    </script>
@stop

