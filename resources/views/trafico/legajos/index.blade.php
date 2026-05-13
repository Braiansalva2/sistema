@extends('layouts.trafico')

@section('title', 'Legajo Vehicular')

@section('content')

<div class="container py-4">

    {{-- TÍTULO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">📁 Legajo de Unidad: {{ $unidad->cod_interno }}</h3>

        <button class="btn btn-success fw-semibold px-4" data-bs-toggle="modal" data-bs-target="#modalAgregar">
            <i class="bi bi-plus-circle"></i> Agregar Documento
        </button>
    </div>

    {{-- ALERTAS DE ERROR --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>No se pudo guardar el documento:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- INFORMACIÓN DE LA UNIDAD --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Marca:</strong> {{ $unidad->marca->nombre }}</div>
                <div class="col-md-3"><strong>Modelo:</strong> {{ $unidad->modelo->nombre }}</div>
                <div class="col-md-3"><strong>Dominio:</strong> {{ $unidad->dominio ?? '-' }}</div>
                <div class="col-md-3"><strong>Año:</strong> {{ $unidad->anio }}</div>
            </div>
        </div>
    </div>

    {{-- BUSCADOR --}}
    <div class="input-group mb-4">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" id="buscador" class="form-control" placeholder="Buscar documento...">
    </div>

    {{-- LISTA DE DOCUMENTOS --}}
    <div id="contenedorDocumentos" class="row g-4">

        @foreach ($documentos as $doc)

        @php
            $vence = $doc->fecha_vencimiento;
            $diasRestantes = $vence ? now()->diffInDays($vence, false) : null;

            $alerta = '';
            if ($diasRestantes !== null) {
                if ($diasRestantes <= 0) $alerta = 'danger';
                elseif ($diasRestantes <= 30) $alerta = 'warning';
            }

            $extension = strtolower(pathinfo($doc->archivo, PATHINFO_EXTENSION));
            $isImage = in_array($extension, ['jpg','jpeg','png','webp']);
        @endphp

        <div class="col-md-4 documento-card">
            <div class="card shadow-sm border-0 h-100">

                {{-- MINIATURA --}}
                <div class="text-center p-3" style="background:#fafafa;">
                    @if($isImage)
                        <img src="{{ asset('storage/' . $doc->archivo) }}"
                             class="img-thumbnail"
                             style="max-height:150px; object-fit:cover;">
                    @else
                        <i class="bi bi-file-earmark-pdf text-danger" style="font-size:70px;"></i>
                    @endif
                </div>

                <div class="card-body">

                    <h5 class="fw-bold">{{ $doc->tipo_documento }}</h5>

                    @if($doc->fecha_vencimiento)
                        <p class="mb-1"><strong>Vence:</strong> {{ $doc->fecha_vencimiento }}</p>

                        @if($alerta == 'danger')
                            <span class="badge bg-danger">VENCIDO</span>
                        @elseif($alerta == 'warning')
                            <span class="badge bg-warning text-dark">PRÓXIMO A VENCER</span>
                        @endif
                    @endif

                    {{-- BOTONES --}}
                    <div class="mt-3 d-flex justify-content-between">

                        {{-- VER --}}
                        <button class="btn btn-primary btn-sm verDocumento"
                            data-file="{{ asset('storage/' . $doc->archivo) }}"
                            data-extension="{{ $extension }}">
                            <i class="bi bi-eye"> Ver</i>
                        </button>

                        {{-- DESCARGAR --}}
                        <a href="{{ asset('storage/' . $doc->archivo) }}" download
                           class="btn btn-secondary btn-sm">
                            <i class="bi bi-download"> Descarga</i>
                        </a>
                        {{-- EDITAR --}}
<button class="btn btn-warning btn-sm editarDoc"
    data-id="{{ $doc->id }}"
    data-tipo="{{ $doc->tipo_documento }}"
    data-descripcion="{{ $doc->descripcion }}"
    data-emision="{{ $doc->fecha_emision }}"
    data-vencimiento="{{ $doc->fecha_vencimiento }}"
    data-bs-toggle="modal"
    data-bs-target="#modalEditar">
    <i class="bi bi-pencil-square">Editar</i>
</button>

                        {{-- ELIMINAR --}}
                        <button class="btn btn-danger btn-sm eliminarDoc"
                            data-id="{{ $doc->id }}"
                            data-nombre="{{ $doc->tipo_documento }}"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEliminar">
                            <i class="bi bi-trash"> Eliminar</i>
                        </button>

                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    {{-- PAGINACIÓN --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $documentos->links() }}
    </div>

</div>

{{-- MODAL DE VISTA PREVIA --}}
<div class="modal fade" id="modalVerDocumento" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Vista Previa del Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center" id="visorDocumento"></div>

        </div>
    </div>
</div>

{{-- MODAL AGREGAR --}}
<div class="modal fade" id="modalAgregar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Agregar Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('trafico.legajo.store', $unidad->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <label class="fw-semibold">Tipo de Documento</label>
                    <input type="text" name="tipo_documento" class="form-control mb-3" required>

                    <label class="fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control mb-3"></textarea>

                    <label class="fw-semibold">Fecha de Emisión</label>
                    <input type="date" name="fecha_emision" class="form-control mb-3">

                    <label class="fw-semibold">Fecha de Vencimiento</label>
                    <input type="date" name="fecha_vencimiento" class="form-control mb-3">

                    <label class="fw-semibold">Archivo (PDF o Foto)</label>
                    <input type="file" name="archivo"
                        accept="image/*,application/pdf"
                        capture="environment"
                        class="form-control" required>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Eliminar Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Seguro que deseas eliminar <strong id="nombreDoc"></strong>?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <form id="formEliminar" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Eliminar</button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- MODAL EDITAR DOCUMENTO --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title">Editar Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditar" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <label class="fw-semibold">Tipo de Documento</label>
                    <input type="text" id="edit_tipo_documento" name="tipo_documento" class="form-control mb-3" required>

                    <label class="fw-semibold">Descripción</label>
                    <textarea id="edit_descripcion" name="descripcion" class="form-control mb-3"></textarea>

                    <label class="fw-semibold">Fecha de Emisión</label>
                    <input type="date" id="edit_fecha_emision" name="fecha_emision" class="form-control mb-3">

                    <label class="fw-semibold">Fecha de Vencimiento</label>
                    <input type="date" id="edit_fecha_vencimiento" name="fecha_vencimiento" class="form-control mb-3">

                    <label class="fw-semibold">Archivo (opcional)</label>
                    <input type="file" name="archivo"
                        accept="image/*,application/pdf"
                        class="form-control mb-3">

                    <small class="text-muted">
                        Si no seleccionas un archivo nuevo, se mantendrá el existente.
                    </small>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-warning">Actualizar</button>
                </div>
            </form>

        </div>
    </div>
</div>


@endsection


@push('scripts')
<script>
/* ===========================================================
   BUSCADOR EN VIVO
   =========================================================== */
document.getElementById('buscador').addEventListener('keyup', function () {
    let filtro = this.value.toLowerCase();

    document.querySelectorAll('.documento-card').forEach(function (card) {
        let texto = card.innerText.toLowerCase();

        card.style.display = texto.includes(filtro) ? '' : 'none';
    });
});


/* ===========================================================
   VISTA PREVIA EN MODAL
   =========================================================== */
document.querySelectorAll('.verDocumento').forEach(btn => {
    btn.addEventListener('click', function () {

        let file = this.dataset.file;
        let ext  = this.dataset.extension.toLowerCase();
        let visor = document.getElementById('visorDocumento');

        visor.innerHTML = "";

        if (['jpg','jpeg','png','webp'].includes(ext)) {
            visor.innerHTML = `<img src="${file}" class="img-fluid rounded shadow">`;
        } else {
            visor.innerHTML = `
                <iframe src="${file}" width="100%" height="600px" class="border-0 rounded"></iframe>
            `;
        }

        new bootstrap.Modal(document.getElementById('modalVerDocumento')).show();
    });
});


/* ===========================================================
   ELIMINAR DOCUMENTO
   =========================================================== */
document.querySelectorAll('.eliminarDoc').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('nombreDoc').innerText = this.dataset.nombre;
        document.getElementById('formEliminar').action =
            "/trafico/legajo/" + this.dataset.id + "/delete";
    });
});


/* ===========================================================
   EDITAR DOCUMENTO
   =========================================================== */
document.querySelectorAll('.editarDoc').forEach(btn => {
    btn.addEventListener('click', function () {

        // Rellenar campos del modal
        document.getElementById('edit_tipo_documento').value = this.dataset.tipo;
        document.getElementById('edit_descripcion').value = this.dataset.descripcion ?? "";
        document.getElementById('edit_fecha_emision').value = this.dataset.emision ?? "";
        document.getElementById('edit_fecha_vencimiento').value = this.dataset.vencimiento ?? "";

        // Configurar ruta de actualización
        document.getElementById('formEditar').action =
            "/trafico/legajo/" + this.dataset.id + "/update";
    });
});

</script>
@endpush
