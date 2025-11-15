@extends('layouts.rrhh')

@section('title', 'Panel RRHH')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center shadow border-0 rounded-4 h-100">
            <div class="card-body">
                <h5 class="card-title text-uppercase fw-bold" style="color: #b34c1a;">Legajos</h5>
                <p class="text-muted mb-4">Gestión completa de empleados y sus datos personales.</p>
                <a href="#" class="btn text-white fw-semibold px-4 py-2" style="background-color: #b34c1a;">Entrar</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow border-0 rounded-4 h-100">
            <div class="card-body">
                <h5 class="card-title text-uppercase fw-bold" style="color: #b34c1a;">Asistencia</h5>
                <p class="text-muted mb-4">Control de entradas, salidas y ausencias.</p>
                <a href="#" class="btn text-white fw-semibold px-4 py-2" style="background-color: #b34c1a;">Entrar</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center shadow border-0 rounded-4 h-100">
            <div class="card-body">
                <h5 class="card-title text-uppercase fw-bold" style="color: #b34c1a;">Contratos</h5>
                <p class="text-muted mb-4">Gestión de contratos y condiciones laborales.</p>
                <a href="#" class="btn text-white fw-semibold px-4 py-2" style="background-color: #b34c1a;">Entrar</a>
            </div>
        </div>
    </div>
</div>

<div class="text-center mt-5 text-muted small fw-medium">
    © {{ date('Y') }} <strong>GVH Logística Minera</strong> — Área de RRHH.
</div>
@endsection
