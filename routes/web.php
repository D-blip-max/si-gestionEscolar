<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.index')->middleware('auth');

// =========================================================================
// MÓDULO 1: SEGURIDAD Y CONFIGURACIÓN INSTITUCIONAL
// =========================================================================
Route::get('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('admin.configuracion.index')->middleware('auth', 'can:admin.configuracion.index');
Route::post('/admin/configuracion/create', [App\Http\Controllers\ConfiguracionController::class, 'store'])->name('admin.configuracion.crear')->middleware('auth', 'can:admin.configuracion.crear');

Route::get('/admin/usuarios', [App\Http\Controllers\UserController::class, 'index'])->name('admin.usuarios.index')->middleware('auth', 'can:admin.usuarios.index');
Route::get('/admin/usuarios/create', [App\Http\Controllers\UserController::class, 'create'])->name('admin.usuarios.create')->middleware('auth', 'can:admin.usuarios.create');
Route::post('/admin/usuarios/create', [App\Http\Controllers\UserController::class, 'store'])->name('admin.usuarios.store')->middleware('auth', 'can:admin.usuarios.create');
Route::get('/admin/usuarios/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth', 'can:admin.usuarios.edit');
Route::put('/admin/usuarios/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('admin.usuarios.update')->middleware('auth', 'can:admin.usuarios.edit');
Route::delete('/admin/usuarios/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth', 'can:admin.usuarios.destroy');

Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth', 'can:admin.roles.index');
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth', 'can:admin.roles.create');
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth', 'can:admin.roles.store');
Route::get('/admin/roles/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth', 'can:admin.roles.edit');
Route::put('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth', 'can:admin.roles.update');
Route::delete('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth', 'can:admin.roles.destroy');
Route::get('/admin/roles/auditoria-notas', [App\Http\Controllers\RoleController::class, 'auditoria_notas'])->name('admin.roles.auditoria')->middleware('auth', 'can:admin.roles.index');
Route::get('/admin/roles/{id}/permisos', [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth', 'can:admin.roles.permisos');
Route::post('/admin/roles/{id}/permisos', [App\Http\Controllers\RoleController::class, 'update_permisos'])->name('admin.roles.update_permisos')->middleware('auth', 'can:admin.roles.update_permisos');
Route::post('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'update_permisos'])->middleware('auth', 'can:admin.roles.update_permisos');

// =========================================================================
// MÓDULO 2: CATÁLOGOS ACADÉMICOS (IDIOMAS, MÓDULOS, CICLOS, PARALELOS)
// =========================================================================
Route::get('/admin/idiomas', [App\Http\Controllers\IdiomaController::class, 'index'])->name('admin.idiomas.index')->middleware('auth', 'can:admin.idiomas.index');
Route::post('/admin/idiomas', [App\Http\Controllers\IdiomaController::class, 'store'])->name('admin.idiomas.store')->middleware('auth', 'can:admin.idiomas.create');
Route::put('/admin/idiomas/{id}', [App\Http\Controllers\IdiomaController::class, 'update'])->name('admin.idiomas.update')->middleware('auth', 'can:admin.idiomas.edit');
Route::delete('/admin/idiomas/{id}', [App\Http\Controllers\IdiomaController::class, 'destroy'])->name('admin.idiomas.destroy')->middleware('auth', 'can:admin.idiomas.destroy');

Route::resource('/admin/modulos', App\Http\Controllers\ModuloController::class)->only(['index', 'store', 'update', 'destroy'])->names('admin.modulos')->middleware('auth')->middlewareFor('index', 'can:admin.modulos.index')->middlewareFor('store', 'can:admin.modulos.create')->middlewareFor('update', 'can:admin.modulos.edit')->middlewareFor('destroy', 'can:admin.modulos.destroy');

Route::post('/admin/ciclos', [App\Http\Controllers\CicloBimestralController::class, 'store'])->name('admin.ciclos.store')->middleware('auth', 'can:admin.ciclos.create');
Route::put('/admin/ciclos/{id}', [App\Http\Controllers\CicloBimestralController::class, 'update'])->name('admin.ciclos.update')->middleware('auth', 'can:admin.ciclos.edit');
Route::post('/admin/ciclos/{id}/activar', [App\Http\Controllers\CicloBimestralController::class, 'activar'])->name('admin.ciclos.activar')->middleware('auth', 'can:admin.ciclos.edit');
Route::delete('/admin/ciclos/{id}', [App\Http\Controllers\CicloBimestralController::class, 'destroy'])->name('admin.ciclos.destroy')->middleware('auth', 'can:admin.ciclos.destroy');

Route::get('/admin/paralelos', [App\Http\Controllers\ParaleloController::class, 'index'])->name('admin.paralelos.index')->middleware('auth', 'can:admin.paralelos.index');
Route::post('/admin/paralelos', [App\Http\Controllers\ParaleloController::class, 'store'])->name('admin.paralelos.store')->middleware('auth', 'can:admin.paralelos.create');
Route::post('/admin/paralelos/create', [App\Http\Controllers\ParaleloController::class, 'store'])->name('admin.paralelos.create')->middleware('auth', 'can:admin.paralelos.create');
Route::put('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'update'])->name('admin.paralelos.update')->middleware('auth', 'can:admin.paralelos.update');
Route::delete('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'destroy'])->name('admin.paralelos.destroy')->middleware('auth', 'can:admin.paralelos.destroy');

// =========================================================================
// MÓDULO 3: PERSONAL Y DOCENCIA (PADRÓN, FORMACIÓN, ASIGNACIONES)
// =========================================================================
Route::get('/admin/personal/formaciones', [App\Http\Controllers\FormacionController::class, 'index_general'])->name('admin.personal.formaciones.general')->middleware('auth', 'can:admin.personal.formaciones.index');
Route::get('/admin/personal/{id}/formaciones', [App\Http\Controllers\FormacionController::class, 'index'])->name('admin.personal.formaciones.index')->middleware('auth', 'can:admin.personal.formaciones.index');
Route::get('/admin/personal/{id}/formaciones/create', [App\Http\Controllers\FormacionController::class, 'create'])->name('admin.personal.formaciones.create')->middleware('auth', 'can:admin.personal.formaciones.create');
Route::post('/admin/personal/{id}/formaciones/create', [App\Http\Controllers\FormacionController::class, 'store'])->name('admin.personal.formaciones.store')->middleware('auth', 'can:admin.personal.formaciones.store');
Route::get('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'edit'])->name('admin.personal.formaciones.edit')->middleware('auth', 'can:admin.personal.formaciones.edit');
Route::put('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'update'])->name('admin.personal.formaciones.update')->middleware('auth', 'can:admin.personal.formaciones.update');
Route::delete('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'destroy'])->name('admin.personal.formaciones.destroy')->middleware('auth', 'can:admin.personal.formaciones.destroy');

Route::get('/admin/personal/create/{tipo?}', [App\Http\Controllers\PersonalController::class, 'create'])->name('admin.personal.create')->middleware('auth', 'can:admin.personal.create');
Route::post('/admin/personal/create', [App\Http\Controllers\PersonalController::class, 'store'])->name('admin.personal.store')->middleware('auth', 'can:admin.personal.store');
Route::get('/admin/personal/show/{id}', [App\Http\Controllers\PersonalController::class, 'show'])->name('admin.personal.show')->middleware('auth', 'can:admin.personal.show');
Route::get('/admin/personal/{id}/edit', [App\Http\Controllers\PersonalController::class, 'edit'])->name('admin.personal.edit')->middleware('auth', 'can:admin.personal.edit');
Route::put('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'update'])->name('admin.personal.update')->middleware('auth', 'can:admin.personal.update');
Route::delete('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'destroy'])->name('admin.personal.destroy')->middleware('auth', 'can:admin.personal.destroy');
Route::get('/admin/personal/{tipo?}', [App\Http\Controllers\PersonalController::class, 'index'])->name('admin.personal.index')->middleware('auth', 'can:admin.personal.index');

Route::get('/admin/asignaciones', [App\Http\Controllers\AsignacionController::class, 'index'])->name('admin.asignaciones.index')->middleware('auth', 'can:admin.asignaciones.index');
Route::get('/admin/asignaciones/create', [App\Http\Controllers\AsignacionController::class, 'create'])->name('admin.asignaciones.create')->middleware('auth', 'can:admin.asignaciones.create');
Route::post('/admin/asignaciones/create', [App\Http\Controllers\AsignacionController::class, 'store'])->name('admin.asignaciones.store')->middleware('auth', 'can:admin.asignaciones.store');
Route::get('/admin/asignaciones/buscar_docente/{id}', [App\Http\Controllers\AsignacionController::class, 'buscar_docente'])->name('admin.asignaciones.buscar_docente')->middleware('auth', 'can:admin.asignaciones.create');
Route::get('/admin/asignaciones/{id}', [App\Http\Controllers\AsignacionController::class, 'show'])->name('admin.asignaciones.show')->middleware('auth', 'can:admin.asignaciones.show');
Route::get('/admin/asignaciones/{id}/edit', [App\Http\Controllers\AsignacionController::class, 'edit'])->name('admin.asignaciones.edit')->middleware('auth', 'can:admin.asignaciones.edit');
Route::put('/admin/asignaciones/{id}', [App\Http\Controllers\AsignacionController::class, 'update'])->name('admin.asignaciones.update')->middleware('auth', 'can:admin.asignaciones.update');
Route::delete('/admin/asignaciones/{id}', [App\Http\Controllers\AsignacionController::class, 'destroy'])->name('admin.asignaciones.destroy')->middleware('auth', 'can:admin.asignaciones.destroy');

// =========================================================================
// MÓDULO 4: ESTUDIANTES Y BOLETAS BANCARIAS
// =========================================================================
Route::get('/admin/estudiantes/buscar/ajax', [App\Http\Controllers\EstudianteController::class, 'buscar_estudiante_ajax'])->name('admin.estudiantes.buscar_ajax')->middleware('auth', 'can:admin.estudiantes.kardex');
Route::get('/admin/estudiantes/kardex', [App\Http\Controllers\EstudianteController::class, 'kardex_general'])->name('admin.estudiantes.kardex.general')->middleware('auth', 'can:admin.estudiantes.kardex');
Route::get('/admin/estudiantes', [App\Http\Controllers\EstudianteController::class, 'index'])->name('admin.estudiantes.index')->middleware('auth', 'can:admin.estudiantes.index');
Route::get('/admin/estudiantes/create', [App\Http\Controllers\EstudianteController::class, 'create'])->name('admin.estudiantes.create')->middleware('auth', 'can:admin.estudiantes.create');
Route::post('/admin/estudiantes/create', [App\Http\Controllers\EstudianteController::class, 'store'])->name('admin.estudiantes.store')->middleware('auth', 'can:admin.estudiantes.store');
Route::get('/admin/estudiantes/{id}/kardex', [App\Http\Controllers\EstudianteController::class, 'kardex'])->name('admin.estudiantes.kardex')->middleware('auth', 'can:admin.estudiantes.kardex');
Route::get('/admin/estudiantes/{id}/kardex-pdf', [App\Http\Controllers\EstudianteController::class, 'kardex_pdf'])->name('admin.estudiantes.kardex_pdf')->middleware('auth', 'can:admin.estudiantes.kardex');
Route::get('/admin/estudiantes/{id}/edit', [App\Http\Controllers\EstudianteController::class, 'edit'])->name('admin.estudiantes.edit')->middleware('auth', 'can:admin.estudiantes.edit');
Route::put('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class, 'update'])->name('admin.estudiantes.update')->middleware('auth', 'can:admin.estudiantes.update');
Route::delete('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class, 'destroy'])->name('admin.estudiantes.destroy')->middleware('auth', 'can:admin.estudiantes.destroy');
Route::get('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class, 'show'])->name('admin.estudiantes.show')->middleware('auth', 'can:admin.estudiantes.show');

Route::get('/admin/boletas', [App\Http\Controllers\BoletaPagoController::class, 'index'])->name('admin.boletas.index')->middleware('auth', 'can:admin.boletas.index');
Route::post('/admin/boletas', [App\Http\Controllers\BoletaPagoController::class, 'store'])->name('admin.boletas.store')->middleware('auth', 'can:admin.boletas.index');
Route::post('/admin/boletas/{id}/anular', [App\Http\Controllers\BoletaPagoController::class, 'anular'])->name('admin.boletas.anular')->middleware('auth', 'can:admin.boletas.index');
Route::delete('/admin/boletas/{id}', [App\Http\Controllers\BoletaPagoController::class, 'destroy'])->name('admin.boletas.destroy')->middleware('auth', 'can:admin.boletas.index');
Route::get('/admin/boletas/disponibles/{estudiante_id}', [App\Http\Controllers\BoletaPagoController::class, 'boletas_disponibles'])->name('admin.boletas.disponibles')->middleware('auth', 'can:admin.boletas.index');

// =========================================================================
// MÓDULO 5: INSCRIPCIONES Y MATRÍCULA MODULAR
// =========================================================================
Route::get('/admin/inscripciones', [App\Http\Controllers\InscripcionController::class, 'index'])->name('admin.inscripciones.index')->middleware('auth', 'can:admin.inscripciones.index');
Route::get('/admin/inscripciones/create', [App\Http\Controllers\InscripcionController::class, 'create'])->name('admin.inscripciones.create')->middleware('auth', 'can:admin.inscripciones.create');
Route::post('/admin/inscripciones/create', [App\Http\Controllers\InscripcionController::class, 'store'])->name('admin.inscripciones.store')->middleware('auth', 'can:admin.inscripciones.create');
Route::get('/admin/inscripciones/anulaciones', [App\Http\Controllers\InscripcionController::class, 'anulaciones'])->name('admin.inscripciones.anulaciones')->middleware('auth', 'can:admin.inscripciones.destroy');
Route::post('/admin/inscripciones/{id}/anular', [App\Http\Controllers\InscripcionController::class, 'anular'])->name('admin.inscripciones.anular')->middleware('auth', 'can:admin.inscripciones.destroy');
Route::get('/admin/inscripciones/{id}/pdf', [App\Http\Controllers\InscripcionController::class, 'pdf_matricula'])->name('admin.inscripciones.pdf')->middleware('auth', 'can:admin.inscripciones.index');
Route::get('/admin/inscripciones/verificar-prerrequisito', [App\Http\Controllers\InscripcionController::class, 'verificar_prerrequisito'])->name('admin.inscripciones.verificar_prerrequisito')->middleware('auth', 'can:admin.inscripciones.create');

// =========================================================================
// MÓDULO 6: EVALUACIÓN Y CALIFICACIONES (DOCENTE)
// =========================================================================
Route::get('/docente/grupos', [App\Http\Controllers\DocenteGrupoController::class, 'index'])->name('docente.grupos.index')->middleware('auth');
Route::get('/docente/grupos/{id}', [App\Http\Controllers\DocenteGrupoController::class, 'show'])->name('docente.grupos.show')->middleware('auth');
Route::get('/docente/calificaciones', [App\Http\Controllers\DocenteCalificacionController::class, 'index'])->name('docente.calificaciones.index')->middleware('auth');
Route::get('/docente/calificaciones/grupo/{paralelo_id}', [App\Http\Controllers\DocenteCalificacionController::class, 'cargar_notas'])->name('docente.calificaciones.cargar')->middleware('auth');
Route::post('/docente/calificaciones/guardar-lote', [App\Http\Controllers\DocenteCalificacionController::class, 'guardar_lote'])->name('docente.calificaciones.guardar_lote')->middleware('auth');
Route::get('/docente/actas', [App\Http\Controllers\DocenteCalificacionController::class, 'actas_index'])->name('docente.actas.index')->middleware('auth');
Route::get('/docente/actas/grupo/{paralelo_id}/pdf', [App\Http\Controllers\DocenteCalificacionController::class, 'acta_pdf'])->name('docente.actas.pdf')->middleware('auth');

// =========================================================================
// MÓDULO 7: PORTAL DEL ESTUDIANTE (AUTOGESTIÓN)
// =========================================================================
Route::get('/estudiante/modulo-activo', [App\Http\Controllers\EstudiantePortalController::class, 'modulo_activo'])->name('estudiante.clases.index')->middleware('auth');
Route::get('/estudiante/avance-curricular', [App\Http\Controllers\EstudiantePortalController::class, 'avance_curricular'])->name('estudiante.avance.index')->middleware('auth');
Route::get('/estudiante/mis-documentos', [App\Http\Controllers\EstudiantePortalController::class, 'mis_documentos'])->name('estudiante.documentos.index')->middleware('auth');
Route::get('/estudiante/mis-documentos/certificado/{tipo}', [App\Http\Controllers\EstudiantePortalController::class, 'certificado_pdf'])->name('estudiante.documentos.certificado')->middleware('auth');

// =========================================================================
// MÓDULO 8: REPORTES Y CONCILIACIÓN GERENCIAL
// =========================================================================
Route::get('/admin/reportes/conciliacion-boletas', [App\Http\Controllers\ReporteController::class, 'conciliacion_boletas'])->name('admin.reportes.conciliacion')->middleware('auth', 'can:admin.reportes.conciliacion');
Route::get('/admin/reportes/conciliacion-boletas/pdf', [App\Http\Controllers\ReporteController::class, 'conciliacion_boletas_pdf'])->name('admin.reportes.conciliacion_pdf')->middleware('auth', 'can:admin.reportes.conciliacion');
Route::get('/admin/reportes/inscritos-modalidad', [App\Http\Controllers\ReporteController::class, 'inscritos_modalidad'])->name('admin.reportes.inscritos')->middleware('auth', 'can:admin.reportes.inscritos');
Route::get('/admin/reportes/inscritos-modalidad/pdf', [App\Http\Controllers\ReporteController::class, 'inscritos_modalidad_pdf'])->name('admin.reportes.inscritos_pdf')->middleware('auth', 'can:admin.reportes.inscritos');
Route::get('/admin/reportes/estudiantes-graduados', [App\Http\Controllers\ReporteController::class, 'estudiantes_graduados'])->name('admin.reportes.graduados')->middleware('auth', 'can:admin.reportes.graduados');
Route::get('/admin/reportes/estudiantes-graduados/pdf', [App\Http\Controllers\ReporteController::class, 'estudiantes_graduados_pdf'])->name('admin.reportes.graduados_pdf')->middleware('auth', 'can:admin.reportes.graduados');
