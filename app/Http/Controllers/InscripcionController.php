<?php

namespace App\Http\Controllers;

use App\Models\BoletaPago;
use App\Models\Calificacion;
use App\Models\CicloBimestral;
use App\Models\Configuracion;
use App\Models\Estudiante;
use App\Models\Idioma;
use App\Models\Inscripcion;
use App\Models\Modulo;
use App\Models\Paralelo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InscripcionController extends Controller
{
    /**
     * CU-14: Listado de Matrículas Modulares
     */
    public function index(Request $request)
    {
        $cicloId = $request->get('ciclo_id');
        $estado = $request->get('estado', 'ACTIVA');

        $query = Inscripcion::with([
            'estudiante.usuario',
            'modulo.idioma',
            'paralelo.ciclo',
            'paralelo.asignacion.personal',
            'boletaPago',
            'calificacion'
        ])->orderBy('id', 'desc');

        if ($cicloId) {
            $query->whereHas('paralelo', function ($q) use ($cicloId) {
                $q->where('ciclo_id', $cicloId);
            });
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        $inscripciones = $query->get();
        $ciclos = CicloBimestral::orderBy('id', 'desc')->get();

        return view('admin.inscripciones.index', compact('inscripciones', 'ciclos', 'cicloId', 'estado'));
    }

    /**
     * CU-13: Asistente de Nueva Inscripción Modular (ACID)
     */
    public function create()
    {
        $estudiantes = Estudiante::where('estado', 'activo')
            ->orderBy('apellidos')
            ->get();

        $idiomas = Idioma::where('estado', 'activo')->get();
        $ciclos = CicloBimestral::where('estado', '!=', 'CERRADO')->get();

        $paralelos = Paralelo::with(['modulo.idioma', 'ciclo'])
            ->whereHas('ciclo', function ($q) {
                $q->where('estado', '!=', 'CERRADO');
            })
            ->whereColumn('cupo_ocupado', '<', 'cupo_maximo')
            ->get();

        return view('admin.inscripciones.create', compact('estudiantes', 'idiomas', 'ciclos', 'paralelos'));
    }

    /**
     * CU-13: Procesamiento ACID de la Inscripción Modular
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id'  => 'required|exists:estudiantes,id',
            'boleta_pago_id' => 'required|exists:boletas_pago,id',
            'modulo_id'      => 'required|exists:modulos,id',
            'paralelo_id'    => 'required|exists:paralelos,id',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Bloquear y verificar la boleta bancaria
            $boleta = BoletaPago::where('id', $request->boleta_pago_id)
                ->where('estudiante_id', $request->estudiante_id)
                ->where('estado', 'DISPONIBLE')
                ->lockForUpdate()
                ->first();

            if (!$boleta) {
                throw ValidationException::withMessages([
                    'boleta_pago_id' => 'La boleta seleccionada no está disponible o no pertenece al estudiante.'
                ]);
            }

            // 2. Bloquear y verificar cupo en el paralelo
            $paralelo = Paralelo::where('id', $request->paralelo_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($paralelo->cupo_ocupado >= $paralelo->cupo_maximo) {
                throw ValidationException::withMessages([
                    'paralelo_id' => "El grupo modular '{$paralelo->nombre}' ya completó su cupo máximo ({$paralelo->cupo_maximo} estudiantes)."
                ]);
            }

            // 3. Verificar que no esté ya inscrito en ese mismo grupo
            $yaInscrito = Inscripcion::where('estudiante_id', $request->estudiante_id)
                ->where('paralelo_id', $request->paralelo_id)
                ->where('estado', 'ACTIVA')
                ->exists();

            if ($yaInscrito) {
                throw ValidationException::withMessages([
                    'paralelo_id' => 'El estudiante ya se encuentra matriculado en este grupo.'
                ]);
            }

            // 4. Validar Prerrequisitos Correlativos del Módulo
            $modulo = Modulo::findOrFail($request->modulo_id);
            if ($modulo->prerrequisito_id) {
                $moduloPrereq = Modulo::find($modulo->prerrequisito_id);
                $aprobado = Inscripcion::where('estudiante_id', $request->estudiante_id)
                    ->where('modulo_id', $modulo->prerrequisito_id)
                    ->whereHas('calificacion', function ($q) {
                        $q->where('estado', 'APROBADO')->where('nota_final', '>=', 51);
                    })->exists();

                if (!$aprobado) {
                    $nombrePrereq = $moduloPrereq ? $moduloPrereq->nombre : 'Módulo Previo';
                    throw ValidationException::withMessages([
                        'modulo_id' => "Control de Correlativas: El estudiante debe aprobar previamente el '{$nombrePrereq}' (Nota >= 51) para poder inscribirse a este módulo."
                    ]);
                }
            }

            // 5. Crear la Inscripción
            $inscripcion = Inscripcion::create([
                'estudiante_id'     => $request->estudiante_id,
                'modulo_id'         => $request->modulo_id,
                'paralelo_id'       => $request->paralelo_id,
                'boleta_pago_id'    => $boleta->id,
                'fecha_inscripcion' => now()->toDateString(),
                'estado'            => 'ACTIVA',
                'inscrito_por'      => Auth::id(),
            ]);

            // 6. Marcar boleta como UTILIZADA
            $boleta->update(['estado' => 'UTILIZADA']);

            // 7. Incrementar cupo del paralelo
            $paralelo->increment('cupo_ocupado');

            // 8. Crear registro de calificación en PENDIENTE
            Calificacion::create([
                'inscripcion_id' => $inscripcion->id,
                'nota_final'     => 0,
                'estado'         => 'PENDIENTE',
                'updated_by'     => Auth::id(),
            ]);

            return redirect()->route('admin.inscripciones.index')
                ->with('mensaje', "Matrícula registrada exitosamente. Ficha Nro. #{$inscripcion->id} generada.")
                ->with('icono', 'success');
        });
    }

    /**
     * CU-14: Emisión de Ficha Oficial de Matrícula en PDF
     */
    public function pdf_matricula($id)
    {
        $inscripcion = Inscripcion::with([
            'estudiante.usuario',
            'modulo.idioma',
            'paralelo.ciclo',
            'paralelo.asignacion.personal',
            'boletaPago'
        ])->findOrFail($id);

        $configuracion = Configuracion::first();

        $pdf = Pdf::loadView('admin.inscripciones.pdf_matricula', compact('inscripcion', 'configuracion'));
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('Ficha_Matricula_' . ($inscripcion->estudiante->codigo_estudiante ?? $inscripcion->id) . '.pdf');
    }

    /**
     * CU-15: Anulación y Reversión Controlada de Inscripción
     */
    public function anular(Request $request, $id)
    {
        $request->validate([
            'motivo_anulacion' => 'required|string|min:8|max:255',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $inscripcion = Inscripcion::lockForUpdate()->findOrFail($id);

            if ($inscripcion->estado === 'ANULADA') {
                return redirect()->back()
                    ->with('mensaje', 'Esta inscripción ya fue anulada previamente.')
                    ->with('icono', 'warning');
            }

            // Regla 1: Validar que no tenga nota definitiva asignada
            if ($inscripcion->calificacion && $inscripcion->calificacion->nota_final > 0) {
                return redirect()->back()
                    ->with('mensaje', 'Operación Bloqueada: El estudiante ya tiene calificación final asentada en este módulo. No puede anularse.')
                    ->with('icono', 'error');
            }

            // Regla 2: Restituir boleta bancaria a DISPONIBLE
            if ($inscripcion->boletaPago) {
                $inscripcion->boletaPago->update(['estado' => 'DISPONIBLE']);
            }

            // Regla 3: Decrementar cupo en el paralelo
            $paralelo = Paralelo::lockForUpdate()->find($inscripcion->paralelo_id);
            if ($paralelo && $paralelo->cupo_ocupado > 0) {
                $paralelo->decrement('cupo_ocupado');
            }

            // Regla 4: Actualizar estado de inscripción y calificación
            $inscripcion->update([
                'estado'           => 'ANULADA',
                'motivo_anulacion' => trim($request->motivo_anulacion),
            ]);

            if ($inscripcion->calificacion) {
                $inscripcion->calificacion->update(['estado' => 'ANULADA']);
            }

            return redirect()->route('admin.inscripciones.index')
                ->with('mensaje', "Inscripción #{$inscripcion->id} anulada exitosamente. La boleta bancaria ha sido devuelta al estado DISPONIBLE.")
                ->with('icono', 'success');
        });
    }

    /**
     * CU-15: Historial de Anulaciones
     */
    public function anulaciones()
    {
        $inscripciones = Inscripcion::with([
            'estudiante',
            'modulo.idioma',
            'paralelo.ciclo',
            'boletaPago',
            'usuarioInscriptor'
        ])->where('estado', 'ANULADA')
          ->orderBy('updated_at', 'desc')
          ->get();

        return view('admin.inscripciones.anulaciones', compact('inscripciones'));
    }

    /**
     * AJAX: Verificar si un estudiante cumple el prerrequisito para un módulo
     */
    public function verificar_prerrequisito(Request $request)
    {
        $estudianteId = $request->get('estudiante_id');
        $moduloId = $request->get('modulo_id');

        $modulo = Modulo::find($moduloId);
        if (!$modulo || !$modulo->prerrequisito_id) {
            return response()->json(['cumple' => true, 'mensaje' => 'Módulo inicial sin correlativa previa.']);
        }

        $prereq = Modulo::find($modulo->prerrequisito_id);
        $aprobado = Inscripcion::where('estudiante_id', $estudianteId)
            ->where('modulo_id', $modulo->prerrequisito_id)
            ->whereHas('calificacion', function ($q) {
                $q->where('estado', 'APROBADO')->where('nota_final', '>=', 51);
            })->exists();

        if ($aprobado) {
            return response()->json(['cumple' => true, 'mensaje' => "Cumple con el prerrequisito {$prereq->nombre} (Aprobado)."]);
        } else {
            return response()->json([
                'cumple' => false,
                'mensaje' => "No cumple prerrequisito: Requiere haber aprobado '{$prereq->nombre}' con nota >= 51."
            ]);
        }
    }
}

