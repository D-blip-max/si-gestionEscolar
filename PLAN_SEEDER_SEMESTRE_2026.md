# Plan de Poblado e Integridad de Datos (Seeder) — CEI UAGRM (2025 – 1er Semestre 2026)

## 1. Análisis de Integridad de Negocio (¿Por qué hacía falta aumentar datos conexos?)

Al poner **10 estudiantes en cada uno de los 9 niveles** en el ciclo actual (**Bimestre III - 2026**), para que el sistema mantenga **integridad académica, contable y referencial al 100%** no basta con crear solo su matrícula actual:

1. **Integridad del Kárdex y Prerrequisitos (Cadena Completa Módulos $1 \dots N$):**
   - Si un estudiante regular está hoy en el **Módulo 8** o **Módulo 9**, su Kárdex Académico **no puede tener huecos**. Debe tener registradas, pagadas (con su respectiva boleta bancaria) y aprobadas ($\ge 51$ pts) **todas las materias previas desde el Módulo 1** en los ciclos bimestrales anteriores.
   - Como cada módulo dura 1 bimestre (2 meses), un alumno regular que hoy cursa el **Módulo 9** en `Bimestre III - 2026 (May-Jun)` inició el **Módulo 1** en `Bimestre I - 2025 (Ene-Feb)` y cursó ininterrumpidamente los 6 bimestres de 2025 más los 3 bimestres del 1er semestre de 2026 (**9 ciclos bimestrales consecutivos**).
   - Únicamente los estudiantes que ingresaron por **Examen de Nivel / Ubicación** (2 por nivel en niveles intermedios) inician directamente en un módulo superior (ej. Módulo 3 o 4) con observación oficial de convalidación, sin necesitar los módulos anteriores.

2. **Integridad de Catálogos Multi-Idioma (Inglés, Guaraní y Francés):**
   - Si existen los idiomas **Guaraní** y **Francés** en el catálogo y en los selectores de filtros de los reportes, no pueden quedar con `0` inscritos ni sin docentes. Se agregan docentes titulares, paralelos activos e inscripciones reales también en **Guaraní** y **Francés** para que cualquier filtro de idioma en reportes devuelva datos reales.

3. **Integridad de Paralelos, Cupos y Asignaciones Docentes:**
   - **Todo paralelo** (tanto histórico como activo) debe tener su `Asignacion` vinculada a un `Personal` docente titular, para que ninguna Acta PDF ni Kárdex salga con *"Sin Docente"*.
   - El campo `cupo_ocupado` de cada paralelo debe coincidir exactamente con el número de inscripciones en estado `ACTIVA` asociadas a ese paralelo.

4. **Integridad Financiera (1 Inscripción = 1 Boleta Bancaria Única):**
   - Por restricción `UNIQUE(boleta_pago_id)` en la tabla `inscripciones`, cada una de las ~455 inscripciones históricas y actuales tiene su propia boleta bancaria única en `boletas_pago` con fecha coherente con el ciclo bimestral en que se cursó.

---

## 2. Volumen Total de Datos Generados por el Seeder

| Tabla / Entidad | Cantidad Exacta | Detalle de Integridad |
| :--- | :---: | :--- |
| **Usuarios (`users`)** | **127 cuentas** | 1 Superadmin + 5 Administrativos + 11 Docentes + 110 Estudiantes (`password = CI`). |
| **Ciclos Bimestrales (`ciclos_bimestrales`)** | **10 ciclos** | 6 bimestres `CERRADO` de 2025 + 2 bimestres `CERRADO` de 2026 (Ene-Abr) + **1 `ACTIVO` (May-Jun 2026)** + 1 `FUTURO` (Jul-Ago 2026). |
| **Idiomas (`idiomas`) y Módulos (`modulos`)** | **3 idiomas / 17 módulos** | **Inglés** (Módulos 1 al 9), **Guaraní** (Módulos 1 al 4) y **Francés** (Módulos 1 al 4). |
| **Plantel Docente y Admin. (`personals`)** | **16 registros** | 5 Administrativos + **11 Docentes Titulares** (9 de Inglés, 1 de Guaraní, 1 de Francés). |
| **Títulos Docentes (`formacions`)** | **22 títulos** | 2 certificaciones académicas (Licenciatura + Postgrado/C2) por cada uno de los 11 docentes. |
| **Paralelos (`paralelos`) y Asignaciones (`asignacions`)** | **76 grupos / 76 asignaciones** | 54 grupos históricos (2025 y Bim I-II 2026) + **22 grupos activos en Bimestre III - 2026** (18 de Inglés + 2 de Guaraní + 2 de Francés, Presenciales y Virtuales). |
| **Estudiantes (`estudiantes`)** | **110 estudiantes** | **90 en Inglés** (exactamente **10 por cada nivel del 1 al 9**) + **10 en Guaraní** + **10 en Francés**. |
| **Inscripciones (`inscripciones`) y Notas (`calificaciones`)** | **457 matrículas** | Historial completo desde Módulo 1 hasta el módulo actual de cada estudiante + 4 inscripciones `ANULADA`. |
| **Boletas Bancarias (`boletas_pago`)** | **472 boletas** | 453 `UTILIZADA` + **15 `DISPONIBLE`** (para probar nuevas inscripciones en vivo) + 4 `ANULADA`. |
| **Auditoría de Notas (`auditoria_calificaciones`)** | **8 registros** | Rectificaciones de nota con trazabilidad de nota anterior, nota nueva, docente y motivo. |

---

## 3. Distribución Exacta de los 110 Estudiantes y Graduados

### 3.1. Programa de Inglés: 90 Estudiantes (10 por cada Nivel del 1 al 9)

| Nivel Actual (Bimestre III - 2026) | Alumnos | Trazabilidad Histórica en su Kárdex | Resultados en el Ciclo Actual |
| :--- | :---: | :--- | :--- |
| **Módulo 1** (`CEI-2026-0001` al `0010`) | 10 | 1ra matrícula en Bimestre III - 2026 (1 módulo en Kárdex). | 7 `APROBADO`, 1 `REPROBADO`, 2 `PENDIENTE` |
| **Módulo 2** (`CEI-2026-0011` al `0020`) | 10 | Mód. 1 aprobado (Bim II-2026) + Mód. 2 actual (**2 módulos en Kárdex**). | 7 `APROBADO`, 1 `REPROBADO`, 2 `PENDIENTE` |
| **Módulo 3** (`CEI-2026-0021` al `0030`) | 10 | 8 regulares (**Mód. 1, 2 y 3 completos**) + **2 por Examen de Nivel directo a Mód. 3**. | 7 `APROBADO`, 1 `REPROBADO`, 2 `PENDIENTE` |
| **Módulo 4** (`CEI-2026-0031` al `0040`) | 10 | 8 regulares (**Mód. 1 al 4 completos**) + **2 por Examen de Nivel en Mód. 3 -> 4**. | 8 `APROBADO`, 1 `REPROBADO`, 1 `PENDIENTE` |
| **Módulo 5** (`CEI-2026-0041` al `0050`) | 10 | 8 regulares (**Mód. 1 al 5 completos**) + **2 por Examen de Nivel en Mód. 4 -> 5**. | 7 `APROBADO`, 2 `REPROBADO`, 1 `PENDIENTE` |
| **Módulo 6** (`CEI-2026-0051` al `0060`) | 10 | 8 regulares (**Mód. 1 al 6 completos**) + **2 por Examen de Nivel en Mód. 4 -> 6**. | 8 `APROBADO`, 1 `REPROBADO`, 1 `PENDIENTE` |
| **Módulo 7** (`CEI-2026-0061` al `0070`) | 10 | 8 regulares (**Mód. 1 al 7 completos**) + **2 por Examen de Nivel en Mód. 5 -> 7**. | 8 `APROBADO`, 1 `REPROBADO`, 1 `PENDIENTE` |
| **Módulo 8 — Título Intermedio** (`0071` al `0080`) | 10 | 8 regulares (**Mód. 1 al 8 completos**) + 2 por Examen de Nivel desde Mód. 5. | **8 `APROBADO` (Graduados Intermedio)**, 1 `REPROBADO`, 1 `PENDIENTE` |
| **Módulo 9 — Título Avanzado** (`0081` al `0090`) | 10 | **Módulos 1 al 9 completos** (cursados desde Ene-2025 hasta Jun-2026). | **9 `APROBADO` (Graduados Avanzado + Intermedio)**, 1 `REPROBADO` |

> **Total de Estudiantes Graduados para Reportes:**
> - **Graduados Nivel Intermedio (Módulo 8 Aprobado):** **18 estudiantes** (los 8 que aprobaron Módulo 8 en el Bimestre III + los 10 de Módulo 9 que aprobaron su Módulo 8 en el Bimestre II).
> - **Graduados Nivel Avanzado (Módulo 9 Aprobado):** **9 estudiantes** (que culminaron los 9 módulos con notas entre 76 y 99 pts).

### 3.2. Programas de Guaraní y Francés: 20 Estudiantes Adicionales
- **Guaraní (`CEI-2026-0091` al `0100`):** 5 estudiantes en `Guaraní Módulo 1` y 5 estudiantes en `Guaraní Módulo 2` (con su `Guaraní Módulo 1` aprobado en el Bimestre II).
- **Francés (`CEI-2026-0101` al `0110`):** 5 estudiantes en `Francés Módulo 1` y 5 estudiantes en `Francés Módulo 2` (con su `Francés Módulo 1` aprobado en el Bimestre II).
