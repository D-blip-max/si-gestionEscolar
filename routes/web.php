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
Route::post('/admin/gestiones/create', [App\Http\Controllers\GestionController::class, 'store'])->name('admin.gestiones.create')->middleware('auth');//Create
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
Route::post('/admin/turnos/create', [App\Http\Controllers\TurnoController::class, 'store'])->name('admin.turnos.create')->middleware('auth');//Create
Route::get('/admin/turnos/{id}/edit', [App\Http\Controllers\TurnoController::class, 'edit'])->name('admin.turnos.edit')->middleware('auth');//Read
Route::put('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'update'])->name('admin.turnos.update')->middleware('auth');//Update
Route::delete('/admin/turnos/{id}', [App\Http\Controllers\TurnoController::class, 'destroy'])->name('admin.turnos.destroy')->middleware('auth');//Delete


//rutas que van a ser de materias del sitema CreateReadUpdateDelete
//Trabajando con Modals
Route::get('/admin/materias', [App\Http\Controllers\MateriaController::class, 'index'])->name('admin.materias.index')->middleware('auth');
Route::post('/admin/materias/create', [App\Http\Controllers\MateriaController::class, 'store'])->name('admin.materias.create')->middleware('auth');//Create
Route::put('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'update'])->name('admin.materias.update')->middleware('auth');//Update
Route::delete('/admin/materias/{id}', [App\Http\Controllers\MateriaController::class, 'destroy'])->name('admin.materias.destroy')->middleware('auth');//Delete
