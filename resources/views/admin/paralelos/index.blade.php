@extends('adminlte::page')

@section('title', 'Gestión de Ciclos y Paralelos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h1><i class="fas fa-chalkboard-teacher text-primary"></i> Ciclos Bimestrales y Grupos (Presencial / Virtual)</h1>
        <div>
            <button type="button" class="btn btn-outline-info shadow-sm mr-2" data-toggle="modal" data-target="#modal-gestionar-ciclos">
                <i class="fas fa-calendar-alt"></i> Gestionar Ciclos
            </button>
            <button type="button" class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modal-crear-paralelo">
                <i class="fas fa-plus-circle"></i> Nuevo Grupo / Paralelo
            </button>
        </div>
    </div>
    <hr>
@stop

@section('content')
    {{-- FILTRO POR CICLO BIMESTRAL --}}
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-body py-2">
            <form action="{{ route('admin.paralelos.index') }}" method="GET" class="form-inline d-flex align-items-center">
                <label for="select-ciclo" class="mr-2 font-weight-bold"><i class="fas fa-filter text-primary"></i> Ciclo Bimestral Activo:</label>
                <select name="ciclo_id" id="select-ciclo" class="form-control mr-3" onchange="this.form.submit()">
                    @forelse ($ciclos as $c)
                        <option value="{{ $c->id }}" {{ $c->id == $cicloSeleccionadoId ? 'selected' : '' }}>
                            {{ $c->nombre }} ({{ $c->gestion }}) - [{{ $c->estado }}]
                        </option>
                    @empty
                        <option value="">Sin ciclos registrados (Cree uno)</option>
                    @endforelse
                </select>
                @php
                    $cicloActual = $ciclos->firstWhere('id', $cicloSeleccionadoId);
                @endphp
                @if ($cicloActual)
                    <span class="badge {{ $cicloActual->estado === 'ACTIVO' ? 'badge-success' : ($cicloActual->estado === 'FUTURO' ? 'badge-info' : 'badge-secondary') }} p-2">
                        Estado: {{ $cicloActual->estado }}
                    </span>
                @endif
            </form>
        </div>
    </div>

    {{-- GRUPOS CLASIFICADOS EN DOS PESTAÑAS: PRESENCIAL Y VIRTUAL --}}
    <div class="card card-primary card-outline card-outline-tabs shadow-sm">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="tabModalidades" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold" id="tab-presencial" data-toggle="pill" href="#content-presencial" role="tab">
                        <i class="fas fa-school text-primary mr-1"></i> MODALIDAD PRESENCIAL
                        <span class="badge badge-primary ml-1">{{ $paralelos->where('modalidad', 'PRESENCIAL')->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="tab-virtual" data-toggle="pill" href="#content-virtual" role="tab">
                        <i class="fas fa-laptop text-success mr-1"></i> MODALIDAD VIRTUAL
                        <span class="badge badge-success ml-1">{{ $paralelos->where('modalidad', 'VIRTUAL')->count() }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="tabModalidadesContent">
                {{-- TABLA PRESENCIAL --}}
                <div class="tab-pane fade show active" id="content-presencial" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm datatable-paralelos">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 50px;">Grupo</th>
                                    <th>Idioma & Módulo</th>
                                    <th>Aula Física en Campus</th>
                                    <th>Turno & Horario</th>
                                    <th style="width: 180px;">Disponibilidad de Cupos</th>
                                    <th style="width: 100px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paralelos->where('modalidad', 'PRESENCIAL') as $p)
                                    @php
                                        $porcentaje = $p->cupo_maximo > 0 ? round(($p->cupo_ocupado / $p->cupo_maximo) * 100) : 0;
                                        $barColor = $porcentaje >= 95 ? 'bg-danger' : ($porcentaje >= 70 ? 'bg-warning' : 'bg-success');
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold">
                                            <span class="badge badge-primary p-2" style="font-size: 1rem;">{{ $p->nombre }}</span>
                                        </td>
                                        <td>
                                            <b>{{ $p->modulo->idioma->nombre ?? 'N/A' }}</b> - {{ $p->modulo->nombre ?? 'Módulo' }}
                                            <small class="d-block text-muted">Nivel: {{ $p->modulo->nivel ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                            <b>{{ $p->aula_o_enlace }}</b>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light border">{{ $p->turno }}</span>
                                            @if ($p->hora_inicio)
                                                <small class="d-block text-muted">{{ \Carbon\Carbon::parse($p->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($p->hora_fin)->format('H:i') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between mb-1">
                                                <small><b>{{ $p->cupo_ocupado }}</b> / {{ $p->cupo_maximo }} ocupados</small>
                                                <small class="font-weight-bold">{{ $porcentaje }}%</small>
                                            </div>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar {{ $barColor }}" style="width: {{ $porcentaje }}%"></div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    onclick="cargarEdicion('{{ $p->id }}', '{{ $p->ciclo_id }}', '{{ $p->modulo_id }}', '{{ $p->nombre }}', '{{ $p->modalidad }}', '{{ addslashes($p->aula_o_enlace) }}', '{{ $p->turno }}', '{{ $p->hora_inicio }}', '{{ $p->hora_fin }}', '{{ $p->cupo_maximo }}')"
                                                    title="Editar Grupo">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmarEliminar('{{ $p->id }}', '{{ $p->nombre }}')"
                                                    title="Eliminar Grupo">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="form-delete-{{ $p->id }}" action="{{ route('admin.paralelos.destroy', $p->id) }}" method="POST" style="display: none;">
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

                {{-- TABLA VIRTUAL --}}
                <div class="tab-pane fade" id="content-virtual" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm datatable-paralelos">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th style="width: 50px;">Grupo</th>
                                    <th>Idioma & Módulo</th>
                                    <th>Enlace de Conexión Virtual</th>
                                    <th>Turno & Horario</th>
                                    <th style="width: 180px;">Disponibilidad de Cupos</th>
                                    <th style="width: 100px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paralelos->where('modalidad', 'VIRTUAL') as $p)
                                    @php
                                        $porcentaje = $p->cupo_maximo > 0 ? round(($p->cupo_ocupado / $p->cupo_maximo) * 100) : 0;
                                        $barColor = $porcentaje >= 95 ? 'bg-danger' : ($porcentaje >= 70 ? 'bg-warning' : 'bg-success');
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold">
                                            <span class="badge badge-success p-2" style="font-size: 1rem;">{{ $p->nombre }}</span>
                                        </td>
                                        <td>
                                            <b>{{ $p->modulo->idioma->nombre ?? 'N/A' }}</b> - {{ $p->modulo->nombre ?? 'Módulo' }}
                                            <small class="d-block text-muted">Nivel: {{ $p->modulo->nivel ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ $p->aula_o_enlace }}" target="_blank" class="btn btn-xs btn-outline-success">
                                                <i class="fas fa-video mr-1"></i> Abrir Sala Virtual
                                            </a>
                                            <small class="d-block text-muted text-truncate" style="max-width: 250px;">{{ $p->aula_o_enlace }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-light border">{{ $p->turno }}</span>
                                            @if ($p->hora_inicio)
                                                <small class="d-block text-muted">{{ \Carbon\Carbon::parse($p->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($p->hora_fin)->format('H:i') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between mb-1">
                                                <small><b>{{ $p->cupo_ocupado }}</b> / {{ $p->cupo_maximo }} ocupados</small>
                                                <small class="font-weight-bold">{{ $porcentaje }}%</small>
                                            </div>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar {{ $barColor }}" style="width: {{ $porcentaje }}%"></div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    onclick="cargarEdicion('{{ $p->id }}', '{{ $p->ciclo_id }}', '{{ $p->modulo_id }}', '{{ $p->nombre }}', '{{ $p->modalidad }}', '{{ addslashes($p->aula_o_enlace) }}', '{{ $p->turno }}', '{{ $p->hora_inicio }}', '{{ $p->hora_fin }}', '{{ $p->cupo_maximo }}')"
                                                    title="Editar Grupo">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="confirmarEliminar('{{ $p->id }}', '{{ $p->nombre }}')"
                                                    title="Eliminar Grupo">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form id="form-delete-{{ $p->id }}" action="{{ route('admin.paralelos.destroy', $p->id) }}" method="POST" style="display: none;">
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

    {{-- MODAL CREAR PARALELO --}}
    <div class="modal fade" id="modal-crear-paralelo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Apertura de Nuevo Grupo / Paralelo</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.paralelos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ciclo Bimestral <span class="text-danger">(*)</span></label>
                                    <select name="ciclo_id" class="form-control" required>
                                        @foreach ($ciclos as $c)
                                            <option value="{{ $c->id }}" {{ $c->id == $cicloSeleccionadoId ? 'selected' : '' }}>
                                                {{ $c->nombre }} ({{ $c->gestion }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Idioma y Módulo <span class="text-danger">(*)</span></label>
                                    <select name="modulo_id" class="form-control select2bs4" style="width: 100%;" required>
                                        <option value="" disabled selected>-- Seleccione un Módulo --</option>
                                        @foreach ($modulos as $m)
                                            <option value="{{ $m->id }}">
                                                {{ $m->idioma->nombre ?? 'N/A' }} - {{ $m->nombre }} (#{{ $m->numero_orden }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Identificador del Grupo <span class="text-danger">(*)</span></label>
                                    <input type="text" name="nombre" class="form-control text-uppercase font-weight-bold" placeholder="Ej: A, B, C, P1" maxlength="10" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Modalidad de Cursado <span class="text-danger">(*)</span></label>
                                    <select name="modalidad" id="create_modalidad" class="form-control font-weight-bold" onchange="actualizarPlaceholderModalidad(this.value, 'create')" required>
                                        <option value="PRESENCIAL" selected>🏫 PRESENCIAL (Aula en Campus)</option>
                                        <option value="VIRTUAL">💻 VIRTUAL (Meet / Zoom)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Turno <span class="text-danger">(*)</span></label>
                                    <select name="turno" class="form-control" required>
                                        <option value="MAÑANA" selected>MAÑANA</option>
                                        <option value="TARDE">TARDE</option>
                                        <option value="NOCHE">NOCHE</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label id="lbl_aula_create">Pabellón y Aula Física Asignada <span class="text-danger">(*)</span></label>
                            <input type="text" name="aula_o_enlace" id="input_aula_create" class="form-control" placeholder="Ej: Pabellón A - Aula 204" required>
                            <small id="help_aula_create" class="text-muted">Indique el edificio y número de aula física para los estudiantes presenciales.</small>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hora Inicio</label>
                                    <input type="time" name="hora_inicio" class="form-control" value="08:00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hora Fin</label>
                                    <input type="time" name="hora_fin" class="form-control" value="10:00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cupo Máximo de Alumnos <span class="text-danger">(*)</span></label>
                                    <input type="number" name="cupo_maximo" class="form-control text-center font-weight-bold" value="30" min="1" max="150" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Grupo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDITAR PARALELO --}}
    <div class="modal fade" id="modal-editar-paralelo" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Modificar Grupo / Paralelo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-edit-paralelo" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ciclo Bimestral</label>
                                    <select name="ciclo_id" id="edit_ciclo_id" class="form-control" required>
                                        @foreach ($ciclos as $c)
                                            <option value="{{ $c->id }}">{{ $c->nombre }} ({{ $c->gestion }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Módulo</label>
                                    <select name="modulo_id" id="edit_modulo_id" class="form-control" required>
                                        @foreach ($modulos as $m)
                                            <option value="{{ $m->id }}">{{ $m->idioma->nombre ?? 'N/A' }} - {{ $m->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Grupo</label>
                                    <input type="text" name="nombre" id="edit_nombre" class="form-control text-uppercase font-weight-bold" maxlength="10" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Modalidad</label>
                                    <select name="modalidad" id="edit_modalidad" class="form-control font-weight-bold" onchange="actualizarPlaceholderModalidad(this.value, 'edit')" required>
                                        <option value="PRESENCIAL">🏫 PRESENCIAL</option>
                                        <option value="VIRTUAL">💻 VIRTUAL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Turno</label>
                                    <select name="turno" id="edit_turno" class="form-control" required>
                                        <option value="MAÑANA">MAÑANA</option>
                                        <option value="TARDE">TARDE</option>
                                        <option value="NOCHE">NOCHE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="lbl_aula_edit">Aula Física o Enlace Virtual</label>
                            <input type="text" name="aula_o_enlace" id="edit_aula_o_enlace" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hora Inicio</label>
                                    <input type="time" name="hora_inicio" id="edit_hora_inicio" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Hora Fin</label>
                                    <input type="time" name="hora_fin" id="edit_hora_fin" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cupo Máximo</label>
                                    <input type="number" name="cupo_maximo" id="edit_cupo_maximo" class="form-control text-center font-weight-bold" min="1" max="150" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Actualizar Grupo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL GESTIONAR CICLOS BIMESTRALES --}}
    <div class="modal fade" id="modal-gestionar-ciclos" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-calendar-alt"></i> Gestión de Ciclos Bimestrales</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Formulario para nuevo ciclo rápido --}}
                    <div class="card card-outline card-secondary mb-3">
                        <div class="card-header py-2">
                            <h6 class="card-title font-weight-bold mb-0">Registrar Nuevo Ciclo</h6>
                        </div>
                        <div class="card-body py-2">
                            <form action="{{ route('admin.ciclos.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Ej: Bimestre I - 2026 (Ene-Feb)" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="gestion" class="form-control form-control-sm text-center" value="2026" required>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="estado" class="form-control form-control-sm" required>
                                            <option value="FUTURO">FUTURO</option>
                                            <option value="ACTIVO">ACTIVO (Vigente)</option>
                                            <option value="CERRADO">CERRADO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block"><i class="fas fa-plus"></i> Crear</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Lista de ciclos existentes --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm text-center">
                            <thead class="bg-light">
                                <tr>
                                    <th>Ciclo Bimestral</th>
                                    <th>Gestión</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ciclos as $c)
                                    <tr>
                                        <td class="text-left font-weight-bold">{{ $c->nombre }}</td>
                                        <td>{{ $c->gestion }}</td>
                                        <td>
                                            <span class="badge {{ $c->estado === 'ACTIVO' ? 'badge-success' : ($c->estado === 'FUTURO' ? 'badge-info' : 'badge-secondary') }} p-1">
                                                {{ $c->estado }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($c->estado !== 'ACTIVO')
                                                <form action="{{ route('admin.ciclos.activar', $c->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-outline-success" title="Activar este ciclo como vigente">
                                                        <i class="fas fa-check-circle"></i> Activar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-success font-weight-bold text-xs"><i class="fas fa-check"></i> Activo Actual</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.datatable-paralelos').DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay grupos registrados en esta modalidad",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ grupos",
                    "infoEmpty": "Mostrando 0 a 0 de 0 grupos",
                    "search": "Buscar:",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "responsive": true,
                "autoWidth": false
            });
        });

        function actualizarPlaceholderModalidad(modalidad, prefijo) {
            if (modalidad === 'PRESENCIAL') {
                $('#lbl_aula_' + prefijo).text('Pabellón y Aula Física Asignada (*)');
                $('#input_aula_' + prefijo).attr('placeholder', 'Ej: Pabellón A - Aula 204');
                $('#help_aula_' + prefijo).text('Indique el edificio y número de aula física para los estudiantes presenciales.');
            } else {
                $('#lbl_aula_' + prefijo).text('Enlace de Sala Virtual Meet / Zoom (*)');
                $('#input_aula_' + prefijo).attr('placeholder', 'Ej: https://meet.google.com/abc-defg-hij');
                $('#help_aula_' + prefijo).text('Pegue el enlace oficial de la sala de videoconferencias.');
            }
        }

        function cargarEdicion(id, ciclo_id, modulo_id, nombre, modalidad, aula_o_enlace, turno, hora_inicio, hora_fin, cupo_maximo) {
            $('#form-edit-paralelo').attr('action', "{{ url('/admin/paralelos') }}/" + id);
            $('#edit_ciclo_id').val(ciclo_id);
            $('#edit_modulo_id').val(modulo_id);
            $('#edit_nombre').val(nombre);
            $('#edit_modalidad').val(modalidad);
            $('#edit_aula_o_enlace').val(aula_o_enlace);
            $('#edit_turno').val(turno);
            $('#edit_hora_inicio').val(hora_inicio);
            $('#edit_hora_fin').val(hora_fin);
            $('#edit_cupo_maximo').val(cupo_maximo);
            actualizarPlaceholderModalidad(modalidad, 'edit');
            $('#modal-editar-paralelo').modal('show');
        }

        function confirmarEliminar(id, nombre) {
            Swal.fire({
                title: '¿Eliminar grupo?',
                text: "Se eliminará el grupo " + nombre + ". Si ya tiene alumnos inscritos la acción será bloqueada.",
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
