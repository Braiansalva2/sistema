@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: #a44a20;">
            <i class="bi bi-people-fill me-2"></i>Listado de empleados
        </h2>

        <a href="{{ route('empleados.create') }}" class="btn text-white" style="background-color: #a44a20;">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo empleado
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABLA OCULTA PARA DATATABLES (NO uses d-none!!) --}}
    <table id="empleados" class="table" style="visibility:hidden; position:absolute; left:-9999px;">
        <thead>
            <tr>
                <th>nombre</th>
                <th>dni</th>
                <th>banco</th>
                <th>obra</th>
                <th>foto</th>
                <th>acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($empleados as $empleado)
                <tr>
                    <td>{{ $empleado->apellido }}, {{ $empleado->nombre }}</td>
                    <td>{{ $empleado->dni }}</td>
                    <td>{{ $empleado->banco->nombre_banco ?? '-' }}</td>
                    <td>{{ $empleado->obraSocial->nombre ?? '-' }}</td>

                    {{-- FOTO --}}
                    <td>
                        @php
                            $foto = $empleado->foto_perfil
                                ? Storage::url('fotos_empleados/'.$empleado->foto_perfil)
                                : asset('img/default-user.png');
                        @endphp

                        {!! '<img src="'.$foto.'" class="img-fluid rounded-circle"
                            style="width:80px;height:80px;object-fit:cover;">' !!}
                    </td>

                    {{-- ACCIONES --}}
                    <td>
                        <a href="{{ route('empleados.show', $empleado->id) }}"
                           class="btn btn-secondary btn-sm w-100 mb-1">Ver perfil</a>

                        <a href="{{ route('empleados.edit', $empleado->id) }}"
                           class="btn btn-primary btn-sm w-100 mb-1">Editar</a>

                        <form action="{{ route('empleados.destroy', $empleado->id) }}" 
                              method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este empleado?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm w-100">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TARJETAS --}}
    <div id="cards-container" class="row"></div>

</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {

    let table = $('#empleados').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 6,
        responsive: true,

        // 🚀 FIX: evitar que DataTables elimine el HTML de la imagen
        columnDefs: [
            {
                targets: [4, 5],
                orderable: false,
                searchable: false,
                render: function (data) {
                    return data; // Mantiene el HTML tal cual
                }
            }
        ]
    });

    function renderCards() {
        $('#cards-container').empty();

        table.rows({ search: 'applied' }).every(function () {
            let data = this.data();

            let card = `
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            ${data[4]}
                            <h5 class="mt-3 fw-bold">${data[0]}</h5>
                            <p><strong>DNI:</strong> ${data[1]}</p>
                            <p><strong>Banco:</strong> ${data[2]}</p>
                            <p><strong>Obra social:</strong> ${data[3]}</p>
                            ${data[5]}
                        </div>
                    </div>
                </div>
            `;
            $('#cards-container').append(card);
        });
    }

    renderCards();
    table.on('draw', renderCards);
});
</script>
@endpush
