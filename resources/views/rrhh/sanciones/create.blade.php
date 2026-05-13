@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">
        <i class="bi bi-plus-circle me-2"></i>Nueva sanción
    </h3>

    <form action="{{ route('rrhh.sanciones.store', $empleado->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tipo de sanción</label>
            <input type="text" name="tipo_sancion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha</label>
            <input type="date" name="fecha_sancion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Motivo</label>
            <textarea name="motivo" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Documento (PDF opcional)</label>
            <input type="file" name="documento" class="form-control" accept="application/pdf">
        </div>

        <button class="btn btn-success">
            <i class="bi bi-save me-1"></i>Guardar
        </button>

        <a href="{{ route('rrhh.sanciones.index', $empleado->id) }}" class="btn btn-secondary ms-2">
            Cancelar
        </a>

    </form>

</div>
@endsection
