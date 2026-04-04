@extends('adminlte::page')

@section('content_header')
    <h1>Datos de la Matriculacion del Estudiante</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Datos del Estudiante</h3>
                        </div>

                        <div class="card-body">

                            <div id="datos_estudiante">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="">Fotografía</label>
                                                    <center>

                                                        <img src="{{ url($matricula->estudiante->foto) }}" width="70%"
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
                                                    <p id="apellidos">{{ $matricula->estudiante->apellidos }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Nombres</label>
                                                    <p id="nombres">{{ $matricula->estudiante->nombres }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Carnet de identidad</label>
                                                    <p id="ci">{{ $matricula->estudiante->ci }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Fecha de nacimiento</label>
                                                    <p id="fecha_nacimiento">{{ $matricula->estudiante->fecha_nacimiento }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Teléfono</label>
                                                    <p id="telefono">{{ $matricula->estudiante->telefono }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Dirección</label>
                                                    <p id="direccion">{{ $matricula->estudiante->direccion }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Correo electrónico</label>
                                                    <p id="email">{{ $matricula->estudiante->usuario->email }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Género</label>
                                                    <p id="genero">{{ $matricula->estudiante->genero }}</p>
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
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Historial Academico</h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Turno</th>
                                        <th>Gestión</th>
                                        <th>Nivel</th>
                                        <th>Grado</th>
                                        <th>Paralelo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($matricula->estudiante->matriculaciones as $datos)
                                        <tr>
                                            <td>{{ $datos->turno->nombre }}</td>
                                            <td>{{ $datos->gestion->nombre }}</td>
                                            <td>{{ $datos->nivel->nombre }}</td>
                                            <td>{{ $datos->grado->nombre }}</td>
                                            <td>{{ $datos->paralelo->nombre }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>


        </div>


        <div class="col-md-4">

            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del formulario</h3>
                </div>

                <div class="card-body">

                        <input type="text" name="estudiante_id" id="estudiante_id" hidden required>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Turnos</label><b> (*)</b>
                                    <p>
                                        {{ $matricula->turno->nombre }}
                                    </p>
                                    @error('turno_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Gestiones</label><b> (*)</b>
                                    <p>
                                        {{ $matricula->gestion->nombre }}
                                    </p>
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
                                    <p>
                                        {{ $matricula->nivel->nombre }}
                                    </p>
                                    @error('nivel_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Fecha</label><b> (*)</b>
                                    <p>
                                        {{ $matricula->fecha_matriculacion }}
                                    </p>
                                    @error('paralelo_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Grados</label><b> (*)</b>
                                    <p>
                                        {{ $matricula->grado->nombre }}
                                    </p>
                                    @error('grado_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Paralelos</label><b> (*)</b>
                                    <p>
                                        {{ $matricula->paralelo->nombre }}
                                    </p>
                                    @error('paralelo_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{ url('/admin/matriculaciones') }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Volver
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                 



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

        $('#buscar_estudiante').on('change', function() {
            var id = $(this).val();

            if (id) {
                $.ajax({
                    url: "{{ url('/admin/matriculaciones/buscar_estudiante/') }}" + '/' + id,
                    type: 'GET',
                    success: function(estudiante) {
                        $('#apellidos').html(estudiante.apellidos);
                        $('#nombres').html(estudiante.nombres);
                        $('#ci').html(estudiante.ci);
                        $('#fecha_nacimiento').html(estudiante.fecha_nacimiento);
                        $('#telefono').html(estudiante.telefono);
                        $('#direccion').html(estudiante.direccion);
                        $('#email').html(estudiante.usuario.email);
                        $('#genero').html(estudiante.genero);
                        $('#foto').attr('src', estudiante.foto_url).show();
                        $('#estudiante_id').val(estudiante.id);
                        $('#datos_estudiante').css('display', 'block');
                        if (estudiante.matriculaciones && estudiante.matriculaciones.length > 0) {
                            var tabla = '<table class="table table-bordered">';
                            tabla +=
                                '<thead><tr><th>Turno</th><th>Gestión</th><th>Nivel</th><th>Grado</th><th>Paralelo</th></tr></thead>';
                            tabla += '<tbody>';
                            estudiante.matriculaciones.forEach(function(matriculacion) {
                                tabla += '<tr>';
                                tabla += '<td>' + matriculacion.turno.nombre + '</td>';
                                tabla += '<td>' + matriculacion.gestion.nombre + '</td>';
                                tabla += '<td>' + matriculacion.nivel.nombre + '</td>';
                                tabla += '<td>' + matriculacion.grado.nombre + '</td>';
                                tabla += '<td>' + matriculacion.paralelo.nombre + '</td>';
                                tabla += '</tr>';
                            });
                            $('#tabla_historial').html(tabla).show();
                        } else {
                            $('#tabla_historial').html(
                                '<p>No hay historial académico registrado del estudiante.</p>');
                        }
                    },
                    error: function() {
                        alert('No se puede obtener información del estudiante');
                    }
                });
            } else {

            }
        });
    </script>
@stop
