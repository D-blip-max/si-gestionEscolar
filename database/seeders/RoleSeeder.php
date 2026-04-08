<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = Role::create(['name' => 'ADMINISTRADOR']);
        $director_general = Role::create(['name' => 'DIRECTOR/A GENERAL']);
        $director_academico = Role::create(['name' => 'DIRECTOR/A ACADÉMICO']);
        $director_administrativo = Role::create(['name' => 'DIRECTOR/A ADMINISTRATIVO']);
        $docente = Role::create(['name' => 'DOCENTE']);
        $estudiante = Role::create(['name' => 'ESTUDIANTE']);
        $caja = Role::create(['name' => 'CAJERO/A']);
        $secretaria = Role::create(['name' => 'SECRETARIO/A']);
        $regente = Role::create(['name' => 'REGENTE']);

        // --- PERMISOS PARA LA CONFIGURACIÓN DEL SISTEMA ---
        Permission::create(['name' => 'admin.configuracion.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.configuracion.crear'])->syncRoles($admin);

        // --- PERMISOS PARA LAS GESTIONES DEL SISTEMA ---
        Permission::create(['name' => 'admin.gestiones.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.gestiones.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.gestiones.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.gestiones.edit'])->syncRoles($admin);
        Permission::create(['name' => 'admin.gestiones.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.gestiones.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA PERIODOS ---
        Permission::create(['name' => 'admin.periodos.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.periodos.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.periodos.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.periodos.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA NIVELES ---
        Permission::create(['name' => 'admin.niveles.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.niveles.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.niveles.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.niveles.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA GRADOS ---
        Permission::create(['name' => 'admin.grados.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.grados.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.grados.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.grados.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA PARALELOS ---
        Permission::create(['name' => 'admin.paralelos.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.paralelos.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.paralelos.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.paralelos.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA TURNOS ---
        Permission::create(['name' => 'admin.turnos.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.turnos.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.turnos.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.turnos.edit'])->syncRoles($admin);
        Permission::create(['name' => 'admin.turnos.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.turnos.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA MATERIAS ---
        Permission::create(['name' => 'admin.materias.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.materias.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.materias.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.materias.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA ROLES ---
        Permission::create(['name' => 'admin.roles.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.roles.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.roles.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.roles.edit'])->syncRoles($admin);
        Permission::create(['name' => 'admin.roles.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.roles.destroy'])->syncRoles($admin);
        Permission::create(['name' => 'admin.roles.permisos'])->syncRoles($admin);
        Permission::create(['name' => 'admin.roles.update_permisos'])->syncRoles($admin);

        // --- PERMISOS PARA PERSONAL ---
        Permission::create(['name' => 'admin.personal.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.show'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.edit'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA FORMACIONES DEL PERSONAL ---
        Permission::create(['name' => 'admin.personal.formaciones.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.formaciones.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.formaciones.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.formaciones.edit'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.formaciones.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.personal.formaciones.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA ESTUDIANTES ---
        Permission::create(['name' => 'admin.estudiantes.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.estudiantes.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.estudiantes.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.estudiantes.show'])->syncRoles($admin);
        Permission::create(['name' => 'admin.estudiantes.edit'])->syncRoles($admin);
        Permission::create(['name' => 'admin.estudiantes.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.estudiantes.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA PADRES DE FAMILIA (PPFF) ---
        Permission::create(['name' => 'admin.ppffs.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.estudiantes.ppffs.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.ppffs.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.ppffs.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.ppffs.show'])->syncRoles($admin);
        Permission::create(['name' => 'admin.ppffs.edit'])->syncRoles($admin);
        Permission::create(['name' => 'admin.ppffs.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.ppffs.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA MATRICULACIONES ---
        Permission::create(['name' => 'admin.matriculaciones.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.buscar_estudiante'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.buscar_grados'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.buscar_paralelos'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.pdf_matricula'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.show'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.edit'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA ASIGNACIONES ---
        Permission::create(['name' => 'admin.asignaciones.index'])->syncRoles($admin);
        Permission::create(['name' => 'admin.asignaciones.create'])->syncRoles($admin);
        Permission::create(['name' => 'admin.asignaciones.store'])->syncRoles($admin);
        Permission::create(['name' => 'admin.matriculaciones.buscar_docente'])->syncRoles($admin);
        Permission::create(['name' => 'admin.asignaciones.show'])->syncRoles($admin);
        Permission::create(['name' => 'admin.asignaciones.edit'])->syncRoles($admin);
        Permission::create(['name' => 'admin.asignaciones.update'])->syncRoles($admin);
        Permission::create(['name' => 'admin.asignaciones.destroy'])->syncRoles($admin);

        // --- PERMISOS PARA ASIGNACIONES ---
        Permission::create(['name' => 'admin.pagos.index'])->syncRoles($admin, $caja);
        Permission::create(['name' => 'admin.pagos.ver_pagos'])->syncRoles($admin, $caja);
        Permission::create(['name' => 'admin.pagos.store'])->syncRoles($admin, $caja);
        Permission::create(['name' => 'admin.pagos.comprobante'])->syncRoles($admin, $caja);
        Permission::create(['name' => 'admin.pagos.destroy'])->syncRoles($admin, $caja);

        // --- PERMISOS PARA ASISTENCIAS DEL ESTUDIANTE ---
        Permission::create(['name' => 'admin.asistencias.index'])->syncRoles($admin,$docente,$estudiante);
        Permission::create(['name' => 'admin.asistencias.create'])->syncRoles($docente);
        Permission::create(['name' => 'admin.asistencias.store'])->syncRoles($docente);
        Permission::create(['name' => 'admin.asistencias.destroy'])->syncRoles($docente);
        Permission::create(['name' => 'admin.asistencias.update'])->syncRoles($docente);



        
    }
}
