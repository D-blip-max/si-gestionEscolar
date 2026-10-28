@extends('adminlte::page')

@section('title', 'Perfil del Estudiante')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-id-badge text-info mr-2"></i>Perfil del Estudiante</h1>
                <small class="text-muted">Ficha Integral de Estudiante y Resumen de Estado</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.estudiantes.index') }}">Estudiantes</a></li>
                    <li class="breadcrumb-item active">Perfil</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Tarjeta de Perfil -->
        <div class="col-md-4">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-body box-profile text-center">
                    @php
                        $foto = ($estudiante->foto && file_exists(public_path($estudiante->foto)))
                            ? asset($estudiante->foto)
                            : asset('vendor/adminlte/dist/img/avatar.png');
                    @endphp
                    <img class="profile-user-img img-fluid img-circle shadow-sm mb-3" style="width: 120px; height: 120px; object-fit: cover;" src="{{ $foto }}" alt="Avatar">

                    <h4 class="profile-username font-weight-bold mb-0">{{ $estudiante->apellidos }} {{ $estudiante->nombres }}</h4>
                    <p class="text-primary font-weight-bold">{{ $estudiante->codigo_estudiante ?? 'CEI-'.$estudiante->id }}</p>

                    <ul class="list-group list-group-unbordered text-left mb-3">
                        <li class="list-group-item">
                            <b><i class="fas fa-id-card text-muted mr-1"></i> CI:</b> <span class="float-right text-dark font-weight-bold">{{ $estudiante->ci }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-envelope text-muted mr-1"></i> Email:</b> <span class="float-right text-dark">{{ optional($estudiante->usuario)->email ?? 'S/C' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fab fa-whatsapp text-success mr-1"></i> Teléfono:</b> <span class="float-right text-dark">{{ $estudiante->telefono }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-university text-muted mr-1"></i> Carrera:</b> <span class="float-right text-dark">{{ $estudiante->carrera_facultad ?? 'Particular' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-calendar-alt text-muted mr-1"></i> Nacimiento:</b> <span class="float-right text-dark">{{ $estudiante->fecha_nacimiento }}</span>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fas fa-map-marker-alt text-muted mr-1"></i> Dirección:</b> <span class="float-right text-dark">{{ $estudiante->direccion ?? 'Sin especificar' }}</span>
                        </li>
                    </ul>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.estudiantes.kardex', $estudiante->id) }}" class="btn btn-outline-primary btn-block mr-1">
                            <i class="fas fa-graduation-cap mr-1"></i> Kárdex Académico
                        </a>
                        <a href="{{ route('admin.estudiantes.edit', $estudiante->id) }}" class="btn btn-outline-warning btn-block ml-1 mt-0">
                            <i class="fas fa-edit mr-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boletas Registradas y Matrículas -->
        <div class="col-md-8">
            <!-- Boletas Bancarias Registradas -->
            <div class="card card-outline card-success shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h3 class="card-title text-bold"><i class="fas fa-file-invoice-dollar mr-1"></i> Boletas Bancarias Registradas</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.boletas.index') }}" class="btn btn-xs btn-success">
                            <i class="fas fa-plus-circle mr-1"></i> Registrar Boleta
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nro. Boleta</th>
                                    <th>Monto</th>
                                    <th>Fecha Depósito</th>
                                    <th>Entidad / Banco</th>
                                    <th>Concepto</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($estudiante->boletas as $boleta)
                                    <tr>
                                        <td class="font-weight-bold">{{ $boleta->numero_boleta }}</td>
                                        <td>Bs. {{ number_format($boleta->monto, 2) }}</td>
                                        <td>{{ $boleta->fecha_deposito }}</td>
                                        <td>{{ $boleta->banco_entidad }}</td>
                                        <td>{{ $boleta->concepto }}</td>
                                        <td>
                                            @if($boleta->estado === 'DISPONIBLE')
                                                <span class="badge badge-success"><i class="fas fa-check"></i> DISPONIBLE</span>
                                            @elseif($boleta->estado === 'UTILIZADA')
                                                <span class="badge badge-primary"><i class="fas fa-check-double"></i> UTILIZADA</span>
                                            @else
                                                <span class="badge badge-danger"><i class="fas fa-times"></i> ANULADA</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3 text-muted">No registra boletas de depósito bancario.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resumen de Módulos Cursados -->
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title text-bold"><i class="fas fa-book-reader mr-1"></i> Módulos Cursados / Matriculaciones</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Ciclo</th>
                                    <th>Idioma / Módulo</th>
                                    <th>Modalidad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($estudiante->inscripciones as $inscripcion)
                                    <tr>
                                        <td>{{ optional(optional($inscripcion->paralelo)->ciclo)->nombre ?? 'Ciclo' }}</td>
                                        <td><strong>{{ optional($inscripcion->modulo->idioma)->nombre }}</strong> - {{ optional($inscripcion->modulo)->nombre }}</td>
                                        <td>
                                            @if(strtoupper(optional($inscripcion->paralelo)->modalidad) === 'VIRTUAL')
                                                <span class="badge badge-info"><i class="fas fa-laptop mr-1"></i> Virtual</span>
                                            @else
                                                <span class="badge badge-secondary"><i class="fas fa-building mr-1"></i> Presencial</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $inscripcion->estado }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">Aún no registra inscripciones modulares en el sistema.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
