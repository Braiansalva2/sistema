@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">
        Editar sanción
    </h3>

    <form action="{{ route('rrhh.sanciones.update', $sancion->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tipo de sanción</label>
            <input type="text" name="tipo_sancion" class="form-control"
                   value="{{ $sancion->tipo_sancion }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha_sancion" class="form-control"
                   value="{{ $sancion->fecha_sancion }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo</label>
            <textarea name="motivo" class="form-control" rows="3" required>{{ $sancion->motivo }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="vigente" {{ $sancion->estado == 'vigente' ? 'selected' : '' }}>Vigente</option>
                <option value="cumplida" {{ $sancion->estado == 'cumplida' ? 'selected' : '' }}>Cumplida</option>
            </select>
        </div>

        <button class="btn btn-success">
            <i class="bi bi-save me-1"></i>Actualizar
        </button>

        <a href="{{ route('rrhh.sanciones.index', $sancion->empleado_id) }}" class="btn btn-secondary ms-2">
            Cancelar
        </a>

    </form>

</div>
@endsection
