@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">Editar legajo de: {{ $empleado->apellido }}, {{ $empleado->nombre }}</h3>

    <div class="card shadow border-0">
        <div class="card-body">

            <form action="{{ route('rrhh.empleados.legajos.update', [$empleado->id, $legajo->id]) }}" 
                  method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre del archivo *</label>
                    <input type="text" name="nombre_archivo" class="form-control"
                           value="{{ $legajo->nombre_archivo }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control">{{ $legajo->descripcion }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control"
                               value="{{ $legajo->fecha_inicio }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fecha_fin" class="form-control"
                               value="{{ $legajo->fecha_fin }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="vigente" {{ $legajo->estado == 'vigente' ? 'selected' : '' }}>Vigente</option>
                        <option value="por_vencer" {{ $legajo->estado == 'por_vencer' ? 'selected' : '' }}>Por vencer</option>
                        <option value="vencido" {{ $legajo->estado == 'vencido' ? 'selected' : '' }}>Vencido</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Archivo (solo si querés reemplazar)</label>
                    <input type="file" name="archivo" class="form-control">
                </div>

                <button class="btn btn-primary px-4">Actualizar</button>

                <a href="{{ route('rrhh.empleados.legajos.index', $empleado->id) }}" class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</div>
@endsection
