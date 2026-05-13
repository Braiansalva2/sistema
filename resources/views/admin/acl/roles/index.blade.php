@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between mb-3">
        <h2 class="fw-bold text-primary">Roles del sistema</h2>

        <a href="{{ route('acl.roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo rol
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($roles as $rol)
                <tr>
                    <td>{{ $rol->name }}</td>
                    <td>
                        <a href="{{ route('acl.roles.edit', $rol) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('acl.roles.destroy', $rol) }}" 
                              method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>
@endsection
