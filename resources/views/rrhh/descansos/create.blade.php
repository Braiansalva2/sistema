@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">
        <i class="bi bi-plus-circle me-2"></i> Registrar descanso
    </h3>

    <form action="{{ route('rrhh.descansos.store', $empleado->id) }}" method="POST">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Fecha inicio</label>
                <input type="date" name="fecha_inicio" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="roster">Roster</option>
                    <option value="especial">Especial</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo</label>
            <textarea name="motivo" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="programado">Programado</option>
                <option value="en_curso">En curso</option>
                <option value="finalizado">Finalizado</option>
            </select>
        </div>

        <button class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Guardar
        </button>
    </form>

</div>
@endsection
