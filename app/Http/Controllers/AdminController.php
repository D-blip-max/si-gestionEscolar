<?php

namespace App\Http\Controllers;
use App\Models\Gestion;
use Illuminate\Http\Request;
use App\Models\Periodo;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Turno;
use App\Models\Paralelo;
use App\Models\Materia;

class AdminController extends Controller
{
    //
    public function index()
    {
        $total_gestiones= Gestion::count();
        $total_periodos= Periodo::count();
        $total_niveles= Nivel::count();
        $total_grados= Grado::count();
        $total_turnos= Turno::count();
        $total_paralelos= Paralelo::count();
        $total_materias= Materia::count();


        return view('admin.index',compact('total_gestiones','total_periodos','total_niveles','total_grados','total_turnos','total_paralelos','total_materias'));
    }
}
