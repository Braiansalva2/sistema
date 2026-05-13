@extends('layouts.empleados')

@section('content')

<div class="container">

    <div class="card shadow-sm border-0 mb-3" style="border-radius:15px;">
        <div class="card-body">

            <h5 class="mb-3">💰 Solicitar Adelanto</h5>
 <a href="{{ route('empleado.index') }}" class="btn btn-secondary btn-sm">Volver</a> <br>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('empleado.adelantos.store') }}">
                @csrf

                <div class="row">
                    <div class="col-6 mb-2">
                        <label class="small text-muted">Nombre</label>
                        <input class="form-control" value="{{ $empleado->nombre }}" readonly>
                    </div>

                    <div class="col-6 mb-2">
                        <label class="small text-muted">DNI</label>
                        <input class="form-control" value="{{ $empleado->dni }}" readonly>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="small text-muted">CBU / Alias</label>
                    <input class="form-control" value="{{ $empleado->cbu }}" readonly>
                </div>

                <div class="row">
                    <div class="col-6 mb-2">
                        <label class="small text-muted">Monto</label>
                        <input type="number" name="monto_total" class="form-control" placeholder="$ 0" required>
                    </div>

                    <div class="col-6 mb-2">
                        <label class="small text-muted">Cuotas</label>
                        <input type="number" name="cuotas_total" class="form-control" placeholder="Ej: 3" required>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="small text-muted">Motivo</label>
                    <textarea name="motivo" class="form-control" rows="2" placeholder="Opcional"></textarea>
                </div>

                <button class="btn btn-primary w-100 mt-2" style="border-radius:10px;">
                    Solicitar Adelanto
                </button>

            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">📄 Mis últimas solicitudes</h6>

      
    </div>
  <a href="{{ route('empleado.adelantos.historial') }}"
           class="btn btn-sm btn-outline-primary"
           style="border-radius:10px;">
            Ver todos los adelantos
        </a>
    @forelse($adelantos as $adelanto)
        @php
            $pagadas = $adelanto->movimientos->where('tipo', 'descuento')->count();
            $restantes = $adelanto->cuotas_total - $pagadas;
        @endphp

        <div class="card border-0 shadow-sm mb-2" style="border-radius:12px;">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong>${{ number_format($adelanto->monto_total, 0, ',', '.') }}</strong>
                        <br>
                        <small class="text-muted">
                            Solicitud: {{ $adelanto->fecha_solicitud ? \Carbon\Carbon::parse($adelanto->fecha_solicitud)->format('d/m/Y') : '-' }}
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

                <small class="text-muted d-block">
                    Cuotas: {{ $pagadas }} / {{ $adelanto->cuotas_total }}
                </small>

                @if($restantes > 0)
                    <small class="text-danger d-block">
                        Restan {{ $restantes }} cuotas
                    </small>
                @else
                    <small class="text-success d-block">
                        ✔ Adelanto saldado
                    </small>
                @endif
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted">
                No hay solicitudes de adelanto registradas.
            </div>
        </div>
    @endforelse

</div>

@endsection