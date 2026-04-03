@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de Estudiantes Matriculados</b></h1>
    <hr>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Estudiantes Matriculados</h3>

                    <div class="card-tools">
                        <a href="{{ url('/admin/matriculaciones/create') }}" class="btn btn-primary">Crear nuevo
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <table id="example1" class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Estudiante</th>
                                <th>Carnet de identidad</th>
                                <th>Turno</th>
                                <th>Gestión</th>
                                <th>Nivel</th>
                                <th>Grado</th>
                                <th>Paralelo</th>
                                <th>Fecha de matriculacion</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matriculaciones as $matriculacione)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td>{{ $matriculacione->estudiante->apellidos . ' ' . $matriculacione->estudiante->nombres }}
                                    </td>
                                    <td>{{ $matriculacione->estudiante->ci }}</td>
                                    <td>{{ $matriculacione->turno->nombre }}</td>
                                    <td style="text-align: center">{{ $matriculacione->gestion->nombre }}</td>
                                    <td>{{ $matriculacione->nivel->nombre }}</td>
                                    <td>{{ $matriculacione->grado->nombre }}</td>
                                    <td style="text-align: center">{{ $matriculacione->paralelo->nombre }}</td>
                                    <td style="text-align: center">{{ $matriculacione->fecha_matriculacion }}</td>


                                    <td>

                                        <div class="row d-flex justify-content-center">

                                            <a href="{{ url('/admin/matriculaciones/pdf/' . $matriculacione->id) }}"
                                                class="btn btn-warning btn-sm"><i class="fas fa-print"></i> Matricula</a>
                                                
                                            <a href="{{ url('/admin/matriculaciones/' . $matriculacione->id . '/edit') }}"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-pencil-alt"></i> Editar
                                            </a>
                                            <a href="{{ url('/admin/matriculaciones/' . $matriculacione->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> ver
                                            </a>

                                            <form action="{{ url('/admin/matriculaciones/' . $matriculacione->id) }}"
                                                method="post" id="miFormulario{{ $matriculacione->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="preguntar{{ $matriculacione->id }}(event)">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>



                                        <script>
                                            function preguntar{{ $matriculacione->id }}(event) {
                                                event.preventDefault();

                                                Swal.fire({
                                                    title: '¿Desea eliminar este registro?',
                                                    text: '',
                                                    icon: 'question',
                                                    showDenyButton: true,
                                                    confirmButtonText: 'Eliminar',
                                                    confirmButtonColor: '#a5161d',
                                                    denyButtonColor: '#270a0a',
                                                    denyButtonText: 'Cancelar',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        // JavaScript puro para enviar el formulario
                                                        document.getElementById('miFormulario{{ $matriculacione->id }}').submit();
                                                    }
                                                });
                                            }
                                        </script>

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
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Estudiantes",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Estudiantes",
                    "infoFiltered": "(Filtrado de _MAX_ total Estudiantes)",
                    "lengthMenu": "Mostrar _MENU_ Estudiantes",
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
