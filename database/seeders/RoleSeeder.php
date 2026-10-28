<?php

namespace Database\Seeders;

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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
        $director_general = Role::firstOrCreate(['name' => 'DIRECTOR/A GENERAL']);
        $director_academico = Role::firstOrCreate(['name' => 'DIRECTOR/A ACADÉMICO']);
        $director_administrativo = Role::firstOrCreate(['name' => 'DIRECTOR/A ADMINISTRATIVO']);
        $docente = Role::firstOrCreate(['name' => 'DOCENTE']);
        $estudiante = Role::firstOrCreate(['name' => 'ESTUDIANTE']);
        $caja = Role::firstOrCreate(['name' => 'CAJERO/A']);
        $secretaria = Role::firstOrCreate(['name' => 'SECRETARIO/A']);
        $regente = Role::firstOrCreate(['name' => 'REGENTE']);

        // =========================================================================
        // PAQUETE 1: ADMINISTRACIÓN Y SEGURIDAD
        // =========================================================================
        Permission::firstOrCreate(['name' => 'admin.seguridad.menu'])->syncRoles([$admin, $director_general]);
        Permission::firstOrCreate(['name' => 'admin.configuracion.index'])->syncRoles([$admin, $director_general]);
        Permission::firstOrCreate(['name' => 'admin.configuracion.crear'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.index'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.create'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.store'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.edit'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.update'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.usuarios.destroy'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.roles.index'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.roles.create'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.roles.store'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.roles.edit'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.roles.update'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.roles.destroy'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.roles.permisos'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.roles.update_permisos'])->syncRoles([$admin]);

        // =========================================================================
        // PAQUETE 2: CATÁLOGOS ACADÉMICOS
        // =========================================================================
        Permission::firstOrCreate(['name' => 'admin.catalogos.menu'])->syncRoles([$admin, $director_general, $director_academico, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.idiomas.index'])->syncRoles([$admin, $director_general, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.idiomas.create'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.idiomas.edit'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.idiomas.destroy'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.modulos.index'])->syncRoles([$admin, $director_general, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.modulos.create'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.modulos.edit'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.modulos.destroy'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.ciclos.index'])->syncRoles([$admin, $director_general, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.ciclos.create'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.ciclos.edit'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.ciclos.destroy'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.paralelos.index'])->syncRoles([$admin, $director_general, $director_academico, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.paralelos.create'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.paralelos.update'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.paralelos.destroy'])->syncRoles([$admin]);

        // =========================================================================
        // PAQUETE 3: PERSONAL Y ASIGNACIONES
        // =========================================================================
        Permission::firstOrCreate(['name' => 'admin.personal.menu'])->syncRoles([$admin, $director_general, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.personal.index'])->syncRoles([$admin, $director_general, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.personal.create'])->syncRoles([$admin, $director_general]);
        Permission::firstOrCreate(['name' => 'admin.personal.store'])->syncRoles([$admin, $director_general]);
        Permission::firstOrCreate(['name' => 'admin.personal.show'])->syncRoles([$admin, $director_general, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.personal.edit'])->syncRoles([$admin, $director_general]);
        Permission::firstOrCreate(['name' => 'admin.personal.update'])->syncRoles([$admin, $director_general]);
        Permission::firstOrCreate(['name' => 'admin.personal.destroy'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.personal.formaciones.index'])->syncRoles([$admin, $director_general, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.personal.formaciones.create'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.personal.formaciones.store'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.personal.formaciones.edit'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.personal.formaciones.update'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.personal.formaciones.destroy'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.asignaciones.index'])->syncRoles([$admin, $director_general, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.asignaciones.create'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.asignaciones.store'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.asignaciones.show'])->syncRoles([$admin, $director_general, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.asignaciones.edit'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.asignaciones.update'])->syncRoles([$admin, $director_academico]);
        Permission::firstOrCreate(['name' => 'admin.asignaciones.destroy'])->syncRoles([$admin]);

        // =========================================================================
        // PAQUETE 4: PADRÓN DE ESTUDIANTES Y BOLETAS BANCARIAS
        // =========================================================================
        Permission::firstOrCreate(['name' => 'administrativo.estudiantes.menu'])->syncRoles([$admin, $director_general, $director_academico, $director_administrativo, $secretaria, $caja]);
        Permission::firstOrCreate(['name' => 'admin.estudiantes.index'])->syncRoles([$admin, $director_general, $director_academico, $director_administrativo, $secretaria, $caja]);
        Permission::firstOrCreate(['name' => 'admin.estudiantes.create'])->syncRoles([$admin, $director_administrativo, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.estudiantes.store'])->syncRoles([$admin, $director_administrativo, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.estudiantes.show'])->syncRoles([$admin, $director_general, $director_academico, $director_administrativo, $secretaria, $caja]);
        Permission::firstOrCreate(['name' => 'admin.estudiantes.edit'])->syncRoles([$admin, $director_administrativo, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.estudiantes.update'])->syncRoles([$admin, $director_administrativo, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.estudiantes.destroy'])->syncRoles([$admin]);
        Permission::firstOrCreate(['name' => 'admin.estudiantes.kardex'])->syncRoles([$admin, $director_general, $director_administrativo, $director_academico, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.boletas.index'])->syncRoles([$admin, $director_administrativo, $caja, $secretaria]);

        // =========================================================================
        // PAQUETE 5: INSCRIPCIONES Y MATRÍCULA MODULAR
        // =========================================================================
        Permission::firstOrCreate(['name' => 'administrativo.inscripciones.menu'])->syncRoles([$admin, $director_general, $director_academico, $director_administrativo, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.index'])->syncRoles([$admin, $director_general, $director_academico, $director_administrativo, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.create'])->syncRoles([$admin, $director_administrativo, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.store'])->syncRoles([$admin, $director_administrativo, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.inscripciones.destroy'])->syncRoles([$admin, $director_administrativo]);

        // =========================================================================
        // PAQUETE 6: EVALUACIÓN Y CALIFICACIONES (DOCENTE)
        // =========================================================================
        Permission::firstOrCreate(['name' => 'docente.calificaciones.menu'])->syncRoles([$admin, $docente, $director_general, $director_academico, $secretaria]);
        Permission::firstOrCreate(['name' => 'docente.grupos.index'])->syncRoles([$admin, $docente]);
        Permission::firstOrCreate(['name' => 'docente.calificaciones.create'])->syncRoles([$admin, $docente]);
        Permission::firstOrCreate(['name' => 'docente.actas.index'])->syncRoles([$admin, $docente, $director_general, $director_academico, $secretaria]);

        // =========================================================================
        // PAQUETE 7: PORTAL DEL ESTUDIANTE (AUTOGESTIÓN)
        // =========================================================================
        Permission::firstOrCreate(['name' => 'estudiante.portal.menu'])->syncRoles([$admin, $estudiante]);
        Permission::firstOrCreate(['name' => 'estudiante.clases.index'])->syncRoles([$admin, $estudiante]);
        Permission::firstOrCreate(['name' => 'estudiante.avance.index'])->syncRoles([$admin, $estudiante]);
        Permission::firstOrCreate(['name' => 'estudiante.documentos.index'])->syncRoles([$admin, $estudiante]);

        // =========================================================================
        // PAQUETE 8: REPORTES Y CONCILIACIÓN GERENCIAL
        // =========================================================================
        Permission::firstOrCreate(['name' => 'admin.reportes.menu'])->syncRoles([$admin, $director_general, $director_academico, $director_administrativo, $secretaria, $caja]);
        Permission::firstOrCreate(['name' => 'admin.reportes.index'])->syncRoles([$admin, $director_general, $director_academico, $director_administrativo, $secretaria, $caja]);
        Permission::firstOrCreate(['name' => 'admin.reportes.conciliacion'])->syncRoles([$admin, $director_general, $director_administrativo, $caja]);
        Permission::firstOrCreate(['name' => 'admin.reportes.inscritos'])->syncRoles([$admin, $director_general, $director_academico, $director_administrativo, $secretaria]);
        Permission::firstOrCreate(['name' => 'admin.reportes.graduados'])->syncRoles([$admin, $director_general, $director_academico]);
    }
}
