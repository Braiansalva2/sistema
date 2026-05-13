@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold" style="color:#a44a20;">
            Gestión de Usuarios
        </h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Permisos directos</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($usuarios as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>

                            <td>
                                @forelse($u->roles as $rol)
                                    <span class="badge bg-primary">{{ $rol->name }}</span>
                                @empty
                                    <span class="text-muted">Sin rol</span>
                                @endforelse
                            </td>

                            <td>
                                @forelse($u->permissions as $perm)
                                    <span class="badge bg-warning text-dark">{{ $perm->name }}</span>
                                @empty
                                    <span class="text-muted">Ninguno</span>
                                @endforelse
                            </td>

                            <td class="text-center">
                                <a href="{{ route('acl.usuarios.edit', $u->id) }}" 
                                   class="btn btn-primary btn-sm">
                                    <i class="bi bi-gear"></i> Gestionar
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
