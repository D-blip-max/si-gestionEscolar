@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de calificaciones de los estudiantes de la gestión: {{ $asignacion->gestion->nombre }} - Turno:
            {{ $asignacion->turno->nombre }}
            - Nivel: {{ $asignacion->nivel->nombre }} - Grado: {{ $asignacion->grado->nombre }} - Paralelo:
            {{ $asignacion->paralelo->nombre }}
            - Materia: {{ $asignacion->materia->nombre }}</b></h1>
    <hr>
@stop

@section('content')


    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Docente:
                        {{ $asignacion->personal->apellidos . ' ' . $asignacion->personal->nombres }}
                    </h3>

                </div>
                <div class="card-body">
                    <h3>
                        Estudiantes:
                    </h3>
                    <hr>
                    <table id="example1" class="table table-bordered table-striped table-hover table-sm">

                        <thead>
                            <tr style="background-color: #b6ccf0; text-align: center;">
                                <th>Nro</th>
                                <th>Estudiante</th>
                                <th>C.I.</th>
                                @foreach ($calificacionesPorPeriodo as $periodoData)
                                    <th colspan="{{ count($periodoData['calificaciones']) + 1 }}"
                                        style="text-align: center; background-color: {{ $loop->index == 0 ? '#FFD700' : ($loop->index == 1 ? '#87CEFA' : '#98FB98') }};">
                                        {{ $periodoData['periodo']->nombre }}
                                    </th>
                                @endforeach
                                <th>Promedio Final</th>
                                <th>Estado</th>
                            </tr>
                            {{-- tener cuidado che --}}
                            <tr style="background-color: #b6ccf0; text-align: center;">
                                <th></th>
                                <th></th>
                                <th></th>
                                @foreach ($calificacionesPorPeriodo as $periodoData)
                                    @foreach ($periodoData['calificaciones'] as $calificacion)
                                        <th
                                            style="background-color: {{ $loop->parent->index == 0 ? '#FFFACD' : ($loop->parent->index == 1 ? '#E6F7FF' : '#F0FFF0') }};">

                                            {{ $calificacion->tipo }} <br><small>{{ $calificacion->fecha }}</small>
                                        </th>
                                    @endforeach
                                    <th>Promedio</th>
                                @endforeach
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $nro = 1;
                            @endphp
                            @foreach ($matriculados as $matriculado)
                                @php
                                    $est = $matriculado->estudiante;
                                    $notasFinales = [];
                                @endphp
                                <tr>
                                    <td>{{ $nro++ }}</td>
                                    <td>{{ $est->apellidos . ' ' . $est->nombres }}</td>
                                    <td>{{ $est->ci }}</td>
                                    @foreach ($calificacionesPorPeriodo as $periodoData)
                                        @php
                                            $notasTrimestre = [];
                                        @endphp
                                        @foreach ($periodoData['calificaciones'] as $calificacion)
                                            @php
                                                $detalle = $calificacion->detalleCalificaciones
                                                    ->where('estudiante_id', $est->id)
                                                    ->first();
                                                $nota = $detalle ? $detalle->nota : '-';

                                                if (is_numeric($nota)) {
                                                    $notasTrimestre[] = floatval($nota);
                                                    $notasFinales[] = floatval($nota);
                                                }
                                            @endphp
                                            <td
                                                style="background-color: {{ $loop->parent->index == 0 ? '#FFFACD' : ($loop->parent->index == 1 ? '#E6F7FF' : '#F0FFF0') }};">
                                                {{ $nota }}</td>
                                        @endforeach
                                        @php
                                            $promedioTrimestre =
                                                count($notasTrimestre) > 0
                                                    ? array_sum($notasTrimestre) / count($notasTrimestre)
                                                    : '-';
                                        @endphp
                                        <td
                                            style="text-align: center; background-color: {{ $loop->index == 0 ? '#FFD700' : ($loop->index == 1 ? '#87CEFA' : '#98FB98') }};">
                                            {{ is_numeric($promedioTrimestre) ? number_format($promedioTrimestre, 2) : $promedioTrimestre }}
                                        </td>
                                    @endforeach
                                    @php
                                        $promedioFinal =
                                            count($notasFinales) > 0
                                                ? array_sum($notasFinales) / count($notasFinales)
                                                : '-';
                                        $estado = $promedioFinal >= 51 ? 'Aprobado' : 'Reprobado';
                                    @endphp
                                    <td style="background-color: #b6ccf0; text-align: center;">
                                        {{ is_numeric($promedioFinal) ? number_format($promedioFinal, 2) : $promedioFinal }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $estado == 'Aprobado' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $estado }}
                                        </span>
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
                "pageLength": 25,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Asignaciones",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Asignaciones",
                    "infoFiltered": "(Filtrado de _MAX_ total Asignaciones)",
                    "lengthMenu": "Mostrar _MENU_ Asignaciones",
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
