<!DOCTYPE html>
<html lang="es">
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 11px; }
        .header { text-align: center; margin-bottom: 10px; }
        .logo { width: 120px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 5px; }
        th { background: #e37c45; color: white; }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ public_path('images/Logo.webp') }}" class="logo">
    <h2>REPORTE DE UNIDADES</h2>
    <p>Fecha: {{ $fecha->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Cod</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Estado</th>
            <th>VTV</th>
            <th>Póliza</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($unidades as $u)
            @php
                $vtv = $u->legajos->first(fn($d) => strtolower($d->tipo_documento) === 'vtv');
                $poliza = $u->legajos->first(fn($d) => strtolower($d->tipo_documento) === 'poliza');
            @endphp
            <tr>
                <td>{{ $u->cod_interno }}</td>
                <td>{{ optional($u->marca)->nombre }}</td>
                <td>{{ optional($u->modelo)->nombre }}</td>
                <td>{{ $u->anio }}</td>
                <td>{{ strtoupper($u->estado) }}</td>
                <td>{{ optional($vtv)->fecha_vencimiento }}</td>
                <td>{{ optional($poliza)->fecha_vencimiento }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
