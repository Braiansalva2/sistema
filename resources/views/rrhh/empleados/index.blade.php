@extends('layouts.rrhh')

@section('content')

<style>
    /* Ocultar buscador feo de DataTables */
    div.dataTables_filter {
        display: none !important;
    }

    .empleado-card {
        border-radius: 14px;
        transition: 0.3s;
        background: white;
    }

    .empleado-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    .empleado-foto {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #a04518;
        box-shadow: 0 0 6px rgba(0,0,0,0.15);
    }
    /* 🔥 Oculta absolutamente todo DataTables */
.dataTables_length,
.dataTables_filter,
.dataTables_info,
.dataTables_paginate {
    display: none !important;
}

/* Ocultar la tabla oculta */
#tabla-oculta {
    display: none !important;
}

</style>

<div class="container-fluid">

    <!-- 🔍 BUSCADOR Y SELECTOR -->
    <div class="card shadow-sm p-3 mb-4" style="border-left: 5px solid #a04518;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <!-- BUSCADOR -->
            <div class="flex-grow-1">
                <input 
                    type="text" 
                    id="buscador" 
                    class="form-control"
                    placeholder="Buscar empleado por nombre, DNI, rol, obra social..."
                >
            </div>

            <!-- Selector de cantidad -->
            <div style="width: 200px;">
                <select id="cantidad" class="form-select">
                    <option value="8">8 por página</option>
                    <option value="12" selected>12 por página</option>
                    <option value="20">20 por página</option>
                    <option value="50">50 por página</option>
                </select>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color:#a04518;">
            <i class="bi bi-people-fill me-2"></i> Empleados
        </h2>

        <a href="{{ route('rrhh.empleados.create') }}" 
           class="btn text-white fw-semibold" 
           style="background-color: #a04518;">
           <i class="bi bi-person-plus-fill me-1"></i> Nuevo empleado
        </a>
    </div>

    <!-- 🧩 Cards dinámicas -->
    <div id="cards-container" class="row g-4"></div>

</div>

<!-- MODAL ELIMINAR -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Seguro que deseas eliminar este empleado?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <form id="formEliminar" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Eliminar</button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {

    let tabla = $('#tabla-oculta').DataTable({
        ajax: "{{ route('rrhh.empleados.json') }}",
        pageLength: 12,
        searching: true,
        ordering: true,
        paging: true,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },

        columns: [
            { data: 'apellido' },
            { data: 'nombre' },
            { data: 'dni' },
            { data: 'rol' },
            { data: 'obra' },
            { data: 'banco' },
            { data: 'contrato' },
            { data: 'foto' },
            { data: 'id' }
        ],

        drawCallback: function () {
            renderCards(this.api().rows({ page:'current' }).data());
        }
    });

   
    $('#buscador').on('keyup', function () {
        tabla.search(this.value).draw();
    });

   
    $('#cantidad').on('change', function () {
        tabla.page.len(this.value).draw();
    });

});


function renderCards(lista) {
    let cont = $("#cards-container");
    cont.empty();

    lista.each(function (e) {

        let claseEstado = (e.estado === "Inactivo")
            ? "opacity-50 border border-dark"
            : "";

        let botonAccion = (e.estado === "Inactivo")
            ? `<form action="/rrhh/empleados/${e.id}/reactivar" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-success btn-sm w-100 mt-1">
                        Reactivar
                    </button>
               </form>`
            : `<button onclick="abrirModalEliminar(${e.id})"
                class="btn btn-danger btn-sm w-100 mt-1">Dar de baja</button>`;

      let card = `
<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="empleado-card shadow-sm p-3 h-100 text-center ${claseEstado}">

        <img src="${e.foto}" class="empleado-foto mb-3">

        <h5 class="fw-bold text-dark">${e.apellido}, ${e.nombre}</h5>
        <small class="d-block mb-1"><strong>DNI:</strong> ${e.dni}</small>
        <small class="d-block mb-1"><strong>Rol:</strong> ${e.rol}</small>

        <a href="/rrhh/empleados/${e.id}" class="btn btn-secondary btn-sm w-100 mb-1">
            <i class="bi bi-person-bounding-box"></i>
            Ver perfil</a>
        <a href="/rrhh/empleados/${e.id}/edit" class="btn btn-primary btn-sm w-100 mb-1"> 
            <i class="bi bi-pencil-square"></i>
            Editar</a>

        <a href="/rrhh/empleados/${e.id}/legajos" 
            class="btn btn-warning btn-sm w-100 mb-1">
            <i class="bi bi-card-list"></i>
            Legajos
        </a>

        <!-- MENU DESPLEGABLE -->
        <div class="dropdown w-100 mb-1">
            <button class="btn btn-dark btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                Más opciones
            </button>

            <ul class="dropdown-menu w-100 p-2">

                <!-- DESCANSOS -->
                <li class="mb-1">
                    <a class="btn btn-secondary btn-sm w-100" 
                       href="/rrhh/empleados/${e.id}/descansos">
                        <i class="bi bi-moon-stars me-1"></i> Descansos
                    </a>
                </li>



                <!-- SUELDOS -->
                <li class="mb-1">
                    <a class="btn btn-success btn-sm w-100" 
                       href="/rrhh/empleados/${e.id}/sueldos">
                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-dollar" viewBox="0 0 16 16">
                          <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z"/>
                       </svg> Sueldos
                    </a>
                </li>   

                <!-- SANCIONES -->
                <li class="mb-1">
                    <a class="btn btn-warning btn-sm w-100" 
                       href="/rrhh/empleados/${e.id}/sanciones">
                        <i class="bi bi-exclamation-triangle me-1"></i> Sanciones
                    </a>
                </li>

                <!-- VACACIONES -->
                <li class="mb-1">
                    <a class="btn btn-primary btn-sm w-100" 
                       href="/rrhh/empleados/${e.id}/vacaciones">
                        <i class="bi bi-sun me-1"></i> Vacaciones
                    </a>
                </li>

                <!-- DAR DE BAJA O REACTIVAR -->
                <li>
                    ${
                        e.estado === "Inactivo"
                        ? `
                        <form action="/rrhh/empleados/${e.id}/reactivar" method="POST">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-success btn-sm w-100">
                                <i class="bi bi-person-check-fill"></i> Reactivar
                            </button>
                        </form>
                        `
                        : `
                        <button onclick="abrirModalEliminar(${e.id})"
                            class="btn btn-danger btn-sm w-100">
                            <i class="bi bi-person-x-fill"></i> Dar de baja
                        </button>
                        `
                    }
                </li>

            </ul>
        </div>

    </div>
</div>
`;


        cont.append(card);
    });
}


function abrirModalEliminar(id) {
    let url = "{{ route('rrhh.empleados.destroy', ':id') }}".replace(':id', id);
    document.getElementById('formEliminar').action = url;

    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}
</script>

<!-- Tabla invisible -->
<table id="tabla-oculta" class="d-none"></table>

@endpush
