@extends('adminlte::page')

@section('content_header')
    <h1>Editar Formacion</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del formulario</h3>
                </div>

                <div class="card-body">
                    <form action="{{ url('/admin/personal/formaciones/'.$formacion->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Archivo</label><b> (*)</b>

                                            <input type="file" class="form-control" name="archivo"
                                                placeholder="Escriba aquí..." onchange="mostrarImagen(event)"
                                                accept="image/*" >
                                            <br>

                                            <center>
                                                <img id="preview" src="{{ URL($formacion->archivo) }}" 
                                                    style="max-width: 200px; margin-top: 10px;">
                                            </center>

                                            @error('foto')
                                                <small style="color: red">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <script>
                                            const mostrarImagen = e => {
                                                document.getElementById('preview').src = URL.createObjectURL(e.target.files[0]);
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="titulo">Título</label><b> (*)</b>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-certificate"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="titulo" id="titulo"
                                                    value="{{ old('titulo',$formacion->titulo) }}" placeholder="Ingrese el título..." required>
                                            </div>
                                            @error('titulo')
                                                <small style="color: red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="institucion">Institución</label><b> (*)</b>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-university"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" name="institucion"
                                                    id="institucion" value="{{ old('institucion',$formacion->institucion) }}"
                                                    placeholder="Ingrese la institución..." required>
                                            </div>
                                            @error('institucion')
                                                <small style="color: red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nivel">Nivel</label><b> (*)</b>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-layer-group"></i>
                                                    </span>
                                                </div>
                                                <select class="form-control" name="nivel" id="nivel" required>
                                                    <option value="" disabled selected>Seleccione el nivel...</option>
                                                    <option value="Técnico"
                                                        {{ old('nivel',$formacion->nivel) == 'Técnico' ? 'selected' : '' }}>Técnico</option>
                                                    <option value="Licenciatura"
                                                        {{ old('nivel',$formacion->nivel) == 'Licenciatura' ? 'selected' : '' }}>Licenciatura
                                                    </option>
                                                    <option value="Maestría"
                                                        {{ old('nivel',$formacion->nivel) == 'Maestría' ? 'selected' : '' }}>Maestría</option>
                                                    <option value="Doctorado"
                                                        {{ old('nivel',$formacion->nivel) == 'Doctorado' ? 'selected' : '' }}>Doctorado
                                                    </option>
                                                </select>
                                            </div>
                                            @error('nivel')
                                                <small style="color: red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_graduacion">Fecha de Graduación</label><b> (*)</b>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar"></i>
                                                    </span>
                                                </div>
                                                <input type="date" class="form-control" name="fecha_graduacion"
                                                    id="fecha_graduacion" value="{{ old('fecha_graduacion',$formacion->fecha_graduacion) }}" required>
                                            </div>
                                            @error('fecha_graduacion')
                                                <small style="color: red">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{ URL::previous() }}" class="btn btn-default">
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
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop
{{--  hola --}}
@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
