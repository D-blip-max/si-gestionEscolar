<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inscritos por Modalidad - CEI</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333;
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
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .summary-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background-color: #1a3a5f;
            color: white;
            padding: 5px;
            text-align: center;
            font-size: 9px;
            border: 1px solid #1a3a5f;
        }
        .data-table td {
            border: 1px solid #ddd;
            padding: 4px;
            font-size: 8.5px;
        }
        .data-table tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 20%;">
                @if($configuracion && $configuracion->logo && file_exists(public_path($configuracion->logo)))
                    <img src="{{ public_path($configuracion->logo) }}" style="max-height: 50px; max-width: 120px;" alt="Logo">
                @else
                    <div style="font-weight: bold; color: #1a3a5f; font-size: 14px;">CEI IDIOMAS</div>
                @endif
            </td>
            <td style="width: 80%;" class="header-title">
                <h2>{{ $configuracion->nombre ?? 'CENTRO DE ENSEÑANZA INTEGRAL DE IDIOMAS (CEI)' }}</h2>
                <h3>ESTADÍSTICAS OFICIALES DE INSCRITOS POR MODALIDAD</h3>
                <p>Fecha de Emisión: {{ date('d/m/Y H:i') }}</p>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td style="width: 33.3%; background-color: #f8f9fa;">
                <strong>Modalidad Presencial (Campus):</strong><br>
                <span style="font-size: 14px; font-weight: bold; color: #6c757d;">{{ $totalPresencial }} Estudiantes</span>
            </td>
            <td style="width: 33.3%; background-color: #f0fbfc;">
                <strong>Modalidad Virtual (Meet/Zoom):</strong><br>
                <span style="font-size: 14px; font-weight: bold; color: #17a2b8;">{{ $totalVirtual }} Estudiantes</span>
            </td>
            <td style="width: 33.3%; background-color: #eef2f7;">
                <strong>Total General Matriculados:</strong><br>
                <span style="font-size: 14px; font-weight: bold; color: #1a3a5f;">{{ $totalPresencial + $totalVirtual }} Estudiantes</span>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th style="width: 70px;">Código CEI</th>
                <th style="width: 65px;">CI</th>
                <th>Apellidos y Nombres</th>
                <th>Idioma / Módulo</th>
                <th style="width: 70px;">Modalidad</th>
                <th style="width: 50px;">Turno</th>
                <th>Aula / Enlace</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inscripciones as $ins)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="text-align: center;">{{ optional($ins->estudiante)->codigo_estudiante ?? 'CEI-'.$ins->estudiante_id }}</td>
                    <td style="text-align: center;">{{ optional($ins->estudiante)->ci }}</td>
                    <td>{{ optional($ins->estudiante)->apellidos }} {{ optional($ins->estudiante)->nombres }}</td>
                    <td>{{ optional($ins->modulo->idioma)->nombre }} - {{ optional($ins->modulo)->nombre }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ optional($ins->paralelo)->modalidad }}</td>
                    <td style="text-align: center;">{{ optional($ins->paralelo)->turno }}</td>
                    <td>{{ optional($ins->paralelo)->aula_o_enlace ?? 'Aula General' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 10px; color: #777;">No se registran alumnos matriculados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

