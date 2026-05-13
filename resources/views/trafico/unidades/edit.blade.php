@extends('layouts.trafico')

@section('title', 'Editar Unidad')

@section('content')

<div class="min-vh-100 d-flex justify-content-center align-items-start py-4"
     style="background:#f2c9a8; padding-bottom:60px !important;">

    <div class="container">

        <h3 class="fw-bold mb-4 text-dark">✏️ Editar Unidad</h3>

        <div class="card shadow-lg border-0">
            <div class="card-body p-4">

                <form action="{{ route('trafico.unidades.update', $unidad->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">

                        {{-- CÓDIGO INTERNO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Código Interno</label>
                            <input type="text" name="cod_interno"
                                   value="{{ $unidad->cod_interno }}"
                                   class="form-control" required>
                        </div>

                        {{-- DOMINIO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Dominio (Patente)</label>
                            <input type="text" name="dominio"
                                   value="{{ $unidad->dominio }}"
                                   class="form-control">
                        </div>

                        {{-- COLOR --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Color</label>
                            <input type="text" name="color"
                                   value="{{ $unidad->color }}"
                                   class="form-control">
                        </div>

                        {{-- TIPO VEHÍCULO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tipo de Vehículo</label>
                            <select name="tipo_vehiculo_id" id="tipo_vehiculo_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach ($tipos as $tipo)
                                    <option value="{{ $tipo->id }}"
                                        {{ $unidad->tipo_vehiculo_id == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- MARCA --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Marca</label>
                            <select name="marca_id" id="marca_id" class="form-select" required>
                                <option value="">Seleccione...</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}"
                                        {{ $unidad->marca_id == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- MODELO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Modelo</label>
                            <select name="modelo_id" id="modelo_id" class="form-select" required>
                                @foreach ($modelosActuales as $modelo)
                                    <option value="{{ $modelo->id }}"
                                        {{ $unidad->modelo_id == $modelo->id ? 'selected' : '' }}>
                                        {{ $modelo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ORIGEN --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Origen</label>
                            <select name="origen" id="origen" class="form-select" required>
                                <option value="propio" {{ $unidad->origen === 'propio' ? 'selected' : '' }}>Propio</option>
                                <option value="tercerizado" {{ $unidad->origen === 'tercerizado' ? 'selected' : '' }}>Tercerizado</option>
                            </select>
                        </div>

                        {{-- EMPRESA TERCERIZADA --}}
                        <div class="col-md-4 d-none" id="grupo-empresa-tercerizada">
                            <label class="form-label fw-semibold">Empresa Dueña</label>
                            <select name="empresa_tercerizada_id" id="empresa_tercerizada_id" class="form-select">
                                <option value="">Seleccione empresa...</option>
                                @foreach ($empresas as $e)
                                    <option value="{{ $e->id }}"
                                        {{ $unidad->empresa_tercerizada_id == $e->id ? 'selected' : '' }}>
                                        {{ $e->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- AÑO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Año</label>
                            <input type="number" name="anio"
                                   value="{{ $unidad->anio }}"
                                   class="form-control" required>
                        </div>

                        {{-- KM --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kilometraje Actual</label>
                            <input type="number" name="km_actual"
                                   value="{{ $unidad->km_actual }}"
                                   class="form-control">
                        </div>

                        {{-- ESTADO --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="estado" class="form-select" required>
                                @foreach (['activo','inactivo','baja','taller'] as $estado)
                                    <option value="{{ $estado }}"
                                        {{ $unidad->estado === $estado ? 'selected' : '' }}>
                                        {{ ucfirst($estado) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DATOS TÉCNICOS --}}
                        <div class="col-12 d-none" id="grupo-datos-tecnicos">
                            <div class="row g-3 mt-2">
                                <div class="col-md-3">
                                    <label class="form-label">Capacidad (kg)</label>
                                    <input type="number" name="capacidad_kg"
                                           value="{{ $unidad->capacidad_kg }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Largo (m)</label>
                                    <input type="number" step="0.01" name="largo_total"
                                           value="{{ $unidad->largo_total }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Alto (m)</label>
                                    <input type="number" step="0.01" name="alto"
                                           value="{{ $unidad->alto }}"
                                           class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Ancho (m)</label>
                                    <input type="number" step="0.01" name="ancho"
                                           value="{{ $unidad->ancho }}"
                                           class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- FECHAS --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Alta</label>
                            <input type="date" name="fecha_alta"
                                   value="{{ $unidad->fecha_alta }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fecha Baja</label>
                            <input type="date" name="fecha_baja"
                                   value="{{ $unidad->fecha_baja }}"
                                   class="form-control">
                        </div>

                        {{-- OBSERVACIONES --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Observaciones</label>
                            <textarea name="observaciones" rows="3"
                                      class="form-control">{{ $unidad->observaciones }}</textarea>
                        </div>

                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-primary px-4">Actualizar Unidad</button>
                        <a href="{{ route('trafico.unidades.index') }}" class="btn btn-secondary px-4">Cancelar</a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function normalizar(txt){
    return (txt||'').toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g,'');
}

function actualizarOrigen(){
    $('#grupo-empresa-tercerizada')
        .toggleClass('d-none', $('#origen').val() !== 'tercerizado');
}

function actualizarCamposTipo(){
    let palabras = normalizar($('#tipo_vehiculo_id option:selected').text())
        .split(/\s+/);
    let pesados = ['camion','semi','acoplado','carreton','bitren','tractor'];
    $('#grupo-datos-tecnicos')
        .toggleClass('d-none', !palabras.some(p=>pesados.includes(p)));
}

$(document).ready(function(){
    actualizarOrigen();
    actualizarCamposTipo();
});

$(document).on('change','#origen',actualizarOrigen);
$(document).on('change','#tipo_vehiculo_id',actualizarCamposTipo);
</script>
@endpush
