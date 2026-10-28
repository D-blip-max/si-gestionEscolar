@extends('adminlte::page')

@section('title', 'Mi Histórico Académico - Instituto CEI')

@section('content_header')
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-8 d-flex align-items-center">
                <img src="{{ asset($estudiante->foto ?: 'img/default-avatar.png') }}"
                     onerror="this.onerror=null;this.src='{{ asset('img/default-avatar.png') }}';"
                     alt="Foto Estudiante"
                     class="img-circle elevation-2 mr-3 bg-white p-1"
                     style="width: 58px; height: 58px; object-fit: cover;">
                <div>
                    <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.45rem;">
                        <i class="fas fa-user-graduate text-primary mr-1"></i>
                        {{ $estudiante->nombres }} {{ $estudiante->apellidos }}
                    </h1>
                    <small class="text-muted font-weight-bold">
                        Código CEI: <strong class="text-primary">{{ $estudiante->codigo_estudiante ?? 'EST-' . $estudiante->id }}</strong>
                        | CI: {{ $estudiante->ci }}
                        | {{ $estudiante->carrera_facultad ?? 'Comunidad Universitaria UAGRM' }}
                    </small>
                </div>
            </div>
            <div class="col-sm-4 text-sm-right mt-2 mt-sm-0">
                <a href="{{ route('estudiante.documentos.kardex_pdf') }}" target="_blank" class="btn btn-primary btn-sm font-weight-bold shadow-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Descargar Mi Kárdex Oficial (PDF)
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <!-- 4 KPIs de Historial Académico del Estudiante -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $inscripciones->count() }}</h3>
                    <p>Cursos / Módulos Tomados</p>
                </div>
                <div class="icon"><i class="fas fa-layer-group"></i></div>
                <a href="{{ route('estudiante.avance.index') }}" class="small-box-footer">
                    Ver Avance Curricular <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $modulosAprobados }}</h3>
                    <p>Módulos Aprobados (Nota >= 51)</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <a href="{{ route('estudiante.documentos.index') }}" class="small-box-footer">
                    Mis Certificados <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box {{ $modulosReprobados > 0 ? 'bg-danger' : 'bg-secondary' }} shadow-sm">
                <div class="inner">
                    <h3>{{ $modulosReprobados }} <small style="font-size: 1rem;">| {{ $modulosEnCurso }} en curso</small></h3>
                    <p>Reprobados (< 51) / En Curso</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                <a href="{{ route('estudiante.clases.index') }}" class="small-box-footer">
                    Detalle de Mi Clase <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3>{{ number_format($promedioGeneral, 1) }} <small style="font-size: 1rem;">/ 100 pts</small></h3>
                    <p>Promedio Histórico General</p>
                </div>
                <div class="icon"><i class="fas fa-star"></i></div>
                <a href="{{ route('estudiante.documentos.kardex_pdf') }}" target="_blank" class="small-box-footer">
                    Exportar Kárdex PDF <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta Resumen de Mi Curso Actual en el Ciclo Vigente -->
    @if($inscripcionActual)
        @php
            $parActual = $inscripcionActual->paralelo;
            $modActual = $inscripcionActual->modulo;
            $docActual = optional(optional($parActual)->asignacion)->personal;
            $calActual = $inscripcionActual->calificacion;
        @endphp
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-chalkboard-teacher text-primary mr-1"></i>
                    Curso Actual Matriculado:
                    <span class="text-primary">{{ optional(optional($modActual)->idioma)->nombre }} — {{ optional($modActual)->nombre }}</span>
                    <small class="text-muted ml-1">({{ optional(optional($parActual)->ciclo)->nombre }})</small>
                </h5>
                <div class="mt-2 mt-md-0">
                    <a href="{{ route('estudiante.documentos.ficha_pdf', $inscripcionActual->id) }}" target="_blank" class="btn btn-outline-info btn-xs font-weight-bold mr-1">
                        <i class="fas fa-file-pdf mr-1"></i> Ficha de Matrícula PDF
                    </a>
                    <a href="{{ route('estudiante.clases.index') }}" class="btn btn-primary btn-xs font-weight-bold">
                        <i class="fas fa-door-open mr-1"></i> Ir a Mi Aula
                    </a>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                        <small class="text-muted d-block text-uppercase font-weight-bold">Grupo y Turno</small>
                        <span class="font-weight-bold text-dark">{{ optional($parActual)->nombre }}</span>
                        <span class="badge badge-secondary ml-1">{{ optional($parActual)->turno }} ({{ substr(optional($parActual)->hora_inicio, 0, 5) }} - {{ substr(optional($parActual)->hora_fin, 0, 5) }})</span>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                        <small class="text-muted d-block text-uppercase font-weight-bold">Docente Titular</small>
                        <span class="font-weight-bold text-dark">
                            <i class="fas fa-user-tie text-primary mr-1"></i>
                            {{ $docActual ? $docActual->nombres . ' ' . $docActual->apellidos : 'Por asignar' }}
                        </span>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
                        <small class="text-muted d-block text-uppercase font-weight-bold">Modalidad y Ubicación / Sala</small>
                        @if(optional($parActual)->modalidad === 'VIRTUAL')
                            <span class="badge badge-info mr-1"><i class="fas fa-video mr-1"></i> VIRTUAL</span>
                            @if(optional($parActual)->aula_o_enlace)
                                <a href="{{ $parActual->aula_o_enlace }}" target="_blank" class="btn btn-success btn-xs font-weight-bold">
                                    <i class="fas fa-external-link-alt mr-1"></i> Entrar a Google Meet
                                </a>
                            @endif
                        @else
                            <span class="badge badge-dark mr-1"><i class="fas fa-building mr-1"></i> PRESENCIAL</span>
                            <span class="font-weight-bold text-dark">{{ optional($parActual)->aula_o_enlace }}</span>
                        @endif
                    </div>
                    <div class="col-md-2 col-sm-6 text-md-right">
                        <small class="text-muted d-block text-uppercase font-weight-bold">Estado / Nota</small>
                        @if(!$calActual || $calActual->estado === 'PENDIENTE')
                            <span class="badge badge-primary px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> EN CURSO</span>
                        @elseif($calActual->estado === 'APROBADO' || $calActual->nota_final >= 51)
                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> {{ $calActual->nota_final }} pts (APROBADO)</span>
                        @else
                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-times mr-1"></i> {{ $calActual->nota_final }} pts (REPROBADO)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- TABLA PRINCIPAL: HISTÓRICO COMPLETO DE NOTAS DE TODOS LOS CURSOS TOMADOS EN EL INSTITUTO -->
    <div class="card card-outline card-dark shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h5 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-graduation-cap text-primary mr-1"></i>
                    Histórico Completo de Notas y Cursos Tomados en el Instituto (Kárdex Estudiantil)
                </h5>
                <br>
                <small class="text-muted">
                    Registro cronológico oficial de todos los módulos cursados durante su permanencia en el CEI - UAGRM (Nota mínima de aprobación: 51 / 100 pts).
                </small>
            </div>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('estudiante.avance.index') }}" class="btn btn-outline-primary btn-xs font-weight-bold mr-1">
                    <i class="fas fa-sitemap mr-1"></i> Ver Malla de Graduación
                </a>
                <a href="{{ route('estudiante.documentos.index') }}" class="btn btn-outline-success btn-xs font-weight-bold">
                    <i class="fas fa-award mr-1"></i> Diplomas y Documentos
                </a>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-striped table-bordered align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 45px;">N°</th>
                        <th>Ciclo Bimestral / Gestión</th>
                        <th>Idioma y Curso / Módulo</th>
                        <th>Paralelo y Modalidad</th>
                        <th>Docente Asignado</th>
                        <th class="text-center">Boleta (Bs.)</th>
                        <th class="text-center">Nota Final</th>
                        <th class="text-center">Estado de Aprobación</th>
                        <th>Observación Académica</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inscripciones as $index => $ins)
                        @php
                            $mod = $ins->modulo;
                            $par = $ins->paralelo;
                            $ciclo = optional($par)->ciclo;
                            $doc = optional(optional($par)->asignacion)->personal;
                            $cal = $ins->calificacion;
                            $bol = $ins->boletaPago;
                            $nota = optional($cal)->nota_final;
                            $estadoCal = optional($cal)->estado ?? 'PENDIENTE';
                        @endphp
                        <tr>
                            <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ optional($ciclo)->nombre ?? 'Ciclo Regular' }}</div>
                                <small class="text-muted">Gestión {{ optional($ciclo)->gestion ?? '2026' }} | Inscrito: {{ $ins->fecha_inscripcion }}</small>
                            </td>
                            <td>
                                <span class="badge badge-dark">{{ optional(optional($mod)->idioma)->nombre ?? 'Idioma' }}</span>
                                <div class="font-weight-bold text-primary">{{ optional($mod)->nombre }}</div>
                                <small class="text-muted">Nivel: {{ optional($mod)->nivel }}</small>
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ optional($par)->nombre }}</div>
                                @if(optional($par)->modalidad === 'VIRTUAL')
                                    <span class="badge badge-info"><i class="fas fa-video mr-1"></i> VIRTUAL</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-building mr-1"></i> PRESENCIAL</span>
                                @endif
                                <small class="text-muted d-block">{{ optional($par)->turno }} ({{ substr(optional($par)->hora_inicio, 0, 5) }}-{{ substr(optional($par)->hora_fin, 0, 5) }})</small>
                            </td>
                            <td>
                                @if($doc)
                                    <span class="font-weight-bold text-dark">{{ $doc->nombres }} {{ $doc->apellidos }}</span>
                                    <small class="text-muted d-block">{{ $doc->profesion }}</small>
                                @else
                                    <span class="text-muted">Plantel Docente CEI</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($bol)
                                    <span class="badge badge-light border text-dark font-weight-bold">{{ $bol->numero_boleta }}</span>
                                    <small class="d-block text-success font-weight-bold">Bs. {{ number_format($bol->monto, 2) }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($estadoCal === 'PENDIENTE')
                                    <span class="text-muted font-weight-bold">— / 100</span>
                                @else
                                    <span class="font-weight-bold {{ $nota >= 51 ? 'text-success' : 'text-danger' }}" style="font-size: 1.15rem;">
                                        {{ number_format($nota, 0) }} <small>/ 100</small>
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($estadoCal === 'PENDIENTE')
                                    <span class="badge badge-primary px-3 py-1">
                                        <i class="fas fa-book-reader mr-1"></i> EN CURSO
                                    </span>
                                @elseif($estadoCal === 'APROBADO' || $nota >= 51)
                                    <span class="badge badge-success px-3 py-1">
                                        <i class="fas fa-check-circle mr-1"></i> APROBADO
                                    </span>
                                @else
                                    <span class="badge badge-danger px-3 py-1">
                                        <i class="fas fa-times-circle mr-1"></i> REPROBADO
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ optional($cal)->observaciones ?: ($estadoCal === 'PENDIENTE' ? 'Cursando módulo en el bimestre actual' : 'Calificación oficial asentada en acta') }}
                                </small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle mr-1"></i> Aún no registra cursos ni calificaciones en su historial académico.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

