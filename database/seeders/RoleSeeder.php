<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(['name' => 'ADMINISTRADOR']);
        Role::create(['name' => 'DIRECTOR/A GENERAL']);
        Role::create(['name' => 'DIRECTOR/A ACADÉMICO']);
        Role::create(['name' => 'DIRECTOR/A ADMINISTRATIVO']);
        Role::create(['name' => 'DOCENTE']);
        Role::create(['name' => 'ESTUDIANTE']);
        Role::create(['name' => 'CAJERO/A']);
        Role::create(['name' => 'SECRETARIO/A']);
        Role::create(['name' => 'REGENTE']);
    }
}
