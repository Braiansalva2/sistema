@extends('layouts.trafico   ')

@section('title', 'Monitoreo de Flota')

@section('content')
<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-uppercase">
                Monitoreo de Flota
            </h4>
            <small class="text-muted">
                Estado en tiempo real de las unidades con GPS
            </small>
        </div>

        <span class="badge bg-secondary" id="ultima-actualizacion">
            Actualizando...
        </span>
    </div>

    {{-- FILTROS --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <select id="filtroTipo" class="form-select">
                <option value="">Todos los tipos</option>
                <option value="camion">Camión</option>
                <option value="auto">Auto</option>
                <option value="utilitario">Utilitario</option>
            </select>
        </div>
    </div>

    {{-- CUERPO PRINCIPAL --}}
    <div class="row">

        {{-- LISTADO --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    Unidades
                </div>
                <div class="list-group list-group-flush" id="lista-unidades">
                    {{-- JS inyecta acá --}}
                </div>
            </div>
        </div>

        {{-- MAPA --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">
                    Mapa
                </div>
                <div id="mapa" style="height: 600px;"></div>
            </div>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script>
let interval = 10000; // 10 segundos

function cargarEstado() {
    fetch('{{ route("trafico.strix.estado") }}')
        .then(r => r.json())
        .then(data => {
            renderLista(data.unidades);
            document.getElementById('ultima-actualizacion').innerText =
                'Actualizado: ' + new Date().toLocaleTimeString();
        });
}

function renderLista(unidades) {
    const contenedor = document.getElementById('lista-unidades');
    contenedor.innerHTML = '';

    unidades.forEach(u => {
        const item = document.createElement('div');
        item.className = 'list-group-item vehiculo-item';
        item.dataset.tipo = u.tipo;

        item.innerHTML = `
            <div class="fw-bold">${u.dominio}</div>
            <small class="text-muted">${u.tipo}</small>
            <div class="mt-1">
                <span class="badge ${u.velocidad > 0 ? 'bg-success' : 'bg-primary'}">
                    ${u.velocidad > 0 ? 'En movimiento' : 'Detenido'}
                </span>
                <span class="badge bg-warning text-dark">
                    ${u.velocidad} km/h
                </span>
            </div>
        `;

        contenedor.appendChild(item);
    });
}

cargarEstado();
setInterval(cargarEstado, interval);
</script>
@endpush
    