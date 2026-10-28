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
        $idiomas = Idioma::where('estado', 'activo')->get();

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
     * CU-24: Reporte de Graduados (Intermedio y Avanzado)
     */
    public function estudiantes_graduados(Request $request)
    {
        $tipoGraduacion = $request->get('tipo', 'INTERMEDIO'); // 'INTERMEDIO' (Modulo 8) o 'AVANZADO' (Modulo 9)
        $idiomaId = $request->get('idioma_id');

        $moduloObjetivo = ($tipoGraduacion === 'AVANZADO') ? 9 : 8;

        $query = Estudiante::whereHas('inscripciones', function ($q) use ($moduloObjetivo, $idiomaId) {
            $q->whereHas('modulo', function ($m) use ($moduloObjetivo, $idiomaId) {
                $m->where('numero_orden', $moduloObjetivo);
                if ($idiomaId) {
                    $m->where('idioma_id', $idiomaId);
                }
            })->whereHas('calificacion', function ($c) {
                $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51);
            });
        })->with([
            'inscripciones.modulo.idioma',
            'inscripciones.calificacion'
        ]);

        $estudiantes = $query->get()->map(function ($est) {
            $aprobadas = $est->inscripciones->filter(fn($i) => optional($i->calificacion)->nota_final >= 51);
            $est->promedio_egreso = $aprobadas->count() > 0 
                ? round($aprobadas->avg(fn($i) => optional($i->calificacion)->nota_final), 1) 
                : 0;
            $est->total_modulos_aprobados = $aprobadas->count();
            return $est;
        });

        $idiomas = Idioma::where('estado', 'activo')->get();

        return view('admin.reportes.graduados', compact('estudiantes', 'tipoGraduacion', 'idiomaId', 'idiomas'));
    }

    /**
     * CU-24: Reporte de Graduados en PDF
     */
    public function estudiantes_graduados_pdf(Request $request)
    {
        $tipoGraduacion = $request->get('tipo', 'INTERMEDIO');
        $idiomaId = $request->get('idioma_id');
        $moduloObjetivo = ($tipoGraduacion === 'AVANZADO') ? 9 : 8;

        $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($moduloObjetivo, $idiomaId) {
            $q->whereHas('modulo', function ($m) use ($moduloObjetivo, $idiomaId) {
                $m->where('numero_orden', $moduloObjetivo);
                if ($idiomaId) {
                    $m->where('idioma_id', $idiomaId);
                }
            })->whereHas('calificacion', function ($c) {
                $c->where('estado', 'APROBADO')->where('nota_final', '>=', 51);
            });
        })->with([
            'inscripciones.modulo.idioma',
            'inscripciones.calificacion'
        ])->get()->map(function ($est) {
            $aprobadas = $est->inscripciones->filter(fn($i) => optional($i->calificacion)->nota_final >= 51);
            $est->promedio_egreso = $aprobadas->count() > 0 
                ? round($aprobadas->avg(fn($i) => optional($i->calificacion)->nota_final), 1) 
                : 0;
            return $est;
        });

        $configuracion = Configuracion::first();

        $pdf = Pdf::loadView('admin.reportes.graduados_pdf', compact('estudiantes', 'tipoGraduacion', 'configuracion'));
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream("Padrón_Graduados_{$tipoGraduacion}.pdf");
    }
}

