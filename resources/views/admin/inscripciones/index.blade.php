@extends('adminlte::page')

@section('title', 'Listado de Matrículas Modulares')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-clipboard-check text-primary mr-2"></i>Matrículas e Inscripciones Modulares</h1>
                <small class="text-muted">Control de Estudiantes Matriculados por Ciclo y Modalidad</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Inscripciones</li>
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
                    timer: 3500
                });
            });
        </script>
    @endif

    <!-- Filtro y Acciones -->
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header bg-white">
            <form method="GET" action="{{ route('admin.inscripciones.index') }}" class="form-inline float-left">
                <label class="mr-2 font-weight-bold text-secondary"><i class="fas fa-filter mr-1"></i> Filtrar:</label>
                <select name="ciclo_id" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    <option value="">-- Todos los Ciclos --</option>
                    @foreach($ciclos as $c)
                        <option value="{{ $c->id }}" {{ $cicloId == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre }} ({{ $c->estado }})
                        </option>
                    @endforeach
                </select>

                <select name="estado" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                    <option value="ACTIVA" {{ $estado === 'ACTIVA' ? 'selected' : '' }}>Solo Activas</option>
                    <option value="ANULADA" {{ $estado === 'ANULADA' ? 'selected' : '' }}>Solo Anuladas</option>
                    <option value="" {{ $estado === '' ? 'selected' : '' }}>Todas</option>
                </select>
            </form>

            <div class="card-tools">
                <a href="{{ route('admin.inscripciones.anulaciones') }}" class="btn btn-outline-danger btn-sm mr-2">
                    <i class="fas fa-history mr-1"></i> Historial de Anulaciones
                </a>
                <a href="{{ route('admin.inscripciones.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Nueva Inscripción Modular
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-inscripciones" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Ficha</th>
                            <th>Estudiante</th>
                            <th>Ciclo Bimestral</th>
                            <th>Idioma / Módulo</th>
                            <th>Modalidad y Aula/Link</th>
                            <th>Turno / Horario</th>
                            <th>Docente</th>
                            <th>Boleta Bancaria</th>
                            <th>Estado</th>
                            <th style="width: 90px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inscripciones as $ins)
                            @php
                                $esVirtual = strtoupper(optional($ins->paralelo)->modalidad) === 'VIRTUAL';
                            @endphp
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-light border px-2 py-1 font-weight-bold text-dark">
                                        #{{ str_pad($ins->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <strong class="text-primary">{{ optional($ins->estudiante)->apellidos }} {{ optional($ins->estudiante)->nombres }}</strong><br>
                                    <small class="text-muted"><i class="fas fa-id-card"></i> CI: {{ optional($ins->estudiante)->ci }} | {{ optional($ins->estudiante)->codigo_estudiante }}</small>
                                </td>
                                <td class="text-center align-middle text-sm">
                                    <span class="badge badge-light border">
                                        <i class="far fa-calendar-alt text-primary mr-1"></i> {{ optional(optional($ins->paralelo)->ciclo)->nombre ?? 'Ciclo' }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ optional($ins->modulo->idioma)->nombre }}</strong><br>
                                    <span class="text-muted text-sm">{{ optional($ins->modulo)->nombre }} (Nivel: {{ optional($ins->modulo)->nivel }})</span>
                                </td>
                                <td class="text-center align-middle">
                                    @if($esVirtual)
                                        <span class="badge badge-info"><i class="fas fa-laptop mr-1"></i> Virtual</span><br>
                                        @if(optional($ins->paralelo)->aula_o_enlace)
                                            <a href="{{ optional($ins->paralelo)->aula_o_enlace }}" target="_blank" class="text-xs text-truncate d-inline-block" style="max-width: 120px;">
                                                <i class="fas fa-video text-info mr-1"></i> Enlace Clase
                                            </a>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-building mr-1"></i> Presencial</span><br>
                                        <small class="text-muted"><i class="fas fa-door-open mr-1"></i> {{ optional($ins->paralelo)->aula_o_enlace ?? 'Aula General' }}</small>
                                    @endif
                                </td>
                                <td class="text-center align-middle text-sm">
                                    <span class="badge badge-warning text-dark font-weight-bold">{{ optional($ins->paralelo)->turno }}</span><br>
                                    <small class="text-muted">{{ substr(optional($ins->paralelo)->hora_inicio, 0, 5) }} - {{ substr(optional($ins->paralelo)->hora_fin, 0, 5) }}</small>
                                </td>
                                <td class="align-middle text-sm">
                                    {{ optional(optional(optional($ins->paralelo)->asignacion)->personal)->apellidos }} {{ optional(optional(optional($ins->paralelo)->asignacion)->personal)->nombres ?? 'Por Asignar' }}
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success font-weight-bold px-2 py-1">
                                        <i class="fas fa-receipt mr-1"></i> {{ optional($ins->boletaPago)->numero_boleta ?? 'S/B' }}
                                    </span><br>
                                    <small class="text-muted">Bs. {{ number_format(optional($ins->boletaPago)->monto, 2) }}</small>
                                </td>
                                <td class="text-center align-middle">
                                    @if($ins->estado === 'ACTIVA')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> ACTIVA</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> ANULADA</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.inscripciones.pdf', $ins->id) }}" target="_blank" class="btn btn-danger" title="Imprimir Ficha Oficial (PDF)">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @if($ins->estado === 'ACTIVA')
                                            <button type="button" class="btn btn-warning" onclick="abrirModalAnular({{ $ins->id }}, '{{ optional($ins->estudiante)->apellidos }} {{ optional($ins->estudiante)->nombres }}', '{{ optional($ins->modulo)->nombre }}')" title="Anular Matrícula">
                                                <i class="fas fa-undo-alt"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Anulación y Reversión de Inscripción -->
<div class="modal fade" id="modal-anular-inscripcion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-anular-inscripcion" action="" method="POST">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-warning text-dark font-weight-bold">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Anular Matrícula Modular</h5>
                    <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Está a punto de anular la inscripción de: <strong id="anular-estudiante"></strong> en el módulo <strong id="anular-modulo"></strong>.</p>
                    
                    <div class="alert alert-light border py-2 text-sm">
                        <i class="fas fa-info-circle text-info mr-1"></i> <strong>Efectos de la anulación:</strong>
                        <ul class="mb-0 pl-3">
                            <li>La boleta bancaria volverá automáticamente al estado <code>DISPONIBLE</code>.</li>
                            <li>Se liberará un cupo en el grupo modular asignado.</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label for="motivo_anulacion">Motivo o Justificación Obligatoria: <b class="text-danger">*</b></label>
                        <textarea name="motivo_anulacion" id="motivo_anulacion" class="form-control" rows="3" required placeholder="Ej: Cambio de turno a solicitud del estudiante, o error administrativo al seleccionar paralelo."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cancelar</button>
                    <button type="submit" class="btn btn-danger font-weight-bold"><i class="fas fa-undo mr-1"></i> Confirmar Anulación</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-inscripciones').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });

        function abrirModalAnular(id, estudiante, modulo) {
            $('#anular-estudiante').text(estudiante);
            $('#anular-modulo').text(modulo);
            $('#form-anular-inscripcion').attr('action', '{{ url("/admin/inscripciones") }}/' + id + '/anular');
            $('#motivo_anulacion').val('');
            $('#modal-anular-inscripcion').modal('show');
        }
    </script>
@stop

