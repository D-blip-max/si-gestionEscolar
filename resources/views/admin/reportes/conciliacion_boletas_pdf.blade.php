<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Conciliación de Boletas - Tesorería</title>
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
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-table th {
            background-color: #1a3a5f;
            color: #ffffff;
            border: 1px solid #1a3a5f;
            padding: 5px 4px;
            text-align: center;
            font-size: 9px;
        }
        .report-table td {
            border: 1px solid #ddd;
            padding: 4px;
            font-size: 8.5px;
        }
        .report-table tr:nth-child(even) {
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
                <h3>INFORME DE CONCILIACIÓN DE BOLETAS BANCARIAS - TESORERÍA DAF</h3>
                <p>{{ $configuracion->direccion ?? 'Campus Universitario' }} | Teléfono: {{ $configuracion->telefono ?? 'S/N' }} | Emisión: {{ date('d/m/Y H:i') }}</p>
            </td>
        </tr>
    </table>

    <div class="summary-box">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <strong>Total Boletas Fiscalizadas:</strong> {{ count($boletas) }} registros<br>
                    <strong>Período Auditado:</strong> {{ $fechaDesde ? date('d/m/Y', strtotime($fechaDesde)) : 'Inicio' }} al {{ $fechaHasta ? date('d/m/Y', strtotime($fechaHasta)) : 'Presente' }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>TOTAL RECAUDADO (DEPÓSITOS VÁLIDOS):</strong><br>
                    <span style="font-size: 14px; font-weight: bold; color: #155724;">Bs. {{ number_format($totalRecaudado, 2) }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 25px;">#</th>
                <th style="width: 65px;">Nro. Boleta</th>
                <th style="width: 55px;">Fecha</th>
                <th>Estudiante Titular</th>
                <th style="width: 55px;">CI</th>
                <th style="width: 80px;">Entidad Bancaria</th>
                <th>Concepto</th>
                <th style="width: 55px;">Estado</th>
                <th style="width: 60px;">Monto (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($boletas as $b)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $b->numero_boleta }}</td>
                    <td style="text-align: center;">{{ $b->fecha_deposito }}</td>
                    <td>{{ optional($b->estudiante)->apellidos }} {{ optional($b->estudiante)->nombres }}</td>
                    <td style="text-align: center;">{{ optional($b->estudiante)->ci }}</td>
                    <td style="text-align: center;">{{ $b->banco_entidad }}</td>
                    <td>{{ $b->concepto }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $b->estado }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($b->monto, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 10px; color: #888;">No se encontraron registros de boletas para los filtros seleccionados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-signatures">
        <tr>
            <td style="width: 45%;">
                <div class="sig-line">
                    RESPONSABLE DE FISCALIZACIÓN Y MATRÍCULA<br>
                    Instituto de Idiomas CEI
                </div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%;">
                <div class="sig-line">
                    DIRECCIÓN ADMINISTRATIVA FINANCIERA (DAF)<br>
                    Tesorería Central Universitaria
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

