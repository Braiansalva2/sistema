@extends('layouts.comercial')


@section('content')

<div class="container-fluid py-3">

    {{-- ENCABEZADO --}}
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold mb-0">Órdenes de Servicio / Viajes</h2>
            <small class="text-muted">Control operativo de tráfico y logística</small>
        </div>
        <div class="col text-end">
            <button class="btn btn-success btn-sm">
                ➕ Nuevo viaje
            </button>
            <button class="btn btn-outline-dark btn-sm">
                🖥 Panel tiempo real
            </button>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-2">
                    <input type="date" class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <select class="form-select form-select-sm">
                        <option>Estado</option>
                        <option>Pendiente</option>
                        <option>En curso</option>
                        <option>Finalizado</option>
                        <option>Demorado</option>
                        <option>Cancelado</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select class="form-select form-select-sm">
                        <option>Tipo de servicio</option>
                        <option>Transporte</option>
                        <option>Descarga</option>
                        <option>Interno</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text"
                           class="form-control form-control-sm"
                           placeholder="Buscar por cliente o código">
                </div>

                <div class="col-md-3 text-end">
                    <button class="btn btn-outline-secondary btn-sm">
                        Limpiar filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA PRINCIPAL --}}
    <div class="card shadow-sm border-0">
        <div class="card-header fw-semibold bg-light">
            Viajes registrados
        </div>

        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Carga</th>
                        <th>Ruta</th>
                        <th>Unidad</th>
                        <th>Choferes</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- FILA DE EJEMPLO --}}
                    <tr>
                        <td class="fw-semibold">VJ-2025-001</td>
                        <td>15/01/2025</td>
                        <td>GVH Minería</td>
                        <td>
                            <span class="badge bg-primary">Transporte</span>
                        </td>
                        <td>Insumos generales</td>
                        <td>
                            Salta → Salar del Hombre Muerto
                        </td>
                        <td>
                            Scania R450<br>
                            <small class="text-muted">Camión + vitren</small>
                        </td>
                        <td>
                            Juan Pérez<br>
                            <small class="text-muted">+ Relevo</small>
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                En curso
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary">
                                👁
                            </button>
                            <button class="btn btn-sm btn-outline-warning">
                                ✏
                            </button>
                            <button class="btn btn-sm btn-outline-success">
                                📍
                            </button>
                        </td>
                    </tr>

                    {{-- OTRA FILA --}}
                    <tr>
                        <td class="fw-semibold">VJ-2025-002</td>
                        <td>14/01/2025</td>
                        <td>Empresa Minera X</td>
                        <td>
                            <span class="badge bg-secondary">Interno</span>
                        </td>
                        <td>Retorno vacío</td>
                        <td>
                            Campo → Base
                        </td>
                        <td>
                            Ranger 4x4
                        </td>
                        <td>
                            Carlos Gómez
                        </td>
                        <td>
                            <span class="badge bg-success">
                                Finalizado
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary">
                                👁
                            </button>
                            <button class="btn btn-sm btn-outline-secondary">
                                🧾
                            </button>
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection

