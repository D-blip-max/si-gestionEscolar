<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register'=>false]);

Route::get('/home', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index.home')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');

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


//rutas que van a ser de Periodos del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/periodos', [App\Http\Controllers\PeriodoController::class, 'index'])->name('admin.periodos.index')->middleware('auth','can:admin.periodos.index');
Route::post('/admin/periodos/create', [App\Http\Controllers\PeriodoController::class, 'store'])->name('admin.periodos.create')->middleware('auth','can:admin.periodos.create');//Create
Route::put('/admin/periodos/{id}', [App\Http\Controllers\PeriodoController::class, 'update'])->name('admin.periodos.update')->middleware('auth','can:admin.periodos.update');//Update
Route::delete('/admin/periodos/{id}', [App\Http\Controllers\PeriodoController::class, 'destroy'])->name('admin.periodos.destroy')->middleware('auth','can:admin.periodos.destroy');//Delete


//rutas que van a ser de niveles del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/niveles', [App\Http\Controllers\NivelController::class, 'index'])->name('admin.niveles.index')->middleware('auth','can:admin.niveles.index');
Route::post('/admin/niveles/create', [App\Http\Controllers\NivelController::class, 'store'])->name('admin.niveles.create')->middleware('auth','can:admin.niveles.create');//Create
Route::put('/admin/niveles/{id}', [App\Http\Controllers\NivelController::class, 'update'])->name('admin.niveles.update')->middleware('auth','can:admin.niveles.update');//Update
Route::delete('/admin/niveles/{id}', [App\Http\Controllers\NivelController::class, 'destroy'])->name('admin.niveles.destroy')->middleware('auth','can:admin.niveles.destroy');//Delete


//rutas que van a ser de grados del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/grados', [App\Http\Controllers\GradoController::class, 'index'])->name('admin.grados.index')->middleware('auth','can:admin.grados.index');
Route::post('/admin/grados/create', [App\Http\Controllers\GradoController::class, 'store'])->name('admin.grados.create')->middleware('auth','can:admin.grados.create');//Create
Route::put('/admin/grados/{id}', [App\Http\Controllers\GradoController::class, 'update'])->name('admin.grados.update')->middleware('auth','can:admin.grados.update');//Update
Route::delete('/admin/grados/{id}', [App\Http\Controllers\GradoController::class, 'destroy'])->name('admin.grados.destroy')->middleware('auth','can:admin.grados.destroy');//Delete


//rutas que van a ser de Paralelos del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/paralelos', [App\Http\Controllers\ParaleloController::class, 'index'])->name('admin.paralelos.index')->middleware('auth','can:admin.paralelos.index');
Route::post('/admin/paralelos/create', [App\Http\Controllers\ParaleloController::class, 'store'])->name('admin.paralelos.create')->middleware('auth','can:admin.paralelos.create');//Create
Route::put('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'update'])->name('admin.paralelos.update')->middleware('auth','can:admin.paralelos.update');//Update
Route::delete('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'destroy'])->name('admin.paralelos.destroy')->middleware('auth','can:admin.paralelos.destroy');//Delete


//rutas que van a ser de Turnos del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/turnos', [App\Http\Controllers\TurnoController::class, 'index'])->name('admin.turnos.index')->middleware('auth','can:admin.turnos.index');
Route::get('/admin/turnos/create', [App\Http\Controllers\TurnoController::class, 'create'])->name('admin.turnos.create')->middleware('auth','can:admin.turnos.create');//retorna la vista
Route::post('/admin/turnos/create', [App\Http\Controllers\TurnoController::class, 'store'])->name('admin.turnos.store')->middleware('auth','can:admin.turnos.store');//Create
Route::get('/admin/turnos/{id}/edit', [App\Http\Controllers\TurnoController::class, 'edit'])->name('admin.turnos.edit')->middleware('auth','can:admin.turnos.edit');//Read
Route::put('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'update'])->name('admin.turnos.update')->middleware('auth','can:admin.turnos.update');//Update
Route::delete('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'destroy'])->name('admin.turnos.destroy')->middleware('auth','can:admin.turnos.destroy');//Delete


//rutas que van a ser de materias del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/materias', [App\Http\Controllers\MateriaController::class, 'index'])->name('admin.materias.index')->middleware('auth','can:admin.materias.index');
Route::post('/admin/materias/create', [App\Http\Controllers\MateriaController::class, 'store'])->name('admin.materias.create')->middleware('auth','can:admin.materias.create');//Create
Route::put('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'update'])->name('admin.materias.update')->middleware('auth','can:admin.materias.update');//Update
Route::delete('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'destroy'])->name('admin.materias.destroy')->middleware('auth','can:admin.materias.destroy');//Delete


//rutas que van a ser de Roles del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth','can:admin.roles.index');
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth','can:admin.roles.create');//retorna la vista
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth','can:admin.roles.store');//Create
Route::get('/admin/roles/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth','can:admin.roles.edit');//Read
Route::put('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth','can:admin.roles.update');//Update
Route::delete('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth','can:admin.roles.destroy');//Delete
//el metodo que da permisos
Route::get('/admin/roles/{id}/permisos', [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth','can:admin.roles.permisos');//el que da permisos che
Route::post('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'update_permisos'])->name('admin.roles.update_permisos')->middleware('auth','can:admin.roles.update_permisos');//el que da permisos che


//rutas que van a ser de Personal del sitema CreateReadUpdateDelete
//trabajando con vistas
//rutas para el personal del sistema
Route::get('/admin/personal/{tipo}', [App\Http\Controllers\PersonalController::class, 'index'])->name('admin.personal.index')->middleware('auth','can:admin.personal.index');//retorna la vista indexx
Route::get('/admin/personal/create/{tipo}', [App\Http\Controllers\PersonalController::class, 'create'])->name('admin.personal.create')->middleware('auth','can:admin.personal.create');//retorna la vista create
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


//rutas que van a ser de Estudiantes del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/estudiantes/', [App\Http\Controllers\EstudianteController::class,'index'])->name('admin.estudiantes.index')->middleware('auth','can:admin.estudiantes.index');
Route::get('/admin/estudiantes/create', [App\Http\Controllers\EstudianteController::class,'create'])->name('admin.estudiantes.create')->middleware('auth','can:admin.estudiantes.create');
Route::post('/admin/estudiantes/create', [App\Http\Controllers\EstudianteController::class,'store'])->name('admin.estudiantes.store')->middleware('auth','can:admin.estudiantes.store');
Route::get('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class,'show'])->name('admin.estudiantes.show')->middleware('auth','can:admin.estudiantes.show');
Route::get('/admin/estudiantes/{id}/edit', [App\Http\Controllers\EstudianteController::class,'edit'])->name('admin.estudiantes.edit')->middleware('auth','can:admin.estudiantes.edit');
Route::put('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class,'update'])->name('admin.estudiantes.update')->middleware('auth','can:admin.estudiantes.update');
Route::delete('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class,'destroy'])->name('admin.estudiantes.destroy')->middleware('auth','can:admin.estudiantes.destroy');

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
Route::post('/admin/asignaciones/create', [App\Http\Controllers\AsignacionController::class, 'store'])->name('admin.asignaciones.store')->middleware('auth','admin.asignaciones.store');
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
Route::get('/admin/asistencias/asignacion/{id}', [App\Http\Controllers\AsistenciaController::class, 'show'])->name('admin.asistencias.show')->middleware('auth');//,'can:admin.asistencias.show');
Route::post('/admin/asistencias/create', [App\Http\Controllers\AsistenciaController::class, 'store'])->name('admin.asistencias.store')->middleware('auth','can:admin.asistencias.store');
Route::put('/admin/asistencias/{id}', [App\Http\Controllers\AsistenciaController::class, 'update'])->name('admin.asistencias.update')->middleware('auth', 'can:admin.asistencias.update');//
Route::delete('/admin/asistencias/{id}', [App\Http\Controllers\AsistenciaController::class, 'destroy'])->name('admin.asistencias.destroy')->middleware('auth', 'can:admin.asistencias.destroy');//





