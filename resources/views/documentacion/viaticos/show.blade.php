@extends('layouts.documentacion')

@section('title', 'Detalle de Viático')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📄 Detalle de Viático</h3>

        <div>
            <a href="{{ route('documentacion.viaticos.print', $viatico->id) }}" 
                        target="_blank" 
                        class="btn btn-dark">
                        🖨️ Imprimir
            </a>

            <a href="{{ route('documentacion.viaticos.index') }}" class="btn btn-secondary">
                Volver
            </a>
        </div>
    </div>

    <!-- CARD PRINCIPAL -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-3">
                    <strong>Código:</strong><br>
                    {{ $viatico->codigo }}
                </div>

                <div class="col-md-3">
                    <strong>Empleado:</strong><br>
                    {{ $viatico->empleado->nombre }} {{ $viatico->empleado->apellido }}
                </div>

                <div class="col-md-3">
                    <strong>Estado:</strong><br>
                    <span class="badge bg-{{ $viatico->estado == 'aprobado' ? 'success' : ($viatico->estado == 'rechazado' ? 'danger' : 'warning') }}">
                        {{ ucfirst($viatico->estado) }}
                    </span>
                </div>

                <div class="col-md-3">
                    <strong>Móvil:</strong><br>
                    {{ $viatico->movil }}
                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-4">
                    <strong>Origen:</strong><br>
                    {{ $viatico->origen }}
                </div>

                <div class="col-md-4">
                    <strong>Destino:</strong><br>
                    {{ $viatico->destino }}
                </div>

                <div class="col-md-4">
                    <strong>Fecha:</strong><br>
                    {{ $viatico->fecha_salida }}
                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-3">
                    <strong>Días:</strong><br>
                    {{ $viatico->dias }}
                </div>

                <div class="col-md-3">
                    <strong>Total:</strong><br>
                    <span class="fw-bold text-success">
                      $ {{ number_format($totalGeneral, 2) }}
                    </span>
                </div>

            </div>

            <div class="mb-3">
                <strong>Observaciones:</strong><br>
                {{ $viatico->observaciones ?? 'Sin observaciones' }}
            </div>

        </div>
    </div>

    <!-- DETALLE -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            Detalle de gastos
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Cantidad</th>
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
                            <td>$ {{ $d->precio }}</td>
                            <td>$ {{ $d->subtotal }}</td>
                            <td>{{ $d->observaciones }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- EXTENSIONES -->
    @if($extensiones->count())
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                Extensiones del viático
            </div>

            <div class="card-body">

                @foreach($extensiones as $ext)

                    <div class="border rounded p-3 mb-3">

                        <div class="mb-2">
                            <strong>Código:</strong> {{ $ext->codigo }} |
                            <strong>Días extra:</strong> {{ $ext->dias_extra }}
                        </div>

                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Subtotal</th>
                                    <th>Obs</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($ext->detalles as $d)
                                    <tr>
                                        <td>{{ $d->concepto }}</td>
                                        <td>{{ $d->cantidad }}</td>
                                        <td>$ {{ $d->precio }}</td>
                                        <td>$ {{ $d->subtotal }}</td>
                                        <td>{{ $d->observaciones }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                @endforeach

            </div>
        </div>
    @endif

</div>

@endsection