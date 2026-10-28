<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaCalificacion;
use App\Models\Calificacion;
use App\Models\Configuracion;
use App\Models\Paralelo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DocenteCalificacionController extends Controller
{
    /**
     * CU-17: Listado de grupos para cargar calificaciones
     */
    public function index()
    {
        return redirect()->route('docente.grupos.index');
    }

    /**
     * CU-17: Planilla de Calificación de Nota Final
     */
    public function cargar_notas($paralelo_id)
    {
        $paralelo = Paralelo::with([
            'modulo.idioma',
            'ciclo',
            'asignacion.personal',
            'inscripciones' => function ($q) {
                $q->where('estado', 'ACTIVA')->orderBy('id', 'asc');
            },
            'inscripciones.estudiante.usuario',
            'inscripciones.calificacion'
        ])->findOrFail($paralelo_id);

        $user = Auth::user();
        if (!$user->hasAnyRole(['ADMINISTRADOR', 'DIRECTOR/A GENERAL', 'DIRECTOR/A ACADÉMICO'])) {
            $docente = $user->personal;
            if (!$docente || optional($paralelo->asignacion)->personal_id !== $docente->id) {
                abort(403, 'No autorizado para asentar calificaciones en este grupo modular.');
            }
        }

        return view('docente.calificaciones.cargar', compact('paralelo'));
    }

    /**
     * Guardar Notas Finales en Lote
     */
    public function guardar_lote(Request $request)
    {
        $request->validate([
            'paralelo_id'            => 'required|exists:paralelos,id',
            'notas'                  => 'required|array',
            'notas.*.inscripcion_id' => ['required', 'distinct', Rule::exists('inscripciones', 'id')->where(fn ($query) => $query->where('paralelo_id', $request->input('paralelo_id'))->where('estado', 'ACTIVA'))],
            'notas.*.observaciones' => 'nullable|string|max:2000',
            'notas.*.nota_final'     => 'required|numeric|min:0|max:100',
        ]);

        $paralelo = Paralelo::findOrFail($request->paralelo_id);

        $user = Auth::user();
        if (!$user->hasAnyRole(['ADMINISTRADOR', 'DIRECTOR/A GENERAL', 'DIRECTOR/A ACADÉMICO'])) {
            $docente = $user->personal;
            if (!$docente || optional($paralelo->asignacion)->personal_id !== $docente->id) {
                abort(403, 'No autorizado para asentar calificaciones en este grupo modular.');
            }
        }

        DB::transaction(function () use ($request, $user) {
            foreach ($request->notas as $item) {
                $nota = round(floatval($item['nota_final']), 2);
                $estado = ($nota >= 51) ? 'APROBADO' : 'REPROBADO';
                $observaciones = $item['observaciones'] ?? null;

                $calificacion = Calificacion::firstOrNew(['inscripcion_id' => $item['inscripcion_id']]);
                $notaAnterior = $calificacion->exists ? $calificacion->nota_final : 0;

                // Auditoría si la nota fue modificada
                if ($calificacion->exists && floatval($notaAnterior) !== floatval($nota)) {
                    AuditoriaCalificacion::create([
                        'calificacion_id' => $calificacion->id ?? 0,
                        'inscripcion_id'  => $item['inscripcion_id'],
                        'nota_anterior'   => $notaAnterior,
                        'nota_nueva'      => $nota,
                        'modificado_por'  => $user->id,
                        'created_at'      => now(),
                    ]);
                }

                $calificacion->nota_final = $nota;
                $calificacion->estado = $estado;
                $calificacion->observaciones = $observaciones;
                $calificacion->updated_by = $user->id;
                $calificacion->save();
            }
        });

        return redirect()->route('docente.calificaciones.cargar', $request->paralelo_id)
            ->with('mensaje', 'Calificaciones del módulo asentadas y guardadas exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Listado de Actas Oficiales
     */
    public function actas_index()
    {
        $user = Auth::user();

        if ($user->hasAnyRole(['ADMINISTRADOR', 'DIRECTOR/A GENERAL', 'DIRECTOR/A ACADÉMICO', 'SECRETARIO/A'])) {
            $paralelos = Paralelo::with([
                'modulo.idioma',
                'ciclo',
                'asignacion.personal',
                'inscripciones' => function ($q) {
                    $q->where('estado', 'ACTIVA');
                },
                'inscripciones.calificacion'
            ])->orderBy('id', 'desc')->get();
        } else {
            $docente = $user->personal;
            if (!$docente) {
                $paralelos = collect();
                return view('docente.calificaciones.actas_index', compact('paralelos'));
            }

            $paralelos = Paralelo::with([
                'modulo.idioma',
                'ciclo',
                'asignacion.personal',
                'inscripciones' => function ($q) {
                    $q->where('estado', 'ACTIVA');
                },
                'inscripciones.calificacion'
            ])->whereHas('asignacion', function ($q) use ($docente) {
                $q->where('personal_id', $docente->id);
            })->orderBy('id', 'desc')->get();
        }

        return view('docente.calificaciones.actas_index', compact('paralelos'));
    }

    /**
     * Generar Acta Oficial de Calificaciones en PDF
     */
    public function acta_pdf($paralelo_id)
    {
        $paralelo = Paralelo::with([
            'modulo.idioma',
            'ciclo',
            'asignacion.personal',
            'inscripciones' => function ($q) {
                $q->where('estado', 'ACTIVA');
            },
            'inscripciones.estudiante',
            'inscripciones.calificacion'
        ])->findOrFail($paralelo_id);

        $user = Auth::user();
        if (!$user->hasAnyRole(['ADMINISTRADOR', 'DIRECTOR/A GENERAL', 'DIRECTOR/A ACADÉMICO', 'SECRETARIO/A'])) {
            $docente = $user->personal;
            if (!$docente || optional($paralelo->asignacion)->personal_id !== $docente->id) {
                abort(403, 'No autorizado para emitir el acta de este grupo.');
            }
        }

        $configuracion = Configuracion::first();

        // Métricas estadísticas oficiales
        $totalInscritos = $paralelo->inscripciones->count();
        $aprobados = $paralelo->inscripciones->filter(function ($i) {
            return optional($i->calificacion)->estado === 'APROBADO';
        })->count();

        $reprobados = $paralelo->inscripciones->filter(function ($i) {
            return optional($i->calificacion)->estado === 'REPROBADO';
        })->count();

        $pendientes = $totalInscritos - ($aprobados + $reprobados);

        $promedio = $totalInscritos > 0 
            ? round($paralelo->inscripciones->avg(function ($i) {
                return optional($i->calificacion)->nota_final ?? 0;
            }), 1)
            : 0;

        $pdf = Pdf::loadView('docente.calificaciones.acta_pdf', compact(
            'paralelo',
            'configuracion',
            'totalInscritos',
            'aprobados',
            'reprobados',
            'pendientes',
            'promedio'
        ));

        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('Acta_Oficial_' . optional($paralelo->modulo)->nombre . '_' . $paralelo->nombre . '.pdf');
    }
}

