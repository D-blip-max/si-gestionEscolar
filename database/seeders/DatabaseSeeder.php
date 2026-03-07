<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuracion;
use App\Models\Gestion;
use App\Models\Periodo;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Paralelo;
use App\Models\Turno;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Cossio Dylan',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678')
        ]);

        Configuracion::create([
            'nombre' => 'Cei',
            'descripcion' => 'Curso de Extension de Idiomas',
            'direccion' => 'Ñuflo de Chavez',
            'telefono' => '75657007 - 54646787',
            'divisa' => 'Bs',
            'correo_electronico' => 'cei@gmail.com',
            'web' => 'https://cei.com',
            'logo' => 'uploads\logos\1772770542_cei Logo.png'
        ]);

        Gestion::create(['nombre' => '2023']);
        Gestion::create(['nombre' => '2024']);
        Gestion::create(['nombre' => '2025']);

        // Creación de Periodos (Trimestres) vinculados a la Gestión
        Periodo::create(['nombre' => '1er Trimestre', 'gestion_id' => 1]);
        Periodo::create(['nombre' => '2do Trimestre', 'gestion_id' => 1]);
        Periodo::create(['nombre' => '3er Trimestre', 'gestion_id' => 1]);
        Periodo::create(['nombre' => '1er Trimestre', 'gestion_id' => 2]);
        Periodo::create(['nombre' => '2do Trimestre', 'gestion_id' => 2]);
        Periodo::create(['nombre' => '3er Trimestre', 'gestion_id' => 2]);
        Periodo::create(['nombre' => '1er Trimestre', 'gestion_id' => 3]);
        Periodo::create(['nombre' => '2do Trimestre', 'gestion_id' => 3]);
        Periodo::create(['nombre' => '3er Trimestre', 'gestion_id' => 3]);

        // Creación de Niveles
        Nivel::create(['nombre' => 'INICIAL']);
        Nivel::create(['nombre' => 'PRIMARIA']);
        Nivel::create(['nombre' => 'SECUNDARIA']);

        // Creación de Grados vinculados a su respectivo Nivel
        Grado::create(['nombre' => '1ro Inicial', 'nivel_id' => 1]);
        Grado::create(['nombre' => '2do Inicial', 'nivel_id' => 1]);

        Grado::create(['nombre' => '1ro Primaria', 'nivel_id' => 2]);
        Grado::create(['nombre' => '2do Primaria', 'nivel_id' => 2]);
        Grado::create(['nombre' => '3ro Primaria', 'nivel_id' => 2]);
        Grado::create(['nombre' => '4to Primaria', 'nivel_id' => 2]);
        Grado::create(['nombre' => '5to Primaria', 'nivel_id' => 2]);
        Grado::create(['nombre' => '6to Primaria', 'nivel_id' => 2]);

        Grado::create(['nombre' => '1ro Secundaria', 'nivel_id' => 3]);
        Grado::create(['nombre' => '2do Secundaria', 'nivel_id' => 3]);
        Grado::create(['nombre' => '3ro Secundaria', 'nivel_id' => 3]);
        Grado::create(['nombre' => '4to Secundaria', 'nivel_id' => 3]);
        Grado::create(['nombre' => '5to Secundaria', 'nivel_id' => 3]);
        Grado::create(['nombre' => '6to Secundaria', 'nivel_id' => 3]);


        Paralelo::create(['nombre' => 'A', 'grado_id' => 1]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 2]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 3]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 4]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 5]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 6]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 7]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 8]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 9]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 10]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 11]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 12]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 13]);
        Paralelo::create(['nombre' => 'A', 'grado_id' => 14]);

        Turno::create(['nombre' => 'Mañana']);
        Turno::create(['nombre' => 'Tarde']);
        Turno::create(['nombre' => 'Noche']);
    }
}
