@extends('adminlte::page')

@section('title', 'Mi Avance Curricular')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-award text-primary mr-2"></i>Avance Curricular y Ruta de Graduación</h1>
                <small class="text-muted">Trazabilidad de Módulos Aprobados del 1 al 9</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('estudiante.clases.index') }}">Mi Aula</a></li>
                    <li class="breadcrumb-item active">Avance</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @php
        $aprobadosCount = 0;
        $tieneGraduacionIntermedia = false;
        $tieneGraduacionAvanzada = false;

        foreach($estudiante->inscripciones as $ins) {
            $nota = optional($ins->calificacion)->nota_final ?? null;
            if ($nota !== null && $nota >= 51) {
                $aprobadosCount++;
                if (optional($ins->modulo)->numero_orden == 8) {
                    $tieneGraduacionIntermedia = true;
                }
                if (optional($ins->modulo)->numero_orden == 9) {
                    $tieneGraduacionAvanzada = true;
                }
            }
        }
        $porcentajeAvance = min(100, round(($aprobadosCount / 9) * 100));
    @endphp

    <!-- Indicador de Ruta de Titulación -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-white">
            <h4 class="card-title text-bold mb-0"><i class="fas fa-medal text-warning mr-1"></i> Progreso hacia la Certificación Universitaria</h4>
        </div>
        <div class="card-body">
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <h5 class="text-muted mb-2">
                        Módulos Aprobados: <strong class="text-primary">{{ $aprobadosCount }} de 9</strong> ({{ $porcentajeAvance }}% completado)
                    </h5>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success font-weight-bold" style="width: {{ $porcentajeAvance }}%">
                            {{ $porcentajeAvance }}%
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center mt-3 mt-md-0">
                    <div class="p-2 border rounded d-inline-block mr-2 {{ $tieneGraduacionIntermedia ? 'bg-success text-white' : 'bg-light text-muted' }}">
                        <i class="fas fa-certificate fa-2x mb-1"></i><br>
                        <small class="font-weight-bold">TÍTULO INTERMEDIO (Mod 8)</small><br>
                        <span class="badge {{ $tieneGraduacionIntermedia ? 'badge-light text-success' : 'badge-secondary' }}">
                            {{ $tieneGraduacionIntermedia ? 'HABILITADO' : 'PENDIENTE' }}
                        </span>
                    </div>
                    <div class="p-2 border rounded d-inline-block {{ $tieneGraduacionAvanzada ? 'bg-warning text-dark' : 'bg-light text-muted' }}">
                        <i class="fas fa-trophy fa-2x mb-1"></i><br>
                        <small class="font-weight-bold">NIVEL AVANZADO (Mod 9)</small><br>
                        <span class="badge {{ $tieneGraduacionAvanzada ? 'badge-dark' : 'badge-secondary' }}">
                            {{ $tieneGraduacionAvanzada ? 'GRADUADO' : 'PENDIENTE' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Paso a Paso de Módulos 1 al 9 -->
            <div class="row text-center">
                @for($i = 1; $i <= 9; $i++)
                    @php
                        $inscripcionModulo = $estudiante->inscripciones->first(function($ins) use ($i) {
                            return optional($ins->modulo)->numero_orden == $i;
                        });
                        $nota = optional(optional($inscripcionModulo)->calificacion)->nota_final;
                        $estadoCal = optional(optional($inscripcionModulo)->calificacion)->estado;
                        $claseCard = 'border-secondary bg-light text-muted';
                        $icono = 'fa-lock';
                        $texto = 'Pendiente';

                        if ($inscripcionModulo) {
                            if ($nota !== null && $estadoCal !== 'PENDIENTE') {
                                if ($nota >= 51) {
                                    $claseCard = 'border-success bg-success text-white shadow-sm';
                                    $icono = 'fa-check-circle';
                                    $texto = $nota . ' pts';
                                } else {
                                    $claseCard = 'border-danger bg-danger text-white shadow-sm';
                                    $icono = 'fa-times-circle';
                                    $texto = $nota . ' pts';
                                }
                            } else {
                                $claseCard = 'border-primary bg-primary text-white shadow-sm';
                                $icono = 'fa-spinner fa-spin';
                                $texto = 'En Curso';
                            }
                        }
                    @endphp
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card p-3 {{ $claseCard }} h-100">
                            <h6 class="font-weight-bold text-uppercase mb-1">
                                @if($i <= 4)
                                    Módulo {{ $i }}: Básico {{ $i }}
                                @elseif($i <= 8)
                                    Módulo {{ $i }}: Intermedio {{ $i - 4 }}
                                @else
                                    Módulo 9: Avanzado
                                @endif
                            </h6>
                            <div class="my-2"><i class="fas {{ $icono }} fa-2x"></i></div>
                            <div class="font-weight-bold">{{ $texto }}</div>
                            @if($i == 8)
                                <small class="mt-1 badge badge-light border text-dark font-weight-bold">Hito Graduación</small>
                            @elseif($i == 9)
                                <small class="mt-1 badge badge-warning text-dark font-weight-bold">Certificación Final</small>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@stop

