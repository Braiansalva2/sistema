@extends('layouts.comercial')

@section('content')

<div class="container">

    <h3>Ubicaciones</h3>

    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate">
        ➕ Nueva Ubicación
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th width="150">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($ubicaciones as $u)
            <tr>
                <td>{{ $u->nombre }}</td>
                <td>{{ $u->tipo }}</td>
                <td>{{ $u->estado }}</td>
                <td>

                    <button class="btn btn-warning btn-sm"
                        onclick="editar({{ $u->id }}, '{{ $u->nombre }}', '{{ $u->tipo }}', '{{ $u->descripcion }}')">
                        Editar
                    </button>

                    <button class="btn btn-danger btn-sm"
                        onclick="eliminar({{ $u->id }})">
                        Eliminar
                    </button>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

{{-- ================= MODAL CREATE ================= --}}
<div class="modal fade" id="modalCreate">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('comercial.ubicaciones.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5>Nueva Ubicación</h5>
                </div>

                <div class="modal-body">

                    <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>

                    <input type="text" name="tipo" class="form-control mb-2" placeholder="Tipo (ciudad, planta, etc)">

                    <textarea name="descripcion" class="form-control" placeholder="Descripción"></textarea>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5>Editar Ubicación</h5>
                </div>

                <div class="modal-body">

                    <input type="text" name="nombre" id="edit_nombre" class="form-control mb-2" required>

                    <input type="text" name="tipo" id="edit_tipo" class="form-control mb-2">

                    <textarea name="descripcion" id="edit_descripcion" class="form-control"></textarea>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-warning">Actualizar</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- ================= MODAL DELETE ================= --}}
<div class="modal fade" id="modalDelete">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formDelete" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-body text-center">
                    <p>¿Eliminar esta ubicación?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger">Eliminar</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

function editar(id, nombre, tipo, descripcion) {
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_tipo').value = tipo ?? '';
    document.getElementById('edit_descripcion').value = descripcion ?? '';

    document.getElementById('formEdit').action = "/comercial/ubicaciones/" + id;

    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

function eliminar(id) {
    document.getElementById('formDelete').action = "/comercial/ubicaciones/" + id;

    new bootstrap.Modal(document.getElementById('modalDelete')).show();
}

</script>
@endsection