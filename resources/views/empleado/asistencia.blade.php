@extends('layouts.empleados')

@section('content')

<div class="container">

    {{-- PERFIL --}}
    <div class="perfil-box d-flex align-items-center">

        <img src="{{ $empleado->foto_perfil 
            ? asset('storage/fotos_empleados/'.$empleado->foto_perfil) 
            : asset('img/default.png') }}"
            class="avatar me-3">

        <div>
            <h5 class="mb-0">
                {{ $empleado->apellido }} {{ $empleado->nombre }}
            </h5>

            <small class="text-muted">
                Asistencia laboral
            </small>
        </div>
    </div>

    {{-- ALERTA SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            {{ session('success') }}
        </div>
    @endif

    {{-- ALERTA ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">
            {{ session('error') }}
        </div>
    @endif

    {{-- TARJETA PRINCIPAL --}}
    <div class="card shadow border-0 rounded-4">

        <div class="card-body text-center p-4">

            <h4 class="mb-2">
                {{ now()->format('d/m/Y') }}
            </h4>

            {{-- HORA --}}
            <h1 class="fw-bold text-primary mb-4" id="hora">
                --
            </h1>

            {{-- ESTADO --}}
            @if($accion == 'entrada')

                <div class="alert alert-success border-0 rounded-3">
                    <i class="bi bi-check-circle"></i>
                    Listo para marcar entrada
                </div>

            @else

                <div class="alert alert-danger border-0 rounded-3">
                    <i class="bi bi-exclamation-circle"></i>
                    Jornada iniciada - marcar salida
                </div>

            @endif

            {{-- FORMULARIO --}}
            <form method="POST"
                  action="{{ route('empleado.asistencia.marcar') }}"
                  id="formAsistencia">

                @csrf

                {{-- GPS --}}
                <input type="hidden" name="latitud" id="latitud">
                <input type="hidden" name="longitud" id="longitud">

                {{-- BOTÓN --}}
               @if($accion == 'entrada')

                    <button type="button"
                        id="btnAsistencia"
                        onclick="marcarAsistencia()"
                        class="btn btn-success btn-lg px-5 py-3 rounded-pill shadow">

                        <i class="bi bi-box-arrow-in-right"></i>
                        MARCAR ENTRADA

                    </button>

                @else

                    <button type="button"
                        id="btnAsistencia"
                        onclick="marcarAsistencia()"
                        class="btn btn-danger btn-lg px-5 py-3 rounded-pill shadow">

                        <i class="bi bi-box-arrow-left"></i>
                        MARCAR SALIDA

                    </button>

                @endif

            </form>

        </div>
    </div>

    {{-- MOVIMIENTOS --}}
    <div class="card shadow-sm border-0 rounded-4 mt-4">

        <div class="card-body">

            <h5 class="mb-3">
                <i class="bi bi-clock-history"></i>
                Movimientos de hoy
            </h5>

            @forelse($movimientosHoy as $movimiento)

                <div class="d-flex justify-content-between align-items-center border-bottom py-2">

                    <div>

                        @if($movimiento->tipo == 'entrada')

                            <span class="badge bg-success">
                                Entrada
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Salida
                            </span>

                        @endif

                    </div>

                    <div>

                        {{ $movimiento->fecha_hora->format('H:i') }}

                    </div>

                </div>

            @empty

                <div class="alert alert-light mb-0">
                    No hay movimientos registrados hoy.
                </div>

            @endforelse

        </div>
    </div>

</div>

{{-- ========================================= --}}
{{-- MODAL CONSENTIMIENTO --}}
{{-- ========================================= --}}

<div class="modal fade"
     id="modalConsentimiento"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">
                    <i class="bi bi-shield-check"></i>
                    Consentimiento de Asistencia
                </h5>

            </div>

            <div class="modal-body">

                <p>
                    Para utilizar el sistema de asistencia, es necesario aceptar el uso de ubicación y datos relacionados al registro laboral.
                </p>

                <div class="alert alert-light border">

                    <small>

                        Al continuar, usted autoriza a GVH Logística Minera a registrar:

                        <ul class="mt-2">
                            <li>Fecha y hora de asistencia</li>
                            <li>Ubicación GPS al momento de marcar</li>
                            <li>Información básica del dispositivo</li>
                        </ul>

                        Esta información será utilizada únicamente para fines laborales y administrativos.

                    </small>

                </div>

                {{-- CHECKBOX --}}
                <div class="form-check mt-3">

                    <input class="form-check-input"
                           type="checkbox"
                           id="checkConsentimiento">

                    <label class="form-check-label" for="checkConsentimiento">

                        Declaro haber leído y acepto las condiciones del sistema de asistencia.

                    </label>

                </div>

            </div>

            <div class="modal-footer">

                <form method="POST"
                      action="{{ route('empleado.asistencia.consentimiento') }}">

                    @csrf

                    <button type="submit"
                            id="btnAceptarConsentimiento"
                            class="btn btn-primary rounded-pill px-4"
                            disabled>

                        Aceptar y continuar

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
{{-- ========================================= --}}
{{-- HORA EN VIVO --}}
{{-- ========================================= --}}
<script>

function actualizarHora() {

    const ahora = new Date();

    document.getElementById('hora').innerHTML =
        ahora.toLocaleTimeString('es-AR');
}

setInterval(actualizarHora, 1000);

actualizarHora();

</script>

{{-- ========================================= --}}
{{-- GPS --}}
{{-- ========================================= --}}
<script>
function marcarAsistencia() {

    let btn = document.getElementById('btnAsistencia');

    btn.disabled = true;
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Obteniendo ubicación...
    `;

    if (!navigator.geolocation) {
        document.getElementById('formAsistencia').submit();
        return;
    }

    navigator.geolocation.getCurrentPosition(

        function(position) {

            document.getElementById('latitud').value =
                position.coords.latitude;

            document.getElementById('longitud').value =
                position.coords.longitude;

            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Registrando...
            `;

            document.getElementById('formAsistencia').submit();
        },

        function(error) {

            // Si no obtiene GPS, igual registra asistencia
            document.getElementById('formAsistencia').submit();
        },

        {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        }
    );
}

</script>

{{-- ========================================= --}}
{{-- BLOQUEAR DOBLE CLICK --}}
{{-- ========================================= --}}
<script>

document.getElementById('formAsistencia')
.addEventListener('submit', function() {

    let btn = document.getElementById('btnAsistencia');

    btn.disabled = true;

    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Procesando...
    `;
});

</script>
{{-- ========================================= --}}
{{-- HABILITAR BOTÓN CONSENTIMIENTO --}}
{{-- ========================================= --}}
<script>

const checkboxConsentimiento =
    document.getElementById('checkConsentimiento');

const btnConsentimiento =
    document.getElementById('btnAceptarConsentimiento');

if (checkboxConsentimiento) {

    checkboxConsentimiento.addEventListener('change', function() {

        btnConsentimiento.disabled = !this.checked;
    });
}

</script>
{{-- ========================================= --}}
{{-- ABRIR MODAL SI NO ACEPTÓ --}}
{{-- ========================================= --}}
@if(!$consentimiento)

<script>

document.addEventListener('DOMContentLoaded', function() {

    let modal = new bootstrap.Modal(
        document.getElementById('modalConsentimiento')
    );

    modal.show();
});
</script>
@endif
@endsection