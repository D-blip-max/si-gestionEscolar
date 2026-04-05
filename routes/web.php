<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register'=>false]);

Route::get('/home', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index.home')->middleware('auth');
Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index')->middleware('auth');

//rutas que van a ser de configuracion del sistema
Route::get('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])->name('admin.configuracion.index')->middleware('auth');
Route::post('/admin/configuracion/create', [App\Http\Controllers\ConfiguracionController::class, 'store'])->name('admin.configuracion.crear')->middleware('auth');


//rutas que van a ser de gestiones del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/gestiones', [App\Http\Controllers\GestionController::class, 'index'])->name('admin.gestiones.index')->middleware('auth');
Route::get('/admin/gestiones/create', [App\Http\Controllers\GestionController::class, 'create'])->name('admin.gestiones.create')->middleware('auth');//retorna la vista
Route::post('/admin/gestiones/create', [App\Http\Controllers\GestionController::class, 'store'])->name('admin.store.create')->middleware('auth');//Create
Route::get('/admin/gestiones/{id}/edit', [App\Http\Controllers\GestionController::class, 'edit'])->name('admin.gestiones.edit')->middleware('auth');//Read
Route::put('/admin/gestiones/{id}', [App\Http\Controllers\GestionController::class, 'update'])->name('admin.gestiones.update')->middleware('auth');//Update
Route::delete('/admin/gestiones/{id}', [App\Http\Controllers\GestionController::class, 'destroy'])->name('admin.gestiones.destroy')->middleware('auth');//Delete


//rutas que van a ser de Periodos del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/periodos', [App\Http\Controllers\PeriodoController::class, 'index'])->name('admin.periodos.index')->middleware('auth');
Route::post('/admin/periodos/create', [App\Http\Controllers\PeriodoController::class, 'store'])->name('admin.periodos.create')->middleware('auth');//Create
Route::put('/admin/periodos/{id}', [App\Http\Controllers\PeriodoController::class, 'update'])->name('admin.periodos.update')->middleware('auth');//Update
Route::delete('/admin/periodos/{id}', [App\Http\Controllers\PeriodoController::class, 'destroy'])->name('admin.periodos.destroy')->middleware('auth');//Delete


//rutas que van a ser de niveles del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/niveles', [App\Http\Controllers\NivelController::class, 'index'])->name('admin.niveles.index')->middleware('auth');
Route::post('/admin/niveles/create', [App\Http\Controllers\NivelController::class, 'store'])->name('admin.niveles.create')->middleware('auth');//Create
Route::put('/admin/niveles/{id}', [App\Http\Controllers\NivelController::class, 'update'])->name('admin.niveles.update')->middleware('auth');//Update
Route::delete('/admin/niveles/{id}', [App\Http\Controllers\NivelController::class, 'destroy'])->name('admin.niveles.destroy')->middleware('auth');//Delete


//rutas que van a ser de grados del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/grados', [App\Http\Controllers\GradoController::class, 'index'])->name('admin.grados.index')->middleware('auth');
Route::post('/admin/grados/create', [App\Http\Controllers\GradoController::class, 'store'])->name('admin.grados.create')->middleware('auth');//Create
Route::put('/admin/grados/{id}', [App\Http\Controllers\GradoController::class, 'update'])->name('admin.grados.update')->middleware('auth');//Update
Route::delete('/admin/grados/{id}', [App\Http\Controllers\GradoController::class, 'destroy'])->name('admin.grados.destroy')->middleware('auth');//Delete


//rutas que van a ser de Paralelos del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/paralelos', [App\Http\Controllers\ParaleloController::class, 'index'])->name('admin.paralelos.index')->middleware('auth');
Route::post('/admin/paralelos/create', [App\Http\Controllers\ParaleloController::class, 'store'])->name('admin.paralelos.create')->middleware('auth');//Create
Route::put('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'update'])->name('admin.paralelos.update')->middleware('auth');//Update
Route::delete('/admin/paralelos/{id}', [App\Http\Controllers\ParaleloController::class, 'destroy'])->name('admin.paralelos.destroy')->middleware('auth');//Delete


//rutas que van a ser de Turnos del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/turnos', [App\Http\Controllers\TurnoController::class, 'index'])->name('admin.turnos.index')->middleware('auth');
Route::get('/admin/turnos/create', [App\Http\Controllers\TurnoController::class, 'create'])->name('admin.turnos.create')->middleware('auth');//retorna la vista
Route::post('/admin/turnos/create', [App\Http\Controllers\TurnoController::class, 'store'])->name('admin.turnos.store')->middleware('auth');//Create
Route::get('/admin/turnos/{id}/edit', [App\Http\Controllers\TurnoController::class, 'edit'])->name('admin.turnos.edit')->middleware('auth');//Read
Route::put('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'update'])->name('admin.turnos.update')->middleware('auth');//Update
Route::delete('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'destroy'])->name('admin.turnos.destroy')->middleware('auth');//Delete


//rutas que van a ser de materias del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/materias', [App\Http\Controllers\MateriaController::class, 'index'])->name('admin.materias.index')->middleware('auth');
Route::post('/admin/materias/create', [App\Http\Controllers\MateriaController::class, 'store'])->name('admin.materias.create')->middleware('auth');//Create
Route::put('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'update'])->name('admin.materias.update')->middleware('auth');//Update
Route::delete('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'destroy'])->name('admin.materias.destroy')->middleware('auth');//Delete


//rutas que van a ser de Roles del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware('auth');
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware('auth');//retorna la vista
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware('auth');//Create
Route::get('/admin/roles/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('auth');//Read
Route::put('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware('auth');//Update
Route::delete('/admin/roles/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('auth');//Delete
//el metodo que da permisos
Route::get('/admin/roles/permisos/{id}', [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware('auth');//el que da permisos che


//rutas que van a ser de Personal del sitema CreateReadUpdateDelete
//trabajando con vistas
//rutas para el personal del sistema
Route::get('/admin/personal/{tipo}', [App\Http\Controllers\PersonalController::class, 'index'])->name('admin.personal.index')->middleware('auth');//retorna la vista indexx
Route::get('/admin/personal/create/{tipo}', [App\Http\Controllers\PersonalController::class, 'create'])->name('admin.personal.create')->middleware('auth');//retorna la vista create
Route::post('/admin/personal/create', [App\Http\Controllers\PersonalController::class, 'store'])->name('admin.personal.store')->middleware('auth');//Create
Route::get('/admin/personal/show/{id}', [App\Http\Controllers\PersonalController::class, 'show'])->name('admin.personal.show')->middleware('auth');//retorna la vista show
Route::get('/admin/personal/{id}/edit', [App\Http\Controllers\PersonalController::class, 'edit'])->name('admin.personal.edit')->middleware('auth');//Read
Route::put('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'update'])->name('admin.personal.update')->middleware('auth');//Update
Route::delete('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'destroy'])->name('admin.personal.destroy')->middleware('auth');//Delete

//rutas para las formaciones 
Route::get('/admin/personal/{id}/formaciones', [App\Http\Controllers\FormacionController::class, 'index'])->name('admin.personal.formaciones.index')->middleware('auth');//retorna la vista index
Route::get('/admin/personal/{id}/formaciones/create', [App\Http\Controllers\FormacionController::class, 'create'])->name('admin.personal.formaciones.create')->middleware('auth');//retorna la vista create
Route::post('/admin/personal/{id}/formaciones/create', [App\Http\Controllers\FormacionController::class, 'store'])->name('admin.personal.formaciones.store')->middleware('auth');//Create
Route::get('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'edit'])->name('admin.personal.formaciones.edit')->middleware('auth');//Edit vista
Route::put('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'update'])->name('admin.personal.formaciones.update')->middleware('auth');//Edit vista
Route::delete('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'destroy'])->name('admin.personal.formaciones.destroy')->middleware('auth');//Edit vista


//rutas que van a ser de Estudiantes del sitema CreateReadUpdateDelete
//trabajando con vistas
Route::get('/admin/estudiantes/', [App\Http\Controllers\EstudianteController::class,'index'])->name('admin.estudiantes.index')->middleware('auth');
Route::get('/admin/estudiantes/create', [App\Http\Controllers\EstudianteController::class,'create'])->name('admin.estudiantes.create')->middleware('auth');
Route::post('/admin/estudiantes/create', [App\Http\Controllers\EstudianteController::class,'store'])->name('admin.estudiantes.store')->middleware('auth');
Route::get('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class,'show'])->name('admin.estudiantes.show')->middleware('auth');
Route::get('/admin/estudiantes/{id}/edit', [App\Http\Controllers\EstudianteController::class,'edit'])->name('admin.estudiantes.edit')->middleware('auth');
Route::put('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class,'update'])->name('admin.estudiantes.update')->middleware('auth');
Route::delete('/admin/estudiantes/{id}', [App\Http\Controllers\EstudianteController::class,'destroy'])->name('admin.estudiantes.destroy')->middleware('auth');

//ruta para registrar a padre de familia
Route::get('/admin/ppffs',[App\Http\Controllers\PpffController::class,'index'])->name('admin.ppffs.index')->middleware('auth');
Route::post('/admin/estudiantes/ppffs/create',[App\Http\Controllers\PpffController::class,'store'])->name('admin.estudiantes.ppffs.store')->middleware('auth');
Route::get('/admin/ppffs/create',[App\Http\Controllers\PpffController::class,'create'])->name('admin.ppffs.create')->middleware('auth');
Route::post('/admin/ppffs/create',[App\Http\Controllers\PpffController::class,'store_ppffs'])->name('admin.ppffs.store')->middleware('auth');
Route::get('/admin/ppffs/{id}',[App\Http\Controllers\PpffController::class,'show'])->name('admin.ppffs.show')->middleware('auth');
Route::get('/admin/ppffs/{id}/edit',[App\Http\Controllers\PpffController::class,'edit'])->name('admin.ppffs.edit')->middleware('auth');
Route::put('/admin/ppffs/{id}',[App\Http\Controllers\PpffController::class,'update'])->name('admin.ppffs.update')->middleware('auth');
Route::delete('/admin/ppffs/{id}',[App\Http\Controllers\PpffController::class,'destroy'])->name('admin.ppffs.destroy')->middleware('auth');

//ruta para la ruta de matriculaciones
Route::get('/admin/matriculaciones',[App\Http\Controllers\MatriculacionController::class,'index'])->name('admin.matriculaciones.index')->middleware('auth');
Route::get('/admin/matriculaciones/create',[App\Http\Controllers\MatriculacionController::class,'create'])->name('admin.matriculaciones.create')->middleware('auth');
Route::post('/admin/matriculaciones/create',[App\Http\Controllers\MatriculacionController::class,'store'])->name('admin.matriculaciones.store')->middleware('auth');
Route::get('/admin/matriculaciones/buscar_estudiante/{id}',[App\Http\Controllers\MatriculacionController::class,'buscar_estudiante'])->name('admin.matriculaciones.buscar_estudiante')->middleware('auth');
Route::get('/admin/matriculaciones/buscar_grado/{id}',[App\Http\Controllers\MatriculacionController::class,'buscar_grados'])->name('admin.matriculaciones.buscar_grados')->middleware('auth');
Route::get('/admin/matriculaciones/buscar_paralelo/{id}',[App\Http\Controllers\MatriculacionController::class,'buscar_paralelos'])->name('admin.matriculaciones.buscar_paralelos')->middleware('auth');
Route::get('/admin/matriculaciones/pdf/{id}',[App\Http\Controllers\MatriculacionController::class,'pdf_matricula'])->name('admin.matriculaciones.pdf_matricula')->middleware('auth');
Route::get('/admin/matriculaciones/{id}',[App\Http\Controllers\MatriculacionController::class,'show'])->name('admin.matriculaciones.show')->middleware('auth');
Route::get('/admin/matriculaciones/{id}/edit',[App\Http\Controllers\MatriculacionController::class,'edit'])->name('admin.matriculaciones.edit')->middleware('auth');
Route::put('/admin/matriculaciones/{id}',[App\Http\Controllers\MatriculacionController::class,'update'])->name('admin.matriculaciones.update')->middleware('auth');
Route::delete('/admin/matriculaciones/{id}',[App\Http\Controllers\MatriculacionController::class,'destroy'])->name('admin.matriculaciones.destroy')->middleware('auth');


//rutas para asignacion de materias de los docentes
Route::get('/admin/asignaciones', [App\Http\Controllers\AsignacionController::class, 'index'])->name('admin.asignaciones.index')->middleware('auth');
Route::get('/admin/asignaciones/create', [App\Http\Controllers\AsignacionController::class, 'create'])->name('admin.asignaciones.create')->middleware('auth');
Route::post('/admin/asignaciones/create', [App\Http\Controllers\AsignacionController::class, 'store'])->name('admin.asignaciones.store')->middleware('auth');
Route::get('/admin/asignaciones/buscar_docente/{id}',[App\Http\Controllers\AsignacionController::class,'buscar_docente'])->name('admin.matriculaciones.buscar_docente')->middleware('auth');
Route::get('/admin/asignaciones/{id}', [App\Http\Controllers\AsignacionController::class, 'show'])->name('admin.asignaciones.show')->middleware('auth');
Route::get('/admin/asignaciones/{id}/edit', [App\Http\Controllers\AsignacionController::class, 'edit'])->name('admin.asignaciones.edit')->middleware('auth');
Route::put('/admin/asignaciones/{id}', [App\Http\Controllers\AsignacionController::class, 'update'])->name('admin.asignaciones.update')->middleware('auth');
Route::delete('/admin/asignaciones/{id}', [App\Http\Controllers\AsignacionController::class, 'destroy'])->name('admin.asignaciones.destroy')->middleware('auth');




