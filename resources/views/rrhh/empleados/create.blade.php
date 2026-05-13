    @extends('layouts.rrhh')

    @section('content')
    <div class="container py-4">
        <h2 class="fw-bold mb-4 text-center" style="color: #a44a20;">
            <i class="bi bi-person-plus-fill me-2"></i>Registro de nuevo empleado
        </h2>


        <form action="{{ route('rrhh.empleados.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
<strong>
    <i class="bi bi-exclamation-triangle-fill me-1"></i>
    Atención:
</strong> Completá los campos obligatorios * para continuar.
 @if ($errors->any())
<script>
    window.addEventListener('load', function () {
        const alerta = document.querySelector('.alert-danger');
        if (alerta) {
            alerta.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    });
</script>
@endif

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
                                            <label class="form-label">Nombre *</label>
                                            <input type="text" name="nombre" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Apellido *</label>
                                            <input type="text" name="apellido" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Sexo</label>
                                            <select name="sexo" class="form-control">
                                                <option value="">Seleccione</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">DNI *</label>
                                            <input type="text" name="dni" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">CUIL *</label>
                                            <input type="text" name="cuil" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Lugar de nacimiento</label>
                                            <input type="text" name="lugar_nacimiento" class="form-control" >
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Nacionalidad</label>
                                            <select name="nacionalidad" class="form-control">
                                                <option value="">Seleccione</option>
                                                <option value="Argentina">Argentina</option>
                                                <option value="Bolivia">Bolivia</option>
                                                <option value="Chile">Chile</option>
                                                <option value="Paraguay">Paraguay</option>
                                                <option value="Uruguay">Uruguay</option>
                                                <option value="Brasil">Brasil</option>
                                                <option value="Perú">Perú</option>
                                                <option value="Colombia">Colombia</option>
                                                <option value="Ecuador">Ecuador</option>
                                                <option value="Venezuela">Venezuela</option>
                                                <option value="México">México</option>
                                                <option value="España">España</option>
                                                <option value="Estados Unidos">Estados Unidos</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Estado civil</label>
                                            <select name="estado_civil" class="form-control">
                                                <option value="">Seleccione</option>
                                                <option value="Soltero">Soltero/a</option>
                                                <option value="Casado">Casado/a</option>
                                                <option value="Divorciado">Divorciado/a</option>
                                                <option value="Viudo">Viudo/a</option>
                                                <option value="Unión libre">Unión libre</option>
                                                <option value="Separado">Separado/a</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Fecha de nacimiento *</label>
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
                            Datos familiares y contactos de emergencia  - (cargar despues de crear al Empleado)
                        </button>
                    </h2>

                    <div id="collapseFamiliares" class="accordion-collapse collapse">
                        <div class="accordion-body">

                            <div id="contactos-container">
                                {{-- Contacto 0 por defecto --}}
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
{{-- DAROS GRUPO FAMILIAR --}}

<!-- 🔹 GRUPO FAMILIAR -->
<div class="accordion-item">
    <h2 class="accordion-header" id="headingGrupoFamiliar">
        <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseGrupoFamiliar"
                style="background-color:#f9e7dd; color:#6b2f1a;">
            Grupo Familiar
        </button>
    </h2>

    <div id="collapseGrupoFamiliar" class="accordion-collapse collapse">
        <div class="accordion-body">

            <div id="familiares-container">

                <!-- Familiar base -->
                <div class="row familiar-item mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="familiares[0][nombre]" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Apellido</label>
                        <input type="text" name="familiares[0][apellido]" class="form-control">
                    </div>

                    <div class="col-md-2">
                         <label class="form-label">Parentesco</label>
                     <select name="familiares[0][parentesco]" class="form-control parentesco-select" onchange="toggleOtro(this)">
                        <option value="">Seleccione</option>
                        <option value="Padre">Padre</option>
                        <option value="Madre">Madre</option>
                        <option value="Hijo">Hijo</option>
                        <option value="Hija">Hija</option>
                        <option value="Hermano">Hermano</option>
                        <option value="Hermana">Hermana</option>
                        <option value="Cónyuge">Cónyuge</option>
                        <option value="Otro">Otro</option>
                    </select>

                    <input type="text"
                        name="familiares[0][parentesco_otro]"
                        class="form-control mt-2 parentesco-otro"
                        placeholder="Especificar parentesco"
                        style="display:none;">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">DNI</label>
                        <input type="text" name="familiares[0][dni]" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">A cargo</label><br>
                        <input type="checkbox" name="familiares[0][a_cargo]">
                    </div>
                </div>

            </div>

            <button type="button" class="btn btn-sm text-white"
                    style="background-color:#a44a20;"
                    onclick="agregarFamiliar()">
                + Agregar familiar
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

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Número de cuenta</label>
                                    <input type="text" name="numero_cuenta" class="form-control">
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
                                <!-- Sucursal -->
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Sucursal *</label>
                                        <select name="sucursal_id" class="form-select" required>
                                            <option value="">Seleccione una sucursal</option>
                                            @foreach ($sucursales as $sucursal)
                                                <option value="{{ $sucursal->id }}"
                                                    {{ old('sucursal_id') == $sucursal->id ? 'selected' : '' }}>
                                                    {{ $sucursal->nombre }} ({{ $sucursal->codigo }})
                                                </option>

                                            @endforeach
                                        </select>
                                    </div>

                                <!-- Fecha de ingreso -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Fecha de ingreso</label>
                                    <input type="date" name="fecha_ingreso" class="form-control">
                                </div>

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

                                        <button type="button" class="btn text-white"
                                                style="background-color:#a44a20;"
                                                data-bs-toggle="modal" data-bs-target="#modalObraSocial">
                                            <i class="bi bi-plus-lg"></i>
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

                                   <!-- Rol / Puesto -->
                           
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Rol / Puesto</label>
                                    <div class="input-group">
                                        <select name="rol_puesto_id" id="rol_puesto_id" class="form-select">
                                            <option value="">Seleccione un rol/puesto</option>
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol->id }}">{{ $rol->nombre_puesto }}</option>
                                            @endforeach
                                        </select>

                                        <!-- Botón para abrir modal -->
                                        <button type="button" class="btn text-white"
                                                style="background-color:#a44a20;"
                                                data-bs-toggle="modal" data-bs-target="#modalRolPuesto">
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
                                {{-- tipo de empleado --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Tipo de Empleado</label>
                                        <select name="tipo_empleado" class="form-select" required>
                                            <option value="base" {{ old('tipo_empleado', $empleado->tipo_empleado ?? '') == 'base' ? 'selected' : '' }}>🟦 Base</option>
                                            <option value="chofer" {{ old('tipo_empleado', $empleado->tipo_empleado ?? '') == 'chofer' ? 'selected' : '' }}>🟧 Chofer</option>
                                            <option value="roster" {{ old('tipo_empleado', $empleado->tipo_empleado ?? '') == 'roster' ? 'selected' : '' }}>🟩 Roster</option>
                                            <option value="mixto" {{ old('tipo_empleado', $empleado->tipo_empleado ?? '') == 'mixto' ? 'selected' : '' }}>🟨 Mixto</option>
                                        </select>
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

   @push('scripts')
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('previewFoto').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

/*  AGREGAR CONTACTO — máximo 2 contactos */
function agregarContacto() {
    const container = document.getElementById('contactos-container');
    const total = container.querySelectorAll('.contacto-item').length;

    if (total >= 2) {
        alert("Solo puedes agregar hasta 2 contactos de emergencia.");
        return;
    }

    const index = total;

    const html = `
        <div class="row contacto-item mb-3">
            <div class="col-md-3">
                <label class="form-label">Nombre contacto</label>
                <input type="text" name="contactos[${index}][nombre]" class="form-control" placeholder="Nombre contacto">
            </div>
            <div class="col-md-3">
                <label class="form-label">Parentesco</label>
                <input type="text" name="contactos[${index}][parentesco]" class="form-control" placeholder="Parentesco">
            </div>
            <div class="col-md-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="contactos[${index}][telefono]" class="form-control" placeholder="Teléfono">
            </div>
            <div class="col-md-2">
                <label class="form-label">Domicilio</label>
                <input type="text" name="contactos[${index}][domicilio]" class="form-control" placeholder="Domicilio">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-sm"
                        onclick="eliminarContacto(this)" title="Quitar contacto">
                    &times;
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

/*  ELIMINAR UN CONTACTO (fila completa) */
function eliminarContacto(button) {
    const row = button.closest('.contacto-item');
    if (row) row.remove();
}


function agregarFamiliar() {
    const container = document.getElementById('familiares-container');
    const index = container.querySelectorAll('.familiar-item').length;

    const html = `
        <div class="row familiar-item mb-3">
            <div class="col-md-3">
                <input type="text" name="familiares[${index}][nombre]" class="form-control" placeholder="Nombre">
            </div>

            <div class="col-md-3">
                <input type="text" name="familiares[${index}][apellido]" class="form-control" placeholder="Apellido">
            </div>

            <div class="col-md-2">
                <input type="text" name="familiares[${index}][parentesco]" class="form-control" placeholder="Parentesco">
            </div>

            <div class="col-md-2">
                <input type="text" name="familiares[${index}][dni]" class="form-control" placeholder="DNI">
            </div>

            <div class="col-md-1 d-flex align-items-center">
                <input type="checkbox" name="familiares[${index}][a_cargo]">
            </div>

            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.familiar-item').remove()">
                    X
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}


function toggleOtro(select) {
    const container = select.closest('.familiar-item');
    const inputOtro = container.querySelector('.parentesco-otro');

    if (select.value === 'Otro') {
        inputOtro.style.display = 'block';
    } else {
        inputOtro.style.display = 'none';
        inputOtro.value = '';
    }
}
</script>
@endpush

