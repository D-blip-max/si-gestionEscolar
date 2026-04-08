@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de Asistencias</b></h1>
    <hr>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <b>
                            Asistencias registrados - Gestión: {{ $asignacion->gestion->nombre }} - Nivel:
                            {{ $asignacion->nivel->nombre }}
                            - Grado: {{ $asignacion->grado->nombre }} - Paralelo: {{ $asignacion->paralelo->nombre }}
                            - Materia: {{ $asignacion->materia->nombre }}</b>
                    </h3>
                    <div class="card-tools">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                            Tomar Asistencia
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #007bff; color: white;">
                                        <h5 class="modal-title" id="exampleModalLabel">Tomar asistencia</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('/admin/asistencias/create') }}" method="POST">
                                            @csrf
                                            <input type="text" name="asignacion_id" value="{{ $asignacion->id }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="">Fecha de la asistencia</label><b> *</b>
                                                        <input type="date" class="form-control" name="fecha" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="">Observación (opcional)</label>
                                                        <input type="text" class="form-control" name="observacion">
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
                                                                    <th style="text-align: center">Nombre</th>
                                                                    <th style="text-align: center">CI</th>
                                                                    <th style="text-align: center">Asistencia</th>
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
                                                                        <td style="text-align: center">
                                                                            <input type="radio"
                                                                                name="estado_asistencia[{{ $matriculado->estudiante->id }}]"
                                                                                value="PRESENTE" required> Presente
                                                                            <input type="radio"
                                                                                name="estado_asistencia[{{ $matriculado->estudiante->id }}]"
                                                                                value="FALTA" required> Falta
                                                                            <input type="radio"
                                                                                name="estado_asistencia[{{ $matriculado->estudiante->id }}]"
                                                                                value="ATRASO" required> Atraso
                                                                            <input type="radio"
                                                                                name="estado_asistencia[{{ $matriculado->estudiante->id }}]"
                                                                                value="LICENCIA" required> Licencia
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
                                                        Asistencia</button>
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

                    <table id="example1" class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Fecha de Asistencia</th>
                                <th>Observacion</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asistencias as $asistencia)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td style="text-align: center">{{ $asistencia->fecha }}</td>
                                    <td>{{ $asistencia->observacion }}</td>
                                    <td>

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
