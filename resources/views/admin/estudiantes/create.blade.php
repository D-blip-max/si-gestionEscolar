@extends('adminlte::page')

@section('title', 'Nuevo Estudiante')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user-plus text-primary mr-2"></i>Registrar Nuevo Estudiante</h1>
                <small class="text-muted">Padrón de Estudiantes Universitarios y Adultos Autónomos</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.estudiantes.index') }}">Estudiantes</a></li>
                    <li class="breadcrumb-item active">Nuevo</li>
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

    <form action="{{ route('admin.estudiantes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header bg-white">
                <h3 class="card-title text-bold"><i class="fas fa-id-card mr-1"></i> Ficha de Registro de Estudiante</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <!-- Datos Personales -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nombres">Nombres: <b class="text-danger">*</b></label>
                            <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres') }}" required placeholder="Ej: Juan Carlos">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="apellidos">Apellidos: <b class="text-danger">*</b></label>
                            <input type="text" name="apellidos" id="apellidos" class="form-control" value="{{ old('apellidos') }}" required placeholder="Ej: Pérez Rodríguez">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ci">Cédula de Identidad (CI): <b class="text-danger">*</b></label>
                            <input type="text" name="ci" id="ci" class="form-control" value="{{ old('ci') }}" required placeholder="Ej: 8492019">
                            <small class="text-muted">Servirá como clave de acceso inicial para su portal.</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Correo Electrónico: <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="estudiante@gmail.com">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="telefono">Teléfono / WhatsApp: <b class="text-danger">*</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-whatsapp"></i></span></div>
                                <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}" required placeholder="Ej: 71234567">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="carrera_facultad">Carrera / Facultad de Origen:</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-university"></i></span></div>
                                <input type="text" name="carrera_facultad" id="carrera_facultad" class="form-control" value="{{ old('carrera_facultad') }}" placeholder="Ej: Ing. Financiera / Particular">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de Nacimiento: <b class="text-danger">*</b></label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="genero">Género: <b class="text-danger">*</b></label>
                            <select name="genero" id="genero" class="form-control" required>
                                <option value="">-- Seleccionar --</option>
                                <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="direccion">Dirección / Residencia:</label>
                            <input type="text" name="direccion" id="direccion" class="form-control" value="{{ old('direccion') }}" placeholder="Zona / Calle">
                        </div>
                    </div>
                </div>

                <div class="row align-items-center mt-2">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="foto">Fotografía del Estudiante (Opcional):</label>
                            <div class="custom-file">
                                <input type="file" name="foto" class="custom-file-input" id="foto" accept="image/*" onchange="previsualizarFoto(event)">
                                <label class="custom-file-label" for="foto">Seleccionar imagen...</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <img id="preview" src="{{ asset('vendor/adminlte/dist/img/avatar.png') }}" class="img-thumbnail rounded-circle shadow-sm" style="width: 90px; height: 90px; object-fit: cover;" alt="Vista previa">
                    </div>
                </div>

                <div class="callout callout-info mt-3 bg-light">
                    <h5><i class="fas fa-info-circle text-info"></i> Generación Automática de Credenciales</h5>
                    <p class="mb-0 text-sm">
                        Al guardar, el sistema generará automáticamente su <strong>Código Institucional CEI</strong> correlativo (Ej: <code>CEI-{{ date('Y') }}-0001</code>) y una cuenta de acceso con el rol <code>ESTUDIANTE</code> donde su usuario será su correo electrónico y su contraseña inicial su número de CI.
                    </p>
                </div>
            </div>

            <div class="card-footer bg-white text-right">
                <a href="{{ route('admin.estudiantes.index') }}" class="btn btn-secondary mr-2">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary font-weight-bold">
                    <i class="fas fa-save mr-1"></i> Registrar Estudiante
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
