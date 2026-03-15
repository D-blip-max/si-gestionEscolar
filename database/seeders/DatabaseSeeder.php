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
use App\Models\Materia;
use Spatie\Permission\Models\Role;
use App\Models\Personal;



class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RoleSeeder::class);

        User::create([
            'name' => 'Cossio Dylan',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678')
        ])->assignRole('ADMINISTRADOR');

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

        Materia::create(['nombre' => 'ARTES PLÁSTICAS Y VISUALES']);
        Materia::create(['nombre' => 'BIOLOGÍA - GEOGRAFÍA']);
        Materia::create(['nombre' => 'CIENCIAS SOCIALES']);
        Materia::create(['nombre' => 'COMPUTACIÓN']);
        Materia::create(['nombre' => 'COSMOVISIONES, FILOSOFÍA Y PSICOLOGÍA']);
        Materia::create(['nombre' => 'EDUCACIÓN FÍSICA Y DEPORTES']);
        Materia::create(['nombre' => 'EDUCACIÓN MUSICAL']);
        Materia::create(['nombre' => 'FÍSICA']);
        Materia::create(['nombre' => 'LENGUA CASTELLANA Y ORIGINARIA']);
        Materia::create(['nombre' => 'LENGUA EXTRANJERA']);
        Materia::create(['nombre' => 'MATEMÁTICA']);
        Materia::create(['nombre' => 'QUÍMICA']);
        Materia::create(['nombre' => 'VALORES, ESPIRITUALIDADES Y RELIGIONES']);
        Materia::create(['nombre' => 'TÉCNICA TECNOLÓGICA GENERAL']);

        // Administrativo 1
        User::create([
            'name' => 'Juan Mendoza',
            'email' => 'juan.mendoza@escuela.com',
            'password' => Hash::make('87654321')
        ])->assignRole('DIRECTOR/A GENERAL');

        Personal::create([
            'user_id' => 2,
            'tipo' => 'administrativo',
            'nombres' => 'Juan',
            'apellidos' => 'Mendoza',
            'ci' => '87654321',
            'fecha_nacimiento' => '1985-05-15',
            'direccion' => 'Av. Libertad 123',
            'telefono' => '76543210',
            'profesion' => 'Lic. en Matemáticas',
            'foto' => 'uploads/fotos/juan.jpg'
        ]);

        // Administrativo 2
        User::create([
            'name' => 'Carlos Rojas',
            'email' => 'carlos.rojas@escuela.com',
            'password' => Hash::make('76543210')
        ])->assignRole('DIRECTOR/A ACADÉMICO');

        Personal::create([
            'user_id' => 3,
            'tipo' => 'administrativo',
            'nombres' => 'Carlos',
            'apellidos' => 'Rojas',
            'ci' => '76543210',
            'fecha_nacimiento' => '1978-11-22',
            'direccion' => 'Calle Junín 456',
            'telefono' => '65432109',
            'profesion' => 'Contador Público',
            'foto' => 'uploads/fotos/' . time() . '_carlos.jpg'
        ]);

        // Administrativo 3
        User::create([
            'name' => 'Ana Torres',
            'email' => 'ana.torres@escuela.com',
            'password' => Hash::make('65432109')
        ])->assignRole('DIRECTOR/A ADMINISTRATIVO');

        Personal::create([
            'user_id' => 4,
            'tipo' => 'administrativo',
            'nombres' => 'Ana',
            'apellidos' => 'Torres',
            'ci' => '65432109',
            'fecha_nacimiento' => '1992-03-30',
            'direccion' => 'Av. Bolívar 789',
            'telefono' => '54321098',
            'profesion' => 'Lic. en Administración',
            'foto' => 'uploads/fotos/' . time() . '_ana.jpg'
        ]);

        // Administrativo 4
        User::create([
            'name' => 'Jorge Paz',
            'email' => 'jorge.paz@escuela.com',
            'password' => Hash::make('54321098')
        ])->assignRole('CAJERO/A');

        Personal::create([
            'user_id' => 5,
            'tipo' => 'administrativo',
            'nombres' => 'Jorge',
            'apellidos' => 'Paz',
            'ci' => '54321098',
            'fecha_nacimiento' => '1980-07-18',
            'direccion' => 'Calle Sucre 321',
            'telefono' => '43210987',
            'profesion' => 'Contaduria Pública',
            'foto' => 'uploads/fotos/' . time() . '_jorge.jpg'
        ]);

        // Administrativo 5
        User::create([
            'name' => 'Sofía Jiménez',
            'email' => 'sofia.jimenez@escuela.com',
            'password' => Hash::make('43210987')
        ])->assignRole('SECRETARIO/A');

        Personal::create([
            'user_id' => 6,
            'tipo' => 'administrativo',
            'nombres' => 'Sofía',
            'apellidos' => 'Jiménez',
            'ci' => '43210987',
            'fecha_nacimiento' => '1987-09-25',
            'direccion' => 'Av. América 654',
            'telefono' => '32109876',
            'profesion' => 'Secretariado Ejecutivo',
            'foto' => 'uploads/fotos/' . time() . '_sofia.jpg'
        ]);

        // Administrativo 6
        User::create([
            'name' => 'Ricardo Montero',
            'email' => 'ricardo.montero@escuela.com',
            'password' => Hash::make('11223344')
        ])->assignRole('REGENTE');

        Personal::create([
            'user_id' => 7,
            'tipo' => 'administrativo',
            'nombres' => 'Ricardo',
            'apellidos' => 'Montero',
            'ci' => '11223344',
            'fecha_nacimiento' => '1975-12-05',
            'direccion' => 'Av. Ejecutivos 1500, Piso 10',
            'telefono' => '70011223',
            'profesion' => 'Magíster en Gestión Educativa',
            'foto' => 'uploads/fotos/' . time() . '_ricardo.jpg',
            'created_at' => now()->subYears(3) // Fecha de ingreso hace 3 años
        ]);
    }
}
