@extends('adminlte::page')

@section('content_header')
    <h1>Creacion de un nuevo Estudiante</h1>
    <hr>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Llene los datos del padre de familia en el formulario</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ModalCreate">
                            <i class="fas fa-search"></i> Buscar padre de familia
                        </button>

                        <div class="modal fade" id="ModalCreate" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Padres de familia</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <table id="example1"
                                            class="table table-bordered table-striped table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Nro</th>
                                                    <th style="text-align: center">Apellidos y nombres</th>
                                                    <th style="text-align: center">Carnet de identidad</th>
                                                    <th style="text-align: center">Fecha de nacimiento</th>
                                                    <th style="text-align: center">Teléfono</th>
                                                    <th style="text-align: center">Parentesco</th>
                                                    <th style="text-align: center">Ocupación</th>
                                                    <th style="text-align: center">Dirección</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($ppffs as $ppff)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $ppff->apellidos }} {{ $ppff->nombres }}</td>
                                                        <td>{{ $ppff->ci }}</td>
                                                        <td>{{ $ppff->fecha_nacimiento }}</td>
                                                        <td>{{ $ppff->telefono }}</td>
                                                        <td>{{ $ppff->parentesco }}</td>
                                                        <td>{{ $ppff->ocupacion }}</td>
                                                        <td>{{ $ppff->direccion }}</td>
                                                        <td style="text-align: center">

                                                            <button class="btn btn-info btn-seleccionar"
                                                                data-id="{{ $ppff->id }}"
                                                                data-nombres="{{ $ppff->nombres }}"
                                                                data-apellidos="{{ $ppff->apellidos }}"
                                                                data-ci="{{ $ppff->ci }}"
                                                                data-fecha_nacimiento="{{ $ppff->fecha_nacimiento }}"
                                                                data-telefono="{{ $ppff->telefono }}"
                                                                data-parentesco="{{ $ppff->parentesco }}"
                                                                data-ocupacion="{{ $ppff->ocupacion }}"
                                                                data-direccion="{{ $ppff->direccion }}">
                                                                Seleccionar
                                                            </button>


                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>

                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#ModalCreatePpff">
                                            Crear nuevo ppff
                                        </button>

                                        <div class="modal fade" id="ModalCreatePpff" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Registro de un nuevo
                                                            PPFF</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ url('/admin/estudiantes/ppffs/create') }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Nombres</label>
                                                                        <input type="text" class="form-control"
                                                                            name="nombres" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">apellidos</label>
                                                                        <input type="text" class="form-control"
                                                                            name="apellidos" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Carnet de identidad</label>
                                                                        <input type="text" class="form-control"
                                                                            name="ci" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Fecha de nacimiento</label>
                                                                        <input type="date" class="form-control"
                                                                            name="fecha_nacimiento" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Teléfono</label>
                                                                        <input type="text" class="form-control"
                                                                            name="telefono" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Parentesco</label>
                                                                        <input type="text" class="form-control"
                                                                            name="parentesco" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Ocupación</label>
                                                                        <input type="text" class="form-control"
                                                                            name="ocupacion" required>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">Dirección</label>
                                                                        <input type="text" class="form-control"
                                                                            name="direccion" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancelar</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Registrar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
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
                <div class="card-body" id="datos_ppff" style="display: none">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Nombres</label>
                                <p id="nombres">a</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Apellidos</label>
                                <p id="apellidos">a</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Carnet de identidad</label>
                                <p id="ci">a</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Fecha de nacimiento</label>
                                <p id="fecha_nacimiento">a</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Teléfono</label>
                                <p id="telefono">a</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Parentesco</label>
                                <p id="parentesco">a</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Ocupación</label>
                                <p id="ocupacion">a</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Dirección</label>
                                <p id="direccion">a</p>
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
                    <h3 class="card-title">Llene los datos del formulario</h3>
                </div>

                <div class="card-body">
                    <form action="{{ url('/admin/estudiantes/create') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="text" name="ppff_id" id="ppff_id" hidden>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Fotografía</label>

                                    <input type="file" class="form-control" name="foto"
                                        placeholder="Escriba aquí..." onchange="mostrarImagen(event)" accept="image/*">
                                    <br>
                                    <center>
                                        <img id="preview" src="" style="max-width: 200px; margin-top: 10px;">
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
                                                    <span class="input-group-text"><i
                                                            class="fas fa-user-check"></i></span>
                                                </div>
                                                <select name="rol" id="" class="form-control" >
                                                    <option value="">Seleccione un rol...</option>
                                                    @foreach ($roles as $rol)
                                                        @if ($rol->name == 'ESTUDIANTE')
                                                            <option value="{{ $rol->name }}" selected>
                                                                {{ $rol->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach

                                                    @if ($roles->isEmpty())
                                                        <option value="">No existe el rol estudiante</option>
                                                    @endif
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
                                                    value="{{ old('nombres') }}" placeholder="Ingrese nombres..."
                                                    required>
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
                                                <input type="text" class="form-control" name="apellidos"
                                                    id="apellidos" value="{{ old('apellidos') }}"
                                                    placeholder="Ingrese apellidos..." required>
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
                                                    value="{{ old('ci') }}" placeholder="Ingrese CI..." required>
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
                                                    id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
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
                                                <input type="text" class="form-control" name="direccion"
                                                    id="direccion" value="{{ old('direccion') }}"
                                                    placeholder="Ingrese dirección...">
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
                                                <input type="text" class="form-control" name="telefono"
                                                    id="telefono" value="{{ old('telefono') }}"
                                                    placeholder="Ingrese teléfo...">
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
                                                <select name="genero" id="" class="form-control">
                                                    <option value="MASCULINO">Masculino</option>
                                                    <option value="FEMENINO">Femenino</option>
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
                                                    value="{{ old('email') }}" placeholder="Ingrese su email..."
                                                    required>
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
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar
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
    <script>
        $(function() {

            $('.btn-seleccionar').click(function() {
                var nombres = $(this).data('nombres');
                var apellidos = $(this).data('apellidos');
                var ci = $(this).data('ci');
                var fecha_nacimiento = $(this).data('fecha_nacimiento');
                var telefono = $(this).data('telefono');
                var parentesco = $(this).data('parentesco');
                var ocupacion = $(this).data('ocupacion');
                var direccion = $(this).data('direccion');
                var id = $(this).data('id');

                $('#nombres').html(nombres);
                $('#apellidos').html(apellidos);
                $('#ci').html(ci);
                $('#fecha_nacimiento').html(fecha_nacimiento);
                $('#telefono').html(telefono);
                $('#parentesco').html(parentesco);
                $('#ocupacion').html(ocupacion);
                $('#direccion').html(direccion);
                $('#ppff_id').val(id);

                $('#datos_ppff').css('display', 'block');
                $('#ModalCreate').modal('hide');
            });





            $("#example1").DataTable({
                "pageLength": 5,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Personal",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Personal",
                    "infoFiltered": "(Filtrado de _MAX_ total Personal)",
                    "lengthMenu": "Mostrar _MENU_ Personal",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscador:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                buttons: [{
                        text: '<i class="fas fa-copy"></i> COPIAR',
                        extend: 'copy',
                        className: 'btn btn-default'
                    },
                    {
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        extend: 'pdf',
                        className: 'btn btn-danger'
                    },
                    {
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        extend: 'csv',
                        className: 'btn btn-info'
                    },
                    {
                        text: '<i class="fas fa-file-excel"></i> EXCEL',
                        extend: 'excel',
                        className: 'btn btn-success'
                    },
                    {
                        text: '<i class="fas fa-print"></i> IMPRIMIR',
                        extend: 'print',
                        className: 'btn btn-warning'
                    }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@stop
