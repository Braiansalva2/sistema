@extends('layouts.comercial')

@section('content')

<div class="container">
    <h3>Servicios</h3>

    <!-- BOTÓN NUEVO -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate">
        ➕ Nuevo Servicio
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th width="150">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($tipos as $tipo)
                <tr>
                    <td>{{ $tipo->nombre }}</td>
                    <td>{{ $tipo->estado }}</td>
                    <td>
                        <button 
                            class="btn btn-warning btn-sm"
                            onclick="abrirEditar({{ $tipo->id }}, '{{ $tipo->nombre }}', '{{ $tipo->descripcion }}')">
                            Editar
                        </button>

                        <button 
                            class="btn btn-danger btn-sm"
                            onclick="abrirEliminar({{ $tipo->id }})">
                            Eliminar
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- ================= MODAL CREAR ================= -->
<div class="modal fade" id="modalCreate">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('comercial.tipos-servicio.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5>Nuevo Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= MODAL EDITAR ================= -->
<div class="modal fade" id="modalEdit">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5>Editar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="edit_descripcion" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-warning">Actualizar</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= MODAL ELIMINAR ================= -->
<div class="modal fade" id="modalDelete">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formDelete" method="POST">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5>Confirmar eliminación</h5>
                </div>

                <div class="modal-body">
                    <p>¿Seguro que querés eliminar este servicio?</p>
                </div>

                <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button class="btn btn-danger">Eliminar</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

function abrirEditar(id, nombre, descripcion) {
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_descripcion').value = descripcion ?? '';

    let form = document.getElementById('formEdit');
    form.action = "/comercial/tipos-servicio/" + id;

    let modal = new bootstrap.Modal(document.getElementById('modalEdit'));
    modal.show();
}

function abrirEliminar(id) {
    let form = document.getElementById('formDelete');
    form.action = "/comercial/tipos-servicio/" + id;

    let modal = new bootstrap.Modal(document.getElementById('modalDelete'));
    modal.show();
}

</script>
@endsection