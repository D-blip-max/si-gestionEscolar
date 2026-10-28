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
    public function index()
    {
        $user = Auth::user();

        // Redirección adaptativa según el rol principal
        if ($user->hasRole('ESTUDIANTE') && !$user->hasRole('ADMINISTRADOR')) {
            return redirect()->route('estudiante.clases.index');
        }

        if ($user->hasRole('DOCENTE') && !$user->hasRole('ADMINISTRADOR')) {
            return redirect()->route('docente.grupos.index');
        }

        // Dashboard Gerencial / Administrativo CEI
        $totalEstudiantes = Estudiante::count();
        $totalDocentes = Personal::where('tipo', 'docente')->count();
        $totalParalelos = Paralelo::count();
        $totalInscripciones = Inscripcion::where('estado', 'ACTIVA')->count();
        $totalRecaudado = BoletaPago::where('estado', '!=', 'ANULADA')->sum('monto');

        $cicloActivo = CicloBimestral::where('estado', 'ACTIVO')->first();

        return view('home', compact(
            'totalEstudiantes',
            'totalDocentes',
            'totalParalelos',
            'totalInscripciones',
            'totalRecaudado',
            'cicloActivo'
        ));
    }
}
