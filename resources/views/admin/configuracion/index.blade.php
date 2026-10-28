@extends('adminlte::page')
@section('title', 'Configuración del Sistema')
@section('content_header')
    <h1><i class="fas fa-university"></i> Configuración Institucional</h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cogs"></i> Datos Generales del Instituto (CEI)</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.configuracion.crear') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="form-group">
                                    <label>Logo Institucional</label> <span class="text-danger">(*)</span>
                                    <div class="custom-file mb-3 text-left">
                                        <input type="file" class="custom-file-input" id="logo" name="logo"
                                            onchange="mostrarImagen(event)" accept="image/*">
                                        <label class="custom-file-label" for="logo" data-browse="Elegir">Seleccionar imagen</label>
                                    </div>

                                    <div class="p-2 border rounded bg-light d-flex justify-content-center align-items-center" style="min-height: 220px;">
                                        @php
                                            $logoSrc = ($configuracion && $configuracion->logo && file_exists(public_path($configuracion->logo)))
                                                ? asset($configuracion->logo)
                                                : asset('vendor/adminlte/dist/img/AdminLTELogo.png');
                                        @endphp
                                        <img id="preview" src="{{ $logoSrc }}" alt="Logo" class="img-fluid rounded" style="max-height: 200px;">
                                    </div>
                                    @error('logo')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre del Instituto</label> <span class="text-danger">(*)</span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-university"></i></span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    value="{{ old('nombre', $configuracion->nombre ?? '') }}" name="nombre"
                                                    placeholder="Ej: Centro de Enseñanza de Idiomas" required>
                                            </div>
                                            @error('nombre')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Descripción / Lema</label> <span class="text-danger">(*)</span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                </div>
                                                <input type="text" class="form-control"
                                                    value="{{ old('descripcion', $configuracion->descripcion ?? '') }}"
                                                    name="descripcion" placeholder="Ej: Curso de Extensión de Idiomas" required>
                                            </div>
                                            @error('descripcion')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dirección</label> <span class="text-danger">(*)</span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                                </div>
                                                <input type="text" name="direccion" class="form-control"
                                                    value="{{ old('direccion', $configuracion->direccion ?? '') }}"
                                                    placeholder="Ej: Campus Universitario Central" required>
                                            </div>
                                            @error('direccion')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Teléfono</label> <span class="text-danger">(*)</span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone-alt"></i></span>
                                                </div>
                                                <input type="text" name="telefono" class="form-control"
                                                    value="{{ old('telefono', $configuracion->telefono ?? '') }}"
                                                    placeholder="Ej: 75657007" required>
                                            </div>
                                            @error('telefono')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Divisa</label> <span class="text-danger">(*)</span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-money-bill-alt"></i></span>
                                                </div>
                                                <input type="text" name="divisa" class="form-control"
                                                    value="{{ old('divisa', $configuracion->divisa ?? 'Bs') }}"
                                                    placeholder="Bs, USD" required>
                                            </div>
                                            @error('divisa')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Correo Electrónico</label> <span class="text-danger">(*)</span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" name="correo_electronico" class="form-control"
                                                    value="{{ old('correo_electronico', $configuracion->correo_electronico ?? '') }}"
                                                    placeholder="cei@universidad.edu" required>
                                            </div>
                                            @error('correo_electronico')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Sitio Web</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                </div>
                                                <input type="text" name="web" class="form-control"
                                                    value="{{ old('web', $configuracion->web ?? '') }}"
                                                    placeholder="https://cei.universidad.edu">
                                            </div>
                                            @error('web')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="{{ url('/admin') }}" class="btn btn-secondary mr-2">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Configuración
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        const mostrarImagen = e => {
            if (e.target.files && e.target.files[0]) {
                document.getElementById('preview').src = URL.createObjectURL(e.target.files[0]);
                const fileName = e.target.files[0].name;
                const label = e.target.nextElementSibling;
                if (label) label.innerText = fileName;
            }
        };

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
