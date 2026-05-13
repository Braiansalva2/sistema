@extends('layouts.empleados')

@section('content')

<div class="container">

    {{-- FORMULARIO --}}
    <div class="card shadow-sm border-0 mb-3" style="border-radius:15px;">
        <div class="card-body">

            <h5 class="mb-3">📄 Solicitar Permiso</h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('empleado.permisos.store') }}">
                @csrf

                {{-- DATOS EMPLEADO --}}
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

                {{-- TIPO --}}
                <div class="mb-2">
                    <label class="small text-muted">Tipo de permiso</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="dias">Por días</option>
                        <option value="horas">Por horas</option>
                    </select>
                </div>

                {{-- DIAS --}}
                <div id="bloque_dias">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="small text-muted">Desde</label>
                            <input type="date" name="fecha_desde" class="form-control">
                        </div>

                        <div class="col-6 mb-2">
                            <label class="small text-muted">Hasta</label>
                            <input type="date" name="fecha_hasta" class="form-control">
                        </div>
                    </div>
                </div>

                {{-- HORAS --}}
               <div id="bloque_horas" style="display:none;">

                    <div class="mb-2">
                        <label class="small text-muted">Día del permiso</label>
                        <input type="date" name="fecha_horas" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="small text-muted">Desde</label>
                            <input type="time" name="hora_desde" class="form-control">
                        </div>

                        <div class="col-6 mb-2">
                            <label class="small text-muted">Hasta</label>
                            <input type="time" name="hora_hasta" class="form-control">
                        </div>
                    </div>

                </div>

                {{-- MOTIVO --}}
                <div class="mb-2">
                    <label class="small text-muted">Motivo</label>
                    <textarea name="motivo" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-2">
                    <label class="small text-muted">Total</label>
                    <input type="text" id="total_preview" class="form-control" readonly>
                </div>  <hr>
                <button class="btn btn-primary w-100 mt-2" style="border-radius:10px;">
                    Solicitar Permiso
                </button>

            </form>
        </div>
    </div>

    {{-- HISTORIAL --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">📄 Mis solicitudes</h6>
    </div>

    @forelse($permisos as $permiso)
     <div class="card border-0 shadow-sm mb-2" style="border-radius:12px;">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <strong>
                    {{ $permiso->tipo == 'dias' ? 'Por días' : 'Por horas' }}
                </strong>
                <br>
<small class="text-muted">
    @if($permiso->tipo == 'dias')

        📅 Permiso:
        {{ \Carbon\Carbon::parse($permiso->fecha_desde)->format('d/m/Y') }} 
        → 
        {{ \Carbon\Carbon::parse($permiso->fecha_hasta)->format('d/m/Y') }}

        <br>

        <small class="text-success">
            {{ $permiso->total_dias }} días
        </small>

    @else

        📅 Día del permiso:
        {{ \Carbon\Carbon::parse($permiso->fecha_horas)->format('d/m/Y') }}

        <br>

        ⏰ Horario:
        {{ $permiso->hora_desde }} → {{ $permiso->hora_hasta }}

        <br>

        <small class="text-success"> 🕒 Cantidad de horas:
            {{ floor($permiso->total_horas / 60) }}h 
            {{ $permiso->total_horas % 60 }}m
        </small>

    @endif
</small>

{{-- 🔥 FECHA DE SOLICITUD --}}
<small class="text-muted d-block mt-1">
    🕒 Solicitado: {{ $permiso->created_at->format('d/m/Y') }}
</small>
            </div>

            <div>
                @if($permiso->estado == 'pendiente')
                    <span class="badge bg-warning text-dark">Pendiente</span>
                @elseif($permiso->estado == 'aprobado')
                    <span class="badge bg-success">Aprobado</span>
                @elseif($permiso->estado == 'rechazado')
                    <span class="badge bg-danger">Rechazado</span>
                @endif
            </div>
        </div>

        <small class="text-muted d-block">
            Motivo: {{ $permiso->motivo }}
        </small>

    </div>
</div>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted">
                No hay solicitudes de permisos registradas.
            </div>
        </div>
    @endforelse

</div>

{{-- SCRIPT --}}
<script>
document.getElementById('tipo').addEventListener('change', function() {
    if (this.value === 'dias') {
        document.getElementById('bloque_dias').style.display = 'block';
        document.getElementById('bloque_horas').style.display = 'none';
    } else {
        document.getElementById('bloque_dias').style.display = 'none';
        document.getElementById('bloque_horas').style.display = 'block';
    }
});
</script>
<script>
function calcularDias() {
    let desde = document.querySelector('[name="fecha_desde"]').value;
    let hasta = document.querySelector('[name="fecha_hasta"]').value;

    if (desde && hasta) {
        let f1 = new Date(desde);
        let f2 = new Date(hasta);

        let diff = Math.ceil((f2 - f1) / (1000 * 60 * 60 * 24)) + 1;

        if (diff > 0) {
            document.getElementById('total_preview').value = diff + ' días';
        }
    }
}

function calcularHoras() {
    let desde = document.querySelector('[name="hora_desde"]').value;
    let hasta = document.querySelector('[name="hora_hasta"]').value;

    if (desde && hasta) {
        let [h1, m1] = desde.split(':').map(Number);
        let [h2, m2] = hasta.split(':').map(Number);

        let inicio = new Date(0, 0, 0, h1, m1);
        let fin = new Date(0, 0, 0, h2, m2);

        let diffMs = fin - inicio;

        if (diffMs > 0) {
            let horas = Math.floor(diffMs / (1000 * 60 * 60));
            let minutos = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

            document.getElementById('total_preview').value = horas + 'h ' + minutos + 'm';
        }
    }
}

// EVENTOS
document.querySelector('[name="fecha_desde"]').addEventListener('change', calcularDias);
document.querySelector('[name="fecha_hasta"]').addEventListener('change', calcularDias);

document.querySelector('[name="hora_desde"]').addEventListener('change', calcularHoras);
document.querySelector('[name="hora_hasta"]').addEventListener('change', calcularHoras);
</script>
@endsection