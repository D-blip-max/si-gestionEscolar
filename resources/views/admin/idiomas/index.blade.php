@extends('adminlte::page')

@section('title', 'Gestión de Idiomas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-language"></i> Catálogo de Idiomas Ofertados</h1>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modal-crear-idioma">
            <i class="fas fa-plus-circle"></i> Nuevo Idioma
        </button>
    </div>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Idiomas Disponibles en el Instituto</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-idiomas" class="table table-bordered table-striped table-hover table-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Código</th>
                                    <th>Idioma</th>
                                    <th>Descripción</th>
                                    <th>Módulos</th>
                                    <th>Estado</th>
                                    <th style="width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($idiomas as $index => $idioma)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-dark font-weight-bold p-1">{{ $idioma->codigo }}</span>
                                        </td>
                                        <td><b>{{ $idioma->nombre }}</b></td>
                                        <td>{{ $idioma->descripcion ?? 'Sin descripción' }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-info p-1">
                                                <i class="fas fa-layer-group"></i> {{ $idioma->modulos_count }} Módulos
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($idioma->estado === 'ACTIVO')
                                                <span class="badge badge-success p-1"><i class="fas fa-check"></i> ACTIVO</span>
                                            @else
                                                <span class="badge badge-secondary p-1"><i class="fas fa-ban"></i> INACTIVO</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    onclick="cargarEdicion('{{ $idioma->id }}', '{{ addslashes($idioma->nombre) }}', '{{ $idioma->codigo }}', '{{ addslashes($idioma->descripcion ?? '') }}', '{{ $idioma->estado }}')"
                                                    title="Editar Idioma">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmarEliminar('{{ $idioma->id }}', '{{ addslashes($idioma->nombre) }}')"
                                                    title="Eliminar Idioma">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="form-delete-{{ $idioma->id }}" action="{{ route('admin.idiomas.destroy', $idioma->id) }}" method="POST" style="display: none;">
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

    {{-- MODAL CREAR IDIOMA --}}
    <div class="modal fade" id="modal-crear-idioma" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Registrar Nuevo Idioma</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.idiomas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="create_nombre">Nombre del Idioma <span class="text-danger">(*)</span></label>
                            <input type="text" name="nombre" id="create_nombre" class="form-control" placeholder="Ej: Inglés, Guaraní, Francés" required>
                        </div>
                        <div class="form-group">
                            <label for="create_codigo">Código Abreviado <span class="text-danger">(*)</span></label>
                            <input type="text" name="codigo" id="create_codigo" class="form-control text-uppercase" placeholder="Ej: ENG, GUA, FRA" maxlength="10" required>
                        </div>
                        <div class="form-group">
                            <label for="create_descripcion">Descripción</label>
                            <textarea name="descripcion" id="create_descripcion" class="form-control" rows="2" placeholder="Observaciones o descripción del programa"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="create_estado">Estado</label>
                            <select name="estado" id="create_estado" class="form-control" required>
                                <option value="ACTIVO" selected>ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Idioma</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDITAR IDIOMA --}}
    <div class="modal fade" id="modal-editar-idioma" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Idioma</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-idioma" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_nombre">Nombre del Idioma <span class="text-danger">(*)</span></label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_codigo">Código Abreviado <span class="text-danger">(*)</span></label>
                            <input type="text" name="codigo" id="edit_codigo" class="form-control text-uppercase" maxlength="10" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_descripcion">Descripción</label>
                            <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_estado">Estado</label>
                            <select name="estado" id="edit_estado" class="form-control" required>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Actualizar Idioma</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#tabla-idiomas').DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay idiomas registrados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ idiomas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 idiomas",
                    "infoFiltered": "(Filtrado de _MAX_ total idiomas)",
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron coincidencias",
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

        function cargarEdicion(id, nombre, codigo, descripcion, estado) {
            $('#form-edit-idioma').attr('action', "{{ url('/admin/idiomas') }}/" + id);
            $('#edit_nombre').val(nombre);
            $('#edit_codigo').val(codigo);
            $('#edit_descripcion').val(descripcion);
            $('#edit_estado').val(estado);
            $('#modal-editar-idioma').modal('show');
        }

        function confirmarEliminar(id, nombre) {
            Swal.fire({
                title: '¿Eliminar idioma?',
                text: "Se eliminará el idioma \"" + nombre + "\". Si tiene módulos o inscripciones no podrá ser eliminado.",
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

