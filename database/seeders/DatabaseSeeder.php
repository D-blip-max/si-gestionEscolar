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
     * Helper para crear u obtener un usuario sin recalcular bcrypt si ya existe
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
     * Helper para matricular y asentar calificación en el seeder
     */
    private function registrarMatriculaSemestral(
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
                'monto'          => 350.00,
                'concepto'       => 'Matrícula ' . $modulo->nombre . ' - Inglés CEI',
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
     * Seed the application's database — Simulación Completa 1er Semestre 2026 (CEI - UAGRM).
     */
    public function run(): void
    {
        // 1. Matriz de Roles y Permisos RBAC
        $this->call(RoleSeeder::class);

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

            // 4. Catálogo de Idiomas
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

            // 5. Cronología de Ciclos Bimestrales — 1er Semestre 2026
            $ciclo1 = CicloBimestral::updateOrCreate(['nombre' => 'Bimestre I - 2026 (Ene-Feb)'], [
                'gestion'      => 2026,
                'fecha_inicio' => '2026-01-05',
                'fecha_fin'    => '2026-02-28',
                'estado'       => 'CERRADO',
            ]);

            $ciclo2 = CicloBimestral::updateOrCreate(['nombre' => 'Bimestre II - 2026 (Mar-Abr)'], [
                'gestion'      => 2026,
                'fecha_inicio' => '2026-03-02',
                'fecha_fin'    => '2026-04-30',
                'estado'       => 'CERRADO',
            ]);

            $ciclo3 = CicloBimestral::updateOrCreate(['nombre' => 'Bimestre III - 2026 (May-Jun)'], [
                'gestion'      => 2026,
                'fecha_inicio' => '2026-05-04',
                'fecha_fin'    => '2026-06-30',
                'estado'       => 'ACTIVO',
            ]);

            CicloBimestral::updateOrCreate(['nombre' => 'Bimestre IV - 2026 (Jul-Ago)'], [
                'gestion'      => 2026,
                'fecha_inicio' => '2026-07-06',
                'fecha_fin'    => '2026-08-31',
                'estado'       => 'FUTURO',
            ]);

            // 6. Módulos 1 al 9 de Inglés (con correlatividades y reglas de graduación en Mód. 8 y 9)
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

            // Módulos básicos de Guaraní y Francés para enriquecer el catálogo
            $prevGua = null;
            for ($i = 1; $i <= 4; $i++) {
                $mg = Modulo::firstOrCreate(
                    ['idioma_id' => $guarani->id, 'numero_orden' => $i],
                    ['nombre' => "Guaraní Módulo {$i}", 'nivel' => 'BASICO', 'duracion_meses' => 2, 'prerrequisito_id' => $prevGua, 'es_modulo_graduacion' => false]
                );
                $prevGua = $mg->id;
            }

            $prevFra = null;
            for ($i = 1; $i <= 4; $i++) {
                $mf = Modulo::firstOrCreate(
                    ['idioma_id' => $frances->id, 'numero_orden' => $i],
                    ['nombre' => "Francés Módulo {$i}", 'nivel' => 'BASICO', 'duracion_meses' => 2, 'prerrequisito_id' => $prevFra, 'es_modulo_graduacion' => false]
                );
                $prevFra = $mf->id;
            }

            // 7. Personal Administrativo Institucional (Contraseña = CI)
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

            // 8. Plantel Docente Titular (9 Docentes — 1 por cada Nivel 1 al 9, Contraseña = CI)
            $docentesData = [
                1 => ['email' => 'maria.fernandez@cei.com', 'nombres' => 'María', 'apellidos' => 'Fernández', 'ci' => '24680246', 'tel' => '79123456', 'prof' => 'Lic. en Lengua Inglesa y Fonética', 'titulo' => 'Licenciatura en Lenguas Modernas', 'inst' => 'UAGRM - Facultad de Humanidades'],
                2 => ['email' => 'carlos.rios@cei.com', 'nombres' => 'Carlos', 'apellidos' => 'Ríos', 'ci' => '97531975', 'tel' => '71234567', 'prof' => 'Certificado C2 Cambridge / Docente Titular', 'titulo' => 'Cambridge English Proficiency (CPE - C2)', 'inst' => 'University of Cambridge Assessment'],
                3 => ['email' => 'elena.vargas@cei.com', 'nombres' => 'Elena', 'apellidos' => 'Vargas', 'ci' => '3841201', 'tel' => '72145601', 'prof' => 'Magíster en Enseñanza del Inglés (TESOL)', 'titulo' => 'Master of Arts in TESOL', 'inst' => 'Universidad Autónoma Gabriel René Moreno'],
                4 => ['email' => 'roberto.salazar@cei.com', 'nombres' => 'Roberto', 'apellidos' => 'Salazar', 'ci' => '3841202', 'tel' => '72145602', 'prof' => 'Lic. en Filología y Traducción', 'titulo' => 'Diplomado en Educación Superior Basada en Competencias', 'inst' => 'Escuela de Postgrado UAGRM'],
                5 => ['email' => 'patricia.montero@cei.com', 'nombres' => 'Patricia', 'apellidos' => 'Montero', 'ci' => '3841203', 'tel' => '72145603', 'prof' => 'Especialista en Inglés Académico (IELTS C1)', 'titulo' => 'Certificación Internacional IELTS Band 8.5', 'inst' => 'British Council Internacional'],
                6 => ['email' => 'diego.arnez@cei.com', 'nombres' => 'Diego', 'apellidos' => 'Arnez', 'ci' => '3841204', 'tel' => '72145604', 'prof' => 'Lic. en Lingüística Aplicada', 'titulo' => 'Licenciatura en Ciencias del Lenguaje', 'inst' => 'UAGRM - Facultad de Humanidades'],
                7 => ['email' => 'lucia.camacho@cei.com', 'nombres' => 'Lucía', 'apellidos' => 'Camacho', 'ci' => '3841205', 'tel' => '72145605', 'prof' => 'Magíster en Lingüística Inglesa', 'titulo' => 'TOEFL iBT C2 International Certification', 'inst' => 'ETS Educational Testing Service'],
                8 => ['email' => 'fernando.justiniano@cei.com', 'nombres' => 'Fernando', 'apellidos' => 'Justiniano', 'ci' => '3841206', 'tel' => '72145606', 'prof' => 'Docente Evaluador de Grado Intermedio', 'titulo' => 'Especialidad en Evaluación de Competencias Lingüísticas', 'inst' => 'Postgrado Humanidades UAGRM'],
                9 => ['email' => 'claudia.banzer@cei.com', 'nombres' => 'Claudia', 'apellidos' => 'Bánzer', 'ci' => '3841207', 'tel' => '72145607', 'prof' => 'Doctora en Filología Inglesa y Suficiencia', 'titulo' => 'Doctorado en Lingüística Aplicada y Traducción', 'inst' => 'Universidad Complutense / Convenio UAGRM'],
            ];

            $docentesPorNivel = [];
            foreach ($docentesData as $nivel => $d) {
                $uDoc = $this->getOrCreateUser($d['email'], "{$d['nombres']} {$d['apellidos']}", $d['ci'], 'DOCENTE');
                $pDoc = Personal::updateOrCreate(['ci' => $d['ci']], [
                    'user_id'          => $uDoc->id,
                    'tipo'             => 'docente',
                    'nombres'          => $d['nombres'],
                    'apellidos'        => $d['apellidos'],
                    'fecha_nacimiento' => '1982-04-1' . $nivel,
                    'direccion'        => 'Av. Busch y 2do Anillo - Santa Cruz',
                    'telefono'         => $d['tel'],
                    'profesion'        => $d['prof'],
                    'foto'             => 'img/default-avatar.png',
                ]);
                $docentesPorNivel[$nivel] = $pDoc;

                Formacion::firstOrCreate(
                    ['personal_id' => $pDoc->id, 'titulo' => $d['titulo']],
                    [
                        'institucion'      => $d['inst'],
                        'nivel'            => ($nivel >= 7) ? 'Postgrado / Maestría' : 'Licenciatura / Certificación',
                        'fecha_graduacion' => '2020-11-15',
                        'archivo'          => 'img/logo_cei.png',
                    ]
                );
            }

            // 9. Paralelos Históricos (Bimestre I y Bimestre II) y Activos (Bimestre III - 2026)
            $paralelosBim1 = [];
            $paralelosBim2 = [];
            $paralelosBim3Presencial = [];
            $paralelosBim3Virtual = [];

            $turnos = ['MAÑANA', 'TARDE', 'NOCHE'];
            $horarios = [
                'MAÑANA' => ['07:30:00', '09:30:00'],
                'TARDE'  => ['14:30:00', '16:30:00'],
                'NOCHE'  => ['19:00:00', '21:00:00'],
            ];

            for ($i = 1; $i <= 9; $i++) {
                $mod = $modulosIngles[$i];
                $docente = $docentesPorNivel[$i];
                $turnoP = $turnos[($i - 1) % 3];
                $turnoV = $turnos[$i % 3];

                // Paralelo histórico Bimestre I (Ene-Feb 2026)
                $p1 = Paralelo::firstOrCreate(
                    ['nombre' => "G{$i}-A (Bim I)", 'ciclo_id' => $ciclo1->id, 'modulo_id' => $mod->id],
                    [
                        'modalidad'     => ($i % 2 == 1) ? 'PRESENCIAL' : 'VIRTUAL',
                        'aula_o_enlace' => ($i % 2 == 1) ? "Aula 10{$i} - Pabellón CEI" : "https://meet.google.com/cei-bim1-mod{$i}",
                        'turno'         => $turnoP,
                        'hora_inicio'   => $horarios[$turnoP][0],
                        'hora_fin'      => $horarios[$turnoP][1],
                        'cupo_maximo'   => 30,
                        'cupo_ocupado'  => 0,
                    ]
                );
                Asignacion::firstOrCreate(
                    ['personal_id' => $docente->id, 'paralelo_id' => $p1->id],
                    ['fecha_asignacion' => '2026-01-05', 'estado' => 'activo']
                );
                $paralelosBim1[$i] = $p1;

                // Paralelo histórico Bimestre II (Mar-Abr 2026)
                $p2 = Paralelo::firstOrCreate(
                    ['nombre' => "G{$i}-A (Bim II)", 'ciclo_id' => $ciclo2->id, 'modulo_id' => $mod->id],
                    [
                        'modalidad'     => ($i % 2 == 0) ? 'PRESENCIAL' : 'VIRTUAL',
                        'aula_o_enlace' => ($i % 2 == 0) ? "Aula 20{$i} - Pabellón CEI" : "https://meet.google.com/cei-bim2-mod{$i}",
                        'turno'         => $turnoV,
                        'hora_inicio'   => $horarios[$turnoV][0],
                        'hora_fin'      => $horarios[$turnoV][1],
                        'cupo_maximo'   => 30,
                        'cupo_ocupado'  => 0,
                    ]
                );
                Asignacion::firstOrCreate(
                    ['personal_id' => $docente->id, 'paralelo_id' => $p2->id],
                    ['fecha_asignacion' => '2026-03-02', 'estado' => 'activo']
                );
                $paralelosBim2[$i] = $p2;

                // Paralelos Activos Bimestre III (May-Jun 2026): 1 Presencial y 1 Virtual por cada Nivel 1..9
                $p3Pres = Paralelo::firstOrCreate(
                    ['nombre' => "M{$i}-P1 Presencial", 'ciclo_id' => $ciclo3->id, 'modulo_id' => $mod->id],
                    [
                        'modalidad'     => 'PRESENCIAL',
                        'aula_o_enlace' => "Aula 30{$i} - Campus UAGRM",
                        'turno'         => $turnoP,
                        'hora_inicio'   => $horarios[$turnoP][0],
                        'hora_fin'      => $horarios[$turnoP][1],
                        'cupo_maximo'   => 25,
                        'cupo_ocupado'  => 0,
                    ]
                );
                Asignacion::firstOrCreate(
                    ['personal_id' => $docente->id, 'paralelo_id' => $p3Pres->id],
                    ['fecha_asignacion' => '2026-05-04', 'estado' => 'activo']
                );
                $paralelosBim3Presencial[$i] = $p3Pres;

                $p3Virt = Paralelo::firstOrCreate(
                    ['nombre' => "M{$i}-V1 Virtual", 'ciclo_id' => $ciclo3->id, 'modulo_id' => $mod->id],
                    [
                        'modalidad'     => 'VIRTUAL',
                        'aula_o_enlace' => "https://meet.google.com/cei-uagrm-mod{$i}",
                        'turno'         => 'NOCHE',
                        'hora_inicio'   => '19:00:00',
                        'hora_fin'      => '21:00:00',
                        'cupo_maximo'   => 30,
                        'cupo_ocupado'  => 0,
                    ]
                );
                Asignacion::firstOrCreate(
                    ['personal_id' => $docente->id, 'paralelo_id' => $p3Virt->id],
                    ['fecha_asignacion' => '2026-05-04', 'estado' => 'activo']
                );
                $paralelosBim3Virtual[$i] = $p3Virt;
            }

            // 10. Población de 90 Estudiantes (10 por cada Nivel del 1 al 9)
            $nombresM = ['Daniel', 'Roberto', 'Lucas', 'Mateo', 'Sebastián', 'Alejandro', 'Gabriel', 'Nicolás', 'Samuel', 'Joaquín', 'Andrés', 'Leonardo', 'Adrián', 'Bruno', 'Martín', 'Hugo', 'Rodrigo', 'Álvaro', 'Gonzalo', 'Iván'];
            $nombresF = ['Carla', 'Valeria', 'Camila', 'Mariana', 'Luciana', 'Sofía', 'Fernanda', 'Natalia', 'Andrea', 'Gabriela', 'Daniela', 'Victoria', 'Isabella', 'Paula', 'Renata', 'Carolina', 'Elena', 'Adriana', 'Claudia', 'Mónica'];
            $apellidosCat = ['Mendoza', 'Sosa', 'Guzmán', 'Ortiz', 'Peña', 'Suárez', 'Roca', 'Justiniano', 'Vaca', 'Ribera', 'Saucedo', 'Parada', 'Chávez', 'Cuéllar', 'Cabrera', 'Aguilera', 'Bánzer', 'Toledo', 'Paz', 'Rivero', 'Zeballos', 'Salvatierra', 'Montaño', 'Arteaga', 'Cronenbold', 'Gutiérrez', 'Kempff', 'Landívar', 'Montero', 'Durán'];
            $carreras = [
                'Ingeniería en Sistemas',
                'Ingeniería Informática',
                'Ingeniería Industrial',
                'Medicina Humana',
                'Bioquímica y Farmacia',
                'Derecho y Cs. Políticas',
                'Ingeniería Comercial',
                'Contaduría Pública',
                'Relaciones Internacionales',
                'Arquitectura y Urbanismo',
                'Ingeniería Civil',
                'Ingeniería Petrolera',
            ];
            $bancos = [
                'BANCO UNIÓN S.A.',
                'BANCO MERCANTIL SANTA CRUZ',
                'BANCO NACIONAL DE BOLIVIA (BNB)',
                'BANCO BISA S.A.',
            ];

            $correlativoGlobal = 1;
            $inscripcionesParaAuditoria = [];

            for ($nivelActual = 1; $nivelActual <= 9; $nivelActual++) {
                for ($pos = 1; $pos <= 10; $pos++) {
                    $idx = $correlativoGlobal;
                    $esMujer = ($idx % 2 === 0);
                    $nombre = $esMujer
                        ? $nombresF[($idx - 1) % count($nombresF)]
                        : $nombresM[($idx - 1) % count($nombresM)];
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

                    // Determinar si este estudiante entró por Examen de Nivel / Ubicación
                    // En niveles 3, 4 y 5, los alumnos pos 8, 9 y 10 ingresaron directo por Examen de Nivel
                    $esExamenNivelDirecto = in_array($nivelActual, [3, 4, 5]) && ($pos >= 8);

                    // A) Historial en Bimestre I (Ene-Feb 2026): si corresponde a su ruta y no entró directo al nivel actual
                    if (!$esExamenNivelDirecto && $nivelActual >= 3) {
                        $modBim1 = $modulosIngles[$nivelActual - 2];
                        $parBim1 = $paralelosBim1[$nivelActual - 2];
                        $notaBim1 = min(98, 66 + (($idx * 3) % 28));
                        $obsBim1 = ($nivelActual - 2 > 1)
                            ? 'Ingreso por Examen de Nivel / Ubicación y aprobado en Bimestre I'
                            : 'Módulo aprobado en Bimestre I - 2026';

                        $this->registrarMatriculaSemestral(
                            $est,
                            $modBim1,
                            $parBim1,
                            sprintf('BOL-2026-B1-%04d', $idx),
                            $banco,
                            '2026-01-04',
                            '2026-01-05',
                            'APROBADO',
                            $notaBim1,
                            $obsBim1,
                            $adminUser->id
                        );
                    }

                    // B) Historial en Bimestre II (Mar-Abr 2026): módulo inmediato anterior (nivelActual - 1)
                    if (!$esExamenNivelDirecto && $nivelActual >= 2) {
                        $modBim2 = $modulosIngles[$nivelActual - 1];
                        $parBim2 = $paralelosBim2[$nivelActual - 1];
                        $notaBim2 = min(99, 68 + (($idx * 5) % 27));
                        $obsBim2 = ($nivelActual === 2)
                            ? 'Ingreso inicial aprobado en Bimestre II - 2026'
                            : 'Promoción correlativa aprobada en Bimestre II - 2026';

                        $this->registrarMatriculaSemestral(
                            $est,
                            $modBim2,
                            $parBim2,
                            sprintf('BOL-2026-B2-%04d', $idx),
                            $banco,
                            '2026-03-01',
                            '2026-03-02',
                            'APROBADO',
                            $notaBim2,
                            $obsBim2,
                            $adminUser->id
                        );
                    }

                    // C) Matrícula en el Ciclo Actual: Bimestre III - 2026 (May-Jun) en su $nivelActual
                    // Repartir 5 en Presencial y 5 en Virtual por cada nivel
                    $modActual = $modulosIngles[$nivelActual];
                    $parActual = ($pos <= 5) ? $paralelosBim3Presencial[$nivelActual] : $paralelosBim3Virtual[$nivelActual];

                    // Distribución realista de notas en el nivel actual:
                    // - Nivel 8 (Título Intermedio): pos 1..8 APROBADO (Graduados Intermedio), pos 9 REPROBADO, pos 10 PENDIENTE
                    // - Nivel 9 (Título Avanzado): pos 1..9 APROBADO (Graduados Avanzado), pos 10 REPROBADO
                    // - Niveles 1..7: pos 1..7 APROBADO, pos 8 REPROBADO, pos 9..10 PENDIENTE (Cursando)
                    if ($nivelActual === 8) {
                        if ($pos <= 8) {
                            $estadoCal = 'APROBADO';
                            $notaCal = min(98, 70 + ($pos * 3));
                        } elseif ($pos === 9) {
                            $estadoCal = 'REPROBADO';
                            $notaCal = 42.00;
                        } else {
                            $estadoCal = 'PENDIENTE';
                            $notaCal = 0.00;
                        }
                    } elseif ($nivelActual === 9) {
                        if ($pos <= 9) {
                            $estadoCal = 'APROBADO';
                            $notaCal = min(99, 74 + ($pos * 2));
                        } else {
                            $estadoCal = 'REPROBADO';
                            $notaCal = 45.00;
                        }
                    } else {
                        if ($pos <= 7) {
                            $estadoCal = 'APROBADO';
                            $notaCal = min(97, 62 + ($pos * 4) + ($nivelActual % 3));
                        } elseif ($pos === 8) {
                            $estadoCal = 'REPROBADO';
                            $notaCal = 38.00 + $nivelActual;
                        } else {
                            $estadoCal = 'PENDIENTE';
                            $notaCal = 0.00;
                        }
                    }

                    $obsActual = $esExamenNivelDirecto
                        ? 'Ingreso directo por Examen de Nivel / Ubicación'
                        : (($estadoCal === 'APROBADO' && $nivelActual >= 8) ? 'Módulo de Graduación Aprobado satisfactoriamente' : null);

                    $insActual = $this->registrarMatriculaSemestral(
                        $est,
                        $modActual,
                        $parActual,
                        sprintf('BOL-2026-B3-%04d', $idx),
                        $banco,
                        '2026-05-02',
                        '2026-05-04',
                        $estadoCal,
                        $notaCal,
                        $obsActual,
                        $adminUser->id
                    );

                    if ($pos === 1 && $nivelActual <= 6) {
                        $inscripcionesParaAuditoria[] = $insActual;
                    }

                    // D) Para los primeros 10 estudiantes, registrar además una boleta DISPONIBLE lista para probar nuevas matrículas
                    if ($idx <= 10) {
                        BoletaPago::firstOrCreate(
                            ['numero_boleta' => sprintf('BOL-2026-DISP-%04d', $idx)],
                            [
                                'estudiante_id'  => $est->id,
                                'banco_entidad'  => $banco,
                                'fecha_deposito' => '2026-06-15',
                                'monto'          => 350.00,
                                'concepto'       => 'Depósito Adelantado Próximo Módulo CEI',
                                'estado'         => 'DISPONIBLE',
                                'registrado_por' => $adminUser->id,
                            ]
                        );
                    }

                    $correlativoGlobal++;
                }
            }

            // 11. Historial de Inscripciones Anuladas (4 casos para poblar /admin/inscripciones/anulaciones)
            $motivosAnulacion = [
                1 => 'Cambio de horario laboral justificado por el estudiante antes del inicio de exámenes.',
                2 => 'Solicitud escrita de retiro temporal por viaje de intercambio académico.',
                3 => 'Error involuntario en selección de modalidad (requería grupo Virtual Nocturno).',
                4 => 'Cruce de horario con laboratorio de carrera en la facultad.',
            ];

            foreach ($motivosAnulacion as $num => $motivo) {
                $estAnulado = Estudiante::where('codigo_estudiante', sprintf('CEI-2026-%04d', $num * 10))->first();
                $parAnulado = $paralelosBim1[($num % 4) + 1];
                if ($estAnulado && $parAnulado) {
                    $bolAnulada = BoletaPago::firstOrCreate(
                        ['numero_boleta' => sprintf('BOL-2026-ANUL-%04d', $num)],
                        [
                            'estudiante_id'  => $estAnulado->id,
                            'banco_entidad'  => 'BANCO UNIÓN S.A.',
                            'fecha_deposito' => '2026-01-08',
                            'monto'          => 350.00,
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

            // 12. Registros de Auditoría de Calificaciones (para poblar /admin/roles/auditoria-notas)
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
                            'created_at'      => now()->subDays(10 - $k),
                        ]);
                    }
                }
            }
        });
    }
}
