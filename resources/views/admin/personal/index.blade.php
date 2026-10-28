@extends('adminlte::page')

@section('title', 'Padrón de Personal')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1>
            <i class="fas fa-id-badge text-primary"></i> Padrón de Personal:
            <span class="badge {{ $tipo === 'docente' ? 'badge-primary' : 'badge-info' }} text-uppercase">{{ $tipo }}s</span>
        </h1>
        <div>
            <div class="btn-group mr-2" role="group">
                <a href="{{ url('/admin/personal/docente') }}" class="btn {{ $tipo === 'docente' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="fas fa-chalkboard-teacher"></i> Docentes
                </a>
                <a href="{{ url('/admin/personal/administrativo') }}" class="btn {{ $tipo === 'administrativo' ? 'btn-info' : 'btn-outline-info' }}">
                    <i class="fas fa-user-tie"></i> Administrativos
                </a>
            </div>
            <a href="{{ url('/admin/personal/create/' . $tipo) }}" class="btn btn-success shadow-sm">
                <i class="fas fa-user-plus"></i> Registrar {{ ucfirst($tipo) }}
            </a>
        </div>
    </div>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline {{ $tipo === 'docente' ? 'card-primary' : 'card-info' }} shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i> Lista de Personal Registrado ({{ count($personals) }})
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-personal" class="table table-bordered table-striped table-hover table-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th style="width: 60px;">Foto</th>
                                    <th>Apellidos y Nombres</th>
                                    <th>C.I.</th>
                                    <th>Rol Asignado</th>
                                    <th>Profesión</th>
                                    <th>Teléfono / WhatsApp</th>
                                    <th>Correo Electrónico</th>
                                    <th style="width: 160px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($personals as $index => $personal)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">
                                            @php
                                                $imgSrc = ($personal->foto && file_exists(public_path($personal->foto)))
                                                    ? asset($personal->foto)
                                                    : asset('img/default-avatar.png');
                                            @endphp
                                            <img src="{{ $imgSrc }}" alt="foto" class="img-circle elevation-1"
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                        </td>
                                        <td><b>{{ $personal->apellidos }}</b> {{ $personal->nombres }}</td>
                                        <td class="text-center font-weight-bold">{{ $personal->ci }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-primary p-1">
                                                {{ $personal->usuario->roles->pluck('name')->implode(', ') ?: 'Sin Rol' }}
                                            </span>
                                        </td>
                                        <td>{{ $personal->profesion }}</td>
                                        <td class="text-center">
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $personal->telefono) }}" target="_blank" class="text-success font-weight-bold">
                                                <i class="fab fa-whatsapp"></i> {{ $personal->telefono }}
                                            </a>
                                        </td>
                                        <td>{{ $personal->usuario->email ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ url('/admin/personal/' . $personal->id . '/formaciones') }}"
                                                    class="btn btn-info btn-sm" title="Títulos y Certificaciones de Idiomas">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </a>
                                                <a href="{{ url('/admin/personal/show/' . $personal->id) }}"
                                                    class="btn btn-secondary btn-sm" title="Ver Ficha">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ url('/admin/personal/' . $personal->id . '/edit') }}"
                                                    class="btn btn-warning btn-sm" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmarEliminar('{{ $personal->id }}', '{{ addslashes($personal->nombres . ' ' . $personal->apellidos) }}')"
                                                    title="Eliminar Personal">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="form-delete-{{ $personal->id }}" action="{{ url('/admin/personal/' . $personal->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
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
            $('#tabla-personal').DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay personal registrado en esta categoría",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "infoFiltered": "(Filtrado de _MAX_ total)",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "responsive": true,
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
                title: '¿Eliminar personal?',
                text: "Se eliminará la ficha de " + nombre + " y su usuario de acceso al sistema.",
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
