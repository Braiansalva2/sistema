@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold mb-3">Nuevo legajo para: {{ $empleado->apellido }}, {{ $empleado->nombre }}</h3>

    <div class="card shadow border-0">
        <div class="card-body">

            <form action="{{ route('rrhh.empleados.legajos.store', $empleado->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre del archivo *</label>
                    <input type="text" name="nombre_archivo" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha fin</label>
                        <input type="date" name="fecha_fin" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado del documento</label>
                    <select name="estado" class="form-select">
                        <option value="vigente">Vigente</option>
                        <option value="por_vencer">Por vencer</option>
                        <option value="vencido">Vencido</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Archivo (PDF/JPG/PNG/WEBP) *</label>
                    <input type="file" name="archivo" class="form-control" required>
                </div>

                <button class="btn btn-success px-4">Guardar</button>

                <a href="{{ route('rrhh.empleados.legajos.index', $empleado->id) }}" class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>
    </div>

</div>
@endsection
