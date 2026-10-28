<?php
namespace Tests\Feature;

use App\Models\{User, Estudiante, Idioma, Modulo, CicloBimestral, Paralelo, BoletaPago, Inscripcion, Calificacion};
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlujoAcademicoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Estudiante $estudiante;
    private Modulo $modulo;
    private Paralelo $grupo;
    private BoletaPago $boleta;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('ADMINISTRADOR');
        $alumno = User::factory()->create();
        $alumno->assignRole('ESTUDIANTE');
        $this->estudiante = Estudiante::create(['usuario_id' => $alumno->id, 'nombres' => 'Alumno', 'apellidos' => 'Prueba', 'ci' => '123456', 'fecha_nacimiento' => '2000-01-01', 'telefono' => '70000000', 'genero' => 'masculino', 'estado' => 'activo']);
        $idioma = Idioma::create(['nombre' => 'Ingles', 'codigo' => 'EN', 'estado' => 'ACTIVO']);
        $this->modulo = Modulo::create(['idioma_id' => $idioma->id, 'nombre' => 'Modulo 1', 'numero_orden' => 1, 'nivel' => 'BASICO', 'duracion_meses' => 2]);
        $ciclo = CicloBimestral::create(['nombre' => 'Bimestre de prueba', 'gestion' => 2026, 'estado' => 'ACTIVO']);
        $this->grupo = Paralelo::create(['nombre' => 'A', 'modulo_id' => $this->modulo->id, 'ciclo_id' => $ciclo->id, 'cupo_maximo' => 30, 'cupo_ocupado' => 0]);
        $this->boleta = BoletaPago::create(['estudiante_id' => $this->estudiante->id, 'numero_boleta' => 'PRUEBA-001', 'monto' => 100, 'fecha_deposito' => now()->toDateString(), 'banco_entidad' => 'Banco', 'concepto' => 'Matricula', 'estado' => 'DISPONIBLE']);
        $this->actingAs($this->admin);
    }

    private function matricular(array $cambios = [])
    {
        return $this->post(route('admin.inscripciones.store'), array_replace(['estudiante_id' => $this->estudiante->id, 'modulo_id' => $this->modulo->id, 'paralelo_id' => $this->grupo->id, 'boleta_pago_id' => $this->boleta->id], $cambios));
    }

    public function test_inscripcion_consume_boleta_y_reserva_cupo(): void
    {
        $this->matricular()->assertSessionHasNoErrors()->assertRedirect(route('admin.inscripciones.index'));
        $this->assertSame('UTILIZADA', $this->boleta->fresh()->estado);
        $this->assertEquals(1, $this->grupo->fresh()->cupo_ocupado);
        $this->assertDatabaseHas('calificaciones', ['estado' => 'PENDIENTE']);
    }

    public function test_anulacion_permite_reutilizar_boleta_y_reinscribir(): void
    {
        $this->matricular();
        $inscripcion = Inscripcion::firstOrFail();
        $this->post(route('admin.inscripciones.anular', $inscripcion), ['motivo_anulacion' => 'Correccion de grupo'])->assertSessionHasNoErrors();
        $this->assertSame('DISPONIBLE', $this->boleta->fresh()->estado);
        $this->assertEquals(0, $this->grupo->fresh()->cupo_ocupado);
        $this->matricular()->assertSessionHasNoErrors();
        $this->assertDatabaseCount('inscripciones', 2);
        $this->assertEquals(1, $this->grupo->fresh()->cupo_ocupado);
    }

    public function test_rechaza_modulo_ajeno_al_grupo(): void
    {
        $otro = Modulo::create(['idioma_id' => $this->modulo->idioma_id, 'nombre' => 'Otro', 'numero_orden' => 2, 'nivel' => 'BASICO']);
        $this->matricular(['modulo_id' => $otro->id])->assertSessionHasErrors('modulo_id');
        $this->assertDatabaseCount('inscripciones', 0);
        $this->assertSame('DISPONIBLE', $this->boleta->fresh()->estado);
    }

    public function test_rechaza_ciclo_cerrado(): void
    {
        $this->grupo->ciclo->update(['estado' => 'CERRADO']);
        $this->matricular()->assertSessionHasErrors('paralelo_id');
    }

    public function test_rechaza_estudiante_inactivo(): void
    {
        $this->estudiante->update(['estado' => 'inactivo']);
        $this->matricular()->assertSessionHasErrors('estudiante_id');
    }

    public function test_rechaza_grupo_lleno(): void
    {
        $this->grupo->update(['cupo_ocupado' => 30]);
        $this->matricular()->assertSessionHasErrors('paralelo_id');
        $this->assertSame('DISPONIBLE', $this->boleta->fresh()->estado);
    }

    public function test_rechaza_boleta_ya_utilizada(): void
    {
        $this->matricular();
        $this->matricular()->assertSessionHasErrors('boleta_pago_id');
        $this->assertDatabaseCount('inscripciones', 1);
    }

    public function test_notas_generan_auditoria_y_bloquean_anulacion_incluso_con_cero(): void
    {
        $this->matricular();
        $inscripcion = Inscripcion::firstOrFail();
        foreach ([80, 0] as $nota) {
            $this->post(route('docente.calificaciones.guardar_lote'), ['paralelo_id' => $this->grupo->id, 'notas' => [['inscripcion_id' => $inscripcion->id, 'nota_final' => $nota]]])->assertSessionHasNoErrors();
        }
        $this->assertDatabaseCount('auditoria_calificaciones', 2);
        $this->post(route('admin.inscripciones.anular', $inscripcion), ['motivo_anulacion' => 'Intento de anulacion']);
        $this->assertSame('ACTIVA', $inscripcion->fresh()->estado);
    }

    public function test_no_admite_notas_de_otro_grupo(): void
    {
        $this->matricular();
        $otro = $this->grupo->replicate(); $otro->nombre = 'B'; $otro->save();
        $this->post(route('docente.calificaciones.guardar_lote'), ['paralelo_id' => $otro->id, 'notas' => [['inscripcion_id' => Inscripcion::firstOrFail()->id, 'nota_final' => 90]]])->assertSessionHasErrors('notas.0.inscripcion_id');
        $this->assertDatabaseHas('calificaciones', ['nota_final' => 0, 'estado' => 'PENDIENTE']);
    }

    public function test_estudiante_no_puede_acceder_administracion(): void
    {
        $this->actingAs($this->estudiante->usuario);
        foreach (['admin.boletas.index', 'admin.inscripciones.index', 'admin.modulos.index', 'admin.reportes.conciliacion', 'admin.estudiantes.kardex.general'] as $ruta) {
            $this->get(route($ruta))->assertForbidden();
        }
    }

    public function test_paginas_principales_renderizan_con_datos(): void
    {
        $this->matricular();
        foreach (['home', 'admin.idiomas.index', 'admin.modulos.index', 'admin.paralelos.index', 'admin.boletas.index', 'admin.inscripciones.index', 'admin.inscripciones.create', 'admin.inscripciones.anulaciones', 'admin.reportes.conciliacion', 'admin.reportes.inscritos', 'admin.reportes.graduados', 'docente.actas.index', 'estudiante.clases.index', 'estudiante.avance.index', 'estudiante.documentos.index'] as $ruta) {
            $this->get(route($ruta))->assertOk();
        }
    }

    public function test_documentos_pdf_se_generan(): void
    {
        $this->matricular();
        foreach ([route('admin.inscripciones.pdf', Inscripcion::firstOrFail()), route('docente.actas.pdf', $this->grupo), route('admin.reportes.conciliacion_pdf'), route('admin.reportes.inscritos_pdf'), route('admin.reportes.graduados_pdf')] as $url) {
            $respuesta = $this->get($url)->assertOk();
            $this->assertStringStartsWith('%PDF-', $respuesta->getContent());
        }
    }
    public function test_catalogos_no_eliminan_historial(): void
    {
        $this->matricular();
        $inscripcion = Inscripcion::firstOrFail();
        $this->post(route('admin.inscripciones.anular', $inscripcion), ['motivo_anulacion' => 'Correccion de prueba']);
        foreach ([route('admin.modulos.destroy', $this->modulo), route('admin.paralelos.destroy', $this->grupo), route('admin.boletas.destroy', $this->boleta), route('admin.estudiantes.destroy', $this->estudiante)] as $url) {
            $this->delete($url)->assertRedirect();
        }
        $this->assertDatabaseCount('inscripciones', 1);
        $this->assertDatabaseCount('boletas_pago', 1);
        $this->assertDatabaseCount('estudiantes', 1);
        $this->assertDatabaseCount('modulos', 1);
        $this->assertDatabaseCount('paralelos', 1);
    }

    public function test_modulo_no_puede_ser_su_propio_prerrequisito(): void
    {
        $this->put(route('admin.modulos.update', $this->modulo), ['idioma_id' => $this->modulo->idioma_id, 'nombre' => 'Modulo', 'numero_orden' => 1, 'nivel' => 'BASICO', 'prerrequisito_id' => $this->modulo->id])->assertSessionHasErrors('prerrequisito_id');
    }

    public function test_certificado_rechaza_tipo_invalido_y_alumno_sin_aprobacion(): void
    {
        $this->actingAs($this->estudiante->usuario);
        $this->get(route('estudiante.documentos.certificado', 'INVALIDO'))->assertNotFound();
        $this->get(route('estudiante.documentos.certificado', 'INTERMEDIO'))->assertForbidden();
    }

    public function test_docente_no_asignado_no_puede_calificar(): void
    {
        $this->matricular();
        $user = User::factory()->create(); $user->assignRole('DOCENTE');
        $this->actingAs($user)->post(route('docente.calificaciones.guardar_lote'), ['paralelo_id' => $this->grupo->id, 'notas' => [['inscripcion_id' => Inscripcion::firstOrFail()->id, 'nota_final' => 90]]])->assertForbidden();
    }

    public function test_portal_del_estudiante_y_kardex(): void
    {
        $this->matricular();
        $this->get(route('admin.estudiantes.kardex', $this->estudiante))->assertOk();
        $this->get(route('admin.estudiantes.kardex_pdf', $this->estudiante))->assertOk();
        $this->actingAs($this->estudiante->usuario);
        foreach (['estudiante.clases.index', 'estudiante.avance.index', 'estudiante.documentos.index'] as $ruta) {
            $this->get(route($ruta))->assertOk();
        }
    }

    public function test_asignacion_detecta_horario_contenido_al_crear_y_editar(): void
    {
        $docenteUser = User::factory()->create();
        $docente = \App\Models\Personal::create(['user_id' => $docenteUser->id, 'tipo' => 'docente', 'nombres' => 'Docente', 'apellidos' => 'Prueba', 'ci' => 'DOC-1', 'fecha_nacimiento' => '1980-01-01', 'telefono' => '70000000', 'direccion' => 'Prueba', 'profesion' => 'Profesor', 'foto' => '']);
        $this->grupo->update(['hora_inicio' => '08:00', 'hora_fin' => '12:00']);
        $asignacion = \App\Models\Asignacion::create(['personal_id' => $docente->id, 'paralelo_id' => $this->grupo->id, 'fecha_asignacion' => '2026-01-01', 'estado' => 'activo']);
        $otro = $this->grupo->replicate(); $otro->nombre = 'B'; $otro->hora_inicio = '09:00'; $otro->hora_fin = '10:00'; $otro->save();
        $datos = ['personal_id' => $docente->id, 'paralelo_id' => $otro->id, 'fecha_asignacion' => '2026-01-01', 'estado' => 'activo'];
        $this->post(route('admin.asignaciones.store'), $datos)->assertRedirect()->assertSessionHas('icono', 'error');
        $this->assertDatabaseCount('asignacions', 1);
        $otraAsignacion = \App\Models\Asignacion::create(['personal_id' => $docente->id, 'paralelo_id' => $otro->id, 'fecha_asignacion' => '2026-01-01', 'estado' => 'inactivo']);
        $this->put(route('admin.asignaciones.update', $otraAsignacion), $datos)->assertRedirect()->assertSessionHas('icono', 'error');
        $this->assertSame('inactivo', $otraAsignacion->fresh()->estado);
    }

    public function test_no_reduce_cupo_por_debajo_de_inscritos(): void
    {
        $this->grupo->update(['cupo_ocupado' => 2]);
        $this->put(route('admin.paralelos.update', $this->grupo), ['nombre' => 'A', 'ciclo_id' => $this->grupo->ciclo_id, 'modulo_id' => $this->modulo->id, 'modalidad' => 'PRESENCIAL', 'aula_o_enlace' => 'Aula 1', 'turno' => 'MAÑANA', 'hora_inicio' => '08:00:00', 'hora_fin' => '10:00:00', 'cupo_maximo' => 1])->assertSessionHasNoErrors()->assertSessionHas('icono', 'error');
        $this->assertEquals(30, $this->grupo->fresh()->cupo_maximo);
    }

    public function test_dashboards_especificos_por_rol_renderizan_correctamente(): void
    {
        $this->matricular();
        $inscripcion = Inscripcion::firstOrFail();

        $this->actingAs($this->estudiante->usuario);
        $this->get(route('home'))->assertOk()->assertSee('Histórico Completo de Notas');
        $this->get(route('estudiante.documentos.kardex_pdf'))->assertOk();
        $this->get(route('estudiante.documentos.ficha_pdf', $inscripcion->id))->assertOk();

        $docenteUser = User::factory()->create();
        $docenteUser->assignRole('DOCENTE');
        $docente = \App\Models\Personal::create(['user_id' => $docenteUser->id, 'tipo' => 'docente', 'nombres' => 'Docente', 'apellidos' => 'Titular', 'ci' => 'DOC-99', 'fecha_nacimiento' => '1985-01-01', 'telefono' => '70000000', 'direccion' => 'UAGRM', 'profesion' => 'Lic. Inglés', 'foto' => 'img/default-avatar.png']);
        \App\Models\Asignacion::create(['personal_id' => $docente->id, 'paralelo_id' => $this->grupo->id, 'fecha_asignacion' => '2026-05-01', 'estado' => 'activo']);

        $this->actingAs($docenteUser)->get(route('home'))->assertOk()->assertSee('Grupos Asignados');

        $secretariaUser = User::factory()->create();
        $secretariaUser->assignRole('SECRETARIO/A');
        $this->actingAs($secretariaUser)->get(route('home'))->assertOk()->assertSee('SECRETARIO/A');

        $cajeroUser = User::factory()->create();
        $cajeroUser->assignRole('CAJERO/A');
        $this->actingAs($cajeroUser)->get(route('home'))->assertOk()->assertSee('CAJERO/A');
    }

}
