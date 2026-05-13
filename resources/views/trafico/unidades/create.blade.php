@extends('layouts.trafico')

@section('title', 'Nueva Unidad')

@section('content')

<div class="min-vh-100 d-flex justify-content-center align-items-start py-4"
     style="background: #f2c9a8; padding-bottom: 60px !important;">

    <div class="container">

        <h3 class="fw-bold mb-4 text-dark">➕ Registrar Nueva Unidad</h3>

        <div class="card shadow-lg border-0">
            <div class="card-body p-4">

                <form action="{{ route('trafico.unidades.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        {{-- CÓDIGO INTERNO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Código Interno</label>
                            <input type="text" name="cod_interno" class="form-control" required>
                        </div>

                        {{-- DOMINIO PRINCIPAL --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Dominio (Patente)</label>
                            <input type="text" name="dominio" class="form-control" placeholder="Ej: AB123CD">
                        </div>

                        {{-- COLOR --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Color</label>
                            <input type="text" name="color" class="form-control">
                        </div>

                        {{-- TIPO VEHÍCULO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipo de Vehículo</label>
                            <div class="input-group">
                                <select name="tipo_vehiculo_id" id="tipo_vehiculo_id" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>

                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalTipo">+</button>
                            </div>
                        </div>

                        {{-- MARCA --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Marca</label>
                            <div class="input-group">
                                <select name="marca_id" id="marca_id" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                    @endforeach
                                </select>

                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalMarca">+</button>
                            </div>
                        </div>

                        {{-- MODELO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Modelo</label>
                            <div class="input-group">
                                <select name="modelo_id" id="modelo_id" class="form-select" required>
                                    <option value="">Seleccione una marca primero</option>
                                </select>

                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalModelo">+</button>
                            </div>
                        </div>

                        {{-- ORIGEN --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Origen</label>
                            <select name="origen" id="origen" class="form-select" required>
                                <option value="propio" selected>Propio</option>
                                <option value="tercerizado">Tercerizado</option>
                            </select>
                        </div>

                        {{-- EMPRESA TERCERIZADA + BOTÓN "+" --}}
                        <div class="col-md-4 d-none" id="grupo-empresa-tercerizada">
                            <label class="form-label fw-semibold">Empresa Dueña (Tercerizada)</label>
                            <div class="input-group">
                                <select name="empresa_tercerizada_id" id="empresa_tercerizada_id" class="form-select">
                                    <option value="">Seleccione empresa...</option>
                                    @foreach ($empresas as $e)
                                        <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                    @endforeach
                                </select>

                                <button type="button" class="btn btn-dark"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEmpresaTercerizada">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- AÑO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Año</label>
                            <input type="number" name="anio" class="form-control" required>
                        </div>

                        {{-- KM ACTUAL --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kilometraje Actual</label>
                            <input type="number" name="km_actual" class="form-control"> 
                        </div>

                        {{-- ESTADO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="estado" class="form-select" required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="baja">Baja</option>
                                <option value="taller">En Taller</option>
                            </select>
                        </div>

                        {{-- DATOS TÉCNICOS --}}
                        <div class="col-12 d-none" id="grupo-datos-tecnicos">
                            <div class="row g-3 mt-1">

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Capacidad de carga (kg)</label>
                                    <input type="number" name="capacidad_kg" class="form-control" placeholder="Ej: 32000">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Largo total (m)</label>
                                    <input type="number" step="0.01" name="largo_total" class="form-control" placeholder="Ej: 16.50">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Alto (m)</label>
                                    <input type="number" step="0.01" name="alto" class="form-control" placeholder="Ej: 4.10">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Ancho (m)</label>
                                    <input type="number" step="0.01" name="ancho" class="form-control" placeholder="Ej: 2.60">
                                </div>

                            </div>
                        </div>

                        {{-- FECHA ALTA --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Alta</label>
                            <input type="date" name="fecha_alta" class="form-control">
                        </div>

                        {{-- FECHA BAJA --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Baja</label>
                            <input type="date" name="fecha_baja" class="form-control">
                        </div>

                        {{-- OBSERVACIONES --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Observaciones</label>
                            <textarea name="observaciones" rows="3" class="form-control"></textarea>
                        </div>

                    </div> <!-- row -->

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success px-4">Guardar Unidad</button>
                        <a href="{{ route('trafico.unidades.index') }}" class="btn btn-secondary px-4">Cancelar</a>
                    </div>

                </form>

            </div>
        </div>
    </div>

</div>

@include('trafico.unidades.modals.marca-modal')
@include('trafico.unidades.modals.modelo-modal')
@include('trafico.unidades.modals.tipo-modal')
@include('trafico.unidades.modals.empresa-tercerizada-modal')

@endsection

@push('scripts')
<script>

/* ==========================================================
   CARGAR MODELOS SEGÚN MARCA
   ========================================================== */
$(document).on('change', '#marca_id', function () {

    let marcaID = $(this).val();
    let modeloSelect = $('#modelo_id');

    if (!marcaID) {
        modeloSelect.html('<option value="">Seleccione una marca primero</option>');
        return;
    }

    $.get(
        '{{ route("trafico.modelos.porMarca", ":id") }}'.replace(':id', marcaID),
        function (data) {

            modeloSelect.empty();
            modeloSelect.append('<option value="">Seleccione...</option>');

            if (data.length === 0) {
                modeloSelect.append('<option value="">Sin modelos cargados</option>');
            }

            data.forEach(m => {
                modeloSelect.append(new Option(m.nombre, m.id));
            });
        }
    );
});

/* ==========================================================
   CUANDO SE ABRE MODAL MODELO → COPIAR MARCAS ACTUALES
   ========================================================== */
$('#modalModelo').on('show.bs.modal', function () {

    let selectPrincipal = $('#marca_id');
    let selectModal = $('#marca_id_modal');

    selectModal.empty();
    selectModal.append('<option value="">Seleccione una marca</option>');

    selectPrincipal.find('option').each(function () {
        if ($(this).val()) {
            selectModal.append(
                new Option($(this).text(), $(this).val())
            );
        }
    });

    let marcaSeleccionada = selectPrincipal.val();
    if (marcaSeleccionada) {
        selectModal.val(marcaSeleccionada);
    }
});

/* ==========================================================
   CREAR MARCA (AJAX)
   ========================================================== */
   
$('#formNuevaMarca').on('submit', function (e) {
    e.preventDefault();

    let form = $(this);

    $.post('{{ route("trafico.marcas.store") }}', form.serialize(), function (resp) {

        let option = new Option(resp.marca.nombre, resp.marca.id, true, true);

        // agregar al select principal
        $('#marca_id').append(option).trigger('change');

        // cerrar modal
        bootstrap.Modal.getInstance(
            document.getElementById('modalMarca')
        ).hide();

        form[0].reset();
    });
});

/* ==========================================================
   CREAR MODELO (AJAX)
   ========================================================== */
$('#formNuevoModelo').on('submit', function (e) {
    e.preventDefault();

    let form = $(this);

    $.post('{{ route("trafico.modelos.store") }}', form.serialize(), function (resp) {

        let option = new Option(resp.modelo.nombre, resp.modelo.id, true, true);
        $('#modelo_id').append(option);

        bootstrap.Modal.getInstance(
            document.getElementById('modalModelo')
        ).hide();

        form[0].reset();
    });
});

/* ==========================================================
   ORIGEN → EMPRESA TERCERIZADA
   ========================================================== */
function actualizarOrigen() {
    let origen = $('#origen').val();
    let grupo = $('#grupo-empresa-tercerizada');

    origen === 'tercerizado'
        ? grupo.removeClass('d-none')
        : grupo.addClass('d-none');
}

$('#origen').on('change', actualizarOrigen);
/* ==========================================================
   CREAR EMPRESA TERCERIZADA (AJAX)
   ========================================================== */
$(document).on('submit', '#formNuevaEmpresaTercerizada', function (e) {
    e.preventDefault();
    console.log('🟢 submit empresa tercerizada');

    let form = $(this);
    let erroresDiv = $('#erroresEmpresaTercerizada');

    erroresDiv.addClass('d-none').empty();

    $.ajax({
        url: "{{ route('trafico.empresas_tercerizadas.store') }}",
        type: "POST",
        data: form.serialize(),
        success: function (resp) {

            // agregar al select principal
            let option = new Option(
                resp.empresa.nombre,
                resp.empresa.id,
                true,
                true
            );

            $('#empresa_tercerizada_id').append(option);

            // cerrar modal
            bootstrap.Modal.getInstance(
                document.getElementById('modalEmpresaTercerizada')
            ).hide();

            form[0].reset();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                erroresDiv.removeClass('d-none');

                Object.values(xhr.responseJSON.errors).forEach(errArr => {
                    errArr.forEach(msg => {
                        erroresDiv.append(`<div>• ${msg}</div>`);
                    });
                });
            } else {
                alert('Error al guardar la empresa');
            }
        }
    });
});

/* ==========================================================
   DATOS TÉCNICOS SEGÚN TIPO
   ========================================================== */
function normalizarTexto(txt) {
    return (txt || '')
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, ''); // quita tildes
}

function actualizarCamposTipo() {
    let texto = normalizarTexto($('#tipo_vehiculo_id option:selected').text());

    // separar en palabras para evitar que "camioneta" matchee con "camion"
    let palabras = texto.split(/\s+/).filter(Boolean);

    // claves pesados (sin tildes porque normalizamos)
    let claves = ['camion', 'semi', 'acoplado', 'bitren', 'tractor', 'carreton']; 

    let esPesado = palabras.some(p => claves.includes(p));

    $('#grupo-datos-tecnicos').toggleClass('d-none', !esPesado);
}

// evento
$(document).on('change', '#tipo_vehiculo_id', actualizarCamposTipo);

// inicializar al cargar
$(document).ready(function () {
    actualizarCamposTipo();
});
/* ==========================================================
   CREAR TIPO DE VEHÍCULO (AJAX)
   ========================================================== */
$(document).on('submit', '#formNuevoTipo', function (e) {
    e.preventDefault();
    console.log('🟢 submit tipo vehiculo AJAX');

    let form = $(this);

    $.ajax({
        url: "{{ route('trafico.tipos_vehiculo.store') }}",
        type: "POST",
        data: form.serialize(),
        success: function (resp) {

            // agregar al select principal
            let option = new Option(
                resp.tipo.nombre,
                resp.tipo.id,
                true,
                true
            );

            $('#tipo_vehiculo_id')
                .append(option)
                .trigger('change'); // importante para datos técnicos

            // cerrar modal
            bootstrap.Modal.getInstance(
                document.getElementById('modalTipo')
            ).hide();

            form[0].reset();
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Error al guardar el tipo de vehículo');
        }
    });
});

/* ==========================================================
   INIT
   ========================================================== */
$(document).ready(function () {
    actualizarOrigen();
    actualizarCamposTipo();
});

</script>
@endpush
