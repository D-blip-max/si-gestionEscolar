@extends('adminlte::page')

@section('title', 'Conciliación de Boletas Bancarias')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-money-check-alt text-primary mr-2"></i>Conciliación de Boletas para Tesorería</h1>
                <small class="text-muted">CU-22: Fiscalización y Auditoría Financiera de Depósitos Bancarios (Cero Efectivo)</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Conciliación de Boletas</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- Filtros de Conciliación -->
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-header bg-white">
            <h5 class="card-title text-bold mb-0"><i class="fas fa-filter text-primary mr-1"></i> Filtros de Auditoría para Tesorería</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reportes.conciliacion') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-2">
                            <label class="text-sm">Fecha Depósito Desde:</label>
                            <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ $fechaDesde }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-2">
                            <label class="text-sm">Fecha Depósito Hasta:</label>
                            <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ $fechaHasta }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-2">
                            <label class="text-sm">Banco / Entidad:</label>
                            <select name="banco_entidad" class="form-control form-control-sm">
                                <option value="">-- Todos los Bancos --</option>
                                @foreach($bancos as $b)
                                    <option value="{{ $b }}" {{ $bancoEntidad == $b ? 'selected' : '' }}>{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-2">
                            <label class="text-sm">Estado:</label>
                            <select name="estado" class="form-control form-control-sm">
                                <option value="">-- Todos los Estados --</option>
                                <option value="DISPONIBLE" {{ $estado === 'DISPONIBLE' ? 'selected' : '' }}>DISPONIBLE</option>
                                <option value="UTILIZADA" {{ $estado === 'UTILIZADA' ? 'selected' : '' }}>UTILIZADA</option>
                                <option value="ANULADA" {{ $estado === 'ANULADA' ? 'selected' : '' }}>ANULADA</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.reportes.conciliacion') }}" class="btn btn-secondary btn-sm mr-2">
                            <i class="fas fa-eraser mr-1"></i> Limpiar Filtros
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm font-weight-bold mr-2">
                            <i class="fas fa-search mr-1"></i> Filtrar Boletas
                        </button>
                        <a href="{{ route('admin.reportes.conciliacion_pdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm font-weight-bold">
                            <i class="fas fa-file-pdf mr-1"></i> Exportar Informe Oficial (PDF)
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Totales Informativos -->
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="info-box shadow-sm bg-success">
                <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Importe Total Recaudado (Válido)</span>
                    <span class="info-box-number" style="font-size: 24px;">Bs. {{ number_format($totalRecaudado, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="info-box shadow-sm bg-info">
                <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Cantidad Total de Boletas Auditadas</span>
                    <span class="info-box-number" style="font-size: 24px;">{{ $cantidadBoletas }} Boletas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Detallada de Boletas -->
    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title text-bold mb-0"><i class="fas fa-table text-primary mr-1"></i> Detalle de Boletas Bancarias Conciliadas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-conciliacion" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Nro. Boleta</th>
                            <th>Estudiante</th>
                            <th>CI</th>
                            <th>Fecha Depósito</th>
                            <th>Banco / Entidad</th>
                            <th>Monto (Bs.)</th>
                            <th>Concepto</th>
                            <th>Estado</th>
                            <th>Módulo / Destino</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($boletas as $b)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle font-weight-bold">{{ $b->numero_boleta }}</td>
                                <td class="align-middle">{{ optional($b->estudiante)->apellidos }} {{ optional($b->estudiante)->nombres }}</td>
                                <td class="text-center align-middle">{{ optional($b->estudiante)->ci }}</td>
                                <td class="text-center align-middle">{{ $b->fecha_deposito }}</td>
                                <td class="text-center align-middle text-sm">{{ $b->banco_entidad }}</td>
                                <td class="text-right align-middle font-weight-bold text-success">Bs. {{ number_format($b->monto, 2) }}</td>
                                <td class="align-middle text-sm">{{ $b->concepto }}</td>
                                <td class="text-center align-middle">
                                    @if($b->estado === 'DISPONIBLE')
                                        <span class="badge badge-success">DISPONIBLE</span>
                                    @elseif($b->estado === 'UTILIZADA')
                                        <span class="badge badge-primary">UTILIZADA</span>
                                    @else
                                        <span class="badge badge-danger">ANULADA</span>
                                    @endif
                                </td>
                                <td class="align-middle text-sm">
                                    {{ optional(optional($b->inscripcion)->modulo)->nombre ?? 'En Espera de Matrícula' }}
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
            $('#tabla-conciliacion').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });
    </script>
@stop

