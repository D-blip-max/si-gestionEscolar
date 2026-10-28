<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Oficial de Matrícula - #{{ str_pad($inscripcion->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 20mm 20mm 20mm 20mm;
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
            margin-bottom: 12px;
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
        .ficha-badge {
            background-color: #1a3a5f;
            color: white;
            padding: 4px 10px;
            font-weight: bold;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .section-box {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 12px;
            background-color: #ffffff;
        }
        .section-header {
            background-color: #f1f4f8;
            padding: 5px 10px;
            font-weight: bold;
            color: #1a3a5f;
            border-bottom: 1px solid #ccc;
            font-size: 11px;
            text-transform: uppercase;
        }
        .section-body {
            padding: 8px 10px;
        }
        table.data-table {
            width: 100%;
        }
        table.data-table td {
            padding: 3px 5px;
            font-size: 10.5px;
        }
        .label {
            font-weight: bold;
            color: #1a3a5f;
            width: 22%;
        }
        .modality-box {
            border: 2px solid #17a2b8;
            background-color: #f0fbfc;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 4px;
        }
        .modality-box.presencial {
            border-color: #6c757d;
            background-color: #f8f9fa;
        }
        .modality-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 4px;
        }
        .financial-box {
            border: 1px solid #28a745;
            background-color: #f4fbf6;
            padding: 8px 12px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .footer-signatures {
            margin-top: 35px;
            width: 100%;
        }
        .footer-signatures td {
            text-align: center;
            padding: 0 25px;
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
                    <img src="{{ public_path($configuracion->logo) }}" style="max-height: 60px; max-width: 130px;" alt="Logo">
                @else
                    <div style="font-weight: bold; color: #1a3a5f; font-size: 16px;">CEI IDIOMAS</div>
                @endif
            </td>
            <td style="width: 80%;" class="header-title">
                <h2>{{ $configuracion->nombre ?? 'CENTRO DE ENSEÑANZA INTEGRAL DE IDIOMAS (CEI)' }}</h2>
                <h3>FICHA OFICIAL DE MATRÍCULA MODULAR</h3>
                <p>{{ $configuracion->direccion ?? 'Campus Universitario' }} | Tel: {{ $configuracion->telefono ?? 'S/N' }}</p>
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-bottom: 8px;">
        <tr>
            <td style="width: 60%;">
                <span class="ficha-badge">MATRÍCULA NRO. #{{ str_pad($inscripcion->id, 5, '0', STR_PAD_LEFT) }}</span>
            </td>
            <td style="width: 40%; text-align: right; font-size: 10px; color: #666;">
                <strong>Fecha de Registro:</strong> {{ $inscripcion->fecha_inscripcion }}<br>
                <strong>Estado:</strong> {{ $inscripcion->estado }}
            </td>
        </tr>
    </table>

    <!-- Datos del Estudiante -->
    <div class="section-box">
        <div class="section-header">1. Información del Estudiante</div>
        <div class="section-body">
            <table class="data-table">
                <tr>
                    <td class="label">Estudiante:</td>
                    <td style="font-weight: bold; font-size: 11px;">{{ optional($inscripcion->estudiante)->apellidos }} {{ optional($inscripcion->estudiante)->nombres }}</td>
                    <td class="label">Código CEI:</td>
                    <td style="font-weight: bold;">{{ optional($inscripcion->estudiante)->codigo_estudiante ?? 'CEI-'.$inscripcion->estudiante_id }}</td>
                </tr>
                <tr>
                    <td class="label">Cédula de Identidad:</td>
                    <td>{{ optional($inscripcion->estudiante)->ci }}</td>
                    <td class="label">Carrera / Facultad:</td>
                    <td>{{ optional($inscripcion->estudiante)->carrera_facultad ?? 'Particular' }}</td>
                </tr>
                <tr>
                    <td class="label">Teléfono / WhatsApp:</td>
                    <td>{{ optional($inscripcion->estudiante)->telefono }}</td>
                    <td class="label">Correo Electrónico:</td>
                    <td>{{ optional(optional($inscripcion->estudiante)->usuario)->email ?? 'S/C' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Datos Académicos -->
    <div class="section-box">
        <div class="section-header">2. Asignación Académica Modular</div>
        <div class="section-body">
            <table class="data-table">
                <tr>
                    <td class="label">Ciclo Bimestral:</td>
                    <td style="font-weight: bold; color: #1a3a5f;">{{ optional(optional($inscripcion->paralelo)->ciclo)->nombre ?? 'Ciclo Vigente' }}</td>
                    <td class="label">Grupo / Paralelo:</td>
                    <td style="font-weight: bold;">{{ optional($inscripcion->paralelo)->nombre }}</td>
                </tr>
                <tr>
                    <td class="label">Idioma y Módulo:</td>
                    <td style="font-weight: bold;">{{ optional($inscripcion->modulo->idioma)->nombre }} - {{ optional($inscripcion->modulo)->nombre }}</td>
                    <td class="label">Nivel Curricular:</td>
                    <td>{{ strtoupper(optional($inscripcion->modulo)->nivel ?? 'General') }} (Módulo {{ optional($inscripcion->modulo)->numero_orden }} de 9)</td>
                </tr>
                <tr>
                    <td class="label">Turno y Horario:</td>
                    <td>{{ optional($inscripcion->paralelo)->turno }}: {{ substr(optional($inscripcion->paralelo)->hora_inicio, 0, 5) }} a {{ substr(optional($inscripcion->paralelo)->hora_fin, 0, 5) }}</td>
                    <td class="label">Docente Titular:</td>
                    <td>{{ optional(optional(optional($inscripcion->paralelo)->asignacion)->personal)->apellidos }} {{ optional(optional(optional($inscripcion->paralelo)->asignacion)->personal)->nombres ?? 'Por Asignar' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Recuadro Destacado de Modalidad y Ubicación (CU-14) -->
    @php
        $esVirtual = strtoupper(optional($inscripcion->paralelo)->modalidad) === 'VIRTUAL';
    @endphp
    <div class="modality-box {{ $esVirtual ? '' : 'presencial' }}">
        @if($esVirtual)
            <div class="modality-title" style="color: #0c5460;">
                [X] MODALIDAD VIRTUAL - PLATAFORMA DE VIDEOCONFERENCIA (MEET / ZOOM)
            </div>
            <div style="font-size: 10.5px; color: #333;">
                <strong>Enlace de Acceso a Clases:</strong> {{ optional($inscripcion->paralelo)->aula_o_enlace ?? 'Se notificará por el portal del estudiante' }}<br>
                <small style="color: #666;">El estudiante debe ingresar con su cuenta institucional con 5 minutos de anticipación.</small>
            </div>
        @else
            <div class="modality-title" style="color: #383d41;">
                [X] MODALIDAD PRESENCIAL - CAMPUS UNIVERSITARIO
            </div>
            <div style="font-size: 10.5px; color: #333;">
                <strong>Aula Física Asignada:</strong> <span style="font-weight: bold; font-size: 11px;">{{ optional($inscripcion->paralelo)->aula_o_enlace ?? 'Aula General' }}</span><br>
                <small style="color: #666;">Presentarse puntualmente portando su credencial o documento de identidad.</small>
            </div>
        @endif
    </div>

    <!-- Respaldo Financiero (CU-11 Boleta) -->
    <div class="financial-box">
        <table style="width: 100%;">
            <tr>
                <td style="width: 33%;">
                    <strong>Nro. Boleta Bancaria:</strong><br>
                    <span style="font-size: 12px; font-weight: bold; color: #155724;">{{ optional($inscripcion->boletaPago)->numero_boleta ?? 'S/N' }}</span>
                </td>
                <td style="width: 33%;">
                    <strong>Monto Depositado:</strong><br>
                    <span style="font-size: 12px; font-weight: bold; color: #155724;">Bs. {{ number_format(optional($inscripcion->boletaPago)->monto, 2) }}</span>
                </td>
                <td style="width: 34%;">
                    <strong>Entidad Bancaria:</strong><br>
                    {{ optional($inscripcion->boletaPago)->banco_entidad ?? 'Depósito Universitario' }} ({{ optional($inscripcion->boletaPago)->fecha_deposito }})
                </td>
            </tr>
        </table>
    </div>

    <table class="footer-signatures">
        <tr>
            <td style="width: 45%;">
                <div class="signature-line">
                    FIRMA DEL ESTUDIANTE<br>
                    CI: {{ optional($inscripcion->estudiante)->ci }}
                </div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 45%;">
                <div class="signature-line">
                    RESPONSABLE DE MATRICULACIÓN<br>
                    Sello y Firma Oficial CEI
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

