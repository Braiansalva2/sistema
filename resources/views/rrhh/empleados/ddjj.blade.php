<!DOCTYPE html>
<html>
<head>
    <title>DDJJ Empleado</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 25px;
            color: #000;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 90px;
        }

        .empresa {
            text-align: right;
            font-weight: bold;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 15px 0;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        td {
            border: 1px solid #444;
            padding: 6px;
        }

        .label {
            background: #f5f5f5;
            font-weight: bold;
            width: 20%;
        }

        .section-title {
            background: #ddd;
            font-weight: bold;
            text-align: left;
        }

        .firma {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .linea {
            border-top: 1px solid black;
            width: 250px;
            margin-top: 40px;
            text-align: center;
        }

        .small {
            font-size: 10px;
            text-align: center;
            margin-top: 10px;
        }

    </style>
</head>

<body onload="window.print()">

<!-- HEADER -->
<div class="header">
    <img src="{{ asset('images/LOGO-gvh-26.webp') }}" class="logo">

    <div class="empresa">
        GVH MINERÍA S.R.L.<br>
        DDJJ DE DATOS PERSONALES
    </div>
</div>

<!-- DATOS PERSONALES -->
<table>
    <tr class="section-title">
        <td colspan="4">Datos Personales</td>
    </tr>

    <tr>
        <td class="label">Nombre Completo</td>
        <td colspan="3">{{ $empleado->apellido }} {{ $empleado->nombre }}</td>
    </tr>

    <tr>
        <td class="label">DNI</td>
        <td>{{ $empleado->dni }}</td>
        <td class="label">CUIL</td>
        <td>{{ $empleado->cuil }}</td>
    </tr>

    <tr>
        <td class="label">Fecha Nacimiento</td>
        <td>{{ $empleado->fecha_nacimiento }}</td>
        <td class="label">Estado Civil</td>
        <td>{{ $empleado->estado_civil }}</td>
    </tr>

    <tr>
        <td class="label">Nacionalidad</td>
        <td>{{ $empleado->nacionalidad }}</td>
        <td class="label">Celular</td>
        <td>{{ $empleado->telefono }}</td>
    </tr>

    <tr>
        <td class="label">Dirección</td>
        <td colspan="3">{{ $empleado->direccion }}</td>
    </tr>
</table>

<!-- OBRA SOCIAL -->
<table>
    <tr class="section-title">
        <td>Datos de Obra Social</td>
    </tr>
    <tr>
        <td>{{ $empleado->obraSocial->nombre ?? '—' }}</td>
    </tr>
</table>

<!-- BANCO -->
<table>
    <tr class="section-title">
        <td colspan="3">Datos Bancarios</td>
    </tr>
    <tr>
        <td class="label">Banco</td>
        <td class="label">N° Cuenta</td>
        <td class="label">CBU</td>
    </tr>
    <tr>
        <td>{{ $empleado->banco->nombre_banco ?? '—' }}</td>
        <td>{{ $empleado->numero_cuenta }}</td>
        <td>{{ $empleado->cbu }}</td>
    </tr>
</table>

<!-- CONTACTOS -->
<table>
    <tr class="section-title">
        <td colspan="4">Contactos de Emergencia</td>
    </tr>

    <tr>
        <td class="label">Nombre</td>
        <td class="label">Parentesco</td>
        <td class="label">Teléfono</td>
        <td class="label">Dirección</td>
    </tr>

    @forelse($empleado->contactosEmergencia as $c)
    <tr>
        <td>{{ $c->nombre_contacto }}</td>
        <td>{{ $c->parentesco }}</td>
        <td>{{ $c->telefono }}</td>
        <td>{{ $c->domicilio }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="4">Sin datos</td>
    </tr>
    @endforelse
</table>

<!-- GRUPO FAMILIAR -->
<table>
    <tr class="section-title">
        <td colspan="4">Grupo Familiar</td>
    </tr>

    <tr>
        <td class="label">Nombre</td>
        <td class="label">DNI</td>
        <td class="label">Parentesco</td>
        <td class="label">A Cargo</td>
    </tr>

    @forelse($empleado->grupoFamiliar as $f)
    <tr>
        <td>{{ $f->apellido }} {{ $f->nombre }}</td>
        <td>{{ $f->dni }}</td>
        <td>{{ $f->parentesco }}</td>
        <td>{{ $f->a_cargo ? 'SI' : 'NO' }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="4">Sin datos</td>
    </tr>
    @endforelse
</table>

<!-- FIRMA -->
<div class="firma">
    <div>
        Firma
        <div class="linea"></div>
    </div>

    <div>
        Aclaración
        <div class="linea"></div>
    </div>
</div>

<div class="small">
    La información brindada forma parte del legajo personal del trabajador.
</div>

</body>
</html>