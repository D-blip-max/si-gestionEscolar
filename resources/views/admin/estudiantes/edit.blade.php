@extends('adminlte::page')

@section('title', 'Editar Estudiante')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user-edit text-warning mr-2"></i>Modificar Datos del Estudiante</h1>
                <small class="text-muted">Actualización de Padrón Estudiantil</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.estudiantes.index') }}">Estudiantes</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Errores de Validación:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.estudiantes.update', $estudiante->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title text-bold"><i class="fas fa-user mr-1"></i> Ficha del Estudiante: {{ $estudiante->apellidos }} {{ $estudiante->nombres }}</h3>
                <div class="card-tools">
                    <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ $estudiante->codigo_estudiante ?? 'CEI-'.$estudiante->id }}</span>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombres">Nombres: <b class="text-danger">*</b></label>
                            <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres', $estudiante->nombres) }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="apellidos">Apellidos: <b class="text-danger">*</b></label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" value="{{ old('apellidos', $estudiante->apellidos) }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ci">Cédula de Identidad (CI): <b class="text-danger">*</b></label>
                            <input type="text" name="ci" id="ci" class="form-control" value="{{ old('ci', $estudiante->ci) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Correo Electrónico (Acceso): <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', optional($estudiante->usuario)->email) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="password">Nueva Contraseña (Opcional):</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Dejar en blanco para conservar la actual">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="telefono">Teléfono / WhatsApp: <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-whatsapp"></i></span></div>
                                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono', $estudiante->telefono) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="carrera_facultad">Carrera / Facultad de Origen:</label>
                            <input type="text" name="carrera_facultad" id="carrera_facultad" class="form-control" value="{{ old('carrera_facultad', $estudiante->carrera_facultad) }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de Nacimiento: <b class="text-danger">*</b></label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $estudiante->fecha_nacimiento) }}" required>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="genero">Género: <b class="text-danger">*</b></label>
                            <select name="genero" id="genero" class="form-control" required>
                                <option value="masculino" {{ old('genero', $estudiante->genero) == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="femenino" {{ old('genero', $estudiante->genero) == 'femenino' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="estado">Estado: <b class="text-danger">*</b></label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="activo" {{ old('estado', $estudiante->estado) == 'activo' ? 'selected' : '' }}>ACTIVO</option>
                                <option value="inactivo" {{ old('estado', $estudiante->estado) == 'inactivo' ? 'selected' : '' }}>INACTIVO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="direccion">Dirección / Residencia:</label>
                            <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion', $estudiante->direccion) }}">
                        </div>

                        <div class="form-group">
                            <label for="foto">Cambiar Fotografía:</label>
                            <div class="custom-file">
                                <input type="file" name="foto" class="custom-file-input" id="foto" accept="image/*" onchange="previsualizarFoto(event)">
                                <label class="custom-file-label" for="foto">Seleccionar nueva imagen...</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        @php
                            $foto = ($estudiante->foto && file_exists(public_path($estudiante->foto)))
                                ? asset($estudiante->foto)
                                : asset('vendor/adminlte/dist/img/avatar.png');
                        @endphp
                        <label>Foto Actual / Previa:</label><br>
                        <img id="preview" src="{{ $foto }}" class="img-thumbnail rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover;" alt="Foto">
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white text-right">
                <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-warning font-weight-bold">
                    <i class="fas fa-save mr-1"></i> Actualizar Estudiante
                </button>
            </div>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    function previsualizarFoto(event) {
        var input = event.target;
        var fileName = input.files[0] ? input.files[0].name : "Seleccionar imagen...";
        $(input).next('.custom-file-label').html(fileName);

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@stop
