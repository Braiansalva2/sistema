@extends('layouts.comercial')

@section('title', 'Tramos')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Tramos</h2>
            <small class="text-muted">Gestión comercial de rutas</small>
        </div>

        <a href="{{ route('comercial.tramos.create') }}"
           class="btn btn-primary btn-sm">
            + Nuevo tramo
        </a>
    </div>

    {{-- ALERTA --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTROS --}}
    <div class="card mb-3 shadow-sm border-0">
        <div class="card-body">

            <div class="row g-2">

                <div class="col-md-3">
                    <input type="text"
                           id="buscador"
                           class="form-control"
                           placeholder="🔍 Buscar tramo...">
                </div>

                <div class="col-md-2">
                    <select id="filtroEstado" class="form-select">
                        <option value="">Estado</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
        
                <div class="col-md-2">
                    <select id="filtroServicio" class="form-select">
                        <option value="">Servicio</option>
                        @foreach($tiposServicio ?? [] as $tipo)
                            <option value="{{ $tipo->nombre }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select id="filtroVehiculo" class="form-select">
                        <option value="">Vehículo</option>
                        @foreach($tiposVehiculo ?? [] as $tipo)
                            <option value="{{ $tipo->nombre }}">{{ $tipo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

        </div>
    </div>

    {{-- TABLA --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <div class="table-responsive">

                <table id="tablaTramos"
                       class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Vehículo</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                            <th>Tarifas</th>
                            
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>

                    <tb                                                                                     ody>
                        @forelse($tramos as $tramo)
                            <tr>
                                <td>{{ $tramo->codigo ?? '-' }}</td>

                                <td class="fw-semibold">
                                    {{ $tramo->nombre }}
                                </td>

                                <td>
                                    {{ $tramo->origen->nombre ?? '-' }}
                                </td>

                                <td>
                                    {{ $tramo->destino->nombre ?? '-' }}
                                </td>

                                <td>
                                    {{ $tramo->tipoVehiculo->nombre ?? '-' }}
                                </td>

                                <td>
                                    {{ $tramo->tipoServicio->nombre ?? '-' }}
                                </td>

                                <td>
                                    {{ ucfirst($tramo->estado) }}
                                </td>
                               
                                    <td>
                                        @if($tramo->tarifas->count())
                                            
                                            <button class="btn btn-sm btn-dark toggle-precio">
                                                👁️
                                            </button>

                                            <div class="precios d-none mt-2">
                                                @foreach($tramo->tarifas as $tarifa)
                                                    <div>
                                                        <strong>{{ strtoupper($tarifa->tipo) }}:</strong>
                                                        ${{ number_format($tarifa->precio, 0, ',', '.') }}
                                                    </div>
                                                @endforeach
                                            </div>

                                        @else
                                            -
                                        @endif
                                    </td>
                                

                                <td class="text-center">
                                    <a href="{{ route('comercial.tramos.edit', $tramo) }}"
                                       class="btn btn-sm btn-outline-warning">
                                        ✏️
                                    </a>
                                </td>
                            </tr>
                        @empty
                            {{-- <tr>
                                <td colspan="8" class="text-center">
                                    No hay tramos registrados
                                </td>
                            </tr> --}}
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@endsection

{{-- ================= LIBRERIAS + JS ================= --}}
@section('scripts')

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Botones -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>

$(document).ready(function () {

    let table = $('#tablaTramos').DataTable({
        pageLength: 10,
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],

        dom: 'Bfrtip',

        buttons: [
            {
                extend: 'excel',
                text: '📊 Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '📄 PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '🖨️ Imprimir',
                className: 'btn btn-secondary btn-sm'
            }
        ],

        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
            emptyTable: "No hay tramos registrados"
        }
    });

    // 🔍 BUSCADOR
    $('#buscador').on('keyup', function () {
        table.search(this.value).draw();
    });

    // 📌 FILTRO ESTADO
    $('#filtroEstado').on('change', function () {
        table.column(6).search(this.value).draw();
    });

    // 📌 FILTRO SERVICIO
    $('#filtroServicio').on('change', function () {
        table.column(5).search(this.value).draw();
    });

    // 📌 FILTRO VEHICULO
    $('#filtroVehiculo').on('change', function () {
        table.column(4).search(this.value).draw();
    });

});




</script>

@endsection

@push('scripts')
<script>
$(document).on('click', '.toggle-precio', function() {
    let contenedor = $(this).closest('td').find('.precios');
    contenedor.toggleClass('d-none');
});
</script>
@endpush