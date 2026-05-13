@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold" style="color:#a44a20;">
            <i class="bi bi-person-vcard me-2"></i>Perfil del empleado
        </h2>

        <a href="{{ route('rrhh.empleados.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
@if(!$empleado->usuario)
    <a href="{{ route('rrhh.empleados.createUser', $empleado->id) }}" 
       class="btn btn-primary mt-3">
        Crear usuario del sistema
    </a>
@else
    <div class="mt-3">
        <span class="badge bg-success">
            Usuario asignado: {{ $empleado->usuario->email }}
        </span>
    </div>
@endif
 
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

                 <div class="d-flex flex-column gap-2">

    <a href="{{ route('rrhh.empleados.edit', $empleado->id) }}" 
       class="btn btn-primary w-100">
        <i class="bi bi-pencil-square me-1"></i>Editar
    </a>

    <a href="{{ route('rrhh.empleados.ddjj', $empleado->id) }}" 
       target="_blank"
       class="btn btn-success w-100">
        <i class="bi bi-printer"></i> Imprimir DDJJ
    </a>

</div>
                    @if($empleado->estado === 'Inactivo')
                            <form action="{{ route('rrhh.empleados.reactivar', $empleado->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-success w-100 mt-3">
                                    <i class="bi bi-person-check-fill"></i> Reactivar empleado
                                </button>
                            </form>
                        @endif
                </div>
                <!-- DATOS --> 
                <div class="col-md-9">

                    <h4 class="fw-bold mb-3" style="color:#a44a20;">Datos Personales</h4>

                                @php
                                    use Carbon\Carbon;

                                    $edad = $empleado->fecha_nacimiento 
                                        ? Carbon::parse($empleado->fecha_nacimiento)->age 
                                        : null;
                                @endphp

                                <div class="row">
                                    <div class="col-md-6 mb-2"><strong>Nombre:</strong> {{ $empleado->nombre }}</div>
                                    <div class="col-md-6 mb-2"><strong>Apellido:</strong> {{ $empleado->apellido }}</div>

                                    <div class="col-md-4 mb-2"><strong>Sexo:</strong> {{ $empleado->sexo ?? '-' }}</div>
                                    <div class="col-md-4 mb-2"><strong>DNI:</strong> {{ $empleado->dni }}</div>
                                    <div class="col-md-4 mb-2"><strong>CUIL:</strong> {{ $empleado->cuil }}</div>

                                    <div class="col-md-4 mb-2">
                                        <strong>Fecha nacimiento:</strong> 
                                        {{ $empleado->fecha_nacimiento ? \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m/Y') : '-' }}
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <strong>Edad:</strong> 
                                        <span class="badge bg-primary">
                                            {{ $edad ? $edad . ' años' : '-' }}
                                        </span>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <strong>Estado civil:</strong> {{ $empleado->estado_civil ?? '-' }}
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <strong>Lugar de nacimiento:</strong> {{ $empleado->lugar_nacimiento ?? '-' }}
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <strong>Nacionalidad:</strong> {{ $empleado->nacionalidad ?? '-' }}
                                    </div>

                                    <div class="col-md-6 mb-2"><strong>Teléfono:</strong> {{ $empleado->telefono }}</div>
                                    <div class="col-md-6 mb-2"><strong>Email:</strong> {{ $empleado->email }}</div>

                                    <div class="col-md-12 mb-2"><strong>Dirección:</strong> {{ $empleado->direccion }}</div>
                                    <div class="col-md-6 mb-2"><strong>Tipo de Empleado:</strong> 
                                    <td>
                                    
                                        @if($empleado->tipo_empleado == 'base')
                                            <span class="badge bg-primary">Base</span>
                                        @elseif($empleado->tipo_empleado == 'chofer')
                                            <span class="badge bg-warning text-dark">Chofer</span>
                                        @elseif($empleado->tipo_empleado == 'roster')
                                            <span class="badge bg-success">Roster</span>
                                        @else
                                            <span class="badge bg-secondary">Mixto</span>
                                        @endif
                                    </td>
                                    
                                    
                                    </div>
                                </div>
                                

                    <hr>


                        <h4 class="fw-bold mb-3" style="color:#a44a20;">Datos Familiares / Contactos de Emergencia</h4>
                        @if($empleado->contactosEmergencia->count())
                            @foreach ($empleado->contactosEmergencia as $contacto)
                                <div class="mb-2">
                                    <strong>{{ $contacto->nombre_contacto }}</strong>
                                    ({{ $contacto->parentesco }}) –
                                    <strong>Tel:</strong> {{ $contacto->telefono }} –
                                    {{ $contacto->domicilio }}
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Sin contactos registrados.</p>
                        @endif
                    <hr>

                   

<h4 class="fw-bold mb-3" style="color:#a44a20;">Grupo Familiar</h4>

@if($empleado->grupoFamiliar->count())
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead style="background-color:#f9e7dd;">
                <tr>
                    <th>Nombre</th>
                    <th>Parentesco</th>
                    <th>Edad</th>
                    <th>A cargo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($empleado->grupoFamiliar as $fam)
                    @php
                        $edadFam = $fam->fecha_nacimiento 
                            ? \Carbon\Carbon::parse($fam->fecha_nacimiento)->age 
                            : null;
                    @endphp
                    <tr>
                        <td>{{ $fam->nombre }} {{ $fam->apellido }}</td>
                        <td>{{ $fam->parentesco }}</td>
                        <td>{{ $edadFam ? $edadFam . ' años' : '-' }}</td>
                        <td>
                            @if($fam->a_cargo)
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">Sin grupo familiar registrado.</p>
@endif
 <hr>
                    <h4 class="fw-bold mb-3" style="color:#a44a20;">Datos Bancarios</h4>

                    <div class="row">
                        <div class="col-md-6 mb-2"><strong>Banco:</strong> {{ $empleado->banco->nombre_banco ?? '-' }}</div>
                        <div class="col-md-6 mb-2"><strong>CBU:</strong> {{ $empleado->cbu }}</div>
                        <div class="col-md-6 mb-2">
                        <strong>Número de cuenta:</strong> {{ $empleado->numero_cuenta }}
                        </div>
                    </div>

                    <hr>

                    <h4 class="fw-bold mb-3" style="color:#a44a20;">Datos Laborales</h4>

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <strong>Sucursal:</strong>
                            {{ $empleado->sucursal
                                ? $empleado->sucursal->nombre . ' (' . $empleado->sucursal->codigo . ')'
                                : '-' }}
                        </div>

                    <div class="col-md-4 mb-2">
                            <strong>Fecha ingreso:</strong> 
                            {{ $empleado->fecha_ingreso ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') : '-' }}
                    </div>
                        <div class="col-md-4 mb-2"><strong>Obra social:</strong> {{ $empleado->obraSocial->nombre ?? '-' }}</div>
                        <div class="col-md-4 mb-2"><strong>Condición laboral:</strong> {{ $empleado->condicionLaboral->nombre_condicion ?? '-' }}</div>
                        <div class="col-md-4 mb-2"><strong>Contrato:</strong> {{ $empleado->contrato->tipo_contrato ?? '-' }}</div>
                            <div class="col-md-4 mb-2">
                                <strong>Rol / Puesto:</strong> {{ $empleado->rolPuesto->nombre_puesto ?? '-' }}
                            </div>

                    </div>


                    
                </div>

            </div>

        </div>
    </div>

</div>
@endsection
