@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold" style="color:#a44a20;">Gestión de Permisos</h2>

    <a href="{{ route('acl.permisos.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg"></i> Nuevo permiso
</a>
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
                        <th>Permiso</th>
                        <th>Guard</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($permisos as $permiso)
                        <tr>
                            <td>{{ $permiso->id }}</td>
                            <td class="fw-bold">{{ $permiso->name }}</td>
                            <td>{{ $permiso->guard_name }}</td>

                            <td class="text-center">

                                <a href="{{ route('acl.permisos.edit', $permiso->id) }}"
                                   class="btn btn-primary btn-sm">
                                   <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('acl.permisos.destroy', $permiso->id) }}"
                                      method="POST" 
                                      class="d-inline">
                                    @csrf @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
