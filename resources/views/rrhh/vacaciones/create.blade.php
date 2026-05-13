@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">
        <i class="bi bi-sun me-2"></i> Registrar vacaciones de {{ $empleado->nombre }} {{ $empleado->apellido }}
    </h3>

    <form action="{{ route('rrhh.vacaciones.store', $empleado->id) }}" method="POST">
        @csrf

        <div class="row">

            <div class="col-md-4 mb-3">
                <label class="form-label">Periodo</label>
                <input type="number" name="periodo" class="form-control"
                       value="{{ date('Y') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Días correspondientes</label>
                <input type="number" name="dias_correspondientes"
                       class="form-control" value="14" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Días tomados</label>
                <input type="number" name="dias_tomados"
                       class="form-control" value="0" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin" class="form-control" required>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3"
                          placeholder="Ej: Solicita adelanto de vacaciones o motivo especial"></textarea>
            </div>

        </div>

        <button class="btn btn-primary mt-3">
            <i class="bi bi-save me-1"></i> Guardar
        </button>
    </form>

</div>
@endsection
