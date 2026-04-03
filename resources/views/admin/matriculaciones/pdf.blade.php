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
                <img src="{{ url($matricula->estudiante->foto) }}" width="100px" alt="">
            </td>
        </tr>
    </table>

    <br>

    <table border="0" cellpadding="5" style="margin-left: 50px">
        <tr>
            <td><b>Gestión: </b></td>
            <td>{{ $matricula->gestion->nombre }}</td>
        </tr>
        <tr>
            <td><b>Turno: </b></td>
            <td>{{ $matricula->turno->nombre }}</td>
        </tr>
        <tr>
            <td><b>Nivel: </b></td>
            <td>{{ $matricula->nivel->nombre }}</td>
        </tr>
        <tr>
            <td><b>Grado: </b></td>
            <td>{{ $matricula->grado->nombre }}</td>
        </tr>
        <tr>
            <td><b>Paralelo: </b></td>
            <td>{{ $matricula->paralelo->nombre }}</td>
        </tr>
    </table>

</body>

</html>
