<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta Oficial de Calificaciones - {{ optional($paralelo->modulo)->nombre }} Grupo {{ $paralelo->nombre }}</title>
    <style>
        @page {
            margin: 20mm 20mm 20mm 20mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10.5px;
            color: #333;
            line-height: 1.35;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1a3a5f;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h2 {
            margin: 0;
            color: #1a3a5f;
            font-size: 15px;
            text-transform: uppercase;
        }
        .header-title h3 {
            margin: 3px 0;
            color: #444;
            font-size: 12px;
        }
        .header-title p {
            margin: 0;
            font-size: 8.5px;
            color: #777;
        }
        .info-box {
            width: 100%;
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 12px;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 2.5px 5px;
            font-size: 10px;
        }
        .label {
            font-weight: bold;
            color: #1a3a5f;
        }
        .acta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .acta-table th {
            background-color: #1a3a5f;
            color: #ffffff;
            border: 1px solid #1a3a5f;
            padding: 5px;
            text-align: center;
            font-size: 9.5px;
        }
        .acta-table td {
            border: 1px solid #ddd;
            padding: 4px 5px;
            font-size: 9.5px;
        }
        .acta-table tr:nth-child(even) {
            background-color: #fafafa;
        }
        .aprobado {
            color: #155724;
            font-weight: bold;
        }
        .reprobado {
            color: #721c24;
            font-weight: bold;
        }
        .summary-table {
            width: 100%;
            margin-top: 12px;
            border: 1px solid #bbb;
            background-color: #fcfcfc;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 6px 10px;
            font-size: 10px;
            border: 1px solid #ddd;
        }
        .footer-signatures {
            margin-top: 40px;
            width: 100%;
        }
        .footer-signatures td {
            text-align: center;
            padding: 0 30px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 45px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 9.5px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 20%; vertical-align: middle;">
                @if($configuracion && $configuracion->logo && file_exists(public_path($configuracion->logo)))
                    <img src="{{ public_path($configuracion->logo) }}" style="max-height: 55px; max-width: 125px;" alt="Logo">
                @else
                    <div style="font-weight: bold; color: #1a3a5f; font-size: 15px;">CEI IDIOMAS</div>
                @endif
            </td>
            <td style="width: 80%;" class="header-title">
                <h2>{{ $configuracion->nombre ?? 'CENTRO DE ENSEÑANZA INTEGRAL DE IDIOMAS (CEI)' }}</h2>
                <h3>ACTA OFICIAL DE CALIFICACIONES MODULARES</h3>
                <p>{{ $configuracion->direccion ?? 'Campus Universitario' }} | Teléfono: {{ $configuracion->telefono ?? 'S/N' }}</p>
            </td>
        </tr>
    </table>

    <div class="info-box">
        <table class="info-table">
            <tr>
                <td style="width: 15%;" class="label">Ciclo Bimestral:</td>
                <td style="width: 35%; font-weight: bold;">{{ optional($paralelo->ciclo)->nombre }}</td>
                <td style="width: 15%;" class="label">Idioma y Módulo:</td>
                <td style="width: 35%; font-weight: bold;">{{ optional($paralelo->modulo->idioma)->nombre }} - {{ optional($paralelo->modulo)->nombre }}</td>
            </tr>
            <tr>
                <td class="label">Grupo / Paralelo:</td>
                <td>Grupo {{ $paralelo->nombre }}</td>
                <td class="label">Modalidad:</td>
                <td>{{ strtoupper($paralelo->modalidad) }} ({{ $paralelo->aula_o_enlace ?? 'Aula General' }})</td>
            </tr>
            <tr>
                <td class="label">Docente Titular:</td>
                <td style="font-weight: bold;">
                    {{ optional(optional(optional($paralelo)->asignacion)->personal)->apellidos }} {{ optional(optional(optional($paralelo)->asignacion)->personal)->nombres ?? 'Docente Titular' }}
                </td>
                <td class="label">Turno y Horario:</td>
                <td>{{ $paralelo->turno }} ({{ substr($paralelo->hora_inicio, 0, 5) }} - {{ substr($paralelo->hora_fin, 0, 5) }})</td>
            </tr>
        </table>
    </div>

    <table class="acta-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th style="width: 75px;">Código CEI</th>
                <th style="width: 75px;">Cédula (CI)</th>
                <th>Apellidos y Nombres</th>
                <th style="width: 60px;">Nota [0-100]</th>
                <th style="width: 80px;">Condición</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($paralelo->inscripciones as $ins)
                @php
                    $nota = optional($ins->calificacion)->nota_final ?? 0;
                    $esAprobado = $nota >= 51;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="text-align: center;">{{ optional($ins->estudiante)->codigo_estudiante ?? 'CEI-'.$ins->estudiante_id }}</td>
                    <td style="text-align: center;">{{ optional($ins->estudiante)->ci }}</td>
                    <td style="text-align: left; font-weight: bold;">{{ optional($ins->estudiante)->apellidos }} {{ optional($ins->estudiante)->nombres }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $nota }}</td>
                    <td style="text-align: center;">
                        <span class="{{ $esAprobado ? 'aprobado' : 'reprobado' }}">
                            {{ $esAprobado ? 'APROBADO' : 'REPROBADO' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 10px; color: #777;">No se registran estudiantes matriculados en este grupo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td style="width: 25%; text-align: center;">
                <strong>Total Matriculados:</strong><br>
                <span style="font-size: 13px; font-weight: bold;">{{ $totalInscritos }}</span>
            </td>
            <td style="width: 25%; text-align: center;">
                <strong>Aprobados (&ge;51):</strong><br>
                <span style="font-size: 13px; font-weight: bold; color: #155724;">
                    {{ $aprobados }} ({{ $totalInscritos > 0 ? round(($aprobados / $totalInscritos) * 100) : 0 }}%)
                </span>
            </td>
            <td style="width: 25%; text-align: center;">
                <strong>Reprobados (&lt;51):</strong><br>
                <span style="font-size: 13px; font-weight: bold; color: #721c24;">
                    {{ $reprobados }} ({{ $totalInscritos > 0 ? round(($reprobados / $totalInscritos) * 100) : 0 }}%)
                </span>
            </td>
            <td style="width: 25%; text-align: center;">
                <strong>Promedio Grupal:</strong><br>
                <span style="font-size: 13px; font-weight: bold; color: #1a3a5f;">{{ $promedio }} pts</span>
            </td>
        </tr>
    </table>

    <table class="footer-signatures">
        <tr>
            <td style="width: 45%;">
                <div class="signature-line">
                    DOCENTE TITULAR RESPONSABLE<br>
                    {{ optional(optional(optional($paralelo)->asignacion)->personal)->apellidos }} {{ optional(optional(optional($paralelo)->asignacion)->personal)->nombres ?? 'Docente Titular' }}<br>
                    CI: {{ optional(optional(optional($paralelo)->asignacion)->personal)->ci ?? 'S/N' }}
                </div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%;">
                <div class="signature-line">
                    DIRECTOR(A) ACADÉMICO(A)<br>
                    Vo. Bo. y Sello Institucional
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

