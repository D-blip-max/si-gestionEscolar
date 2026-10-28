# Plan Estructurado: Matriz de Accesos y Dashboards por Rol (CEI - UAGRM)

## 1. Objetivo General
Alinear la **lógica de negocio académica y financiera del Instituto CEI - UAGRM** con una experiencia de usuario única, limpia y especializada para cada uno de los **4 perfiles institucionales**:
1. **Superadministrador (`ADMINISTRADOR`)**
2. **Personal Administrativo (`DIRECTOR/A GENERAL`, `DIRECTOR/A ACADÉMICO`, `DIRECTOR/A ADMINISTRATIVO`, `SECRETARIO/A`, `CAJERO/A`)**
3. **Plantel Docente (`DOCENTE`)**
4. **Comunidad Estudiantil (`ESTUDIANTE`)**

Cada usuario visualiza en su **menú lateral** y en su **Dashboard (`/home`)** únicamente las métricas, tablas e indicadores que corresponden a su función dentro del instituto, ocultando cualquier botón o acceso ajeno a su rol.

---

## 2. Matriz de Accesos y Módulos por Rol (`RoleSeeder.php` & `config/adminlte.php`)

| Módulo / Funcionalidad | Superadmin | Dir. General | Dir. Académico | Dir. Administrativo | Secretario/a | Cajero/a | Docente | Estudiante |
| :--- | :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| **Dashboard Especializado (`/home`)** | Ejecutivo Global | Operativo Gerencial | Operativo Académico | Operativo Financiero | Operativo Ventanilla | Operativo Caja | **Dashboard Docente** | **Histórico / Kárdex Estudiante** |
| **1. Seguridad y Configuración** (Usuarios, Roles, Auditoría) | Total | Configuración | — | — | — | — | — | — |
| **2. Catálogos Académicos** (Idiomas: Inglés 9 / Guaraní 2, Ciclos, Paralelos) | Total | Consulta | Gestión Total | — | Consulta Paralelos | — | — | — |
| **3. Personal y Docencia** (Padrón, Formación, Asignaciones) | Total | Gestión Personal | Docentes y Asignaciones | — | — | — | — | — |
| **4. Estudiantes y Boletas** (Padrón, Boletas Bs. 250, Kárdex) | Total | Consulta Kárdex | Consulta Kárdex | Gestión Total | Gestión Padrón/Boletas/Kárdex | Registro Boletas y Consulta Padrón | — | — |
| **5. Inscripciones y Matrícula** (Nueva Matrícula, Listado, Anulaciones) | Total | Consulta | Consulta | Gestión + Anulaciones | Nueva Matrícula + Listado | — | — | — |
| **6. Control de Calificaciones** (Mis Grupos, Cargar Notas, Actas PDF) | Supervisión | Actas PDF | Supervisión Actas | — | Actas PDF | — | **Mis Grupos, Notas y Actas** | — |
| **7. Portal del Estudiante** (Mi Aula, Avance, Certificados y Kárdex PDF) | Supervisión | — | — | — | — | — | — | **Autogestión Total** |
| **8. Reportes y Finanzas** (Conciliación, Modalidad, Graduados) | Total | Todos los Reportes | Inscritos y Graduados | Conciliación e Inscritos | Inscritos | Conciliación de Boletas | — | — |

---

## 3. Diseño de los 4 Dashboards Especializados (`/home`)

### 3.1. Dashboard Superadministrador (`ADMINISTRADOR`)
- **Vista:** `resources/views/home.blade.php`
- **Enfoque:** Control ejecutivo global y trazabilidad histórica por Gestión Anual (`Gestión 2026`, `Gestión 2025`, `Todas`).
- **Componentes:**
  1. **Banner de Ciclo Bimestral Vigente** con selector de Gestión Anual en tiempo real.
  2. **4 KPIs Ejecutivos:**
     - Estudiantes Activos en la gestión seleccionada.
     - Recaudación Fiscalizada en Bolivianos (`Bs.`) por boletas de `250.00 Bs`.
     - Plantel de Docentes Titulares (`11` docentes: `9` de Inglés + `2` de Guaraní).
     - Matrículas Registradas en la gestión seleccionada.
  3. **Tarjetas de Módulos Principales del Sistema** con acceso directo a la administración.
  4. **Panel Operativo en Vivo del Ciclo Vigente:**
     - Resumen de ocupación de cupos por grupo en el bimestre activo (`Bimestre III - 2026`).
     - Últimas matrículas y boletas registradas con descarga directa de Ficha PDF y Kárdex.

### 3.2. Dashboard Personal Administrativo (`DIRECTOR/*`, `SECRETARIO/A`, `CAJERO/A`)
- **Vista:** `resources/views/home.blade.php` (adaptado dinámicamente al cargo administrativo)
- **Enfoque:** Gestión operativa diaria de ventanilla, tesorería y dirección según las atribuciones del cargo.
- **Componentes:**
  1. **Banner Institucional con Identificador de Cargo Activo** (ej. `SECRETARIO/A`, `CAJERO/A`, `DIRECTOR/A ACADÉMICO`) y filtro de Gestión Anual (`2026`, `2025`, `Todas`).
  2. **4 KPIs Operativos:**
     - Estudiantes Activos / Padrón Estudiantil.
     - Recaudación Fiscalizada (`Bs.`) y Boletas Disponibles para matricular.
     - Grupos / Paralelos Habilitados en el Ciclo Vigente (`22` grupos activos).
     - Matrículas Activas Registradas.
  3. **Accesos Rápidos Filtrados por Permiso (`@can`):** El usuario administrativo solo ve las tarjetas de los módulos en los que trabaja (ej. Caja solo ve Boletas, Padrón y Conciliación; Secretaría ve Estudiantes, Boletas, Matrículas y Kárdex).
  4. **2 Tablas Operativas de Trabajo Diario:**
     - **Control de Cupos del Ciclo Vigente:** Lista de grupos abiertos en el bimestre actual con modalidad (`PRESENCIAL` / `VIRTUAL`), horario y barra de cupos (`ocupados / máximo`) para inscribir alumnos sin saturar aulas.
     - **Últimas Matrículas Registradas:** Historial inmediato de las últimas inscripciones asentadas con enlace directo a la Ficha de Matrícula PDF.

### 3.3. Dashboard Único del Docente (`DOCENTE`)
- **Vista:** `resources/views/docente/dashboard.blade.php` (renderizado directamente en `/home`)
- **Enfoque:** Control académico de sus grupos asignados, cantidad de alumnos y rendimiento (aprobados, reprobados y pendientes).
- **Componentes:**
  1. **Ficha Institucional del Docente:** Avatar, Nombre Completo, Especialidad/Profesión, CI y Ciclo Bimestral Vigente.
  2. **4 KPIs Académicos del Docente:**
     - **Grupos Asignados:** Total de grupos asignados al docente (indicando cuántos pertenecen al Ciclo Vigente y cuántos a ciclos anteriores).
     - **Total Alumnos a Cargo:** Suma total de estudiantes inscritos en sus grupos (con desglose de alumnos en el bimestre actual).
     - **Alumnos Aprobados (`Nota >= 51`):** Cantidad total de estudiantes aprobados y su **Porcentaje de Aprobación (%)**.
     - **Reprobados y Pendientes:** Cantidad de alumnos reprobados (`Nota < 51`) y alumnos con calificación `PENDIENTE` en el ciclo en curso.
  3. **Tabla Principal — Mis Grupos en el Ciclo Bimestral Vigente:**
     - Muestra los grupos que dicta hoy (Presencial y Virtual), Idioma y Módulo, Turno/Horario, Aula física o botón directo a **Google Meet**, total de alumnos inscritos, contadores por color (**Aprobados / Reprobados / Pendientes**), promedio del grupo y botones directos a:
       - **Cargar / Editar Notas** (`docente.calificaciones.cargar`)
       - **Ver Nómina de Alumnos** (`docente.grupos.show`)
       - **Emitir Acta Oficial PDF** (`docente.actas.pdf`)
  4. **Tabla Secundaria — Historial de Grupos Dictados en Bimestres Anteriores:**
     - Resumen de todos los módulos que dictó en bimestres cerrados (`2025` y `2026`), con inscritos, aprobados, reprobados, promedio final del curso y descarga de **Acta Oficial PDF**.

### 3.4. Dashboard Único del Estudiante (`ESTUDIANTE`)
- **Vista:** `resources/views/estudiante/dashboard.blade.php` (renderizado directamente en `/home`)
- **Enfoque:** Histórico completo de notas de todos los cursos/módulos tomados a lo largo de su estadía en el instituto (con su nota y estado de aprobación/reprobación), junto con el acceso a su clase actual y descarga de su Kárdex y Certificados PDF.
- **Componentes:**
  1. **Tarjeta de Identidad Estudiantil:** Avatar, Nombre Completo, Código Estudiantil (`CEI-2026-XXXX`), CI, Carrera UAGRM, Idioma que cursa (`Inglés` de 9 niveles o `Guaraní` de 2 niveles) y botón directo para **Descargar Mi Kárdex Académico PDF**.
  2. **4 KPIs de Rendimiento Estudiantil:**
     - **Cursos / Módulos Tomados:** Total de módulos cursados en el instituto y nivel actual.
     - **Módulos Aprobados (`>= 51 pts`):** Total de cursos aprobados satisfactoriamente.
     - **Módulos Reprobados / En Curso:** Total de cursos reprobados (`< 51 pts`) e indicador de módulo actualmente en curso.
     - **Promedio Histórico General:** Promedio acumulado (0 a 100 pts) de todas las notas finales obtenidas en el instituto.
  3. **Banner de Mi Clase en el Ciclo Vigente:**
     - Muestra el módulo que está cursando actualmente, Grupo, Turno/Horario, Docente asignado y su Aula física o botón directo para entrar a **Sala Virtual Google Meet**, más descarga de su **Ficha de Matrícula PDF**.
  4. **Tabla Principal — Histórico Completo de Notas (Kárdex Estudiantil):**
     - Todos los cursos/módulos que el estudiante tomó desde su ingreso al CEI en orden cronológico:
       - **N°**
       - **Ciclo Bimestral y Gestión** (`2025` / `2026`)
       - **Idioma y Módulo / Nivel** (`Inglés Módulo 1..9` o `Guaraní Nivel 1..2`)
       - **Grupo, Turno y Modalidad** (`PRESENCIAL` / `VIRTUAL`)
       - **Docente Titular**
       - **N° Boleta (`250.00 Bs`)**
       - **Nota Final (`0 - 100 pts`)**
       - **Resultado Oficial (`APROBADO`, `REPROBADO`, `EN CURSO`)** y Observación académica.

---

## 4. Secuencia de Ejecución Técnica
1. **Actualizar `RoleSeeder.php` y `routes/web.php`:**
   - Ajustar permisos de roles administrativos para que Secretaría, Caja y Direcciones tengan acceso coherente a sus módulos de trabajo.
   - Habilitar rutas propias en `EstudiantePortalController` (`estudiante.documentos.kardex_pdf` y `estudiante.documentos.ficha_pdf`) para que el estudiante descargue su propio Kárdex y Ficha de Matrícula en PDF sin requerir permisos administrativos.
2. **Actualizar `HomeController.php`:**
   - Si el usuario autenticado es `ESTUDIANTE`, cargar su ficha, sus métricas académicas y su histórico completo de inscripciones/notas y renderizar `resources/views/estudiante/dashboard.blade.php`.
   - Si el usuario autenticado es `DOCENTE`, cargar su ficha de personal docente, sus asignaciones activas e históricas, el conteo de alumnos aprobados/reprobados/pendientes y renderizar `resources/views/docente/dashboard.blade.php`.
   - Si el usuario es `ADMINISTRADOR` o Personal Administrativo, cargar los indicadores por Gestión Anual más el resumen operativo del Ciclo Vigente (grupos/cupos y últimas matrículas) en `resources/views/home.blade.php`.
3. **Crear y actualizar las vistas Blade:**
   - Crear `resources/views/docente/dashboard.blade.php`.
   - Crear `resources/views/estudiante/dashboard.blade.php`.
   - Actualizar `resources/views/home.blade.php`, `resources/views/estudiante/mis_documentos.blade.php` y `resources/views/estudiante/avance_curricular.blade.php` (adaptando dinámicamente Inglés de 9 módulos y Guaraní de 2 niveles).
4. **Verificación Integral:**
   - Compilar vistas (`php artisan view:cache && php artisan view:clear`) y ejecutar la suite completa de pruebas automatizadas (`php artisan test`).

