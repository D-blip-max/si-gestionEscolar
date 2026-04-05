@extends('adminlte::page')

@section('content_header')
    <h1>Editar una Asignacion</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Datos del Docente</h3>
                        </div>

                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Buscar docente: <b>(*)</b></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                            </div>
                                            <select name="" id="buscar_docente" class="form-control select2">
                                                <option value="">Selecciona un docente...</option>
                                                @foreach ($docentes as $docente)
                                                    <option value="{{ $docente->id }}"
                                                        {{ $docente->id == $asignacion->personal_id ? 'selected' : '' }}>
                                                        {{ $docente->apellidos . ' ' . $docente->nombres . ' - ' . $docente->ci }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('nombre')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id="datos_docente">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Fotografía</label>
                                                    <center>

                                                        <img src="{{ url($asignacion->personal->foto) }}" width="70%"
                                                            id="foto" alt="">
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Apellidos</label>
                                                    <p id="apellidos">{{ $asignacion->personal->apellidos }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nombres</label>
                                                    <p id="nombres">{{ $asignacion->personal->nombres }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Carnet de identidad</label>
                                                    <p id="ci">{{ $asignacion->personal->ci }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Fecha de nacimiento</label>
                                                    <p id="fecha_nacimiento">{{ $asignacion->personal->fecha_nacimiento }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Teléfono</label>
                                                    <p id="telefono">{{ $asignacion->personal->telefono }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Dirección</label>
                                                    <p id="direccion">{{ $asignacion->personal->direccion }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Correo electrónico</label>
                                                    <p id="email">{{ $asignacion->personal->usuario->email }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Profesion</label>
                                                    <p id="profesion">{{ $asignacion->personal->profesion }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Formacion Academica</h3>
                        </div>

                        <div class="card-body">
                            <div id="tabla_formacion">
                                <div id="tabla_bd">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Título</th>
                                                <th>Institución</th>
                                                <th>Nivel</th>
                                                <th>Fecha de graduación</th>
                                                <th>Archivo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($asignacion->personal->formaciones as $formacion)
                                                <tr>
                                                    <td>{{ $formacion->titulo }}</td>
                                                    <td>{{ $formacion->institucion }}</td>
                                                    <td>{{ $formacion->nivel }}</td>
                                                    <td>{{ $formacion->fecha_graduacion }}</td>
                                                    <td><a href="{{ url($formacion->archivo) }}" target="_blank">Ver
                                                            archivo</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>
            </div>


        </div>


        <div class="col-md-4">

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del formulario</h3>
                </div>

                <div class="card-body">

                    <form action="{{ url('/admin/asignaciones/'.$asignacion->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <input type="text" name="personal_id" value={{ $asignacion->personal_id }} id="docente_id" hidden required>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Turnos</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                        </div>
                                        <select name="turno_id" id="" class="form-control" required>
                                            <option value="">Seleccione un turno...</option>
                                            @foreach ($turnos as $turno)
                                                <option value="{{ $turno->id }}" {{ $turno->id == $asignacion->turno_id ? 'selected':'' }}>{{ $turno->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('turno_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Gestiones</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-university"></i></span>
                                        </div>
                                        <select name="gestion_id" id="" class="form-control" required>
                                            <option value="">Seleccione una gestión...</option>
                                            @foreach ($gestiones as $gestione)
                                                <option value="{{ $gestione->id }}" {{ $gestione->id == $asignacion->gestion_id ? 'selected':'' }}>{{ $gestione->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('gestion_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Niveles</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                        </div>
                                        <select name="nivel_id" id="niveles" class="form-control" required>
                                            <option value="">Seleccione un nivel...</option>
                                            @foreach ($niveles as $nivele)
                                                <option value="{{ $nivele->id }}" {{ $nivele->id == $asignacion->nivel_id ? 'selected':'' }}>{{ $nivele->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('nivel_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Fecha</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="date" class="form-control" value="{{ $asignacion->fecha_asignacion }}" name="fecha_asignacion" required>
                                    </div>
                                    @error('fecha_asignacion')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Grados</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                        </div>
                                        <select name="grado_id" id="grados" class="form-control" required>
                                            <option value="">Primero Seleccione un Nivel...</option>
                                            @foreach ($grados as $grado)
                                                <option value="{{ $grado->id }}" {{ $grado->id == $asignacion->grado_id ? 'selected':'' }}>{{ $grado->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('grado_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Paralelos</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-clone"></i></span>
                                        </div>
                                        <select name="paralelo_id" id="paralelos" class="form-control" required>
                                            <option value="">Primero Seleccione un grado...</option>
                                            @foreach ($paralelos as $paralelo)
                                                <option value="{{ $paralelo->id }}" {{ $paralelo->id == $asignacion->paralelo_id ? 'selected':'' }}>{{ $paralelo->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('paralelo_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="class="form-group">
                                    <label for="">Materia a impartir</label><b> (*)</b>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-book"></i></span>
                                        </div>
                                        <select name="materia_id" class="form-control" required>
                                            <option value="">Seleccione una materia...</option>
                                            @foreach ($materias as $materia)
                                                <option value="{{ $materia->id }}" {{ $materia->id == $asignacion->materia_id ? 'selected':'' }}>{{ $materia->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('materia_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{ url('/admin/asignaciones') }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>



                </div>
            </div>
        </div>
</div> @stop
@section('css')
    <style>
        .select2-container .select2-selection--single {
            height: 40px !important;
        }
    </style>
@stop

@section('js')
    <script>
        $('.select2').select2({});

        $('#niveles').on('change', function() {
            var id = $(this).val();
            if (id) {
                $.ajax({
                    url: "{{ url('/admin/matriculaciones/buscar_grado/') }}" + '/' + id,
                    type: 'GET',
                    success: function(grados) {
                        var options = '<option value="">Seleccione un grado...</option>';
                        $.each(grados, function(key, value) {
                            options += '<option value="' + key + '">' + value + '</option>';
                        });
                        $('#grados').html(options);
                    },
                    error: function() {
                        alert('No se puede obtener información del nivel');
                    }
                });
            } else {
                alert('Seleccione un nivel...');
            }
        });

        $('#grados').on('change', function() {
            var id = $(this).val();
            if (id) {
                $.ajax({
                    url: "{{ url('/admin/matriculaciones/buscar_paralelo/') }}" + '/' + id,
                    type: 'GET',
                    success: function(paralelos) {
                        var options = '<option value="">Seleccione un paralelo...</option>';
                        $.each(paralelos, function(key, value) {
                            options += '<option value="' + key + '">' + value + '</option>';
                        });
                        $('#paralelos').html(options);
                    },
                    error: function() {
                        alert('No se puede obtener información del grado');
                    }
                });
            } else {
                alert('Seleccione un nivel...');
            }
        });

        $('#buscar_docente').on('change', function() {
            var id = $(this).val();

            if (id) {
                $.ajax({
                    url: "{{ url('/admin/asignaciones/buscar_docente/') }}" + '/' + id,
                    type: 'GET',
                    success: function(docente) {
                        $('#apellidos').html(docente.apellidos);
                        $('#nombres').html(docente.nombres);
                        $('#ci').html(docente.ci);
                        $('#fecha_nacimiento').html(docente.fecha_nacimiento);
                        $('#telefono').html(docente.telefono);
                        $('#direccion').html(docente.direccion);
                        $('#email').html(docente.usuario.email);
                        $('#profesion').html(docente.profesion);
                        $('#foto').attr('src', docente.foto_url).show();
                        $('#docente_id').val(docente.id);
                        $('#datos_docente').css('display', 'block');
                        var baseUrl = "{{ url('/') }}";
                        if (docente.formaciones && docente.formaciones.length > 0) {
                            var tabla = '<table class="table table-bordered">';
                            tabla +=
                                '<thead><tr><th>Título</th><th>Institución</th><th>Nivel</th><th>Fecha de graduación</th><th>Archivo</th></tr></thead>';
                            tabla += '<tbody>';
                            docente.formaciones.forEach(function(formacion) {
                                tabla += '<tr>';
                                tabla += '<td>' + formacion.titulo + '</td>';
                                tabla += '<td>' + formacion.institucion + '</td>';
                                tabla += '<td>' + formacion.nivel + '</td>';
                                tabla += '<td>' + formacion.fecha_graduacion + '</td>';
                                tabla += '<td><a href="' + baseUrl + '/' + formacion.archivo +
                                    '" target="_blank">Ver archivo</a></td>';
                                tabla += '</tr>';
                            });
                            $('#tabla_formacion').html(tabla).show();
                            $('#tabla_bd').css('display','none');
                        } else {
                            $('#tabla_formacion').html(
                                
                                '<p>No hay formación académica registrado del docente.</p>');
                                
                                $('#tabla_bd').css('display','none');
                        }

                    },
                    error: function() {
                        alert('No se puede obtener información del docente');
                    }
                });
            } else {

            }
        });
    </script>
@stop
