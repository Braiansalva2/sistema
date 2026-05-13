@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">
        <i class="bi bi-pencil-square me-2"></i>
        Editar descanso
    </h3>

    <form action="{{ route('rrhh.descansos.update', [$empleado->id, $descanso->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Fecha inicio</label>
                <input type="date" name="fecha_inicio" class="form-control"
                    value="{{ $descanso->fecha_inicio }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Fecha fin</label>
                <input type="date" name="fecha_fin" class="form-control"
                    value="{{ $descanso->fecha_fin }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="roster" {{ $descanso->tipo=='roster' ? 'selected':'' }}>Roster</option>
                    <option value="especial" {{ $descanso->tipo=='especial' ? 'selected':'' }}>Especial</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo</label>
            <textarea name="motivo" class="form-control">{{ $descanso->motivo }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="programado" {{ $descanso->estado=='programado'?'selected':'' }}>Programado</option>
                <option value="en_curso" {{ $descanso->estado=='en_curso'?'selected':'' }}>En curso</option>
                <option value="finalizado" {{ $descanso->estado=='finalizado'?'selected':'' }}>Finalizado</option>
            </select>
        </div>

        <button class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Guardar cambios
        </button>

    </form>

</div>
@endsection
