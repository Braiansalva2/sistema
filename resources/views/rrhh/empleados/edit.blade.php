@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4 text-center" style="color: #a44a20;">
        <i class="bi bi-pencil-square me-2"></i>Editar empleado
    </h2>
    
<strong>
    <i class="bi bi-exclamation-triangle-fill me-1"></i>
    Atención:
</strong> Completá los campos obligatorios * para continuar.
@if ($errors->any())
<script>
    window.addEventListener('load', function () {
        const alerta = document.querySelector('.alert-danger');
        if (alerta) {
            alerta.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
</script>
@endif

    <form action="{{ route('rrhh.empleados.update', $empleado->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Se encontraron errores:</strong>
                <ul class="mt-2 mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="accordion" id="accordionEmpleado">

            <!-- 🔹 DATOS PERSONALES -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPersonal">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapsePersonal"
                            style="background-color:#f9e7dd; color:#6b2f1a;">
                        Datos personales
                    </button>
                </h2>

                <div id="collapsePersonal" class="accordion-collapse collapse show">
                    <div class="accordion-body">

                        <div class="row mb-3">

                            <div class="col-md-3 text-center">
                                @php
                                    $foto = $empleado->foto_perfil
                                        ? Storage::url('fotos_empleados/'.$empleado->foto_perfil)
                                        : asset('img/default-user.png');
                                @endphp

                                <img id="previewFoto"
                                     src="{{ $foto }}"
                                     class="img-thumbnail mb-2 rounded-circle"
                                     style="width:150px; height:150px; object-fit:cover;">

                                <input type="file" name="foto_perfil" class="form-control form-control-sm"
                                       accept="image/*" onchange="previewImage(event)">
                            </div>

                            <div class="col-md-9">
    <div class="row">

        <div class="col-md-6 mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control"
                   value="{{ $empleado->nombre }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control"
                   value="{{ $empleado->apellido }}" required>
        </div>

        <!-- 🔥 SEXO -->
        <div class="col-md-4 mb-3">
            <label class="form-label">Sexo</label>
            <select name="sexo" class="form-control">
                <option value="">Seleccione</option>
                <option value="Masculino" {{ $empleado->sexo == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                <option value="Femenino" {{ $empleado->sexo == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                <option value="Otro" {{ $empleado->sexo == 'Otro' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control"
                   value="{{ $empleado->dni }}" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">CUIL</label>
            <input type="text" name="cuil" class="form-control"
                   value="{{ $empleado->cuil }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control"
                  value="{{ $empleado->fecha_nacimiento ? \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('Y-m-d') : '' }}">
        </div>

        <!-- 🔥 ESTADO CIVIL -->
        <div class="col-md-4 mb-3">
            <label class="form-label">Estado civil</label>
            <select name="estado_civil" class="form-control">
                <option value="">Seleccione</option>
                <option value="Soltero" {{ $empleado->estado_civil == 'Soltero' ? 'selected' : '' }}>Soltero/a</option>
                <option value="Casado" {{ $empleado->estado_civil == 'Casado' ? 'selected' : '' }}>Casado/a</option>
                <option value="Divorciado" {{ $empleado->estado_civil == 'Divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                <option value="Viudo" {{ $empleado->estado_civil == 'Viudo' ? 'selected' : '' }}>Viudo/a</option>
                <option value="Unión libre" {{ $empleado->estado_civil == 'Unión libre' ? 'selected' : '' }}>Unión libre</option>
                <option value="Separado" {{ $empleado->estado_civil == 'Separado' ? 'selected' : '' }}>Separado/a</option>
                <option value="Otro" {{ $empleado->estado_civil == 'Otro' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <!-- 🔥 NACIONALIDAD -->
        <div class="col-md-4 mb-3">
            <label class="form-label">Nacionalidad</label>
        @php
            $nacionalidad = trim(old('nacionalidad', $empleado->nacionalidad));
        @endphp
                    <select name="nacionalidad" class="form-control">
                        <option value="">Seleccione</option>
                        <option value="Argentina" {{ $nacionalidad == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                        <option value="Bolivia" {{ $nacionalidad == 'Bolivia' ? 'selected' : '' }}>Bolivia</option>
                        <option value="Chile" {{ $nacionalidad == 'Chile' ? 'selected' : '' }}>Chile</option>
                        <option value="Paraguay" {{ $nacionalidad == 'Paraguay' ? 'selected' : '' }}>Paraguay</option>
                        <option value="Uruguay" {{ $nacionalidad == 'Uruguay' ? 'selected' : '' }}>Uruguay</option>
                        <option value="Brasil" {{ $nacionalidad == 'Brasil' ? 'selected' : '' }}>Brasil</option>
                        <option value="Perú" {{ $nacionalidad == 'Perú' ? 'selected' : '' }}>Perú</option>
                        <option value="Colombia" {{ $nacionalidad == 'Colombia' ? 'selected' : '' }}>Colombia</option>
                        <option value="Ecuador" {{ $nacionalidad == 'Ecuador' ? 'selected' : '' }}>Ecuador</option>
                        <option value="Venezuela" {{ $nacionalidad == 'Venezuela' ? 'selected' : '' }}>Venezuela</option>
                        <option value="México" {{ $nacionalidad == 'México' ? 'selected' : '' }}>México</option>
                        <option value="España" {{ $nacionalidad == 'España' ? 'selected' : '' }}>España</option>
                        <option value="Estados Unidos" {{ $nacionalidad == 'Estados Unidos' ? 'selected' : '' }}>Estados Unidos</option>
                        <option value="Otro" {{ $nacionalidad == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
        </div>

        <!--  LUGAR NACIMIENTO -->
        <div class="col-md-6 mb-3">
            <label class="form-label">Lugar de nacimiento</label>
            <input type="text" name="lugar_nacimiento" class="form-control"
                   value="{{ $empleado->lugar_nacimiento }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   value="{{ $empleado->telefono }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ $empleado->email }}">
        </div>

        <div class="col-md-12 mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control"
                   value="{{ $empleado->direccion }}">
        </div>

    </div>
</div>

                        </div>

                    </div>
                </div>
            </div>

            <!-- 🔹 CONTACTOS DE EMERGENCIA -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseFamiliares"
                            style="background-color:#f9e7dd; color:#6b2f1a;">
                        Datos familiares y contactos de emergencia
                    </button>
                </h2>

                <div id="collapseFamiliares" class="accordion-collapse collapse">
                    <div class="accordion-body">

                        <div id="contactos-container">

                            @foreach ($empleado->contactosemergencia as $idx => $c)
                                <div class="row contacto-item mb-3">

                                    <div class="col-md-3">
                                        <label class="form-label">Nombre contacto</label>
                                        <input type="text" class="form-control"
                                               name="contactos[{{ $idx }}][nombre]"
                                               value="{{ $c->nombre_contacto }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Parentesco</label>
                                        <input type="text" class="form-control"
                                               name="contactos[{{ $idx }}][parentesco]"
                                               value="{{ $c->parentesco }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control"
                                               name="contactos[{{ $idx }}][telefono]"
                                               value="{{ $c->telefono }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Domicilio</label>
                                        <input type="text" class="form-control"
                                               name="contactos[{{ $idx }}][domicilio]"
                                               value="{{ $c->domicilio }}">
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="eliminarContacto(this)">
                                            &times;
                                        </button>
                                    </div>

                                </div>
                            @endforeach

                            @if ($empleado->contactosemergencia->count() == 0)
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
                            @endif

                        </div>

                        <button type="button" class="btn btn-sm text-white"
                                style="background-color:#a44a20;"
                                onclick="agregarContacto()">
                            + Agregar otro contacto
                        </button>

                    </div>
                </div>
            </div>
           
<!-- 🔹 GRUPO FAMILIAR -->
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed fw-semibold" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapseGrupoFamiliar"
                style="background-color:#f9e7dd; color:#6b2f1a;">
            Grupo familiar
        </button>
    </h2>

    <div id="collapseGrupoFamiliar" class="accordion-collapse collapse">
        <div class="accordion-body">

            <div id="grupo-familiar-container">

                @foreach ($empleado->grupoFamiliar as $index => $familiar)
                    <div class="row familiar-item mb-3">

                        <input type="hidden" name="familiares[{{ $index }}][id]" value="{{ $familiar->id }}">

                        <div class="col-md-2">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="familiares[{{ $index }}][nombre]"
                                   class="form-control" value="{{ $familiar->nombre }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="familiares[{{ $index }}][apellido]"
                                   class="form-control" value="{{ $familiar->apellido }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Parentesco</label>
                            <select name="familiares[{{ $index }}][parentesco]" class="form-control">
                                <option value="Hijo" {{ $familiar->parentesco == 'Hijo' ? 'selected' : '' }}>Hijo</option>
                                <option value="Cónyuge" {{ $familiar->parentesco == 'Cónyuge' ? 'selected' : '' }}>Cónyuge</option>
                                <option value="Padre" {{ $familiar->parentesco == 'Padre' ? 'selected' : '' }}>Padre</option>
                                <option value="Madre" {{ $familiar->parentesco == 'Madre' ? 'selected' : '' }}>Madre</option>
                                <option value="Hermano" {{ $familiar->parentesco == 'Hermano' ? 'selected' : '' }}>Hermano</option>
                                <option value="Otro" {{ $familiar->parentesco == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">DNI</label>
                            <input type="text" name="familiares[{{ $index }}][dni]"
                                   class="form-control" value="{{ $familiar->dni }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Fecha nacimiento</label>
                            <input type="date" name="familiares[{{ $index }}][fecha_nacimiento]"
                                   class="form-control"
                                   value="{{ $familiar->fecha_nacimiento ? \Carbon\Carbon::parse($familiar->fecha_nacimiento)->format('Y-m-d') : '' }}">
                        </div>

                        <div class="col-md-1">
                            <label class="form-label">A cargo</label><br>
                            <input type="checkbox" name="familiares[{{ $index }}][a_cargo]" value="1"
                                {{ $familiar->a_cargo ? 'checked' : '' }}>
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger btn-sm"
                                    onclick="eliminarFamiliar(this)">
                                &times;
                            </button>
                        </div>

                    </div>
                @endforeach

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
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseBanco"
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
                                    
                                    <!-- 🔹 ID CORREGIDO -->
                                    <select name="banco_id" id="banco_id" class="form-select">
                                        <option value="">Seleccione</option>
                                        @foreach ($bancos as $banco)
                                            <option value="{{ $banco->id }}"
                                                {{ $empleado->banco_id == $banco->id ? 'selected' : '' }}>
                                                {{ $banco->nombre_banco }}
                                            </option>
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
                                <input type="text" name="cbu" class="form-control"
                                       value="{{ $empleado->cbu }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Número de cuenta</label>
                                <input type="text" name="numero_cuenta" class="form-control"
                                       value="{{ $empleado->numero_cuenta }}">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- 🔹 DATOS LABORALES -->
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-semibold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseLaboral"
                            style="background-color:#f9e7dd; color:#6b2f1a;">
                        Datos laborales
                    </button>
                </h2>
                        <!-- Sucursal -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sucursal *</label>
                            <select name="sucursal_id"
                                    class="form-select @error('sucursal_id') is-invalid @enderror"
                                    required>
                                <option value="">Seleccione una sucursal</option>

                                @foreach ($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}"
                                        {{ old('sucursal_id', $empleado->sucursal_id) == $sucursal->id ? 'selected' : '' }}>
                                        {{ $sucursal->nombre }} ({{ $sucursal->codigo }})
                                    </option>
                                @endforeach
                            </select>

                            @error('sucursal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                <div id="collapseLaboral" class="accordion-collapse collapse">
                    <div class="accordion-body">
                                 <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Fecha de ingreso</label>
                                <input type="date" name="fecha_ingreso" class="form-control" 
                                   value="{{ $empleado->fecha_ingreso ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('Y-m-d') : '' }}">
                            </div>
  
                            <!-- Obra social -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Obra social</label>
                                <div class="input-group">

                                    <!-- 🔹 ID CORREGIDO -->
                                    <select name="obra_social_id" id="obra_social_id" class="form-select">
                                        <option value="">Seleccione</option>
                                        @foreach ($obras as $obra)
                                            <option value="{{ $obra->id }}"
                                                {{ $empleado->obra_social_id == $obra->id ? 'selected' : '' }}>
                                                {{ $obra->nombre }}
                                            </option>
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

                                    <!-- 🔹 ID CORREGIDO -->
                                    <select name="condicion_laboral_id" id="condicion_id" class="form-select">
                                        <option value="">Seleccione</option>
                                        @foreach ($condiciones as $cond)
                                            <option value="{{ $cond->id }}"
                                                {{ $empleado->condicion_laboral_id == $cond->id ? 'selected' : '' }}>
                                                {{ $cond->nombre_condicion }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="btn text-white"
                                            style="background-color:#a44a20;"
                                            data-bs-toggle="modal" data-bs-target="#modalCondicion">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Rol / puesto -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Rol / Puesto</label>
                                <div class="input-group">

                                    <!-- 🔹 ID CORREGIDO -->
                                    <select name="rol_puesto_id" id="rol_puesto_id" class="form-select">
                                        <option value="">Seleccione</option>
                                        @foreach ($roles as $rol)
                                            <option value="{{ $rol->id }}"
                                                {{ $empleado->rol_puesto_id == $rol->id ? 'selected' : '' }}>
                                                {{ $rol->nombre_puesto }}
                                            </option>
                                        @endforeach
                                    </select>

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

                                    <!-- 🔹 ID CORREGIDO -->
                                    <select name="contrato_id" id="contrato_id" class="form-select">
                                        <option value="">Seleccione</option>
                                        @foreach ($contratos as $cont)
                                            <option value="{{ $cont->id }}"
                                                {{ $empleado->contrato_id == $cont->id ? 'selected' : '' }}>
                                                {{ $cont->tipo_contrato }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" class="btn text-white"
                                            style="background-color:#a44a20;"
                                            data-bs-toggle="modal" data-bs-target="#modalContrato">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- tipo de empleado  --}}}
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
            <button class="btn btn-lg text-white px-5" style="background-color:#a44a20;">
                <i class="bi bi-save me-2"></i>Guardar cambios
            </button>
        </div>

    </form>

</div>

@include('rrhh.empleados.modales')

@endsection

@push('scripts')
<script>

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = () => {
        document.getElementById('previewFoto').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

/* AGREGAR CONTACTO — máximo 2 */
function agregarContacto() {
    const container = document.getElementById('contactos-container');
    const total = container.querySelectorAll('.contacto-item').length;

    if (total >= 2) {
        alert("Solo puedes agregar hasta 2 contactos.");
        return;
    }

    const index = total;

    const html = `
        <div class="row contacto-item mb-3">
            <div class="col-md-3">
                <label class="form-label">Nombre contacto</label>
                <input type="text" name="contactos[${index}][nombre]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Parentesco</label>
                <input type="text" name="contactos[${index}][parentesco]" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="contactos[${index}][telefono]" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Domicilio</label>
                <input type="text" name="contactos[${index}][domicilio]" class="form-control">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger btn-sm"
                        onclick="eliminarContacto(this)">
                    &times;
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

function eliminarContacto(btn) {
    btn.closest('.contacto-item').remove();
}

</script>
@if ($errors->any())
<script>
    window.addEventListener('load', function () {

        const camposLaborales = [
            'sucursal_id',
            'fecha_ingreso',
            'obra_social_id',
            'condicion_laboral_id',
            'rol_puesto_id',
            'contrato_id'
        ];

        const errores = @json($errors->keys());

        if (errores.some(e => camposLaborales.includes(e))) {
            const collapse = document.getElementById('collapseLaboral');
            const button = document.querySelector('[data-bs-target="#collapseLaboral"]');

            collapse.classList.add('show');
            button.classList.remove('collapsed');
            button.setAttribute('aria-expanded', 'true');
        }
    });
</script>

@endif
@push('scripts')

<script>
function agregarFamiliar() {

    const container = document.getElementById('grupo-familiar-container');

    if (!container) {
        console.error("No existe el contenedor grupo-familiar-container");
        return;
    }

    const index = container.querySelectorAll('.familiar-item').length;

    const html = `
    <div class="row familiar-item mb-3">

        <div class="col-md-2">
             <label class="form-label">Nombre</label>
            <input type="text" name="familiares[${index}][nombre]" class="form-control" placeholder="Nombre">
        </div>

        <div class="col-md-2">
             <label class="form-label">Apellido</label>
            <input type="text" name="familiares[${index}][apellido]" class="form-control" placeholder="Apellido">
        </div>

        <div class="col-md-2">
             <label class="form-label">Parentesco</label>
            <select name="familiares[${index}][parentesco]" class="form-control">
                <option value="Hijo">Hijo</option>
                <option value="Cónyuge">Cónyuge</option>
                <option value="Padre">Padre</option>
                <option value="Madre">Madre</option>
                <option value="Hermano">Hermano</option>
                <option value="Otro">Otro</option>
            </select>
        </div>

        <div class="col-md-2">
             <label class="form-label">DNI</label>
            <input type="text" name="familiares[${index}][dni]" class="form-control" placeholder="DNI">
        </div>

        <div class="col-md-2">
             <label class="form-label">Fecha de nac</label>
            <input type="date" name="familiares[${index}][fecha_nacimiento]" class="form-control">
        </div>

        <div class="col-md-1">
             <label class="form-label">A cargo</label> <br>
            <input type="checkbox" name="familiares[${index}][a_cargo]" value="1">
        </div>

        <div class="col-md-1">
             <label class="form-label">Accion</label>
            <button type="button" class="btn btn-outline-danger btn-sm"
                    onclick="eliminarFamiliar(this)">
                &times;
            </button>
        </div>

    </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

function eliminarFamiliar(btn) {
    btn.closest('.familiar-item').remove();
}
</script>

@endpush
@endpush
