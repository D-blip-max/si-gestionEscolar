<?php

namespace App\Http\Controllers;

use App\Models\BoletaPago;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoletaPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $estado = $request->get('estado');
        $query = BoletaPago::with(['estudiante', 'usuarioRegistrador'])
            ->orderBy('id', 'desc');

        if ($estado && in_array(strtoupper($estado), ['DISPONIBLE', 'UTILIZADA', 'ANULADA'])) {
            $query->where('estado', strtoupper($estado));
        }

        $boletas = $query->get();
        $estudiantes = Estudiante::where('estado', 'activo')
            ->orderBy('apellidos')
            ->get();

        // Totales para estadísticas de fiscalización
        $totalRecaudado = BoletaPago::where('estado', '!=', 'ANULADA')->sum('monto');
        $totalDisponibles = BoletaPago::where('estado', 'DISPONIBLE')->count();
        $totalUtilizadas = BoletaPago::where('estado', 'UTILIZADA')->count();
        $totalAnuladas = BoletaPago::where('estado', 'ANULADA')->count();

        return view('admin.boletas.index', compact(
            'boletas',
            'estudiantes',
            'totalRecaudado',
            'totalDisponibles',
            'totalUtilizadas',
            'totalAnuladas'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'estudiante_id'       => 'required|exists:estudiantes,id',
            'numero_boleta'       => 'required|string|max:50|unique:boletas_pago,numero_boleta',
            'monto'               => 'required|numeric|min:1',
            'fecha_deposito'      => 'required|date|before_or_equal:today',
            'banco_entidad'       => 'required|string|max:100',
            'concepto'            => 'required|string|max:150',
            'archivo_comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $rutaArchivo = null;
        if ($request->hasFile('archivo_comprobante')) {
            $file = $request->file('archivo_comprobante');
            $nombreArchivo = 'voucher_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destino = public_path('uploads/vouchers');
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }
            $file->move($destino, $nombreArchivo);
            $rutaArchivo = 'uploads/vouchers/' . $nombreArchivo;
        }

        BoletaPago::create([
            'estudiante_id'       => $request->estudiante_id,
            'numero_boleta'       => trim($request->numero_boleta),
            'monto'               => $request->monto,
            'fecha_deposito'      => $request->fecha_deposito,
            'banco_entidad'       => trim($request->banco_entidad),
            'concepto'            => trim($request->concepto),
            'archivo_comprobante' => $rutaArchivo,
            'estado'              => 'DISPONIBLE',
            'registrado_por'      => Auth::id(),
        ]);

        return redirect()->route('admin.boletas.index')
            ->with('mensaje', "Boleta Nro. {$request->numero_boleta} registrada exitosamente y disponible para matrícula.")
            ->with('icono', 'success');
    }

    /**
     * Update status (e.g. Anular or reactivar boleta)
     */
    public function anular(Request $request, $id)
    {
        $boleta = BoletaPago::findOrFail($id);

        if ($boleta->estado === 'UTILIZADA') {
            return redirect()->back()
                ->with('mensaje', 'No se puede anular una boleta que ya fue consumida en una inscripción activa.')
                ->with('icono', 'error');
        }

        $boleta->update([
            'estado' => 'ANULADA'
        ]);

        return redirect()->route('admin.boletas.index')
            ->with('mensaje', "La boleta Nro. {$boleta->numero_boleta} ha sido anulada.")
            ->with('icono', 'info');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $boleta = BoletaPago::findOrFail($id);

        if ($boleta->estado === 'UTILIZADA') {
            return redirect()->back()
                ->with('mensaje', 'No se puede eliminar una boleta que ya fue consumida en una inscripción activa.')
                ->with('icono', 'error');
        }

        if (\App\Models\Inscripcion::where('boleta_pago_id', $boleta->id)->exists()) {
            return back()->with('mensaje', 'La boleta tiene historial de inscripciones y no puede eliminarse.')->with('icono', 'error');
        }

        if ($boleta->archivo_comprobante && file_exists(public_path($boleta->archivo_comprobante))) {
            @unlink(public_path($boleta->archivo_comprobante));
        }

        $boleta->delete();

        return redirect()->route('admin.boletas.index')
            ->with('mensaje', 'Boleta eliminada correctamente del registro.')
            ->with('icono', 'success');
    }

    /**
     * AJAX: Obtener boletas DISPONIBLES de un estudiante para matriculación (CU-13)
     */
    public function boletas_disponibles($estudiante_id)
    {
        $boletas = BoletaPago::where('estudiante_id', $estudiante_id)
            ->where('estado', 'DISPONIBLE')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($boletas);
    }
}

