<?php

namespace App\Http\Controllers;

use App\Models\BoletaPago;
use App\Models\CicloBimestral;
use App\Models\Configuracion;
use App\Models\Estudiante;
use App\Models\Idioma;
use App\Models\Inscripcion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    /**
     * CU-22: Conciliación de Boletas para Tesorería
     */
    public function conciliacion_boletas(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde');
        $fechaHasta = $request->get('fecha_hasta');
        $bancoEntidad = $request->get('banco_entidad');
        $estado = $request->get('estado');

        $query = BoletaPago::with(['estudiante', 'usuarioRegistrador', 'inscripcion.modulo.idioma'])
            ->orderBy('fecha_deposito', 'desc');

        if ($fechaDesde) {
            $query->whereDate('fecha_deposito', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('fecha_deposito', '<=', $fechaHasta);
        }
        if ($bancoEntidad) {
            $query->where('banco_entidad', $bancoEntidad);
        }
        if ($estado) {
            $query->where('estado', $estado);
        }

        $boletas = $query->get();
        $totalRecaudado = $boletas->where('estado', '!=', 'ANULADA')->sum('monto');
        $cantidadBoletas = $boletas->count();

        // Entidades únicas para el filtro
        $bancos = BoletaPago::select('banco_entidad')->distinct()->pluck('banco_entidad');

        return view('admin.reportes.conciliacion_boletas', compact(
            'boletas',
            'totalRecaudado',
            'cantidadBoletas',
            'fechaDesde',
            'fechaHasta',
            'bancoEntidad',
            'estado',
            'bancos'
        ));
    }

    /**
     * CU-22: Conciliación de Boletas en PDF para Tesorería
     */
    public function conciliacion_boletas_pdf(Request $request)
    {
        $fechaDesde = $request->get('fecha_desde');
        $fechaHasta = $request->get('fecha_hasta');
        $bancoEntidad = $request->get('banco_entidad');
        $estado = $request->get('estado');

        $query = BoletaPago::with(['estudiante', 'inscripcion.modulo.idioma'])
            ->orderBy('fecha_deposito', 'desc');

        if ($fechaDesde) {
            $query->whereDate('fecha_deposito', '>=', $fechaDesde);
        }
        if ($fechaHasta) {
            $query->whereDate('fecha_deposito', '<=', $fechaHasta);
        }
        if ($bancoEntidad) {
            $query->where('banco_entidad', $bancoEntidad);
        }
        if ($estado) {
            $query->where('estado', $estado);
        }

        $boletas = $query->get();
        $totalRecaudado = $boletas->where('estado', '!=', 'ANULADA')->sum('monto');
        $configuracion = Configuracion::first();

        $pdf = Pdf::loadView('admin.reportes.conciliacion_boletas_pdf', compact(
            'boletas',
            'totalRecaudado',
            'configuracion',
            'fechaDesde',
            'fechaHasta',
            'bancoEntidad',
            'estado'
        ));

        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('Conciliacion_Boletas_Tesoreria.pdf');
    }

    /**
     * CU-23: Reporte de Inscritos por Modalidad
     */
    public function inscritos_modalidad(Request $request)
    {
        $cicloId = $request->get('ciclo_id');
        $idiomaId = $request->get('idioma_id');

        $query = Inscripcion::with(['modulo.idioma', 'paralelo.ciclo', 'estudiante'])
            ->where('estado', 'ACTIVA');

        if ($cicloId) {
            $query->whereHas('paralelo', function ($q) use ($cicloId) {
                $q->where('ciclo_id', $cicloId);
            });
        }
        if ($idiomaId) {
            $query->whereHas('modulo', function ($q) use ($idiomaId) {
                $q->where('idioma_id', $idiomaId);
            });
        }

        $inscripciones = $query->get();

        // Métricas
        $totalPresencial = $inscripciones->filter(fn($i) => strtoupper(optional($i->paralelo)->modalidad) === 'PRESENCIAL')->count();
        $totalVirtual = $inscripciones->filter(fn($i) => strtoupper(optional($i->paralelo)->modalidad) === 'VIRTUAL')->count();
        $totalGeneral = $inscripciones->count();

        // Agrupación por idioma
        $porIdioma = $inscripciones->groupBy(fn($i) => optional(optional($i->modulo)->idioma)->nombre ?? 'Otro')->map->count();

        // Agrupación por turno
        $porTurno = $inscripciones->groupBy(fn($i) => optional($i->paralelo)->turno ?? 'General')->map->count();

        $ciclos = CicloBimestral::orderBy('id', 'desc')->get();
        $idiomas = Idioma::where('estado', 'ACTIVO')->get();

        return view('admin.reportes.inscritos_modalidad', compact(
            'inscripciones',
            'totalPresencial',
            'totalVirtual',
            'totalGeneral',
            'porIdioma',
            'porTurno',
            'ciclos',
            'idiomas',
            'cicloId',
            'idiomaId'
        ));
    }

    /**
     * CU-23: Reporte de Inscritos por Modalidad en PDF
     */
    public function inscritos_modalidad_pdf(Request $request)
    {
        $cicloId = $request->get('ciclo_id');
        $idiomaId = $request->get('idioma_id');

        $query = Inscripcion::with(['modulo.idioma', 'paralelo.ciclo', 'estudiante'])
            ->where('estado', 'ACTIVA');

        if ($cicloId) {
            $query->whereHas('paralelo', function ($q) use ($cicloId) {
                $q->where('ciclo_id', $cicloId);
            });
        }
        if ($idiomaId) {
            $query->whereHas('modulo', function ($q) use ($idiomaId) {
                $q->where('idioma_id', $idiomaId);
            });
        }

        $inscripciones = $query->get();
        $totalPresencial = $inscripciones->filter(fn($i) => strtoupper(optional($i->paralelo)->modalidad) === 'PRESENCIAL')->count();
        $totalVirtual = $inscripciones->filter(fn($i) => strtoupper(optional($i->paralelo)->modalidad) === 'VIRTUAL')->count();
        $configuracion = Configuracion::first();

        $pdf = Pdf::loadView('admin.reportes.inscritos_modalidad_pdf', compact(
            'inscripciones',
            'totalPresencial',
            'totalVirtual',
            'configuracion'
        ));

        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('Reporte_Inscritos_Modalidad.pdf');
    }

    /**
     * Reporte de Graduados, Aprobados y Reprobados
     */
    public function estudiantes_graduados(Request $request)
    {
        $tipoGraduacion = $request->get('tipo', 'INTERMEDIO'); // 'INTERMEDIO', 'AVANZADO', 'APROBADOS', 'REPROBADOS'
        $idiomaId = $request->get('idioma_id');

        // Métricas globales de rendimiento académico (filtradas por idioma si aplica)
        $baseInscripciones = Inscripcion::where('estado', 'ACTIVA')
            ->when($idiomaId, function ($q) use ($idiomaId) {
                $q->whereHas('modulo', fn($m) => $m->where('idioma_id', $idiomaId));
            });

        $totalAprobados = (clone $baseInscripciones)->whereHas('calificacion', function ($c) {
            $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51);
        })->count();

        $totalReprobados = (clone $baseInscripciones)->whereHas('calificacion', function ($c) {
            $c->where('estado', 'REPROBADO');
        })->count();

        $totalCursando = (clone $baseInscripciones)->whereHas('calificacion', function ($c) {
            $c->where('estado', 'PENDIENTE');
        })->count();

        $totalGraduados = Estudiante::whereHas('inscripciones', function ($q) use ($idiomaId) {
            $q->where('estado', 'ACTIVA')
              ->whereHas('modulo', function ($m) use ($idiomaId) {
                  $m->whereIn('numero_orden', [8, 9]);
                  if ($idiomaId) {
                      $m->where('idioma_id', $idiomaId);
                  }
              })->whereHas('calificacion', function ($c) {
                  $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51);
              });
        })->count();

        // Consulta según condición seleccionada
        $query = Estudiante::whereHas('inscripciones', function ($q) use ($tipoGraduacion, $idiomaId) {
            $q->where('estado', 'ACTIVA');
            if ($idiomaId) {
                $q->whereHas('modulo', fn($m) => $m->where('idioma_id', $idiomaId));
            }

            if ($tipoGraduacion === 'INTERMEDIO') {
                $q->whereHas('modulo', fn($m) => $m->where('numero_orden', 8))
                  ->whereHas('calificacion', fn($c) => $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51));
            } elseif ($tipoGraduacion === 'AVANZADO') {
                $q->whereHas('modulo', fn($m) => $m->where('numero_orden', 9))
                  ->whereHas('calificacion', fn($c) => $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51));
            } elseif ($tipoGraduacion === 'APROBADOS') {
                $q->whereHas('calificacion', fn($c) => $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51));
            } elseif ($tipoGraduacion === 'REPROBADOS') {
                $q->whereHas('calificacion', fn($c) => $c->where('estado', 'REPROBADO'));
            }
        })->with([
            'inscripciones.modulo.idioma',
            'inscripciones.calificacion'
        ]);

        $estudiantes = $query->get()->map(function ($est) use ($tipoGraduacion) {
            $aprobadas = $est->inscripciones->filter(fn($i) => optional($i->calificacion)->estado === 'APROBADO' && optional($i->calificacion)->nota_final >= 51);
            $reprobadas = $est->inscripciones->filter(fn($i) => optional($i->calificacion)->estado === 'REPROBADO');
            $evaluadas = ($tipoGraduacion === 'REPROBADOS') ? $reprobadas : $aprobadas;

            $est->promedio_egreso = $evaluadas->count() > 0 
                ? round($evaluadas->avg(fn($i) => optional($i->calificacion)->nota_final), 1) 
                : 0;
            $est->total_modulos_aprobados = $aprobadas->count();
            $est->total_modulos_reprobados = $reprobadas->count();
            $est->ultimo_modulo = optional($evaluadas->last()?->modulo)->nombre ?? optional($est->inscripciones->last()?->modulo)->nombre ?? 'General';
            return $est;
        });

        $idiomas = Idioma::where('estado', 'ACTIVO')->get();

        return view('admin.reportes.graduados', compact(
            'estudiantes',
            'tipoGraduacion',
            'idiomaId',
            'idiomas',
            'totalGraduados',
            'totalAprobados',
            'totalReprobados',
            'totalCursando'
        ));
    }

    /**
     * Reporte de Graduados, Aprobados y Reprobados en PDF
     */
    public function estudiantes_graduados_pdf(Request $request)
    {
        $tipoGraduacion = $request->get('tipo', 'INTERMEDIO');
        $idiomaId = $request->get('idioma_id');

        $baseInscripciones = Inscripcion::where('estado', 'ACTIVA')
            ->when($idiomaId, function ($q) use ($idiomaId) {
                $q->whereHas('modulo', fn($m) => $m->where('idioma_id', $idiomaId));
            });

        $totalAprobados = (clone $baseInscripciones)->whereHas('calificacion', fn($c) => $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51))->count();
        $totalReprobados = (clone $baseInscripciones)->whereHas('calificacion', fn($c) => $c->where('estado', 'REPROBADO'))->count();
        $totalCursando = (clone $baseInscripciones)->whereHas('calificacion', fn($c) => $c->where('estado', 'PENDIENTE'))->count();
        $totalGraduados = Estudiante::whereHas('inscripciones', function ($q) use ($idiomaId) {
            $q->where('estado', 'ACTIVA')
              ->whereHas('modulo', function ($m) use ($idiomaId) {
                  $m->whereIn('numero_orden', [8, 9]);
                  if ($idiomaId) {
                      $m->where('idioma_id', $idiomaId);
                  }
              })->whereHas('calificacion', fn($c) => $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51));
        })->count();

        $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($tipoGraduacion, $idiomaId) {
            $q->where('estado', 'ACTIVA');
            if ($idiomaId) {
                $q->whereHas('modulo', fn($m) => $m->where('idioma_id', $idiomaId));
            }

            if ($tipoGraduacion === 'INTERMEDIO') {
                $q->whereHas('modulo', fn($m) => $m->where('numero_orden', 8))
                  ->whereHas('calificacion', fn($c) => $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51));
            } elseif ($tipoGraduacion === 'AVANZADO') {
                $q->whereHas('modulo', fn($m) => $m->where('numero_orden', 9))
                  ->whereHas('calificacion', fn($c) => $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51));
            } elseif ($tipoGraduacion === 'APROBADOS') {
                $q->whereHas('calificacion', fn($c) => $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51));
            } elseif ($tipoGraduacion === 'REPROBADOS') {
                $q->whereHas('calificacion', fn($c) => $c->where('estado', 'REPROBADO'));
            }
        })->with([
            'inscripciones.modulo.idioma',
            'inscripciones.calificacion'
        ])->get()->map(function ($est) use ($tipoGraduacion) {
            $aprobadas = $est->inscripciones->filter(fn($i) => optional($i->calificacion)->estado === 'APROBADO' && optional($i->calificacion)->nota_final >= 51);
            $reprobadas = $est->inscripciones->filter(fn($i) => optional($i->calificacion)->estado === 'REPROBADO');
            $evaluadas = ($tipoGraduacion === 'REPROBADOS') ? $reprobadas : $aprobadas;

            $est->promedio_egreso = $evaluadas->count() > 0 
                ? round($evaluadas->avg(fn($i) => optional($i->calificacion)->nota_final), 1) 
                : 0;
            $est->total_modulos_aprobados = $aprobadas->count();
            $est->total_modulos_reprobados = $reprobadas->count();
            return $est;
        });

        $configuracion = Configuracion::first();

        $pdf = Pdf::loadView('admin.reportes.graduados_pdf', compact(
            'estudiantes',
            'tipoGraduacion',
            'configuracion',
            'totalGraduados',
            'totalAprobados',
            'totalReprobados',
            'totalCursando'
        ));
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream("Reporte_Rendimiento_{$tipoGraduacion}.pdf");
    }
}

