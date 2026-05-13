@extends('layouts.empleados')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="text-center mb-5">

        <div class="mb-3">

            <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow"
                 style="width:90px; height:90px; font-size:40px;">

                <i class="bi bi-person-workspace"></i>

            </div>

        </div>

        <h3 class="fw-bold mb-2">
            Jornada laboral
        </h3>

        <p class="text-muted mb-0">
            Seleccioná el tipo de jornada que vas a realizar hoy
        </p>

    </div>

    {{-- OPCIONES --}}
    <div class="row justify-content-center g-4">

        {{-- BASE --}}
        <div class="col-md-5">

            <a href="{{ route('empleado.asistencia') }}"
               class="text-decoration-none">

                <div class="card opcion-card border-0 shadow-lg h-100 rounded-4 overflow-hidden">

                    {{-- TOP --}}
                    <div class="bg-primary text-white text-center py-4">

                        <div class="icon-circle bg-white text-primary mx-auto mb-3">

                            <i class="bi bi-building"></i>

                        </div>

                        <h4 class="fw-bold mb-1">
                            Trabajar en Base
                        </h4>

                        <small class="opacity-75">
                            Jornada presencial en sucursal
                        </small>

                    </div>

                    {{-- BODY --}}
                    <div class="card-body p-4">

                        <ul class="list-unstyled small text-muted mb-0">

                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                Marcación de entrada y salida
                            </li>

                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                Validación GPS de asistencia
                            </li>

                        </ul>

                    </div>

                    {{-- FOOTER --}}
                    <div class="card-footer bg-white border-0 p-4">

                        <div class="btn btn-primary w-100 rounded-pill fw-semibold">

                            Ingresar

                            <i class="bi bi-arrow-right-circle ms-1"></i>

                        </div>

                    </div>

                </div>

            </a>

        </div>

        {{-- VIAJE --}}
        <div class="col-md-5">

            <a href="{{ route('empleado.operativo.viaje') }}"
               class="text-decoration-none">

                <div class="card opcion-card border-0 shadow-lg h-100 rounded-4 overflow-hidden">

                    {{-- TOP --}}
                    <div class="bg-warning text-dark text-center py-4">

                        <div class="icon-circle bg-dark text-warning mx-auto mb-3">

                            <i class="bi bi-truck"></i>

                        </div>

                        <h4 class="fw-bold mb-1">
                            Salir a Viaje
                        </h4>

                        <small class="opacity-75">
                            Jornada operativa y logística
                        </small>

                    </div>

                    {{-- BODY --}}
                    <div class="card-body p-4">

                        <ul class="list-unstyled small text-muted mb-0">

                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                Seguimiento operativo GPS
                            </li>

                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                Puntos de control en ruta
                            </li>

                            <li class="mb-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                Trazabilidad de viajes
                            </li>

                        </ul>

                    </div>

                    {{-- FOOTER --}}
                    <div class="card-footer bg-white border-0 p-4">

                        <div class="btn btn-warning w-100 rounded-pill fw-semibold">

                            Ingresar

                            <i class="bi bi-arrow-right-circle ms-1"></i>

                        </div>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>

{{-- ESTILOS --}}
<style>

.opcion-card{
    transition: all .25s ease;
}

.opcion-card:hover{
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,.15) !important;
}

.icon-circle{
    width:80px;
    height:80px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:35px;
}

</style>

@endsection