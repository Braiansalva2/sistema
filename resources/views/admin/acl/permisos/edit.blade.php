@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-3" style="color:#a44a20;">
        <i class="bi bi-pencil-square"></i> Editar permiso
    </h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('acl.permisos.update', $permiso->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre del permiso</label>
                    <input type="text" name="name" 
                           class="form-control" 
                           value="{{ $permiso->name }}" required>
                </div>

                <button class="btn text-white px-4" 
                        style="background-color:#a44a20;">
                    Actualizar
                </button>

                <a href="{{ route('acl.permisos.index') }}" class="btn btn-secondary">
                    Volver
                </a>

            </form>

        </div>
    </div>

</div>
@endsection
