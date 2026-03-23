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
use App\Models\Ppff;
use App\Models\Estudiante;



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

        // Docente 1 - Patrón: Puro Par (Ascendente)
        $user1 = User::create([
            'name' => 'María Fernández',
            'email' => 'maria.fernandez@escuela.com',
            'password' => Hash::make('24680246')
        ])->assignRole('DOCENTE');

        Personal::create([
            'user_id' => $user1->id, // Relación directa
            'tipo' => 'docente',
            'nombres' => 'María',
            'apellidos' => 'Fernández',
            'ci' => '24680246',
            'fecha_nacimiento' => '1980-03-15',
            'direccion' => 'Calle Las Flores 245',
            'telefono' => '79123456',
            'profesion' => 'Lic. en Lengua y Literatura',
            'foto' => 'uploads/fotos/' . time() . '_maria.jpg'
        ]);

        // Docente 2 - Patrón: Puro Impar (Descendente)
        $user2 = User::create([
            'name' => 'Carlos Ríos',
            'email' => 'carlos.rios@escuela.com',
            'password' => Hash::make('97531975')
        ])->assignRole('DOCENTE');

        Personal::create([
            'user_id' => $user2->id,
            'tipo' => 'docente',
            'nombres' => 'Carlos',
            'apellidos' => 'Ríos',
            'ci' => '97531975',
            'fecha_nacimiento' => '1975-07-22',
            'direccion' => 'Av. Ciencias 789',
            'telefono' => '71234567',
            'profesion' => 'Magíster en Matemáticas',
            'foto' => 'uploads/fotos/' . time() . '_carlos.jpg'
        ]);

        // Docente 3 - Patrón: Ascendente Simple
        $user3 = User::create([
            'name' => 'Ricardo Méndez',
            'email' => 'ricardo.mendez@escuela.com',
            'password' => Hash::make('12345678')
        ])->assignRole('DOCENTE');

        Personal::create([
            'user_id' => $user3->id,
            'tipo' => 'docente',
            'nombres' => 'Ricardo',
            'apellidos' => 'Méndez',
            'ci' => '12345678',
            'fecha_nacimiento' => '1988-06-10',
            'direccion' => 'Barrio Lindo 123',
            'telefono' => '70011223',
            'profesion' => 'Ingeniero Civil',
            'foto' => 'uploads/fotos/' . time() . '_ricardo.jpg'
        ]);

        // Docente 4 - Patrón: Descendente Simple
        $user4 = User::create([
            'name' => 'Elena Suárez',
            'email' => 'elena.suarez@escuela.com',
            'password' => Hash::make('8765321')
        ])->assignRole('DOCENTE');

        Personal::create([
            'user_id' => $user4->id,
            'tipo' => 'docente',
            'nombres' => 'Elena',
            'apellidos' => 'Suárez',
            'ci' => '8765321',
            'fecha_nacimiento' => '1992-02-28',
            'direccion' => 'Calle Junín 456',
            'telefono' => '70044556',
            'profesion' => 'Lic. en Historia',
            'foto' => 'uploads/fotos/' . time() . '_elena.jpg'
        ]);

        // Docente 5 - Patrón: Espejo (Capicúa)
        $user5 = User::create([
            'name' => 'Jorge Vaca',
            'email' => 'jorge.vaca@escuela.com',
            'password' => Hash::make('13577531')
        ])->assignRole('DOCENTE');

        Personal::create([
            'user_id' => $user5->id,
            'tipo' => 'docente',
            'nombres' => 'Jorge',
            'apellidos' => 'Vaca',
            'ci' => '13577531',
            'fecha_nacimiento' => '1985-11-20',
            'direccion' => 'UV 45, Av. Bush',
            'telefono' => '70088990',
            'profesion' => 'Lic. en Geografía',
            'foto' => 'uploads/fotos/' . time() . '_jorge.jpg'
        ]);
        //padres de familia
        $p1 = Ppff::create(['nombres' => 'Ricardo', 'apellidos' => 'Mendoza Luna', 'ci' => '1000001', 'fecha_nacimiento' => '1975-04-12', 'telefono' => '71000001', 'parentesco' => 'Padre', 'ocupacion' => 'Ingeniero Civil', 'direccion' => 'Av. Banzer, Calle 4 Este #45']);
        $p2 = Ppff::create(['nombres' => 'Beatriz', 'apellidos' => 'Sosa Villalba', 'ci' => '1000002', 'fecha_nacimiento' => '1978-08-22', 'telefono' => '71000002', 'parentesco' => 'Madre', 'ocupacion' => 'Contadora', 'direccion' => 'Barrio Equipetrol, Calle Tucumán #12']);
        $p3 = Ppff::create(['nombres' => 'Humberto', 'apellidos' => 'Guzmán Rico', 'ci' => '1000003', 'fecha_nacimiento' => '1980-01-30', 'telefono' => '71000003', 'parentesco' => 'Tutor', 'ocupacion' => 'Abogado', 'direccion' => 'Zona Sur, Av. Las Lomas#300']);
        $p4 = Ppff::create(['nombres' => 'Elena', 'apellidos' => 'Paredes Soliz', 'ci' => '1000004', 'fecha_nacimiento' => '1982-11-15', 'telefono' => '71000004', 'parentesco' => 'Madre', 'ocupacion' => 'Médico Pediatra', 'direccion' => 'Condominio La Riviera, dpto 4B']);
        $p5 = Ppff::create(['nombres' => 'Marcos', 'apellidos' => 'Peña Durán', 'ci' => '1000005', 'fecha_nacimiento' => '1976-06-05', 'telefono' => '71000005', 'parentesco' => 'Padre', 'ocupacion' => 'Arquitecto', 'direccion' => 'Urb. Los Pinos, Pasaje C #5']);
        $p6 = Ppff::create(['nombres' => 'Raquel', 'apellidos' => 'Ortiz Bravo', 'ci' => '1000006', 'fecha_nacimiento' => '1979-09-18', 'telefono' => '71000006', 'parentesco' => 'Madre', 'ocupacion' => 'Diseñadora Gráfica', 'direccion' => 'Av. Piraí, 4to Anillo #88']);
        $p7 = Ppff::create(['nombres' => 'Gustavo', 'apellidos' => 'Ramos Calle', 'ci' => '1000007', 'fecha_nacimiento' => '1981-03-25', 'telefono' => '71000007', 'parentesco' => 'Padre', 'ocupacion' => 'Chef Ejecutiva', 'direccion' => 'Barrio Lujan, Av. San Aurelio#150']);
        $p8 = Ppff::create(['nombres' => 'Clara', 'apellidos' => 'Vaca Méndez', 'ci' => '1000008', 'fecha_nacimiento' => '1983-12-10', 'telefono' => '71000008', 'parentesco' => 'Madre', 'ocupacion' => 'Administradora', 'direccion' => 'Villa Primero de Mayo, Calle 7']);
        $p9 = Ppff::create(['nombres' => 'Silvia', 'apellidos' => 'Rojas Terceros', 'ci' => '1000009', 'fecha_nacimiento' => '1977-05-20', 'telefono' => '71000009', 'parentesco' => 'Tutor', 'ocupacion' => 'Periodista', 'direccion' => 'Plan 3000, Av. Principal #22']);
        $p10 = Ppff::create(['nombres' => 'Andrés', 'apellidos' => 'Zeballos Paz', 'ci' => '1000010', 'fecha_nacimiento' => '1974-02-28', 'telefono' => '71000010', 'parentesco' => 'Padre', 'ocupacion' => 'Transportista', 'direccion' => 'Av. Santos Dumont, Calle Lanza #10']);

        // --- HIJOS DE P1 ---
        $u1 = User::create(['name' => 'Daniel Mendoza', 'email' => 'daniel.m@estudiante.com', 'password' => Hash::make('111001')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u1->id, 'ppff_id' => $p1->id, 'nombres' => 'Daniel', 'apellidos' => 'Mendoza', 'ci' => '111001', 'fecha_nacimiento' => '2010-05-10', 'telefono' => '60000001', 'direccion' => 'Av. Banzer #45', 'foto' => 'uploads/estudiantes/est1.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u2 = User::create(['name' => 'Carla Mendoza', 'email' => 'carla.m@estudiante.com', 'password' => Hash::make('111002')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u2->id, 'ppff_id' => $p1->id, 'nombres' => 'Carla', 'apellidos' => 'Mendoza', 'ci' => '111002', 'fecha_nacimiento' => '2012-08-15', 'telefono' => '60000002', 'direccion' => 'Av. Banzer #45', 'foto' => 'uploads/estudiantes/est2.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        $u3 = User::create(['name' => 'Roberto Mendoza', 'email' => 'roberto.m@estudiante.com', 'password' => Hash::make('111003')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u3->id, 'ppff_id' => $p1->id, 'nombres' => 'Roberto', 'apellidos' => 'Mendoza', 'ci' => '111003', 'fecha_nacimiento' => '2014-03-20', 'telefono' => '60000003', 'direccion' => 'Av. Banzer #45', 'foto' => 'uploads/estudiantes/est3.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        // --- HIJOS DE P2 ---
        $u4 = User::create(['name' => 'Luis Sosa', 'email' => 'luis.s@estudiante.com', 'password' => Hash::make('111004')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u4->id, 'ppff_id' => $p2->id, 'nombres' => 'Luis', 'apellidos' => 'Sosa', 'ci' => '111004', 'fecha_nacimiento' => '2009-01-20', 'telefono' => '60000004', 'direccion' => 'Barrio Equipetrol #12', 'foto' => 'uploads/estudiantes/est4.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u5 = User::create(['name' => 'Ana Sosa', 'email' => 'ana.s@estudiante.com', 'password' => Hash::make('111005')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u5->id, 'ppff_id' => $p2->id, 'nombres' => 'Ana', 'apellidos' => 'Sosa', 'ci' => '111005', 'fecha_nacimiento' => '2011-03-12', 'telefono' => '60000005', 'direccion' => 'Barrio Equipetrol #12', 'foto' => 'uploads/estudiantes/est5.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        $u6 = User::create(['name' => 'Mario Sosa', 'email' => 'mario.s@estudiante.com', 'password' => Hash::make('111006')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u6->id, 'ppff_id' => $p2->id, 'nombres' => 'Mario', 'apellidos' => 'Sosa', 'ci' => '111006', 'fecha_nacimiento' => '2013-05-05', 'telefono' => '60000006', 'direccion' => 'Barrio Equipetrol #12', 'foto' => 'uploads/estudiantes/est6.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        // --- HIJOS DE P3 ---
        $u7 = User::create(['name' => 'Hugo Guzmán', 'email' => 'hugo.g@estudiante.com', 'password' => Hash::make('111007')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u7->id, 'ppff_id' => $p3->id, 'nombres' => 'Hugo', 'apellidos' => 'Guzmán', 'ci' => '111007', 'fecha_nacimiento' => '2008-11-30', 'telefono' => '60000007', 'direccion' => 'Zona Sur #300', 'foto' => 'uploads/estudiantes/est7.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u8 = User::create(['name' => 'Sofia Guzmán', 'email' => 'sofia.g@estudiante.com', 'password' => Hash::make('111008')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u8->id, 'ppff_id' => $p3->id, 'nombres' => 'Sofia', 'apellidos' => 'Guzmán', 'ci' => '111008', 'fecha_nacimiento' => '2013-02-14', 'telefono' => '60000008', 'direccion' => 'Zona Sur #300', 'foto' => 'uploads/estudiantes/est8.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        $u9 = User::create(['name' => 'Mateo Guzmán', 'email' => 'mateo.g@estudiante.com', 'password' => Hash::make('111009')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u9->id, 'ppff_id' => $p3->id, 'nombres' => 'Mateo', 'apellidos' => 'Guzmán', 'ci' => '111009', 'fecha_nacimiento' => '2015-06-10', 'telefono' => '60000009', 'direccion' => 'Zona Sur #300', 'foto' => 'uploads/estudiantes/est9.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        // --- HIJOS DE P4 ---
        $u10 = User::create(['name' => 'Pedro Paredes', 'email' => 'pedro.p@estudiante.com', 'password' => Hash::make('111010')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u10->id, 'ppff_id' => $p4->id, 'nombres' => 'Pedro', 'apellidos' => 'Paredes', 'ci' => '111010', 'fecha_nacimiento' => '2010-07-07', 'telefono' => '60000010', 'direccion' => 'Cond. La Riviera', 'foto' => 'uploads/estudiantes/est10.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u11 = User::create(['name' => 'Marta Paredes', 'email' => 'marta.p@estudiante.com', 'password' => Hash::make('111011')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u11->id, 'ppff_id' => $p4->id, 'nombres' => 'Marta', 'apellidos' => 'Paredes', 'ci' => '111011', 'fecha_nacimiento' => '2012-12-25', 'telefono' => '60000011', 'direccion' => 'Cond. La Riviera', 'foto' => 'uploads/estudiantes/est11.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        $u12 = User::create(['name' => 'Lucía Paredes', 'email' => 'lucia.p@estudiante.com', 'password' => Hash::make('111012')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u12->id, 'ppff_id' => $p4->id, 'nombres' => 'Lucía', 'apellidos' => 'Paredes', 'ci' => '111012', 'fecha_nacimiento' => '2014-01-15', 'telefono' => '60000012', 'direccion' => 'Cond. La Riviera', 'foto' => 'uploads/estudiantes/est12.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        // --- HIJOS DE P5 ---
        $u13 = User::create(['name' => 'Jorge Peña', 'email' => 'jorge.pe@estudiante.com', 'password' => Hash::make('111013')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u13->id, 'ppff_id' => $p5->id, 'nombres' => 'Jorge', 'apellidos' => 'Peña', 'ci' => '111013', 'fecha_nacimiento' => '2009-04-05', 'telefono' => '60000013', 'direccion' => 'Urb. Los Pinos', 'foto' => 'uploads/estudiantes/est13.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u14 = User::create(['name' => 'Lucas Peña', 'email' => 'lucas.pe@estudiante.com', 'password' => Hash::make('111014')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u14->id, 'ppff_id' => $p5->id, 'nombres' => 'Lucas', 'apellidos' => 'Peña', 'ci' => '111014', 'fecha_nacimiento' => '2011-06-18', 'telefono' => '60000014', 'direccion' => 'Urb. Los Pinos', 'foto' => 'uploads/estudiantes/est14.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u15 = User::create(['name' => 'Sara Peña', 'email' => 'sara.pe@estudiante.com', 'password' => Hash::make('111015')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u15->id, 'ppff_id' => $p5->id, 'nombres' => 'Sara', 'apellidos' => 'Peña', 'ci' => '111015', 'fecha_nacimiento' => '2013-09-22', 'telefono' => '60000015', 'direccion' => 'Urb. Los Pinos', 'foto' => 'uploads/estudiantes/est15.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        // --- HIJOS DE P6 ---
        $u16 = User::create(['name' => 'Valeria Ortiz', 'email' => 'valeria.o@estudiante.com', 'password' => Hash::make('111016')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u16->id, 'ppff_id' => $p6->id, 'nombres' => 'Valeria', 'apellidos' => 'Ortiz', 'ci' => '111016', 'fecha_nacimiento' => '2010-09-01', 'telefono' => '60000016', 'direccion' => 'Av. Piraí #88', 'foto' => 'uploads/estudiantes/est16.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        $u17 = User::create(['name' => 'Bruno Ortiz', 'email' => 'bruno.o@estudiante.com', 'password' => Hash::make('111017')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u17->id, 'ppff_id' => $p6->id, 'nombres' => 'Bruno', 'apellidos' => 'Ortiz', 'ci' => '111017', 'fecha_nacimiento' => '2012-10-10', 'telefono' => '60000017', 'direccion' => 'Av. Piraí #88', 'foto' => 'uploads/estudiantes/est17.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u18 = User::create(['name' => 'Claudio Ortiz', 'email' => 'claudio.o@estudiante.com', 'password' => Hash::make('111018')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u18->id, 'ppff_id' => $p6->id, 'nombres' => 'Claudio', 'apellidos' => 'Ortiz', 'ci' => '111018', 'fecha_nacimiento' => '2014-02-28', 'telefono' => '60000018', 'direccion' => 'Av. Piraí #88', 'foto' => 'uploads/estudiantes/est18.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        // --- HIJOS DE P7 ---
        $u19 = User::create(['name' => 'Fabio Ramos', 'email' => 'fabio.r@estudiante.com', 'password' => Hash::make('111019')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u19->id, 'ppff_id' => $p7->id, 'nombres' => 'Fabio', 'apellidos' => 'Ramos', 'ci' => '111019', 'fecha_nacimiento' => '2008-05-20', 'telefono' => '60000019', 'direccion' => 'Barrio Lujan #150', 'foto' => 'uploads/estudiantes/est19.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u20 = User::create(['name' => 'Andrea Ramos', 'email' => 'andrea.r@estudiante.com', 'password' => Hash::make('111020')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u20->id, 'ppff_id' => $p7->id, 'nombres' => 'Andrea', 'apellidos' => 'Ramos', 'ci' => '111020', 'fecha_nacimiento' => '2010-11-05', 'telefono' => '60000020', 'direccion' => 'Barrio Lujan #150', 'foto' => 'uploads/estudiantes/est20.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        $u21 = User::create(['name' => 'Iker Ramos', 'email' => 'iker.r@estudiante.com', 'password' => Hash::make('111021')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u21->id, 'ppff_id' => $p7->id, 'nombres' => 'Iker', 'apellidos' => 'Ramos', 'ci' => '111021', 'fecha_nacimiento' => '2013-07-14', 'telefono' => '60000021', 'direccion' => 'Barrio Lujan #150', 'foto' => 'uploads/estudiantes/est21.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        // --- HIJOS DE P8 ---
        $u22 = User::create(['name' => 'Isabela Vaca', 'email' => 'isabela.v@estudiante.com', 'password' => Hash::make('111022')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u22->id, 'ppff_id' => $p8->id, 'nombres' => 'Isabela', 'apellidos' => 'Vaca', 'ci' => '111022', 'fecha_nacimiento' => '2011-11-11', 'telefono' => '60000022', 'direccion' => 'Villa 1ro de Mayo', 'foto' => 'uploads/estudiantes/est22.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        $u23 = User::create(['name' => 'Julian Vaca', 'email' => 'julian.v@estudiante.com', 'password' => Hash::make('111023')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u23->id, 'ppff_id' => $p8->id, 'nombres' => 'Julian', 'apellidos' => 'Vaca', 'ci' => '111023', 'fecha_nacimiento' => '2013-01-22', 'telefono' => '60000023', 'direccion' => 'Villa 1ro de Mayo', 'foto' => 'uploads/estudiantes/est23.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u24 = User::create(['name' => 'Noelia Vaca', 'email' => 'noelia.v@estudiante.com', 'password' => Hash::make('111024')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u24->id, 'ppff_id' => $p8->id, 'nombres' => 'Noelia', 'apellidos' => 'Vaca', 'ci' => '111024', 'fecha_nacimiento' => '2015-08-30', 'telefono' => '60000024', 'direccion' => 'Villa 1ro de Mayo', 'foto' => 'uploads/estudiantes/est24.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        // --- HIJOS DE P9 ---
        $u25 = User::create(['name' => 'Mateo Rojas', 'email' => 'mateo.r@estudiante.com', 'password' => Hash::make('111025')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u25->id, 'ppff_id' => $p9->id, 'nombres' => 'Mateo', 'apellidos' => 'Rojas', 'ci' => '111025', 'fecha_nacimiento' => '2012-01-30', 'telefono' => '60000025', 'direccion' => 'Plan 3000 #22', 'foto' => 'uploads/estudiantes/est25.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u26 = User::create(['name' => 'Elena Rojas', 'email' => 'elena.ro@estudiante.com', 'password' => Hash::make('111026')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u26->id, 'ppff_id' => $p9->id, 'nombres' => 'Elena', 'apellidos' => 'Rojas', 'ci' => '111026', 'fecha_nacimiento' => '2014-04-12', 'telefono' => '60000026', 'direccion' => 'Plan 3000 #22', 'foto' => 'uploads/estudiantes/est26.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        $u27 = User::create(['name' => 'Victor Rojas', 'email' => 'victor.r@estudiante.com', 'password' => Hash::make('111027')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u27->id, 'ppff_id' => $p9->id, 'nombres' => 'Victor', 'apellidos' => 'Rojas', 'ci' => '111027', 'fecha_nacimiento' => '2016-02-05', 'telefono' => '60000027', 'direccion' => 'Plan 3000 #22', 'foto' => 'uploads/estudiantes/est27.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        // --- HIJOS DE P10 ---
        $u28 = User::create(['name' => 'Oscar Zeballos', 'email' => 'oscar.z@estudiante.com', 'password' => Hash::make('111028')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u28->id, 'ppff_id' => $p10->id, 'nombres' => 'Oscar', 'apellidos' => 'Zeballos', 'ci' => '111028', 'fecha_nacimiento' => '2009-10-10', 'telefono' => '60000028', 'direccion' => 'Av. Santos Dumont', 'foto' => 'uploads/estudiantes/est28.jpg', 'genero' => 'Masculino', 'estado' => 1]);

        $u29 = User::create(['name' => 'Paula Zeballos', 'email' => 'paula.z@estudiante.com', 'password' => Hash::make('111029')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u29->id, 'ppff_id' => $p10->id, 'nombres' => 'Paula', 'apellidos' => 'Zeballos', 'ci' => '111029', 'fecha_nacimiento' => '2011-12-01', 'telefono' => '60000029', 'direccion' => 'Av. Santos Dumont', 'foto' => 'uploads/estudiantes/est29.jpg', 'genero' => 'Femenino', 'estado' => 1]);

        $u30 = User::create(['name' => 'Raúl Zeballos', 'email' => 'raul.z@estudiante.com', 'password' => Hash::make('111030')])->assignRole('ESTUDIANTE');
        Estudiante::create(['usuario_id' => $u30->id, 'ppff_id' => $p10->id, 'nombres' => 'Raúl', 'apellidos' => 'Zeballos', 'ci' => '111030', 'fecha_nacimiento' => '2013-05-18', 'telefono' => '60000030', 'direccion' => 'Av. Santos Dumont', 'foto' => 'uploads/estudiantes/est30.jpg', 'genero' => 'Masculino', 'estado' => 1]);
    }
}
