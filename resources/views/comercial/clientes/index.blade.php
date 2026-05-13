@extends('layouts.comercial')

@section('title', 'Empresas')

@section('content')

<div class="container-fluid py-3">

    {{-- TÍTULO + BOTÓN --}}
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold text-dark mb-0">Empresas</h2>
            <small class="text-muted">Gestión comercial de clientes y proveedores</small>
        </div>
        <div class="col text-end">
            <a href="{{ route('comercial.clientes.create') }}"
               class="btn btn-success fw-semibold btn-sm">
                Nueva empresa
            </a>
        </div>
    </div>

    {{-- MENSAJE SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLA --}}
    <div class="card shadow-sm border-0">
        <div class="card-header fw-semibold bg-light">
            Listado de empresas
        </div>

        <div class="card-body p-0">
            <table id="tablaEmpresas" class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th style="width:80px">Logo</th>
                        <th>Razón Social</th>
                        <th>CUIT</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($empresas as $empresa)
                    <tr>
                        <td class="text-center">
                            @if($empresa->logo)
                                <img src="{{ asset('storage/'.$empresa->logo) }}"
                                     style="width:45px; height:45px; object-fit:contain;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="fw-semibold">
                            {{ $empresa->razon_social }}
                        </td>

                        <td>{{ $empresa->cuit }}</td>

                        <td>
                            @if($empresa->estado === 'activa')
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </td>

                        <td class="text-end">
                            {{-- VER EN MODAL --}}
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    onclick="verEmpresa({{ $empresa->id }})">
                               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                               </svg>
                            </button>

                            <a href="{{ route('comercial.clientes.edit', $empresa->id) }}"
                               class="btn btn-sm btn-outline-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                            </a>
                            <form method="POST"
      action="{{ route('comercial.clientes.destroy', $empresa) }}"
      class="d-inline">

    @csrf
    @method('DELETE')

    <button type="button"
            class="btn btn-sm btn-outline-danger"
            onclick="confirmarEliminacionEmpresa(this)">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
             <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
        </svg>
    </button>
</form>

                        </td>
                    </tr>
                @empty
                    {{-- <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            No hay empresas cargadas
                        </td>
                    </tr> --}}
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ================= MODAL SHOW EMPRESA ================= --}}
<div class="modal fade" id="modalEmpresa" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Detalle de empresa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="contenidoEmpresa">
        <div class="text-center text-muted py-5">
            Cargando información...
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
            Cerrar
        </button>
      </div>

    </div>
  </div>
</div>
 
<div class="modal fade" id="modalEliminarEmpresa" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-danger text-white">
        <h6 class="modal-title">Eliminar empresa</h6>
        <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="mb-2">
            ⚠️ Esta acción eliminará:
        </p>
        <ul class="mb-0">
            <li>La empresa</li>
            <li>Todos los referentes</li>
            <li>Todos los documentos</li>
        </ul>
        <p class="mt-2 fw-semibold text-danger">
            Esta acción no se puede deshacer.
        </p>
      </div>

      <div class="modal-footer">
        <button type="button"
                class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">
            Cancelar
        </button>

        <button type="button"
                class="btn btn-danger btn-sm"
                id="btnConfirmarEliminarEmpresa">
            Sí, eliminar
        </button>
      </div>

    </div>
  </div>
</div>

{{-- ================= JS ================= --}}
<script>
function verEmpresa(id) {

    const modal = new bootstrap.Modal(
        document.getElementById('modalEmpresa')
    );

    document.getElementById('contenidoEmpresa').innerHTML =
        '<div class="text-center text-muted py-5">Cargando...</div>';

    fetch("{{ url('/comercial/clientes') }}/" + id, {
        credentials: 'same-origin' 
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById('contenidoEmpresa').innerHTML = html;
    })
    .catch(() => {
        document.getElementById('contenidoEmpresa').innerHTML =
            '<div class="alert alert-danger">Error al cargar la empresa</div>';
    });

    modal.show();
}
</script>

<script>
window.verDocumento = function (url, nombre) {
    const iframe = document.getElementById('iframeDocumento');
    const titulo = document.getElementById('tituloDocumento');
    const contenedor = document.getElementById('contenedorDocumento');

    if (!iframe || !contenedor) {
        console.warn('Contenedor de documento no encontrado');
        return;
    }

    iframe.src = url;
    titulo.innerText = nombre || 'Documento';
    contenedor.style.display = 'block';
};

window.cerrarDocumento = function () {
    const iframe = document.getElementById('iframeDocumento');
    const contenedor = document.getElementById('contenedorDocumento');

    if (iframe) iframe.src = '';
    if (contenedor) contenedor.style.display = 'none';
};
</script>
@push('scripts')
<script>
$(document).ready(function () {
    $('#tablaEmpresas').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        },
        pageLength: 10,
        lengthChange: false,
        ordering: true,
        searching: true,
        info: true, 
        columnDefs: [
            { orderable: false, targets: [0, 4] }
        ]
    });
});
</script>
<script>
let formEliminarEmpresa = null;

function confirmarEliminacionEmpresa(boton) {
    formEliminarEmpresa = boton.closest('form');

    const modal = new bootstrap.Modal(
        document.getElementById('modalEliminarEmpresa')
    );
    modal.show();
}

document.getElementById('btnConfirmarEliminarEmpresa')
    .addEventListener('click', function () {
        if (formEliminarEmpresa) {
            formEliminarEmpresa.submit();
        }
    });
</script>

@endpush

@endsection

