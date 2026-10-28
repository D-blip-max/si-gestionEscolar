<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\BoletaPago;
use App\Models\CicloBimestral;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\Paralelo;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard adapted to each institutional role.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $cicloActivo = CicloBimestral::where('estado', 'ACTIVO')->first();

        // =====================================================================
        // 1. DASHBOARD ÚNICO DEL ESTUDIANTE (Histórico de Notas y Kárdex)
        // =====================================================================
        if ($user->hasRole('ESTUDIANTE') && !$user->hasRole('ADMINISTRADOR')) {
            $estudiante = Estudiante::with([
                'usuario',
                'inscripciones' => function ($q) {
                    $q->where('estado', '!=', 'ANULADA')->orderBy('id', 'asc');
                },
                'inscripciones.modulo.idioma',
                'inscripciones.paralelo.ciclo',
                'inscripciones.paralelo.asignacion.personal',
                'inscripciones.calificacion',
                'inscripciones.boletaPago',
            ])->where('usuario_id', $user->id)->first();

            if (!$estudiante) {
                return redirect()->route('estudiante.clases.index');
            }

            $inscripciones = $estudiante->inscripciones;
            $inscripcionActual = $inscripciones->last();

            $modulosAprobados = 0;
            $modulosReprobados = 0;
            $modulosEnCurso = 0;
            $sumaNotas = 0;
            $conteoCalificados = 0;

            foreach ($inscripciones as $ins) {
                $cal = $ins->calificacion;
                if (!$cal || $cal->estado === 'PENDIENTE') {
                    $modulosEnCurso++;
                } elseif ($cal->estado === 'APROBADO' || $cal->nota_final >= 51) {
                    $modulosAprobados++;
                    $sumaNotas += (float) $cal->nota_final;
                    $conteoCalificados++;
                } else {
                    $modulosReprobados++;
                    $sumaNotas += (float) $cal->nota_final;
                    $conteoCalificados++;
                }
            }

            $promedioGeneral = $conteoCalificados > 0 ? round($sumaNotas / $conteoCalificados, 1) : 0;

            return view('estudiante.dashboard', compact(
                'estudiante',
                'cicloActivo',
                'inscripciones',
                'inscripcionActual',
                'modulosAprobados',
                'modulosReprobados',
                'modulosEnCurso',
                'promedioGeneral'
            ));
        }

        // =====================================================================
        // 2. DASHBOARD ÚNICO DEL DOCENTE (Grupos, Alumnos, Aprobados/Reprobados)
        // =====================================================================
        if ($user->hasRole('DOCENTE') && !$user->hasRole('ADMINISTRADOR')) {
            $docente = Personal::where('user_id', $user->id)->first();

            $asignaciones = collect();
            if ($docente) {
                $asignaciones = Asignacion::with([
                    'paralelo.modulo.idioma',
                    'paralelo.ciclo',
                    'paralelo.inscripciones' => function ($q) {
                        $q->where('estado', 'ACTIVA');
                    },
                    'paralelo.inscripciones.estudiante',
                    'paralelo.inscripciones.calificacion',
                ])
                ->where('personal_id', $docente->id)
                ->where('estado', 'activo')
                ->orderBy('id', 'desc')
                ->get();
            }

            $gruposActivos = $asignaciones->filter(function ($asig) {
                return optional(optional($asig->paralelo)->ciclo)->estado === 'ACTIVO';
            });

            $gruposHistoricos = $asignaciones->filter(function ($asig) {
                return optional(optional($asig->paralelo)->ciclo)->estado !== 'ACTIVO';
            });

            $totalGrupos = $asignaciones->count();
            $totalAlumnos = 0;
            $alumnosCicloActivo = 0;
            $totalAprobados = 0;
            $totalReprobados = 0;
            $totalPendientes = 0;

            foreach ($asignaciones as $asig) {
                $esActivo = optional(optional($asig->paralelo)->ciclo)->estado === 'ACTIVO';
                $inscritos = optional($asig->paralelo)->inscripciones ?? collect();
                $totalAlumnos += $inscritos->count();
                if ($esActivo) {
                    $alumnosCicloActivo += $inscritos->count();
                }

                foreach ($inscritos as $ins) {
                    $estadoCal = optional($ins->calificacion)->estado ?? 'PENDIENTE';
                    if ($estadoCal === 'APROBADO') {
                        $totalAprobados++;
                    } elseif ($estadoCal === 'REPROBADO') {
                        $totalReprobados++;
                    } else {
                        $totalPendientes++;
                    }
                }
            }

            $totalEvaluados = $totalAprobados + $totalReprobados;
            $porcentajeAprobacion = $totalEvaluados > 0
                ? round(($totalAprobados / $totalEvaluados) * 100, 1)
                : 0;

            return view('docente.dashboard', compact(
                'docente',
                'cicloActivo',
                'asignaciones',
                'gruposActivos',
                'gruposHistoricos',
                'totalGrupos',
                'totalAlumnos',
                'alumnosCicloActivo',
                'totalAprobados',
                'totalReprobados',
                'totalPendientes',
                'porcentajeAprobacion'
            ));
        }

        // =====================================================================
        // 3. DASHBOARD EJECUTIVO Y ADMINISTRATIVO (Superadmin y Personal Adm.)
        // =====================================================================
        $gestionesDisponibles = CicloBimestral::select('gestion')
            ->distinct()
            ->orderBy('gestion', 'desc')
            ->pluck('gestion');

        // Por defecto mostrar la gestión del ciclo activo (2026), o permitir ver 2025 / Todas ('TODAS')
        $gestionSeleccionada = $request->input('gestion', optional($cicloActivo)->gestion ?? 2026);

        $totalDocentes = Personal::where('tipo', 'docente')->count();
        $boletasDisponibles = BoletaPago::where('estado', 'DISPONIBLE')->count();

        if ($gestionSeleccionada === 'TODAS') {
            $totalEstudiantes = Estudiante::count();
            $totalParalelos = Paralelo::count();
            $totalInscripciones = Inscripcion::where('estado', 'ACTIVA')->count();
            $totalRecaudado = BoletaPago::where('estado', '!=', 'ANULADA')->sum('monto');
        } else {
            $anio = (int) $gestionSeleccionada;

            $totalEstudiantes = Estudiante::whereHas('inscripciones.paralelo.ciclo', function ($q) use ($anio) {
                $q->where('gestion', $anio);
            })->count();

            $totalParalelos = Paralelo::whereHas('ciclo', function ($q) use ($anio) {
                $q->where('gestion', $anio);
            })->count();

            $totalInscripciones = Inscripcion::where('estado', 'ACTIVA')
                ->whereHas('paralelo.ciclo', function ($q) use ($anio) {
                    $q->where('gestion', $anio);
                })->count();

            $totalRecaudado = BoletaPago::where('estado', '!=', 'ANULADA')
                ->whereYear('fecha_deposito', $anio)
                ->sum('monto');
        }

        // Tablas operativas en tiempo real para Personal Administrativo y Superadministrador
        $paralelosCicloActivo = Paralelo::with(['modulo.idioma', 'asignacion.personal'])
            ->where('ciclo_id', optional($cicloActivo)->id)
            ->orderBy('modulo_id', 'asc')
            ->orderBy('nombre', 'asc')
            ->take(10)
            ->get();

        $ultimasInscripciones = Inscripcion::with(['estudiante', 'modulo.idioma', 'paralelo.ciclo', 'boletaPago'])
            ->where('estado', 'ACTIVA')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        return view('home', compact(
            'totalEstudiantes',
            'totalDocentes',
            'totalParalelos',
            'totalInscripciones',
            'totalRecaudado',
            'boletasDisponibles',
            'cicloActivo',
            'gestionesDisponibles',
            'gestionSeleccionada',
            'paralelosCicloActivo',
            'ultimasInscripciones'
        ));
    }
}
