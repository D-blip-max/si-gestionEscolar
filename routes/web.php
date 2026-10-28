<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register'=>true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.index')->middleware('auth');

//rutas que van a ser de configuracion del sistema
Route::get('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('admin.configuracion.index')->middleware('auth','can:admin.configuracion.index');
Route::post('/admin/configuracion/create', [App\Http\Controllers\ConfiguracionController::class, 'store'])->name('admin.configuracion.crear')->middleware('auth','can:admin.configuracion.crear');


//rutas que van a ser de gestiones del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/gestiones', [App\Http\Controllers\GestionController::class, 'index'])->name('admin.gestiones.index')->middleware('auth','can:admin.gestiones.index');
Route::get('/admin/gestiones/create', [App\Http\Controllers\GestionController::class, 'create'])->name('admin.gestiones.create')->middleware('auth','can:admin.gestiones.create');//retorna la vista
Route::post('/admin/gestiones/create', [App\Http\Controllers\GestionController::class, 'store'])->name('admin.gestiones.store')->middleware('auth','can:admin.gestiones.store');//Create
Route::get('/admin/gestiones/{id}/edit', [App\Http\Controllers\GestionController::class, 'edit'])->name('admin.gestiones.edit')->middleware('auth','can:admin.gestiones.edit');//Read
Route::put('/admin/gestiones/{id}', [App\Http\Controllers\GestionController::class, 'update'])->name('admin.gestiones.update')->middleware('auth','can:admin.gestiones.update');//Update
Route::delete('/admin/gestiones/{id}', [App\Http\Controllers\GestionController::class, 'destroy'])->name('admin.gestiones.destroy')->middleware('auth','can:admin.gestiones.destroy');//Delete



//rutas que van a ser de niveles del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/niveles', [App\Http\Controllers\NivelController::class, 'index'])->name('admin.niveles.index')->middleware('auth','can:admin.niveles.index');
Route::post('/admin/niveles/create', [App\Http\Controllers\NivelController::class, 'store'])->name('admin.niveles.create')->middleware('auth','can:admin.niveles.create');//Create
Route::put('/admin/niveles/{id}', [App\Http\Controllers\NivelController::class, 'update'])->name('admin.niveles.update')->middleware('auth','can:admin.niveles.update');//Update
Route::delete('/admin/niveles/{id}', [App\Http\Controllers\NivelController::class, 'destroy'])->name('admin.niveles.destroy')->middleware('auth','can:admin.niveles.destroy');//Delete


//rutas que van a ser de Turnos del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/turnos', [App\Http\Controllers\TurnoController::class, 'index'])->name('admin.turnos.index')->middleware('auth','can:admin.turnos.index');
Route::get('/admin/turnos/create', [App\Http\Controllers\TurnoController::class, 'create'])->name('admin.turnos.create')->middleware('auth','can:admin.turnos.create');//retorna la vista
Route::post('/admin/turnos/create', [App\Http\Controllers\TurnoController::class, 'store'])->name('admin.turnos.store')->middleware('auth','can:admin.turnos.store');//Create
Route::get('/admin/turnos/{id}/edit', [App\Http\Controllers\TurnoController::class, 'edit'])->name('admin.turnos.edit')->middleware('auth','can:admin.turnos.edit');//Read
Route::put('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'update'])->name('admin.turnos.update')->middleware('auth','can:admin.turnos.update');//Update
Route::delete('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'destroy'])->name('admin.turnos.destroy')->middleware('auth','can:admin.turnos.destroy');//Delete




//rutas que van a ser de Roles del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth','can:admin.roles.index');
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth','can:admin.roles.create');//retorna la vista
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth','can:admin.roles.store');//Create
Route::get('/admin/roles/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth','can:admin.roles.edit');//Read
Route::put('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth','can:admin.roles.update');//Update
Route::delete('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth','can:admin.roles.destroy');//Delete
//el metodo que da permisos
Route::get('/admin/roles/auditoria-notas', [App\Http\Controllers\RoleController::class, 'auditoria_notas'])->name('admin.roles.auditoria')->middleware('auth', 'can:admin.roles.index');
Route::get('/admin/roles/{id}/permisos', [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth','can:admin.roles.permisos');
Route::post('/admin/roles/{id}/permisos', [App\Http\Controllers\RoleController::class, 'update_permisos'])->name('admin.roles.update_permisos')->middleware('auth','can:admin.roles.update_permisos');
Route::post('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'update_permisos'])->middleware('auth','can:admin.roles.update_permisos');


//rutas que van a ser de Usuarios del sistema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/usuarios', [App\Http\Controllers\UserController::class, 'index'])->name('admin.usuarios.index')->middleware('auth', 'can:admin.usuarios.index');
Route::get('/admin/usuarios/create', [App\Http\Controllers\UserController::class, 'create'])->name('admin.usuarios.create')->middleware('auth', 'can:admin.usuarios.create');
Route::post('/admin/usuarios/create', [App\Http\Controllers\UserController::class, 'store'])->name('admin.usuarios.store')->middleware('auth', 'can:admin.usuarios.create');
Route::get('/admin/usuarios/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('admin.usuarios.edit')->middleware('auth', 'can:admin.usuarios.edit');
Route::put('/admin/usuarios/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('admin.usuarios.update')->middleware('auth', 'can:admin.usuarios.edit');
Route::delete('/admin/usuarios/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware('auth', 'can:admin.usuarios.destroy');



































//rutas que van a ser de Periodos del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/periodos', [App\Http\Controllers\PeriodoController::class, 'index'])->name('admin.periodos.index')->middleware('auth','can:admin.periodos.index');
Route::post('/admin/periodos/create', [App\Http\Controllers\PeriodoController::class, 'store'])->name('admin.periodos.create')->middleware('auth','can:admin.periodos.create');//Create
Route::put('/admin/periodos/{id}', [App\Http\Controllers\PeriodoController::class, 'update'])->name('admin.periodos.update')->middleware('auth','can:admin.periodos.update');//Update
Route::delete('/admin/periodos/{id}', [App\Http\Controllers\PeriodoController::class, 'destroy'])->name('admin.periodos.destroy')->middleware('auth','can:admin.periodos.destroy');//Delete


//rutas que van a ser de materias del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/materias', [App\Http\Controllers\MateriaController::class, 'index'])->name('admin.materias.index')->middleware('auth','can:admin.materias.index');
Route::post('/admin/materias/create', [App\Http\Controllers\MateriaController::class, 'store'])->name('admin.materias.create')->middleware('auth','can:admin.materias.create');//Create
Route::put('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'update'])->name('admin.materias.update')->middleware('auth','can:admin.materias.update');//Update
Route::delete('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'destroy'])->name('admin.materias.destroy')->middleware('auth','can:admin.materias.destroy');//Delete



//rutas que van a ser de grados del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/grados', [App\Http\Controllers\GradoController::class, 'index'])->name('admin.grados.index')->middleware('auth','can:admin.grados.index');
Route::post('/admin/grados/create', [App\Http\Controllers\GradoController::class, 'store'])->name('admin.grados.create')->middleware('auth','can:admin.grados.create');//Create
Route::put('/admin/grados/{id}', [App\Http\Controllers\GradoController::class, 'update'])->name('admin.grados.update')->middleware('auth','can:admin.grados.update');//Update
Route::delete('/admin/grados/{id}', [App\Http\Controllers\GradoController::class, 'destroy'])->name('admin.grados.destroy')->middleware('auth','can:admin.grados.destroy');//Delete


// =========================================================================
// PAQUETE 2: CATÁLOGOS ACADÉMICOS (IDIOMAS, MÓDULOS, CICLOS, PARALELOS)
// =========================================================================
Route::get('/admin/idiomas', [App\Http\Controllers\IdiomaController::class, 'index'])->name('admin.idiomas.index')->middleware('auth');
Route::post('/admin/idiomas', [App\Http\Controllers\IdiomaController::class, 'store'])->name('admin.idiomas.store')->middleware('auth');
Route::put('/admin/idiomas/{id}', [App\Http\Controllers\IdiomaController::class, 'update'])->name('admin.idiomas.update')->middleware('auth');
Route::delete('/admin/idiomas/{id}', [App\Http\Controllers\IdiomaController::class, 'destroy'])->name('admin.idiomas.destroy')->middleware('auth');

Route::resource('/admin/modulos', App\Http\Controllers\ModuloController::class)->names('admin.modulos')->middleware('auth');

Route::post('/admin/ciclos', [App\Http\Controllers\CicloBimestralController::class, 'store'])->name('admin.ciclos.store')->middleware('auth');
Route::put('/admin/ciclos/{id}', [App\Http\Controllers\CicloBimestralController::class, 'update'])->name('admin.ciclos.update')->middleware('auth');
Route::post('/admin/ciclos/{id}/activar', [App\Http\Controllers\CicloBimestralController::class, 'activar'])->name('admin.ciclos.activar')->middleware('auth');
Route::delete('/admin/ciclos/{id}', [App\Http\Controllers\CicloBimestralController::class, 'destroy'])->name('admin.ciclos.destroy')->middleware('auth');

//rutas que van a ser de Paralelos del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/paralelos', [App\Http\Controllers\ParaleloController::class, 'index'])->name('admin.paralelos.index')->middleware('auth','can:admin.paralelos.index');
Route::post('/admin/paralelos', [App\Http\Controllers\ParaleloController::class, 'store'])->name('admin.paralelos.store')->middleware('auth','can:admin.paralelos.create');
Route::post('/admin/paralelos/create', [App\Http\Controllers\ParaleloController::class, 'store'])->name('admin.paralelos.create')->middleware('auth','can:admin.paralelos.create');//Create
Route::put('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'update'])->name('admin.paralelos.update')->middleware('auth','can:admin.paralelos.update');//Update
Route::delete('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'destroy'])->name('admin.paralelos.destroy')->middleware('auth','can:admin.paralelos.destroy');//Delete






//rutas que van a ser de Personal del sitema CreateReadUpdateDelete
//trabajando con vistas
//rutas para el personal del sistema
Route::get('/admin/personal/{tipo?}', [App\Http\Controllers\PersonalController::class, 'index'])->name('admin.personal.index')->middleware('auth','can:admin.personal.index');//retorna la vista indexx
Route::get('/admin/personal/create/{tipo?}', [App\Http\Controllers\PersonalController::class, 'create'])->name('admin.personal.create')->middleware('auth','can:admin.personal.create');//retorna la vista create
Route::post('/admin/personal/create', [App\Http\Controllers\PersonalController::class, 'store'])->name('admin.personal.store')->middleware('auth','can:admin.personal.store');//Create
Route::get('/admin/personal/show/{id}', [App\Http\Controllers\PersonalController::class, 'show'])->name('admin.personal.show')->middleware('auth','can:admin.personal.show');//retorna la vista show
Route::get('/admin/personal/{id}/edit', [App\Http\Controllers\PersonalController::class, 'edit'])->name('admin.personal.edit')->middleware('auth','can:admin.personal.edit');//Read
Route::put('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'update'])->name('admin.personal.update')->middleware('auth','can:admin.personal.update');//Update
Route::delete('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'destroy'])->name('admin.personal.destroy')->middleware('auth','can:admin.personal.destroy');//Delete

//rutas para las formaciones 
Route::get('/admin/personal/{id}/formaciones', [App\Http\Controllers\FormacionController::class, 'index'])->name('admin.personal.formaciones.index')->middleware('auth','can:admin.personal.formaciones.index');//retorna la vista index
Route::get('/admin/personal/{id}/formaciones/create', [App\Http\Controllers\FormacionController::class, 'create'])->name('admin.personal.formaciones.create')->middleware('auth','can:admin.personal.formaciones.create');//retorna la vista create
Route::post('/admin/personal/{id}/formaciones/create', [App\Http\Controllers\FormacionController::class, 'store'])->name('admin.personal.formaciones.store')->middleware('auth','can:admin.personal.formaciones.store');//Create
Route::get('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'edit'])->name('admin.personal.formaciones.edit')->middleware('auth','can:admin.personal.formaciones.edit');//Edit vista
Route::put('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'update'])->name('admin.personal.formaciones.update')->middleware('auth','can:admin.personal.formaciones.update');//Edit vista
Route::delete('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'destroy'])->name('admin.personal.formaciones.destroy')->middleware('auth','can:admin.personal.formaciones.destroy');//Edit vista


// =========================================================================
// PAQUETE 4: PADRÓN DE ESTUDIANTES Y BOLETAS BANCARIAS
// =========================================================================
// CU-10: Padrón de Estudiantes
Route::get('/admin/estudiantes/buscar/ajax', [App\Http\Controllers\EstudianteController::class, 'buscar_estudiante_ajax'])->name('admin.estudiantes.buscar_ajax')->middleware('auth');
Route::get('/admin/estudiantes', [App\Http\Controllers\EstudianteController::class, 'index'])->name('admin.estudiantes.index')->middleware('auth', 'can:admin.estudiantes.index');
Route::get('/admin/estudiantes/create', [App\Http\Controllers\EstudianteController::class, 'create'])->name('admin.estudiantes.create')->middleware('auth', 'can:admin.estudiantes.create');
Route::post('/admin/estudiantes/create', [App\Http\Controllers\EstudianteController::class, 'store'])->name('admin.estudiantes.store')->middleware('auth', 'can:admin.estudiantes.store');
Route::get('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class, 'show'])->name('admin.estudiantes.show')->middleware('auth', 'can:admin.estudiantes.show');
Route::get('/admin/estudiantes/{id}/edit', [App\Http\Controllers\EstudianteController::class, 'edit'])->name('admin.estudiantes.edit')->middleware('auth', 'can:admin.estudiantes.edit');
Route::put('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class, 'update'])->name('admin.estudiantes.update')->middleware('auth', 'can:admin.estudiantes.update');
Route::delete('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class, 'destroy'])->name('admin.estudiantes.destroy')->middleware('auth', 'can:admin.estudiantes.destroy');

// CU-12: Kárdex Académico del Estudiante
Route::get('/admin/estudiantes/{id}/kardex', [App\Http\Controllers\EstudianteController::class, 'kardex'])->name('admin.estudiantes.kardex')->middleware('auth');
Route::get('/admin/estudiantes/{id}/kardex-pdf', [App\Http\Controllers\EstudianteController::class, 'kardex_pdf'])->name('admin.estudiantes.kardex_pdf')->middleware('auth');

// CU-11: Registro y Fiscalización de Boletas Bancarias
Route::get('/admin/boletas', [App\Http\Controllers\BoletaPagoController::class, 'index'])->name('admin.boletas.index')->middleware('auth');
Route::post('/admin/boletas', [App\Http\Controllers\BoletaPagoController::class, 'store'])->name('admin.boletas.store')->middleware('auth');
Route::post('/admin/boletas/{id}/anular', [App\Http\Controllers\BoletaPagoController::class, 'anular'])->name('admin.boletas.anular')->middleware('auth');
Route::delete('/admin/boletas/{id}', [App\Http\Controllers\BoletaPagoController::class, 'destroy'])->name('admin.boletas.destroy')->middleware('auth');
Route::get('/admin/boletas/disponibles/{estudiante_id}', [App\Http\Controllers\BoletaPagoController::class, 'boletas_disponibles'])->name('admin.boletas.disponibles')->middleware('auth');

// =========================================================================
// PAQUETE 5: INSCRIPCIONES Y MATRÍCULA MODULAR
// =========================================================================
Route::get('/admin/inscripciones', [App\Http\Controllers\InscripcionController::class, 'index'])->name('admin.inscripciones.index')->middleware('auth');
Route::get('/admin/inscripciones/create', [App\Http\Controllers\InscripcionController::class, 'create'])->name('admin.inscripciones.create')->middleware('auth');
Route::post('/admin/inscripciones/create', [App\Http\Controllers\InscripcionController::class, 'store'])->name('admin.inscripciones.store')->middleware('auth');
Route::get('/admin/inscripciones/anulaciones', [App\Http\Controllers\InscripcionController::class, 'anulaciones'])->name('admin.inscripciones.anulaciones')->middleware('auth');
Route::post('/admin/inscripciones/{id}/anular', [App\Http\Controllers\InscripcionController::class, 'anular'])->name('admin.inscripciones.anular')->middleware('auth');
Route::get('/admin/inscripciones/{id}/pdf', [App\Http\Controllers\InscripcionController::class, 'pdf_matricula'])->name('admin.inscripciones.pdf')->middleware('auth');
Route::get('/admin/inscripciones/verificar-prerrequisito', [App\Http\Controllers\InscripcionController::class, 'verificar_prerrequisito'])->name('admin.inscripciones.verificar_prerrequisito')->middleware('auth');

//ruta para registrar a padre de familia
Route::get('/admin/ppffs',[App\Http\Controllers\PpffController::class,'index'])->name('admin.ppffs.index')->middleware('auth','can:admin.ppffs.index');
Route::post('/admin/estudiantes/ppffs/create',[App\Http\Controllers\PpffController::class,'store'])->name('admin.estudiantes.ppffs.store')->middleware('auth','can:admin.ppffs.store');
Route::get('/admin/ppffs/create',[App\Http\Controllers\PpffController::class,'create'])->name('admin.ppffs.create')->middleware('auth','can:admin.ppffs.create');
Route::post('/admin/ppffs/create',[App\Http\Controllers\PpffController::class,'store_ppffs'])->name('admin.ppffs.store')->middleware('auth','can:admin.ppffs.store');
Route::get('/admin/ppffs/{id}',[App\Http\Controllers\PpffController::class,'show'])->name('admin.ppffs.show')->middleware('auth','can:admin.ppffs.show');
Route::get('/admin/ppffs/{id}/edit',[App\Http\Controllers\PpffController::class,'edit'])->name('admin.ppffs.edit')->middleware('auth','can:admin.ppffs.edit');
Route::put('/admin/ppffs/{id}',[App\Http\Controllers\PpffController::class,'update'])->name('admin.ppffs.update')->middleware('auth','can:admin.ppffs.update');
Route::delete('/admin/ppffs/{id}',[App\Http\Controllers\PpffController::class,'destroy'])->name('admin.ppffs.destroy')->middleware('auth','can:admin.ppffs.destroy');

//ruta para la ruta de matriculaciones
Route::get('/admin/matriculaciones',[App\Http\Controllers\MatriculacionController::class,'index'])->name('admin.matriculaciones.index')->middleware('auth','can:admin.matriculaciones.index');
Route::get('/admin/matriculaciones/create',[App\Http\Controllers\MatriculacionController::class,'create'])->name('admin.matriculaciones.create')->middleware('auth','can:admin.matriculaciones.create');
Route::post('/admin/matriculaciones/create',[App\Http\Controllers\MatriculacionController::class,'store'])->name('admin.matriculaciones.store')->middleware('auth','can:admin.matriculaciones.store');
Route::get('/admin/matriculaciones/buscar_estudiante/{id}',[App\Http\Controllers\MatriculacionController::class,'buscar_estudiante'])->name('admin.matriculaciones.buscar_estudiante')->middleware('auth','can:admin.matriculaciones.buscar_estudiante');
Route::get('/admin/matriculaciones/buscar_grado/{id}',[App\Http\Controllers\MatriculacionController::class,'buscar_grados'])->name('admin.matriculaciones.buscar_grados')->middleware('auth','can:admin.matriculaciones.buscar_grados');
Route::get('/admin/matriculaciones/buscar_paralelo/{id}',[App\Http\Controllers\MatriculacionController::class,'buscar_paralelos'])->name('admin.matriculaciones.buscar_paralelos')->middleware('auth','can:admin.matriculaciones.buscar_paralelos');
Route::get('/admin/matriculaciones/pdf/{id}',[App\Http\Controllers\MatriculacionController::class,'pdf_matricula'])->name('admin.matriculaciones.pdf_matricula')->middleware('auth','can:admin.matriculaciones.pdf_matricula');
Route::get('/admin/matriculaciones/{id}',[App\Http\Controllers\MatriculacionController::class,'show'])->name('admin.matriculaciones.show')->middleware('auth','can:admin.matriculaciones.show');
Route::get('/admin/matriculaciones/{id}/edit',[App\Http\Controllers\MatriculacionController::class,'edit'])->name('admin.matriculaciones.edit')->middleware('auth','can:admin.matriculaciones.edit');
Route::put('/admin/matriculaciones/{id}',[App\Http\Controllers\MatriculacionController::class,'update'])->name('admin.matriculaciones.update')->middleware('auth','can:admin.matriculaciones.update');
Route::delete('/admin/matriculaciones/{id}',[App\Http\Controllers\MatriculacionController::class,'destroy'])->name('admin.matriculaciones.destroy')->middleware('auth','can:admin.matriculaciones.destroy');


//rutas para asignacion de materias de los docentes
Route::get('/admin/asignaciones', [App\Http\Controllers\AsignacionController::class, 'index'])->name('admin.asignaciones.index')->middleware('auth','can:admin.asignaciones.index');
Route::get('/admin/asignaciones/create', [App\Http\Controllers\AsignacionController::class, 'create'])->name('admin.asignaciones.create')->middleware('auth','can:admin.asignaciones.create');
Route::post('/admin/asignaciones/create', [App\Http\Controllers\AsignacionController::class, 'store'])->name('admin.asignaciones.store')->middleware('auth','can:admin.asignaciones.store');
Route::get('/admin/asignaciones/buscar_docente/{id}',[App\Http\Controllers\AsignacionController::class,'buscar_docente'])->name('admin.matriculaciones.buscar_docente')->middleware('auth','can:admin.matriculaciones.buscar_docente');
Route::get('/admin/asignaciones/{id}', [App\Http\Controllers\AsignacionController::class, 'show'])->name('admin.asignaciones.show')->middleware('auth','can:admin.asignaciones.show');
Route::get('/admin/asignaciones/{id}/edit', [App\Http\Controllers\AsignacionController::class, 'edit'])->name('admin.asignaciones.edit')->middleware('auth','can:admin.asignaciones.edit');
Route::put('/admin/asignaciones/{id}', [App\Http\Controllers\AsignacionController::class, 'update'])->name('admin.asignaciones.update')->middleware('auth','can:admin.asignaciones.update');
Route::delete('/admin/asignaciones/{id}', [App\Http\Controllers\AsignacionController::class, 'destroy'])->name('admin.asignaciones.destroy')->middleware('auth','can:admin.asignaciones.destroy');

//rutas para los pagos
Route::get('/admin/pagos', [App\Http\Controllers\PagoController::class, 'index'])->name('admin.pagos.index')->middleware('auth','can:admin.pagos.index');
Route::get('/admin/pagos/estudiante/{id}', [App\Http\Controllers\PagoController::class, 'ver_pagos'])->name('admin.pagos.ver_pagos')->middleware('auth','can:admin.pagos.ver_pagos');
Route::post('/admin/pagos/create', [App\Http\Controllers\PagoController::class, 'store'])->name('admin.pagos.store')->middleware('auth','can:admin.pagos.store');
Route::get('/admin/pagos/{id}/comprobante', [App\Http\Controllers\PagoController::class, 'comprobante'])->name('admin.pagos.comprobante')->middleware('auth','can:admin.pagos.comprobante');
Route::delete('/admin/pagos/{id}', [App\Http\Controllers\PagoController::class, 'destroy'])->name('admin.pagos.destroy')->middleware('auth','can:admin.pagos.destroy');

//rutas para las asistencias
Route::get('/admin/asistencias', [App\Http\Controllers\AsistenciaController::class, 'index'])->name('admin.asistencias.index')->middleware('auth','can:admin.asistencias.index');
Route::get('/admin/asistencias/create/asignacion/{id}', [App\Http\Controllers\AsistenciaController::class, 'create'])->name('admin.asistencias.create')->middleware('auth','can:admin.asistencias.create');
Route::get('/admin/asistencias/asignacion/{id}', [App\Http\Controllers\AsistenciaController::class, 'show'])->name('admin.asistencias.show')->middleware('auth','can:admin.asistencias.show');
Route::get('/admin/asistencias/detalle/asignacion/{id_asignacion}/estudiante/{id_estudiante}', [App\Http\Controllers\AsistenciaController::class, 'show_estudiante'])->name('admin.asistencias.show_estudiante')->middleware('auth','can:admin.asistencias.show_estudiante');
Route::post('/admin/asistencias/create', [App\Http\Controllers\AsistenciaController::class, 'store'])->name('admin.asistencias.store')->middleware('auth','can:admin.asistencias.store');
Route::put('/admin/asistencias/{id}', [App\Http\Controllers\AsistenciaController::class, 'update'])->name('admin.asistencias.update')->middleware('auth', 'can:admin.asistencias.update');//
Route::delete('/admin/asistencias/{id}', [App\Http\Controllers\AsistenciaController::class, 'destroy'])->name('admin.asistencias.destroy')->middleware('auth', 'can:admin.asistencias.destroy');//

//rutas para las calificaciones
Route::get('/admin/calificaciones', [App\Http\Controllers\CalificacionController::class, 'index'])->name('admin.calificaciones.index')->middleware('auth','can:admin.calificaciones.index');
Route::get('/admin/calificaciones/create/asignacion/{id}', [App\Http\Controllers\CalificacionController::class, 'create'])->name('admin.calificaciones.create')->middleware('auth','can:admin.calificaciones.create');
Route::post('/admin/calificaciones/create', [App\Http\Controllers\CalificacionController::class, 'store'])->name('admin.calificaciones.store')->middleware('auth','can:admin.calificaciones.store');
Route::put('/admin/calificaciones/{id}', [App\Http\Controllers\CalificacionController::class, 'update'])->name('admin.calificaciones.update')->middleware('auth','can:admin.calificaciones.update');
Route::get('/admin/calificaciones/detalle/asignacion/{id_asignacion}/estudiante/{id_estudiante}', [App\Http\Controllers\CalificacionController::class, 'show_estudiante'])->name('admin.calificaciones.show_estudiante')->middleware('auth','can:admin.calificaciones.show_estudiante');
Route::get('/admin/calificaciones/asignacion/{id}', [App\Http\Controllers\CalificacionController::class, 'show_admin'])->name('admin.calificaciones.show_admin')->middleware('auth','can:admin.calificaciones.show_admin');
Route::delete('/admin/calificaciones/{id}', [App\Http\Controllers\CalificacionController::class, 'destroy'])->name('admin.calificaciones.destroy')->middleware('auth','can:admin.calificaciones.destroy');

// =========================================================================
// PAQUETE 6: EVALUACIÓN Y CALIFICACIONES (DOCENTE)
// =========================================================================
Route::get('/docente/grupos', [App\Http\Controllers\DocenteGrupoController::class, 'index'])->name('docente.grupos.index')->middleware('auth');
Route::get('/docente/grupos/{id}', [App\Http\Controllers\DocenteGrupoController::class, 'show'])->name('docente.grupos.show')->middleware('auth');
Route::get('/docente/calificaciones', [App\Http\Controllers\DocenteCalificacionController::class, 'index'])->name('docente.calificaciones.index')->middleware('auth');
Route::get('/docente/calificaciones/grupo/{paralelo_id}', [App\Http\Controllers\DocenteCalificacionController::class, 'cargar_notas'])->name('docente.calificaciones.cargar')->middleware('auth');
Route::post('/docente/calificaciones/guardar-lote', [App\Http\Controllers\DocenteCalificacionController::class, 'guardar_lote'])->name('docente.calificaciones.guardar_lote')->middleware('auth');
Route::get('/docente/actas', [App\Http\Controllers\DocenteCalificacionController::class, 'actas_index'])->name('docente.actas.index')->middleware('auth');
Route::get('/docente/actas/grupo/{paralelo_id}/pdf', [App\Http\Controllers\DocenteCalificacionController::class, 'acta_pdf'])->name('docente.actas.pdf')->middleware('auth');

// =========================================================================
// PAQUETE 7: PORTAL DEL ESTUDIANTE (AUTOGESTIÓN)
// =========================================================================
Route::get('/estudiante/modulo-activo', [App\Http\Controllers\EstudiantePortalController::class, 'modulo_activo'])->name('estudiante.clases.index')->middleware('auth');
Route::get('/estudiante/avance-curricular', [App\Http\Controllers\EstudiantePortalController::class, 'avance_curricular'])->name('estudiante.avance.index')->middleware('auth');
Route::get('/estudiante/mis-documentos', [App\Http\Controllers\EstudiantePortalController::class, 'mis_documentos'])->name('estudiante.documentos.index')->middleware('auth');
Route::get('/estudiante/mis-documentos/certificado/{tipo}', [App\Http\Controllers\EstudiantePortalController::class, 'certificado_pdf'])->name('estudiante.documentos.certificado')->middleware('auth');

// =========================================================================
// PAQUETE 8: REPORTES Y CONCILIACIÓN GERENCIAL
// =========================================================================
Route::get('/admin/reportes/conciliacion-boletas', [App\Http\Controllers\ReporteController::class, 'conciliacion_boletas'])->name('admin.reportes.conciliacion')->middleware('auth');
Route::get('/admin/reportes/conciliacion-boletas/pdf', [App\Http\Controllers\ReporteController::class, 'conciliacion_boletas_pdf'])->name('admin.reportes.conciliacion_pdf')->middleware('auth');
Route::get('/admin/reportes/inscritos-modalidad', [App\Http\Controllers\ReporteController::class, 'inscritos_modalidad'])->name('admin.reportes.inscritos')->middleware('auth');
Route::get('/admin/reportes/inscritos-modalidad/pdf', [App\Http\Controllers\ReporteController::class, 'inscritos_modalidad_pdf'])->name('admin.reportes.inscritos_pdf')->middleware('auth');
Route::get('/admin/reportes/estudiantes-graduados', [App\Http\Controllers\ReporteController::class, 'estudiantes_graduados'])->name('admin.reportes.graduados')->middleware('auth');
Route::get('/admin/reportes/estudiantes-graduados/pdf', [App\Http\Controllers\ReporteController::class, 'estudiantes_graduados_pdf'])->name('admin.reportes.graduados_pdf')->middleware('auth');




