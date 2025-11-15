@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.rrhh')

@section('content')
<div class="container py-4">
    
    <h2 class="fw-bold mb-4 text-center" style="color:#a44a20;">
        <i class="bi bi-pencil-square me-2"></i>Editar empleado
    </h2>

    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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

                                @php
                                    $foto = $empleado->foto_perfil
                                        ? Storage::url('fotos_empleados/'.$empleado->foto_perfil)
                                        : asset('img/default-user.png');
                                @endphp

                                <img id="previewFoto" src="{{ $foto }}" class="img-thumbnail mb-2"
                                     style="width:150px; height:150px; object-fit:cover;">

                                <input type="file" name="foto_perfil"
                                       class="form-control form-control-sm"
                                       accept="image/*" onchange="previewImage(event)">
                            </div>

                            <div class="col-md-9">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="nombre" value="{{ $empleado->nombre }}" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Apellido</label>
                                        <input type="text" name="apellido" value="{{ $empleado->apellido }}" class="form-control" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">DNI</label>
                                        <input type="text" name="dni" value="{{ $empleado->dni }}" class="form-control" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">CUIL</label>
                                        <input type="text" name="cuil" value="{{ $empleado->cuil }}" class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fecha nacimiento</label>
                                        <input type="date" name="fecha_nacimiento"
                                               value="{{ $empleado->fecha_nacimiento }}"
                                               class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" name="telefono" value="{{ $empleado->telefono }}" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" value="{{ $empleado->email }}" class="form-control">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" name="direccion" value="{{ $empleado->direccion }}" class="form-control">
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
                        Datos familiares y contactos
                    </button>
                </h2>

                <div id="collapseFamiliares" class="accordion-collapse collapse">
                    <div class="accordion-body">

                        <div id="contactos-container">

                            @foreach ($empleado->contactos ?? [] as $i => $contacto)
                                <div class="row contacto-item mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="contactos[{{ $i }}][nombre]"
                                               value="{{ $contacto['nombre'] }}" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Parentesco</label>
                                        <input type="text" name="contactos[{{ $i }}][parentesco]"
                                               value="{{ $contacto['parentesco'] }}" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" name="contactos[{{ $i }}][telefono]"
                                               value="{{ $contacto['telefono'] }}" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Domicilio</label>
                                        <input type="text" name="contactos[{{ $i }}][domicilio]"
                                               value="{{ $contacto['domicilio'] }}" class="form-control">
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <button type="button" class="btn btn-sm text-white" style="background-color:#a44a20;"
                            onclick="agregarContacto()">+ Agregar contacto</button>
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
                                    <select name="banco_id" class="form-select">
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
                                <input type="text" name="cbu" value="{{ $empleado->cbu }}" class="form-control">
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
                                    <select name="obra_social_id" class="form-select">
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
                                    <select name="condicion_laboral_id" class="form-select">
                                        <option value="">Seleccione</option>

                                        @foreach ($condiciones as $condicion)
                                            <option value="{{ $condicion->id }}"
                                                {{ $empleado->condicion_laboral_id == $condicion->id ? 'selected' : '' }}>
                                                {{ $condicion->nombre_condicion }}
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


                            <!-- Contrato -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contrato</label>

                                <div class="input-group">
                                    <select name="contrato_id" class="form-select">
                                        <option value="">Seleccione</option>

                                        @foreach ($contratos as $contrato)
                                            <option value="{{ $contrato->id }}"
                                                {{ $empleado->contrato_id == $contrato->id ? 'selected' : '' }}>
                                                {{ $contrato->tipo_contrato }}
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

                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-lg text-white px-5" style="background-color:#a44a20;">
                <i class="bi bi-save me-2"></i>Actualizar empleado
            </button>
        </div>

    </form>
</div>

<!-- 🟠 INCLUIMOS LOS MODALES IGUAL QUE EN CREATE -->
@include('rrhh.empleados.modales')

@endsection


@push('scripts')
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = () => document.getElementById('previewFoto').src = reader.result;
    reader.readAsDataURL(event.target.files[0]);
}

function agregarContacto() {
    const container = document.getElementById('contactos-container');
    const index = container.querySelectorAll('.contacto-item').length;

    const html = `
        <div class="row contacto-item mb-3">
            <div class="col-md-3">
                <label class="form-label">Nombre</label>
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
            <div class="col-md-3">
                <label class="form-label">Domicilio</label>
                <input type="text" name="contactos[${index}][domicilio]" class="form-control">
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}
</script>
@endpush


