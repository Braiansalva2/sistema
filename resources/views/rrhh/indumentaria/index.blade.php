@extends('layouts.rrhh')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1"> Indumentaria</h2>

            <small class="text-muted">
                Gestión de prendas y talles
            </small>
        </div>
<div class="d-flex gap-2">

    <button class="btn btn-success rounded-pill px-4 shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalExportarEmpleados">
        📥 Exportar empleados
    </button>

    <button class="btn btn-primary rounded-pill px-4 shadow-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalNuevaPrenda">
        + Nueva prenda
    </button>

</div>

    </div>

    {{-- GRID --}}
    <div class="row align-items-start">

        @foreach($prendas as $prenda)

            <div class="col-md-4 mb-4">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body">

                        {{-- HEADER --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">

                            <div>

                                <h4 class="fw-bold mb-1">
                                    {{ $prenda->nombre }}
                                </h4>

                                <small class="text-muted">
                                    {{ $prenda->talles->count() }} talles cargados
                                </small>

                            </div>

                            @if($prenda->estado)

                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    Activo
                                </span>

                            @else

                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                    Inactivo
                                </span>

                            @endif

                        </div>

                        {{-- BOTON DESPLEGAR --}}
                        <button class="btn btn-light border shadow-sm w-100 d-flex justify-content-between align-items-center"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseTalles{{ $prenda->id }}">

                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg> Ver talles
                            </span>

                            <span class="fw-bold">
                                {{ $prenda->talles->count() }}
                            </span>

                        </button>

                        {{-- TALLES --}}
                        <div class="collapse mt-3"
                             id="collapseTalles{{ $prenda->id }}">

                            @forelse($prenda->talles as $talle)

                                <div class="border rounded-4 p-3 mb-2 bg-light">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>

                                            <h6 class="fw-bold mb-1">
                                                {{ $talle->nombre }}
                                            </h6>

                                            @if($talle->estado)

                                                <span class="badge bg-success rounded-pill">
                                                    Activo
                                                </span>

                                            @else

                                                <span class="badge bg-danger rounded-pill">
                                                    Inactivo
                                                </span>

                                            @endif

                                        </div>

                                        <button class="btn btn-outline-warning btn-sm rounded-circle"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditarTalle{{ $talle->id }}">

                                            ✏️

                                        </button>

                                    </div>

                                </div>

                                {{-- MODAL EDITAR TALLE --}}
                                <div class="modal fade"
                                     id="modalEditarTalle{{ $talle->id }}"
                                     tabindex="-1">

                                    <div class="modal-dialog">

                                        <div class="modal-content border-0 rounded-4">

                                            <form method="POST"
                                                  action="{{ route('rrhh.tipos-prenda-talles.update', $talle->id) }}">

                                                @csrf
                                                @method('PUT')

                                                <div class="modal-header border-0">

                                                    <h5 class="modal-title fw-bold">
                                                        ✏️ Editar talle
                                                    </h5>

                                                    <button type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"></button>

                                                </div>

                                                <div class="modal-body">

                                                    <div class="mb-3">

                                                        <label class="form-label fw-bold">
                                                            Nombre
                                                        </label>

                                                        <input type="text"
                                                               name="nombre"
                                                               class="form-control"
                                                               value="{{ $talle->nombre }}"
                                                               required>

                                                    </div>

                                                    <div class="mb-3">

                                                        <label class="form-label fw-bold">
                                                            Estado
                                                        </label>

                                                        <select name="estado"
                                                                class="form-select">

                                                            <option value="1"
                                                                {{ $talle->estado ? 'selected' : '' }}>
                                                                Activo
                                                            </option>

                                                            <option value="0"
                                                                {{ !$talle->estado ? 'selected' : '' }}>
                                                                Inactivo
                                                            </option>

                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="modal-footer border-0">

                                                    <button class="btn btn-primary rounded-pill px-4">
                                                        Guardar
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="alert alert-light border text-center mt-3">

                                    Sin talles cargados

                                </div>

                            @endforelse

                        </div>

                        {{-- BOTONES --}}
                        <div class="d-grid gap-2 mt-3">

                            {{-- AGREGAR TALLE --}}
                            <button class="btn btn-outline-primary rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAgregarTalle{{ $prenda->id }}">

                                + Agregar talle

                            </button>

                            {{-- EDITAR PRENDA --}}
                            <button class="btn btn-outline-warning rounded-pill"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarPrenda{{ $prenda->id }}">

                                ✏️ Editar prenda

                            </button>

                        </div>

                    </div>
                </div>
            </div>

            {{-- MODAL AGREGAR TALLE --}}
            <div class="modal fade"
                 id="modalAgregarTalle{{ $prenda->id }}"
                 tabindex="-1">

                <div class="modal-dialog">

                    <div class="modal-content border-0 rounded-4">

                        <form method="POST"
                              action="{{ route('rrhh.tipos-prenda-talles.store') }}">

                            @csrf

                            <div class="modal-header border-0">

                                <h5 class="modal-title fw-bold">
                                    + Agregar talle
                                </h5>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"></button>

                            </div>

                            <div class="modal-body">

                                <input type="hidden"
                                       name="tipo_prenda_id"
                                       value="{{ $prenda->id }}">

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Nombre
                                    </label>

                                    <input type="text"
                                           name="nombre"
                                           class="form-control"
                                           placeholder="Ej: XL o 42"
                                           required>

                                </div>

                            </div>

                            <div class="modal-footer border-0">

                                <button class="btn btn-primary rounded-pill px-4">
                                    Guardar
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

            {{-- MODAL EDITAR PRENDA --}}
            <div class="modal fade"
                 id="modalEditarPrenda{{ $prenda->id }}"
                 tabindex="-1">

                <div class="modal-dialog">

                    <div class="modal-content border-0 rounded-4">

                        <form method="POST"
                              action="{{ route('rrhh.tipos-prenda.update', $prenda->id) }}">

                            @csrf
                            @method('PUT')

                            <div class="modal-header border-0">

                                <h5 class="modal-title fw-bold">
                                    ✏️ Editar prenda
                                </h5>

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"></button>

                            </div>

                            <div class="modal-body">

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Nombre
                                    </label>

                                    <input type="text"
                                           name="nombre"
                                           class="form-control"
                                           value="{{ $prenda->nombre }}"
                                           required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Estado
                                    </label>

                                    <select name="estado"
                                            class="form-select">

                                        <option value="1"
                                            {{ $prenda->estado ? 'selected' : '' }}>
                                            Activo
                                        </option>

                                        <option value="0"
                                            {{ !$prenda->estado ? 'selected' : '' }}>
                                            Inactivo
                                        </option>

                                    </select>

                                </div>

                            </div>

                            <div class="modal-footer border-0">

                                <button class="btn btn-primary rounded-pill px-4">
                                    Guardar
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

    {{-- PAGINACION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $prendas->links() }}
    </div>

</div>

{{-- MODAL NUEVA PRENDA --}}
<div class="modal fade"
     id="modalNuevaPrenda"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content border-0 rounded-4">

            <form method="POST"
                  action="{{ route('rrhh.tipos-prenda.store') }}">

                @csrf

                <div class="modal-header border-0">

                    <h5 class="modal-title fw-bold">
                        👕 Nueva prenda
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label fw-bold">
                            Nombre
                        </label>

                        <input type="text"
                               name="nombre"
                               class="form-control"
                               placeholder="Ej: Guantes"
                               required>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button class="btn btn-primary rounded-pill px-4">
                        Guardar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
{{-- MODAL EXPORTAR EMPLEADOS --}}
<div class="modal fade" id="modalExportarEmpleados" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content border-0 rounded-4">

            <form method="GET"
                  action="{{ route('rrhh.tipos-prenda.exportar-empleados') }}">

                <div class="modal-header border-0">

                    <h5 class="modal-title fw-bold">
                        📥 Exportar talles de empleados
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Tipo de exportación
                        </label>

                        <select name="tipo_exportacion"
                                id="tipo_exportacion"
                                class="form-select"
                                required>

                            <option value="todos">
                                Todos los empleados
                            </option>

                            <option value="seleccionados">
                                Seleccionar empleados
                            </option>

                        </select>
                    </div>

                    <div id="boxEmpleadosSeleccionados" class="d-none">

                        <label class="form-label fw-bold">
                            Seleccionar empleados
                        </label>

                        <div class="border rounded-4 p-3 bg-light"
                             style="max-height: 320px; overflow-y: auto;">

                            @foreach($empleados as $empleado)

                                <div class="form-check mb-2">

                                    <input class="form-check-input empleado-check"
                                           type="checkbox"
                                           name="empleado_ids[]"
                                           value="{{ $empleado->id }}"
                                           id="empleadoExport{{ $empleado->id }}">

                                    <label class="form-check-label"
                                           for="empleadoExport{{ $empleado->id }}">
                                        {{ $empleado->apellido }} {{ $empleado->nombre }}
                                        - DNI: {{ $empleado->dni }}
                                    </label>

                                </div>

                            @endforeach

                        </div>

                        <small class="text-muted">
                            Marcá solo los empleados que querés incluir en el Excel.
                        </small>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <button type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button class="btn btn-success rounded-pill px-4">
                        Descargar Excel
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const tipoExportacion = document.getElementById('tipo_exportacion');
    const boxEmpleados = document.getElementById('boxEmpleadosSeleccionados');
    const checksEmpleados = document.querySelectorAll('.empleado-check');

    function actualizarFiltroEmpleados() {
        if (tipoExportacion.value === 'seleccionados') {
            boxEmpleados.classList.remove('d-none');
        } else {
            boxEmpleados.classList.add('d-none');

            checksEmpleados.forEach(function (check) {
                check.checked = false;
            });
        }
    }

    tipoExportacion.addEventListener('change', actualizarFiltroEmpleados);

    actualizarFiltroEmpleados();

});
</script>
@endsection