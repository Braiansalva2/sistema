@extends('layouts.rrhh')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-calendar3 text-primary"></i> Gestión de Roster
            </h2>
            <small class="text-muted">Grupos de subida, bajada e integrantes</small>
        </div>

        <button class="btn btn-primary rounded-pill px-4"
                data-bs-toggle="modal"
                data-bs-target="#modalCrearGrupo">
            <i class="bi bi-plus-circle"></i> Nuevo grupo
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        @forelse($grupos as $grupo)

            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow rounded-4 h-100 roster-card">

                    <div class="card-header border-0 bg-dark text-white rounded-top-4 p-3">
                        <div class="d-flex justify-content-between align-items-start">

                            <div>
                                <h5 class="fw-bold mb-1">
                                    <i class="bi bi-calendar-week"></i>
                                    {{ $grupo->nombre }}
                                </h5>

                                <small class="opacity-75">
                                    Modalidad {{ $grupo->modalidad_trabajo }}x{{ $grupo->modalidad_descanso }}
                                </small>
                            </div>

                            <span class="badge bg-{{ $grupo->estado == 'Activo' ? 'success' : 'secondary' }}">
                                {{ $grupo->estado }}
                            </span>

                        </div>
                    </div>

                    <div class="card-body p-4">

                        <div class="row text-center mb-3">

                            <div class="col-6">
                                <div class="border rounded-4 p-2 bg-light">
                                    <small class="text-muted d-block">Subida</small>
                                    <strong>{{ $grupo->fecha_subida->format('d/m/Y') }}</strong>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="border rounded-4 p-2 bg-light">
                                    <small class="text-muted d-block">Bajada</small>
                                    <strong>{{ $grupo->fecha_bajada->format('d/m/Y') }}</strong>
                                </div>
                            </div>

                        </div>

                        <div class="mb-3">
                            <strong>
                                <i class="bi bi-people"></i>
                                Integrantes:
                            </strong>

                            <span class="badge bg-primary ms-1">
                                {{ $grupo->empleados->count() }}
                            </span>
                        </div>

                        <div class="integrantes-box">

                            @forelse($grupo->empleados as $empleado)

                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar-mini me-2">
                                        {{ strtoupper(substr($empleado->nombre, 0, 1)) }}
                                    </div>

                                    <div class="small">
                                        {{ $empleado->apellido }} {{ $empleado->nombre }}
                                        <div class="text-muted">
                                            DNI: {{ $empleado->dni }}
                                        </div>
                                    </div>
                                </div>

                            @empty

                                <div class="text-muted small">
                                    Sin integrantes asignados.
                                </div>

                            @endforelse

                        </div>

                    </div>

                    <div class="card-footer bg-white border-0 p-3 d-flex gap-2">

                        <button class="btn btn-warning btn-sm rounded-pill w-50"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarGrupo{{ $grupo->id }}">
                            <i class="bi bi-pencil"></i> Editar
                        </button>

                        <form action="{{ route('rrhh.rosters.destroy', $grupo) }}"
                              method="POST"
                              class="w-50"
                              onsubmit="return confirm('¿Deseás dar de baja este grupo de roster?');">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm rounded-pill w-100">
                                <i class="bi bi-trash"></i> Baja
                            </button>
                        </form>

                    </div>

                </div>
            </div>

            {{-- MODAL EDITAR --}}
            <div class="modal fade" id="modalEditarGrupo{{ $grupo->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-4">

                        <form method="POST" action="{{ route('rrhh.rosters.update', $grupo) }}">
                            @csrf
                            @method('PUT')

                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">
                                    <i class="bi bi-pencil-square"></i> Editar grupo roster
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                @include('rrhh.rosters.partials.form', [
                                    'grupo' => $grupo
                                ])
                            </div>

                            <div class="modal-footer border-0">
                                <button class="btn btn-warning rounded-pill px-4">
                                    <i class="bi bi-save"></i> Actualizar
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        @empty

            <div class="col-12">
                <div class="alert alert-light border text-center shadow-sm">
                    No hay grupos de roster cargados.
                </div>
            </div>

        @endforelse

    </div>

</div>

{{-- MODAL CREAR --}}
<div class="modal fade" id="modalCrearGrupo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">

            <form method="POST" action="{{ route('rrhh.rosters.store') }}">
                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i> Nuevo grupo de roster
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @include('rrhh.rosters.partials.form')
                </div>

                <div class="modal-footer border-0">
                    <button class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save"></i> Guardar grupo
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<style>
    .roster-card {
        transition: all .25s ease;
    }

    .roster-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 35px rgba(0,0,0,.18) !important;
    }

    .integrantes-box {
        max-height: 190px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .avatar-mini {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #0d6efd;
        color: #fff;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
</style>
@endsection