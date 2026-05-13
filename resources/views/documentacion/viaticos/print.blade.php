<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Viático {{ $viatico->codigo }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 25px;
            color: #000;
            font-size: 14px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #0d3c61;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            height: 70px;
        }

        .titulo {
            font-size: 22px;
            font-weight: bold;
            color: #0d3c61;
            text-align: right;
        }

        .box {
            border: 1px solid #ccc;
            padding: 12px;
            margin-bottom: 18px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .col {
            width: 48%;
        }

        .label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #bbb;
        }

        th {
            background: #0d3c61;
            color: white;
            padding: 8px;
            font-size: 13px;
        }

        td {
            padding: 7px;
            font-size: 13px;
        }

        .total-box {
            margin-top: 10px;
            text-align: right;
        }

        .total-general {
            font-size: 20px;
            font-weight: bold;
            color: #0d3c61;
            margin-top: 10px;
        }

        .deposito-box {
            border: 1px solid #ccc;
            padding: 12px;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .deposito-titulo {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            color: #0d3c61;
        }

        .linea {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 180px;
            height: 18px;
            vertical-align: middle;
        }

        .linea-corta {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 70px;
            height: 18px;
            vertical-align: middle;
        }

        .firma-section {
            margin-top: 50px;
        }

        .firmas-row {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 40px;
        }

        .firma-box {
            width: 48%;
            text-align: center;
        }

        .firma-linea {
            border-top: 1px solid #000;
            margin-top: 45px;
            padding-top: 8px;
            font-weight: bold;
        }

        .firma-single {
            width: 48%;
            margin-left: auto;
            text-align: center;
        }

        .observaciones-box {
            margin-top: 15px;
        }

        .observaciones-area {
            border: 1px solid #ccc;
            min-height: 55px;
            margin-top: 8px;
            padding: 8px;
        }

        @media print {
            body {
                margin: 15px;
            }
        }

        .firmas-container {
    margin-top: 70px;
}

.firmas-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 50px;
}

.firmas-row-center {
    display: flex;
    justify-content: center;
}

.firma-box {
    width: 40%;
    text-align: center;
}

.firma-line {
    border-top: 2px solid #000;
    margin-bottom: 8px;
    height: 40px; /* espacio para firmar */
}

.firma-label {
    font-size: 13px;
    font-weight: bold;
    letter-spacing: 0.5px;
}


.header-formato {
    margin-bottom: 15px;
}

.header-formato table td {
    vertical-align: middle;
}
    </style>
</head>

<body onload="window.print()">

    <!-- HEADER -->
  <div class="header-formato">

    <table style="width:100%; border-collapse: collapse; font-size:12px;">
        <tr>

            <!-- LOGO -->
            <td style="width:20%; text-align:center; border:1px solid #000;">
                <img src="{{ asset('images/LOGO-gvh-26.webp') }}" style="height:60px;">
            </td>

            <!-- TITULO -->
            <td style="width:50%; text-align:center; border:1px solid #000;">
                <strong style="font-size:14px;">
                    FORMULARIO DE SOLICITUD DE VIÁTICOS
                </strong><br>
                <span style="font-size:12px;">
                    REG-A&F-01
                </span>
            </td>

            <!-- DATOS -->
            <td style="width:30%; border:1px solid #000; padding:5px;">
                <strong>Revisión:</strong> 0<br>
                <strong>Vigencia:</strong> 31-01-26<br>
                <strong>Elaboró:</strong> Verónica Barboza<br>
                <strong>Revisó y Aprobó:</strong> Luis Vacazur
            </td>

        </tr>
    </table>

</div>  

<br>

    <!-- DATOS DEL VIÁTICO -->
    <div class="box">
        <div class="row">
            <div class="col">
                <div><span class="label">Código:</span> {{ $viatico->codigo }}</div>
                <div><span class="label">Empleado:</span> {{ $viatico->empleado->nombre }} {{ $viatico->empleado->apellido }}</div>
                <div><span class="label">Móvil:</span> {{ $viatico->movil }}</div>
            </div>

            <div class="col">
                <div><span class="label">Origen:</span> {{ $viatico->origen }}</div>
                <div><span class="label">Destino:</span> {{ $viatico->destino }}</div>
                <div><span class="label">Fecha salida:</span> {{ $viatico->fecha_salida }}</div>
            </div>
        </div>
    </div>

    <!-- DETALLE -->
    <h4 style="margin-bottom: 8px;">Detalle de gastos</h4>

    <table>
        <thead>
            <tr>
                <th style="width: 28%;">Concepto</th>
                <th style="width: 10%;">Cant.</th>
                <th style="width: 18%;">Precio</th>
                <th style="width: 18%;">Subtotal</th>
                <th style="width: 26%;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($viatico->detalles as $d)
                <tr>
                    <td>{{ $d->concepto }}</td>
                    <td>{{ $d->cantidad }}</td>
                    <td>$ {{ number_format($d->precio, 2, ',', '.') }}</td>
                    <td>$ {{ number_format($d->subtotal, 2, ',', '.') }}</td>
                    <td>{{ $d->observaciones }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Sin detalles cargados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TOTAL SOLO DEL VIÁTICO -->
    <div class="total-box">
        <div class="total-general">
            TOTAL VIÁTICO: $ {{ number_format($viatico->total, 2, ',', '.') }}
        </div>
    </div>

    <!-- DATOS DE DEPÓSITO / PAGO -->
    <div class="deposito-box">
        <div class="deposito-titulo">DATOS DE DEPÓSITO / PAGO (Completa Finanzas)</div>

        <div style="margin-bottom: 10px;">
            ☐ Transferencia
            &nbsp;&nbsp;&nbsp;&nbsp;
            ☐ Efectivo
        </div>

        <div style="margin-bottom: 10px;">
            <strong>Fecha depósito:</strong>
            <span class="linea-corta"></span> /
            <span class="linea-corta"></span> /
            <span class="linea-corta"></span>
        </div>

        <div>
            <strong>Comprobante N°:</strong>
            <span class="linea"></span>
        </div>
    </div>

    <!-- OBSERVACIONES -->
    <div class="observaciones-box">
        <strong>Observaciones:</strong>
        <div class="observaciones-area"></div>
    </div>

    <!-- FIRMAS -->
   <div class="firmas-container">

    <!-- FILA 1 -->
    <div class="firmas-row">
        <div class="firma-box">
            <div class="firma-line"></div>
            <div class="firma-label">Responsable</div>
        </div>

        <div class="firma-box">
            <div class="firma-line"></div>
            <div class="firma-label">Responsable de Operaciones</div>
        </div>
    </div>

    <!-- FILA 2 -->
    <div class="firmas-row-center">
        <div class="firma-box">
            <div class="firma-line"></div>
            <div class="firma-label">Finanzas</div>
        </div>
    </div>

</div>

</body>
</html>