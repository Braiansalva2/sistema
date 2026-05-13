@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">
        <i class="bi bi-sun me-2"></i>
        Vacaciones de {{ $empleado->nombre }} {{ $empleado->apellido }}
    </h3>

    <a href="{{ route('rrhh.vacaciones.create', $empleado->id) }}"
       class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle me-1"></i> Registrar vacaciones
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($vacaciones->count() == 0)
        <p class="text-muted">No hay vacaciones registradas.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Periodo</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Días tomados</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($vacaciones as $v)
                <tr>
                    <td>{{ $v->periodo }}</td>
                    <td>{{ $v->fecha_inicio ?? '-' }}</td>
                    <td>{{ $v->fecha_fin ?? '-' }}</td>
                    <td>{{ $v->dias_tomados }}</td>
                    <td>
                        <span class="badge 
                            @if($v->estado=='pendiente') bg-warning
                            @elseif($v->estado=='aprobadas') bg-success
                            @elseif($v->estado=='rechazadas') bg-danger
                            @else bg-secondary @endif">
                            {{ ucfirst($v->estado) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('rrhh.vacaciones.edit', [$empleado->id, $v->id]) }}"
   class="btn btn-sm btn-primary">
   Editar
</a>


                       @if(auth()->user()->can('aprobar vacaciones') && $v->estado === 'pendiente')
   <form action="{{ route('rrhh.vacaciones.aprobar', [$empleado->id, $v->id]) }}"
      method="POST" class="d-inline">
    @csrf
    @method('PUT')
    <button class="btn btn-success btn-sm">
        <i class="bi bi-check2-circle"></i> Aprobar
    </button>
</form>


<form action="{{ route('rrhh.vacaciones.rechazar', [$empleado->id, $v->id]) }}"
      method="POST" class="d-inline">
    @csrf
    @method('PUT')
    <button class="btn btn-danger btn-sm">
        <i class="bi bi-x-circle"></i> Rechazar
    </button>
</form>



@endif


                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    @endif

</div>
@endsection
