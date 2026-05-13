@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4">Detalle de vacaciones</h2>

    <p><strong>Empleado:</strong> {{ $vacacion->empleado->nombre }} {{ $vacacion->empleado->apellido }}</p>
    <p><strong>Periodo:</strong> {{ $vacacion->periodo }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($vacacion->estado) }}</p>

    @if($vacacion->fecha_inicio)
    <p><strong>Desde:</strong> {{ $vacacion->fecha_inicio }}</p>
    <p><strong>Hasta:</strong> {{ $vacacion->fecha_fin }}</p>
    @endif

    @if($vacacion->aprobadoPorUsuario)
        <div class="alert alert-success mt-3">
            ✅ Aprobado por: <strong>{{ $vacacion->aprobadoPorUsuario->name }}</strong><br>
            📅 Fecha: {{ $vacacion->fecha_aprobacion }}
        </div>
    @endif

    {{-- ✅ BOTONES SOLO SI ESTÁ PENDIENTE --}}
    @if($vacacion->estado == 'pendiente')

        <form action="{{ route('rrhh.vacaciones.aprobar', $vacacion->id) }}" method="POST" class="mt-3">
            @csrf
            <button class="btn btn-success">Aprobar</button>
        </form>

        <form action="{{ route('rrhh.vacaciones.rechazar', $vacacion->id) }}" method="POST" class="mt-3">
            @csrf
            <textarea name="observaciones" class="form-control" placeholder="Motivo de rechazo"></textarea>
            <button class="btn btn-danger mt-2">Rechazar</button>
        </form>

    @endif

    <a href="{{ route('rrhh.vacaciones.index') }}" class="btn btn-secondary mt-4">Volver</a>

</div>
@endsection
