@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de paralelos registrados</b></h1>
    <hr>
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Paralelos Registrados</h3>

                    <div class="card-tools">

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCreate">
                            Crear nuevo Paralelo
                        </button>

                        <!-- Modal de Creacion -->
                        <div class="modal fade" id="ModalCreate" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #007bff; color: white;">
                                        <h5 class="modal-title" id="exampleModalLabel">Registro de un nuevo Paralelo</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('/admin/paralelos/create') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Grados</label><b> (*)</b>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-list-alt"></i></span>
                                                            </div>

                                                            <select class="form-control" name="grado_id_create"
                                                                id="grado_id_create" required>
                                                                <option value="">Seleccione una grado</option>
                                                                @foreach ($grados as $grado)
                                                                    <option value="{{ $grado->id }}">
                                                                        {{ $grado->nombre }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        @error('grado_id_create')
                                                            <small style="color: red;">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="">Nombre del Paralelo</label><b> (*)</b>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-clone"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" name="nombre_create"
                                                                value="{{ old('nombre_create') }}"
                                                                placeholder="Escriba aquí..." required>
                                                        </div>
                                                        @error('nombre_create')
                                                            <small style="color: red;">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <hr>
                                                <div class="row">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Guardar</button>
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

                    <table id="example" class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Grados</th>
                                <th>Paralelos</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>
                        <tbody>
                           @foreach ($grados as $grado)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $grado->nombre }}</td>
                                    <td>
                                        @foreach ($grado->paralelos as $paralelo)
                                            <span class="badge badge-info">{{ $paralelo->nombre }}</span><br>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach ($grado->paralelos as $paralelo)
                                            <div class="row d-flex justify-content-center">
                                                <button type="button" style="margin: 4px" class="btn btn-success btn-sm"
                                                    data-toggle="modal" data-target="#ModalUpdate{{ $paralelo->id }}">
                                                    <i class="fas fa-pencil-alt"></i> Editar
                                                </button>


                                                <form action="{{ url('/admin/paralelos/' . $paralelo->id) }}" method="post"
                                                    id="miFormulario{{ $paralelo->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="margin: 4px" class="btn btn-danger btn-sm"
                                                        onclick="preguntar{{ $paralelo->id }}(event)">
                                                        <i class="fas fa-trash-alt"></i> Eliminar
                                                    </button>
                                                </form>


                                            </div>


                                            <script>
                                                function preguntar{{ $paralelo->id }}(event) {
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
                                                            document.getElementById('miFormulario{{ $paralelo->id }}').submit();
                                                        }
                                                    });
                                                }
                                            </script>




                                            <!-- Modal de Edicion -->
                                            <div class="modal fade" id="ModalUpdate{{ $paralelo->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header"
                                                            style="background-color: #09ae5b; color: white;">
                                                            <h5 class="modal-title" id="exampleModalLabel">Editar Paralelo
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ url('/admin/paralelos/' . $paralelo->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="">Grados</label><b>
                                                                                (*)
                                                                            </b>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i
                                                                                            class="fas fa-list-alt"></i></span>
                                                                                </div>

                                                                                <select class="form-control"
                                                                                    name="grado_id" id="grado_id"
                                                                                    required>
                                                                                    <option value="">Seleccione una
                                                                                        grado</option>
                                                                                    @foreach ($grados as $grado)
                                                                                        <option
                                                                                            value="{{ $grado->id }}"
                                                                                            {{ $grado->id == $paralelo->grado_id ? 'selected' : '' }}>{{-- aqui esa consulta medio ujum --}}
                                                                                            {{ $grado->nombre }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>

                                                                            </div>
                                                                            @error('grado_id')
                                                                                <small
                                                                                    style="color: red;">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="">Nombre del
                                                                                Paralelo</label><b>
                                                                                (*)
                                                                            </b>
                                                                            <div class="input-group mb-3">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text"><i
                                                                                            class="fas fa-clone"></i></span>
                                                                                </div>
                                                                                <input type="text" class="form-control"
                                                                                    name="nombre"
                                                                                    value="{{ old('nombre', $paralelo->nombre) }}"
                                                                                    placeholder="Escriba aquí..." required>
                                                                            </div>
                                                                            @error('nombre')
                                                                                <small
                                                                                    style="color: red;">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <hr>
                                                                    <div class="row">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancelar</button>
                                                                        <button type="submit"
                                                                            class="btn btn-success">Actualizar</button>
                                                                    </div>
                                                                </div>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

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
@stop
{{--  hola --}}
@section('js')
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                @if (session('modal_id'))
                    $('#ModalUpdate{{ session('modal_id') }}').modal('show');
                @else
                    // Opcional: solo si manejas un modal de creación que deba mostrarse por defecto o bajo otra condición
                    $('#ModalCreate').modal('show');
                @endif
            });
        </script>
    @endif
@stop
