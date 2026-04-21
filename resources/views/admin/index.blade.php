@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><b>Bienvenido: {{ Auth::user()->roles->pluck('name')->implode(', ') }} - </b>{{ Auth::user()->name }}</h1>
    <hr>
@stop

@section('content')


    <div class="row">
        @if (Auth::user()->roles->pluck('name')->implode(', ') == 'DOCENTE')
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <b>Datos del usuario</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <tr>
                                <th>Nombre</th>
                                <td>{{ Auth::user()->personal->nombres }}</td>
                            </tr>
                            <tr>
                                <th>Apellidos</th>
                                <td>{{ Auth::user()->personal->apellidos }}</td>
                            </tr>
                            <tr>
                                <th>Carnet de identidad</th>
                                <td>{{ Auth::user()->personal->ci }}</td>
                            </tr>
                            <tr>
                                <th>Fecha de nacimiento</th>
                                <td>{{ Auth::user()->personal->fecha_nacimiento }}</td>
                            </tr>
                            <tr>
                                <th>Teléfono</th>
                                <td>{{ Auth::user()->personal->telefono }}</td>
                            </tr>
                            <tr>
                                <th>Profesión</th>
                                <td>{{ Auth::user()->personal->profesion }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="row">
        @can('admin.gestiones.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/gestion.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Gestiones registrados</b></span>
                        <span class="info-box-number">{{ $total_gestiones }} gestiones</span>
                    </div>
                </div>
            </div>
        @endcan

        {{--  
        @can('admin.periodos.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/periodo.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Periodos registrados</b></span>
                        <span class="info-box-number">{{ $total_periodos }} periodos</span>
                    </div>
                </div>
            </div>
        @endcan
            
        @can('admin.grados.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/grado.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Grados registrados</b></span>
                        <span class="info-box-number">{{ $total_grados }} grados</span>
                    </div>
                </div>
            </div>
        @endcan
            --}}
        @can('admin.niveles.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/nivel.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Niveles registrados</b></span>
                        <span class="info-box-number">{{ $total_niveles }} niveles</span>
                    </div>
                </div>
            </div>
        @endcan


    </div>

    <div class="row">



        @can('admin.turnos.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/turno.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Turnos registrados</b></span>
                        <span class="info-box-number">{{ $total_turnos }} turnos</span>
                    </div>
                </div>
            </div>
        @endcan
        {{--  
        @can('admin.paralelos.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/paralelo.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Paralelos registrados</b></span>
                        <span class="info-box-number">{{ $total_paralelos }} paralelos</span>
                    </div>
                </div>
            </div>
        @endcan

        @can('admin.materias.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/materia.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Materias registradas</b></span>
                        <span class="info-box-number">{{ $total_materias }} Materias</span>
                    </div>
                </div>
            </div>
        @endcan
            --}}
        @can('admin.roles.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/usuario.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Roles registrados</b></span>
                        <span class="info-box-number">{{ $total_roles }} Roles</span>
                    </div>
                </div>
            </div>
        @endcan

    </div>
    <div class="row">

        @can('admin.personal.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/administrativo.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Personal Administrativo</b></span>
                        <span class="info-box-number">{{ $total_personal_administrativos }} Administrativos</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/docente.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Personal docente</b></span>
                        <span class="info-box-number">{{ $total_personal_docentes }} docentes</span>
                    </div>
                </div>
            </div>
        @endcan

        @can('admin.estudiantes.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/estudiantes.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Estudiantes registrados</b></span>
                        <span class="info-box-number">{{ $total_estudiantes }} Estudiantes</span>
                    </div>
                </div>
            </div>
        @endcan
        {{-- 
        @can('admin.ppffs.index')
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box zoomP">
                    <img src="{{ url('/img/padres.gif') }}" width="70px" alt="">
                    <div class="info-box-content">
                        <span class="info-box-text"><b>Ppffs registrados</b></span>
                        <span class="info-box-number">{{ $total_ppffs }} Ppffs</span>
                    </div>
                </div>
            </div>
        @endcan
            --}}



    </div>



@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
