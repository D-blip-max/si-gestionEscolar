@extends('adminlte::page')

@section('title', 'Nueva Inscripción Modular')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user-plus text-primary mr-2"></i>Nueva Matrícula Modular</h1>
                <small class="text-muted">Inscripción con Validación de Boleta Bancaria y Control de Prerrequisitos</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.inscripciones.index') }}">Inscripciones</a></li>
                    <li class="breadcrumb-item active">Nueva</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Error en la Inscripción:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.inscripciones.store') }}" method="POST" id="form-inscripcion">
        @csrf

        <div class="row">
            <!-- Columna Izquierda: Estudiante y Boleta Bancaria -->
            <div class="col-md-6">
                <!-- Paso 1: Selección de Estudiante -->
                <div class="card card-outline card-primary shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h5 class="card-title text-bold"><i class="fas fa-user-graduate text-primary mr-1"></i> 1. Seleccionar Estudiante</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="estudiante_id">Buscar Estudiante: <b class="text-danger">*</b></label>
                            <select name="estudiante_id" id="estudiante_id" class="form-control select2" required style="width: 100%;">
                                <option value="">-- Buscar por Nombre, Apellidos o Cédula (CI) --</option>
                                @foreach ($estudiantes as $est)
                                    <option value="{{ $est->id }}" {{ old('estudiante_id') == $est->id ? 'selected' : '' }}>
                                        {{ $est->apellidos }} {{ $est->nombres }} (CI: {{ $est->ci }} | {{ $est->codigo_estudiante ?? 'CEI-'.$est->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mini ficha de datos del estudiante seleccionado -->
                        <div id="info-estudiante" style="display: none;" class="callout callout-info py-2 bg-light text-sm">
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>Estudiante:</strong> <span id="lbl-nombre" class="text-primary font-weight-bold">-</span><br>
                                    <strong>Cédula:</strong> <span id="lbl-ci">-</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong>Código CEI:</strong> <span id="lbl-codigo" class="badge badge-primary">-</span><br>
                                    <strong>Carrera:</strong> <span id="lbl-carrera">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paso 2: Selección de Boleta Bancaria -->
                <div class="card card-outline card-success shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="card-title text-bold"><i class="fas fa-file-invoice-dollar text-success mr-1"></i> 2. Boleta Bancaria Fiscalizada (Cero Efectivo)</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="boleta_pago_id">Boleta Bancaria Disponible: <b class="text-danger">*</b></label>
                            <select name="boleta_pago_id" id="boleta_pago_id" class="form-control" required disabled>
                                <option value="">-- Primero seleccione un estudiante --</option>
                            </select>
                            <small class="text-muted">Solo se despliegan boletas registradas en estado <code>DISPONIBLE</code>.</small>
                        </div>

                        <div id="alerta-sin-boleta" style="display: none;" class="alert alert-warning py-2 text-sm">
                            <i class="fas fa-exclamation-circle mr-1"></i> <strong>Atención:</strong> Este estudiante no tiene ninguna boleta bancaria disponible.
                            <div class="mt-2">
                                <a href="{{ route('admin.boletas.index') }}" target="_blank" class="btn btn-warning btn-xs font-weight-bold">
                                    <i class="fas fa-plus-circle mr-1"></i> Registrar Boleta en CU-11
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Selección de Módulo y Paralelo -->
            <div class="col-md-6">
                <div class="card card-outline card-info shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h5 class="card-title text-bold"><i class="fas fa-layer-group text-info mr-1"></i> 3. Módulo y Grupo Modular</h5>
                    </div>
                    <div class="card-body">
                        <!-- Idioma -->
                        <div class="form-group">
                            <label for="idioma_id">Idioma de Formación: <b class="text-danger">*</b></label>
                            <select id="idioma_id" class="form-control" required>
                                <option value="">-- Seleccionar Idioma --</option>
                                @foreach($idiomas as $idioma)
                                    <option value="{{ $idioma->id }}">{{ $idioma->nombre }} ({{ $idioma->codigo }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Paralelo / Grupo Modular -->
                        <div class="form-group">
                            <label for="paralelo_id">Seleccionar Paralelo / Grupo Ofertado: <b class="text-danger">*</b></label>
                            <select name="paralelo_id" id="paralelo_id" class="form-control select2" required style="width: 100%;">
                                <option value="">-- Seleccionar Paralelo / Turno / Modalidad --</option>
                                @foreach ($paralelos as $p)
                                    @php
                                        $esVirtual = strtoupper($p->modalidad) === 'VIRTUAL';
                                        $icono = $esVirtual ? '💻 Virtual' : '🏫 Presencial';
                                        $cuposLibres = $p->cupo_maximo - $p->cupo_ocupado;
                                        $label = optional($p->ciclo)->nombre . ' | ' . 
                                                 optional($p->modulo->idioma)->nombre . ' - ' . 
                                                 optional($p->modulo)->nombre . ' [' . $p->nombre . '] (' . 
                                                 $icono . ' - ' . $p->turno . ' ' . substr($p->hora_inicio, 0, 5) . '-' . substr($p->hora_fin, 0, 5) . ') [Cupo: ' . $cuposLibres . ' lib.]';
                                    @endphp
                                    <option value="{{ $p->id }}" 
                                            data-idioma="{{ optional($p->modulo)->idioma_id }}"
                                            data-modulo="{{ $p->modulo_id }}"
                                            data-modalidad="{{ $p->modalidad }}"
                                            data-aula="{{ $p->aula_o_enlace }}"
                                            data-cupo="{{ $cuposLibres }}">
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="modulo_id" id="modulo_id" value="">
                        </div>

                        <!-- Alerta Dinámica de Prerrequisitos -->
                        <div id="alerta-prerrequisito" style="display: none;" class="alert alert-danger py-2 text-sm">
                            <i class="fas fa-lock mr-1"></i> <span id="msg-prerrequisito"></span>
                        </div>

                        <!-- Resumen del paralelo elegido -->
                        <div id="detalle-paralelo" style="display: none;" class="callout callout-success py-2 bg-light text-sm">
                            <div class="row">
                                <div class="col-sm-6">
                                    <strong>Modalidad:</strong> <span id="lbl-modalidad" class="badge badge-info">-</span><br>
                                    <strong>Ubicación:</strong> <span id="lbl-aula">-</span>
                                </div>
                                <div class="col-sm-6">
                                    <strong>Cupos Disponibles:</strong> <span id="lbl-cupo" class="text-success font-weight-bold">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white text-right">
                        <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                        <button type="submit" id="btn-inscribir" class="btn btn-success font-weight-bold" disabled>
                            <i class="fas fa-check-circle mr-1"></i> Confirmar Inscripción Modular
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

            // 1. Al seleccionar estudiante, consultar sus boletas disponibles
            $('#estudiante_id').on('change', function() {
                var estudianteId = $(this).val();
                if (!estudianteId) {
                    $('#info-estudiante').hide();
                    $('#boleta_pago_id').html('<option value="">-- Primero seleccione un estudiante --</option>').prop('disabled', true);
                    $('#alerta-sin-boleta').hide();
                    verificarFormulario();
                    return;
                }

                $.ajax({
                    url: '{{ url("/admin/boletas/disponibles") }}/' + estudianteId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(boletas) {
                        var options = '<option value="">-- Seleccionar Boleta Registrada --</option>';
                        if (boletas && boletas.length > 0) {
                            $.each(boletas, function(i, b) {
                                options += '<option value="' + b.id + '">Boleta Nro. ' + b.numero_boleta + ' (Bs. ' + parseFloat(b.monto).toFixed(2) + ' - ' + b.banco_entidad + ' - ' + b.fecha_deposito + ')</option>';
                            });
                            $('#boleta_pago_id').html(options).prop('disabled', false);
                            $('#alerta-sin-boleta').hide();
                        } else {
                            $('#boleta_pago_id').html('<option value="">-- Sin boletas disponibles --</option>').prop('disabled', true);
                            $('#alerta-sin-boleta').show();
                        }
                        verificarFormulario();
                    }
                });
            });

            // 2. Al seleccionar Paralelo, extraer módulo y verificar correlativas
            $('#paralelo_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var moduloId = selectedOption.data('modulo');
                var modalidad = selectedOption.data('modalidad');
                var aula = selectedOption.data('aula');
                var cupo = selectedOption.data('cupo');
                var estudianteId = $('#estudiante_id').val();

                $('#modulo_id').val(moduloId || '');

                if (moduloId) {
                    $('#lbl-modalidad').text(modalidad);
                    $('#lbl-aula').text(aula || (modalidad === 'VIRTUAL' ? 'Enlace Meet' : 'Aula General'));
                    $('#lbl-cupo').text(cupo + ' plazas libres');
                    $('#detalle-paralelo').show();

                    // Validación AJAX de prerrequisitos
                    if (estudianteId) {
                        $.ajax({
                            url: '{{ route("admin.inscripciones.verificar_prerrequisito") }}',
                            type: 'GET',
                            data: { estudiante_id: estudianteId, modulo_id: moduloId },
                            dataType: 'json',
                            success: function(resp) {
                                if (resp.cumple) {
                                    $('#alerta-prerrequisito').hide();
                                    $('#btn-inscribir').prop('disabled', false);
                                } else {
                                    $('#msg-prerrequisito').text(resp.mensaje);
                                    $('#alerta-prerrequisito').show();
                                    $('#btn-inscribir').prop('disabled', true);
                                }
                            }
                        });
                    }
                } else {
                    $('#detalle-paralelo').hide();
                    $('#alerta-prerrequisito').hide();
                }
                verificarFormulario();
            });

            $('#boleta_pago_id').on('change', function() {
                verificarFormulario();
            });

            function verificarFormulario() {
                var est = $('#estudiante_id').val();
                var bol = $('#boleta_pago_id').val();
                var par = $('#paralelo_id').val();
                var tieneBloqueo = $('#alerta-prerrequisito').is(':visible');

                if (est && bol && par && !tieneBloqueo) {
                    $('#btn-inscribir').prop('disabled', false);
                } else {
                    $('#btn-inscribir').prop('disabled', true);
                }
            }
        });
    </script>
@stop

