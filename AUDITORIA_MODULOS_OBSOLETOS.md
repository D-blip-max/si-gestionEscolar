# 📋 Auditoría Integral de Módulos y Componentes Obsoletos (Versión Final CEI - UAGRM)

**Fecha de Auditoría:** 28 de Octubre de 2026  
**Sistema:** SisLaravel6 — Sistema de Gestión Académica CEI (Centro de Extensión de Idiomas)  
**Entorno de Producción:** [si-gestionescolar-production.up.railway.app](https://si-gestionescolar-production.up.railway.app)

---

## 1. Resumen Ejecutivo y Diagnóstico Global

El sistema actual tiene su génesis en una plantilla base para administración de colegios regulares de educación escolar primaria/secundaria. Durante el desarrollo del proyecto para el **CEI (Centro de Extensión de Idiomas - UAGRM)**, se implementó una arquitectura modular universitaria organizada en **8 Módulos Funcionales**, reemplazando la dinámica escolar por un modelo de educación superior modular bimestral para adultos.

### Conclusión Principal de la Auditoría:
* **Módulos Oficiales Activos:** 8 módulos funcionales, 24 casos de uso limpios y operativos en la barra lateral de AdminLTE y en la matriz de roles RBAC.
* **Módulos Obsoletos / Código Muerto:** Existen **11 módulos heredados del sistema escolar** que no son utilizados en la versión final de producción, pero cuyos controladores, modelos, vistas y rutas aún permanecen en el repositorio.
* **Componentes Híbridos:** 3 modelos clave (`Paralelo`, `Asignacion`, `Estudiante`) fueron adaptados al CEI pero aún retienen campos o relaciones de compatibilidad heredadas.

---

## 2. Matriz de Auditoría: Módulos Activos vs. Módulos Obsoletos

| Módulo / Dominio | Estado en Versión Final | Equivalente Oficial CEI | Archivos Obsoletos Asociados |
| :--- | :---: | :--- | :--- |
| **Padres de Familia (PPFF)** | ❌ **OBSOLETO** | Estudiantes Autónomos (`EstudianteController`) | `PpffController`, `Ppff.php`, `views/admin/ppffs/` |
| **Matriculación Escolar** | ❌ **OBSOLETO** | Inscripción Modular (`InscripcionController`) | `MatriculacionController`, `Matriculacion.php`, `views/admin/matriculaciones/` |
| **Pagos en Caja / Efectivo** | ❌ **OBSOLETO** | Boletas Bancarias Cero Efectivo (`BoletaPagoController`) | `PagoController`, `Pago.php`, `views/admin/pagos/` |
| **Calificaciones Escolares** | ❌ **OBSOLETO** | Planilla Modular y Actas PDF (`DocenteCalificacionController`) | `CalificacionController`, `DetalleCalificacionController`, `Calificacion.php`, `DetalleCalificacion.php`, `views/admin/calificaciones/` |
| **Asistencias Escolares** | ❌ **OBSOLETO** | No aplica en modelo modular CEI | `AsistenciaController`, `DetalleAsistenciaController`, `Asistencia.php`, `DetalleAsistencia.php`, `views/admin/asistencias/` |
| **Grados Escolares** | ❌ **OBSOLETO** | Módulos 1 al 9 (`ModuloController`) | `GradoController`, `Grado.php`, `views/admin/grados/` |
| **Materias Escolares** | ❌ **OBSOLETO** | Idiomas y Módulos (`IdiomaController`, `ModuloController`) | `MateriaController`, `Materia.php`, `views/admin/materias/` |
| **Gestiones Escolares** | ❌ **OBSOLETO** | Ciclos Bimestrales (`CicloBimestralController`) | `GestionController`, `Gestion.php`, `views/admin/gestiones/` |
| **Periodos Trimestrales** | ❌ **OBSOLETO** | Ciclos Bimestrales (`CicloBimestralController`) | `PeriodoController`, `Periodo.php`, `views/admin/periodos/` |
| **Niveles Escolares** | ❌ **OBSOLETO** | Niveles Modulares (`BASICO`, `INTERMEDIO`, `AVANZADO`) | `NivelController`, `Nivel.php`, `views/admin/niveles/` |
| **Turnos (Entidad Relacional)** | ⚠️ **REDUNDANTE** | Campo directo `turno` en `paralelos` (`MANANA`,`TARDE`,`NOCHE`) | `TurnoController`, `Turno.php`, `views/admin/turnos/` |

---

## 3. Detalle Técnico de los Módulos No Utilizados

### 3.1. Módulo Padres de Familia (`PPFF`)
* **Por qué no se usa:** El CEI es un instituto universitario donde los estudiantes son adultos autónomos o universitarios de la UAGRM; no existe la figura de tutor o padre de familia que autorice la matrícula.
* **Evidencia en código:** En `app/Http/Controllers/EstudianteController.php` (Línea 105), el guardado asigna explícitamente `'ppff_id' => null`. No figura en el menú lateral ni en los permisos de `RoleSeeder.php`.
* **Archivos residuales:**
  - Controlador: `app/Http/Controllers/PpffController.php`
  - Modelo: `app/Models/Ppff.php`
  - Rutas: `routes/web.php` (Líneas 217-224: `admin/ppffs/*`)
  - Vistas: Carpeta `resources/views/admin/ppffs/` (`create.blade.php`, `edit.blade.php`, `index.blade.php`, `show.blade.php`)
  - Migración: `2026_03_20_140613_create_ppffs_table.php`

### 3.2. Módulo de Matriculación Escolar (`Matriculacion`)
* **Por qué no se usa:** Diseñado para inscribir alumnos a un año escolar anual completo asociado a grado, nivel y gestión. En el CEI se reemplazó íntegramente por **Inscripciones Modulares Bimestrales** con verificación de comprobante bancario universitario y control estricto de prerrequisitos (Módulos 1 al 9).
* **Archivos residuales:**
  - Controlador: `app/Http/Controllers/MatriculacionController.php`
  - Modelo: `app/Models/Matriculacion.php`
  - Rutas: `routes/web.php` (Líneas 227-237: `admin/matriculaciones/*`)
  - Vistas: Carpeta `resources/views/admin/matriculaciones/` (`create.blade.php`, `edit.blade.php`, `index.blade.php`, `show.blade.php`, `pdf.blade.php`)
  - Migración: `2026_03_22_232552_create_matriculacions_table.php`

### 3.3. Módulo Pagos en Caja / Efectivo (`Pago`)
* **Por qué no se usa:** Estructurado para el cobro manual de pensiones escolares mensuales en efectivo en caja institucional. El CEI opera bajo una **política estricta de Cero Efectivo**, donde el estudiante deposita en cuentas bancarias de la UAGRM y tesorería registra o concilia la `BoletaPago`.
* **Archivos residuales:**
  - Controlador: `app/Http/Controllers/PagoController.php`
  - Modelo: `app/Models/Pago.php`
  - Rutas: `routes/web.php` (Líneas 251-255: `admin/pagos/*`)
  - Vistas: Carpeta `resources/views/admin/pagos/` (`index.blade.php`, `comprobante.blade.php`)
  - Migración: `2026_04_06_112515_create_pagos_table.php`

### 3.4. Módulo Calificaciones Escolares Trimestrales (`Calificacion` y `DetalleCalificacion`)
* **Por qué no se usa:** Pensado para promediar dimensiones escolares del sistema educativo boliviano regular (Ser, Saber, Hacer, Decidir) por trimestres. El CEI utiliza `DocenteCalificacionController`, que asienta la **Nota Final única sobre 100 puntos**, genera actas oficiales en PDF firmadas y audita cualquier rectificación mediante `AuditoriaCalificacion`.
* **Archivos residuales:**
  - Controladores: `app/Http/Controllers/CalificacionController.php`, `DetalleCalificacionController.php`
  - Modelos: `app/Models/Calificacion.php`, `DetalleCalificacion.php`
  - Rutas: `routes/web.php` (Líneas 267-273: `admin/calificaciones/*`)
  - Vistas: Carpeta `resources/views/admin/calificaciones/` (`create.blade.php`, `index.blade.php`, `show_admin.blade.php`, `show_estudiante.blade.php`)
  - Migraciones: `2026_04_10_231050_create_calificacions_table.php`, `2026_04_10_231113_create_detalle_calificacions_table.php`

### 3.5. Módulo de Asistencias Escolares (`Asistencia` y `DetalleAsistencia`)
* **Por qué no se usa:** Diseñado para registrar asistencia diaria escolar (presente, falta, licencia, retraso). No forma parte del alcance operativo ni de los requisitos funcionales del CEI.
* **Archivos residuales:**
  - Controladores: `app/Http/Controllers/AsistenciaController.php`, `DetalleAsistenciaController.php`
  - Modelos: `app/Models/Asistencia.php`, `DetalleAsistencia.php`
  - Rutas: `routes/web.php` (Líneas 258-264: `admin/asistencias/*`)
  - Vistas: Carpeta `resources/views/admin/asistencias/` (`create.blade.php`, `index.blade.php`, `show.blade.php`, `show_estudiante.blade.php`)
  - Migraciones: `2026_04_07_222024_create_asistencias_table.php`, `2026_04_07_222045_create_detalle_asistencias_table.php`

### 3.6. Catálogos Escolares Descontinuados
* **Gestiones y Periodos (`Gestion`, `Periodo`):**
  - Reemplazados por `CicloBimestralController` (ej: *Bimestre I - 2026 (Ene-Feb)*).
  - Vistas: `resources/views/admin/gestiones/`, `resources/views/admin/periodos/`
  - Rutas: `routes/web.php` (Líneas 21-26, 110-113)
* **Niveles y Grados (`Nivel`, `Grado`):**
  - Reemplazados por `Modulo` e `Idioma` (Módulos 1 al 9, estructurados en niveles curriculares *BASICO*, *INTERMEDIO*, *AVANZADO*).
  - Vistas: `resources/views/admin/niveles/`, `resources/views/admin/grados/`
  - Rutas: `routes/web.php` (Líneas 32-35, 127-130)
* **Materias Escolares (`Materia`):**
  - En CEI los estudiantes no cursan materias diversas (Matemáticas, Historia, etc.), sino un programa continuo de un idioma determinado.
  - Vistas: `resources/views/admin/materias/`
  - Rutas: `routes/web.php` (Líneas 118-121)
* **Turnos como Entidad Independiente (`Turno`):**
  - La tabla `turnos` es redundante; los paralelos del CEI guardan directamente el turno como campo textual (`MAÑANA`, `TARDE`, `NOCHE`).
  - Vistas: `resources/views/admin/turnos/`
  - Rutas: `routes/web.php` (Líneas 40-45)

---

## 4. Componentes Híbridos / Adaptados (Con Deuda Técnica Residual)

Tres componentes del sistema fueron adaptados para soportar el flujo del CEI pero mantienen columnas o métodos legados:

```
+-----------------------------------------------------------------------------------+
| MODELO        | CAMPOS ACTIVOS CEI (USADOS)         | CAMPOS HEREDADOS (NO USADOS)|
+-----------------------------------------------------------------------------------+
| Paralelo      | ciclo_id, modulo_id, modalidad,     | grado_id                    |
|               | aula_o_enlace, turno, hora_inicio,  |                             |
|               | hora_fin, cupo_maximo, cupo_ocupado |                             |
+-----------------------------------------------------------------------------------+
| Asignacion    | personal_id, paralelo_id, estado,   | gestion_id, nivel_id,       |
|               | fecha_asignacion                    | grado_id, materia_id,       |
|               |                                     | turno_id (hechos nullables) |
+-----------------------------------------------------------------------------------+
| Estudiante    | codigo_estudiante, nombres,         | ppff_id (siempre null en    |
|               | apellidos, ci, fecha_nacimiento,    | CEI)                        |
|               | telefono, carrera_facultad, foto    |                             |
+-----------------------------------------------------------------------------------+
```

---

## 5. Auditoría de Semillas (`DatabaseSeeder.php`)

En el archivo [`database/seeders/DatabaseSeeder.php`](file:///c:/wamp64/www/SisLaravel6/database/seeders/DatabaseSeeder.php), existen bloques de prueba heredados que pueblan datos de colegio:

1. **Líneas 123-196:** Crea gestiones escolares 2023, 2024, 2025; periodos trimestrales; niveles (Inicial, Primaria, Secundaria); grados (1ro Primaria a 6to Secundaria); materias escolares (Biología, Religión, Matemáticas, etc.).
2. **Líneas 418-427:** Inserta 10 registros de padres de familia (`Ppff`).
3. **Líneas 430-527:** Inserta 30 estudiantes asignándoles `ppff_id` como hijos de esos padres.
4. **Líneas 530-550:** Inserta 20 matrículas anuales escolares (`Matriculacion`) con `grado_id` y `nivel_id`.
5. **Líneas 551-552:** Inserta asignaciones escolares viejas con `materia_id`.

> [!NOTE]
> Estos registros residen únicamente en la base de datos local si se ejecutó el seeder completo anteriormente. En la base de datos de producción en Railway, los datos operativos que mandan son los idiomas, módulos y ciclos del CEI.

---

## 6. Módulos Oficiales Activos (El Núcleo Definitivo del CEI)

Todo el flujo de producción del CEI está cubierto al 100% por estos 8 módulos limpios:

```
SISTEMA DE GESTIÓN ACADÉMICA CEI
├── 1. Seguridad y Configuración
│   ├── Configuración Institucional (Logo, datos CEI, divisas)
│   ├── Gestión de Usuarios
│   └── Roles y Permisos RBAC (Spatie)
├── 2. Catálogos Académicos
│   ├── Idiomas (Inglés, Guaraní)
│   ├── Módulos (1 al 9, Prerrequisitos y Reglas de Graduación)
│   └── Ciclos Bimestrales y Paralelos (Presencial con Aula / Virtual con Meet-Zoom)
├── 3. Personal y Docencia
│   ├── Padrón de Personal (Docente y Administrativo)
│   ├── Formación Académica (Títulos y Certificaciones)
│   └── Asignación Académica a Grupos Modulares
├── 4. Estudiantes y Boletas
│   ├── Padrón de Estudiantes Autónomos Universitarios
│   ├── Registro y Fiscalización de Boletas Bancarias (Cero Efectivo)
│   └── Kárdex Académico e Historial Modular
├── 5. Inscripciones y Matrícula
│   ├── Nueva Inscripción Modular con Validación de Boleta y Prerrequisito
│   ├── Listado y Ficha Oficial de Matrícula en PDF
│   └── Anulaciones y Reversión Controlada de Cupos y Boletas
├── 6. Control de Calificaciones (Panel Docente)
│   ├── Mis Grupos Asignados
│   ├── Cargar Nota Final del Módulo (0 a 100)
│   └── Cierre y Emisión de Actas Oficiales en PDF (Firmadas)
├── 7. Portal del Estudiante (Autogestión)
│   ├── Mi Módulo y Clases (Acceso directo a Aula / Enlace Virtual)
│   ├── Avance a Graduación (Trazabilidad Módulos 1 al 9)
│   └── Historial y Certificados Oficiales en PDF
└── 8. Reportes y Finanzas (Dirección / Tesorería)
    ├── Conciliación de Boletas Bancarias (Cero Efectivo)
    ├── Estadísticas de Inscritos por Modalidad (Presencial vs Virtual)
    └── Nómina Oficial de Egresados / Graduados (Intermedio y Avanzado)
```

---

## 7. Estado de la Depuración y Limpieza Realizada

✅ **Fase 1 completada:** Se depuró [`routes/web.php`](file:///c:/wamp64/www/SisLaravel6/routes/web.php) eliminando todas las rutas de los 11 módulos escolares obsoletos y unificando el esquema de rutas bajo los 8 módulos oficiales del CEI.
✅ **Fase 2 completada:** Se eliminaron las 11 carpetas de vistas huérfanas en `resources/views/admin/` (`ppffs`, `matriculaciones`, `pagos`, `calificaciones`, `asistencias`, `grados`, `materias`, `gestiones`, `periodos`, `niveles`, `turnos` y `index.blade.php`).
✅ **Fase 3 completada:** Se reescribió [`database/seeders/DatabaseSeeder.php`](file:///c:/wamp64/www/SisLaravel6/database/seeders/DatabaseSeeder.php) eliminando todas las semillas de colegio y dejando únicamente datos 100% compatibles con la operativa modular del CEI (Roles, Idiomas, Ciclos, Módulos 1-9, Paralelos, Docentes y Estudiantes adultos autónomos).

