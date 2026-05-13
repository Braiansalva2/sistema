@extends('layouts.empleados')

@section('content')

{{-- PERFIL --}}
<div class="perfil-box d-flex align-items-center">

    <img src="{{ $empleado->foto_perfil 
        ? asset('storage/fotos_empleados/'.$empleado->foto_perfil) 
                    : asset('img/default.png') }}"
        class="avatar me-3">
        

    <div>
        <strong>{{ $empleado->nombre }} {{ $empleado->apellido }}</strong><br>
        <small>DNI: {{ $empleado->dni }}</small><br>
        <small>{{ $empleado->rolPuesto->nombre ?? 'Empleado' }}</small>
    </div>

</div>


<div class="accordion mt-3" id="accordionEmpleado">

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingGrupo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#grupoFamiliar">
                👨‍👩‍👧 Grupo Familiar
            </button>
        </h2>

        <div id="grupoFamiliar" class="accordion-collapse collapse" data-bs-parent="#accordionEmpleado">
            <div class="accordion-body">

                <!-- CONTENIDO -->
            <ul class="list-group">

                    @forelse($empleado->grupoFamiliar as $familiar)
                        <li class="list-group-item">
                            {{ $familiar->nombre_oculto }} {{ $familiar->apellido_oculto }}
                        </li>
                    @empty
                        <li class="list-group-item text-muted">
                            No hay familiares registrados
                        </li>
                    @endforelse

            </ul>

                {{-- <a href="#" class="btn btn-sm btn-primary mt-2">
                    Ver completo
                </a> --}}

            </div>
        </div>
    </div>

</div>  <br>

<h6 class="mb-3">Accesos rápidos</h6>

<div class="row g-3">

    <div class="col-6">
        <a href="{{ route('empleado.perfil') }}" class="text-decoration-none text-dark">
            <div class="card card-opcion text-center p-3">
                <i class="bi bi-person icono text-primary"></i>
                <div>Mi Perfil</div>
            </div>
        </a>
    </div>
@php
    $tipo = auth()->user()->empleado->tipo_empleado;
@endphp

@if($tipo !== 'roster')
<div class="col-6">
    <a href="{{ route('empleado.asistencia') }}" style="text-decoration:none;">
        <div class="card card-opcion text-center p-3">
            <i class="bi bi-clock icono text-success"></i>
            <div>Asistencia</div>
        </div>
    </a>
</div>
@endif

{{-- <div class="col-6">
    <a href="{{ route('empleado.permisos') }}" style="text-decoration:none;">
        <div class="card card-opcion text-center p-3">

            <i class="bi bi-calendar-check icono text-success"></i>

            <div class="mt-2">Permisos</div>  

        </div>
    </a>
</div> --}}


<div class="col-6">
    <a href="{{ route('empleado.licencias') }}" style="text-decoration:none;">
        <div class="card card-opcion text-center p-3">

            <i class="bi bi-file-earmark-text icono text-primary"></i>

            <div class="mt-2">Licencias</div>  

        </div>
    </a>
</div>

@php
    $tipo = auth()->user()->empleado->tipo_empleado;
@endphp

@if(in_array($tipo, ['chofer','mixto']))
<div class="col-6">
    <a href="{{ route('empleado.jornada.selector') }}" style="text-decoration:none;">
        <div class="card card-opcion text-center p-3">

            <i class="bi bi-truck icono text-warning"></i>

            <div class="mt-2">Jornada</div>

        </div>
    </a>
</div>
@endif


 @php
    $tipo = auth()->user()->empleado->tipo_empleado;
@endphp

{{-- BOTÓN CALENDARIO DE ROSTER --}}
@if($tipo === 'roster')
<div class="col-6">
    <a href="{{ route('empleado.roster') }}" style="text-decoration:none;">
        <div class="card card-opcion text-center p-3">

            <i class="bi bi-calendar3 icono text-info"></i>

            <div class="mt-2">Mi Roster</div>

        </div>
    </a>
</div>
@endif

  <div class="col-6">
    <a href="{{ route('empleado.adelantos') }}" style="text-decoration:none;">
        <div class="card card-opcion text-center p-3">
            <i class="bi bi-cash icono text-danger"></i>
            <div>Adelantos</div>
        </div>
    </a>
</div>

</div>
<br><br>
@endsection