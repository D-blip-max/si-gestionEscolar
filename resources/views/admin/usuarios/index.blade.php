@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-users-cog"></i> Gestión de Usuarios</h1>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Padrón de Usuarios del Sistema</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-usuarios" class="table table-bordered table-striped table-hover table-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th style="width: 60px;">Foto</th>
                                    <th>Nombre Completo</th>
                                    <th>Correo Electrónico</th>
                                    <th>Rol Asignado</th>
                                    <th>Fecha Registro</th>
                                    <th style="width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user)
                                    <tr>
                                        <td class="text-center align-middle">{{ $index + 1 }}</td>
                                        <td class="text-center align-middle">
                                            <img src="{{ $user->adminlte_image() }}" alt="Avatar" class="img-circle elevation-1" style="width: 38px; height: 38px; object-fit: cover;">
                                        </td>
                                        <td class="align-middle font-weight-bold">{{ $user->name }}</td>
                                        <td class="align-middle">{{ $user->email }}</td>
                                        <td class="text-center">
                                            @forelse ($user->roles as $rol)
                                                @php
                                                    $badgeClass = match ($rol->name) {
                                                        'ADMINISTRADOR' => 'badge-danger',
                                                        'ADMINISTRATIVO' => 'badge-info',
                                                        'DOCENTE' => 'badge-primary',
                                                        'ESTUDIANTE' => 'badge-success',
                                                        default => 'badge-secondary',
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }} p-1">{{ $rol->name }}</span>
                                            @empty
                                                <span class="badge badge-secondary">Sin Rol</span>
                                            @endforelse
                                        </td>
                                        <td class="text-center">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Editar Usuario">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if ($user->id !== auth()->id())
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar('{{ $user->id }}', '{{ $user->name }}')" title="Eliminar Usuario">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    <form id="form-delete-{{ $user->id }}" action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" style="display: none;">
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
            $('#tabla-usuarios').DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay información disponible",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ usuarios",
                    "infoEmpty": "Mostrando 0 a 0 de 0 usuarios",
                    "infoFiltered": "(Filtrado de _MAX_ total usuarios)",
                    "lengthMenu": "Mostrar _MENU_ usuarios",
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
                "lengthChange": true,
                "autoWidth": false,
                "dom": 'Bfrtip',
                "buttons": [
                    { extend: 'copy', text: '<i class="fas fa-copy"></i> Copiar', className: 'btn btn-default btn-sm' },
                    { extend: 'excel', text: '<i class="fas fa-file-excel text-success"></i> Excel', className: 'btn btn-default btn-sm' },
                    { extend: 'pdf', text: '<i class="fas fa-file-pdf text-danger"></i> PDF', className: 'btn btn-default btn-sm' },
                    { extend: 'print', text: '<i class="fas fa-print"></i> Imprimir', className: 'btn btn-default btn-sm' }
                ]
            });
        });

        function confirmarEliminar(id, nombre) {
            Swal.fire({
                title: '¿Eliminar usuario?',
                text: "Se eliminará la cuenta de " + nombre + ". ¡Esta acción no se puede deshacer!",
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
