# CONFIGURACIÓN DEL MENÚ ADMINLTE POR PAQUETES (SUBMENÚS)
## Sistema de Gestión Académica para Instituto Universitario de Idiomas (CEI)

Este archivo define la estructura del menú lateral de **AdminLTE 3** organizada estrictamente en **8 Paquetes temáticos**, donde cada paquete cuenta con al menos **3 Casos de Uso (CU) como submenús**, respetando los permisos de acceso de **Spatie**.

---

### 1. Mapa General del Menú Lateral

```text
📁 PAQUETE 1: ADMINISTRACIÓN Y SEGURIDAD
   ├── CU-01: Configuración Institucional
   ├── CU-02: Gestión de Usuarios
   └── CU-03: Roles y Permisos (con Auditoría de Notas)

📁 PAQUETE 2: CATÁLOGOS ACADÉMICOS
   ├── CU-04: Gestión de Idiomas
   ├── CU-05: Módulos y Niveles (1 al 9)
   └── CU-06: Ciclos, Modalidades y Paralelos (Presencial/Virtual)

📁 PAQUETE 3: PERSONAL Y ASIGNACIONES
   ├── CU-07: Gestión de Personal (Docente/Admin)
   ├── CU-08: Formación Académica del Personal
   └── CU-09: Asignación Académica a Grupos

📁 PAQUETE 4: PADRÓN DE ESTUDIANTES Y BOLETAS
   ├── CU-10: Padrón y Registro de Estudiantes
   ├── CU-11: Registro y Validación de Boletas
   └── CU-12: Kárdex Académico del Estudiante

📁 PAQUETE 5: INSCRIPCIONES Y MATRÍCULA
   ├── CU-13: Inscripción Modular
   ├── CU-14: Ficha Oficial de Matrícula (PDF)
   └── CU-15: Anulación y Reversión de Inscripciones

📁 PAQUETE 6: EVALUACIÓN Y CALIFICACIONES
   ├── CU-16: Mis Grupos Asignados (Docente)
   ├── CU-17: Registro de Nota Final del Módulo
   └── CU-18: Cierre y Emisión de Actas Oficiales (PDF)

📁 PAQUETE 7: PORTAL DEL ESTUDIANTE
   ├── CU-19: Mi Módulo y Acceso a Clases (Aula / Meet)
   ├── CU-20: Avance Curricular y Ruta de Graduación
   └── CU-21: Historial de Notas y Certificados (PDF)

📁 PAQUETE 8: REPORTES Y CONCILIACIÓN
   ├── CU-22: Conciliación de Boletas para Tesorería
   ├── CU-23: Reporte de Inscritos por Idioma y Modalidad
   └── CU-24: Reporte de Estudiantes Graduados
```

---

### 2. Configuración PHP para `config/adminlte.php`

```php
'menu' => [
    // Dashboard General (Redirige según el rol)
    [
        'text' => 'Inicio / Dashboard',
        'url'  => 'home',
        'icon' => 'fas fa-fw fa-tachometer-alt',
    ],

    ['header' => 'MÓDULOS DE GESTIÓN ACADÉMICA'],

    // =========================================================================
    // PAQUETE 1: ADMINISTRACIÓN Y SEGURIDAD
    // =========================================================================
    [
        'text'    => 'Paquete 1: Seguridad y Config.',
        'icon'    => 'fas fa-fw fa-shield-alt',
        'can'     => 'admin.seguridad.menu',
        'submenu' => [
            [
                'text' => 'CU-01: Config. Institución',
                'url'  => 'admin/configuracion',
                'icon' => 'fas fa-fw fa-university',
                'can'  => 'admin.configuracion.index',
            ],
            [
                'text' => 'CU-02: Gestión de Usuarios',
                'url'  => 'admin/usuarios',
                'icon' => 'fas fa-fw fa-users-cog',
                'can'  => 'admin.usuarios.index',
            ],
            [
                'text' => 'CU-03: Roles y Permisos',
                'url'  => 'admin/roles',
                'icon' => 'fas fa-fw fa-user-tag',
                'can'  => 'admin.roles.index',
            ],
        ],
    ],

    // =========================================================================
    // PAQUETE 2: CATÁLOGOS ACADÉMICOS
    // =========================================================================
    [
        'text'    => 'Paquete 2: Catálogos Académicos',
        'icon'    => 'fas fa-fw fa-book-open',
        'can'     => 'admin.catalogos.menu',
        'submenu' => [
            [
                'text' => 'CU-04: Gestión de Idiomas',
                'url'  => 'admin/idiomas',
                'icon' => 'fas fa-fw fa-language',
                'can'  => 'admin.idiomas.index',
            ],
            [
                'text' => 'CU-05: Módulos y Niveles',
                'url'  => 'admin/modulos',
                'icon' => 'fas fa-fw fa-layer-group',
                'can'  => 'admin.modulos.index',
            ],
            [
                'text' => 'CU-06: Ciclos y Paralelos',
                'url'  => 'admin/paralelos',
                'icon' => 'fas fa-fw fa-chalkboard',
                'can'  => 'admin.paralelos.index',
            ],
        ],
    ],

    // =========================================================================
    // PAQUETE 3: PERSONAL Y ASIGNACIONES
    // =========================================================================
    [
        'text'    => 'Paquete 3: Personal y Docencia',
        'icon'    => 'fas fa-fw fa-chalkboard-teacher',
        'can'     => 'admin.personal.menu',
        'submenu' => [
            [
                'text' => 'CU-07: Padrón de Personal',
                'url'  => 'admin/personal',
                'icon' => 'fas fa-fw fa-id-badge',
                'can'  => 'admin.personal.index',
            ],
            [
                'text' => 'CU-08: Formación Académica',
                'url'  => 'admin/formaciones',
                'icon' => 'fas fa-fw fa-graduation-cap',
                'can'  => 'admin.formaciones.index',
            ],
            [
                'text' => 'CU-09: Asignación a Grupos',
                'url'  => 'admin/asignaciones',
                'icon' => 'fas fa-fw fa-tasks',
                'can'  => 'admin.asignaciones.index',
            ],
        ],
    ],

    // =========================================================================
    // PAQUETE 4: PADRÓN DE ESTUDIANTES Y BOLETAS BANCARIAS
    // =========================================================================
    [
        'text'    => 'Paquete 4: Estudiantes y Boletas',
        'icon'    => 'fas fa-fw fa-user-graduate',
        'can'     => 'administrativo.estudiantes.menu',
        'submenu' => [
            [
                'text' => 'CU-10: Padrón de Estudiantes',
                'url'  => 'admin/estudiantes',
                'icon' => 'fas fa-fw fa-users',
                'can'  => 'admin.estudiantes.index',
            ],
            [
                'text' => 'CU-11: Registro de Boletas',
                'url'  => 'admin/boletas',
                'icon' => 'fas fa-fw fa-file-invoice-dollar',
                'can'  => 'admin.boletas.index',
            ],
            [
                'text' => 'CU-12: Kárdex Académico',
                'url'  => 'admin/estudiantes/kardex',
                'icon' => 'fas fa-fw fa-address-book',
                'can'  => 'admin.estudiantes.kardex',
            ],
        ],
    ],

    // =========================================================================
    // PAQUETE 5: INSCRIPCIONES Y MATRÍCULA
    // =========================================================================
    [
        'text'    => 'Paquete 5: Inscripción Modular',
        'icon'    => 'fas fa-fw fa-clipboard-check',
        'can'     => 'administrativo.inscripciones.menu',
        'submenu' => [
            [
                'text' => 'CU-13: Nueva Inscripción',
                'url'  => 'admin/inscripciones/create',
                'icon' => 'fas fa-fw fa-user-plus',
                'can'  => 'admin.inscripciones.create',
            ],
            [
                'text' => 'CU-14: Listado de Matrículas',
                'url'  => 'admin/inscripciones',
                'icon' => 'fas fa-fw fa-list-alt',
                'can'  => 'admin.inscripciones.index',
            ],
            [
                'text' => 'CU-15: Historial y Anulaciones',
                'url'  => 'admin/inscripciones/anulaciones',
                'icon' => 'fas fa-fw fa-undo-alt',
                'can'  => 'admin.inscripciones.destroy',
            ],
        ],
    ],

    // =========================================================================
    // PAQUETE 6: EVALUACIÓN Y CALIFICACIONES (DOCENTE)
    // =========================================================================
    [
        'text'    => 'Paquete 6: Control de Notas',
        'icon'    => 'fas fa-fw fa-marker',
        'can'     => 'docente.calificaciones.menu',
        'submenu' => [
            [
                'text' => 'CU-16: Mis Grupos Asignados',
                'url'  => 'docente/grupos',
                'icon' => 'fas fa-fw fa-users',
                'can'  => 'docente.grupos.index',
            ],
            [
                'text' => 'CU-17: Cargar Nota Final',
                'url'  => 'docente/calificaciones',
                'icon' => 'fas fa-fw fa-edit',
                'can'  => 'docente.calificaciones.create',
            ],
            [
                'text' => 'CU-18: Actas Oficiales (PDF)',
                'url'  => 'docente/actas',
                'icon' => 'fas fa-fw fa-file-pdf',
                'can'  => 'docente.actas.index',
            ],
        ],
    ],

    // =========================================================================
    // PAQUETE 7: PORTAL DEL ESTUDIANTE (AUTOGESTIÓN)
    // =========================================================================
    [
        'text'    => 'Paquete 7: Mi Aula y Avance',
        'icon'    => 'fas fa-fw fa-laptop-code',
        'can'     => 'estudiante.portal.menu',
        'submenu' => [
            [
                'text' => 'CU-19: Mi Módulo y Clases',
                'url'  => 'estudiante/modulo-activo',
                'icon' => 'fas fa-fw fa-video',
                'can'  => 'estudiante.clases.index',
            ],
            [
                'text' => 'CU-20: Avance a Graduación',
                'url'  => 'estudiante/avance-curricular',
                'icon' => 'fas fa-fw fa-award',
                'can'  => 'estudiante.avance.index',
            ],
            [
                'text' => 'CU-21: Historial y Ficha (PDF)',
                'url'  => 'estudiante/mis-documentos',
                'icon' => 'fas fa-fw fa-certificate',
                'can'  => 'estudiante.documentos.index',
            ],
        ],
    ],

    // =========================================================================
    // PAQUETE 8: REPORTES Y CONCILIACIÓN GERENCIAL
    // =========================================================================
    [
        'text'    => 'Paquete 8: Reportes y Finanzas',
        'icon'    => 'fas fa-fw fa-chart-line',
        'can'     => 'admin.reportes.menu',
        'submenu' => [
            [
                'text' => 'CU-22: Conciliación de Boletas',
                'url'  => 'admin/reportes/conciliacion-boletas',
                'icon' => 'fas fa-fw fa-money-check-alt',
                'can'  => 'admin.reportes.conciliacion',
            ],
            [
                'text' => 'CU-23: Inscritos por Modalidad',
                'url'  => 'admin/reportes/inscritos-modalidad',
                'icon' => 'fas fa-fw fa-chart-pie',
                'can'  => 'admin.reportes.inscritos',
            ],
            [
                'text' => 'CU-24: Graduados Intermedio/Avanzado',
                'url'  => 'admin/reportes/estudiantes-graduados',
                'icon' => 'fas fa-fw fa-user-check',
                'can'  => 'admin.reportes.graduados',
            ],
        ],
    ],
];
```
