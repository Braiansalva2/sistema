@extends('layouts.empleados')

@section('content')

<div class="container py-4 text-center">


{{--  MODAL CONSENTIMIENTO OPERATIVO --}}
@if(!$consentimiento)

<div class="modal fade"
     id="modalConsentimiento"
     data-bs-backdrop="static"
     data-bs-keyboard="false"
     tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="bg-dark text-white p-4">

                <h5 class="mb-1 fw-bold">

                    <i class="bi bi-shield-lock"></i>

                    Consentimiento operativo

                </h5>

                <small class="text-light opacity-75">
                    Sistema de seguimiento operativo y geolocalización
                </small>

            </div>

            {{-- FORM --}}
            <form method="POST"
                  action="{{ route('empleado.operativo.consentimiento') }}">

                @csrf

                <div class="modal-body text-start p-4">

                    <p class="small text-muted mb-3">

                        Para utilizar el módulo operativo,
                        es necesario aceptar el consentimiento
                        de uso y trazabilidad laboral.

                    </p>

                    {{-- LISTA --}}
                    <div class="border rounded-4 p-3 bg-light mb-4">

                        <div class="small fw-semibold mb-2">
                            El sistema podrá registrar:
                        </div>

                        <ul class="small mb-0">

                            <li>Ubicación GPS durante viajes</li>

                            <li>Puntos de control operativos</li>

                            <li>Inicio y finalización de jornadas</li>

                            <li>Información técnica del dispositivo</li>

                            <li>Trazabilidad laboral y operativa</li>

                        </ul>

                    </div>

                    {{-- TEXTO LEGAL --}}
                    <div class="border rounded-4 p-3 mb-4 small">

                        Declaro haber leído y comprendido
                        la información detallada anteriormente,
                        y otorgo mi consentimiento para el uso
                        del sistema operativo y registro de datos
                        asociados a jornadas operativas iniciadas
                        desde el portal del empleado.

                    </div>

                    {{-- CHECKBOX --}}
                    <div class="form-check">

                        <input class="form-check-input"
                               type="checkbox"
                               id="checkConsentimiento"
                               required>

                        <label class="form-check-label small"
                               for="checkConsentimiento">

                            He leído y acepto los términos,
                            condiciones y el consentimiento
                            de uso del sistema operativo.

                        </label>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 px-4 pb-4">

                    <button type="submit"
                            class="btn btn-success rounded-pill px-4">

                        <i class="bi bi-check-circle"></i>

                        Aceptar consentimiento

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif



    <h5 class="mb-4">Jornada Operativa</h5>

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    {{-- 🔵 ESTADO: NO INICIÓ --}}
    @if(!$ultimo || $ultimo->tipo_evento == 'fin_jornada')

        <div class="card p-3 shadow-sm mb-3">
            <h6 class="mb-3">Datos del viaje</h6>
                    <form method="POST" action="{{ route('empleado.operativo.iniciar') }}" id="formIniciar">
                        @csrf

                        <input type="text"
                            name="vehiculo"
                            class="form-control mb-2"
                            placeholder="Vehículo"
                            required>

                        <input type="text"
                            name="origen"
                            class="form-control mb-2"
                            placeholder="Origen"
                            required>

                        <input type="text"
                            name="destino"
                            class="form-control mb-3"
                            placeholder="Destino"
                            required>

                        {{-- GPS --}}
                        <input type="hidden" name="latitud" id="latitud_inicio">
                        <input type="hidden" name="longitud" id="longitud_inicio">

                        <button type="button"
                                onclick="obtenerUbicacion('inicio')"
                                class="btn btn-success w-100">

                            <i class="bi bi-play-circle"></i>
                            Iniciar viaje

                        </button>

                    </form>
        </div>

    @endif


    {{-- 🟡 ESTADO: VIAJE INICIADO --}}
    @if($ultimo && in_array($ultimo->tipo_evento, ['inicio_jornada','punto_control']))

        {{-- BOTÓN REPORTAR --}}
        <div class="card p-3 shadow-sm mb-3">

            <form method="POST" action="{{ route('empleado.operativo.reportar') }}">
                @csrf

                <input type="hidden" name="latitud" id="latitud">
                <input type="hidden" name="longitud" id="longitud">

                <button type="button" onclick="obtenerUbicacion('reportar')" class="btn btn-warning w-100">
                    <i class="bi bi-geo-alt"></i> Reportar ubicación
                </button>

            </form>

        </div>

    @endif


    {{-- 🔴 ESTADO: YA HIZO AL MENOS UN REPORTE --}}
    @if($ultimo && $ultimo->tipo_evento == 'punto_control')

        <div class="card p-3 shadow-sm">

            <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalFinalizar">
                <i class="bi bi-stop-circle"></i> Finalizar viaje
            </button>

        </div>

    @endif

</div>


{{-- 🔴 MODAL FINALIZAR --}}
<div class="modal fade" id="modalFinalizar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Finalizar viaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        ¿Estás seguro que querés finalizar el viaje?
      </div>

      <div class="modal-footer">

        <form method="POST" action="{{ route('empleado.operativo.finalizar') }}">
            @csrf

            <input type="hidden" name="latitud" id="latitud_fin">
            <input type="hidden" name="longitud" id="longitud_fin">

            <button type="button" onclick="obtenerUbicacion('finalizar')" class="btn btn-danger">
                Sí, finalizar
            </button>
        </form>

      </div>

    </div>
  </div>
</div>


{{-- GPS SCRIPT --}}
<script>

function obtenerUbicacion(tipo)
{
    navigator.geolocation.getCurrentPosition(function(position) {

        //  INICIAR
        if (tipo === 'inicio') {

            document.getElementById('latitud_inicio').value =
                position.coords.latitude;

            document.getElementById('longitud_inicio').value =
                position.coords.longitude;

            document.getElementById('formIniciar').submit();
        }

        //  REPORTAR
        if (tipo === 'reportar') {

            document.getElementById('latitud').value =
                position.coords.latitude;

            document.getElementById('longitud').value =
                position.coords.longitude;

            document.querySelector('form[action*="reportar"]').submit();
        }

        //  FINALIZAR
        if (tipo === 'finalizar') {

            document.getElementById('latitud_fin').value =
                position.coords.latitude;

            document.getElementById('longitud_fin').value =
                position.coords.longitude;

            document.querySelector('form[action*="finalizar"]').submit();
        }

    }, function(error) {

        alert("No se pudo obtener la ubicación");

    });
}

</script>


{{-- 🔥 AUTO MOSTRAR MODAL --}}
@if(!$consentimiento)

<script>

document.addEventListener('DOMContentLoaded', function(){

    let modal = new bootstrap.Modal(
        document.getElementById('modalConsentimiento')
    );

    modal.show();

});

</script>

@endif

@endsection