@extends('adminlte::page')

@section('content_header')
    <h1>Datos del Estudiante</h1>
    <hr>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Datos del padre de familia</h3>


                </div>
                <div class="card-body" id="datos_ppff">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Nombres</label>
                                <p id="nombres"> {{ $estudiante->ppff->nombres }} </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Apellidos</label>
                                <p id="apellidos">{{ $estudiante->ppff->apellidos }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Carnet de identidad</label>
                                <p id="ci">{{ $estudiante->ppff->ci }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fecha de nacimiento</label>
                                <p id="fecha_nacimiento">{{ $estudiante->ppff->fecha_nacimiento }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Teléfono</label>
                                <p id="telefono">{{ $estudiante->ppff->telefono }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Parentesco</label>
                                <p id="parentesco">{{ $estudiante->ppff->parentesco }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Ocupación</label>
                                <p id="ocupacion">{{ $estudiante->ppff->ocupacion }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Dirección</label>
                                <p id="direccion">{{ $estudiante->ppff->direccion }}</p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Datos del formulario</h3>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fotografía</label>

                                
                                <center>
                                    <img id="preview" src="{{ url($estudiante->foto) }}"
                                        style="max-width: 200px; margin-top: 10px;">
                                </center>
                                @error('foto')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                            </div>

                            <script>
                                const mostrarImagen = e => {
                                    document.getElementById('preview').src = URL.createObjectURL(e.target.files[0]);
                                };
                            </script>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Nombre del rol</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                            </div>
                                            <select name="rol" id="" class="form-control" disabled>
                                                <option value="">
                                                    {{ $estudiante->usuario->roles->pluck('name')->implode(', ') }}
                                                </option>
                                            </select>
                                        </div>
                                        @error('rol')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nombres">Nombres</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="nombres" id="nombres"
                                                value="{{ old('nombres', $estudiante->nombres) }}"
                                                placeholder="Ingrese nombres..." disabled>
                                        </div>
                                        @error('nombres')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="apellidos">Apellidos</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user-friends"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="apellidos" id="apellidos"
                                                value="{{ old('apellidos', $estudiante->apellidos) }}"
                                                placeholder="Ingrese apellidos..." disabled>
                                        </div>
                                        @error('apellidos')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ci">Cédula de Identidad</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-id-card"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="ci" id="ci"
                                                value="{{ old('ci', $estudiante->ci) }}" placeholder="Ingrese CI..."
                                                disabled>
                                        </div>
                                        @error('ci')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fecha_nacimiento">Fecha Nacimiento</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="date" class="form-control" name="fecha_nacimiento"
                                                id="fecha_nacimiento"
                                                value="{{ old('fecha_nacimiento', $estudiante->fecha_nacimiento) }}"
                                                disabled>
                                        </div>
                                        @error('fecha_nacimiento')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="direccion" id="direccion"
                                                value="{{ old('direccion', $estudiante->direccion) }}" disabled>
                                        </div>
                                        @error('direccion')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" name="telefono" id="telefono"
                                                value="{{ old('telefono', $estudiante->telefono) }}" disabled>
                                        </div>
                                        @error('telefono')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="genero">Genero</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user-alt"></i>
                                                </span>
                                            </div>
                                            <select name="genero" id="" class="form-control" disabled>
                                                <option value="">{{ $estudiante->genero }} </option>
                                            </select>
                                        </div>
                                        @error('genero')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label><b> (*)</b>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                            </div>
                                            <input type="email" class="form-control" name="email" id="email"
                                                value="{{ old('email', $estudiante->usuario->email) }}"
                                                placeholder="Ingrese su email..." disabled>
                                        </div>
                                        @error('email')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ url('/admin/estudiantes/') }}" class="btn btn-light">
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
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    <style>
        /* Fondo transparente y sin borde en el contenedor */
        #example1_wrapper .dt-buttons {
            background-color: transparent;
            box-shadow: none;
            border: none;
            display: flex;
            justify-content: center;
            /* Centrar los botones */
            gap: 10px;
            /* Espaciado entre botones */
            margin-bottom: 15px;
            /* Separar botones de la tabla */
        }

        /* Estilo personalizado para los botones */
        #example1_wrapper .btn {
            color: #fff;
            /* Color del texto en blanco */
            border-radius: 4px;
            /* Bordes redondeados */
            padding: 5px 15px;
            /* Espaciado interno */
            font-size: 14px;
            /* Tamaño de fuente */
        }

        /* Colores por tipo de botón */
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
            border: none;
        }

        .btn-default {
            background-color: #6e7176;
            color: #212529;
            border: none;
        }
    </style>
@stop
{{--  hola --}}
@section('js')
@stop
