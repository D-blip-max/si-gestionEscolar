<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Matricula del estudiante</title>
</head>

<body>

    <table border="0">
        <tr>
            <td style="text-align: center; font-size: 8pt; width: 200px">
                <img src="{{ url($configuracion->logo) }}" alt="" width="100px"> <br>
                <b>{{ $configuracion->nombre }}</b> <br>
                {{ $configuracion->direccion }} <br>
                {{ $configuracion->telefono }} <br>
                {{ $configuracion->correo_electronico }}
            </td>

            <td style="width: 300px; text-align: center">
                <br><br><br><b>
                    <h2>Matricula del estudiante</h2>
                </b>
            </td>

            <td style="width: 200px; text-align: center">
                <p>Fotografia</p>
                <img src="{{ url($matricula->estudiante->foto) }}" width="80px" alt="">
            </td>
        </tr>
    </table>

    <br>

    <table border="0" cellpadding="5" style="margin-left: 50px">
        <tr>
            <td style="width: 100px"><b>Gestión: </b></td>
            <td style="width: 170px">{{ $matricula->gestion->nombre }}</td>
            <td style="width: 100px"><b>Nombres: </b></td>
            <td style="width: 220px">{{ $matricula->estudiante->nombres }}</td>
        </tr>
        <tr>
            <td><b>Turno: </b></td>
            <td>{{ $matricula->turno->nombre }}</td>
            <td><b>Apellidos: </b></td>
            <td>{{ $matricula->estudiante->apellidos }}</td>
        </tr>
        <tr>
            <td><b>Nivel: </b></td>
            <td>{{ $matricula->nivel->nombre }}</td>
            <td><b>C.I.: </b></td>
            <td>{{ $matricula->estudiante->ci }}</td>
        </tr>
        <tr>
            <td><b>Grado: </b></td>
            <td>{{ $matricula->grado->nombre }}</td>
            <td><b>Género: </b></td>
            <td>{{ $matricula->estudiante->genero }}</td>
        </tr>
        <tr>
            <td><b>Paralelo: </b></td>
            <td>{{ $matricula->paralelo->nombre }}</td>
            <td><b>Teléfono: </b></td>
            <td>{{ $matricula->estudiante->telefono }}</td>
        </tr>
    </table>
    <hr>
    <p style="text-align: justify">
        <b>Partes Contratantes:</b>
        <br><br>
        La Institución <b>{{ $configuracion->nombre }}</b>, en adelante denominado "La Institución Educativa",
        representado por el <b style="color: blue">Lic. Freddy Hilari Michua</b>, con
        domicilio en av. del maestro s/n. Padres/Tutores legales del estudiante <b
            style="color: blue">{{ $matricula->estudiante->apellidos }}
            {{ $matricula->estudiante->nombres }}</b>, en adelante denominado "El Estudiante",
        representados por <b
            style="color: blue">{{ $matricula->estudiante->ppff->apellidos . ' ' . $matricula->estudiante->ppff->nombres }}</b>,
        con domicilio en <b style="color: blue">{{ $matricula->estudiante->ppff->direccion }}</b>.

        <br><br>

        <b>Términos y Condiciones:</b>

        Matrícula: Los Padres/Tutores legales matriculan al Estudiante en la Institución Educativa para el año escolar
        <b style="color: blue">'.{{ $matricula->grado->nombre }}.'</b>.

        <br><br>

        <b>Compromisos del Estudiante:</b> El Estudiante se compromete a asistir puntualmente a clases, participar
        activamente en las actividades escolares y seguir las normas y reglamentos establecidos por la Institución
        Educativa.

        <br><br>

        <b>Compromisos de los Padres/Tutores:</b> Los Padres/Tutores se comprometen a apoyar activamente la educación
        del Estudiante, mantener una comunicación regular con los maestros y cumplir con las obligaciones financieras
        relacionadas con la matrícula y las tarifas escolares.

        <br><br>

        <b>Duración del Contrato:</b> Este contrato tiene una duración de un año escolar y se renovará automáticamente
        para cada año escolar subsiguiente, a menos que cualquiera de las partes notifique lo contrario con al menos 10
        días de antelación al inicio del nuevo año escolar.

        <br><br>

        <b>Terminación del Contrato:</b> La Institución Educativa se reserva el derecho de rescindir este contrato si el
        Estudiante o los Padres/Tutores no cumplen con las normas y reglamentos internos.

        <br><br>
        {{ 'Fecha: ' . now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
        <br><br>
    </p>
    <table border="0" style="width: 100%">
        <tr>
            <td style="text-align: center">
                ____________________________________ <br>
                <b>La Institución Educativa</b> <br>
                Lic. Freddy Hilari Michua
            </td>
            <td style="text-align: center">
                ____________________________________ <br>
                <b>Padres/Tutores</b> <br>
                {{ $matricula->estudiante->ppff->apellidos . ' ' . $matricula->estudiante->ppff->nombres }}
            </td>
        </tr>
    </table>





</body>

</html>
