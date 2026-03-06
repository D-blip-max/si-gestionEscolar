<?php

namespace App\Http\Controllers;
use App\Models\Gestion;
use Illuminate\Http\Request;
use App\Models\Periodo;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Turno;


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


        return view('admin.index',compact('total_gestiones','total_periodos','total_niveles','total_grados','total_turnos'));
    }
}
