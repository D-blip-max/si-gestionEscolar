<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\Paralelo;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocenteGrupoController extends Controller
{
    /**
     * Mis Grupos Asignados (Docente)
     */
    public function index()
    {
        $user = Auth::user();

        // Si es administrador o directivo, puede ver todos los grupos asignados o supervisar
        if ($user->hasAnyRole(['ADMINISTRADOR', 'DIRECTOR/A GENERAL', 'DIRECTOR/A ACADÉMICO', 'SECRETARIO/A'])) {
            $asignaciones = Asignacion::with([
                'personal.usuario',
                'paralelo.modulo.idioma',
                'paralelo.ciclo',
                'paralelo.inscripciones' => function ($q) {
                    $q->where('estado', 'ACTIVA');
                },
                'paralelo.inscripciones.estudiante',
                'paralelo.inscripciones.calificacion'
            ])->where('estado', 'activo')
              ->orderBy('id', 'desc')
              ->get();

            return view('docente.grupos.index', compact('asignaciones'));
        }

        $docente = $user->personal;
        if (!$docente) {
            $asignaciones = collect();
            return view('docente.grupos.index', compact('asignaciones'));
        }

        $asignaciones = Asignacion::with([
            'personal.usuario',
            'paralelo.modulo.idioma',
            'paralelo.ciclo',
            'paralelo.inscripciones' => function ($q) {
                $q->where('estado', 'ACTIVA');
            },
            'paralelo.inscripciones.estudiante',
            'paralelo.inscripciones.calificacion'
        ])
        ->where('personal_id', $docente->id)
        ->where('estado', 'activo')
        ->orderBy('id', 'desc')
        ->get();

        return view('docente.grupos.index', compact('asignaciones'));
    }

    /**
     * Ver Lista de Alumnos del Grupo
     */
    public function show($id)
    {
        $paralelo = Paralelo::with([
            'modulo.idioma',
            'ciclo',
            'asignacion.personal',
            'inscripciones' => function ($q) {
                $q->where('estado', 'ACTIVA');
            },
            'inscripciones.estudiante.usuario',
            'inscripciones.calificacion'
        ])->findOrFail($id);

        $user = Auth::user();
        if (!$user->hasAnyRole(['ADMINISTRADOR', 'DIRECTOR/A GENERAL', 'DIRECTOR/A ACADÉMICO', 'SECRETARIO/A'])) {
            $docente = $user->personal;
            if (!$docente || optional($paralelo->asignacion)->personal_id !== $docente->id) {
                abort(403, 'No tiene autorización para visualizar los alumnos de este grupo.');
            }
        }

        return view('docente.grupos.show', compact('paralelo'));
    }
}

