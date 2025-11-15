@extends('layouts.rrhh')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-center" style="color: #a44a20;">
        <i class="bi bi-person-plus-fill me-2"></i>Registro de nuevo empleado
    </h2>

    <form action="{{ route('empleados.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="accordion" id="accordionEmpleado">

            <!-- 🔹 DATOS PERSONALES -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPersonal">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapsePersonal" aria-expanded="true"
                            style="background-color:#f9e7dd; color:#6b2f1a;">
                        Datos personales
                    </button>
                </h2>

                <div id="collapsePersonal" class="accordion-collapse collapse show">
                    <div class="accordion-body">

                        <div class="row mb-3">

                            <div class="col-md-3 text-center">
                                <img id="previewFoto" src="{{ asset('img/default-user.png') }}"
                                     class="img-thumbnail mb-2"
                                     style="width:150px; height:150px; object-fit:cover;">

                                <input type="file" name="foto_perfil" class="form-control form-control-sm"
                                       accept="image/*" onchange="previewImage(event)">
                            </div>

                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="nombre" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Apellido</label>
                                        <input type="text" name="apellido" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">DNI</label>
                                        <input type="text" name="dni" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">CUIL</label>
                                        <input type="text" name="cuil" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fecha de nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" name="telefono" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" name="direccion" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <!-- 🔹 DATOS FAMILIARES -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFamiliares">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseFamiliares"
                            style="background-color:#f9e7dd; color:#6b2f1a;">
                        Datos familiares y contactos de emergencia
                    </button>
                </h2>

                <div id="collapseFamiliares" class="accordion-collapse collapse">
                    <div class="accordion-body">

                        <div id="contactos-container">
                            <div class="row contacto-item mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Nombre contacto</label>
                                    <input type="text" name="contactos[0][nombre]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Parentesco</label>
                                    <input type="text" name="contactos[0][parentesco]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" name="contactos[0][telefono]" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Domicilio</label>
                                    <input type="text" name="contactos[0][domicilio]" class="form-control">
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm text-white"
                                style="background-color:#a44a20;"
                                onclick="agregarContacto()">
                            + Agregar otro contacto
                        </button>

                    </div>
                </div>
            </div>


            <!-- 🔹 DATOS BANCARIOS -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingBanco">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseBanco"
                            style="background-color:#f9e7dd; color:#6b2f1a;">
                        Datos bancarios
                    </button>
                </h2>

                <div id="collapseBanco" class="accordion-collapse collapse">
                    <div class="accordion-body">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Banco</label>
                                <div class="input-group">
                                    <select name="banco_id" id="banco_id" class="form-select">
                                        <option value="">Seleccione un banco</option>
                                        @foreach ($bancos as $banco)
                                            <option value="{{ $banco->id }}">{{ $banco->nombre_banco }}</option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="btn text-white"
                                            style="background-color:#a44a20;"
                                            data-bs-toggle="modal" data-bs-target="#modalBanco">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">CBU</label>
                                <input type="text" name="cbu" class="form-control">
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <!-- 🔹 DATOS LABORALES -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingLaboral">
                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseLaboral"
                            style="background-color:#f9e7dd; color:#6b2f1a;">
                        Datos laborales
                    </button>
                </h2>

                <div id="collapseLaboral" class="accordion-collapse collapse">
                    <div class="accordion-body">

                        <div class="row">

                            <!-- Obra social -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Obra social</label>
                             <div class="input-group">
    <select name="obra_social_id" id="obra_social_id" class="form-select">
        <option value="">Seleccione</option>
        @foreach ($obras as $obra)
            <option value="{{ $obra->id }}">{{ $obra->nombre }}</option>
        @endforeach
    </select>

    <!-- Agregar -->
    <button type="button" class="btn text-white" style="background-color:#a44a20;"
            data-bs-toggle="modal" data-bs-target="#modalObraSocial">
        <i class="bi bi-plus-lg"></i>
    </button>

    <!-- Editar obra seleccionada -->
    <button type="button" class="btn btn-warning text-white"
            onclick="abrirModalEditarObra()">
        <i class="bi bi-pencil-square"></i>
    </button>

    <!-- Eliminar obra seleccionada -->
    <button type="button" class="btn btn-danger"
            onclick="confirmarEliminarObra()">
        <i class="bi bi-trash"></i>
    </button>
</div>


                            </div>

                            <!-- Condición laboral -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Condición laboral</label>

                                <div class="input-group">
                                    <select name="condicion_laboral_id" id="condicion_id" class="form-select">
                                        <option value="">Seleccione</option>
                                        @foreach ($condiciones as $condicion)
                                            <option value="{{ $condicion->id }}">{{ $condicion->nombre_condicion }}</option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="btn text-white"
                                            style="background-color:#a44a20;"
                                            data-bs-toggle="modal" data-bs-target="#modalCondicion">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>

                            </div>

                            <!-- Contrato -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contrato</label>

                                <div class="input-group">
                                    <select name="contrato_id" id="contrato_id" class="form-select">
                                        <option value="">Seleccione</option>
                                        @foreach ($contratos as $contrato)
                                            <option value="{{ $contrato->id }}">{{ $contrato->tipo_contrato }}</option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="btn text-white"
                                            style="background-color:#a44a20;"
                                            data-bs-toggle="modal" data-bs-target="#modalContrato">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-lg text-white px-5" style="background-color:#a44a20;">
                <i class="bi bi-save me-2"></i>Guardar empleado
            </button>
        </div>

    </form>
</div>

<!-- MODALES -->
@include('rrhh.empleados.modales')

@endsection


@section('scripts')
<script>
function guardarModal(endpoint, selectId, modalId) {

    const form = document.querySelector(`#${modalId} form`);
    const data = new FormData(form);

    fetch(`{{ url('rrhh') }}/${endpoint}`, {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: data
    })
    .then(async r => {
        if (!r.ok) {
            const error = await r.json();
            console.error("ERRORES DE VALIDACIÓN:", error);
            alert("Error: faltan completar campos requeridos");
            return;
        }
        return r.json();
    })
    .then(result => {

        if (!result) return;

        const select = document.getElementById(selectId);

        const texto =
            result.nombre_banco ??
            result.tipo_contrato ??
            result.nombre_condicion ??
            result.nombre ??
            'Nuevo';

        const option = new Option(texto, result.id, true, true);
        select.add(option);

        // cerrar modal
        bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
        form.reset();
    })
    .catch(err => console.error("ERROR FETCH:", err));
}
</script>
@endsection

