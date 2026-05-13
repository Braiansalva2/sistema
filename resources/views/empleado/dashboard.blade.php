@extends('layouts.empleados')

@section('content')

<h5 class="mb-3">👋 Bienvenido</h5>

<div class="row g-3">

    {{-- PERFIL --}}
    <div class="col-6">
        <a href="{{ route('empleado.perfil') }}" class="text-decoration-none text-dark">
            <div class="card card-opcion text-center p-3">
                <i class="bi bi-person icono text-primary"></i>
                <div>Mi Perfil</div>
            </div>
        </a>
    </div>

    {{-- ASISTENCIA --}}
    <div class="col-6">
        <a href="#" class="text-decoration-none text-dark">
            <div class="card card-opcion text-center p-3">
                <i class="bi bi-clock icono text-success"></i>
                <div>Asistencia</div>
            </div>
        </a>
    </div>

    {{-- VACACIONES --}}
    <div class="col-6">
        <a href="#" class="text-decoration-none text-dark">
            <div class="card card-opcion text-center p-3">
                <i class="bi bi-calendar icono text-warning"></i>
                <div>Vacaciones</div>
            </div>
        </a>
    </div>

    {{-- ADELANTOS --}}
    <div class="col-6">
        <a href="#" class="text-decoration-none text-dark">
            <div class="card card-opcion text-center p-3">
                <i class="bi bi-cash icono text-danger"></i>
                <div>Adelantos</div> 
            </div>
        </a>
    </div>

</div>

@endsection