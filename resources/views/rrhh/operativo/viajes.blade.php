@extends('layouts.rrhh')

@section('content')

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-0">
                <i class="bi bi-truck text-primary"></i>
                Historial de viajes
            </h3>

            <small class="text-muted">
                Control y seguimiento de viajes realizados
            </small>
        </div>

    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <form method="GET" class="row g-3">

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Chofer
                    </label>

                    <select name="empleado_id" class="form-select">

                        <option value="">
                            Todos los choferes
                        </option>

                        @foreach($empleados as $emp)

                            <option value="{{ $emp->id }}"
                                {{ request('empleado_id') == $emp->id ? 'selected' : '' }}>

                                {{ $emp->nombre }} {{ $emp->apellido }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Desde
                    </label>

                    <input type="date"
                           name="desde"
                           value="{{ request('desde') }}"
                           class="form-control">

                </div>

                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Hasta
                    </label>

                    <input type="date"
                           name="hasta"
                           value="{{ request('hasta') }}"
                           class="form-control">

                </div>

                <div class="col-md-2 d-flex align-items-end">

                    <button class="btn btn-primary w-100 rounded-3">

                        <i class="bi bi-search"></i>
                        Buscar

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLA --}}
    <div class="card border-0 shadow rounded-4">

        <div class="card-body">

            <div class="table-responsive">

                <table id="tablaViajes"
                       class="table align-middle table-hover">

                    <thead class="table-light">

                        <tr>

                            <th>Chofer</th>
                            <th>Movil</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th width="120">Detalle</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($viajes as $v)

                            <tr>

                                <td>

                                    <div class="fw-semibold">
                                        {{ $v->empleado->nombre }}
                                        {{ $v->empleado->apellido }}
                                    </div>

                                </td>

                                <td>

                                    <span class="badge bg-dark">

                                        <i class="bi bi-truck"></i>
                                        {{ $v->vehiculo }}

                                    </span>

                                </td>

                                <td>

                                    <span class="text-muted">
                                        {{ $v->origen }}
                                    </span>

                                </td>

                                <td>

                                    <span class="text-muted">
                                        {{ $v->destino }}
                                    </span>

                                </td>

                                <td>

                                    {{ \Carbon\Carbon::parse($v->fecha_hora)->format('d/m/Y H:i') }}

                                </td>

                                <td>

                                    <span class="badge bg-success">

                                        <i class="bi bi-check-circle"></i>
                                        Finalizado

                                    </span>

                                </td>

                                <td>

                                    <button class="btn btn-sm btn-primary rounded-pill px-3"
                                            onclick="verDetalle({{ $v->id }})">

                                        <i class="bi bi-eye"></i>

                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- MODAL DETALLE --}}
<div class="modal fade"
     id="modalDetalle"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">

        <div class="modal-content border-0 rounded-4">

            {{-- HEADER --}}
            <div class="modal-header bg-dark text-white border-0">

                <h5 class="modal-title fw-bold">

                    <i class="bi bi-map"></i>
                    Seguimiento del viaje

                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>

            </div>

            {{-- BODY --}}
            <div class="modal-body bg-light">

                <div id="detalleBody"></div>

            </div>

        </div>

    </div>

</div>

{{-- DATATABLE --}}
<link rel="stylesheet"
href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>

$(document).ready(function () {

    $('#tablaViajes').DataTable({

        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },

        pageLength: 10,

        responsive: true,

        ordering: true

    });

});

function verDetalle(id)
{
    fetch('/rrhh/viajes/' + id)

        .then(res => res.json())

        .then(data => {

            let html = '';

            data.forEach(e => {

                let color = 'secondary';
                let icono = 'geo-alt';

                if(e.tipo_evento == 'inicio_jornada'){
                    color = 'success';
                    icono = 'play-circle';
                }

                if(e.tipo_evento == 'punto_control'){
                    color = 'primary';
                    icono = 'broadcast-pin';
                }

                if(e.tipo_evento == 'fin_jornada'){
                    color = 'danger';
                    icono = 'stop-circle';
                }

                html += `

                    <div class="d-flex mb-4">

                        <div class="me-3">

                            <div class="bg-${color}
                                        text-white
                                        rounded-circle
                                        d-flex
                                        align-items-center
                                        justify-content-center"
                                 style="
                                    width:45px;
                                    height:45px;
                                 ">

                                <i class="bi bi-${icono}"></i>

                            </div>

                        </div>

                        <div class="flex-grow-1">

                            <div class="card border-0 shadow-sm">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between">

                                        <div>

                                            <h6 class="fw-bold mb-1 text-${color}">

                                                ${e.tipo_evento
                                                    .replace('_', ' ')
                                                    .toUpperCase()}

                                            </h6>

                                            <div class="text-muted small">

                                                <i class="bi bi-geo-alt"></i>

                                                ${e.lugar ?? 'Sin ubicación'}

                                            </div>

                                        </div>

                                        <div class="text-end small text-muted">

                                            <i class="bi bi-clock"></i>

                                            ${e.fecha_hora}

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div> 

                `;
            });

            document.getElementById('detalleBody').innerHTML = html;

            let modal = new bootstrap.Modal(
                document.getElementById('modalDetalle')
            );

            modal.show();

        });
}

</script>

@endsection