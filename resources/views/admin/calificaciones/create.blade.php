@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de Calificaiones</b></h1>
    <hr>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <b>
                            Calificaiones registrados - Gestión: {{ $asignacion->gestion->nombre }} - Nivel:
                            {{ $asignacion->nivel->nombre }}
                            - Grado: {{ $asignacion->grado->nombre }} - Paralelo: {{ $asignacion->paralelo->nombre }}
                            - Materia: {{ $asignacion->materia->nombre }}</b>
                    </h3>
                    <div class="card-tools">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                            Calificar estudiantes
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #007bff; color: white;">
                                        <h5 class="modal-title" id="exampleModalLabel">Registrar calificacion</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('/admin/calificaciones/create') }}" method="POST">
                                            @csrf
                                            <input type="text" name="asignacion_id" value="{{ $asignacion->id }}" hidden>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Periodo para calificación</label><b> *</b>
                                                        <select name="periodo_id" class="form-control" id=""
                                                            required>
                                                            <option value="">Seleccione el periodo...</option>
                                                            @foreach ($periodos as $periodo)
                                                                <option value="{{ $periodo->id }}">
                                                                    {{ $periodo->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Tipo de calificación</label><b> *</b>
                                                        <input type="text" class="form-control" name="tipo" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Fecha de la calificacion</label><b> *</b>
                                                        <input type="date" class="form-control" name="fecha" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="">Descripcion (opcional)</label>
                                                        <input type="text" class="form-control" name="descripcion">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Estudiantes</label>
                                                        <table
                                                            class="table table-bordered table-striped table-hover table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th style="text-align: center">Nro</th>
                                                                    <th style="text-align: center">Estudiante</th>
                                                                    <th style="text-align: center">CI</th>
                                                                    <th style="text-align: center">Calificacion</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($matriculados as $matriculado)
                                                                    <tr>
                                                                        <td style="text-align: center">
                                                                            {{ $loop->iteration }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $matriculado->estudiante->apellidos }}
                                                                            {{ $matriculado->estudiante->nombres }}
                                                                        </td>
                                                                        <td style="text-align: center">
                                                                            {{ $matriculado->estudiante->ci }}
                                                                        </td>
                                                                        <td>
                                                                            <center>
                                                                                <input style="text-align: center;width:60px"
                                                                                    type="text" class="form-control"
                                                                                    name="nota[{{ $matriculado->estudiante->id }}]"
                                                                                    value="{{ old('nota.' . $matriculado->estudiante->id) }}"
                                                                                    required>
                                                                            </center>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Registrar
                                                        Calificacion</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    {{-- carajo --}}


                    <table id="example1" class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Periodo</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($calificaciones as $calificacione)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td style="text-align: center">{{ $calificacione->tipo }}</td>
                                    <td>{{ $calificacione->descripcion }}</td>
                                    <td>{{ $calificacione->periodo->nombre }}</td>
                                    <td>{{ $calificacione->fecha }}</td>
                                    <td>
                                        {{-- boton de modal de calificacione --}}
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                            data-target="#modalVerCalificaciones{{ $calificacione->id }}">
                                            <i class="fas fa-eye"></i> Ver calificaciones
                                        </button>
                                        {{-- modal para ver las calificacione --}}
                                        <div class="modal fade" id="modalVerCalificaciones{{ $calificacione->id }}"
                                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header"
                                                        style="background-color: #17a2b8; color: white;">
                                                        <h5 class="modal-title" id="exampleModalLabel">Detalle de
                                                            calificaciones</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">Periodo de
                                                                        calificación</label><b> *</b>
                                                                    <p>
                                                                        {{ $calificacione->periodo->nombre }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">Tipo de calificación</label><b>
                                                                        *</b>
                                                                    <p>
                                                                        {{ $calificacione->tipo }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="">Fecha de la
                                                                        calificacion</label><b> *</b>
                                                                    <p>
                                                                        {{ $calificacione->fecha }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label for="">Descripcion (opcional)</label>
                                                                    <p>
                                                                        {{ $calificacione->descripcion }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="">Estudiantes</label>
                                                                    <table
                                                                        class="table table-bordered table-striped table-hover table-sm">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="text-align: center">Nro</th>
                                                                                <th style="text-align: center">Estudiante
                                                                                </th>
                                                                                <th style="text-align: center">CI</th>
                                                                                <th style="text-align: center">Calificacion
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($calificacione->detalleCalificaciones as $detalle)
                                                                                <tr>
                                                                                    <td style="text-align: center">
                                                                                        {{ $loop->iteration }}</td>
                                                                                    <td>{{ $detalle->estudiante->apellidos }}
                                                                                        {{ $detalle->estudiante->nombres }}
                                                                                    </td>
                                                                                    <td>{{ $detalle->estudiante->ci }}</td>
                                                                                    <td style="text-align: center">
                                                                                        {{ $detalle->nota ?? 'No calificado' }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cerrar</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- modal para ver las asistencias --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
            </div>

        </div>
    </div>

@stop



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
            $("#example1").DataTable({
                "pageLength": 5,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Asistencias",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Asistencias",
                    "infoFiltered": "(Filtrado de _MAX_ total Asistencias)",
                    "lengthMenu": "Mostrar _MENU_ Asistencias",
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
