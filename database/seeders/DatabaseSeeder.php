<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuracion;
use App\Models\Paralelo;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Personal;
use App\Models\Estudiante;
use App\Models\Asignacion;
use App\Models\Idioma;
use App\Models\Modulo;
use App\Models\CicloBimestral;
use App\Models\BoletaPago;
use App\Models\Inscripcion;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Matriz de Roles y Permisos RBAC
        $this->call(RoleSeeder::class);

        // 2. Administrador Principal (Superadmin)
        $adminUser = User::firstOrCreate([
            'email' => 'admin@admin.com'
        ], [
            'name' => 'Cossio Dylan',
            'password' => Hash::make('12345678')
        ]);
        if (!$adminUser->hasRole('ADMINISTRADOR')) {
            $adminUser->assignRole('ADMINISTRADOR');
        }

        // 3. Configuración Institucional CEI - UAGRM
        if (Configuracion::count() == 0) {
            Configuracion::create([
                'nombre' => 'CEI - Centro de Extensión de Idiomas',
                'descripcion' => 'Instituto de Formación Lingüística - Universidad Autónoma Gabriel René Moreno',
                'direccion' => 'Campus Universitario UAGRM, Calle Ñuflo de Chávez',
                'telefono' => '75657007 - 3345678',
                'divisa' => 'Bs',
                'correo_electronico' => 'contacto@cei-uagrm.edu.bo',
                'web' => 'https://si-gestionescolar-production.up.railway.app',
                'logo' => 'img/logo_cei.png'
            ]);
        }

        // 4. Catálogos Académicos: Idiomas
        $ingles = Idioma::firstOrCreate(['codigo' => 'ENG'], [
            'nombre' => 'Inglés',
            'descripcion' => 'Programa modular de Lengua Inglesa (Básico, Intermedio y Avanzado)',
            'estado' => 'ACTIVO'
        ]);

        $guarani = Idioma::firstOrCreate(['codigo' => 'GUA'], [
            'nombre' => 'Guaraní',
            'descripcion' => 'Programa modular de Lengua y Cultura Guaraní',
            'estado' => 'ACTIVO'
        ]);

        // 5. Ciclos Bimestrales
        $ciclo1 = CicloBimestral::firstOrCreate(['nombre' => 'Bimestre I - 2026 (Ene-Feb)'], [
            'gestion' => 2026,
            'fecha_inicio' => '2026-01-15',
            'fecha_fin' => '2026-03-15',
            'estado' => 'ACTIVO'
        ]);

        CicloBimestral::firstOrCreate(['nombre' => 'Bimestre II - 2026 (Mar-Abr)'], [
            'gestion' => 2026,
            'fecha_inicio' => '2026-03-16',
            'fecha_fin' => '2026-05-15',
            'estado' => 'FUTURO'
        ]);

        // 6. Módulos 1 al 9 de Inglés con correlatividades y reglas de graduación
        $m1 = Modulo::firstOrCreate(['idioma_id' => $ingles->id, 'numero_orden' => 1], [
            'nombre' => 'Módulo 1', 'nivel' => 'BASICO', 'duracion_meses' => 2, 'prerrequisito_id' => null, 'es_modulo_graduacion' => false
        ]);
        $m2 = Modulo::firstOrCreate(['idioma_id' => $ingles->id, 'numero_orden' => 2], [
            'nombre' => 'Módulo 2', 'nivel' => 'BASICO', 'duracion_meses' => 2, 'prerrequisito_id' => $m1->id, 'es_modulo_graduacion' => false
        ]);
        $m3 = Modulo::firstOrCreate(['idioma_id' => $ingles->id, 'numero_orden' => 3], [
            'nombre' => 'Módulo 3', 'nivel' => 'BASICO', 'duracion_meses' => 2, 'prerrequisito_id' => $m2->id, 'es_modulo_graduacion' => false
        ]);
        $m4 = Modulo::firstOrCreate(['idioma_id' => $ingles->id, 'numero_orden' => 4], [
            'nombre' => 'Módulo 4', 'nivel' => 'BASICO', 'duracion_meses' => 2, 'prerrequisito_id' => $m3->id, 'es_modulo_graduacion' => false
        ]);
        $m5 = Modulo::firstOrCreate(['idioma_id' => $ingles->id, 'numero_orden' => 5], [
            'nombre' => 'Módulo 5', 'nivel' => 'INTERMEDIO', 'duracion_meses' => 2, 'prerrequisito_id' => $m4->id, 'es_modulo_graduacion' => false
        ]);
        $m6 = Modulo::firstOrCreate(['idioma_id' => $ingles->id, 'numero_orden' => 6], [
            'nombre' => 'Módulo 6', 'nivel' => 'INTERMEDIO', 'duracion_meses' => 2, 'prerrequisito_id' => $m5->id, 'es_modulo_graduacion' => false
        ]);
        $m7 = Modulo::firstOrCreate(['idioma_id' => $ingles->id, 'numero_orden' => 7], [
            'nivel' => 'INTERMEDIO', 'nombre' => 'Módulo 7', 'duracion_meses' => 2, 'prerrequisito_id' => $m6->id, 'es_modulo_graduacion' => false
        ]);
        $m8 = Modulo::firstOrCreate(['idioma_id' => $ingles->id, 'numero_orden' => 8], [
            'nombre' => 'Módulo 8', 'nivel' => 'INTERMEDIO', 'duracion_meses' => 2, 'prerrequisito_id' => $m7->id, 'es_modulo_graduacion' => true
        ]);
        $m9 = Modulo::firstOrCreate(['idioma_id' => $ingles->id, 'numero_orden' => 9], [
            'nombre' => 'Módulo 9', 'nivel' => 'AVANZADO', 'duracion_meses' => 2, 'prerrequisito_id' => $m8->id, 'es_modulo_graduacion' => true
        ]);

        // 7. Paralelos CEI (Presencial y Virtual)
        $paralelo1 = Paralelo::firstOrCreate(['nombre' => 'Paralelo 1 - Básico Presencial', 'ciclo_id' => $ciclo1->id, 'modulo_id' => $m1->id], [
            'modalidad' => 'PRESENCIAL',
            'aula_o_enlace' => 'Aula 102 - Pabellón Central UAGRM',
            'turno' => 'MAÑANA',
            'hora_inicio' => '07:30:00',
            'hora_fin' => '09:30:00',
            'cupo_maximo' => 25,
            'cupo_ocupado' => 0
        ]);

        $paralelo2 = Paralelo::firstOrCreate(['nombre' => 'Paralelo 2 - Básico Virtual', 'ciclo_id' => $ciclo1->id, 'modulo_id' => $m1->id], [
            'modalidad' => 'VIRTUAL',
            'aula_o_enlace' => 'https://meet.google.com/cei-uagrm-ingles1',
            'turno' => 'NOCHE',
            'hora_inicio' => '19:00:00',
            'hora_fin' => '21:00:00',
            'cupo_maximo' => 30,
            'cupo_ocupado' => 0
        ]);

        // 8. Personal Administrativo Institucional
        $uAdmin1 = User::firstOrCreate(['email' => 'juan.mendoza@cei.com'], [
            'name' => 'Juan Mendoza', 'password' => Hash::make('87654321')
        ]);
        $uAdmin1->syncRoles(['DIRECTOR/A GENERAL']);
        Personal::firstOrCreate(['ci' => '87654321'], [
            'user_id' => $uAdmin1->id, 'tipo' => 'administrativo', 'nombres' => 'Juan', 'apellidos' => 'Mendoza',
            'fecha_nacimiento' => '1985-05-15', 'direccion' => 'Av. Libertad 123', 'telefono' => '76543210',
            'profesion' => 'Lic. en Lingüística y Gestión Educativa', 'foto' => 'uploads/fotos/juan.jpg'
        ]);

        $uAdmin2 = User::firstOrCreate(['email' => 'carlos.rojas@cei.com'], [
            'name' => 'Carlos Rojas', 'password' => Hash::make('76543210')
        ]);
        $uAdmin2->syncRoles(['DIRECTOR/A ACADÉMICO']);
        Personal::firstOrCreate(['ci' => '76543210'], [
            'user_id' => $uAdmin2->id, 'tipo' => 'administrativo', 'nombres' => 'Carlos', 'apellidos' => 'Rojas',
            'fecha_nacimiento' => '1978-11-22', 'direccion' => 'Calle Junín 456', 'telefono' => '65432109',
            'profesion' => 'Magíster en Educación Superior', 'foto' => 'uploads/fotos/carlos.jpg'
        ]);

        $uAdmin3 = User::firstOrCreate(['email' => 'ana.torres@cei.com'], [
            'name' => 'Ana Torres', 'password' => Hash::make('65432109')
        ]);
        $uAdmin3->syncRoles(['DIRECTOR/A ADMINISTRATIVO']);
        Personal::firstOrCreate(['ci' => '65432109'], [
            'user_id' => $uAdmin3->id, 'tipo' => 'administrativo', 'nombres' => 'Ana', 'apellidos' => 'Torres',
            'fecha_nacimiento' => '1992-03-30', 'direccion' => 'Av. Bolívar 789', 'telefono' => '54321098',
            'profesion' => 'Lic. en Administración Financiera', 'foto' => 'uploads/fotos/ana.jpg'
        ]);

        $uCaja = User::firstOrCreate(['email' => 'jorge.paz@cei.com'], [
            'name' => 'Jorge Paz', 'password' => Hash::make('54321098')
        ]);
        $uCaja->syncRoles(['CAJERO/A']);
        Personal::firstOrCreate(['ci' => '54321098'], [
            'user_id' => $uCaja->id, 'tipo' => 'administrativo', 'nombres' => 'Jorge', 'apellidos' => 'Paz',
            'fecha_nacimiento' => '1980-07-18', 'direccion' => 'Calle Sucre 321', 'telefono' => '43210987',
            'profesion' => 'Contador General (Tesorería)', 'foto' => 'uploads/fotos/jorge.jpg'
        ]);

        $uSecretaria = User::firstOrCreate(['email' => 'sofia.jimenez@cei.com'], [
            'name' => 'Sofía Jiménez', 'password' => Hash::make('43210987')
        ]);
        $uSecretaria->syncRoles(['SECRETARIO/A']);
        Personal::firstOrCreate(['ci' => '43210987'], [
            'user_id' => $uSecretaria->id, 'tipo' => 'administrativo', 'nombres' => 'Sofía', 'apellidos' => 'Jiménez',
            'fecha_nacimiento' => '1987-09-25', 'direccion' => 'Av. América 654', 'telefono' => '32109876',
            'profesion' => 'Secretariado Ejecutivo y Atención CEI', 'foto' => 'uploads/fotos/sofia.jpg'
        ]);

        // 9. Personal Docente CEI
        $uDoc1 = User::firstOrCreate(['email' => 'maria.fernandez@cei.com'], [
            'name' => 'María Fernández', 'password' => Hash::make('24680246')
        ]);
        $uDoc1->syncRoles(['DOCENTE']);
        $pDoc1 = Personal::firstOrCreate(['ci' => '24680246'], [
            'user_id' => $uDoc1->id, 'tipo' => 'docente', 'nombres' => 'María', 'apellidos' => 'Fernández',
            'fecha_nacimiento' => '1980-03-15', 'direccion' => 'Calle Las Flores 245', 'telefono' => '79123456',
            'profesion' => 'Lic. en Lengua Inglesa y Literatura', 'foto' => 'uploads/fotos/maria.jpg'
        ]);

        $uDoc2 = User::firstOrCreate(['email' => 'carlos.rios@cei.com'], [
            'name' => 'Carlos Ríos', 'password' => Hash::make('97531975')
        ]);
        $uDoc2->syncRoles(['DOCENTE']);
        $pDoc2 = Personal::firstOrCreate(['ci' => '97531975'], [
            'user_id' => $uDoc2->id, 'tipo' => 'docente', 'nombres' => 'Carlos', 'apellidos' => 'Ríos',
            'fecha_nacimiento' => '1975-07-22', 'direccion' => 'Av. Ciencias 789', 'telefono' => '71234567',
            'profesion' => 'Certificado C2 Cambridge / Docente Titular', 'foto' => 'uploads/fotos/carlos_doc.jpg'
        ]);

        // 10. Asignación Académica Oficial CEI
        Asignacion::firstOrCreate([
            'personal_id' => $pDoc1->id,
            'paralelo_id' => $paralelo1->id
        ], [
            'fecha_asignacion' => '2026-01-15',
            'estado' => 'ACTIVO'
        ]);

        Asignacion::firstOrCreate([
            'personal_id' => $pDoc2->id,
            'paralelo_id' => $paralelo2->id
        ], [
            'fecha_asignacion' => '2026-01-15',
            'estado' => 'ACTIVO'
        ]);

        // 11. Estudiantes Universitarios Autónomos CEI
        $estudiantesData = [
            ['nombres' => 'Daniel', 'apellidos' => 'Mendoza Luna', 'ci' => '111001', 'email' => 'daniel.m@estudiante.cei.edu.bo', 'carrera' => 'Ingeniería en Sistemas', 'telefono' => '71000001'],
            ['nombres' => 'Carla', 'apellidos' => 'Sosa Villalba', 'ci' => '111002', 'email' => 'carla.s@estudiante.cei.edu.bo', 'carrera' => 'Bioquímica y Farmacia', 'telefono' => '71000002'],
            ['nombres' => 'Roberto', 'apellidos' => 'Guzmán Rico', 'ci' => '111003', 'email' => 'roberto.g@estudiante.cei.edu.bo', 'carrera' => 'Derecho y Cs. Políticas', 'telefono' => '71000003'],
            ['nombres' => 'Valeria', 'apellidos' => 'Ortiz Bravo', 'ci' => '111004', 'email' => 'valeria.o@estudiante.cei.edu.bo', 'carrera' => 'Medicina Humana', 'telefono' => '71000004'],
            ['nombres' => 'Lucas', 'apellidos' => 'Peña Durán', 'ci' => '111005', 'email' => 'lucas.p@estudiante.cei.edu.bo', 'carrera' => 'Ingeniería Comercial', 'telefono' => '71000005'],
        ];

        $correlativo = 1;
        foreach ($estudiantesData as $item) {
            $uEst = User::firstOrCreate(['email' => $item['email']], [
                'name' => "{$item['nombres']} {$item['apellidos']}",
                'password' => Hash::make($item['ci'])
            ]);
            $uEst->syncRoles(['ESTUDIANTE']);

            $est = Estudiante::firstOrCreate(['ci' => $item['ci']], [
                'usuario_id' => $uEst->id,
                'user_id' => $uEst->id,
                'codigo_estudiante' => sprintf('CEI-2026-%04d', $correlativo++),
                'nombres' => $item['nombres'],
                'apellidos' => $item['apellidos'],
                'carrera_facultad' => $item['carrera'],
                'fecha_nacimiento' => '2002-05-10',
                'telefono' => $item['telefono'],
                'direccion' => 'Santa Cruz de la Sierra - Campus Universitario',
                'genero' => ($correlativo % 2 == 0) ? 'Femenino' : 'Masculino',
                'estado' => 1,
                'ppff_id' => null
            ]);

            // Boleta Bancaria Universitaria de muestra
            $boleta = BoletaPago::firstOrCreate(['numero_boleta' => 'BOL-2026-000' . $est->id], [
                'estudiante_id' => $est->id,
                'banco' => 'BANCO UNIÓN S.A.',
                'fecha_deposito' => '2026-01-14',
                'monto' => 350.00,
                'concepto' => 'Matrícula Módulo 1 Inglés CEI',
                'estado' => 'UTILIZADA',
                'registrado_por' => $adminUser->id
            ]);

            // Inscripción Activa al Módulo 1 en el ciclo
            Inscripcion::firstOrCreate([
                'estudiante_id' => $est->id,
                'ciclo_id' => $ciclo1->id,
                'modulo_id' => $m1->id
            ], [
                'paralelo_id' => ($correlativo % 2 == 0) ? $paralelo1->id : $paralelo2->id,
                'boleta_pago_id' => $boleta->id,
                'costo_matricula' => 350.00,
                'fecha_inscripcion' => '2026-01-15',
                'estado' => 'ACTIVA',
                'observacion' => 'Inscripción modular regular ciclo 2026-I',
                'usuario_id' => $adminUser->id
            ]);
        }
    }
}
