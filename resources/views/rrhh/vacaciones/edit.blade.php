@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">
        <i class="bi bi-pencil-square me-2"></i>
        Editar vacaciones de {{ $empleado->nombre }} {{ $empleado->apellido }}
    </h3>

    <form action="{{ route('rrhh.vacaciones.update', $vacacion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <!-- PERIODO -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Periodo</label>
                <input type="number" name="periodo" class="form-control"
                       value="{{ $vacacion->periodo }}" required>
            </div>

            <!-- DÍAS CORRESPONDIENTES -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Días correspondientes</label>
                <input type="number" name="dias_correspondientes" class="form-control"
                       value="{{ $vacacion->dias_correspondientes }}" required>
            </div>

            <!-- DÍAS TOMADOS -->
            <div class="col-md-4 mb-3">
                <label class="form-label">Días tomados</label>
                <input type="number" name="dias_tomados" class="form-control"
                       value="{{ $vacacion->dias_tomados }}" required>
            </div>

            <!-- FECHA INICIO -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha inicio</label>
                <input type="date" name="fecha_inicio" class="form-control"
                       value="{{ $vacacion->fecha_inicio }}" required>
            </div>

            <!-- FECHA FIN -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin" class="form-control"
                       value="{{ $vacacion->fecha_fin }}" required>
            </div>

            <!-- OBSERVACIONES -->
            <div class="col-md-12 mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3">{{ $vacacion->observaciones }}</textarea>
            </div>

        </div>

        <div class="d-flex gap-2 mt-3">
            <button class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Guardar cambios
            </button>

            <a href="{{ route('rrhh.vacaciones.index', $empleado->id) }}"
               class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>

    </form>

</div>
@endsection
