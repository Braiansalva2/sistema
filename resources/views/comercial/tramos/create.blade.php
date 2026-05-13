@extends('layouts.comercial')

@section('title', 'Nuevo Tramo')

@section('content')

<div class="container-fluid py-3">

    {{-- ================= HEADER ================= --}}
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold mb-0">Nuevo Tramo</h2>
            <small class="text-muted">Configuración de rutas y parámetros comerciales</small>
        </div>

        <div class="col text-end">
            <a href="{{ route('comercial.tramos.index') }}"
               class="btn btn-outline-secondary btn-sm">
                Volver
            </a>
        </div>
    </div>
<div class="d-flex justify-content-between mb-3">

    <!-- SERVICIOS -->
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
            ⚙️ Servicios
        </button>

        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalServicio">
                    ➕ Agregar servicio
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('comercial.tipos-servicio.index') }}">
                    📋 Ver servicios
                </a>
            </li>
        </ul>
    </div>

    <!-- UBICACIONES -->
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
            📍 Ubicaciones
        </button>

        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalUbicacion">
                    ➕ Agregar ubicación
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('comercial.ubicaciones.index') }}">
                    📋 Ver ubicaciones
                </a>
            </li>
        </ul>
    </div>

</div>
    {{-- ================= ERRORES ================= --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errores en el formulario</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('comercial.tramos.store') }}">
        @csrf

        <div class="card shadow-sm border-0">

            {{-- ================= DATOS GENERALES ================= --}}
            <div class="card-header bg-light fw-semibold">
                Datos generales
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- CODIGO --}}
                    <div class="col-md-3">
                        <label class="form-label">Código</label>
                        <input type="text"
                               name="codigo"
                               class="form-control"
                               value="{{ old('codigo') }}"
                               placeholder="Opcional">
                    </div>

                    {{-- NOMBRE --}}
                    <div class="col-md-9">
                        <label class="form-label">Nombre del tramo</label>
                        <input type="text"
                               name="nombre"
                               class="form-control"
                               value="{{ old('nombre') }}"
                               required
                               placeholder="Ej: Salta → Posco">
                    </div>

                </div>
            </div>

            {{-- ================= ORIGEN DESTINO ================= --}}
            <div class="card-header bg-light fw-semibold">
                Ubicación del tramo
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- ORIGEN --}}
                         <div class="col-md-6">
                            <label class="form-label">Origen</label>
                            <select name="origen_id" id="origen_id" class="form-select select2" required>
                                <option value="">Seleccione origen</option>
                                @foreach($ubicaciones as $u)
                                    <option value="{{ $u->id }}">
                                        {{ $u->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Destino</label>
                            <select name="destino_id" id="destino_id" class="form-select select2" required>
                                <option value="">Seleccione destino</option>
                                @foreach($ubicaciones as $u)
                                    <option value="{{ $u->id }}">
                                        {{ $u->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                </div>
            </div>

            {{-- ================= CONFIGURACION ================= --}}
            <div class="card-header bg-light fw-semibold">
                Configuración operativa
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- VEHICULO --}}
                    <div class="col-md-6">
                        <label class="form-label"> <strong> Tipo de vehículo </strong></label>
                        <select name="tipo_vehiculo_id" class="form-select">
                            <option value="">Seleccione</option>
                            @foreach($tiposVehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id }}"
                                    {{ old('tipo_vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                    {{ $vehiculo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                {{-- SERVICIO --}}
        <div class="col-md-6">
            <label class="form-label"> <strong> Tipo de Servicio</strong></label>
            <select name="tipo_servicio_id" id="tipo_servicio_id" class="form-select" required>
                <option value="">Seleccione</option>

                @foreach($tiposServicio as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                @endforeach
            </select>
        </div>

                    {{-- DISTANCIA --}}
                    <div class="col-md-6">
                        <label class="form-label">Distancia estimada (km)</label>
                        <input type="number"
                               step="0.01"
                               name="distancia_km"
                               value="{{ old('distancia_km') }}"
                               class="form-control">
                    </div>

                    {{-- TIEMPO --}}
                    <div class="col-md-6">
                        <label class="form-label">Tiempo estimado</label>
                        <input type="number"
                               name="tiempo_estimado_min"
                               value="{{ old('tiempo_estimado_min') }}"
                               class="form-control">
                    </div>

                </div>
            </div>

            {{-- ================= OBSERVACIONES ================= --}}
            <div class="card-header bg-light fw-semibold">
                Observaciones
            </div>

            <div class="card-body">
                <textarea name="observaciones"
                          rows="3"
                          class="form-control"
                          placeholder="Detalles adicionales...">{{ old('observaciones') }}</textarea>
            </div>

            {{-- ================= ESTADO ================= --}}
            <div class="card-header bg-light fw-semibold">
                Estado
            </div>

            <div class="card-body">
                <select name="estado" class="form-select">
                    <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>
                        Activo
                    </option>
                    <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>
                        Inactivo
                    </option>
                </select>
            </div>
{{-- ================= TARIFAS ================= --}}
<div class="card-header bg-light fw-semibold">
    Tarifas del tramo
</div>

<div class="card-body">

    <div id="contenedor-tarifas">

        <div class="row g-3 mb-3 tarifa-item">

            {{-- TIPO --}}
            <div class="col-md-2">
                <label class="form-label">Tipo</label>
                <select name="tarifas[0][tipo]" class="form-select">
                    <option value="">Seleccione</option>
                    <option value="viaje">Viaje</option>
                    <option value="km">Km</option>
                    <option value="m3">m³</option>
                    <option value="tonelada">Tonelada</option>
                    <option value="hora">Hora</option>
                </select>
            </div>

            {{-- PRECIO --}}
            <div class="col-md-2">
                <label class="form-label">Precio</label>
                <input type="number" step="0.01"
                       name="tarifas[0][precio]"
                       class="form-control">
            </div>

            {{-- FECHA DESDE --}}
            <div class="col-md-3">
                <label class="form-label">Desde</label>
                <input type="date"
                       name="tarifas[0][fecha_desde]"
                       class="form-control">
            </div>

            {{-- FECHA HASTA --}}
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date"
                       name="tarifas[0][fecha_hasta]"
                       class="form-control">
            </div>

            {{-- ELIMINAR --}}
            <div class="col-md-2 d-flex align-items-end">
                <button type="button"
                        class="btn btn-danger btn-sm w-100 btn-eliminar">
                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                   </svg>
                </button>
            </div>

        </div>

    </div>

    <button type="button" id="agregar-tarifa" class="btn btn-outline-primary btn-sm">
        + Agregar tarifa
    </button>

</div>
            {{-- ================= BOTONES ================= --}}
            <div class="card-footer text-end bg-light">

                <a href="{{ route('comercial.tramos.index') }}"
                   class="btn btn-secondary btn-sm">
                    Cancelar
                </a>

                <button type="submit"
                        class="btn btn-success btn-sm px-4">
                    Guardar tramo
                </button>

            </div>

        </div>
    </form>
</div>

{{-- =========== modal de servicios =============== --}}
<div class="modal fade" id="modalServicio" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Agregar Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formServicio">

                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control"></textarea>
                    </div>

                </form>

                <div id="errorServicio" class="text-danger"></div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="guardarServicio()">Guardar</button>
            </div>

        </div>
    </div>
</div>



{{-- ============ modal de ubicaciones======== --}}

<div class="modal fade" id="modalUbicacion">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formUbicacion">

                <div class="modal-header">
                    <h5>Nueva Ubicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>

                    <input type="text" name="tipo" class="form-control mb-2" placeholder="Tipo (ciudad, planta, etc)">

                    <textarea name="descripcion" class="form-control" placeholder="Descripción"></textarea>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarUbicacion()">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>


{{-- ===================SCRIPT====================== --}}


<script>
let index = 1;

document.getElementById('agregar-tarifa').addEventListener('click', function () {

    let html = `
    <div class="row g-3 mb-3 tarifa-item">

        <div class="col-md-2">
            <select name="tarifas[${index}][tipo]" class="form-select">
                <option value="">Seleccione</option>
                <option value="viaje">Viaje</option>
                <option value="km">Km</option>
                <option value="m3">m³</option>
                <option value="tonelada">Tonelada</option>
                <option value="hora">Hora</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="number" step="0.01"
                   name="tarifas[${index}][precio]"
                   class="form-control">
        </div>

        <div class="col-md-3">
            <input type="date"
                   name="tarifas[${index}][fecha_desde]"
                   class="form-control">
        </div>

        <div class="col-md-3">
            <input type="date"
                   name="tarifas[${index}][fecha_hasta]"
                   class="form-control">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm w-100 btn-eliminar">X</button>
        </div>

    </div>
    `;

    document.getElementById('contenedor-tarifas').insertAdjacentHTML('beforeend', html);

    index++;
});

// eliminar fila
document.addEventListener('click', function(e){
    if(e.target.classList.contains('btn-eliminar')){
        e.target.closest('.tarifa-item').remove();
    }
});
</script>
<script>
function guardarServicio() {

    let form = document.getElementById('formServicio');
    let data = new FormData(form);

    fetch("{{ route('comercial.tipos-servicio.storeAjax') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: data
    })
    .then(res => res.json())
    .then(data => {

        // agregar al select automáticamente
        let select = document.getElementById('tipo_servicio_id');

        let option = document.createElement('option');
        option.value = data.id;
        option.text = data.nombre;
        option.selected = true;

        select.appendChild(option);

        // cerrar modal
        let modal = bootstrap.Modal.getInstance(document.getElementById('modalServicio'));
        modal.hide();

        form.reset();

    })
    .catch(err => {
        document.getElementById('errorServicio').innerText = "Error al guardar";
    });
}
</script>

<script>
function guardarUbicacion() {

    let form = document.getElementById('formUbicacion');
    let data = new FormData(form);

    fetch("{{ route('comercial.ubicaciones.storeAjax') }}", {
        method: "POST",
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: data
    })
    .then(res => res.json())
    .then(data => {

        // ORIGEN
        let origen = document.getElementById('origen_id');
        let opt1 = new Option(data.nombre, data.id, true, true);
        origen.add(opt1);

        // DESTINO
        let destino = document.getElementById('destino_id');
        let opt2 = new Option(data.nombre, data.id, true, true);
        destino.add(opt2);

        // cerrar modal
        bootstrap.Modal.getInstance(document.getElementById('modalUbicacion')).hide();

        form.reset();
    });
}
</script>

@endsection
@push('scripts')
<script>
$(function() {
    $('#origen_id, #destino_id').select2({
        placeholder: "Buscar ubicación...",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endpush