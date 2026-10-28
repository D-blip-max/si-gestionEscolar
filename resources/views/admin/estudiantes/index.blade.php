@extends('adminlte::page')

@section('title', 'Padrón de Estudiantes')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user-graduate text-primary mr-2"></i>Padrón de Estudiantes</h1>
                <small class="text-muted">CU-10: Registro y Gestión de Estudiantes Autónomos del Instituto</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Estudiantes</li>
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
            <h3 class="card-title text-bold"><i class="fas fa-users mr-1"></i> Estudiantes Matriculados</h3>
            <div class="card-tools">
                <a href="{{ route('admin.estudiantes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus mr-1"></i> Nuevo Estudiante
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-estudiantes" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Código CEI</th>
                            <th>Estudiante</th>
                            <th>Cédula (CI)</th>
                            <th>Carrera / Origen</th>
                            <th>Contacto / Email</th>
                            <th>Boletas / Estado</th>
                            <th style="width: 140px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estudiantes as $estudiante)
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-primary font-weight-bold px-2 py-1">
                                        {{ $estudiante->codigo_estudiante ?? 'CEI-' . str_pad($estudiante->id, 4, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $foto = ($estudiante->foto && file_exists(public_path($estudiante->foto)))
                                                ? asset($estudiante->foto)
                                                : asset('vendor/adminlte/dist/img/avatar.png');
                                        @endphp
                                        <img src="{{ $foto }}" class="img-circle elevation-1 mr-2" style="width: 38px; height: 38px; object-fit: cover;" alt="Avatar">
                                        <div>
                                            <strong class="text-dark">{{ $estudiante->apellidos }} {{ $estudiante->nombres }}</strong><br>
                                            <small class="text-muted"><i class="fas fa-venus-mars mr-1"></i> {{ ucfirst($estudiante->genero) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle font-weight-bold">
                                    {{ $estudiante->ci }}
                                </td>
                                <td class="align-middle text-sm">
                                    <i class="fas fa-university text-secondary mr-1"></i> {{ $estudiante->carrera_facultad ?? 'Particular' }}
                                </td>
                                <td class="align-middle text-sm">
                                    <div><i class="fab fa-whatsapp text-success mr-1"></i> {{ $estudiante->telefono }}</div>
                                    <small class="text-muted"><i class="fas fa-envelope mr-1"></i> {{ optional($estudiante->usuario)->email ?? 'S/C' }}</small>
                                </td>
                                <td class="text-center align-middle">
                                    @php
                                        $boletasDisp = $estudiante->boletas->where('estado', 'DISPONIBLE')->count();
                                    @endphp
                                    @if($boletasDisp > 0)
                                        <span class="badge badge-success mb-1" title="Boletas listas para matricular"><i class="fas fa-ticket-alt mr-1"></i> {{ $boletasDisp }} Boleta(s)</span><br>
                                    @endif
                                    @if(strtolower($estudiante->estado) === 'activo')
                                        <span class="badge badge-light border text-success"><i class="fas fa-check-circle"></i> ACTIVO</span>
                                    @else
                                        <span class="badge badge-light border text-danger"><i class="fas fa-times-circle"></i> INACTIVO</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.estudiantes.kardex', $estudiante->id) }}" class="btn btn-outline-primary" title="Kárdex Académico (CU-12)">
                                            <i class="fas fa-graduation-cap"></i>
                                        </a>
                                        <a href="{{ route('admin.estudiantes.show', $estudiante->id) }}" class="btn btn-info" title="Ver Perfil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.estudiantes.edit', $estudiante->id) }}" class="btn btn-warning" title="Editar">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" onclick="confirmarEliminar({{ $estudiante->id }})" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <form id="delete-estudiante-{{ $estudiante->id }}" action="{{ route('admin.estudiantes.destroy', $estudiante->id) }}" method="POST" style="display: none;">
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
            $('#tabla-estudiantes').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });

        function confirmarEliminar(id) {
            Swal.fire({
                title: '¿Eliminar estudiante?',
                text: "Se eliminará su registro y el usuario de acceso al portal.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-estudiante-' + id).submit();
                }
            });
        }
    </script>
@stop
