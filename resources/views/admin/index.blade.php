@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1><b>Bienvenido: </b>{{ Auth::user()->name }}</h1>
    <hr>
@stop

@section('content')

    <div class="row">

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/gestion.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Gestiones registrados</b></span>
                    <span class="info-box-number">{{ $total_gestiones }} gestiones</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/periodo.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Periodos registrados</b></span>
                    <span class="info-box-number">{{ $total_periodos }} periodos</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/grado.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Grados registrados</b></span>
                    <span class="info-box-number">{{ $total_grados }} grados</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/nivel.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Niveles registrados</b></span>
                    <span class="info-box-number">{{ $total_niveles }} niveles</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/turno.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Turnos registrados</b></span>
                    <span class="info-box-number">{{ $total_turnos }} turnos</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/paralelo.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Paralelos registrados</b></span>
                    <span class="info-box-number">{{ $total_paralelos }} paralelos</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/materia.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Materias registradas</b></span>
                    <span class="info-box-number">{{ $total_materias }} Materias</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/usuario.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Roles registrados</b></span>
                    <span class="info-box-number">{{ $total_roles }} Roles</span>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
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
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/estudiantes.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Estudiantes registrados</b></span>
                    <span class="info-box-number">{{ $total_estudiantes }} Estudiantes</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box zoomP">
                <img src="{{ url('/img/padres.gif') }}" width="70px" alt="">
                <div class="info-box-content">
                    <span class="info-box-text"><b>Ppffs registrados</b></span>
                    <span class="info-box-number">{{ $total_ppffs }} Ppffs</span>
                </div>
            </div>
        </div>

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
