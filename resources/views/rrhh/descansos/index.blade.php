@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">
        <i class="bi bi-moon-stars me-2"></i>
        Descansos de {{ $empleado->nombre }} {{ $empleado->apellido }}
    </h3>

    <a href="{{ route('rrhh.descansos.create', $empleado->id) }}"
       class="btn btn-primary mb-3">
        <i class="bi bi-plus-circle me-1"></i> Nuevo descanso
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($descansos->isEmpty())
        <p class="text-muted">No hay descansos registrados.</p>
    @else
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Motivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($descansos as $d)
                <tr>
                    <td>{{ $d->fecha_inicio }}</td>
                    <td>{{ $d->fecha_fin }}</td>
                    <td>{{ ucfirst($d->tipo) }}</td>
                    <td>{{ ucfirst(str_replace('_',' ',$d->estado)) }}</td>
                    <td>{{ $d->motivo ?? '-' }}</td>

                    <td>
                        <a href="{{ route('rrhh.descansos.edit', [$empleado->id, $d->id]) }}"
                           class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('rrhh.descansos.destroy', [$empleado->id, $d->id]) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Eliminar descanso?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    @endif

</div>
@endsection
