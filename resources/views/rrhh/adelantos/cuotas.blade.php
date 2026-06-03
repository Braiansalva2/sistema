@extends('layouts.rrhh')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">

        <div>
            <h2 class="fw-bold mb-1">
                💳 Gestión de cuotas de adelantos
            </h2>

            <p class="text-muted mb-0">
                Administrá descuentos, cuotas pendientes y adelantos saldados.
            </p>
        </div>

        <a href="{{ route('rrhh.adelantos.index') }}"
           class="btn btn-secondary shadow-sm">

            <i class="bi bi-arrow-left"></i>
            Volver a solicitudes

        </a>

    </div>

    {{-- ALERTAS --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            {{ session('success') }}

            <button class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            {{ session('error') }}

            <button class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif

    {{-- MÉTRICAS --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <small class="text-muted">
                        Adelantos activos
                    </small>

                    <h3 class="fw-bold text-primary mb-0">
                        {{ $adelantos->total() }}
                    </h3>

                </div>
            </div>

        </div>

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <small class="text-muted">
                        Cuotas pendientes
                    </small>

                    <h3 class="fw-bold text-warning mb-0">
                        {{ $totalPendientes }}
                    </h3>

                </div>
            </div>

        </div>

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <small class="text-muted">
                        Cuotas pagadas
                    </small>

                    <h3 class="fw-bold text-success mb-0">
                        {{ $totalPagadas }}
                    </h3>

                </div>
            </div>

        </div>

        <div class="col-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <small class="text-muted">
                        Monto pendiente
                    </small>

                    <h3 class="fw-bold text-danger mb-0">
                        ${{ number_format($montoPendiente, 0, ',', '.') }}
                    </h3>

                </div>
            </div>

        </div>

    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">

                        <label class="form-label">
                            Buscar empleado
                        </label>

                        <input type="text"
                               name="buscar"
                               class="form-control"
                               placeholder="Nombre, apellido o DNI"
                               value="{{ request('buscar') }}">

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Estado
                        </label>

                        <select name="filtro"
                                class="form-select">

                            <option value="">
                                Todos
                            </option>

                            <option value="pendientes"
                                {{ request('filtro') == 'pendientes' ? 'selected' : '' }}>
                                Pendientes
                            </option>

                            <option value="saldados"
                                {{ request('filtro') == 'saldados' ? 'selected' : '' }}>
                                Saldados
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-primary w-100">

                            <i class="bi bi-search"></i>
                            Filtrar

                        </button>

                    </div>

                    <div class="col-md-2">

                        <a href="{{ route('rrhh.adelantos.cuotas') }}"
                           class="btn btn-secondary w-100">

                            Limpiar

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLA --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0">

            <h5 class="mb-0">
                📄 Gestión de cuotas
            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>
                            <th>Empleado</th>
                            <th>Monto</th>
                            <th>Cuotas</th>
                            <th>Restante</th>
                            <th>Estado</th>
                            <th width="180">
                                Acciones
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($adelantos as $adelanto)

                            @php

                                $pagadas = $adelanto->cuotas
                                    ->where('estado', 'pagada')
                                    ->count();

                                $pendientes = $adelanto->cuotas
                                    ->where('estado', 'pendiente')
                                    ->count();

                                $restante = $adelanto->cuotas
                                    ->where('estado', 'pendiente')
                                    ->sum('monto');

                            @endphp

                            <tr>

                                <td>
                                    {{ $adelanto->id }}
                                </td>

                                <td>

                                    <div class="fw-bold">

                                        {{ $adelanto->empleado->nombre ?? '' }}
                                        {{ $adelanto->empleado->apellido ?? '' }}

                                    </div>

                                    <small class="text-muted">

                                        DNI:
                                        {{ $adelanto->empleado->dni ?? '-' }}

                                    </small>

                                </td>

                                <td class="fw-bold text-success">

                                    ${{ number_format($adelanto->monto_total, 0, ',', '.') }}

                                </td>

                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $pagadas }}/{{ $adelanto->cuotas_total }}

                                    </span>

                                </td>

                                <td class="fw-bold text-danger">

                                    ${{ number_format($restante, 0, ',', '.') }}

                                </td>

                                <td>

                                    @if($adelanto->estado == 'pagado')

                                        <span class="badge bg-primary px-3 py-2">
                                            Pagado
                                        </span>

                                    @elseif($adelanto->estado == 'saldado')

                                        <span class="badge bg-success px-3 py-2">
                                            ✔ Saldado
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($adelanto->estado == 'pagado')

                                        <button class="btn btn-warning btn-sm shadow-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalCuotas{{ $adelanto->id }}">

                                            <i class="bi bi-cash-stack"></i>
                                            Gestionar cuotas

                                        </button>

                                    @else

                                        <span class="badge bg-success">
                                            Adelanto saldado
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5 text-muted">

                                    No hay adelantos para mostrar.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINACIÓN --}}
            <div class="mt-4">

                {{ $adelantos->withQueryString()->links() }}

            </div>

        </div>

    </div>

</div>

{{-- MODALES --}}
@foreach($adelantos as $adelanto)

<div class="modal fade"
     id="modalCuotas{{ $adelanto->id }}"
     tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <form action="{{ route('rrhh.adelantos.cuotas.pagar') }}"
              method="POST"
              class="formCuotas">

            @csrf

            <input type="hidden"
                   name="adelanto_id"
                   value="{{ $adelanto->id }}">

            <div class="modal-content border-0 shadow">

                {{-- HEADER --}}
                <div class="modal-header bg-warning">

                    <h5 class="modal-title fw-bold">

                        💳 Gestionar cuotas
                        #{{ $adelanto->id }}

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>

                </div>

                {{-- BODY --}}
                <div class="modal-body">

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th width="50">

                                        <input type="checkbox"
                                               class="check-all">

                                    </th>

                                    <th>
                                        Cuota
                                    </th>

                                    <th>
                                        Monto
                                    </th>

                                    <th>
                                        Estado
                                    </th>

                                    <th>
                                        Fecha pago
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($adelanto->cuotas as $cuota)

                                    <tr>

                                        <td>

                                            @if($cuota->estado == 'pendiente')

                                                <input type="checkbox"
                                                       name="cuotas[]"
                                                       value="{{ $cuota->id }}"
                                                       class="cuota-checkbox">

                                            @endif

                                        </td>

                                        <td>

                                            Cuota
                                            {{ $cuota->numero_cuota }}

                                        </td>

                                        <td class="fw-bold text-success">

                                            ${{ number_format($cuota->monto, 0, ',', '.') }}

                                        </td>

                                        <td>

                                            @if($cuota->estado == 'pagada')

                                                <span class="badge bg-success">
                                                    Pagada
                                                </span>

                                            @else

                                                <span class="badge bg-warning text-dark">
                                                    Pendiente
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            @if($cuota->fecha_pago)

                                                {{ \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') }}

                                            @else

                                                -

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5"
                                            class="text-center text-muted py-4">

                                            No existen cuotas generadas.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-primary btnRegistrarCuotas">

                        <span class="texto-btn">

                            <i class="bi bi-check-circle"></i>
                            Registrar cuotas seleccionadas

                        </span>

                    </button>

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Cerrar

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endforeach

{{-- SCRIPTS --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    // 🔥 CHECK TODOS
    document.querySelectorAll('.check-all').forEach(checkAll => {

        checkAll.addEventListener('change', function () {

            const modal = this.closest('.modal');

            modal.querySelectorAll('.cuota-checkbox')
                .forEach(check => {

                    check.checked = this.checked;

                });

        });

    });

    // 🔥 BLOQUEAR BOTÓN
    document.querySelectorAll('.formCuotas').forEach(formulario => {

        formulario.addEventListener('submit', function (e) {

            const checks = formulario.querySelectorAll('.cuota-checkbox:checked');

            if (checks.length === 0) {

                e.preventDefault();

                alert('Debes seleccionar al menos una cuota.');

                return;
            }

            if (!confirm('¿Confirmar registro de cuotas seleccionadas?')) {

                e.preventDefault();

                return;
            }

            const boton = formulario.querySelector('.btnRegistrarCuotas');

            boton.disabled = true;

            boton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Procesando...
            `;

        });

    });

});

</script>

@endsection