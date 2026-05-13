@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-3" style="color:#a44a20;">
        <i class="bi bi-plus-circle"></i> Crear nuevo rol
    </h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre del rol</label>
                    <input type="text" name="name" 
                           class="form-control" required>
                </div>

                <button class="btn text-white px-4" 
                        style="background-color:#a44a20;">
                    Guardar
                </button>

                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
            </form>

        </div>
    </div>

</div>
@endsection
