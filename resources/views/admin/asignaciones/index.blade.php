@extends('adminlte::page')

@section('title', 'Asignación Académica a Grupos')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-chalkboard-teacher text-primary mr-2"></i>Asignación de Docentes a Grupos</h1>
                <small class="text-muted">Gestión de Docentes Titulares por Paralelo Presencial / Virtual</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Asignaciones</li>
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

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title text-bold"><i class="fas fa-list mr-1"></i> Padrón de Asignaciones Académicas</h3>
            <div class="card-tools">
                <a href="{{ route('admin.asignaciones.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle mr-1"></i> Nueva Asignación Docente
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-asignaciones" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Docente Titular</th>
                            <th>Ciclo Bimestral</th>
                            <th>Idioma / Módulo</th>
                            <th>Grupo / Modalidad</th>
                            <th>Turno y Horario</th>
                            <th>Aula / Enlace</th>
                            <th>Estado</th>
                            <th style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($asignaciones as $asignacion)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $foto = ($asignacion->personal && $asignacion->personal->foto && file_exists(public_path($asignacion->personal->foto)))
                                                ? asset($asignacion->personal->foto)
                                                : asset('img/default-avatar.png');
                                        @endphp
                                        <img src="{{ $foto }}" class="img-circle elevation-1 mr-2" style="width: 38px; height: 38px; object-fit: cover;" alt="Docente">
                                        <div>
                                            <strong class="text-primary">{{ optional($asignacion->personal)->apellidos }} {{ optional($asignacion->personal)->nombres }}</strong><br>
                                            <small class="text-muted"><i class="fas fa-id-card"></i> CI: {{ optional($asignacion->personal)->ci ?? 'S/N' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-light border px-2 py-1">
                                        <i class="far fa-calendar-alt text-primary"></i> {{ optional($asignacion->paralelo->ciclo)->nombre ?? ($asignacion->gestion->nombre ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ optional(optional($asignacion->paralelo)->modulo)->idioma->nombre ?? 'Idioma General' }}</strong><br>
                                    <span class="text-muted">{{ optional(optional($asignacion->paralelo)->modulo)->nombre ?? ($asignacion->materia->nombre ?? 'N/A') }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="font-weight-bold">{{ optional($asignacion->paralelo)->nombre ?? 'Paralelo' }}</div>
                                    @php
                                        $modalidad = optional($asignacion->paralelo)->modalidad ?? 'PRESENCIAL';
                                    @endphp
                                    @if(strtoupper($modalidad) === 'VIRTUAL')
                                        <span class="badge badge-info"><i class="fas fa-laptop mr-1"></i> Virtual</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fas fa-building mr-1"></i> Presencial</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-warning text-dark font-weight-bold">
                                        {{ optional($asignacion->paralelo)->turno ?? (optional($asignacion->turno)->nombre ?? 'Regular') }}
                                    </span><br>
                                    <small class="text-muted">
                                        {{ optional($asignacion->paralelo)->hora_inicio ? substr($asignacion->paralelo->hora_inicio, 0, 5) : '00:00' }} - 
                                        {{ optional($asignacion->paralelo)->hora_fin ? substr($asignacion->paralelo->hora_fin, 0, 5) : '00:00' }}
                                    </small>
                                </td>
                                <td class="text-center align-middle">
                                    @if(strtoupper(optional($asignacion->paralelo)->modalidad) === 'VIRTUAL')
                                        @if(optional($asignacion->paralelo)->aula_o_enlace)
                                            <a href="{{ optional($asignacion->paralelo)->aula_o_enlace }}" target="_blank" class="btn btn-outline-info btn-xs text-truncate" style="max-width: 140px;" title="{{ optional($asignacion->paralelo)->aula_o_enlace }}">
                                                <i class="fas fa-video mr-1"></i> Enlace Reunión
                                            </a>
                                        @else
                                            <span class="text-muted small">Sin enlace</span>
                                        @endif
                                    @else
                                        <span class="badge badge-light border"><i class="fas fa-door-open text-primary mr-1"></i> {{ optional($asignacion->paralelo)->aula_o_enlace ?? 'Aula General' }}</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if(strtolower($asignacion->estado) === 'activo')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> ACTIVO</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle"></i> INACTIVO</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.asignaciones.show', $asignacion->id) }}" class="btn btn-info" title="Ver Detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.asignaciones.edit', $asignacion->id) }}" class="btn btn-warning" title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" onclick="confirmarEliminacion({{ $asignacion->id }})" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $asignacion->id }}" action="{{ route('admin.asignaciones.destroy', $asignacion->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-asignaciones').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });

        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Está seguro de eliminar esta asignación?',
                text: "El docente titular será desvinculado de este grupo modular.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@stop
