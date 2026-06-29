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

    <!--  Cards dinámicas -->
<!-- Cards dinámicas -->
<div id="cards-container" class="row g-4"></div>

<div id="paginacion-empleados" class="d-flex justify-content-center mt-4"></div>

<div id="resumen-empleados" class="text-center text-muted mt-2"></div>

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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const buscador = document.getElementById('buscador');
    const cantidad = document.getElementById('cantidad');
    const cardsContainer = document.getElementById('cards-container');
    const paginacion = document.getElementById('paginacion-empleados');
    const resumen = document.getElementById('resumen-empleados');

    let temporizadorBusqueda;

    function escaparHtml(valor) {
        const div = document.createElement('div');
        div.textContent = valor ?? '';
        return div.innerHTML;
    }

    async function cargarEmpleados(pagina = 1) {
        cardsContainer.innerHTML = `
            <div class="col-12 text-center text-muted py-5">
                Cargando empleados...
            </div>
        `;

        const parametros = new URLSearchParams({
            page: pagina,
            por_pagina: cantidad.value,
            buscar: buscador.value.trim()
        });

        const respuesta = await fetch("{{ route('rrhh.empleados.json') }}?" + parametros.toString(), {
            headers: {
                'Accept': 'application/json'
            }
        });

        const datos = await respuesta.json();

        renderCards(datos.data);
        renderPaginacion(datos);
        renderResumen(datos);
    }

    function renderCards(empleados) {
        cardsContainer.innerHTML = '';

        if (!empleados || empleados.length === 0) {
            cardsContainer.innerHTML = `
                <div class="col-12 text-center text-muted py-5">
                    No se encontraron empleados.
                </div>
            `;
            return;
        }

        empleados.forEach(function (e) {
            const claseEstado = e.estado === 'Inactivo'
                ? 'opacity-50 border border-dark'
                : '';

            const botonEstado = e.estado === 'Inactivo'
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
                    <button type="button" onclick="abrirModalEliminar(${e.id})"
                        class="btn btn-danger btn-sm w-100">
                        <i class="bi bi-person-x-fill"></i> Dar de baja
                    </button>
                `;

            cardsContainer.insertAdjacentHTML('beforeend', `
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="empleado-card shadow-sm p-3 h-100 text-center ${claseEstado}">

                        <img src="${escaparHtml(e.foto)}" class="empleado-foto mb-3">

                        <h5 class="fw-bold text-dark">
                            ${escaparHtml(e.apellido)}, ${escaparHtml(e.nombre)}
                        </h5>

                        <small class="d-block mb-1"><strong>DNI:</strong> ${escaparHtml(e.dni)}</small>
                        <small class="d-block mb-1"><strong>Rol:</strong> ${escaparHtml(e.rol)}</small>

                        <a href="/rrhh/empleados/${e.id}" class="btn btn-secondary btn-sm w-100 mb-1">
                            <i class="bi bi-person-bounding-box"></i> Ver perfil
                        </a>

                        <a href="/rrhh/empleados/${e.id}/edit" class="btn btn-primary btn-sm w-100 mb-1">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>

                        <a href="/rrhh/empleados/${e.id}/legajos" class="btn btn-warning btn-sm w-100 mb-1">
                            <i class="bi bi-card-list"></i> Legajos
                        </a>

                        <div class="dropdown w-100 mb-1">
                            <button class="btn btn-dark btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                Más opciones
                            </button>

                            <ul class="dropdown-menu w-100 p-2">
                                <li class="mb-1">
                                    <a class="btn btn-secondary btn-sm w-100" href="/rrhh/empleados/${e.id}/descansos">
                                        <i class="bi bi-moon-stars me-1"></i> Descansos
                                    </a>
                                </li>

                                <li class="mb-1">
                                    <a class="btn btn-success btn-sm w-100" href="/rrhh/empleados/${e.id}/sueldos">
                                        <i class="bi bi-currency-dollar"></i> Sueldos
                                    </a>
                                </li>

                                <li class="mb-1">
                                    <a class="btn btn-warning btn-sm w-100" href="/rrhh/empleados/${e.id}/sanciones">
                                        <i class="bi bi-exclamation-triangle me-1"></i> Sanciones
                                    </a>
                                </li>

                                <li class="mb-1">
                                    <a class="btn btn-primary btn-sm w-100" href="/rrhh/empleados/${e.id}/vacaciones">
                                        <i class="bi bi-sun me-1"></i> Vacaciones
                                    </a>
                                </li>

                                <li>${botonEstado}</li>
                            </ul>
                        </div>

                    </div>
                </div>
            `);
        });
    }

    function renderPaginacion(datos) {
        paginacion.innerHTML = '';

        if (datos.last_page <= 1) {
            return;
        }

        paginacion.innerHTML = `
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item ${datos.current_page === 1 ? 'disabled' : ''}">
                        <button class="page-link" type="button" data-page="${datos.current_page - 1}">
                            Anterior
                        </button>
                    </li>

                    <li class="page-item disabled">
                        <span class="page-link">
                            Página ${datos.current_page} de ${datos.last_page}
                        </span>
                    </li>

                    <li class="page-item ${datos.current_page === datos.last_page ? 'disabled' : ''}">
                        <button class="page-link" type="button" data-page="${datos.current_page + 1}">
                            Siguiente
                        </button>
                    </li>
                </ul>
            </nav>
        `;

        paginacion.querySelectorAll('button[data-page]').forEach(function (boton) {
            boton.addEventListener('click', function () {
                cargarEmpleados(this.dataset.page);
            });
        });
    }

    function renderResumen(datos) {
        resumen.textContent = datos.total > 0
            ? `Mostrando ${datos.from} a ${datos.to} de ${datos.total} empleados`
            : '';
    }

    buscador.addEventListener('input', function () {
        clearTimeout(temporizadorBusqueda);

        temporizadorBusqueda = setTimeout(function () {
            cargarEmpleados(1);
        }, 350);
    });

    cantidad.addEventListener('change', function () {
        cargarEmpleados(1);
    });

    cargarEmpleados(1);
});

function abrirModalEliminar(id) {
    let url = "{{ route('rrhh.empleados.destroy', ':id') }}".replace(':id', id);
    document.getElementById('formEliminar').action = url;

    new bootstrap.Modal(document.getElementById('modalEliminar')).show();
}
</script>
@endpush
