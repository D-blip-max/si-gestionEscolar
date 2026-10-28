@extends('adminlte::page')

@section('title', 'Mi Aula y Clases')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-chalkboard text-primary mr-2"></i>Mi Módulo Activo y Acceso a Clases</h1>
                <small class="text-muted">Portal del Estudiante - Conexión Directa a Clases</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Mi Aula</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if ($inscripcionActual)
        @php
            $paralelo = $inscripcionActual->paralelo;
            $modulo = $inscripcionActual->modulo;
            $esVirtual = strtoupper(optional($paralelo)->modalidad) === 'VIRTUAL';
        @endphp

        <div class="row">
            <!-- Tarjeta Principal del Módulo Activo -->
            <div class="col-lg-8 col-12">
                <div class="card card-outline {{ $esVirtual ? 'card-info' : 'card-primary' }} shadow-lg">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge {{ $esVirtual ? 'badge-info' : 'badge-secondary' }} px-3 py-2 font-weight-bold">
                                <i class="fas {{ $esVirtual ? 'fa-laptop' : 'fa-building' }} mr-1"></i>
                                MODALIDAD {{ strtoupper(optional($paralelo)->modalidad) }}
                            </span>
                            <span class="badge badge-light border text-muted">
                                <i class="far fa-calendar-alt text-primary mr-1"></i> {{ optional(optional($paralelo)->ciclo)->nombre }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body py-4">
                        <div class="text-center mb-4">
                            <h2 class="font-weight-bold text-primary mb-1">
                                {{ optional(optional($modulo)->idioma)->nombre }} - {{ optional($modulo)->nombre }}
                            </h2>
                            <p class="text-muted font-weight-bold">
                                Nivel: <span class="text-uppercase">{{ optional($modulo)->nivel }}</span> | Grupo: {{ optional($paralelo)->nombre }}
                            </p>
                        </div>

                        <!-- Panel de Acceso Directo a la Clase -->
                        <div class="jumbotron {{ $esVirtual ? 'bg-light border border-info' : 'bg-light border' }} text-center py-4 mb-4">
                            @if($esVirtual)
                                <i class="fas fa-video fa-3x text-info mb-3"></i>
                                <h4 class="font-weight-bold text-dark">Aula Virtual en Google Meet / Zoom</h4>
                                <p class="text-muted text-sm mb-3">
                                    Su clase se encuentra habilitada. Ingrese puntualmente con su cuenta de estudiante.
                                </p>
                                @if(optional($paralelo)->aula_o_enlace)
                                    <a href="{{ $paralelo->aula_o_enlace }}" target="_blank" class="btn btn-info btn-lg px-5 font-weight-bold shadow">
                                        <i class="fas fa-external-link-alt mr-2"></i> ENTRAR A LA CLASE VIRTUAL
                                    </a>
                                @else
                                    <span class="badge badge-secondary p-2">El enlace será compartido en breve por el docente</span>
                                @endif
                            @else
                                <i class="fas fa-door-open fa-3x text-secondary mb-3"></i>
                                <h4 class="font-weight-bold text-dark">Clase Presencial en Campus</h4>
                                <p class="text-muted text-sm mb-2">Su aula física asignada para este ciclo bimestral es:</p>
                                <div class="display-4 font-weight-bold text-primary mb-2">
                                    {{ optional($paralelo)->aula_o_enlace ?? 'Aula 101' }}
                                </div>
                                <small class="text-muted">Preséntese portando su documento de identidad.</small>
                            @endif
                        </div>

                        <!-- Detalles del Horario y Docente -->
                        <div class="row text-center">
                            <div class="col-md-4 mb-2">
                                <div class="border rounded p-2 bg-light">
                                    <small class="text-muted font-weight-bold">TURNO Y HORARIO</small>
                                    <div class="font-weight-bold text-dark mt-1">
                                        {{ optional($paralelo)->turno }} ({{ substr(optional($paralelo)->hora_inicio ?? '00:00',0,5) }} - {{ substr(optional($paralelo)->hora_fin ?? '00:00',0,5) }})
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="border rounded p-2 bg-light">
                                    <small class="text-muted font-weight-bold">DOCENTE TITULAR</small>
                                    <div class="font-weight-bold text-dark mt-1">
                                        {{ optional(optional(optional($paralelo)->asignacion)->personal)->apellidos }} {{ optional(optional(optional($paralelo)->asignacion)->personal)->nombres ?? 'Docente Asignado' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="border rounded p-2 bg-light">
                                    <small class="text-muted font-weight-bold">ESTADO DE MATRÍCULA</small>
                                    <div class="mt-1">
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> MATRICULADO</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white text-right">
                        <a href="{{ route('admin.inscripciones.pdf', $inscripcionActual->id) }}" target="_blank" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-file-pdf mr-1"></i> Descargar Ficha de Matrícula (PDF)
                        </a>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Perfil del Alumno y Enlaces Rápidos -->
            <div class="col-lg-4 col-12">
                <div class="card card-outline card-primary shadow-sm mb-3">
                    <div class="card-body box-profile text-center">
                        @php
                            $foto = ($estudiante->foto && file_exists(public_path($estudiante->foto)))
                                ? asset($estudiante->foto)
                                : asset('img/default-avatar.png');
                        @endphp
                        <img src="{{ $foto }}" class="profile-user-img img-fluid img-circle shadow-sm mb-3" style="width: 100px; height: 100px; object-fit: cover;" alt="Avatar">
                        <h5 class="font-weight-bold text-dark mb-0">{{ $estudiante->apellidos }} {{ $estudiante->nombres }}</h5>
                        <p class="text-primary font-weight-bold mb-3">{{ $estudiante->codigo_estudiante ?? 'CEI-'.$estudiante->id }}</p>

                        <ul class="list-group list-group-unbordered text-left text-sm mb-3">
                            <li class="list-group-item py-1">
                                <b>CI:</b> <span class="float-right text-dark">{{ $estudiante->ci }}</span>
                            </li>
                            <li class="list-group-item py-1">
                                <b>Carrera:</b> <span class="float-right text-dark">{{ $estudiante->carrera_facultad ?? 'Particular' }}</span>
                            </li>
                            <li class="list-group-item py-1">
                                <b>Nro. Boleta:</b> <span class="float-right font-weight-bold text-success">{{ optional($inscripcionActual->boletaPago)->numero_boleta }}</span>
                            </li>
                        </ul>

                        <a href="{{ route('estudiante.avance.index') }}" class="btn btn-primary btn-block font-weight-bold mb-2">
                            <i class="fas fa-award mr-1"></i> Ver Mi Avance Curricular
                        </a>
                        <a href="{{ route('estudiante.documentos.index') }}" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-certificate mr-1"></i> Mis Documentos Oficiales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow-sm py-5 text-center">
            <div class="card-body">
                <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                <h3 class="font-weight-bold text-dark">No registra matrícula activa en el ciclo actual.</h3>
                <p class="text-muted max-w-500 mx-auto">
                    Si ya realizó su depósito bancario, comuníquese con ventanilla de Administración para que fiscalicen su boleta y formalicen su inscripción.
                </p>
                <div class="mt-4">
                    <a href="{{ route('estudiante.avance.index') }}" class="btn btn-primary mr-2">
                        <i class="fas fa-history mr-1"></i> Ver Mi Historial Académico
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@stop

