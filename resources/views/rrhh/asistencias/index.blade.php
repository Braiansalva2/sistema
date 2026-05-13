@extends('layouts.rrhh')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-clock-history"></i>
                Asistencias RRHH
            </h2>

            <small class="text-muted">
                {{ now()->format('d/m/Y') }}
            </small>
        </div>

    </div>
<div class="d-flex gap-2 flex-wrap mb-4">

    <a href="{{ route('rrhh.asistencias.index') }}"
       class="btn btn-primary rounded-pill">

        <i class="bi bi-calendar-day"></i>
        Hoy

    </a>

    <a href="{{ route('rrhh.asistencias.matriz') }}"
       class="btn btn-dark rounded-pill">

        <i class="bi bi-grid-3x3-gap"></i>
        Matriz

    </a>

    <button class="btn btn-success rounded-pill"
        data-bs-toggle="modal"
        data-bs-target="#modalExportarMatriz">
    <i class="bi bi-file-earmark-excel"></i>
    Exportar
    </button>

</div>
    {{-- MÉTRICAS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow border-0 rounded-4 bg-success text-white">

                <div class="card-body">

                    <h6>Presentes</h6>

                    <h2 class="fw-bold">
                        {{ $presentes }}
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0 rounded-4 bg-danger text-white">

                <div class="card-body">

                    <h6>Ausentes</h6>

                    <h2 class="fw-bold">
                        {{ $ausentes }}
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0 rounded-4 bg-warning text-dark">

                <div class="card-body">

                    <h6>Jornadas abiertas</h6>

                    <h2 class="fw-bold">
                        {{ $abiertas }}
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow border-0 rounded-4 bg-primary text-white">

                <div class="card-body">

                    <h6>Jornadas cerradas</h6>

                    <h2 class="fw-bold">
                        {{ $cerradas }}
                    </h2>

                </div>
            </div>
        </div>

    </div>

    {{-- TARJETAS --}}
    <div class="row">

        @foreach($asistencias as $item)

            <div class="col-md-4 mb-4">

                <div class="card shadow-sm border-0 rounded-4 h-100">

                    <div class="card-body">

                        {{-- EMPLEADO --}}
                        <div class="d-flex align-items-center mb-3">

                            <img src="{{ $item['empleado']->foto_perfil
                                ? asset('storage/fotos_empleados/'.$item['empleado']->foto_perfil)
                                : asset('img/default.png') }}"
                                class="rounded-circle me-3"
                                width="60"
                                height="60"
                                style="object-fit: cover;">

                            <div>

                                <h5 class="mb-0">
                                    {{ $item['empleado']->apellido }}
                                    {{ $item['empleado']->nombre }}
                                </h5>

                                <small class="text-muted">
                                    DNI:
                                    {{ $item['empleado']->dni }}
                                </small>

                            </div>

                        </div>

                        {{-- ESTADO --}}
                        @if($item['estado'] == 'ausente')

                            <div class="alert alert-danger py-2">
                                ❌ Ausente
                            </div>

                        @elseif($item['estado'] == 'jornada_abierta')

                            <div class="alert alert-warning py-2">
                                🟡 Jornada abierta
                            </div>

                        @else

                            <div class="alert alert-success py-2">
                                ✅ Jornada cerrada
                            </div>

                        @endif

                        {{-- DATOS --}}
                        <div class="mb-2">

                            <strong>Entrada:</strong>

                            @if($item['entrada'])
                                {{ $item['entrada']->fecha_hora->format('H:i') }}
                            @else
                                -
                            @endif

                        </div>

                     <div class="mb-2">

                        <strong>Salida:</strong>

                        @if($item['salida'])

                            {{ $item['salida']->fecha_hora->format('H:i') }}

                            @if($item['salida']->automatico)
                                <span class="badge bg-warning text-dark ms-1">
                                    <i class="bi bi-robot"></i> Salida generada automáticamente.
                                </span>
                            @endif

                        @else
                            -
                        @endif

                    </div>
                        <div class="mb-3">

                            <strong>Movimientos:</strong>

                            @php

    $ultimoMovimiento = $item['movimientos']->last();

@endphp

@if($ultimoMovimiento)

    {{-- GPS OK --}}
    @if($ultimoMovimiento->estado_gps == 'correcto')

        <div class="alert alert-success py-2 small">

            <i class="bi bi-geo-alt-fill"></i>

            Dentro del rango permitido

            @if($ultimoMovimiento->distancia_base_metros)

                <br>

                Distancia:
                {{ round($ultimoMovimiento->distancia_base_metros) }}m

            @endif

        </div>

    {{-- FUERA DE ZONA --}}
    @elseif($ultimoMovimiento->estado_gps == 'fuera_de_zona')

        <div class="alert alert-warning py-2 small">

            <i class="bi bi-exclamation-triangle-fill"></i>

            Empleado fuera del rango permitido

            @if($ultimoMovimiento->distancia_base_metros)

                <br>

                Distancia:
                {{ round($ultimoMovimiento->distancia_base_metros) }}m

            @endif

        </div>

    {{-- SIN GPS --}}
    @else

        <div class="alert alert-danger py-2 small">

            <i class="bi bi-x-circle-fill"></i>

            Sin ubicación GPS

        </div>

    @endif

    {{-- BOTÓN MODAL --}}
    <button class="btn btn-outline-dark btn-sm rounded-pill w-100"
            data-bs-toggle="modal"
            data-bs-target="#modalMapa{{ $ultimoMovimiento->id }}">

        <i class="bi bi-eye"></i>
        Ver ubicación

    </button>

@endif

                        </div>

                        {{-- BOTÓN --}}
                        {{-- <button class="btn btn-outline-primary w-100 rounded-pill">

                            <i class="bi bi-eye"></i>
                            Ver detalle

                        </button> --}}

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>




@foreach($asistencias as $item)

    @php
        $ultimoMovimiento = $item['movimientos']->last();
    @endphp

    @if($ultimoMovimiento)

    <div class="modal fade"
         id="modalMapa{{ $ultimoMovimiento->id }}"
         tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content border-0 rounded-4">

                <div class="modal-header bg-dark text-white">

                    <h5 class="modal-title">

                        <i class="bi bi-geo-alt-fill"></i>
                        Ubicación asistencia

                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <strong>Tipo:</strong>

                        {{ ucfirst($ultimoMovimiento->tipo) }}

                    </div>

                    <div class="mb-3">

                        <strong>Hora:</strong>

                        {{ $ultimoMovimiento->fecha_hora->format('H:i') }}

                    </div>

                    <div class="mb-3">

                        <strong>Estado GPS:</strong>

                        @if($ultimoMovimiento->estado_gps == 'correcto')

                            <span class="badge bg-success">
                                Dentro del rango
                            </span>

                        @elseif($ultimoMovimiento->estado_gps == 'fuera_de_zona')

                            <span class="badge bg-warning text-dark">
                                Fuera de rango
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Sin GPS
                            </span>

                        @endif

                    </div>

                    @if($ultimoMovimiento->distancia_base_metros)

                    <div class="mb-3">

                        <strong>Distancia:</strong>

                        {{ round($ultimoMovimiento->distancia_base_metros) }}
                        metros

                    </div>

                    @endif

                    <div class="mb-3">

                        <strong>Latitud:</strong>

                        {{ $ultimoMovimiento->latitud }}

                        <br>

                        <strong>Longitud:</strong>

                        {{ $ultimoMovimiento->longitud }}

                    </div>

                    @if($ultimoMovimiento->latitud &&
                        $ultimoMovimiento->longitud)

                        <a href="https://www.google.com/maps?q={{ $ultimoMovimiento->latitud }},{{ $ultimoMovimiento->longitud }}"
                           target="_blank"
                           class="btn btn-primary w-100">

                            <i class="bi bi-map"></i>
                            Abrir en Google Maps

                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>

    @endif

@endforeach


<div class="modal fade" id="modalExportarMatriz" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            <form method="GET"
                  action="{{ route('rrhh.asistencias.exportar') }}">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-file-earmark-excel"></i>
                        Exportar matriz
                    </h5>
                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- Tipo --}}
                    <div class="mb-3">
                        <label class="form-label">Tipo de matriz</label>
                        <select name="tipo_personal"
                                class="form-select"
                                required>
                            <option value="base">Personal de Base</option>
                            <option value="operativo">Personal Operativo</option>
                        </select>
                    </div>

                    {{-- Mes --}}
                    <div class="mb-3">
                        <label class="form-label">Mes</label>
                        <select name="mes"
                                class="form-select"
                                required>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}"
                                    {{ $m == $mes ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Año --}}
                    <div class="mb-3">
                        <label class="form-label">Año</label>
                        <input type="number"
                               name="anio"
                               class="form-control"
                               value="{{ $anio }}"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit"
                            class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-download"></i>
                        Exportar Excel
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection