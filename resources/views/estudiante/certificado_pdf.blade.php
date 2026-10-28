<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado Oficial de Idiomas - {{ $estudiante->apellidos }}</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: letter landscape;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #222;
            text-align: center;
            padding: 10px;
        }
        .outer-border {
            border: 4px double #1a3a5f;
            padding: 20px;
            background-color: #ffffff;
            height: 94%;
        }
        .inner-border {
            border: 1px solid #c8a14d;
            padding: 25px;
            height: 90%;
        }
        .header-logo {
            max-height: 60px;
            margin-bottom: 5px;
        }
        .institution-name {
            font-size: 16px;
            font-weight: bold;
            color: #1a3a5f;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .institution-subtitle {
            font-size: 11px;
            color: #666;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .cert-title {
            font-size: 26px;
            font-weight: bold;
            color: #c8a14d;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 10px 0;
        }
        .cert-conferred {
            font-size: 12px;
            color: #555;
            font-style: italic;
            margin-bottom: 10px;
        }
        .student-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a3a5f;
            text-transform: uppercase;
            border-bottom: 2px solid #c8a14d;
            display: inline-block;
            padding: 2px 30px;
            margin-bottom: 15px;
        }
        .cert-body {
            font-size: 12px;
            color: #444;
            max-width: 650px;
            margin: 0 auto 30px auto;
            line-height: 1.6;
        }
        .signatures-table {
            width: 100%;
            margin-top: 40px;
        }
        .signatures-table td {
            width: 33.3%;
            text-align: center;
            vertical-align: bottom;
            font-size: 10px;
        }
        .sig-line {
            border-top: 1px solid #444;
            margin: 0 25px 5px 25px;
            padding-top: 5px;
            font-weight: bold;
        }
        .code-stamp {
            font-size: 9px;
            color: #888;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="outer-border">
        <div class="inner-border">
            @if($configuracion && $configuracion->logo && file_exists(public_path($configuracion->logo)))
                <img src="{{ public_path($configuracion->logo) }}" class="header-logo" alt="Logo">
            @endif

            <div class="institution-name">{{ $configuracion->nombre ?? 'CENTRO DE ENSEÑANZA INTEGRAL DE IDIOMAS (CEI)' }}</div>
            <div class="institution-subtitle">Reconocimiento Académico de Competencia Lingüística</div>

            <div class="cert-title">
                CERTIFICADO DE {{ $tipo === 'AVANZADO' ? 'NIVEL AVANZADO' : 'NIVEL INTERMEDIO' }}
            </div>

            <div class="cert-conferred">El Instituto Universitario de Idiomas confiere el presente reconocimiento a:</div>

            <div class="student-name">
                {{ $estudiante->apellidos }} {{ $estudiante->nombres }}
            </div>

            <div class="cert-body">
                Por haber cursado, evaluado y aprobado satisfactoriamente los módulos correspondientes al 
                <strong>{{ $tipo === 'AVANZADO' ? 'Programa de Perfeccionamiento y Nivel Avanzado (Módulos 1 al 9)' : 'Ciclo Regular y Nivel Intermedio (Módulos 1 al 8)' }}</strong> 
                en el idioma de formación, habiendo demostrado suficiencia comunicativa y rendimiento académico conforme a las normas del Instituto.
            </div>

            <table class="signatures-table">
                <tr>
                    <td>
                        <div class="sig-line">
                            DOCENTE TITULAR EVALUADOR<br>
                            Firma Autorizada
                        </div>
                    </td>
                    <td>
                        <div class="sig-line" style="border-top: none;">
                            <img src="{{ public_path('vendor/adminlte/dist/img/AdminLTELogo.png') }}" style="max-height: 45px; opacity: 0.7;" alt="Sello"><br>
                            SELLO OFICIAL
                        </div>
                    </td>
                    <td>
                        <div class="sig-line">
                            DIRECTOR(A) GENERAL DEL INSTITUTO<br>
                            {{ $configuracion->nombre ?? 'Instituto CEI' }}
                        </div>
                    </td>
                </tr>
            </table>

            <div class="code-stamp">
                Código CEI: <strong>{{ $estudiante->codigo_estudiante ?? 'CEI-'.$estudiante->id }}</strong> | 
                Cédula: <strong>{{ $estudiante->ci }}</strong> | 
                Emisión Oficial: {{ date('d/m/Y') }}
            </div>
        </div>
    </div>
</body>
</html>

