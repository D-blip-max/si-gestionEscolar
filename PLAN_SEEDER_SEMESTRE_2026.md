# Plan de Poblado de Datos (Seeder) — Operación 1er Semestre 2026 (CEI - UAGRM)

## 1. Verificación de Creación Automática de Usuarios (`users`)

Se verificó en el código fuente que **siempre que se registra un Estudiante o un Personal (Docente/Administrativo)**, el sistema crea automáticamente su cuenta de acceso en la tabla `users` dentro de una transacción segura (`DB::transaction`):

| Módulo | Controlador | Correo de Acceso (`email`) | Contraseña Inicial (`password`) | Rol Asignado (Spatie) | Vínculo en BD |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Estudiantes** | `EstudianteController@store` | Correo ingresado en el formulario | **Número de CI** (`Hash::make($ci)`) | `ESTUDIANTE` | `estudiantes.usuario_id -> users.id` |
| **Docentes / Admin.** | `PersonalController@store` | Correo ingresado en el formulario | **Número de CI** (`Hash::make($ci)`) | `DOCENTE` / Rol elegido | `personals.user_id -> users.id` |

> **Regla en el Seeder:** Cada uno de los **90 estudiantes**, **9 docentes** y **5 administrativos** generados por el `DatabaseSeeder` tendrá su respectivo registro en `users` con su rol asignado y **su número de CI como contraseña**, permitiendo iniciar sesión con cualquiera de ellos para probar los portales de Docente y Estudiante.

---

## 2. Objetivo del Nuevo Seeder (`DatabaseSeeder.php`)

Simular la operación completa del **1er Semestre de la Gestión 2026 (Enero a Junio de 2026)** del **Centro de Extensión de Idiomas (CEI - UAGRM)**, garantizando que todos los módulos, tarjetas del Dashboard, Kárdex, Portales y los 3 Reportes Gerenciales tengan volumen de datos realista:

1. **10 estudiantes activos por cada uno de los 9 niveles/módulos = 90 estudiantes en total.**
2. **Estudiantes Graduados reales** tanto en **Nivel Intermedio (Módulo 8 aprobado)** como en **Nivel Avanzado (Módulo 9 aprobado)**.
3. **Estudiantes con ingreso por Examen de Nivel / Ubicación** (que iniciaron directamente en Módulos 3, 4 o 5 convalidando los anteriores).
4. **Distribución realista de calificaciones:** Estudiantes **Aprobados** ($\ge 51$ pts), **Reprobados** ($< 51$ pts) y **Cursando** (`PENDIENTE`), además de historial de **Inscripciones Anuladas** y **Auditoría de Modificación de Notas**.

---

## 3. Estructura Detallada de Datos a Generar

### 3.1. Cronología de Ciclos Bimestrales (1er Semestre 2026)

| Ciclo Bimestral | Fechas | Estado | Propósito en la Simulación |
| :--- | :--- | :--- | :--- |
| **Bimestre I - 2026 (Ene-Feb)** | `2026-01-05` al `2026-02-28` | `CERRADO` | Historial de módulos previos cursados y aprobados a inicios de semestre. |
| **Bimestre II - 2026 (Mar-Abr)** | `2026-03-02` al `2026-04-30` | `CERRADO` | Historial intermedio de promoción modular y primeras cohortes. |
| **Bimestre III - 2026 (May-Jun)** | `2026-05-04` al `2026-06-30` | `ACTIVO` | Ciclo actual de cierre del 1er semestre (grupos Presenciales y Virtuales activos). |
| **Bimestre IV - 2026 (Jul-Ago)** | `2026-07-06` al `2026-08-31` | `PLANIFICADO` | Ciclo programado para la apertura del 2do semestre 2026. |

---

### 3.2. Catálogo de Idiomas, Módulos y Paralelos

1. **Idiomas Ofertados:**
   - **Inglés (`ENG`)**: Módulos 1 al 9 completos:
     - *Nivel Básico:* Módulo 1, Módulo 2, Módulo 3, Módulo 4.
     - *Nivel Intermedio:* Módulo 5, Módulo 6, Módulo 7, **Módulo 8** (`es_modulo_graduacion = true` — *Título Intermedio*).
     - *Nivel Avanzado:* **Módulo 9** (`es_modulo_graduacion = true` — *Certificación Avanzada*).
   - **Guaraní (`GUA`)**: Módulos 1 al 4 (Nivel Básico).
   - **Francés (`FRA`)**: Módulos 1 al 4 (Nivel Básico).

2. **Paralelos / Grupos Modulares (Módulos 1 al 9):**
   - Creación de **18 paralelos en el Ciclo Activo (`Bimestre III - 2026`)** (2 paralelos por nivel: uno `PRESENCIAL` en aulas del Campus UAGRM y otro `VIRTUAL` con enlace de Google Meet, repartidos en turnos `MAÑANA`, `TARDE` y `NOCHE`), más paralelos históricos en los Bimestres I y II.

---

### 3.3. Plantel Administrativo y Docente (con Expediente Académico)

1. **Personal Administrativo (5 cuentas):**
   - `admin@admin.com` (Superadministrador)
   - `juan.mendoza@cei.com` (`DIRECTOR/A GENERAL`, CI: `87654321`)
   - `carlos.rojas@cei.com` (`DIRECTOR/A ACADÉMICO`, CI: `76543210`)
   - `ana.torres@cei.com` (`DIRECTOR/A ADMINISTRATIVO`, CI: `65432109`)
   - `jorge.paz@cei.com` (`CAJERO/A`, CI: `54321098`)
   - `sofia.jimenez@cei.com` (`SECRETARIO/A`, CI: `43210987`)

2. **Plantel Docente Titular (9 docentes — 1 titular responsable por cada nivel 1 al 9):**
   - 9 docentes con cuenta `User`, rol `DOCENTE`, asignados mediante `Asignacion` a los paralelos de los Módulos 1 al 9.
   - Cada docente contará con **títulos y certificaciones registrados en `formacions`** (Licenciatura en Lenguas Modernas, Certificación TOEFL/Cambridge C1/C2, Diplomado en Educación Superior) para poblar el módulo de **Formación Académica**.

---

### 3.4. Población Estudiantil: 90 Estudiantes (10 por cada Nivel del 1 al 9)

Se distribuirán **90 estudiantes universitarios** (`CEI-2026-0001` al `CEI-2026-0090`), a razón de **10 estudiantes situados en cada uno de los 9 niveles**:

| Nivel Actual | Cantidad | Historial Semestral (Bimestres I, II y III - 2026) | Estado de Calificaciones en el Nivel |
| :--- | :---: | :--- | :--- |
| **Módulo 1 (Básico I)** | **10 alumnos** | Ingreso nuevo en Bimestre III (`ACTIVO`). | 6 `APROBADO` ($\ge 51$), 2 `REPROBADO` ($< 51$), 2 `PENDIENTE` (Cursando). |
| **Módulo 2 (Básico II)** | **10 alumnos** | Aprobaron Mód. 1 en Bimestre II; cursan Mód. 2 en Bimestre III. | 7 `APROBADO`, 1 `REPROBADO`, 2 `PENDIENTE`. |
| **Módulo 3 (Básico III)** | **10 alumnos** | 7 por vía regular (Mód. 1 y 2 aprobados) + **3 por Examen de Nivel directo a Mód. 3**. | 7 `APROBADO`, 1 `REPROBADO`, 2 `PENDIENTE`. |
| **Módulo 4 (Básico IV)** | **10 alumnos** | 7 por vía regular + **3 por Examen de Nivel directo a Mód. 4**. | 8 `APROBADO`, 1 `REPROBADO`, 1 `PENDIENTE`. |
| **Módulo 5 (Intermedio I)** | **10 alumnos** | 6 por vía regular + **4 por Examen de Nivel directo a Mód. 5**. | 7 `APROBADO`, 2 `REPROBADO`, 1 `PENDIENTE`. |
| **Módulo 6 (Intermedio II)** | **10 alumnos** | Ingreso por Examen de Ubicación / promoción semestral en Bimestres I-III. | 8 `APROBADO`, 1 `REPROBADO`, 1 `PENDIENTE`. |
| **Módulo 7 (Intermedio III)** | **10 alumnos** | Promoción semestral avanzada (Mód. 5 y 6 aprobados en Bim. I y II). | 8 `APROBADO`, 1 `REPROBADO`, 1 `PENDIENTE`. |
| **Módulo 8 (Título Intermedio)** | **10 alumnos** | **Cohorte de Graduación Intermedia:** 8 alumnos con **Módulo 8 `APROBADO`** (notas 68 a 95 pts -> **Graduados Nivel Intermedio**), 1 `REPROBADO`, 1 `PENDIENTE`. | **8 Graduados Intermedio** listos en reportes y diplomas PDF. |
| **Módulo 9 (Título Avanzado)** | **10 alumnos** | **Cohorte de Graduación Avanzada:** Aprobaron Mód. 8 en Bimestre II y cursan Mód. 9 en Bimestre III: **9 alumnos con Módulo 9 `APROBADO`** (notas 74 a 98 pts -> **Graduados Nivel Avanzado**) y 1 `REPROBADO`. | **9 Graduados Avanzado** (y también cuentan con Mód. 8 aprobado). |

---

### 3.5. Tesorería, Anulaciones y Auditoría para Reportes

1. **Conciliación de Boletas Bancarias (`/admin/reportes/conciliacion-boletas`):**
   - Más de **180 boletas bancarias** distribuidas entre Enero y Junio de 2026 en 4 entidades financieras (`BANCO UNIÓN S.A.`, `BANCO MERCANTIL SANTA CRUZ`, `BANCO NACIONAL DE BOLIVIA (BNB)`, `BANCO BISA`).
   - Incluye boletas en estado `UTILIZADA` (vinculadas a cada inscripción), **10 boletas extra en estado `DISPONIBLE`** (para que puedas probar en vivo el formulario de Nueva Matrícula inmediatamente) y **4 boletas en estado `ANULADA`**.

2. **Historial de Anulaciones (`/admin/inscripciones/anulaciones`):**
   - **4 inscripciones anuladas** con motivos realistas (*"Cambio de turno laboral solicitado por el estudiante"*, *"Error involuntario en selección de modalidad"*, etc.) para que la tabla de Anulaciones tenga registros inmediatos.

3. **Auditoría de Notas (`/admin/roles/auditoria-notas`):**
   - **6 registros en `auditoria_calificaciones`** simulando rectificaciones de actas realizadas en el semestre (con nota anterior, nota nueva, motivo y usuario responsable).
