<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kárdex Académico Oficial - {{ $estudiante->codigo_estudiante ?? $estudiante->ci }}</title>
    <style>
        @page {
            margin: 25mm 20mm 25mm 20mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1a3a5f;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h2 {
            margin: 0;
            color: #1a3a5f;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header-title h3 {
            margin: 3px 0;
            color: #444;
            font-size: 13px;
        }
        .header-title p {
            margin: 0;
            font-size: 9px;
            color: #777;
        }
        .student-box {
            width: 100%;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 15px;
        }
        .student-box table {
            width: 100%;
        }
        .student-box td {
            padding: 3px 6px;
            font-size: 11px;
        }
        .label {
            font-weight: bold;
            color: #1a3a5f;
        }
        .kardex-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }
        .kardex-table th {
            background-color: #1a3a5f;
            color: #ffffff;
            border: 1px solid #1a3a5f;
            padding: 6px;
            text-align: center;
            font-size: 9.5px;
        }
        .kardex-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }
        .kardex-table tr:nth-child(even) {
            background-color: #fbfbfb;
        }
        .aprobado {
            color: #155724;
            font-weight: bold;
        }
        .reprobado {
            color: #721c24;
            font-weight: bold;
        }
        .cursando {
            color: #004085;
            font-style: italic;
        }
        .summary-box {
            margin-top: 15px;
            border: 1px solid #ccc;
            padding: 8px;
            background-color: #fdfdfd;
        }
        .footer-signatures {
            margin-top: 50px;
            width: 100%;
        }
        .footer-signatures td {
            text-align: center;
            padding: 0 30px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 20%; text-align: left; vertical-align: middle;">
                @if($configuracion && $configuracion->logo && file_exists(public_path($configuracion->logo)))
                    <img src="{{ public_path($configuracion->logo) }}" style="max-height: 65px; max-width: 140px;" alt="Logo">
                @else
                    <div style="font-weight: bold; color: #1a3a5f; font-size: 16px;">CEI IDIOMAS</div>
                @endif
            </td>
            <td style="width: 80%;" class="header-title">
                <h2>{{ $configuracion->nombre ?? 'CENTRO DE ENSEÑANZA INTEGRAL DE IDIOMAS (CEI)' }}</h2>
                <h3>KÁRDEX ACADÉMICO OFICIAL DEL ESTUDIANTE</h3>
                <p>{{ $configuracion->direccion ?? 'Campus Universitario' }} | Teléfono: {{ $configuracion->telefono ?? 'S/N' }}</p>
            </td>
        </tr>
    </table>

    <div class="student-box">
        <table>
            <tr>
                <td style="width: 15%;" class="label">Estudiante:</td>
                <td style="width: 45%; font-weight: bold;">{{ $estudiante->apellidos }} {{ $estudiante->nombres }}</td>
                <td style="width: 15%;" class="label">Código CEI:</td>
                <td style="width: 25%; font-weight: bold;">{{ $estudiante->codigo_estudiante ?? 'CEI-'.$estudiante->id }}</td>
            </tr>
            <tr>
                <td class="label">Cédula (CI):</td>
                <td>{{ $estudiante->ci }}</td>
                <td class="label">Carrera / Origen:</td>
                <td>{{ $estudiante->carrera_facultad ?? 'Particular' }}</td>
            </tr>
            <tr>
                <td class="label">Teléfono:</td>
                <td>{{ $estudiante->telefono }}</td>
                <td class="label">Fecha Emisión:</td>
                <td>{{ date('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <table class="kardex-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th style="width: 90px;">Ciclo Bimestral</th>
                <th>Idioma / Módulo</th>
                <th style="width: 70px;">Nivel</th>
                <th style="width: 70px;">Modalidad</th>
                <th>Docente Titular</th>
                <th style="width: 50px;">Nota</th>
                <th style="width: 75px;">Condición</th>
            </tr>
        </thead>
        <tbody>
            @php
                $aprobados = 0;
            @endphp
            @forelse($estudiante->inscripciones as $ins)
                @php
                    $nota = optional($ins->calificacion)->nota_final;
                    $estadoCal = optional($ins->calificacion)->estado;
                    if ($nota !== null && $estadoCal !== 'PENDIENTE' && $nota >= 51) {
                        $aprobados++;
                    }
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional(optional($ins->paralelo)->ciclo)->nombre ?? 'Ciclo' }}</td>
                    <td style="text-align: left;">
                        <strong>{{ optional(optional($ins->modulo)->idioma)->nombre }}</strong> - {{ optional($ins->modulo)->nombre }}
                    </td>
                    <td>{{ strtoupper(optional($ins->modulo)->nivel ?? 'General') }}</td>
                    <td>{{ strtoupper(optional($ins->paralelo)->modalidad ?? 'Presencial') }}</td>
                    <td style="text-align: left;">
                        {{ optional(optional(optional($ins->paralelo)->asignacion)->personal)->apellidos }} {{ optional(optional(optional($ins->paralelo)->asignacion)->personal)->nombres ?? 'Docente' }}
                    </td>
                    <td>
                        <strong>{{ ($nota !== null && $estadoCal !== 'PENDIENTE') ? $nota : '-' }}</strong>
                    </td>
                    <td>
                        @if($nota !== null && $estadoCal !== 'PENDIENTE')
                            @if($nota >= 51)
                                <span class="aprobado">APROBADO</span>
                            @else
                                <span class="reprobado">REPROBADO</span>
                            @endif
                        @else
                            <span class="cursando">CURSANDO</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="padding: 15px; color: #888;">El estudiante no registra historial de asignaturas en el sistema.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <strong>Total Módulos Cursados:</strong> {{ count($estudiante->inscripciones) }}<br>
                    <strong>Módulos Aprobados:</strong> {{ $aprobados }} de 9
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Habilitación Título Intermedio (Mod. 8):</strong> 
                    <span style="color: {{ $aprobados >= 8 ? '#155724' : '#666' }}; font-weight: bold;">
                        {{ $aprobados >= 8 ? 'CUMPLIDO' : 'PENDIENTE' }}
                    </span><br>
                    <strong>Certificación Avanzada (Mod. 9):</strong> 
                    <span style="color: {{ $aprobados >= 9 ? '#155724' : '#666' }}; font-weight: bold;">
                        {{ $aprobados >= 9 ? 'CUMPLIDO' : 'PENDIENTE' }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <table class="footer-signatures">
        <tr>
            <td style="width: 45%;">
                <div class="signature-line">
                    DIRECTOR(A) ACADÉMICO(A)<br>
                    {{ $configuracion->nombre ?? 'Instituto de Idiomas' }}
                </div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%;">
                <div class="signature-line">
                    RESPONSABLE DE REGISTROS Y ACTAS<br>
                    Sello y Firma Autorizada
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

