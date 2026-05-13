@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color:#a44a20;">
            Legajos de: {{ $empleado->apellido }}, {{ $empleado->nombre }}
        </h3>

        <a href="{{ route('rrhh.empleados.legajos.create', $empleado->id) }}" 
           class="btn text-white" style="background-color:#a44a20;">
            <i class="bi bi-plus-lg"></i> Nuevo legajo
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- TABLA (DESKTOP) -->
    <div class="card shadow border-0 d-none d-lg-block">
        <div class="card-body">

            <table id="tablaLegajos" class="table table-striped table-hover w-100">
                <thead class="table-dark">
                    <tr>
                        <th>Archivo</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($legajos as $legajo)
                        @php
                            $url = Storage::url($legajo->archivo_path);
                            $isPdf = Str::endsWith($legajo->archivo_path, '.pdf');
                            $estado = $legajo->estado_calculado ?? $legajo->estado;

                            $badge =
                                $estado == 'vigente' ? 'success' :
                                ($estado == 'por_vencer' ? 'warning' : 'danger');
                        @endphp

                        <tr>
                            <td>
                                <button class="btn btn-outline-primary btn-sm"
                                        onclick="verArchivoModal('{{ $url }}', {{ $isPdf ? 'true' : 'false' }})">
                                    @if($isPdf)
                                        <i class="bi bi-file-earmark-pdf"></i> PDF
                                    @else
                                        <img src="{{ $url }}" style="width:50px;height:50px;object-fit:cover;border-radius:5px;">
                                    @endif
                                </button>
                            </td>

                            <td>{{ $legajo->nombre_archivo }}</td>
                            <td>{{ $legajo->descripcion ?? '-' }}</td>
                            <td>{{ $legajo->fecha_inicio ?? '-' }}</td>
                            <td>{{ $legajo->fecha_fin ?? '-' }}</td>

                            <td>
                                <span class="badge bg-{{ $badge }} px-3 py-2 text-uppercase fw-semibold">
                                    {{ $estado }}
                                </span>
                            </td>

                            <td class="text-center">

                                <button class="btn btn-secondary btn-sm mb-1"
                                        onclick="verArchivoModal('{{ $url }}', {{ $isPdf ? 'true' : 'false' }})">
                                    Ver
                                </button>

                                <a href="{{ route('rrhh.empleados.legajos.edit', [$empleado->id, $legajo->id]) }}"
                                   class="btn btn-primary btn-sm mb-1">
                                    Editar
                                </a>

                                <button class="btn btn-danger btn-sm"
                                        onclick="abrirModalEliminarLegajo('{{ route('rrhh.empleados.legajos.destroy', [$empleado->id, $legajo->id]) }}')">
                                    Eliminar
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>


    <!-- CARDS (MÓVIL) -->
    <div id="cards-container" class="d-lg-none"></div>


    <!-- BOTÓN VOLVER -->
    <div class="mt-3">
        <a href="{{ route('rrhh.empleados.index') }}" class="btn btn-secondary">Volver</a>
    </div>

</div>


<!-- =======================
     MODAL VISTA PREVIA
======================= -->
<div class="modal fade" id="modalArchivo" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Vista previa del archivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center" id="contenidoArchivo"></div>

        </div>
    </div>
</div>

<!-- =======================
     MODAL ELIMINACIÓN
======================= -->
<div class="modal fade" id="modalEliminarLegajo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Eliminar documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este legajo?
                <br>
                <small class="text-muted">Esta acción no se puede deshacer.</small>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <form id="formEliminarLegajo" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">Sí, eliminar</button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection




@push('scripts')
<!-- LIBRERÍAS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


<script>
/* ============================
   PREVIEW ARCHIVO EN MODAL
============================ */
function verArchivoModal(url, esPdf) {

    let html = esPdf
        ? `<iframe src="${url}" style="width:100%; height:80vh;" frameborder="0"></iframe>`
        : `<img src="${url}" class="img-fluid" style="max-height:80vh;object-fit:contain;">`;

    document.getElementById('contenidoArchivo').innerHTML = html;

    new bootstrap.Modal(document.getElementById('modalArchivo')).show();
}



/* ============================
   DATATABLES + CARDS
============================ */
$(document).ready(function() {

    let table = $('#tablaLegajos').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
        pageLength: 10,
        ordering: true,
        columnDefs: [
            { targets: 0, orderable:false },
            { targets: 6, orderable:false }
        ]
    });

    // Render de cards
    function renderCards() {
        const container = $('#cards-container');
        container.empty();

        table.rows({ search: 'applied' }).every(function () {
            const row = this.data();
            if (!row) return;

            const card = `
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="fw-bold">${row[1]}</h5>
                        <p class="text-muted">${row[2]}</p>

                        <p><strong>Inicio:</strong> ${row[3]}</p>
                        <p><strong>Fin:</strong> ${row[4]}</p>
                        <p><strong>Estado:</strong> ${row[5]}</p>

                        <div class="d-grid gap-2">
                            ${row[6]}
                        </div>
                    </div>
                </div>
            `;

            container.append(card);
        });
    }

    renderCards();

    table.on('draw', function () {
        renderCards();
    });

});



/* ============================
   MODAL ELIMINAR LEGAJO
============================ */
function abrirModalEliminarLegajo(action) {
    document.getElementById('formEliminarLegajo').action = action;

    new bootstrap.Modal(document.getElementById('modalEliminarLegajo')).show();
}
</script>

@endpush
