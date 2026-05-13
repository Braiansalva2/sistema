@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h2 class="mb-4 fw-bold">Panel de Usuarios</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php $modals = []; @endphp


{{-- vista para movil --}}
    <div class="d-md-none">
        {{-- Buscador móvil --}}
<div class="d-md-none mb-3">
    <input type="text" id="buscadorMovil" class="form-control" placeholder="Buscar usuario...">
</div>
        @foreach($usuarios as $user)
            <div class="card mb-3 shadow-sm">

                <div class="card-body">

                    <h5 class="card-title fw-bold">{{ $user->email }}</h5>

                    <p class="mb-1">
                        <strong>Empleado:</strong>
                        @if($user->empleado)
                            {{ $user->empleado->nombre }} {{ $user->empleado->apellido }}
                        @else
                            <span class="text-muted">No asignado</span>
                        @endif
                    </p>


                    <p class="mb-1">
                        <strong>Rol:</strong>
                        @foreach($user->roles as $rol)
                            <span class="badge bg-primary">{{ $rol->name }}</span>
                        @endforeach
                    </p>

                    <p>
                        <strong>Estado:</strong>
                        @if($user->active)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Deshabilitado</span>
                        @endif
                    </p>

                <div class="d-flex justify-content-between mt-3">

    {{-- Activar / Deshabilitar --}}
    <form id="formToggle{{ $user->id }}" 
          action="{{ route('rrhh.usuarios.toggle', $user) }}" 
          method="POST">
        @csrf @method('PUT')

        @if($user->active)
            <button type="button"
                class="btn btn-danger btn-sm rounded-circle"
                onclick="confirmarDeshabilitar({{ $user->id }})"
                title="Deshabilitar">
                <i class="fa-solid fa-lock"></i>
            </button>
        @else
            <button type="submit" class="btn btn-success btn-sm rounded-circle" title="Habilitar">
                <i class="fa-solid fa-lock-open"></i>
            </button>
        @endif
    </form>

    {{-- Cambiar contraseña --}}
    <button class="btn btn-warning btn-sm rounded-circle"
        data-bs-toggle="modal"
        data-bs-target="#modalPassword{{ $user->id }}"
        title="Cambiar contraseña">
        <i class="fa-solid fa-key"></i>
    </button>

    {{-- Editar --}}
<button class="btn btn-primary btn-sm rounded-circle"
        data-bs-toggle="modal"
        data-bs-target="#modalEditarUsuario{{ $user->id }}"
        title="Editar usuario">
    <i class="fa-solid fa-pen"></i>
</button>


    {{-- Eliminar --}}
    <form action="{{ route('rrhh.usuarios.destroy', $user) }}" 
          method="POST" 
          onsubmit="return confirm('¿Seguro que querés eliminar este usuario?')" 
          class="d-inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm rounded-circle" title="Eliminar">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>

</div>



                </div>
            </div>

            {{-- Guardar modal para imprimir al final --}}
            @php
                $modals[] = view('rrhh.usuarios.modal-password', ['user' => $user])->render();
            @endphp

        @endforeach
    </div>



    {{-- vistas del escritorio --}}
    <div class="table-responsive d-none d-md-block">
        <table id="tablaUsuarios" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                   
                    <th>Empleado</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($usuarios as $user)
                <tr>
                   

                    <td>
                        @if($user->empleado)
                            {{ $user->empleado->nombre }} {{ $user->empleado->apellido }}
                        @else
                            <span class="text-muted">No asignado</span>
                        @endif
                    </td>

                    <td>{{ $user->email }}</td>

                    <td>
                        @foreach($user->roles as $rol)
                            <span class="badge bg-primary">{{ $rol->name }}</span>
                        @endforeach
                    </td>

                    <td>
                        @if($user->active)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Deshabilitado</span>
                        @endif
                    </td>

                  <td class="d-flex gap-2">

    {{-- Activar / Deshabilitar --}}
    <form id="formToggle{{ $user->id }}" 
          action="{{ route('rrhh.usuarios.toggle', $user) }}" 
          method="POST">
        @csrf @method('PUT')

        @if($user->active)
            <button type="button"
                class="btn btn-danger btn-sm"
                onclick="confirmarDeshabilitar({{ $user->id }})">
                Deshabilitar
            </button>
        @else
            <button type="submit" class="btn btn-success btn-sm">
                Habilitar
            </button>
        @endif
    </form>

    {{-- Cambiar contraseña --}}
    <button class="btn btn-warning btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#modalPassword{{ $user->id }}">
        Contraseña
    </button>

    {{-- Editar usuario --}}
    <button class="btn btn-primary btn-sm rounded-circle"
        data-bs-toggle="modal"
        data-bs-target="#modalEditarUsuario{{ $user->id }}"
        title="Editar usuario">
    <i class="fa-solid fa-pen"></i>
</button>



    {{-- Eliminar usuario --}}
  <form action="{{ route('rrhh.usuarios.destroy', $user) }}" 
      method="POST" 
      onsubmit="return confirm('¿Seguro que querés eliminar este usuario?')" 
      class="d-inline">
    @csrf
    @method('DELETE')

    <button class="btn btn-danger btn-sm rounded-circle" title="Eliminar">
        <i class="fa-solid fa-trash"></i>
    </button>
</form>


</td>

                </tr>

                {{-- Guardar modal para imprimir después --}}
                @php
                    $modals[] = view('rrhh.usuarios.modal-password', ['user' => $user])->render();
                @endphp

                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- aqui trae los modales --}}
{!! implode("", $modals) !!}

{{-- scrip --}}
@push('scripts')
<script>
$(document).ready(function() {

    let isMobile = window.matchMedia("(max-width: 768px)").matches;

    if (!isMobile) {
        $('#tablaUsuarios').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
        });
    }
});
</script>

<script>
let idUsuarioADeshabilitar = null;

function confirmarDeshabilitar(id) {
    idUsuarioADeshabilitar = id;
    let modal = new bootstrap.Modal(document.getElementById('modalConfirmar'));
    modal.show();
}

document.getElementById('btnConfirmarDeshabilitar').addEventListener('click', function() {
    if (idUsuarioADeshabilitar) {
        document.getElementById('formToggle' + idUsuarioADeshabilitar).submit();
    }
});
</script>

<script>
document.getElementById('buscadorMovil').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let tarjetas = document.querySelectorAll('.card');

    tarjetas.forEach(card => {
        let texto = card.innerText.toLowerCase();
        card.style.display = texto.includes(filtro) ? '' : 'none';
    });
});



let idUsuarioAEliminar = null;

function confirmarEliminar(id) {
    idUsuarioAEliminar = id;
    let modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
    modal.show();
}

document.getElementById('btnConfirmarEliminar').addEventListener('click', function() {
    if (idUsuarioAEliminar) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '/rrhh/usuarios/' + idUsuarioAEliminar; // ajusta si tu ruta cambia

        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;

        document.body.appendChild(form);
        form.submit();
    }
});

</script>

@endpush

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Modal Confirmación Deshabilitar Usuario -->
<div class="modal fade" id="modalConfirmar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; overflow:hidden;">

            <div class="modal-header" style="background:#b34c1a; color: white;">
                <h5 class="modal-title">
                    Confirmar acción
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center" style="background:#f8d3b5;">
                <p class="fw-bold" style="font-size: 1.1rem;">
                    ¿Estás seguro de que deseas <span class="text-danger">deshabilitar</span> este usuario?
                </p>
                <p class="text-muted">El usuario no podrá iniciar sesión hasta que sea habilitado nuevamente.</p>
            </div>

            <div class="modal-footer" style="background:#fbe7d6;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" id="btnConfirmarDeshabilitar" class="btn btn-danger">
                    Sí, deshabilitar
                </button>
            </div>

        </div>
    </div>
</div>
<!-- Modal Confirmar Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; overflow:hidden;">

            <div class="modal-header" style="background:#a00000; color: white;">
                <h5 class="modal-title">Eliminar Usuario</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center" style="background:#f8d3b5;">
                <p class="fw-bold" style="font-size: 1.1rem;">
                    ¿Seguro deseas <span class="text-danger">eliminar</span> este usuario?
                </p>
                <p class="text-muted">Esta acción es permanente y no se puede deshacer.</p>
            </div>

            <div class="modal-footer" style="background:#fbe7d6;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" id="btnConfirmarEliminar" class="btn btn-danger">
                    Sí, eliminar
                </button>
            </div>

        </div>
    </div>
</div>
{{-- MODAL EDITAR USUARIO --}}
<div class="modal fade" id="modalEditarUsuario{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header" style="background:#a04518; color:white;">
                <h5 class="modal-title">Editar Usuario</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('rrhh.usuarios.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control mb-3" required>

                    <label>Rol</label>
                    <select name="role" class="form-select">
                        @foreach($roles as $rol)
                            <option value="{{ $rol }}" 
                                @if($user->hasRole($rol)) selected @endif>
                                {{ $rol }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Guardar cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
