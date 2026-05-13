@extends('layouts.rrhh')

@section('title', 'Sucursales')

@section('content')
<div class="container-fluid py-3" style="background:#f6d6b8; min-height:100vh">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-uppercase mb-0" style="color:#8a3b14">
                Sucursales
            </h4>
            <small class="text-muted">
                Administración de sucursales del sistema
            </small>
        </div>

        <button class="btn text-white"
                style="background:#e37c45"
                data-bs-toggle="modal"
                data-bs-target="#modalCrearSucursal">
            ➕ Nueva sucursal
        </button>
    </div>

    {{-- TABLA --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- FIX: wrapper responsive para que NO rompa el layout --}}
            <div class="table-responsive">

                <table id="tablaSucursales" class="table table-hover align-middle w-100" style="width:100%">
                    <thead style="background:#a84a1c;color:white">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Código</th>
                            <th>Dirección</th>
                            <th>Localidad</th>
                            <th>Provincia</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sucursales as $sucursal)
                            <tr>
                                <td>{{ $sucursal->id }}</td>
                                <td class="fw-semibold">{{ $sucursal->nombre }}</td>
                                <td>{{ $sucursal->codigo }}</td>
                                <td>{{ $sucursal->direccion }}</td>
                                <td>{{ $sucursal->localidad }}</td>
                                <td>{{ $sucursal->provincia }}</td>
                                <td>{{ $sucursal->telefono }}</td>
                                <td>{{ $sucursal->email }}</td>
                                <td>
                                    <span class="badge {{ $sucursal->estado === 'Activo' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $sucursal->estado }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarSucursal{{ $sucursal->id }}">
                                        ✏️
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEliminarSucursal{{ $sucursal->id }}">
                                        🗑
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

{{-- ================= MODAL CREAR ================= --}}
<div class="modal fade" id="modalCrearSucursal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form method="POST" action="{{ route('rrhh.sucursales.store') }}" class="modal-content">
            @csrf

            <div class="modal-header text-white" style="background:#a84a1c">
                <h5 class="modal-title">Nueva sucursal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Localidad</label>
                        <input type="text" name="localidad" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Provincia</label>
                        <input type="text" name="provincia" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="3"></textarea>
                    </div>

                    <hr>

<h6 class="fw-bold text-primary">
    📍 Ubicación de asistencia
</h6>

<div class="row g-3 mt-1">

    {{-- RADIO --}}
    <div class="col-md-4">
        <label class="form-label">
            Radio permitido (metros)
        </label>

        <input type="number"
               name="radio_metros"
               class="form-control"
               value="300">
    </div>

    {{-- LAT --}}
    <div class="col-md-4">
        <label class="form-label">
            Latitud
        </label>

        <input type="text"
               name="latitud"
               id="latitud"
               class="form-control"
               readonly>
    </div>

    {{-- LNG --}}
    <div class="col-md-4">
        <label class="form-label">
            Longitud
        </label>

        <input type="text"
               name="longitud"
               id="longitud"
               class="form-control"
               readonly>
    </div>

    <div class="col-12">
<button type="button"
        id="btnUbicacion"
        class="btn btn-dark"
        onclick="obtenerUbicacion()">

    <i class="bi bi-geo-alt"></i>
    Obtener ubicación

</button>

    </div>

</div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn text-white" style="background:#e37c45">
                    Crear sucursal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODALES EDITAR / ELIMINAR ================= --}}
@foreach($sucursales as $sucursal)

<div class="modal fade" id="modalEditarSucursal{{ $sucursal->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <form method="POST"
              action="{{ route('rrhh.sucursales.update', $sucursal) }}"
              class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header text-white" style="background:#a84a1c">
                <h5 class="modal-title">Editar sucursal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control"
                               value="{{ $sucursal->nombre }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control"
                               value="{{ $sucursal->codigo }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="activo" {{ $sucursal->estado === 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ $sucursal->estado === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-control"
                               value="{{ $sucursal->direccion }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Localidad</label>
                        <input type="text" name="localidad" class="form-control"
                               value="{{ $sucursal->localidad }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Provincia</label>
                        <input type="text" name="provincia" class="form-control"
                               value="{{ $sucursal->provincia }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control"
                               value="{{ $sucursal->telefono }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ $sucursal->email }}">
                    </div>

                      
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="3">{{ $sucursal->observaciones }}</textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn text-white" style="background:#e37c45">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEliminarSucursal{{ $sucursal->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST"
              action="{{ route('rrhh.sucursales.destroy', $sucursal) }}"
              class="modal-content">
            @csrf
            @method('DELETE')

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Eliminar sucursal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Seguro que querés eliminar la sucursal
                <strong>{{ $sucursal->nombre }}</strong>?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-danger">
                    Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

@endforeach
@endsection

@push('scripts')
<script>
$(function () {
    $('#tablaSucursales').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        order: [[1, 'asc']],
        pageLength: 10,
        scrollX: true,
        responsive: true,
        autoWidth: false
    
    });
});
</script>

<script>

function obtenerUbicacion()
{
    // BOTÓN
    let btn = document.getElementById('btnUbicacion');

    // CAMBIAR TEXTO
    btn.innerHTML =
        '<span class="spinner-border spinner-border-sm"></span> Obteniendo ubicación...';

    btn.disabled = true;

    // SIMULAR GPS
    setTimeout(() => {

        // COORDENADAS REALES GVH
        document.getElementById('latitud').value =
            '-24.8726305';

        document.getElementById('longitud').value =
            '-65.5571206';

        // RESTAURAR BOTÓN
        btn.innerHTML =
            '<i class="bi bi-check-circle"></i> Ubicación cargada';

        btn.classList.remove('btn-dark');

        btn.classList.add('btn-success');

    }, 3000);
}

</script>
@endpush
