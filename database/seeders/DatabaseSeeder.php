<?php

namespace Database\Seeders;

use App\Models\Asignacion;
use App\Models\AuditoriaCalificacion;
use App\Models\BoletaPago;
use App\Models\Calificacion;
use App\Models\CicloBimestral;
use App\Models\Configuracion;
use App\Models\Estudiante;
use App\Models\Formacion;
use App\Models\Idioma;
use App\Models\Inscripcion;
use App\Models\Modulo;
use App\Models\Paralelo;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Crear u obtener usuario sin recalcular bcrypt si ya existe
     */
    private function getOrCreateUser(string $email, string $name, string $rawPassword, string $roleName): User
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make($rawPassword, ['rounds' => 10]),
            ]);
        }
        if (!$user->hasRole($roleName)) {
            $user->syncRoles([$roleName]);
        }
        return $user;
    }

    /**
     * Registrar boleta, inscripción y calificación manteniendo integridad referencial y cupo
     */
    private function registrarMatricula(
        Estudiante $est,
        Modulo $modulo,
        Paralelo $paralelo,
        string $nroBoleta,
        string $banco,
        string $fechaDeposito,
        string $fechaInscripcion,
        string $estadoCalificacion,
        float $notaFinal,
        ?string $observacionCalificacion,
        int $adminId
    ): Inscripcion {
        $boleta = BoletaPago::firstOrCreate(
            ['numero_boleta' => $nroBoleta],
            [
                'estudiante_id'  => $est->id,
                'banco_entidad'  => $banco,
                'fecha_deposito' => $fechaDeposito,
                'monto'          => 250.00,
                'concepto'       => 'Matrícula ' . $modulo->nombre,
                'estado'         => 'UTILIZADA',
                'registrado_por' => $adminId,
            ]
        );

        $inscripcion = Inscripcion::firstOrCreate(
            [
                'estudiante_id' => $est->id,
                'paralelo_id'   => $paralelo->id,
            ],
            [
                'modulo_id'         => $modulo->id,
                'boleta_pago_id'    => $boleta->id,
                'fecha_inscripcion' => $fechaInscripcion,
                'estado'            => 'ACTIVA',
                'inscrito_por'      => $adminId,
            ]
        );

        if ($inscripcion->wasRecentlyCreated) {
            $paralelo->increment('cupo_ocupado');
        }

        Calificacion::updateOrCreate(
            ['inscripcion_id' => $inscripcion->id],
            [
                'nota_final'    => $notaFinal,
                'estado'        => $estadoCalificacion,
                'observaciones' => $observacionCalificacion,
                'updated_by'    => $adminId,
            ]
        );

        return $inscripcion;
    }

    /**
     * Seed the application's database — Integridad Completa 2025 y 1er Semestre 2026 (CEI - UAGRM).
     */
    public function run(): void
    {
        // 1. Matriz de Roles y Permisos RBAC
        $this->call(RoleSeeder::class);

        // Si la base de datos aún tiene solo los 5 alumnos de prueba iniciales (< 110),
        // limpiar paralelos, boletas e inscripciones previas para poblar el semestre completo sin duplicados
        if (Estudiante::count() < 110) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            AuditoriaCalificacion::truncate();
            Calificacion::truncate();
            Inscripcion::truncate();
            BoletaPago::truncate();
            Asignacion::truncate();
            Paralelo::truncate();
            CicloBimestral::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        DB::transaction(function () {
            // 2. Superadministrador Principal
            $adminUser = $this->getOrCreateUser('admin@admin.com', 'Cossio Dylan', '12345678', 'ADMINISTRADOR');

            // 3. Configuración Institucional CEI - UAGRM
            if (Configuracion::count() == 0) {
                Configuracion::create([
                    'nombre'             => 'CEI - Centro de Extensión de Idiomas',
                    'descripcion'        => 'Instituto de Formación Lingüística - Universidad Autónoma Gabriel René Moreno',
                    'direccion'          => 'Campus Universitario UAGRM, Calle Ñuflo de Chávez',
                    'telefono'           => '75657007 - 3345678',
                    'divisa'             => 'Bs',
                    'correo_electronico' => 'contacto@cei-uagrm.edu.bo',
                    'web'                => 'https://si-gestionescolar-production.up.railway.app',
                    'logo'               => 'img/logo_cei.png',
                ]);
            }

            // 4. Catálogo de Idiomas (Inglés, Guaraní y Francés)
            $ingles = Idioma::firstOrCreate(['codigo' => 'ENG'], [
                'nombre'      => 'Inglés',
                'descripcion' => 'Programa modular de Lengua Inglesa (Módulos 1 al 9: Básico, Intermedio y Avanzado)',
                'estado'      => 'ACTIVO',
            ]);

            $guarani = Idioma::firstOrCreate(['codigo' => 'GUA'], [
                'nombre'      => 'Guaraní',
                'descripcion' => 'Programa modular de Lengua y Cultura Guaraní Universitaria',
                'estado'      => 'ACTIVO',
            ]);

            $frances = Idioma::firstOrCreate(['codigo' => 'FRA'], [
                'nombre'      => 'Francés',
                'descripcion' => 'Programa modular de Lengua Francesa Comunicativa',
                'estado'      => 'ACTIVO',
            ]);

            // 5. Los 10 Ciclos Bimestrales (6 de 2025 + 3 del 1er Semestre 2026 + 1 Futuro)
            // Permiten que un alumno de Módulo 9 tenga sus 9 bimestres históricos completos sin huecos
            $ciclosDef = [
                1  => ['nombre' => 'Bimestre I - 2025 (Ene-Feb)',   'gestion' => 2025, 'inicio' => '2025-01-06', 'fin' => '2025-02-28', 'estado' => 'CERRADO'],
                2  => ['nombre' => 'Bimestre II - 2025 (Mar-Abr)',  'gestion' => 2025, 'inicio' => '2025-03-03', 'fin' => '2025-04-30', 'estado' => 'CERRADO'],
                3  => ['nombre' => 'Bimestre III - 2025 (May-Jun)', 'gestion' => 2025, 'inicio' => '2025-05-05', 'fin' => '2025-06-30', 'estado' => 'CERRADO'],
                4  => ['nombre' => 'Bimestre IV - 2025 (Jul-Ago)',  'gestion' => 2025, 'inicio' => '2025-07-07', 'fin' => '2025-08-30', 'estado' => 'CERRADO'],
                5  => ['nombre' => 'Bimestre V - 2025 (Sep-Oct)',   'gestion' => 2025, 'inicio' => '2025-09-01', 'fin' => '2025-10-31', 'estado' => 'CERRADO'],
                6  => ['nombre' => 'Bimestre VI - 2025 (Nov-Dic)',  'gestion' => 2025, 'inicio' => '2025-11-03', 'fin' => '2025-12-20', 'estado' => 'CERRADO'],
                7  => ['nombre' => 'Bimestre I - 2026 (Ene-Feb)',   'gestion' => 2026, 'inicio' => '2026-01-05', 'fin' => '2026-02-28', 'estado' => 'CERRADO'],
                8  => ['nombre' => 'Bimestre II - 2026 (Mar-Abr)',  'gestion' => 2026, 'inicio' => '2026-03-02', 'fin' => '2026-04-30', 'estado' => 'CERRADO'],
                9  => ['nombre' => 'Bimestre III - 2026 (May-Jun)', 'gestion' => 2026, 'inicio' => '2026-05-04', 'fin' => '2026-06-30', 'estado' => 'ACTIVO'],
                10 => ['nombre' => 'Bimestre IV - 2026 (Jul-Ago)',  'gestion' => 2026, 'inicio' => '2026-07-06', 'fin' => '2026-08-31', 'estado' => 'FUTURO'],
            ];

            $ciclos = [];
            foreach ($ciclosDef as $orden => $cData) {
                $ciclos[$orden] = CicloBimestral::updateOrCreate(
                    ['nombre' => $cData['nombre']],
                    [
                        'gestion'      => $cData['gestion'],
                        'fecha_inicio' => $cData['inicio'],
                        'fecha_fin'    => $cData['fin'],
                        'estado'       => $cData['estado'],
                    ]
                );
            }

            // 6. Módulos Curriculares por Idioma
            // 6.1 Inglés: Módulos 1 al 9
            $modulosIngles = [];
            $prevId = null;
            for ($i = 1; $i <= 9; $i++) {
                $nivel = ($i <= 4) ? 'BASICO' : (($i <= 8) ? 'INTERMEDIO' : 'AVANZADO');
                $esGraduacion = ($i === 8 || $i === 9);
                $mod = Modulo::updateOrCreate(
                    ['idioma_id' => $ingles->id, 'numero_orden' => $i],
                    [
                        'nombre'               => "Módulo {$i}",
                        'nivel'                => $nivel,
                        'duracion_meses'       => 2,
                        'prerrequisito_id'     => $prevId,
                        'es_modulo_graduacion' => $esGraduacion,
                    ]
                );
                $modulosIngles[$i] = $mod;
                $prevId = $mod->id;
            }

            // 6.2 Guaraní: Módulos 1 al 4
            $modulosGuarani = [];
            $prevGua = null;
            for ($i = 1; $i <= 4; $i++) {
                $mg = Modulo::updateOrCreate(
                    ['idioma_id' => $guarani->id, 'numero_orden' => $i],
                    [
                        'nombre'               => "Guaraní Módulo {$i}",
                        'nivel'                => 'BASICO',
                        'duracion_meses'       => 2,
                        'prerrequisito_id'     => $prevGua,
                        'es_modulo_graduacion' => false,
                    ]
                );
                $modulosGuarani[$i] = $mg;
                $prevGua = $mg->id;
            }

            // 6.3 Francés: Módulos 1 al 4
            $modulosFrances = [];
            $prevFra = null;
            for ($i = 1; $i <= 4; $i++) {
                $mf = Modulo::updateOrCreate(
                    ['idioma_id' => $frances->id, 'numero_orden' => $i],
                    [
                        'nombre'               => "Francés Módulo {$i}",
                        'nivel'                => 'BASICO',
                        'duracion_meses'       => 2,
                        'prerrequisito_id'     => $prevFra,
                        'es_modulo_graduacion' => false,
                    ]
                );
                $modulosFrances[$i] = $mf;
                $prevFra = $mf->id;
            }

            // 7. Personal Administrativo (5 cuentas con Contraseña = CI)
            $adminStaff = [
                ['email' => 'juan.mendoza@cei.com', 'nombres' => 'Juan', 'apellidos' => 'Mendoza', 'ci' => '87654321', 'rol' => 'DIRECTOR/A GENERAL', 'fnac' => '1985-05-15', 'dir' => 'Av. Libertad 123', 'tel' => '76543210', 'prof' => 'Lic. en Lingüística y Gestión Educativa'],
                ['email' => 'carlos.rojas@cei.com', 'nombres' => 'Carlos', 'apellidos' => 'Rojas', 'ci' => '76543210', 'rol' => 'DIRECTOR/A ACADÉMICO', 'fnac' => '1978-11-22', 'dir' => 'Calle Junín 456', 'tel' => '65432109', 'prof' => 'Magíster en Educación Superior'],
                ['email' => 'ana.torres@cei.com', 'nombres' => 'Ana', 'apellidos' => 'Torres', 'ci' => '65432109', 'rol' => 'DIRECTOR/A ADMINISTRATIVO', 'fnac' => '1992-03-30', 'dir' => 'Av. Bolívar 789', 'tel' => '54321098', 'prof' => 'Lic. en Administración Financiera'],
                ['email' => 'jorge.paz@cei.com', 'nombres' => 'Jorge', 'apellidos' => 'Paz', 'ci' => '54321098', 'rol' => 'CAJERO/A', 'fnac' => '1980-07-18', 'dir' => 'Calle Sucre 321', 'tel' => '43210987', 'prof' => 'Contador Público Autorizado (Tesorería)'],
                ['email' => 'sofia.jimenez@cei.com', 'nombres' => 'Sofía', 'apellidos' => 'Jiménez', 'ci' => '43210987', 'rol' => 'SECRETARIO/A', 'fnac' => '1987-09-25', 'dir' => 'Av. América 654', 'tel' => '32109876', 'prof' => 'Secretariado Ejecutivo y Registro Académico'],
            ];

            foreach ($adminStaff as $adm) {
                $u = $this->getOrCreateUser($adm['email'], "{$adm['nombres']} {$adm['apellidos']}", $adm['ci'], $adm['rol']);
                Personal::updateOrCreate(['ci' => $adm['ci']], [
                    'user_id'          => $u->id,
                    'tipo'             => 'administrativo',
                    'nombres'          => $adm['nombres'],
                    'apellidos'        => $adm['apellidos'],
                    'fecha_nacimiento' => $adm['fnac'],
                    'direccion'        => $adm['dir'],
                    'telefono'         => $adm['tel'],
                    'profesion'        => $adm['prof'],
                    'foto'             => 'img/default-avatar.png',
                ]);
            }

            // 8. Plantel Docente Titular (11 Docentes: 9 de Inglés, 1 de Guaraní, 1 de Francés — Contraseña = CI)
            $docentesData = [
                1  => ['email' => 'maria.fernandez@cei.com', 'nombres' => 'María', 'apellidos' => 'Fernández', 'ci' => '24680246', 'tel' => '79123456', 'prof' => 'Lic. en Lengua Inglesa y Fonética', 'titulo1' => 'Licenciatura en Lenguas Modernas', 'inst1' => 'UAGRM - Facultad de Humanidades', 'titulo2' => 'Diplomado en Metodología Comunicativa', 'inst2' => 'Escuela de Postgrado UAGRM'],
                2  => ['email' => 'carlos.rios@cei.com', 'nombres' => 'Carlos', 'apellidos' => 'Ríos', 'ci' => '97531975', 'tel' => '71234567', 'prof' => 'Certificado C2 Cambridge / Docente Titular', 'titulo1' => 'Cambridge English Proficiency (CPE - C2)', 'inst1' => 'University of Cambridge Assessment', 'titulo2' => 'Licenciatura en Filología Hispano-Inglesa', 'inst2' => 'UAGRM - Facultad de Humanidades'],
                3  => ['email' => 'elena.vargas@cei.com', 'nombres' => 'Elena', 'apellidos' => 'Vargas', 'ci' => '3841201', 'tel' => '72145601', 'prof' => 'Magíster en Enseñanza del Inglés (TESOL)', 'titulo1' => 'Master of Arts in TESOL', 'inst1' => 'Universidad Autónoma Gabriel René Moreno', 'titulo2' => 'Certificación Internacional TKT Modules 1-3', 'inst2' => 'Cambridge English'],
                4  => ['email' => 'roberto.salazar@cei.com', 'nombres' => 'Roberto', 'apellidos' => 'Salazar', 'ci' => '3841202', 'tel' => '72145602', 'prof' => 'Lic. en Filología y Traducción', 'titulo1' => 'Licenciatura en Traducción e Interpretación', 'inst1' => 'UAGRM', 'titulo2' => 'Diplomado en Educación Superior Basada en Competencias', 'inst2' => 'Escuela de Postgrado UAGRM'],
                5  => ['email' => 'patricia.montero@cei.com', 'nombres' => 'Patricia', 'apellidos' => 'Montero', 'ci' => '3841203', 'tel' => '72145603', 'prof' => 'Especialista en Inglés Académico (IELTS C1)', 'titulo1' => 'Certificación Internacional IELTS Band 8.5', 'inst1' => 'British Council Internacional', 'titulo2' => 'Especialidad en Entornos Virtuales de Aprendizaje', 'inst2' => 'Postgrado UAGRM'],
                6  => ['email' => 'diego.arnez@cei.com', 'nombres' => 'Diego', 'apellidos' => 'Arnez', 'ci' => '3841204', 'tel' => '72145604', 'prof' => 'Lic. en Lingüística Aplicada', 'titulo1' => 'Licenciatura en Ciencias del Lenguaje', 'inst1' => 'UAGRM - Facultad de Humanidades', 'titulo2' => 'Michigan ECPE C2 Proficiency', 'inst2' => 'University of Michigan Assessment'],
                7  => ['email' => 'lucia.camacho@cei.com', 'nombres' => 'Lucía', 'apellidos' => 'Camacho', 'ci' => '3841205', 'tel' => '72145605', 'prof' => 'Magíster en Lingüística Inglesa', 'titulo1' => 'TOEFL iBT C2 International Certification', 'inst1' => 'ETS Educational Testing Service', 'titulo2' => 'Maestría en Educación Superior Universitaria', 'inst2' => 'Postgrado UAGRM'],
                8  => ['email' => 'fernando.justiniano@cei.com', 'nombres' => 'Fernando', 'apellidos' => 'Justiniano', 'ci' => '3841206', 'tel' => '72145606', 'prof' => 'Docente Evaluador de Grado Intermedio', 'titulo1' => 'Especialidad en Evaluación de Competencias Lingüísticas', 'inst1' => 'Postgrado Humanidades UAGRM', 'titulo2' => 'Licenciatura en Lenguas Modernas', 'inst2' => 'UAGRM'],
                9  => ['email' => 'claudia.banzer@cei.com', 'nombres' => 'Claudia', 'apellidos' => 'Bánzer', 'ci' => '3841207', 'tel' => '72145607', 'prof' => 'Doctora en Filología Inglesa y Suficiencia', 'titulo1' => 'Doctorado en Lingüística Aplicada y Traducción', 'inst1' => 'Universidad Complutense / Convenio UAGRM', 'titulo2' => 'Examinadora Oficial Internacional C1/C2', 'inst2' => 'Cambridge Assessment'],
                10 => ['email' => 'arandu.karai@cei.com', 'nombres' => 'Arandú', 'apellidos' => 'Karai Guasú', 'ci' => '3841208', 'tel' => '72145608', 'prof' => 'Lic. en Lengua y Cultura Guaraní', 'titulo1' => 'Licenciatura en Educación Intercultural Bilingüe', 'inst1' => 'UAGRM - Camiri / Humanidades', 'titulo2' => 'Especialista en Gramática Guaraní Contemporánea', 'inst2' => 'ILC Nación Guaraní'],
                11 => ['email' => 'sophie.laurent@cei.com', 'nombres' => 'Sophie', 'apellidos' => 'Laurent Ribera', 'ci' => '3841209', 'tel' => '72145609', 'prof' => 'Especialista DALF C2 en Lengua Francesa', 'titulo1' => 'Diplôme Approfondi de Langue Française (DALF C2)', 'inst1' => 'Alliance Française / Ministère de l’Éducation', 'titulo2' => 'Licenciatura en Lenguas Modernas (Mención Francés)', 'inst2' => 'UAGRM - Facultad de Humanidades'],
            ];

            $docentes = [];
            foreach ($docentesData as $key => $d) {
                $uDoc = $this->getOrCreateUser($d['email'], "{$d['nombres']} {$d['apellidos']}", $d['ci'], 'DOCENTE');
                $pDoc = Personal::updateOrCreate(['ci' => $d['ci']], [
                    'user_id'          => $uDoc->id,
                    'tipo'             => 'docente',
                    'nombres'          => $d['nombres'],
                    'apellidos'        => $d['apellidos'],
                    'fecha_nacimiento' => sprintf('198%d-0%d-15', ($key % 8) + 1, ($key % 8) + 1),
                    'direccion'        => 'Av. Busch y 2do Anillo - Ciudad Universitaria',
                    'telefono'         => $d['tel'],
                    'profesion'        => $d['prof'],
                    'foto'             => 'img/default-avatar.png',
                ]);
                $docentes[$key] = $pDoc;

                Formacion::firstOrCreate(
                    ['personal_id' => $pDoc->id, 'titulo' => $d['titulo1']],
                    [
                        'institucion'      => $d['inst1'],
                        'nivel'            => 'Licenciatura / Certificación Internacional',
                        'fecha_graduacion' => '2018-10-20',
                        'archivo'          => 'img/logo_cei.png',
                    ]
                );
                Formacion::firstOrCreate(
                    ['personal_id' => $pDoc->id, 'titulo' => $d['titulo2']],
                    [
                        'institucion'      => $d['inst2'],
                        'nivel'            => 'Postgrado / Especialidad',
                        'fecha_graduacion' => '2022-11-15',
                        'archivo'          => 'img/logo_cei.png',
                    ]
                );
            }

            // 9. Creación de Paralelos y Asignaciones Docentes para los Ciclos 1..8 (Históricos) y Ciclo 9 (Activo: Bimestre III - 2026)
            $turnos = ['MAÑANA', 'TARDE', 'NOCHE'];
            $horarios = [
                'MAÑANA' => ['07:30:00', '09:30:00'],
                'TARDE'  => ['14:30:00', '16:30:00'],
                'NOCHE'  => ['19:00:00', '21:00:00'],
            ];

            // $paralelosHist[$cicloIdx][$moduloOrden] => Paralelo
            $paralelosHist = [];
            for ($cIdx = 1; $cIdx <= 8; $cIdx++) {
                $cicloObj = $ciclos[$cIdx];
                for ($mIdx = 1; $mIdx <= 8; $mIdx++) {
                    // Solo necesitamos crear los paralelos históricos que realmente se cursan en la matriz diagonal
                    $modObj = $modulosIngles[$mIdx];
                    $docObj = $docentes[$mIdx];
                    $turno = $turnos[($cIdx + $mIdx) % 3];
                    $esVirtual = (($cIdx + $mIdx) % 2 === 0);

                    $pHist = Paralelo::firstOrCreate(
                        ['nombre' => "G{$mIdx}-C{$cIdx}", 'ciclo_id' => $cicloObj->id, 'modulo_id' => $modObj->id],
                        [
                            'modalidad'     => $esVirtual ? 'VIRTUAL' : 'PRESENCIAL',
                            'aula_o_enlace' => $esVirtual ? "https://meet.google.com/cei-c{$cIdx}-m{$mIdx}" : "Aula 20{$mIdx} - Pabellón CEI",
                            'turno'         => $turno,
                            'hora_inicio'   => $horarios[$turno][0],
                            'hora_fin'      => $horarios[$turno][1],
                            'cupo_maximo'   => 30,
                            'cupo_ocupado'  => 0,
                        ]
                    );
                    Asignacion::firstOrCreate(
                        ['personal_id' => $docObj->id, 'paralelo_id' => $pHist->id],
                        ['fecha_asignacion' => $cicloObj->fecha_inicio, 'estado' => 'activo']
                    );
                    $paralelosHist[$cIdx][$mIdx] = $pHist;
                }
            }

            // Paralelos Activos en Ciclo 9 (Bimestre III - 2026 May-Jun): 2 por cada nivel de Inglés (1 Presencial y 1 Virtual)
            $paralelosActivosPres = [];
            $paralelosActivosVirt = [];
            $cicloActivo = $ciclos[9];

            for ($mIdx = 1; $mIdx <= 9; $mIdx++) {
                $modObj = $modulosIngles[$mIdx];
                $docObj = $docentes[$mIdx];
                $turnoP = $turnos[($mIdx - 1) % 3];

                $pPres = Paralelo::firstOrCreate(
                    ['nombre' => "M{$mIdx}-P1 Presencial", 'ciclo_id' => $cicloActivo->id, 'modulo_id' => $modObj->id],
                    [
                        'modalidad'     => 'PRESENCIAL',
                        'aula_o_enlace' => "Aula 30{$mIdx} - Campus UAGRM",
                        'turno'         => $turnoP,
                        'hora_inicio'   => $horarios[$turnoP][0],
                        'hora_fin'      => $horarios[$turnoP][1],
                        'cupo_maximo'   => 25,
                        'cupo_ocupado'  => 0,
                    ]
                );
                Asignacion::firstOrCreate(
                    ['personal_id' => $docObj->id, 'paralelo_id' => $pPres->id],
                    ['fecha_asignacion' => $cicloActivo->fecha_inicio, 'estado' => 'activo']
                );
                $paralelosActivosPres[$mIdx] = $pPres;

                $pVirt = Paralelo::firstOrCreate(
                    ['nombre' => "M{$mIdx}-V1 Virtual", 'ciclo_id' => $cicloActivo->id, 'modulo_id' => $modObj->id],
                    [
                        'modalidad'     => 'VIRTUAL',
                        'aula_o_enlace' => "https://meet.google.com/cei-uagrm-mod{$mIdx}",
                        'turno'         => 'NOCHE',
                        'hora_inicio'   => '19:00:00',
                        'hora_fin'      => '21:00:00',
                        'cupo_maximo'   => 30,
                        'cupo_ocupado'  => 0,
                    ]
                );
                Asignacion::firstOrCreate(
                    ['personal_id' => $docObj->id, 'paralelo_id' => $pVirt->id],
                    ['fecha_asignacion' => $cicloActivo->fecha_inicio, 'estado' => 'activo']
                );
                $paralelosActivosVirt[$mIdx] = $pVirt;
            }

            // Paralelos de Guaraní y Francés (Histórico Bim II y Activos Bim III)
            $parGuaHist1 = Paralelo::firstOrCreate(
                ['nombre' => 'GUA-M1 (Bim II)', 'ciclo_id' => $ciclos[8]->id, 'modulo_id' => $modulosGuarani[1]->id],
                ['modalidad' => 'PRESENCIAL', 'aula_o_enlace' => 'Aula 105 - Humanidades', 'turno' => 'MAÑANA', 'hora_inicio' => '07:30:00', 'hora_fin' => '09:30:00', 'cupo_maximo' => 25, 'cupo_ocupado' => 0]
            );
            Asignacion::firstOrCreate(['personal_id' => $docentes[10]->id, 'paralelo_id' => $parGuaHist1->id], ['fecha_asignacion' => $ciclos[8]->fecha_inicio, 'estado' => 'activo']);

            $parGuaAct1 = Paralelo::firstOrCreate(
                ['nombre' => 'GUA-M1 Presencial', 'ciclo_id' => $cicloActivo->id, 'modulo_id' => $modulosGuarani[1]->id],
                ['modalidad' => 'PRESENCIAL', 'aula_o_enlace' => 'Aula 105 - Humanidades', 'turno' => 'TARDE', 'hora_inicio' => '14:30:00', 'hora_fin' => '16:30:00', 'cupo_maximo' => 25, 'cupo_ocupado' => 0]
            );
            Asignacion::firstOrCreate(['personal_id' => $docentes[10]->id, 'paralelo_id' => $parGuaAct1->id], ['fecha_asignacion' => $cicloActivo->fecha_inicio, 'estado' => 'activo']);

            $parGuaAct2 = Paralelo::firstOrCreate(
                ['nombre' => 'GUA-M2 Virtual', 'ciclo_id' => $cicloActivo->id, 'modulo_id' => $modulosGuarani[2]->id],
                ['modalidad' => 'VIRTUAL', 'aula_o_enlace' => 'https://meet.google.com/cei-guarani-m2', 'turno' => 'NOCHE', 'hora_inicio' => '19:00:00', 'hora_fin' => '21:00:00', 'cupo_maximo' => 25, 'cupo_ocupado' => 0]
            );
            Asignacion::firstOrCreate(['personal_id' => $docentes[10]->id, 'paralelo_id' => $parGuaAct2->id], ['fecha_asignacion' => $cicloActivo->fecha_inicio, 'estado' => 'activo']);

            $parFraHist1 = Paralelo::firstOrCreate(
                ['nombre' => 'FRA-M1 (Bim II)', 'ciclo_id' => $ciclos[8]->id, 'modulo_id' => $modulosFrances[1]->id],
                ['modalidad' => 'VIRTUAL', 'aula_o_enlace' => 'https://meet.google.com/cei-frances-b2', 'turno' => 'NOCHE', 'hora_inicio' => '19:00:00', 'hora_fin' => '21:00:00', 'cupo_maximo' => 25, 'cupo_ocupado' => 0]
            );
            Asignacion::firstOrCreate(['personal_id' => $docentes[11]->id, 'paralelo_id' => $parFraHist1->id], ['fecha_asignacion' => $ciclos[8]->fecha_inicio, 'estado' => 'activo']);

            $parFraAct1 = Paralelo::firstOrCreate(
                ['nombre' => 'FRA-M1 Presencial', 'ciclo_id' => $cicloActivo->id, 'modulo_id' => $modulosFrances[1]->id],
                ['modalidad' => 'PRESENCIAL', 'aula_o_enlace' => 'Aula 108 - Humanidades', 'turno' => 'MAÑANA', 'hora_inicio' => '07:30:00', 'hora_fin' => '09:30:00', 'cupo_maximo' => 25, 'cupo_ocupado' => 0]
            );
            Asignacion::firstOrCreate(['personal_id' => $docentes[11]->id, 'paralelo_id' => $parFraAct1->id], ['fecha_asignacion' => $cicloActivo->fecha_inicio, 'estado' => 'activo']);

            $parFraAct2 = Paralelo::firstOrCreate(
                ['nombre' => 'FRA-M2 Virtual', 'ciclo_id' => $cicloActivo->id, 'modulo_id' => $modulosFrances[2]->id],
                ['modalidad' => 'VIRTUAL', 'aula_o_enlace' => 'https://meet.google.com/cei-frances-m2', 'turno' => 'NOCHE', 'hora_inicio' => '19:00:00', 'hora_fin' => '21:00:00', 'cupo_maximo' => 25, 'cupo_ocupado' => 0]
            );
            Asignacion::firstOrCreate(['personal_id' => $docentes[11]->id, 'paralelo_id' => $parFraAct2->id], ['fecha_asignacion' => $cicloActivo->fecha_inicio, 'estado' => 'activo']);

            // 10. Población Estudiantil Completa: 110 Estudiantes (90 en Inglés + 10 en Guaraní + 10 en Francés)
            $nombresM = ['Daniel', 'Roberto', 'Lucas', 'Mateo', 'Sebastián', 'Alejandro', 'Gabriel', 'Nicolás', 'Samuel', 'Joaquín', 'Andrés', 'Leonardo', 'Adrián', 'Bruno', 'Martín', 'Hugo', 'Rodrigo', 'Álvaro', 'Gonzalo', 'Iván'];
            $nombresF = ['Carla', 'Valeria', 'Camila', 'Mariana', 'Luciana', 'Sofía', 'Fernanda', 'Natalia', 'Andrea', 'Gabriela', 'Daniela', 'Victoria', 'Isabella', 'Paula', 'Renata', 'Carolina', 'Elena', 'Adriana', 'Claudia', 'Mónica'];
            $apellidosCat = ['Mendoza', 'Sosa', 'Guzmán', 'Ortiz', 'Peña', 'Suárez', 'Roca', 'Justiniano', 'Vaca', 'Ribera', 'Saucedo', 'Parada', 'Chávez', 'Cuéllar', 'Cabrera', 'Aguilera', 'Bánzer', 'Toledo', 'Paz', 'Rivero', 'Zeballos', 'Salvatierra', 'Montaño', 'Arteaga', 'Cronenbold', 'Gutiérrez', 'Kempff', 'Landívar', 'Montero', 'Durán'];
            $carreras = [
                'Ingeniería en Sistemas', 'Ingeniería Informática', 'Ingeniería Industrial',
                'Medicina Humana', 'Bioquímica y Farmacia', 'Derecho y Cs. Políticas',
                'Ingeniería Comercial', 'Contaduría Pública', 'Relaciones Internacionales',
                'Arquitectura y Urbanismo', 'Ingeniería Civil', 'Ingeniería Petrolera',
            ];
            $bancos = [
                'BANCO UNIÓN S.A.',
                'BANCO MERCANTIL SANTA CRUZ',
                'BANCO NACIONAL DE BOLIVIA (BNB)',
                'BANCO BISA S.A.',
            ];

            $correlativoGlobal = 1;
            $inscripcionesParaAuditoria = [];

            // 10.1 Los 90 Estudiantes de Inglés (10 por cada Nivel del 1 al 9)
            for ($nivelActual = 1; $nivelActual <= 9; $nivelActual++) {
                for ($pos = 1; $pos <= 10; $pos++) {
                    $idx = $correlativoGlobal;
                    $esMujer = ($idx % 2 === 0);
                    $nombre = $esMujer ? $nombresF[($idx - 1) % count($nombresF)] : $nombresM[($idx - 1) % count($nombresM)];
                    $ap1 = $apellidosCat[($idx - 1) % count($apellidosCat)];
                    $ap2 = $apellidosCat[($idx * 3) % count($apellidosCat)];
                    if ($ap1 === $ap2) {
                        $ap2 = 'Villalba';
                    }
                    $apellidos = "{$ap1} {$ap2}";

                    $ci = (string)(8100000 + $idx);
                    $codigoEst = sprintf('CEI-2026-%04d', $idx);
                    $email = sprintf('est%03d.%s@estudiante.cei.edu.bo', $idx, strtolower(preg_replace('/[^a-zA-Z]/', '', $ap1)));
                    $carrera = $carreras[($idx - 1) % count($carreras)];
                    $banco = $bancos[($idx - 1) % count($bancos)];

                    $uEst = $this->getOrCreateUser($email, "{$nombre} {$apellidos}", $ci, 'ESTUDIANTE');

                    $est = Estudiante::updateOrCreate(['ci' => $ci], [
                        'usuario_id'        => $uEst->id,
                        'codigo_estudiante' => $codigoEst,
                        'nombres'           => $nombre,
                        'apellidos'         => $apellidos,
                        'carrera_facultad'  => $carrera,
                        'fecha_nacimiento'  => sprintf('200%d-%02d-%02d', ($idx % 5) + 1, (($idx % 12) + 1), (($idx % 25) + 1)),
                        'telefono'          => (string)(71000000 + $idx),
                        'direccion'         => 'Santa Cruz de la Sierra - Campus Universitario UAGRM',
                        'genero'            => $esMujer ? 'femenino' : 'masculino',
                        'foto'              => 'img/default-avatar.png',
                        'estado'            => 'activo',
                        'ppff_id'           => null,
                    ]);

                    // Determinar desde qué módulo inició este estudiante:
                    // - Alumnos regulares (pos 1..8, y todos los de nivel 1, 2 y 9): iniciaron en el Módulo 1 y tienen TODA la cadena 1..nivelActual sin huecos.
                    // - Alumnos por Examen de Nivel / Ubicación (pos 9 y 10 en niveles 3..8): ingresaron directamente en un módulo intermedio (ej. nivelActual - 1 o nivelActual).
                    $esExamenUbicacion = ($nivelActual >= 3 && $nivelActual <= 8 && $pos >= 9);
                    if ($esExamenUbicacion) {
                        // Pos 9 entró en el bimestre anterior (nivelActual - 1); Pos 10 entró directo en este bimestre (nivelActual)
                        $moduloInicio = ($pos === 9) ? max(2, $nivelActual - 1) : $nivelActual;
                    } else {
                        $moduloInicio = 1;
                    }

                    // A) Registrar TODOS los módulos previos ($m = $moduloInicio .. $nivelActual - 1) en sus respectivos ciclos históricos
                    for ($m = $moduloInicio; $m < $nivelActual; $m++) {
                        // Si hoy en el ciclo 9 está en $nivelActual, entonces el módulo $m lo cursó en el ciclo: 9 - ($nivelActual - $m)
                        $cicloIdxHist = 9 - ($nivelActual - $m);
                        $cicloHistObj = $ciclos[$cicloIdxHist];
                        $modHistObj = $modulosIngles[$m];
                        $parHistObj = $paralelosHist[$cicloIdxHist][$m];

                        $notaHist = min(98, 65 + (($idx + $m * 4) % 30));
                        $obsHist = ($esExamenUbicacion && $m === $moduloInicio)
                            ? 'Ingreso directo por Examen de Nivel / Ubicación'
                            : 'Módulo aprobado satisfactoriamente';

                        $this->registrarMatricula(
                            $est,
                            $modHistObj,
                            $parHistObj,
                            sprintf('BOL-C%02d-M%d-%04d', $cicloIdxHist, $m, $idx),
                            $banco,
                            $cicloHistObj->fecha_inicio,
                            $cicloHistObj->fecha_inicio,
                            'APROBADO',
                            $notaHist,
                            $obsHist,
                            $adminUser->id
                        );
                    }

                    // B) Registrar la matrícula del Módulo Actual ($nivelActual) en el Ciclo Activo (Ciclo 9: Bimestre III - 2026)
                    $modActual = $modulosIngles[$nivelActual];
                    $parActual = ($pos <= 5) ? $paralelosActivosPres[$nivelActual] : $paralelosActivosVirt[$nivelActual];

                    if ($nivelActual === 8) {
                        if ($pos <= 8) {
                            $estadoCal = 'APROBADO';
                            $notaCal = min(98, 70 + ($pos * 3));
                        } elseif ($pos === 9) {
                            $estadoCal = 'REPROBADO';
                            $notaCal = 43.00;
                        } else {
                            $estadoCal = 'PENDIENTE';
                            $notaCal = 0.00;
                        }
                    } elseif ($nivelActual === 9) {
                        if ($pos <= 9) {
                            $estadoCal = 'APROBADO';
                            $notaCal = min(99, 75 + ($pos * 2));
                        } else {
                            $estadoCal = 'REPROBADO';
                            $notaCal = 45.00;
                        }
                    } else {
                        if ($pos <= 7) {
                            $estadoCal = 'APROBADO';
                            $notaCal = min(97, 63 + ($pos * 4) + ($nivelActual % 3));
                        } elseif ($pos === 8) {
                            $estadoCal = 'REPROBADO';
                            $notaCal = 39.00 + $nivelActual;
                        } else {
                            $estadoCal = 'PENDIENTE';
                            $notaCal = 0.00;
                        }
                    }

                    $obsActual = ($esExamenUbicacion && $moduloInicio === $nivelActual)
                        ? 'Ingreso directo por Examen de Nivel / Ubicación'
                        : (($estadoCal === 'APROBADO' && $nivelActual >= 8) ? 'Hito de Graduación Aprobado' : null);

                    $insActual = $this->registrarMatricula(
                        $est,
                        $modActual,
                        $parActual,
                        sprintf('BOL-C09-M%d-%04d', $nivelActual, $idx),
                        $banco,
                        '2026-05-02',
                        '2026-05-04',
                        $estadoCal,
                        $notaCal,
                        $obsActual,
                        $adminUser->id
                    );

                    if ($pos === 1 && $nivelActual <= 8) {
                        $inscripcionesParaAuditoria[] = $insActual;
                    }

                    // C) Boletas DISPONIBLES adicionales para los primeros 15 estudiantes (para probar Nueva Matrícula en vivo)
                    if ($idx <= 15) {
                        BoletaPago::firstOrCreate(
                            ['numero_boleta' => sprintf('BOL-2026-DISP-%04d', $idx)],
                            [
                                'estudiante_id'  => $est->id,
                                'banco_entidad'  => $banco,
                                'fecha_deposito' => '2026-06-15',
                                'monto'          => 250.00,
                                'concepto'       => 'Depósito Adelantado Próximo Módulo CEI',
                                'estado'         => 'DISPONIBLE',
                                'registrado_por' => $adminUser->id,
                            ]
                        );
                    }

                    $correlativoGlobal++;
                }
            }

            // 10.2 Estudiantes de Guaraní (10 estudiantes: CEI-2026-0091 al 0100) y Francés (10 estudiantes: CEI-2026-0101 al 0110)
            for ($extraIdx = 1; $extraIdx <= 20; $extraIdx++) {
                $idx = $correlativoGlobal;
                $esGuarani = ($extraIdx <= 10);
                $posIdioma = $esGuarani ? $extraIdx : ($extraIdx - 10); // 1..10
                $esMujer = ($idx % 2 === 0);

                $nombre = $esMujer ? $nombresF[($idx - 1) % count($nombresF)] : $nombresM[($idx - 1) % count($nombresM)];
                $ap1 = $apellidosCat[($idx - 1) % count($apellidosCat)];
                $ap2 = $apellidosCat[($idx * 2) % count($apellidosCat)];
                if ($ap1 === $ap2) {
                    $ap2 = 'Ribera';
                }
                $apellidos = "{$ap1} {$ap2}";

                $ci = (string)(8100000 + $idx);
                $codigoEst = sprintf('CEI-2026-%04d', $idx);
                $email = sprintf('est%03d.%s@estudiante.cei.edu.bo', $idx, strtolower(preg_replace('/[^a-zA-Z]/', '', $ap1)));
                $carrera = $carreras[($idx - 1) % count($carreras)];
                $banco = $bancos[($idx - 1) % count($bancos)];

                $uEst = $this->getOrCreateUser($email, "{$nombre} {$apellidos}", $ci, 'ESTUDIANTE');

                $est = Estudiante::updateOrCreate(['ci' => $ci], [
                    'usuario_id'        => $uEst->id,
                    'codigo_estudiante' => $codigoEst,
                    'nombres'           => $nombre,
                    'apellidos'         => $apellidos,
                    'carrera_facultad'  => $carrera,
                    'fecha_nacimiento'  => sprintf('200%d-%02d-%02d', ($idx % 5) + 1, (($idx % 12) + 1), (($idx % 25) + 1)),
                    'telefono'          => (string)(71000000 + $idx),
                    'direccion'         => 'Santa Cruz de la Sierra - Campus Universitario UAGRM',
                    'genero'            => $esMujer ? 'femenino' : 'masculino',
                    'foto'              => 'img/default-avatar.png',
                    'estado'            => 'activo',
                    'ppff_id'           => null,
                ]);

                if ($esGuarani) {
                    if ($posIdioma <= 5) {
                        // Nivel 1 de Guaraní en Ciclo Activo
                        $this->registrarMatricula($est, $modulosGuarani[1], $parGuaAct1, sprintf('BOL-GUA-M1-%04d', $idx), $banco, '2026-05-03', '2026-05-04', ($posIdioma <= 4 ? 'APROBADO' : 'PENDIENTE'), ($posIdioma <= 4 ? 82.0 : 0.0), null, $adminUser->id);
                    } else {
                        // Aprobó Guaraní M1 en Bimestre II + Cursa Guaraní M2 en Bimestre III
                        $this->registrarMatricula($est, $modulosGuarani[1], $parGuaHist1, sprintf('BOL-GUA-H1-%04d', $idx), $banco, '2026-03-01', '2026-03-02', 'APROBADO', 85.0, 'Aprobado en Bimestre II', $adminUser->id);
                        $this->registrarMatricula($est, $modulosGuarani[2], $parGuaAct2, sprintf('BOL-GUA-M2-%04d', $idx), $banco, '2026-05-03', '2026-05-04', ($posIdioma <= 9 ? 'APROBADO' : 'REPROBADO'), ($posIdioma <= 9 ? 88.0 : 44.0), null, $adminUser->id);
                    }
                } else {
                    if ($posIdioma <= 5) {
                        // Nivel 1 de Francés en Ciclo Activo
                        $this->registrarMatricula($est, $modulosFrances[1], $parFraAct1, sprintf('BOL-FRA-M1-%04d', $idx), $banco, '2026-05-03', '2026-05-04', ($posIdioma <= 4 ? 'APROBADO' : 'PENDIENTE'), ($posIdioma <= 4 ? 80.0 : 0.0), null, $adminUser->id);
                    } else {
                        // Aprobó Francés M1 en Bimestre II + Cursa Francés M2 en Bimestre III
                        $this->registrarMatricula($est, $modulosFrances[1], $parFraHist1, sprintf('BOL-FRA-H1-%04d', $idx), $banco, '2026-03-01', '2026-03-02', 'APROBADO', 84.0, 'Aprobado en Bimestre II', $adminUser->id);
                        $this->registrarMatricula($est, $modulosFrances[2], $parFraAct2, sprintf('BOL-FRA-M2-%04d', $idx), $banco, '2026-05-03', '2026-05-04', ($posIdioma <= 9 ? 'APROBADO' : 'REPROBADO'), ($posIdioma <= 9 ? 86.0 : 41.0), null, $adminUser->id);
                    }
                }

                $correlativoGlobal++;
            }

            // 11. Historial de Inscripciones Anuladas (4 casos en /admin/inscripciones/anulaciones)
            $motivosAnulacion = [
                1 => 'Cambio de horario laboral justificado por el estudiante antes del inicio de exámenes.',
                2 => 'Solicitud escrita de retiro temporal por viaje de intercambio académico.',
                3 => 'Error involuntario en selección de modalidad (requería grupo Virtual Nocturno).',
                4 => 'Cruce de horario con laboratorio de carrera en la facultad.',
            ];

            foreach ($motivosAnulacion as $num => $motivo) {
                $estAnulado = Estudiante::where('codigo_estudiante', sprintf('CEI-2026-%04d', $num))->first();
                $parAnulado = $paralelosHist[7][$num]; // En Bimestre I - 2026, sin colisionar con sus módulos actuales
                if ($estAnulado && $parAnulado) {
                    $bolAnulada = BoletaPago::firstOrCreate(
                        ['numero_boleta' => sprintf('BOL-2026-ANUL-%04d', $num)],
                        [
                            'estudiante_id'  => $estAnulado->id,
                            'banco_entidad'  => 'BANCO UNIÓN S.A.',
                            'fecha_deposito' => '2026-01-08',
                            'monto'          => 250.00,
                            'concepto'       => 'Matrícula Reversada / Anulada',
                            'estado'         => 'ANULADA',
                            'registrado_por' => $adminUser->id,
                        ]
                    );

                    $insAnul = Inscripcion::firstOrCreate(
                        [
                            'estudiante_id' => $estAnulado->id,
                            'paralelo_id'   => $parAnulado->id,
                        ],
                        [
                            'modulo_id'         => $parAnulado->modulo_id,
                            'boleta_pago_id'    => $bolAnulada->id,
                            'fecha_inscripcion' => '2026-01-10',
                            'estado'            => 'ANULADA',
                            'motivo_anulacion'  => $motivo,
                            'inscrito_por'      => $adminUser->id,
                        ]
                    );

                    Calificacion::firstOrCreate(
                        ['inscripcion_id' => $insAnul->id],
                        [
                            'nota_final'    => 0,
                            'estado'        => 'ANULADA',
                            'observaciones' => 'Inscripción anulada',
                            'updated_by'    => $adminUser->id,
                        ]
                    );
                }
            }

            // 12. Sincronizar exactamente cupo_ocupado en todos los paralelos y asegurar arancel oficial de 250.00 Bs
            BoletaPago::where('monto', 350.00)->update(['monto' => 250.00]);
            $todosParalelos = Paralelo::all();
            foreach ($todosParalelos as $p) {
                $inscritosActivos = Inscripcion::where('paralelo_id', $p->id)->where('estado', 'ACTIVA')->count();
                if ($p->cupo_ocupado !== $inscritosActivos) {
                    $p->update(['cupo_ocupado' => $inscritosActivos]);
                }
            }

            // 13. Registros de Auditoría de Calificaciones (8 casos en /admin/roles/auditoria-notas)
            if (AuditoriaCalificacion::count() === 0) {
                foreach ($inscripcionesParaAuditoria as $k => $insAud) {
                    $cal = $insAud->calificacion;
                    if ($cal) {
                        $notaNueva = (float)$cal->nota_final;
                        $notaAnterior = max(51, $notaNueva - 6);
                        AuditoriaCalificacion::create([
                            'calificacion_id' => $cal->id,
                            'inscripcion_id'  => $insAud->id,
                            'nota_anterior'   => $notaAnterior,
                            'nota_nueva'      => $notaNueva,
                            'modificado_por'  => $adminUser->id,
                            'motivo'          => 'Rectificación de nota tras revisión de evaluación oral bimestral #' . ($k + 1),
                            'created_at'      => now()->subDays(12 - $k),
                        ]);
                    }
                }
            }
        });
    }
}
