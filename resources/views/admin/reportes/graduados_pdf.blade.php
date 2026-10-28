<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Padrón Oficial de Graduados - Nivel {{ $tipoGraduacion }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
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
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 8px 12px;
            margin-bottom: 12px;
            font-size: 10px;
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
        .footer-signatures {
            margin-top: 40px;
            width: 100%;
        }
        .footer-signatures td {
            text-align: center;
            padding: 0 30px;
        }
        .sig-line {
            border-top: 1px solid #333;
            margin-top: 45px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 9px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 20%; vertical-align: middle;">
                @if($configuracion && $configuracion->logo && file_exists(public_path($configuracion->logo)))
                    <img src="{{ public_path($configuracion->logo) }}" style="max-height: 50px; max-width: 120px;" alt="Logo">
                @else
                    <div style="font-weight: bold; color: #1a3a5f; font-size: 14px;">CEI IDIOMAS</div>
                @endif
            </td>
            <td style="width: 80%;" class="header-title">
                <h2>{{ $configuracion->nombre ?? 'CENTRO DE ENSEÑANZA INTEGRAL DE IDIOMAS (CEI)' }}</h2>
                <h3>NÓMINA OFICIAL DE ESTUDIANTES APTOS PARA GRADUACIÓN</h3>
                <p>Nivel de Certificación: <strong>{{ $tipoGraduacion }}</strong> | Fecha de Emisión: {{ date('d/m/Y H:i') }}</p>
            </td>
        </tr>
    </table>

    <div class="summary-box">
        <table style="width: 100%;">
            <tr>
                <td style="width: 55%;">
                    <strong>Resumen General de Rendimiento Académico:</strong><br>
                    &bull; <strong>Graduados:</strong> {{ $totalGraduados ?? 0 }} | 
                    <strong>Módulos Aprobados:</strong> {{ $totalAprobados ?? 0 }} | 
                    <strong>Módulos Reprobados:</strong> {{ $totalReprobados ?? 0 }} | 
                    <strong>En Curso:</strong> {{ $totalCursando ?? 0 }}
                </td>
                <td style="width: 45%; text-align: right;">
                    <strong>Registros en este Reporte ({{ $tipoGraduacion }}):</strong> {{ count($estudiantes) }}<br>
                    <strong>Gestión Académica:</strong> {{ date('Y') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th style="width: 75px;">Código CEI</th>
                <th style="width: 70px;">Cédula (CI)</th>
                <th>Apellidos y Nombres</th>
                <th>Carrera / Origen</th>
                <th style="width: 70px;">Promedio</th>
                <th style="width: 90px;">Condición</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($estudiantes as $est)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $est->codigo_estudiante ?? 'CEI-'.$est->id }}</td>
                    <td style="text-align: center;">{{ $est->ci }}</td>
                    <td style="font-weight: bold;">{{ $est->apellidos }} {{ $est->nombres }}</td>
                    <td>{{ $est->carrera_facultad ?? 'Particular' }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $est->promedio_egreso }} pts</td>
                    <td style="text-align: center; font-weight: bold; color: {{ $tipoGraduacion === 'REPROBADOS' ? '#721c24' : '#155724' }};">
                        {{ $tipoGraduacion === 'REPROBADOS' ? 'REPROBADO' : ($tipoGraduacion === 'APROBADOS' ? 'APROBADO' : 'HABILITADO') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 10px; color: #888;">No se registran estudiantes para este criterio ({{ $tipoGraduacion }}).</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td style="width: 45%;">
                <div class="sig-line">
                    DIRECTOR(A) DEL INSTITUTO CEI<br>
                    Firma y Sello Oficial
                </div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%;">
                <div class="sig-line">
                    SECRETARÍA GENERAL / DAF<br>
                    Recepción y Trámite de Diplomas
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

