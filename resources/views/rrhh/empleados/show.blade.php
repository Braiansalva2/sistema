@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold" style="color:#a44a20;">
            <i class="bi bi-person-vcard me-2"></i>Perfil del empleado
        </h2>

        <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            <div class="row">

                <!-- FOTO DE PERFIL -->
                <div class="col-md-3 text-center">
                    @php
                        $foto = $empleado->foto_perfil
                            ? Storage::url('fotos_empleados/'.$empleado->foto_perfil)
                            : asset('img/default-user.png');
                    @endphp

                    <img src="{{ $foto }}" class="img-thumbnail mb-3 rounded-circle"
                         style="width:180px; height:180px; object-fit:cover;">

                    <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-primary w-100">
                        <i class="bi bi-pencil-square me-1"></i>Editar
                    </a>
                </div>

                <!-- DATOS -->
                <div class="col-md-9">

                    <h4 class="fw-bold mb-3" style="color:#a44a20;">Datos personales</h4>

                    <div class="row">
                        <div class="col-md-6 mb-2"><strong>Nombre:</strong> {{ $empleado->nombre }}</div>
                        <div class="col-md-6 mb-2"><strong>Apellido:</strong> {{ $empleado->apellido }}</div>
                        <div class="col-md-4 mb-2"><strong>DNI:</strong> {{ $empleado->dni }}</div>
                        <div class="col-md-4 mb-2"><strong>CUIL:</strong> {{ $empleado->cuil }}</div>
                        <div class="col-md-4 mb-2"><strong>Fecha nacimiento:</strong> {{ $empleado->fecha_nacimiento }}</div>
                        <div class="col-md-6 mb-2"><strong>Teléfono:</strong> {{ $empleado->telefono }}</div>
                        <div class="col-md-6 mb-2"><strong>Email:</strong> {{ $empleado->email }}</div>
                        <div class="col-md-12 mb-2"><strong>Dirección:</strong> {{ $empleado->direccion }}</div>
                    </div>

                    <hr>

                    <h4 class="fw-bold mb-3" style="color:#a44a20;">Datos familiares / Contactos</h4>

                    @if($empleado->contactos && count($empleado->contactos))
                        @foreach ($empleado->contactos as $contacto)
                            <div class="mb-2">
                                <strong>{{ $contacto['nombre'] }}</strong>
                                ({{ $contacto['parentesco'] }}) – Tel: {{ $contacto['telefono'] }}
                                – {{ $contacto['domicilio'] }}
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Sin contactos registrados.</p>
                    @endif

                    <hr>

                    <h4 class="fw-bold mb-3" style="color:#a44a20;">Datos bancarios</h4>

                    <div class="row">
                        <div class="col-md-6 mb-2"><strong>Banco:</strong> {{ $empleado->banco->nombre_banco ?? '-' }}</div>
                        <div class="col-md-6 mb-2"><strong>CBU:</strong> {{ $empleado->cbu }}</div>
                    </div>

                    <hr>

                    <h4 class="fw-bold mb-3" style="color:#a44a20;">Datos laborales</h4>

                    <div class="row">
                        <div class="col-md-4 mb-2"><strong>Obra social:</strong> {{ $empleado->obraSocial->nombre ?? '-' }}</div>
                        <div class="col-md-4 mb-2"><strong>Condición laboral:</strong> {{ $empleado->condicionLaboral->nombre_condicion ?? '-' }}</div>
                        <div class="col-md-4 mb-2"><strong>Contrato:</strong> {{ $empleado->contrato->tipo_contrato ?? '-' }}</div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>
@endsection
