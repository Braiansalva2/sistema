@extends('layouts.empleados')

@section('content')

<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">📄 Mis licencias</h4>
        <a href="{{ route('empleado.index') }}" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- DATOS EMPLEADO --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5>Empleado</h5>
            <p class="mb-1"><strong>{{ $empleado->nombre }} {{ $empleado->apellido }}</strong></p>
            <small>DNI: {{ $empleado->dni }}</small>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-3">Solicitar licencia</h5>

            <form action="{{ route('empleado.licencias.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- TIPO --}}
                <div class="mb-3">
                    <label class="form-label">Tipo de licencia</label>
                    <select name="tipo" id="tipo" class="form-select" onchange="cambiarFormulario()" required>
                        <option value="">-- Seleccionar --</option>
                        <option value="vacaciones">Vacaciones</option>
                        <option value="enfermedad">Enfermedad</option>
                        <option value="matrimonio">Matrimonio</option>
                        <option value="nacimiento">Nacimiento</option>
                        <option value="fallecimiento">Fallecimiento</option>
                        <option value="capacitacion">Capacitación</option>
                        <option value="ordinaria">Licencia ordinaria (horas)</option>
                    </select>
                </div>

                {{-- FECHAS --}}
                <div id="bloque_fechas" class="row d-none">
                    <div class="col-md-6 mb-3">
                        <label>Fecha desde</label>
                        <input type="date" name="fecha_desde" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3" id="bloque_hasta">
                        <label>Fecha hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control">
                    </div>
                </div>

                {{-- HORAS --}}
                <div id="bloque_horas" class="row d-none">
                    <div class="col-md-6 mb-3">
                        <label>Hora desde</label>
                        <input type="time" name="hora_desde" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Hora hasta</label>
                        <input type="time" name="hora_hasta" class="form-control">
                    </div>
                </div>

                {{-- ARCHIVO --}}
                <div id="bloque_archivo" class="mb-3 d-none">
                    <label>Certificado (PDF o imagen)</label>
                    <input type="file" name="archivo" class="form-control">
                </div>

                {{-- OBSERVACIONES --}}
                <div class="mb-3">
                    <label>Observaciones</label>
                    <textarea name="observaciones" class="form-control"></textarea>
                </div>

                <button class="btn btn-primary">Enviar solicitud</button>

            </form>
        </div>
    </div>

    {{-- HISTORIAL --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="mb-3">Historial</h5>     <a href="{{ route('empleado.licencias.historial') }}" class="btn btn-outline-primary btn-sm">
                                                    Ver historial completo
                                                </a>

            @forelse($licencias as $licencia)
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <strong>{{ ucfirst($licencia->tipo) }}</strong>
                            <span class="badge bg-warning">{{ $licencia->estado }}</span>
                        </div>

                        <hr>

                        <p class="mb-1">
                            📅fecha de solicitud: {{ $licencia->fecha_desde->format('d/m/Y') }}
                            @if($licencia->fecha_hasta)
                                - {{ $licencia->fecha_hasta->format('d/m/Y') }}
                            @endif
                        </p>

                      @if($licencia->horas)

    @php
        $minutos = (int) $licencia->horas;

        $horas = intdiv($minutos, 60);
        $restoMinutos = $minutos % 60;

        if ($horas > 0 && $restoMinutos > 0) {
            $tiempo = $horas . 'h ' . $restoMinutos . 'm';
        } elseif ($horas > 0) {
            $tiempo = $horas . 'h';
        } else {
            $tiempo = $restoMinutos . 'm';
        }
    @endphp

    <p class="mb-1">⏱ {{ $tiempo }}</p>

@endif

@if($licencia->observaciones)
    <div class="mt-2 p-2 bg-light rounded">
        <small class="text-muted">📝 Observaciones</small>
        <div class="fw-semibold">
            {{ $licencia->observaciones }}
        </div>
    </div>
@endif

                        @if($licencia->archivo)
                            <a href="{{ asset('storage/'.$licencia->archivo) }}" target="_blank">
                                📎 Ver archivo
                            </a> <br>
                        @endif

                        <small class="text-muted">
                            Solicitud: {{ $licencia->created_at->format('d/m/Y') }}
                        </small>

                    </div>
                </div>
            @empty
                <div class="alert alert-info">No tenés licencias registradas.</div>
            @endforelse

        </div>
    </div>

</div>

{{-- JS DINÁMICO --}}
<script>
function cambiarFormulario() {
    let tipo = document.getElementById('tipo').value;

    let fechas = document.getElementById('bloque_fechas');
    let horas = document.getElementById('bloque_horas');
    let archivo = document.getElementById('bloque_archivo');
    let hasta = document.getElementById('bloque_hasta');

    // reset
    fechas.classList.add('d-none');
    horas.classList.add('d-none');
    archivo.classList.add('d-none');
    hasta.classList.remove('d-none');

    if (!tipo) return;

    fechas.classList.remove('d-none');

    if (tipo === 'ordinaria') {
        horas.classList.remove('d-none');
        hasta.classList.add('d-none');
    }

    if (tipo === 'enfermedad') {
        archivo.classList.remove('d-none');
    }
}
</script>

@endsection