<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudiantePortalController extends Controller
{
    /**
     * Helper para obtener el estudiante autenticado o el primero para ADMIN/Supervisión
     */
    private function getEstudiante()
    {
        $user = Auth::user();
        $estudiante = Estudiante::where('usuario_id', $user->id)
            ->orWhere('user_id', $user->id)
            ->first();

        if (!$estudiante && $user->hasRole('ADMINISTRADOR')) {
            $estudiante = Estudiante::first();
        }

        return $estudiante;
    }

    /**
     * CU-19: Mi Módulo y Acceso a Clases (Presencial / Meet)
     */
    public function modulo_activo()
    {
        $estudiante = $this->getEstudiante();
        if (!$estudiante) {
            abort(403, 'No tiene una ficha de estudiante vinculada a esta cuenta.');
        }

        $inscripcionActual = Inscripcion::with([
            'modulo.idioma',
            'paralelo.ciclo',
            'paralelo.asignacion.personal',
            'calificacion',
            'boletaPago'
        ])
        ->where('estudiante_id', $estudiante->id)
        ->where('estado', 'ACTIVA')
        ->orderBy('id', 'desc')
        ->first();

        return view('estudiante.modulo_activo', compact('estudiante', 'inscripcionActual'));
    }

    /**
     * CU-20: Avance Curricular y Ruta de Graduación (1 al 9)
     */
    public function avance_curricular()
    {
        $estudiante = $this->getEstudiante();
        if (!$estudiante) {
            abort(403, 'No tiene una ficha de estudiante vinculada.');
        }

        $estudiante->load([
            'inscripciones.modulo.idioma',
            'inscripciones.paralelo.ciclo',
            'inscripciones.calificacion'
        ]);

        return view('estudiante.avance_curricular', compact('estudiante'));
    }

    /**
     * CU-21: Historial de Documentos y Certificados Oficiales
     */
    public function mis_documentos()
    {
        $estudiante = $this->getEstudiante();
        if (!$estudiante) {
            abort(403, 'No tiene una ficha de estudiante vinculada.');
        }

        $estudiante->load([
            'inscripciones.modulo.idioma',
            'inscripciones.paralelo.ciclo',
            'inscripciones.calificacion',
            'inscripciones.boletaPago'
        ]);

        // Verificar hitos de graduación
        $aptoIntermedio = $estudiante->inscripciones->contains(function ($ins) {
            return optional($ins->modulo)->numero_orden == 8 && optional($ins->calificacion)->nota_final >= 51;
        });

        $aptoAvanzado = $estudiante->inscripciones->contains(function ($ins) {
            return optional($ins->modulo)->numero_orden == 9 && optional($ins->calificacion)->nota_final >= 51;
        });

        return view('estudiante.mis_documentos', compact('estudiante', 'aptoIntermedio', 'aptoAvanzado'));
    }

    /**
     * CU-21: Descarga de Certificado de Graduación Oficial (Intermedio / Avanzado)
     */
    public function certificado_pdf($tipo)
    {
        $estudiante = $this->getEstudiante();
        if (!$estudiante) {
            abort(403, 'No tiene una ficha de estudiante vinculada.');
        }

        $tipo = strtoupper($tipo);
        $moduloNumero = ($tipo === 'AVANZADO') ? 9 : 8;

        $aprobado = $estudiante->inscripciones()->whereHas('modulo', function ($q) use ($moduloNumero) {
            $q->where('numero_orden', $moduloNumero);
        })->whereHas('calificacion', function ($q) {
            $q->where('estado', 'APROBADO')->where('nota_final', '>=', 51);
        })->exists();

        if (!$aprobado && !Auth::user()->hasRole('ADMINISTRADOR')) {
            abort(403, "Aún no ha cumplido con los requisitos de aprobación para el Certificado {$tipo}.");
        }

        $configuracion = Configuracion::first();

        $pdf = Pdf::loadView('estudiante.certificado_pdf', compact('estudiante', 'configuracion', 'tipo'));
        $pdf->setPaper('letter', 'landscape');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream("Certificado_{$tipo}_{$estudiante->ci}.pdf");
    }
}

