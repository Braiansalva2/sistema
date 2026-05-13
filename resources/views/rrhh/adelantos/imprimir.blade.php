<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Adelanto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background:#dfe3e8;
            font-family: Arial, Helvetica, sans-serif;
            margin:0;
            padding:30px;
            color:#1e293b;
        }

        .documento{
            width:850px;
            margin:auto;
            background:white;
            border-radius:8px;
            overflow:hidden;
            box-shadow:0 5px 20px rgba(0,0,0,0.12);
        }

        /* HEADER */

        .header{
            background:#0f172a;
            color:white;
            padding:30px 40px;
        }

        .logo{
            width:70px;
            height:70px;
            object-fit:contain;
            background:white;
            padding:6px;
            border-radius:8px;
        }

        .empresa{
            font-size:28px;
            font-weight:bold;
            line-height:1.2;
        }

        .subtitulo{
            font-size:13px;
            opacity:.8;
            margin-top:5px;
        }

        .fecha{
            text-align:right;
            font-size:13px;
            line-height:1.5;
        }

        /* BODY */

        .contenido{
            padding:35px 40px;
        }

        .titulo-seccion{
            font-size:18px;
            font-weight:bold;
            margin-bottom:18px;
            padding-bottom:8px;
            border-bottom:2px solid #e2e8f0;
            color:#0f172a;
        }

        .tabla{
            width:100%;
            border-collapse:collapse;
            margin-bottom:35px;
        }

        .tabla td{
            padding:14px;
            border:1px solid #e2e8f0;
            vertical-align:top;
        }

        .label{
            font-size:11px;
            color:#64748b;
            margin-bottom:4px;
            text-transform:uppercase;
            font-weight:bold;
            letter-spacing:.5px;
        }

        .valor{
            font-size:17px;
            font-weight:600;
            color:#111827;
        }

        .estado{
            display:inline-block;
            padding:6px 14px;
            border-radius:20px;
            font-size:12px;
            font-weight:bold;
        }

        .pendiente{
            background:#fef3c7;
            color:#92400e;
        }

        .aprobado{
            background:#dcfce7;
            color:#166534;
        }

        .rechazado{
            background:#fee2e2;
            color:#991b1b;
        }

        .observaciones{
            border:1px solid #dbe2ea;
            padding:20px;
            min-height:140px;
            border-radius:6px;
            line-height:1.7;
            font-size:14px;
        }

        /* FIRMAS */

        .firmas{
            margin-top:90px;
        }

        .firma-box{
            text-align:center;
        }

        .linea{
            border-top:1px solid #111827;
            width:240px;
            margin:auto;
            margin-bottom:8px;
        }

        .firma-label{
            font-size:13px;
            font-weight:bold;
            color:#334155;
        }

        /* BOTON */

        .acciones{
            text-align:center;
            margin-top:45px;
        }

        /* PRINT */

        @media print{

            body{
                background:white;
                padding:0;
            }

            .documento{
                box-shadow:none;
                border-radius:0;
                width:100%;
            }

            .acciones{
                display:none;
            }

        }

    </style>
</head>
<body>

<div class="documento">

    {{-- HEADER --}}
    <div class="header">

        <div class="row align-items-center">

            <div class="col-2">

                <img src="{{ asset('images/LOGO-gvh-26.webp') }}"
                     class="logo">

            </div>

            <div class="col-7">

                <div class="empresa">
                    GVH LOGÍSTICA MINERA
                </div>

                <div class="subtitulo">
                    Solicitud de adelanto de sueldo
                </div>

            </div>

            <div class="col-3">

                <div class="fecha">

                    <strong>Fecha emisión</strong><br>

                    {{ now()->format('d/m/Y H:i') }}

                </div>

            </div>

        </div>

    </div>

    {{-- CONTENIDO --}}
    <div class="contenido">

        {{-- DATOS --}}
        <div class="titulo-seccion">
            Información del empleado
        </div>

        <table class="tabla">

            <tr>

                <td width="50%">

                    <div class="label">
                        Empleado
                    </div>

                    <div class="valor">
                        {{ $adelanto->empleado->nombre ?? '' }}
                        {{ $adelanto->empleado->apellido ?? '' }}
                    </div>

                </td>

                <td width="50%">

                    <div class="label">
                        DNI
                    </div>

                    <div class="valor">
                        {{ $adelanto->empleado->dni ?? '-' }}
                    </div>

                </td>

            </tr>

            <tr>

                <td>

                    <div class="label">
                        Monto solicitado
                    </div>

                    <div class="valor">
                        ${{ number_format($adelanto->monto_total, 0, ',', '.') }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Cuotas
                    </div>

                    <div class="valor">
                        {{ $adelanto->cuotas_total }}
                    </div>

                </td>

            </tr>

            <tr>

                <td>

                    <div class="label">
                        Fecha solicitud
                    </div>

                    <div class="valor">
                        {{ \Carbon\Carbon::parse($adelanto->fecha_solicitud)->format('d/m/Y') }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Estado
                    </div>

                    <div class="mt-2">

                        @if($adelanto->estado == 'pendiente')

                            <span class="estado pendiente">
                                Pendiente
                            </span>

                        @elseif($adelanto->estado == 'aprobado')

                            <span class="estado aprobado">
                                Aprobado
                            </span>

                        @else

                            <span class="estado rechazado">
                                Rechazado
                            </span>

                        @endif

                    </div>

                </td>

            </tr>

        </table>

        {{-- OBSERVACIONES --}}
        <div class="titulo-seccion">
            Motivo / Observaciones
        </div>

        <div class="observaciones">

            {{ $adelanto->motivo ?? 'Sin motivo cargado.' }}

        </div>

        {{-- FIRMAS --}}
        <div class="row firmas">

            <div class="col-6">

                <div class="firma-box">

                    <div class="linea"></div>

                    <div class="firma-label">
                        Firma empleado
                    </div>

                </div>

            </div>

            <div class="col-6">

                <div class="firma-box">

                    <div class="linea"></div>

                    <div class="firma-label">
                        Firma de Aprobacion
                    </div>

                </div>

            </div>

        </div>

        {{-- BOTON --}}
        <div class="acciones">

            <button onclick="window.print()"
                    class="btn btn-dark px-5">

                🖨 Imprimir documento

            </button>

        </div>

    </div>

</div>

<script>

    window.onload = function(){
        window.print();
    }

</script>

</body>
</html>