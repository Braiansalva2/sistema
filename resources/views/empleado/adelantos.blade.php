@extends('layouts.empleados')

@section('content')

<div class="container">

    <div class="card shadow-sm border-0 mb-3" style="border-radius:15px;">
        <div class="card-body">

            <h5 class="mb-3">💰 Solicitar Adelanto</h5>
             <a href="{{ route('empleado.index') }}" class="btn btn-secondary btn-sm">Volver</a> <br>

<br><br>

                    <div class="alert alert-warning border-0 shadow-sm"
                        style="border-radius:12px; background:#fff3cd;">

                        <div class="d-flex align-items-start">
                            <div class="me-2 fs-5">
                                ⚠
                            </div>

                            <div>
                                <strong>Importante</strong><br>

                                Solo se permite una solicitud de adelanto por mes.

                                <br>

                                Para casos excepcionales deberá comunicarse con RRHH.
                            </div>
                        </div>
                    </div>


            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
@if(!$yaSolicitoEsteMes)
  <form id="formAdelanto"
      method="POST"
      action="{{ route('empleado.adelantos.store') }}"> 
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

                <button type="submit"
                        class="btn btn-primary w-100 mt-2"
                        id="btnEnviarAdelanto"
                        style="border-radius:10px;">

                    <span class="texto-btn">
                        Solicitar Adelanto
                    </span>

                </button>   >

            </form>

            @else

<div class="alert alert-info border-0 shadow-sm mt-3"
     style="border-radius:12px; background:#dbeafe; color:#1e3a8a;">

    <div class="d-flex align-items-start">
        <div class="me-2 fs-5">
            ℹ
        </div>

        <div>
            Ya realizaste una solicitud de adelanto este mes.

            <br>

            Si necesitás otro adelanto deberás comunicarte con RRHH.
        </div>
    </div>

</div>

@endif
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

    $pagadas = $adelanto->cuotas
        ->where('estado', 'pagada')
        ->count();

    $restantes = $adelanto->cuotas
        ->where('estado', 'pendiente')
        ->count();

    $montoPendiente = $adelanto->cuotas
        ->where('estado', 'pendiente')
        ->sum('monto');

    $porcentaje = $adelanto->cuotas_total > 0
        ? ($pagadas / $adelanto->cuotas_total) * 100
        : 0;

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
                        @elseif($adelanto->estado == 'saldado')
                            <span class="badge bg-success">
                                ✔ Saldado
                            </span>
                        @endif
                    </div>
                </div>

              <div class="mt-2">

    <small class="text-muted d-block">

        Cuotas pagadas:
        <strong>

            {{ $pagadas }}

        </strong>

        / {{ $adelanto->cuotas_total }}

    </small>

    @if($restantes > 0)

        <small class="text-danger d-block fw-semibold">

            Restan
            {{ $restantes }}
            cuota(s)

        </small>

        <small class="text-muted d-block">

            Debe:
            <strong>

                ${{ number_format($montoPendiente, 0, ',', '.') }}

            </strong>

        </small>

    @else

        <small class="text-success d-block fw-bold">

            ✔ Adelanto completamente saldado

        </small>

    @endif

    {{-- BARRA PROGRESO --}}
    <div class="progress mt-2"
         style="height:8px; border-radius:10px;">

        <div class="progress-bar bg-success"
             style="width: {{ $porcentaje }}%">

        </div>

    </div>

</div>
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


<script>
document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.getElementById('formAdelanto');
    const boton = document.getElementById('btnEnviarAdelanto');

    // verificar existencia
    if (!formulario || !boton) {
        return;
    }

    formulario.addEventListener('submit', function () {

        // bloquear botón
        boton.disabled = true;

        // mostrar carga
        boton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"
                  role="status"
                  aria-hidden="true"></span>
            Enviando solicitud...
        `;

    });  

});
</script>   
@endsection