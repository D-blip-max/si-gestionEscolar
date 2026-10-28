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
     * CU-16: Mis Grupos Asignados (Docente)
     */
    public function index()
    {
        $user = Auth::user();

        // Si es administrador, puede ver todos los grupos asignados o supervisar
        if ($user->hasRole('ADMINISTRADOR')) {
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
            abort(403, 'El usuario actual no tiene una ficha de personal docente vinculada.');
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
     * CU-16: Ver Lista de Alumnos del Grupo
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
        if (!$user->hasRole('ADMINISTRADOR')) {
            $docente = $user->personal;
            if (!$docente || optional($paralelo->asignacion)->personal_id !== $docente->id) {
                abort(403, 'No tiene autorización para visualizar los alumnos de este grupo.');
            }
        }

        return view('docente.grupos.show', compact('paralelo'));
    }
}

