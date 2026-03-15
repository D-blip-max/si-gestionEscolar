@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Datos del Sistema</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Bienvenido!! la sección de configuración del sistema</h3>
                </div>

                <div class="card-body">
                    <form action="{{ url('/admin/configuracion/create') }}" method="POST" >
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Logo de la institución</label> <b> (*)</b>

                                    <input type="file" class="form-control"
                                        value="{{ old('logo', $configuracion->logo ?? '') }}" name="logo"
                                        placeholder="Escriba aquí..." onchange="mostrarImagen(event)" accept="image/*">
                                    <br>
                                    {{-- Este es un comentario secreto de Blade --}}
                                    {{-- esto es lo que hizo el wachicolero del coso coso
                                    <center>
                                        <img id="preview" src="{{url($configuracion->logo)}}" style="max-width: 200px; margin-top: 10px;">
                                    </center>
                                    --}}
                                    {{-- esto hizo G --}}

                                    <center>
                                        @if ($configuracion && $configuracion->logo)
                                            <img id="preview" src="{{ url($configuracion->logo) }}"
                                                style="max-width: 200px; margin-top: 10px;">
                                        @endif
                                    </center>

                                    @error('logo')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                                <script>
                                    const mostrarImagen = e => {
                                        document.getElementById('preview').src = URL.createObjectURL(e.target.files[0]);
                                    }
                                </script>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Nombre</label><b> (*)</b>

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    value="{{ old('nombre', $configuracion->nombre ?? '') }}" name="nombre"
                                                    placeholder="Escriba el nombre..." required>
                                            </div> @error('nombre')
                                                <small style="color: red;">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="">Descripcion</label><b> (*)</b>

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    value="{{ old('descripcion', $configuracion->descripcion ?? '') }}"
                                                    name="descripcion" placeholder="Escriba aquí..." required>
                                            </div> @error('descripcion')
                                                <small style="color: red;">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Dirección <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="direccion" class="form-control"
                                                    value="{{ old('direccion', $configuracion->direccion ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('direccion')
                                                <small style="color: red;">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Telefono <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-phone-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="telefono" class="form-control"
                                                    value="{{ old('telefono', $configuracion->telefono ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('telefono')
                                                <small style="color: red;">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Divisa <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-money-bill-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="divisa" class="form-control"
                                                    value="{{ old('divisa', $configuracion->divisa ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('divisa')
                                                <small style="color: red;">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Correo Electronico <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-mail-bulk"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="correo_electronico" class="form-control"
                                                    value="{{ old('correo_electronico', $configuracion->correo_electronico ?? '') }}"
                                                    placeholder="Escriba aquí..." required>
                                            </div>
                                            @error('correo_electronico')
                                                <small style="color: red;">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Sitio Web <b>(*)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-globe"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="web" class="form-control"
                                                    value="{{ old('web', $configuracion->web ?? '') }}"
                                                    placeholder="Escriba aquí...">
                                            </div>
                                            @error('web')
                                                <small style="color: red;">{{ $message }}</small>
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
                                    <a href="{{ url('/admin') }}" class="btn btn-default"><i
                                            class="fas fa-arrow-left"></i> Cancelar</a>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>
                                        Guardar</button>
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
