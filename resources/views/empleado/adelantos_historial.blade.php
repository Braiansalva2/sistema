@extends('layouts.empleados')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0">💰 Historial de Adelantos</h5>
            <small class="text-muted">Acá podés ver todas tus solicitudes</small>
        </div>

        <a href="{{ route('empleado.adelantos') }}"
           class="btn btn-sm btn-outline-secondary"
           style="border-radius:10px;">
            ← Volver
        </a>
    </div>

    @forelse($adelantos as $adelanto)
        @php
            $pagadas = $adelanto->movimientos->where('tipo', 'descuento')->count();
            $restantes = $adelanto->cuotas_total - $pagadas;
            $valorCuota = $adelanto->cuotas_total > 0 ? $adelanto->monto_total / $adelanto->cuotas_total : 0;
        @endphp

        <div class="card border-0 shadow-sm mb-3" style="border-radius:14px;">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                    <div>
                        <h6 class="mb-1 fw-bold">
                            ${{ number_format($adelanto->monto_total, 0, ',', '.') }}
                        </h6>
                        <small class="text-muted">
                            Solicitado el:
                            {{ $adelanto->fecha_solicitud ? \Carbon\Carbon::parse($adelanto->fecha_solicitud)->format('d/m/Y') : '-' }}
                        </small>
                    </div>

                    <div>
                        @if($adelanto->estado == 'pendiente')
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        @elseif($adelanto->estado == 'aprobado')
                            <span class="badge bg-success">Aprobado</span>
                        @elseif($adelanto->estado == 'rechazado')
                            <span class="badge bg-danger">Rechazado</span>
                        @elseif($adelanto->estado == 'pagado')
                            <span class="badge bg-primary">Pagado</span>
                        @endif
                    </div>
                </div>

                <div class="row g-2 mt-1">
                    <div class="col-6">
                        <small class="text-muted">Cuotas totales</small>
                        <div class="fw-semibold">{{ $adelanto->cuotas_total }}</div>
                    </div>

                    <div class="col-6">
                        <small class="text-muted">Valor por cuota</small>
                        <div class="fw-semibold">${{ number_format($valorCuota, 2, ',', '.') }}</div>
                    </div>

                    <div class="col-6">
                        <small class="text-muted">Cuotas pagadas</small>
                        <div class="fw-semibold">{{ $pagadas }}</div>
                    </div>

                    <div class="col-6">
                        <small class="text-muted">Cuotas restantes</small>
                        <div class="fw-semibold">
                            @if($restantes > 0)
                                <span class="text-danger">{{ $restantes }}</span>
                            @else
                                <span class="text-success">0</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12">
                        <small class="text-muted">Motivo</small>
                        <div class="fw-semibold">
                            {{ $adelanto->motivo ?: 'Sin motivo cargado' }}
                        </div>
                    </div>

                    @if($adelanto->fecha_pago)
                        <div class="col-12">
                            <small class="text-muted">Fecha de pago</small>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($adelanto->fecha_pago)->format('d/m/Y') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted">
                No tenés adelantos cargados.
            </div>
        </div>
    @endforelse

    <div class="d-flex justify-content-center mt-3">
        {{ $adelantos->links() }}
    </div>

</div>

@endsection