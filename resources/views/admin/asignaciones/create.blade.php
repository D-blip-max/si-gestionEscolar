@extends('adminlte::page')

@section('title', 'Nueva Asignación Docente')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user-plus text-primary mr-2"></i>Asignar Docente a Grupo</h1>
                <small class="text-muted">CU-09: Selección de Docente Titular y Grupo Modular</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.asignaciones.index') }}">Asignaciones</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
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
                    showConfirmButton: true
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Hubo errores en el formulario:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- Columna Izquierda: Búsqueda y Perfil del Docente -->
        <div class="col-md-7">
            <div class="card card-outline card-primary shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title text-bold"><i class="fas fa-search mr-1"></i> 1. Seleccionar Docente Titular</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="buscar_docente">Buscar Docente Registrado: <b class="text-danger">*</b></label>
                        <select id="buscar_docente" class="form-control select2" style="width: 100%;">
                            <option value="">-- Seleccione un docente por nombre o carnet --</option>
                            @foreach ($docentes as $docente)
                                <option value="{{ $docente->id }}" {{ old('personal_id') == $docente->id ? 'selected' : '' }}>
                                    {{ $docente->apellidos }} {{ $docente->nombres }} (CI: {{ $docente->ci }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="panel_docente" style="display: none;">
                        <hr>
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <img id="doc_foto" src="{{ asset('vendor/adminlte/dist/img/avatar5.png') }}" 
                                     class="img-thumbnail rounded-circle shadow-sm mb-2" 
                                     style="width: 130px; height: 130px; object-fit: cover;" alt="Docente">
                                <div class="badge badge-success px-3 py-1"><i class="fas fa-check-circle"></i> Docente Activo</div>
                            </div>
                            <div class="col-md-8">
                                <h4 id="doc_nombre_completo" class="text-primary text-bold mb-1">Nombre Completo</h4>
                                <p class="text-muted mb-2"><i class="fas fa-briefcase mr-1"></i> <span id="doc_profesion">-</span></p>
                                
                                <ul class="list-group list-group-unbordered text-sm">
                                    <li class="list-group-item py-1">
                                        <b><i class="fas fa-id-card text-muted mr-1"></i> Cédula:</b> <span id="doc_ci" class="float-right text-dark">-</span>
                                    </li>
                                    <li class="list-group-item py-1">
                                        <b><i class="fas fa-phone text-muted mr-1"></i> Teléfono / Celular:</b> <span id="doc_telefono" class="float-right text-dark">-</span>
                                    </li>
                                    <li class="list-group-item py-1">
                                        <b><i class="fas fa-envelope text-muted mr-1"></i> Correo Electrónico:</b> <span id="doc_email" class="float-right text-dark">-</span>
                                    </li>
                                    <li class="list-group-item py-1">
                                        <b><i class="fas fa-map-marker-alt text-muted mr-1"></i> Dirección:</b> <span id="doc_direccion" class="float-right text-dark">-</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6 class="font-weight-bold text-secondary"><i class="fas fa-graduation-cap mr-1"></i> Formación y Acreditaciones:</h6>
                            <div id="doc_formaciones_container" class="table-responsive">
                                <p class="text-muted small">Sin información adicional.</p>
                            </div>
                        </div>
                    </div>

                    <div id="docente_placeholder" class="text-center py-5 text-muted">
                        <i class="fas fa-user-circle fa-4x mb-2 text-gray"></i>
                        <p class="mb-0">Seleccione un docente en el buscador superior para verificar su perfil y habilitar la asignación.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Selección del Grupo y Confirmación -->
        <div class="col-md-5">
            <form action="{{ route('admin.asignaciones.store') }}" method="POST">
                @csrf
                <input type="hidden" name="personal_id" id="personal_id" value="{{ old('personal_id') }}">

                <div class="card card-outline card-success shadow-sm">
                    <div class="card-header bg-white">
                        <h3 class="card-title text-bold"><i class="fas fa-layer-group mr-1"></i> 2. Asignar Grupo Modular</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="paralelo_id">Seleccionar Paralelo / Grupo: <b class="text-danger">*</b></label>
                            <select name="paralelo_id" id="paralelo_id" class="form-control select2" required style="width: 100%;">
                                <option value="">-- Seleccionar grupo modular --</option>
                                @foreach ($paralelos as $paralelo)
                                    @php
                                        $esVirtual = strtoupper($paralelo->modalidad) === 'VIRTUAL';
                                        $iconoMod = $esVirtual ? '💻 Virtual' : '🏫 Presencial';
                                        $label = optional($paralelo->ciclo)->nombre . ' | ' . 
                                                 optional($paralelo->modulo->idioma)->nombre . ' - ' . 
                                                 optional($paralelo->modulo)->nombre . ' [' . $paralelo->nombre . '] (' . 
                                                 $iconoMod . ' - ' . $paralelo->turno . ' ' . substr($paralelo->hora_inicio,0,5) . '-' . substr($paralelo->hora_fin,0,5) . ')';
                                    @endphp
                                    <option value="{{ $paralelo->id }}" {{ old('paralelo_id') == $paralelo->id ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">El sistema validará que el docente no tenga choques de horario en el mismo ciclo.</small>
                        </div>

                        <div class="form-group">
                            <label for="fecha_asignacion">Fecha de Asignación: <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="date" name="fecha_asignacion" id="fecha_asignacion" class="form-control" value="{{ old('fecha_asignacion', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="callout callout-info text-sm bg-light">
                            <h5><i class="fas fa-info-circle text-info"></i> Reglas de Asignación (CU-09)</h5>
                            <ul class="pl-3 mb-0">
                                <li>Solo puede existir <strong>un docente titular</strong> por cada grupo modular.</li>
                                <li>Se verifica automáticamente la <strong>disponibilidad de horario</strong> en el ciclo bimestral.</li>
                                <li>Esta asignación autoriza al docente a evaluar y asentar calificaciones (CU-16).</li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-footer bg-white text-right">
                        <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                        <button type="submit" id="btn-guardar" class="btn btn-primary" disabled>
                            <i class="fas fa-save mr-1"></i> Confirmar Asignación
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Seleccione una opción'
            });

            function cargarDocente(docenteId) {
                if (!docenteId) {
                    $('#panel_docente').hide();
                    $('#docente_placeholder').show();
                    $('#personal_id').val('');
                    $('#btn-guardar').prop('disabled', true);
                    return;
                }

                $.ajax({
                    url: '{{ url("/admin/asignaciones/buscar_docente") }}/' + docenteId,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Cargando información del docente...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });
                    },
                    success: function(data) {
                        Swal.close();
                        $('#doc_foto').attr('src', data.foto_url);
                        $('#doc_nombre_completo').text(data.nombres + ' ' + data.apellidos);
                        $('#doc_profesion').text(data.profesion || 'Docente de Idiomas');
                        $('#doc_ci').text(data.ci || '-');
                        $('#doc_telefono').text(data.celular || data.telefono || '-');
                        $('#doc_email').text((data.usuario && data.usuario.email) ? data.usuario.email : '-');
                        $('#doc_direccion').text(data.direccion || '-');

                        // Formaciones académicas
                        var formacionesHtml = '';
                        if (data.formaciones && data.formaciones.length > 0) {
                            formacionesHtml += '<table class="table table-sm table-bordered mb-0"><thead class="thead-light"><tr><th>Título / Grado</th><th>Institución</th><th>Año</th></tr></thead><tbody>';
                            $.each(data.formaciones, function(i, f) {
                                formacionesHtml += '<tr><td>' + (f.titulo || f.nombre) + ' <span class="badge badge-info">' + (f.nivel || '') + '</span></td><td>' + (f.institucion || '-') + '</td><td>' + (f.fecha_graduacion ? f.fecha_graduacion.substring(0,4) : '-') + '</td></tr>';
                            });
                            formacionesHtml += '</tbody></table>';
                        } else {
                            formacionesHtml = '<p class="text-muted small mb-0"><i class="fas fa-info-circle"></i> No registra formaciones académicas adjuntas.</p>';
                        }
                        $('#doc_formaciones_container').html(formacionesHtml);

                        $('#personal_id').val(data.id);
                        $('#panel_docente').show();
                        $('#docente_placeholder').hide();
                        $('#btn-guardar').prop('disabled', false);
                    },
                    error: function() {
                        Swal.fire('Error', 'No se pudo obtener los datos del docente seleccionado', 'error');
                        $('#personal_id').val('');
                        $('#btn-guardar').prop('disabled', true);
                    }
                });
            }

            $('#buscar_docente').on('change', function() {
                var id = $(this).val();
                cargarDocente(id);
            });

            // Trigger si ya había un valor previo (por validación fallida)
            var preSelected = $('#buscar_docente').val();
            if (preSelected) {
                cargarDocente(preSelected);
            }
        });
    </script>
@stop
