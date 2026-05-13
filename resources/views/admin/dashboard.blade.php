@extends('layouts.admin')

@section('title', 'Panel de Administración')

@section('content')

    <h2 class="text-center text-success fw-bold mb-4">Módulo del Administrador</h2>
    <div class="alert alert-info text-center">Bienvenido, {{ Auth::user()->name }}</div>

    <!-- TODO el contenido del dashboard queda igual -->

      <!-- Contenido principal -->
            <main class="flex-grow-1 p-4" style="background-color: #f8d3b5;">
                <div class="container-fluid">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card text-center shadow border-0 rounded-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase fw-bold" style="color: #b34c1a;">Recursos Humanos</h5>
                                    <p class="text-muted mb-4">Gestión de legajos, empleados y asistencia.</p>
                                    <a href="{{ route('rrhh.empleados.index') }}" class="btn text-white fw-semibold px-4 py-2"
                                        style="background-color: #b34c1a;">Entrar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center shadow border-0 rounded-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase fw-bold" style="color: #b34c1a;">Comercial</h5>
                                    <p class="text-muted mb-4">Gestión de contratos.</p>
                                    <a href="{{ route('comercial.dashboard') }}" class="btn text-white fw-semibold px-4 py-2"
                                        style="background-color: #b34c1a;">Entrar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center shadow border-0 rounded-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase fw-bold" style="color: #b34c1a;">Proveedores</h5>
                                    <p class="text-muted mb-4">Tratos con proveedores</p>
                                    <a href="#" class="btn text-white fw-semibold px-4 py-2"
                                        style="background-color: #b34c1a;">Entrar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center shadow border-0 rounded-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase fw-bold" style="color: #b34c1a;">Salud</h5>
                                    <p class="text-muted mb-4">Gestión de legajos, empleados y asistencia.</p>
                                    <a href="#" class="btn text-white fw-semibold px-4 py-2"
                                        style="background-color: #b34c1a;">Entrar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center shadow border-0 rounded-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase fw-bold" style="color: #b34c1a;">Operaciones</h5>
                                    <p class="text-muted mb-4">Control de maquinaria y personal activo.</p>
                                    <a href="#" class="btn text-white fw-semibold px-4 py-2"
                                        style="background-color: #b34c1a;">Entrar</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center shadow border-0 rounded-4 h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-uppercase fw-bold" style="color: #b34c1a;">Documentación</h5>
                                    <p class="text-muted mb-4">Gestión de archivos y reportes internos.</p>
                                    <a href="#" class="btn text-white fw-semibold px-4 py-2"
                                        style="background-color: #b34c1a;">Entrar</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-5 text-muted small fw-medium">
                        © {{ date('Y') }} <strong>GVH Logística Minera</strong> — Todos los derechos reservados.
                    </div>
                </div>
            </main>
        </div>
    </div>

@endsection
