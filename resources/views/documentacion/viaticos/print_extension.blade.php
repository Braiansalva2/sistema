<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Extensión {{ $viatico->codigo }}</title>

    <style>
        body {
            font-family: Arial;
            margin: 30px;
        }

        .box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 15px;
        }

        .row {
            display: flex;
            justify-content: space-between;
        }

        .col {
            width: 48%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #0d3c61;
            color: #fff;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
            font-size: 18px;
        }

        .firmas {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .firma {
            width: 30%;
            text-align: center;
        }

        .firma-linea {
            border-top: 1px solid #000;
            margin-top: 40px;
        }

        @media print {
            button { display: none; }
        }



        /* =========================
   BLOQUE DEPÓSITO
========================= */
.deposito-box {
    border: 1px solid #000;
    padding: 12px;
    margin-top: 20px;
    font-size: 13px;
}

.deposito-titulo {
    font-weight: bold;
    margin-bottom: 10px;
    background: #0d3c61;
    color: white;
    padding: 5px;
}

/* líneas */
.linea {
    display: inline-block;
    border-bottom: 1px solid #000;
    width: 250px;
    height: 12px;
}

.linea-corta {
    display: inline-block;
    border-bottom: 1px solid #000;
    width: 40px;
    height: 12px;
}

/* =========================
   OBSERVACIONES
========================= */
.observaciones-box {
    border: 1px solid #000;
    padding: 12px;
    margin-top: 15px;
    font-size: 13px;
}

.observaciones-area {
    border: 1px solid #000;
    height: 60px;
    margin-top: 8px;
}

/* =========================
   FIRMAS
========================= */
.firmas-container {
    margin-top: 50px;
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
}
    </style>
</head>

<body onload="window.print()">

<div style="margin-bottom:15px;">

    <table style="width:100%; border-collapse: collapse; font-size:12px;">
        <tr>

            <!-- LOGO -->
            <td style="width:20%; text-align:center; border:1px solid #000;">
                <img src="{{ asset('images/LOGO-gvh-26.webp') }}" style="height:60px;">
            </td>

            <!-- TITULO -->
            <td style="width:50%; text-align:center; border:1px solid #000;">
                <strong style="font-size:14px;">
                    COMPROBANTE DE EXTENSIÓN DE VIÁTICO
                </strong><br>
                <span style="font-size:12px;">
                    REG-A8F-01
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

<!-- DATOS ORIGINAL -->
<div class="box">
    <strong>VIÁTICO ORIGINAL</strong><br><br>

    Código: {{ $viatico->padre->codigo ?? '-' }}<br>
    Empleado: {{ $viatico->padre->empleado->nombre ?? '' }} {{ $viatico->padre->empleado->apellido ?? '' }}<br>
    Origen: {{ $viatico->padre->origen ?? '-' }}<br>
    Destino: {{ $viatico->padre->destino ?? '-' }} <br>
    <strong>Fecha solicitud extensión:</strong> 
{{ $viatico->created_at->format('d/m/Y H:i') }}
</div>

<!-- DATOS EXTENSION -->
<div class="box">
    <strong>DATOS DE LA EXTENSIÓN</strong><br><br>

    Código extensión: {{ $viatico->codigo }}<br>
    Días extra: {{ $viatico->dias_extra }}<br>
    Observaciones: {{ $viatico->observaciones }}
</div>

<!-- DETALLE -->
<h4>Detalle de gastos (Extensión)</h4>

<table>
    <thead>
        <tr>
            <th>Concepto</th>
            <th>Cant</th>
            <th>Precio</th>
            <th>Subtotal</th>
            <th>Obs</th>
        </tr>
    </thead>
    <tbody>
        @foreach($viatico->detalles as $d)
        <tr>
            <td>{{ $d->concepto }}</td>
            <td>{{ $d->cantidad }}</td>
            <td>{{ $d->precio }}</td>
            <td>{{ $d->subtotal }}</td>
            <td>{{ $d->observaciones }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="total">
    TOTAL EXTENSIÓN: $ {{ number_format($viatico->total, 2) }}
</div>
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