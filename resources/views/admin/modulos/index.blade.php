@extends('adminlte::page')

@section('title', 'Gestión de Módulos y Niveles')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-layer-group text-primary"></i> Estructura Curricular: Módulos y Niveles</h1>
        <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modal-crear-modulo">
            <i class="fas fa-plus-circle"></i> Nuevo Módulo
        </button>
    </div>
    <hr>
@stop

@section('content')
    {{-- TARJETAS INFORMATIVAS DE LA ESTRUCTURA CURRICULAR --}}
    <div class="row">
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning text-white"><i class="fas fa-seedling"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">NIVEL BÁSICO</span>
                    <span class="info-box-number text-muted">Módulos 1 al 4 (8 meses)</span>
                    <small>Fundamentos comunicativos</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary"><i class="fas fa-graduation-cap"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">NIVEL INTERMEDIO</span>
                    <span class="info-box-number text-muted">Módulos 5 al 8 (8 meses)</span>
                    <small class="text-success font-weight-bold"><i class="fas fa-award"></i> Meta: Titulación Intermedia</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-purple text-white" style="background-color: #6f42c1;"><i class="fas fa-certificate"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">NIVEL AVANZADO</span>
                    <span class="info-box-number text-muted">Módulo 9 (2 meses)</span>
                    <small class="text-purple font-weight-bold"><i class="fas fa-star"></i> Certificación Avanzada</small>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE MÓDULOS --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-book"></i> Módulos Curriculares Registrados</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-modulos" class="table table-bordered table-striped table-hover table-sm">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 40px;">Orden</th>
                                    <th>Idioma</th>
                                    <th>Nombre del Módulo</th>
                                    <th>Nivel Académico</th>
                                    <th>Prerrequisito Obligatorio</th>
                                    <th>Hito de Graduación</th>
                                    <th>Duración</th>
                                    <th style="width: 120px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modulos as $modulo)
                                    <tr>
                                        <td class="text-center font-weight-bold">
                                            <span class="badge badge-secondary p-1">#{{ $modulo->numero_orden }}</span>
                                        </td>
                                        <td>
                                            <i class="fas fa-language text-muted mr-1"></i>
                                            <b>{{ $modulo->idioma->nombre ?? 'N/A' }}</b>
                                            <small class="badge badge-light border">({{ $modulo->idioma->codigo ?? '' }})</small>
                                        </td>
                                        <td>{{ $modulo->nombre }}</td>
                                        <td class="text-center">
                                            @if ($modulo->nivel === 'BASICO')
                                                <span class="badge badge-warning text-dark font-weight-bold p-1">BÁSICO</span>
                                            @elseif ($modulo->nivel === 'INTERMEDIO')
                                                <span class="badge badge-primary font-weight-bold p-1">INTERMEDIO</span>
                                            @else
                                                <span class="badge badge-info font-weight-bold p-1" style="background-color: #6f42c1; color: white;">AVANZADO</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($modulo->prerrequisito)
                                                <span class="badge badge-light border p-1">
                                                    <i class="fas fa-arrow-left text-danger mr-1"></i> {{ $modulo->prerrequisito->nombre }}
                                                </span>
                                            @else
                                                <span class="badge badge-success p-1"><i class="fas fa-check"></i> Inicial (Sin Correlativa)</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($modulo->numero_orden == 8)
                                                <span class="badge badge-warning text-dark font-weight-bold p-1 border">
                                                    <i class="fas fa-graduation-cap"></i> Titulación Intermedia
                                                </span>
                                            @elseif ($modulo->numero_orden == 9)
                                                <span class="badge badge-success font-weight-bold p-1 border">
                                                    <i class="fas fa-award"></i> Certificación Avanzada
                                                </span>
                                            @else
                                                <span class="text-muted text-sm">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted">
                                            {{ $modulo->duracion_meses }} meses
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                @can('admin.modulos.edit')
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="cargarEdicion('{{ $modulo->id }}', '{{ $modulo->idioma_id }}', '{{ addslashes($modulo->nombre) }}', '{{ $modulo->numero_orden }}', '{{ $modulo->nivel }}', '{{ $modulo->prerrequisito_id ?? '' }}', '{{ $modulo->duracion_meses }}')"
                                                        title="Editar Módulo">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                @endcan
                                                @can('admin.modulos.destroy')
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="confirmarEliminar('{{ $modulo->id }}', '{{ addslashes($modulo->nombre) }}')"
                                                        title="Eliminar Módulo">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                @endcan
                                                <form id="form-delete-{{ $modulo->id }}" action="{{ route('admin.modulos.destroy', $modulo->id) }}" method="POST" style="display: none;">
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

    {{-- MODAL CREAR MÓDULO --}}
    <div class="modal fade" id="modal-crear-modulo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Nuevo Módulo Curricular</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.modulos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="create_idioma_id">Idioma Ofertado <span class="text-danger">(*)</span></label>
                            <select name="idioma_id" id="create_idioma_id" class="form-control" required>
                                <option value="" disabled selected>-- Seleccione un Idioma --</option>
                                @foreach ($idiomas as $idioma)
                                    <option value="{{ $idioma->id }}">{{ $idioma->nombre }} ({{ $idioma->codigo }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="create_nombre">Nombre del Módulo <span class="text-danger">(*)</span></label>
                                    <input type="text" name="nombre" id="create_nombre" class="form-control" placeholder="Ej: Módulo 1, Módulo 2" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="create_numero_orden">Orden (1-9) <span class="text-danger">(*)</span></label>
                                    <input type="number" name="numero_orden" id="create_numero_orden" class="form-control text-center" min="1" max="20" placeholder="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_nivel">Nivel Curricular <span class="text-danger">(*)</span></label>
                                    <select name="nivel" id="create_nivel" class="form-control" required>
                                        <option value="BASICO" selected>BÁSICO (Módulos 1-4)</option>
                                        <option value="INTERMEDIO">INTERMEDIO (Módulos 5-8)</option>
                                        <option value="AVANZADO">AVANZADO (Módulo 9)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_duracion_meses">Duración (Meses)</label>
                                    <input type="number" name="duracion_meses" id="create_duracion_meses" class="form-control text-center" value="2" min="1" max="12">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="create_prerrequisito_id">Prerrequisito Correlativo Obligatorio</label>
                            <select name="prerrequisito_id" id="create_prerrequisito_id" class="form-control">
                                <option value="">-- Sin prerrequisito (Módulo inicial) --</option>
                                @foreach ($modulos as $m)
                                    <option value="{{ $m->id }}">{{ $m->idioma->codigo ?? '' }} - {{ $m->nombre }} (#{{ $m->numero_orden }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">El estudiante debe aprobar este módulo antes de inscribirse al nuevo módulo.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Módulo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDITAR MÓDULO --}}
    <div class="modal fade" id="modal-editar-modulo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Módulo Curricular</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-modulo" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_idioma_id">Idioma <span class="text-danger">(*)</span></label>
                            <select name="idioma_id" id="edit_idioma_id" class="form-control" required>
                                @foreach ($idiomas as $idioma)
                                    <option value="{{ $idioma->id }}">{{ $idioma->nombre }} ({{ $idioma->codigo }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="edit_nombre">Nombre del Módulo <span class="text-danger">(*)</span></label>
                                    <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_numero_orden">Orden <span class="text-danger">(*)</span></label>
                                    <input type="number" name="numero_orden" id="edit_numero_orden" class="form-control text-center" min="1" max="20" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_nivel">Nivel <span class="text-danger">(*)</span></label>
                                    <select name="nivel" id="edit_nivel" class="form-control" required>
                                        <option value="BASICO">BÁSICO</option>
                                        <option value="INTERMEDIO">INTERMEDIO</option>
                                        <option value="AVANZADO">AVANZADO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_duracion_meses">Duración (Meses)</label>
                                    <input type="number" name="duracion_meses" id="edit_duracion_meses" class="form-control text-center" min="1" max="12">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_prerrequisito_id">Prerrequisito Obligatorio</label>
                            <select name="prerrequisito_id" id="edit_prerrequisito_id" class="form-control">
                                <option value="">-- Sin prerrequisito --</option>
                                @foreach ($modulos as $m)
                                    <option value="{{ $m->id }}">{{ $m->idioma->codigo ?? '' }} - {{ $m->nombre }} (#{{ $m->numero_orden }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Actualizar Módulo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#tabla-modulos').DataTable({
                "pageLength": 15,
                "order": [[0, "asc"]],
                "language": {
                    "emptyTable": "No hay módulos registrados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ módulos",
                    "infoEmpty": "Mostrando 0 a 0 de 0 módulos",
                    "infoFiltered": "(Filtrado de _MAX_ total módulos)",
                    "lengthMenu": "Mostrar _MENU_ módulos",
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

        function cargarEdicion(id, idioma_id, nombre, numero_orden, nivel, prerrequisito_id, duracion_meses) {
            $('#form-edit-modulo').attr('action', "{{ url('/admin/modulos') }}/" + id);
            $('#edit_idioma_id').val(idioma_id);
            $('#edit_nombre').val(nombre);
            $('#edit_numero_orden').val(numero_orden);
            $('#edit_nivel').val(nivel);
            $('#edit_prerrequisito_id').val(prerrequisito_id);
            $('#edit_duracion_meses').val(duracion_meses);
            $('#modal-editar-modulo').modal('show');
        }

        function confirmarEliminar(id, nombre) {
            Swal.fire({
                title: '¿Eliminar módulo?',
                text: "Se eliminará el módulo \"" + nombre + "\". Si es prerrequisito de otro módulo la acción será rechazada.",
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

