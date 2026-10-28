@extends('adminlte::page')

@section('title', 'Registro y Fiscalización de Boletas')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-file-invoice-dollar text-primary mr-2"></i>Boletas de Depósito Bancario</h1>
                <small class="text-muted">CU-11: Fiscalización de Comprobantes Bancarios Universitarios (Cero Efectivo)</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Boletas de Pago</li>
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
            <h5><i class="icon fas fa-ban"></i> Errores al registrar la boleta:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tarjetas Informativas / Info-Boxes -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>Bs. {{ number_format($totalRecaudado, 2) }}</h3>
                    <p>Total Fiscalizado</p>
                </div>
                <div class="icon"><i class="fas fa-cash-register"></i></div>
                <span class="small-box-footer">Depósitos No Anulados</span>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $totalDisponibles }}</h3>
                    <p>Boletas Disponibles</p>
                </div>
                <div class="icon"><i class="fas fa-ticket-alt"></i></div>
                <a href="{{ route('admin.boletas.index', ['estado' => 'DISPONIBLE']) }}" class="small-box-footer">Ver Disponibles <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary shadow-sm">
                <div class="inner">
                    <h3>{{ $totalUtilizadas }}</h3>
                    <p>Boletas Utilizadas</p>
                </div>
                <div class="icon"><i class="fas fa-check-double"></i></div>
                <a href="{{ route('admin.boletas.index', ['estado' => 'UTILIZADA']) }}" class="small-box-footer">Ver Utilizadas <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>{{ $totalAnuladas }}</h3>
                    <p>Boletas Anuladas</p>
                </div>
                <div class="icon"><i class="fas fa-ban"></i></div>
                <a href="{{ route('admin.boletas.index', ['estado' => 'ANULADA']) }}" class="small-box-footer">Ver Anuladas <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Padrón de Boletas Bancarias -->
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title text-bold"><i class="fas fa-list mr-1"></i> Padrón General de Boletas y Vouchers Bancarios</h3>
            <div class="card-tools">
                <div class="btn-group mr-2">
                    <a href="{{ route('admin.boletas.index') }}" class="btn btn-default btn-sm {{ !request('estado') ? 'active font-weight-bold' : '' }}">Todas</a>
                    <a href="{{ route('admin.boletas.index', ['estado' => 'DISPONIBLE']) }}" class="btn btn-default btn-sm {{ request('estado') === 'DISPONIBLE' ? 'active font-weight-bold text-success' : '' }}">Disponibles</a>
                    <a href="{{ route('admin.boletas.index', ['estado' => 'UTILIZADA']) }}" class="btn btn-default btn-sm {{ request('estado') === 'UTILIZADA' ? 'active font-weight-bold text-primary' : '' }}">Utilizadas</a>
                    <a href="{{ route('admin.boletas.index', ['estado' => 'ANULADA']) }}" class="btn btn-default btn-sm {{ request('estado') === 'ANULADA' ? 'active font-weight-bold text-danger' : '' }}">Anuladas</a>
                </div>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-nueva-boleta">
                    <i class="fas fa-plus-circle mr-1"></i> Registrar Boleta Bancaria
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-boletas" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Nro. Boleta / Trx</th>
                            <th>Estudiante Beneficiario</th>
                            <th>Monto (Bs.)</th>
                            <th>Fecha Depósito</th>
                            <th>Banco / Entidad</th>
                            <th>Concepto</th>
                            <th>Comprobante</th>
                            <th>Estado</th>
                            <th style="width: 90px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($boletas as $boleta)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-light border px-2 py-1 font-weight-bold text-dark" style="font-size: 13px;">
                                        <i class="fas fa-receipt text-primary mr-1"></i> {{ $boleta->numero_boleta }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ optional($boleta->estudiante)->apellidos }} {{ optional($boleta->estudiante)->nombres }}</strong><br>
                                    <small class="text-muted"><i class="fas fa-id-card"></i> CI: {{ optional($boleta->estudiante)->ci }} | {{ optional($boleta->estudiante)->codigo_estudiante }}</small>
                                </td>
                                <td class="text-right align-middle font-weight-bold text-success">
                                    Bs. {{ number_format($boleta->monto, 2) }}
                                </td>
                                <td class="text-center align-middle">
                                    <i class="far fa-calendar-alt text-muted mr-1"></i> {{ $boleta->fecha_deposito }}
                                </td>
                                <td class="text-center align-middle text-sm">
                                    <span class="badge badge-secondary">{{ $boleta->banco_entidad }}</span>
                                </td>
                                <td class="align-middle text-sm">
                                    {{ $boleta->concepto }}
                                </td>
                                <td class="text-center align-middle">
                                    @if($boleta->archivo_comprobante && file_exists(public_path($boleta->archivo_comprobante)))
                                        @php
                                            $ext = pathinfo($boleta->archivo_comprobante, PATHINFO_EXTENSION);
                                        @endphp
                                        @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                            <button type="button" class="btn btn-outline-info btn-xs" onclick="verComprobante('{{ asset($boleta->archivo_comprobante) }}', '{{ $boleta->numero_boleta }}', false)">
                                                <i class="fas fa-image mr-1"></i> Ver Voucher
                                            </button>
                                        @else
                                            <a href="{{ asset($boleta->archivo_comprobante) }}" target="_blank" class="btn btn-outline-danger btn-xs">
                                                <i class="fas fa-file-pdf mr-1"></i> Ver PDF
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted small">Sin adjunto</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if($boleta->estado === 'DISPONIBLE')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> DISPONIBLE</span>
                                    @elseif($boleta->estado === 'UTILIZADA')
                                        <span class="badge badge-primary px-2 py-1"><i class="fas fa-check-double"></i> UTILIZADA</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> ANULADA</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if($boleta->estado === 'DISPONIBLE')
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-warning" onclick="confirmarAnulacion({{ $boleta->id }}, '{{ $boleta->numero_boleta }}')" title="Anular Boleta">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="confirmarEliminarBoleta({{ $boleta->id }})" title="Eliminar Registro">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        <form id="anular-boleta-{{ $boleta->id }}" action="{{ route('admin.boletas.anular', $boleta->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <form id="delete-boleta-{{ $boleta->id }}" action="{{ route('admin.boletas.destroy', $boleta->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @else
                                        <span class="text-muted small"><i class="fas fa-lock"></i> Bloqueada</span>
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

<!-- Modal: Registrar Boleta Bancaria -->
<div class="modal fade" id="modal-nueva-boleta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.boletas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-receipt mr-2"></i>Registrar Boleta / Voucher de Depósito</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 text-sm">
                        <i class="fas fa-info-circle mr-1"></i> <strong>Política de Cero Efectivo:</strong> Todas las inscripciones modulares requieren una boleta bancaria fiscalizada en estado <code>DISPONIBLE</code>.
                    </div>

                    <div class="form-group">
                        <label for="estudiante_id">Estudiante Titular del Depósito: <b class="text-danger">*</b></label>
                        <select name="estudiante_id" id="estudiante_id" class="form-control select2" required style="width: 100%;">
                            <option value="">-- Buscar por Nombre, Apellido o Carnet --</option>
                            @foreach ($estudiantes as $est)
                                <option value="{{ $est->id }}" {{ old('estudiante_id') == $est->id ? 'selected' : '' }}>
                                    {{ $est->apellidos }} {{ $est->nombres }} (CI: {{ $est->ci }} | Código: {{ $est->codigo_estudiante ?? 'CEI-'.$est->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero_boleta">Nro. de Boleta / Comprobante Único: <b class="text-danger">*</b></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-barcode"></i></span></div>
                                    <input type="text" name="numero_boleta" id="numero_boleta" class="form-control" value="{{ old('numero_boleta') }}" required placeholder="Ej: 94810283">
                                </div>
                                <small class="text-muted">El sistema valida que no haya sido registrada previamente.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="monto">Monto Depositado (Bs.): <b class="text-danger">*</b></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Bs.</span></div>
                                    <input type="number" step="0.50" min="1" name="monto" id="monto" class="form-control font-weight-bold" value="{{ old('monto', '150.00') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_deposito">Fecha de Depósito Bancario: <b class="text-danger">*</b></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i></span></div>
                                    <input type="date" name="fecha_deposito" id="fecha_deposito" class="form-control" value="{{ old('fecha_deposito', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="banco_entidad">Banco o Entidad Recaudadora: <b class="text-danger">*</b></label>
                                <select name="banco_entidad" id="banco_entidad" class="form-control" required>
                                    <option value="Banco Unión S.A." {{ old('banco_entidad') == 'Banco Unión S.A.' ? 'selected' : '' }}>Banco Unión S.A.</option>
                                    <option value="Tesorería Universitaria" {{ old('banco_entidad') == 'Tesorería Universitaria' ? 'selected' : '' }}>Tesorería Central Universitaria</option>
                                    <option value="Transferencia Bancaria / QR" {{ old('banco_entidad') == 'Transferencia Bancaria / QR' ? 'selected' : '' }}>Transferencia Bancaria / QR</option>
                                    <option value="Banco Fassil" {{ old('banco_entidad') == 'Banco Fassil' ? 'selected' : '' }}>Banco Fassil / Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="concepto">Concepto de Pago: <b class="text-danger">*</b></label>
                        <input type="text" name="concepto" id="concepto" class="form-control" value="{{ old('concepto', 'Matrícula Módulo Regular') }}" required placeholder="Ej: Matrícula Inglés Módulo 1">
                    </div>

                    <div class="form-group">
                        <label for="archivo_comprobante">Subir Fotografía o Escaneo del Voucher:</label>
                        <div class="custom-file">
                            <input type="file" name="archivo_comprobante" class="custom-file-input" id="archivo_comprobante" accept="image/*,application/pdf" onchange="actualizarEtiquetaVoucher(event)">
                            <label class="custom-file-label" id="label-voucher" for="archivo_comprobante">Seleccionar comprobante digital...</label>
                        </div>
                        <small class="text-muted">Formatos admitidos: JPG, PNG o PDF (Máx. 4MB).</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold"><i class="fas fa-save mr-1"></i> Registrar y Validar Boleta</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Previsualización de Comprobante / Voucher -->
<div class="modal fade" id="modal-visor-comprobante" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-dark text-white py-2">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-receipt mr-1"></i> Voucher Boleta Nro. <span id="voucher-num"></span></h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="img-voucher" src="" class="img-fluid rounded border shadow-sm" style="max-height: 500px;" alt="Voucher">
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-boletas').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });

            $('.select2').select2({
                theme: 'bootstrap4',
                dropdownParent: $('#modal-nueva-boleta')
            });
        });

        function actualizarEtiquetaVoucher(event) {
            var fileName = event.target.files[0] ? event.target.files[0].name : "Seleccionar comprobante digital...";
            $('#label-voucher').html(fileName);
        }

        function verComprobante(url, numBoleta) {
            $('#voucher-num').text(numBoleta);
            $('#img-voucher').attr('src', url);
            $('#modal-visor-comprobante').modal('show');
        }

        function confirmarAnulacion(id, numBoleta) {
            Swal.fire({
                title: '¿Anular boleta Nro. ' + numBoleta + '?',
                text: "La boleta quedará inhabilitada para ser consumida en una matrícula.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, anular boleta',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('anular-boleta-' + id).submit();
                }
            });
        }

        function confirmarEliminarBoleta(id) {
            Swal.fire({
                title: '¿Eliminar boleta definitivamente?',
                text: "Esta acción no se puede deshacer.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-boleta-' + id).submit();
                }
            });
        }
    </script>
@stop

