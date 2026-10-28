@extends('adminlte::page')

@section('title', 'Mis Documentos y Certificados')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-certificate text-primary mr-2"></i>Mis Documentos y Certificaciones Oficiales</h1>
                <small class="text-muted">Descarga Directa de Fichas, Kárdex y Diplomas en PDF</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('estudiante.clases.index') }}">Mi Aula</a></li>
                    <li class="breadcrumb-item active">Documentos</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Documento 1: Kárdex Académico Oficial -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card card-outline card-primary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-primary text-white rounded mr-3">
                            <i class="fas fa-graduation-cap fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-dark mb-0">Kárdex Académico Oficial</h5>
                            <small class="text-muted">Historial completo de calificaciones modulares con membrete institucional.</small>
                        </div>
                    </div>
                    <p class="text-sm text-secondary">
                        Documento oficial firmado digitalmente para trámites universitarios, convalidaciones y respaldos académicos.
                    </p>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0">
                    <a href="{{ route('estudiante.documentos.kardex_pdf') }}" target="_blank" class="btn btn-primary btn-block font-weight-bold">
                        <i class="fas fa-file-pdf mr-1"></i> Descargar Kárdex Oficial (PDF)
                    </a>
                </div>
            </div>
        </div>

        <!-- Documento 2: Ficha de Matrícula Activa -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card card-outline card-info shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-info text-white rounded mr-3">
                            <i class="fas fa-clipboard-check fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-dark mb-0">Ficha de Matrícula Vigente</h5>
                            <small class="text-muted">Comprobante oficial de asignación de aula física o enlace Meet.</small>
                        </div>
                    </div>
                    <p class="text-sm text-secondary">
                        Acredita su condición de estudiante activo en el ciclo bimestral en curso y respalda su boleta bancaria.
                    </p>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0">
                    @php
                        $ultimaInscripcion = $estudiante->inscripciones->where('estado', 'ACTIVA')->last();
                    @endphp
                    @if($ultimaInscripcion)
                        <a href="{{ route('estudiante.documentos.ficha_pdf', $ultimaInscripcion->id) }}" target="_blank" class="btn btn-info btn-block font-weight-bold">
                            <i class="fas fa-file-pdf mr-1"></i> Descargar Ficha de Matrícula #{{ $ultimaInscripcion->id }}
                        </a>
                    @else
                        <button class="btn btn-secondary btn-block disabled" disabled>
                            Sin matrícula activa
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Documento 3: Certificado Nivel Intermedio (Módulo 8) -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card card-outline {{ $aptoIntermedio ? 'card-success' : 'card-secondary' }} shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 {{ $aptoIntermedio ? 'bg-success text-white' : 'bg-light text-muted border' }} rounded mr-3">
                            <i class="fas fa-medal fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-dark mb-0">Diploma de Nivel Intermedio</h5>
                            <small class="text-muted">Hito de Graduación: Aprobación de Módulos 1 al 8.</small>
                        </div>
                    </div>
                    <p class="text-sm text-secondary">
                        Certifica competencia comunicativa intermedia en el idioma según los estándares del Instituto.
                    </p>
                    <div class="mb-2">
                        @if($aptoIntermedio)
                            <span class="badge badge-success px-3 py-1 font-weight-bold"><i class="fas fa-check-circle"></i> REQUISITOS CUMPLIDOS</span>
                        @else
                            <span class="badge badge-secondary px-3 py-1 font-weight-bold"><i class="fas fa-lock"></i> REQUIERE APROBAR MÓDULO 8</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0">
                    @if($aptoIntermedio)
                        <a href="{{ route('estudiante.documentos.certificado', 'intermedio') }}" target="_blank" class="btn btn-success btn-block font-weight-bold">
                            <i class="fas fa-award mr-1"></i> Descargar Diploma Intermedio (PDF)
                        </a>
                    @else
                        <button class="btn btn-secondary btn-block disabled" disabled>
                            No habilitado aún
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Documento 4: Certificado Nivel Avanzado (Módulo 9) -->
        <div class="col-md-6 col-12 mb-4">
            <div class="card card-outline {{ $aptoAvanzado ? 'card-warning' : 'card-secondary' }} shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 {{ $aptoAvanzado ? 'bg-warning text-dark' : 'bg-light text-muted border' }} rounded mr-3">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                        <div>
                            <h5 class="font-weight-bold text-dark mb-0">Certificado de Nivel Avanzado</h5>
                            <small class="text-muted">Culminación del Programa Completo (Módulo 9).</small>
                        </div>
                    </div>
                    <p class="text-sm text-secondary">
                        Acredita suficiencia académica y dominio avanzado del idioma para fines de graduación universitaria.
                    </p>
                    <div class="mb-2">
                        @if($aptoAvanzado)
                            <span class="badge badge-warning text-dark px-3 py-1 font-weight-bold"><i class="fas fa-check-circle"></i> PROGRAMA CULMINADO</span>
                        @else
                            <span class="badge badge-secondary px-3 py-1 font-weight-bold"><i class="fas fa-lock"></i> REQUIERE APROBAR MÓDULO 9</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0">
                    @if($aptoAvanzado)
                        <a href="{{ route('estudiante.documentos.certificado', 'avanzado') }}" target="_blank" class="btn btn-warning text-dark btn-block font-weight-bold">
                            <i class="fas fa-certificate mr-1"></i> Descargar Certificado Avanzado (PDF)
                        </a>
                    @else
                        <button class="btn btn-secondary btn-block disabled" disabled>
                            No habilitado aún
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

