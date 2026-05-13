@extends('layouts.empleados')

@section('content')

<h4 class="mb-4">
    <i class="bi bi-calendar3 text-primary"></i>
    Mi Roster
</h4>

@if(!$roster)

    <div class="alert alert-warning">
        No tenés un roster asignado actualmente.
    </div>

@else

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">

            <h5 class="fw-bold text-primary">
                {{ $roster->nombre }}
            </h5>

            <p class="mb-2">
                <strong>Modalidad:</strong>
                {{ $roster->modalidad_trabajo }} x {{ $roster->modalidad_descanso }}
            </p>

            <p class="mb-2">
                <strong>Fecha de subida:</strong>
                {{ $roster->fecha_subida->format('d/m/Y') }}
            </p>

            <p class="mb-2">
                <strong>Fecha de bajada:</strong>
                {{ $roster->fecha_bajada->format('d/m/Y') }}
            </p>

            <p class="mb-0">
                <strong>Estado:</strong>
                <span class="badge bg-success">
                    {{ $roster->estado }}
                </span>
            </p>

        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body text-center">

            <h5 class="mb-3">Próximos movimientos</h5>

            <div class="row g-3">

                <div class="col-6">
                    <div class="p-3 bg-primary text-white rounded-3">
                        <small>Próxima Subida</small>
                        <h5 class="mb-0">
                            {{ $roster->proximaSubida()->format('d/m/Y') }}
                        </h5>
                    </div>
                </div>

                <div class="col-6">
                    <div class="p-3 bg-danger text-white rounded-3">
                        <small>Próxima Bajada</small>
                        <h5 class="mb-0">
                            {{ $roster->proximaBajada()->format('d/m/Y') }}
                        </h5>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endif

@endsection