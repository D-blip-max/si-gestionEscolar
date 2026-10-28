@extends('adminlte::page')

@section('title', 'Gestión de Roles y Permisos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-tag"></i> Roles y Permisos RBAC</h1>
        <div>
            <a href="{{ route('admin.roles.auditoria') }}" class="btn btn-outline-danger shadow-sm mr-2">
                <i class="fas fa-shield-alt"></i> Auditoría de Notas
            </a>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle"></i> Crear Nuevo Rol
            </a>
        </div>
    </div>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Matriz de Roles Registrados</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-roles" class="table table-bordered table-striped table-hover table-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nombre del Rol</th>
                                    <th>Permisos Asignados</th>
                                    <th style="width: 250px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $index => $role)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match ($role->name) {
                                                    'ADMINISTRADOR' => 'badge-danger',
                                                    'ADMINISTRATIVO' => 'badge-info',
                                                    'DOCENTE' => 'badge-primary',
                                                    'ESTUDIANTE' => 'badge-success',
                                                    default => 'badge-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }} p-2" style="font-size: 0.95rem;">
                                                <i class="fas fa-shield-alt mr-1"></i> {{ $role->name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light border p-2">
                                                <i class="fas fa-key text-warning mr-1"></i>
                                                <b>{{ $role->permissions_count ?? $role->permissions->count() }}</b> permisos activos
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.roles.permisos', $role->id) }}" class="btn btn-warning btn-sm" title="Configurar Permisos">
                                                    <i class="fas fa-check-circle"></i> Permisos
                                                </a>
                                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-success btn-sm" title="Editar Nombre">
                                                    <i class="fas fa-pencil-alt"></i> Editar
                                                </a>
                                                @if (!in_array($role->name, ['ADMINISTRADOR', 'ADMINISTRATIVO', 'DOCENTE', 'ESTUDIANTE']))
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar('{{ $role->id }}', '{{ $role->name }}')" title="Eliminar Rol">
                                                        <i class="fas fa-trash-alt"></i> Eliminar
                                                    </button>
                                                    <form id="form-delete-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
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
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#tabla-roles').DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay información disponible",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ roles",
                    "infoEmpty": "Mostrando 0 a 0 de 0 roles",
                    "infoFiltered": "(Filtrado de _MAX_ total roles)",
                    "lengthMenu": "Mostrar _MENU_ roles",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscador:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "responsive": true,
                "autoWidth": false
            });
        });

        function confirmarEliminar(id, nombre) {
            Swal.fire({
                title: '¿Eliminar rol?',
                text: "Se eliminará el rol " + nombre + " y se revocarán todos sus permisos asociados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash-alt"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete-' + id).submit();
                }
            });
        }

        @if (session('mensaje'))
            Swal.fire({
                position: 'top-end',
                icon: "{{ session('icono') ?? 'success' }}",
                title: "{{ session('mensaje') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>
@stop
