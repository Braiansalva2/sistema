@extends('layouts.trafico')

@section('title', 'Panel de Tráfico')

@section('content')

<div class="container-fluid py-3">

    {{-- TÍTULO + BOTÓN --}}
    <div class="row mb-4 align-items-center g-2">
        <div class="col-12 col-md-6">
            <h2 class="fw-bold text-dark mb-0">🚦 Panel de Tráfico</h2>
        </div>

        <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
            <a href="{{ route('trafico.unidades.create') }}" 
               class="btn btn-success fw-semibold px-4 py-2 d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i> Nueva Unidad
            </a>
        </div>
    </div>

    <hr class="my-4 my-md-5">

    {{-- TABLA --}}
    <div class="card shadow-sm border-0">
        <div class="card-header fw-bold" style="background:#e37c45; color:white;">
            Últimas unidades cargadas
        </div>
<div class="d-flex gap-2 mb-3">
 <button id="exportCSV" class="btn btn-success">
    📊 Exportar Excel
</button>

<button id="exportPDF" class="btn btn-danger">
    📄 Exportar PDF
</button>

</div>


        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0" id="tabla-unidades">
                    <thead class="table-dark">
                        <tr>
                            <th>Cod. Interno</th>
                             <th>vehiculo</th>  
                             <th>Dominio</th>
                            {{-- Visible solo en PC --}}
                            <th class="d-none d-md-table-cell">Año</th>
                            <th class="d-none d-md-table-cell">Estado</th>
                             <th class="d-none d-md-table-cell">VTV</th>
                              <th class="d-none d-md-table-cell">Poliza</th>
                            {{-- ACCIONES visible SIEMPRE --}}
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
        
    </div>

<br><br><br>
</div>

{{-- MODAL DETALLE --}}
<div class="modal fade" id="modalUnidad" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg">

            <div class="modal-header" style="background:#e37c45; color:white;">
                <h5 class="modal-title fw-bold">Información Completa de la Unidad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

           <div class="modal-body">

    <div class="row g-3">

        <div class="col-md-4">
            <strong>Código Interno:</strong>
            <p id="m_cod" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Dominio:</strong>
            <p id="m_dominio" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Color:</strong>
            <p id="m_color" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Marca:</strong>
            <p id="m_marca" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Modelo:</strong>
            <p id="m_modelo" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Tipo Vehículo:</strong>
            <p id="m_tipo" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Año:</strong>
            <p id="m_anio" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Kilometraje:</strong>
            <p id="m_km" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Estado:</strong>
            <p id="m_estado" class="text-muted"></p>
        </div>

        <hr>

        <div class="col-md-4">
            <strong>Origen:</strong>
            <p id="m_origen" class="text-muted"></p>
        </div>

        <div class="col-md-8">
            <strong>Empresa Tercerizada:</strong>
            <p id="m_empresa" class="text-muted"></p>
        </div>

        <hr>

        <div class="col-md-4">
            <strong>Capacidad (kg):</strong>
            <p id="m_capacidad" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Largo (m):</strong>
            <p id="m_largo" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Ancho (m):</strong>
            <p id="m_ancho" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Alto (m):</strong>
            <p id="m_alto" class="text-muted"></p>
        </div>

        <hr>

        <div class="col-md-4">
            <strong>Fecha Alta:</strong>
            <p id="m_alta" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Fecha Baja:</strong>
            <p id="m_baja" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>VTV:</strong>
            <p id="m_vtv" class="text-muted"></p>
        </div>

        <div class="col-md-4">
            <strong>Póliza:</strong>
            <p id="m_poliza" class="text-muted"></p>
        </div>

        <div class="col-12">
            <strong>Observaciones:</strong>
            <p id="m_observ" class="text-muted"></p>
        </div>

    </div>

</div>


            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

{{-- MODAL ELIMINACIÓN --}}
<div class="modal fade" id="modalEliminarUnidad" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Estás seguro que deseas eliminar la unidad 
                <strong id="unidadAEliminar"></strong>?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <form id="formEliminarUnidad" method="POST">
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

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Responsive -->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    if ($.fn.DataTable.isDataTable('#tabla-unidades')) {
        $('#tabla-unidades').DataTable().destroy();
    }

    let tabla = $('#tabla-unidades').DataTable({

        responsive: true,
        autoWidth: false,
        pageLength: 5,

        ajax: "{{ route('trafico.unidades.index') }}",

        columns: [
    { data: 'cod_interno' },
    { data: 'tipo_vehiculo.nombre' }, 
    { data: 'dominio' },
   

    { data: 'anio', className: "d-none d-md-table-cell" },
    { data: 'estado', className: "d-none d-md-table-cell" },

    // 🔥 NUEVOS CAMPOS
    {
        data: 'vtv_vto',
        className: "d-none d-md-table-cell",
        render: function(data){
            return data ? data : '-';
        }
    },
    {
        data: 'poliza_vto',
        className: "d-none d-md-table-cell",
        render: function(data){
            return data ? data : '-';
        }
    },

    {
        data: null,
        className: "text-center",
        orderable: false,
        render: function(data){

            return `
                <div class="d-flex gap-2 justify-content-center flex-wrap">

                   <button class="btn btn-sm btn-primary verUnidad"
    data-cod="${data.cod_interno}"
    data-dominio="${data.dominio ?? '-'}"
    data-color="${data.color ?? '-'}"
    data-marca="${data.marca?.nombre ?? '-'}"
    data-modelo="${data.modelo?.nombre ?? '-'}"
    data-tipo="${data.tipo_vehiculo?.nombre ?? '-'}"
    data-anio="${data.anio ?? '-'}"
    data-km="${data.km_actual ?? '-'}"
    data-estado="${data.estado ?? '-'}"

    data-origen="${data.origen ?? '-'}"
    data-empresa="${data.empresa_tercerizada?.nombre ?? 'No aplica'}"

    data-capacidad="${data.capacidad_kg ?? '-'}"
    data-largo="${data.largo_total ?? '-'}"
    data-ancho="${data.ancho ?? '-'}"
    data-alto="${data.alto ?? '-'}"

    data-alta="${data.fecha_alta ?? '-'}"
    data-baja="${data.fecha_baja ?? '-'}"
    data-vtv="${data.vtv_vto ?? '-'}"
    data-poliza="${data.poliza_vto ?? '-'}"
    data-observ="${data.observaciones ?? '-'}"
>

                        <i class="bi bi-eye-fill"></i>
                    </button>

                    <a href="/trafico/unidades/${data.id}/edit"
                        class="btn btn-sm btn-warning text-white"
                        title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <a href="/trafico/unidades/${data.id}/legajo"
                        class="btn btn-sm btn-info text-white"
                        title="Legajo">
                        <i class="bi bi-folder2-open"></i>
                    </a>

                    <button class="btn btn-sm btn-danger eliminarUnidad"
                        title="Eliminar"
                        data-id="${data.id}"
                        data-nombre="${data.cod_interno}">
                        <i class="bi bi-trash3-fill"></i>
                    </button>

                </div>
            `;
        }
    }
],


        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
    });

});


// 🔎 ABRIR MODAL DETALLE
$('#tabla-unidades').on('click', '.verUnidad', function () {

    $('#m_cod').text($(this).data('cod'));
    $('#m_dominio').text($(this).data('dominio'));
    $('#m_color').text($(this).data('color'));
    $('#m_marca').text($(this).data('marca'));
    $('#m_modelo').text($(this).data('modelo'));
    $('#m_tipo').text($(this).data('tipo'));
    $('#m_anio').text($(this).data('anio'));
    $('#m_km').text($(this).data('km'));
    $('#m_estado').text($(this).data('estado'));

    $('#m_origen').text($(this).data('origen'));
    $('#m_empresa').text($(this).data('empresa'));

    $('#m_capacidad').text($(this).data('capacidad'));
    $('#m_largo').text($(this).data('largo'));
    $('#m_ancho').text($(this).data('ancho'));
    $('#m_alto').text($(this).data('alto'));

    $('#m_alta').text($(this).data('alta'));
    $('#m_baja').text($(this).data('baja'));
    $('#m_vtv').text($(this).data('vtv'));
    $('#m_poliza').text($(this).data('poliza'));
    $('#m_observ').text($(this).data('observ'));

    $('#modalUnidad').modal('show');
});


// ❌ ABRIR MODAL ELIMINAR
$('#tabla-unidades').on('click', '.eliminarUnidad', function () {

    let id = $(this).data('id');
    let nombre = $(this).data('nombre');

    $('#unidadAEliminar').text(nombre);

    $('#formEliminarUnidad').attr('action', "/trafico/unidades/" + id);

    let modal = new bootstrap.Modal(document.getElementById('modalEliminarUnidad'));
    modal.show();
});
  



  function getFiltrosDataTable() {
    let table = $('#tabla-unidades').DataTable();

    return {
        search: table.search(), // texto del buscador
    };
}


$('#exportCSV').on('click', function () {

    let filtros = getFiltrosDataTable();

    let url = "{{ route('trafico.unidades.export.csv') }}";

    window.location.href = url + '?search=' + encodeURIComponent(filtros.search);
     
}); 

$('#exportPDF').on('click', function () {

    let filtros = getFiltrosDataTable();

    let url = "{{ route('trafico.unidades.export.pdf') }}";

    window.location.href = url + '?search=' + encodeURIComponent(filtros.search);

    
});

</script>

@endpush
