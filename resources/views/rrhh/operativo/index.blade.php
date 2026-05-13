@extends('layouts.rrhh')

@section('content')

<style>
.tracking-wrapper {
    position: relative;
    padding: 30px 20px 50px;
}

.tracking-line {
    height: 4px;
    background: #dee2e6;
    border-radius: 20px;
    position: relative;
}

.tracking-progress {
    height: 4px;
    background: linear-gradient(90deg,#28a745,#20c997);
    border-radius: 20px;
    position: absolute;
    top: 0;
    left: 0;
    transition: width .5s ease;
}

.tracking-point {
    position: absolute;
    top: -10px;
    transform: translateX(-50%);
    text-align: center;
    width: 80px;
}

.tracking-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: auto;
    background: #adb5bd;
}

.tracking-dot.active {
    background: #28a745;
}

.tracking-label {
    margin-top: 4px;
    font-size: 10px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 80px;
    color: #495057;
}

.truck {
    position: absolute;
    top: -18px;
    transform: translateX(-50%);
    font-size: 18px;
    transition: left .5s ease;
}

.card-pro {
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}


.tracking-point {
    position: absolute;
    top: -10px;
    transform: translateX(-50%);
    text-align: center;
    width: 80px;
}

/* 🔥 TOOLTIP */
.tracking-point .tooltip-custom {
    position: absolute;
    bottom: 25px;
    left: 50%;
    transform: translateX(-50%);
    background: #212529;
    color: #fff;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 11px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: 0.2s ease;
    z-index: 10;
}

/* flechita */
.tracking-point .tooltip-custom::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: #212529 transparent transparent transparent;
}

/* 🔥 HOVER */
.tracking-point:hover .tooltip-custom {
    opacity: 1;
}
</style>

<div class="container py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            <i class="bi bi-broadcast-pin"></i> Operativo en vivo
        </h5>

        <a href="{{ route('rrhh.viajes') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-clock-history"></i> Historial
        </a>
    </div>

    {{-- BUSCADOR --}}
    <form method="GET" action="{{ route('rrhh.operativo') }}" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" 
                       name="buscar" 
                       class="form-control form-control-sm"
                       placeholder="Buscar chofer..."
                       value="{{ request('buscar') }}">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="row">

        @forelse($activos as $item)

            @php
                $emp = $item['empleado'];
                $inicio = $item['inicio'];
                $reportes = $item['reportes'];
                $ultimo = $item['ultimo'];

                $puntos = $reportes->values();
                $totalReportes = $reportes->count();
                $maxPasos = 10;

                $porcentaje = min(($totalReportes / $maxPasos) * 100, 90);

                if($ultimo->tipo_evento == 'fin_jornada'){
                    $porcentaje = 100;
                }
            @endphp

            <div class="col-12">

                <div class="card card-pro mb-3 border-0">

                    <div class="card-body py-2 px-3">

                        {{-- HEADER --}}
                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <div>
                                <div style="font-size:14px;">
                                    <i class="bi bi-person-circle text-primary"></i>
                                    <strong>{{ $emp->nombre }} {{ $emp->apellido }}</strong>
                                </div>

                                <small class="text-muted" style="font-size:12px;">
                                    <i class="bi bi-truck"></i>
                                    {{ $inicio->vehiculo ?? 'Sin vehículo' }}
                                </small>
                            </div>

                            <span class="badge bg-success px-2 py-1">
                                En viaje
                            </span>

                        </div>

                        {{-- TRACKING --}}
                        <div class="tracking-wrapper">

                            <div class="tracking-line">

                                {{-- PROGRESO --}}
                                <div class="tracking-progress"
                                     style="width: {{ $porcentaje }}%;">
                                </div>

                                {{-- ORIGEN --}}
                                <div class="tracking-point" style="left:0%;">
                                    <div class="tracking-dot active"></div>
                                    <div class="tracking-label">
                                        {{ $inicio->origen ?? 'Origen' }}
                                    </div>
                                </div>

                                {{-- DESTINO --}}
                                <div class="tracking-point" style="left:100%;">
                                    <div class="tracking-dot {{ $porcentaje == 100 ? 'active' : '' }}"></div>
                                    <div class="tracking-label">
                                        {{ $inicio->destino ?? 'Destino' }}
                                    </div>
                                </div>

                                {{-- PUNTOS DINÁMICOS --}}
                                @foreach($puntos as $index => $r)

                                    @php
                                        $pos = ($porcentaje / ($puntos->count() ?: 1)) * ($index + 1);
                                    @endphp

                                    <div class="tracking-point" style="left: {{ $pos }}%;">

    <div class="tracking-dot active"></div>

    {{-- TEXTO CORTO --}}
    <div class="tracking-label">
        {{ Str::limit($r->lugar, 12) }}
    </div>

    {{-- TOOLTIP COMPLETO --}}
    <div class="tooltip-custom">
        {{ $r->lugar ?? 'Sin ubicación' }}
    </div>

</div>

                                @endforeach

                                {{-- CAMIÓN --}}
                                <div class="truck text-success" style="left: {{ $porcentaje }}%;">
                                    <i class="bi bi-truck"></i>
                                </div>

                            </div>

                        </div>

                        {{-- INFO --}}
                        <div class="d-flex justify-content-between text-muted" style="font-size:12px;">

                            <span>
                                <i class="bi bi-geo"></i>
                                {{ $ultimo->lugar ?? 'Sin ubicación' }}
                            </span>

                            <span>
                                <i class="bi bi-clock"></i>
                                {{ $ultimo->fecha_hora->format('H:i') }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="text-center text-muted">
                No hay viajes activos
            </div>

        @endforelse

    </div>

</div>

{{-- FINALIZADOS --}}
<hr class="my-4">

<h6 class="mb-3">
    <i class="bi bi-check-circle"></i> Viajes finalizados
</h6>

@forelse($finalizados as $item)

    @php
        $emp = $item['empleado'];
        $inicio = $item['inicio'];
        $ultimo = $item['ultimo'];
    @endphp

    <div class="card mb-2 shadow-sm border-0 rounded-3 bg-light">
        <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">

            <div style="font-size:13px;">
                <strong>{{ $emp->nombre }} {{ $emp->apellido }}</strong><br>
                <small class="text-muted">
                    {{ $inicio->origen }} → {{ $inicio->destino }}
                </small>
            </div>

            <div class="text-end">
                <span class="badge bg-dark mb-1">
                    Finalizado
                </span><br>
                <small class="text-muted">
                    {{ $ultimo->fecha_hora->format('H:i') }}
                </small>
            </div>

        </div>
    </div>

@empty

<div class="text-muted text-center">
    No hay viajes finalizados
</div>

@endforelse

{{-- AUTO REFRESH --}}
<script>
setTimeout(() => location.reload(), 10000);
</script>

@endsection