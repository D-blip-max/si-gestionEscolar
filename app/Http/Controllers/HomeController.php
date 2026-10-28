<?php

namespace App\Http\Controllers;

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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Redirección adaptativa según el rol principal
        if ($user->hasRole('ESTUDIANTE') && !$user->hasRole('ADMINISTRADOR')) {
            return redirect()->route('estudiante.clases.index');
        }

        if ($user->hasRole('DOCENTE') && !$user->hasRole('ADMINISTRADOR')) {
            return redirect()->route('docente.grupos.index');
        }

        $cicloActivo = CicloBimestral::where('estado', 'ACTIVO')->first();
        $gestionesDisponibles = CicloBimestral::select('gestion')
            ->distinct()
            ->orderBy('gestion', 'desc')
            ->pluck('gestion');

        // Por defecto mostrar la gestión del ciclo activo (2026), o permitir ver 2025 / Todas ('TODAS')
        $gestionSeleccionada = $request->input('gestion', optional($cicloActivo)->gestion ?? 2026);

        $totalDocentes = Personal::where('tipo', 'docente')->count();

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

        return view('home', compact(
            'totalEstudiantes',
            'totalDocentes',
            'totalParalelos',
            'totalInscripciones',
            'totalRecaudado',
            'cicloActivo',
            'gestionesDisponibles',
            'gestionSeleccionada'
        ));
    }
}
