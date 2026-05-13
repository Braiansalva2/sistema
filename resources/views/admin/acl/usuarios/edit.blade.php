@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-3" style="color:#a44a20;">
        Gestionar permisos de: {{ $usuario->name }}
    </h2>

    {{-- ROLES --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold" style="background:#a44a20; color:white;">
            Roles del usuario
        </div>
        <div class="card-body">

      <form action="{{ route('acl.usuarios.roles.update', $usuario->id) }}" method="POST">

    @csrf
    @method('PUT')
                @foreach($roles as $rol)
                    <div class="form-check mb-2">
                        <input type="checkbox" 
                               name="roles[]" 
                               value="{{ $rol->name }}"
                               class="form-check-input"
                               {{ $usuario->hasRole($rol->name) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            {{ $rol->name }}
                        </label>
                    </div>
                @endforeach

                <button class="btn text-white mt-3" style="background:#a44a20;">
                    Guardar roles
                </button>
            </form>

        </div>
    </div>

    {{-- PERMISOS DIRECTOS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold" style="background:#a44a20; color:white;">
            Permisos individuales
        </div>

        <div class="card-body">

          <form action="{{ route('acl.usuarios.permisos.update', $usuario->id) }}" method="POST">

                @csrf @method('PUT')

                @foreach($permisos as $permiso)
                    <div class="form-check mb-2">
                        <input type="checkbox" 
                               name="permisos[]" 
                               value="{{ $permiso->name }}"
                               class="form-check-input"
                              {{ $usuario->permissions->contains('name', $permiso->name) ? 'checked' : '' }}


                        <label class="form-check-label">
                            {{ $permiso->name }}
                        </label>
                    </div>
                @endforeach

                <button class="btn text-white mt-3" style="background:#a44a20;">
                    Guardar permisos
                </button>
            </form>

        </div>
    </div>


    {{-- PERMISOS HEREDADOS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold bg-secondary text-white">
            Permisos heredados del rol
        </div>

        <div class="card-body">

            @forelse($usuario->getPermissionsViaRoles() as $perm)
                <span class="badge bg-success mb-1">{{ $perm->name }}</span>
            @empty
                <p class="text-muted">No tiene permisos heredados</p>
            @endforelse

        </div>
    </div>


    <a href="{{ route('acl.usuarios.index') }}" class="btn btn-secondary">
        Volver
    </a>

</div>
@endsection
