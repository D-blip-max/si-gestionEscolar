@extends('adminlte::page')

@section('title', 'Actas Oficiales de Calificación')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-file-pdf text-danger mr-2"></i>Actas Oficiales de Calificación</h1>
                <small class="text-muted">Emisión y Descarga de Actas Definitivas en Formato PDF</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Actas de Notas</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="card card-outline card-danger shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title text-bold"><i class="fas fa-list mr-1"></i> Grupos Modulares Disponibles para Emisión de Actas</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla-actas" class="table table-bordered table-striped table-hover table-sm">
                    <thead class="thead-light text-center">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Ciclo Bimestral</th>
                            <th>Idioma y Módulo</th>
                            <th>Grupo</th>
                            <th>Modalidad</th>
                            <th>Turno / Horario</th>
                            <th>Inscritos</th>
                            <th>Aprobados</th>
                            <th>Reprobados</th>
                            <th style="width: 140px;">Acta Oficial</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paralelos as $p)
                            @php
                                $total = $p->inscripciones->count();
                                $aprob = $p->inscripciones->filter(fn($i) => optional($i->calificacion)->estado === 'APROBADO')->count();
                                $reprob = $p->inscripciones->filter(fn($i) => optional($i->calificacion)->estado === 'REPROBADO')->count();
                            @endphp
                            <tr>
                                <td class="text-center align-middle font-weight-bold">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle">{{ optional($p->ciclo)->nombre }}</td>
                                <td class="align-middle">
                                    <strong>{{ optional($p->modulo->idioma)->nombre }}</strong> - {{ optional($p->modulo)->nombre }}
                                </td>
                                <td class="text-center align-middle font-weight-bold">Grupo {{ $p->nombre }}</td>
                                <td class="text-center align-middle">
                                    <span class="badge {{ strtoupper($p->modalidad) === 'VIRTUAL' ? 'badge-info' : 'badge-secondary' }}">
                                        {{ $p->modalidad }}
                                    </span>
                                </td>
                                <td class="text-center align-middle text-sm">
                                    {{ $p->turno }} ({{ substr($p->hora_inicio,0,5) }}-{{ substr($p->hora_fin,0,5) }})
                                </td>
                                <td class="text-center align-middle font-weight-bold">{{ $total }}</td>
                                <td class="text-center align-middle text-success font-weight-bold">{{ $aprob }}</td>
                                <td class="text-center align-middle text-danger font-weight-bold">{{ $reprob }}</td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('docente.actas.pdf', $p->id) }}" target="_blank" class="btn btn-danger btn-sm font-weight-bold shadow-sm">
                                        <i class="fas fa-file-pdf mr-1"></i> Imprimir Acta
                                    </a>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabla-actas').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });
    </script>
@stop

