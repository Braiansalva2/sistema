@extends('layouts.rrhh')

@section('content')
<div class="container-fluid">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h2 class="fw-bold text-dark mb-1"> Gestión de Adelantos</h2>
            <p class="text-muted mb-0">Administrá solicitudes, aprobaciones y pagos de adelantos.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Revisá los datos cargados:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $pendientes = $adelantos->where('estado', 'pendiente')->count();
        $aprobados = $adelantos->where('estado', 'aprobado')->count();
        $pagados = $adelantos->where('estado', 'pagado')->count();
        $rechazados = $adelantos->where('estado', 'rechazado')->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Pendientes</small>
                    <h3 class="mb-0 text-warning">{{ $pendientes }}</h3>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Aprobados</small>
                    <h3 class="mb-0 text-success">{{ $aprobados }}</h3>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Pagados</small>
                    <h3 class="mb-0 text-primary">{{ $pagados }}</h3>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <small class="text-muted">Rechazados</small>
                    <h3 class="mb-0 text-danger">{{ $rechazados }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Listado de solicitudes</h5>
            <small class="text-muted">Buscá, filtrá y gestioná desde esta pantalla</small>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaAdelantos" class="table table-hover align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Empleado</th>
                            <th>Monto</th>
                            <th>Cuotas</th>
                            <th>Fecha solicitud</th>
                            <th>Estado</th>
                            <th style="min-width: 280px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adelantos as $adelanto)
                            @php
                                $pagadas = $adelanto->movimientos->where('tipo', 'descuento')->count();
                                $restantes = $adelanto->cuotas_total - $pagadas;
                                $valorCuota = $adelanto->cuotas_total > 0 ? $adelanto->monto_total / $adelanto->cuotas_total : 0;
                            @endphp

                            <tr>
                                <td>{{ $adelanto->id }}</td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $adelanto->empleado->nombre ?? '' }}
                                        {{ $adelanto->empleado->apellido ?? '' }}
                                    </div>
                                    <small class="text-muted">
                                        DNI: {{ $adelanto->empleado->dni ?? '-' }}
                                    </small>
                                </td>   

                                <td class="fw-bold text-success">
                                    ${{ number_format($adelanto->monto_total, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $adelanto->cuotas_total }} cuotas
                                    </span>
                                </td>

                                <td>
                                    {{ $adelanto->fecha_solicitud ? \Carbon\Carbon::parse($adelanto->fecha_solicitud)->format('d/m/Y') : '-' }}
                                </td>
                                
 
                                <td>
                                    @if($adelanto->estado == 'pendiente')
                                        <span class="badge bg-warning text-dark px-3 py-2">Pendiente</span>
                                    @elseif($adelanto->estado == 'aprobado')
                                        <span class="badge bg-success px-3 py-2">Aprobado</span>
                                    @elseif($adelanto->estado == 'rechazado')
                                        <span class="badge bg-danger px-3 py-2">Rechazado</span>
                                    @elseif($adelanto->estado == 'pagado')
                                        <span class="badge bg-primary px-3 py-2">Pagado</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap gap-2">

                                        <button type="button"
                                            class="btn btn-outline-secondary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalVer{{ $adelanto->id }}">
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                            <a href="{{ route('rrhh.adelantos.imprimir', $adelanto->id) }}"
                                                target="_blank"
                                                class="btn btn-dark btn-sm">

                                                    <i class="bi bi-printer"></i>

                                                </a>
                                        @if($adelanto->estado == 'pendiente')
                                            <button type="button"
                                                class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalAprobar{{ $adelanto->id }}">
                                                <i class="bi bi-check-circle"></i> Aprobar
                                            </button>

                                            <button type="button"
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalRechazar{{ $adelanto->id }}">
                                                <i class="bi bi-x-circle"></i> Rechazar
                                            </button>
                                        @endif

                                        @if($adelanto->estado == 'aprobado')
                                            <button type="button"
                                                class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalPagar{{ $adelanto->id }}">
                                                <i class="bi bi-cash-coin"></i> Registrar pago
                                            </button>
                                        @endif
                                    </div>

                                    {{-- MODAL VER --}}
                                    <div class="modal fade" id="modalVer{{ $adelanto->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-secondary text-white">
                                                    <h5 class="modal-title">Detalle del adelanto #{{ $adelanto->id }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="row g-3">

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Empleado</label>
                                                            <div class="form-control bg-light">
                                                                {{ $adelanto->empleado->nombre ?? '' }} {{ $adelanto->empleado->apellido ?? '' }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">DNI</label>
                                                            <div class="form-control bg-light">
                                                                {{ $adelanto->empleado->dni ?? '-' }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Monto solicitado</label>
                                                            <div class="form-control bg-light">
                                                                ${{ number_format($adelanto->monto_total, 0, ',', '.') }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Estado</label>
                                                            <div class="form-control bg-light">
                                                                {{ ucfirst($adelanto->estado) }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Cuotas totales</label>
                                                            <div class="form-control bg-light">
                                                                {{ $adelanto->cuotas_total }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Cuotas pagadas</label>
                                                            <div class="form-control bg-light">
                                                                {{ $pagadas }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Cuotas restantes</label>
                                                            <div class="form-control bg-light">
                                                                {{ $restantes }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Valor por cuota</label>
                                                            <div class="form-control bg-light">
                                                                ${{ number_format($valorCuota, 2, ',', '.') }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Fecha de solicitud</label>
                                                            <div class="form-control bg-light">
                                                                {{ $adelanto->fecha_solicitud ? \Carbon\Carbon::parse($adelanto->fecha_solicitud)->format('d/m/Y') : '-' }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="form-label fw-semibold">Motivo</label>
                                                            <div class="form-control bg-light" style="min-height: 80px;">
                                                                {{ $adelanto->motivo ?? 'Sin motivo cargado.' }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Aprobado por</label>
                                                            <div class="form-control bg-light">
                                                                {{ $adelanto->aprobadoPor->name ?? '-' }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Fecha de aprobación</label>
                                                            <div class="form-control bg-light">
                                                                {{ $adelanto->fecha_aprobacion ? \Carbon\Carbon::parse($adelanto->fecha_aprobacion)->format('d/m/Y H:i') : '-' }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Fecha de pago</label>
                                                            <div class="form-control bg-light">
                                                                {{ $adelanto->fecha_pago ? \Carbon\Carbon::parse($adelanto->fecha_pago)->format('d/m/Y') : '-' }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label fw-semibold">Método de pago</label>
                                                            <div class="form-control bg-light">
                                                                {{ $adelanto->metodo_pago ?? '-' }}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="form-label fw-semibold">Comprobante de pago</label>
                                                            <div class="form-control bg-light">
                                                                @if($adelanto->comprobante_pago)
                                                                    <a href="{{ asset('storage/' . $adelanto->comprobante_pago) }}" target="_blank">
                                                                        Ver comprobante
                                                                    </a>
                                                                @else
                                                                    No cargado
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label class="form-label fw-semibold">Detalle de cuotas registradas</label>
                                                            <div class="table-responsive">
                                                                <table class="table table-sm table-bordered align-middle mb-0">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Cuota</th>
                                                                            <th>Monto</th>
                                                                            <th>Fecha</th>
                                                                            <th>Comprobante</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                            $cuotas = $adelanto->movimientos->where('tipo', 'descuento')->values();
                                                                        @endphp

                                                                        @forelse($cuotas as $index => $cuota)
                                                                            <tr>
                                                                                <td>{{ $index + 1 }}</td>
                                                                                <td>${{ number_format($cuota->monto, 2, ',', '.') }}</td>
                                                                                <td>{{ $cuota->fecha ? \Carbon\Carbon::parse($cuota->fecha)->format('d/m/Y') : '-' }}</td>
                                                                                <td>
                                                                                    @if($cuota->comprobante_pago)
                                                                                        <a href="{{ asset('storage/' . $cuota->comprobante_pago) }}" target="_blank">
                                                                                            Ver comprobante
                                                                                        </a>
                                                                                    @else
                                                                                        -
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr>
                                                                                <td colspan="4" class="text-center text-muted">
                                                                                    Todavía no hay cuotas registradas.
                                                                                </td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- MODAL APROBAR --}}
                                    @if($adelanto->estado == 'pendiente')
                                        <div class="modal fade" id="modalAprobar{{ $adelanto->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form action="{{ route('rrhh.adelantos.aprobar', $adelanto->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title">Confirmar aprobación</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <p class="mb-2">¿Seguro que querés aprobar este adelanto?</p>
                                                            <ul class="mb-0">
                                                                <li><strong>Empleado:</strong> {{ $adelanto->empleado->nombre ?? '' }} {{ $adelanto->empleado->apellido ?? '' }}</li>
                                                                <li><strong>Monto:</strong> ${{ number_format($adelanto->monto_total, 0, ',', '.') }}</li>
                                                                <li><strong>Cuotas:</strong> {{ $adelanto->cuotas_total }}</li>
                                                            </ul>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">
                                                                Sí, aprobar
                                                            </button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                Cancelar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- MODAL RECHAZAR --}}
                                        <div class="modal fade" id="modalRechazar{{ $adelanto->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form action="{{ route('rrhh.adelantos.rechazar', $adelanto->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">Confirmar rechazo</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <p class="mb-2">¿Seguro que querés rechazar este adelanto?</p>
                                                            <ul class="mb-0">
                                                                <li><strong>Empleado:</strong> {{ $adelanto->empleado->nombre ?? '' }} {{ $adelanto->empleado->apellido ?? '' }}</li>
                                                                <li><strong>Monto:</strong> ${{ number_format($adelanto->monto_total, 0, ',', '.') }}</li>
                                                                <li><strong>Cuotas:</strong> {{ $adelanto->cuotas_total }}</li>
                                                            </ul>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-danger">
                                                                Sí, rechazar
                                                            </button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                Cancelar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- MODAL PAGAR --}}
                                    @if($adelanto->estado == 'aprobado')
                                        <div class="modal fade" id="modalPagar{{ $adelanto->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <form action="{{ route('rrhh.adelantos.pagar', $adelanto->id) }}"
                                                      method="POST"
                                                      enctype="multipart/form-data">
                                                    @csrf

                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">Registrar pago del adelanto</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Fecha de pago</label>
                                                                <input type="date" name="fecha_pago" class="form-control" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Método de pago</label>
                                                                <select name="metodo_pago" class="form-select" required>
                                                                    <option value="">Seleccionar</option>
                                                                    <option value="transferencia">Transferencia</option>
                                                                    <option value="efectivo">Efectivo</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Comprobante</label>
                                                                <input type="file" name="comprobante_pago" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">
                                                                Guardar pago
                                                            </button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                Cancelar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No hay solicitudes de adelanto registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#tablaAdelantos').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            order: [[0, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
            }
        });
    });
</script>
@endpush