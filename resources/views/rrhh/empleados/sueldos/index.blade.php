@extends('layouts.rrhh')

@section('content')
<div class="container">

   <div class="d-flex justify-content-between align-items-center mb-4">

    {{-- 👤 EMPLEADO --}}
    <div class="d-flex align-items-center">

        <img src="{{ $empleado->foto_perfil 
            ? asset('storage/fotos_empleados/'.$empleado->foto_perfil) 
            : asset('img/default.png') }}"
            width="80"
            height="80"
            class="rounded-circle me-3">

        <div>
            <h4 class="mb-0">
                {{ $empleado->nombre }} {{ $empleado->apellido }}
            </h4>
            <small class="text-muted">
                DNI: {{ $empleado->dni }}
            </small>
        </div>

    </div>

    {{--BOTONES --}}
    <div class="d-flex gap-2">

        <a href="#" 
           class="btn btn-warning btn-sm"
           data-bs-toggle="modal"
           data-bs-target="#modalSueldo">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                 <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
            </svg>
                Configurar Sueldo
        </a>
    </div>

</div>

    {{-- SUELDO BASE --}}
    <div class="card mb-3">
        <div class="card-body">

            <h5 class="mb-2">Sueldo Base</h5>

            <h4 class="text-primary">
                ${{ number_format($sueldo->sueldo_base ?? 0, 2) }}
            </h4>

            <small class="text-muted">
                Desde: {{ $sueldo->fecha_desde ?? '-' }}
            </small>

        </div>
    </div>
<div class="card mt-3 shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">💰 Adelantos</h5>

            <a href="{{ route('rrhh.adelantos.index') }}" class="btn btn-sm btn-outline-primary">
                Ver todos
            </a>
        </div>

@forelse($empleado->adelantos->sortByDesc('created_at')->take(4) as $adelanto)

    @php
        $pagadas = $adelanto->movimientos->where('tipo', 'descuento')->count();
        $restantes = $adelanto->cuotas_total - $pagadas;
        $valorCuota = $adelanto->cuotas_total > 0 ? $adelanto->monto_total / $adelanto->cuotas_total : 0;
    @endphp

    <div class="border rounded p-3 mb-2 bg-light">
        <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div>
                <strong>${{ number_format($adelanto->monto_total, 0, ',', '.') }}</strong>
                <br>
                <small class="text-muted">
                    Solicitud:
                    {{ $adelanto->fecha_solicitud ? \Carbon\Carbon::parse($adelanto->fecha_solicitud)->format('d/m/Y') : '-' }}
                </small>
            </div>

            <div class="mt-2 mt-md-0">
                <span class="badge bg-{{
                    $adelanto->estado == 'aprobado' ? 'success' :
                    ($adelanto->estado == 'pendiente' ? 'warning text-dark' :
                    ($adelanto->estado == 'rechazado' ? 'danger' : 'primary'))
                }}">
                    {{ ucfirst($adelanto->estado) }}
                </span>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-md-4">
                <small class="text-muted">Cuotas</small><br>
                <strong>{{ $pagadas }} / {{ $adelanto->cuotas_total }}</strong>
            </div>

            <div class="col-md-4">
                <small class="text-muted">Restantes</small><br>
                @if($restantes > 0)
                    <span class="text-danger fw-semibold">{{ $restantes }}</span>
                @else
                    <span class="text-success fw-semibold">Saldado</span>
                @endif
            </div>

            <div class="col-md-4">
                <small class="text-muted">Valor cuota</small><br>
                <strong>${{ number_format($valorCuota, 2, ',', '.') }}</strong>
            </div>
        </div>
    </div>

@empty
    <div class="alert alert-light border mb-0">
        No tiene adelantos registrados.
    </div>
@endforelse

    </div>
</div><br>
    {{-- RESUMEN --}}
    <div class="row mb-3">

        <div class="col-md-4">
            <div class="alert alert-success">
                <strong>Ingresos:</strong><br>
                ${{ number_format($ingresos, 2) }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="alert alert-danger">
                <strong>Descuentos:</strong><br>
                ${{ number_format($descuentos, 2) }}
            </div>
        </div>

        <div class="col-md-4">
            <div class="alert alert-primary">
                <strong>Neto:</strong><br>
                ${{ number_format($neto, 2) }}
            </div>
        </div>

    </div>

    {{--  BOTÓN --}}
    <button class="btn btn-success mb-3"
        data-bs-toggle="modal"
        data-bs-target="#modalMovimiento">
    ➕ Nuevo Movimiento
    </button>

    {{--  TABLA MOVIMIENTOS --}}
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th width="120">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse($movimientos as $m)
                <tr>

                    <td>
                        <span class="badge bg-info">
                            {{ ucfirst(str_replace('_',' ', $m->tipo)) }}
                        </span>
                    </td>

                    <td class="{{ in_array($m->tipo, ['descuento','anticipo']) ? 'text-danger' : 'text-success' }}">
                        <strong>${{ number_format($m->monto, 2) }}</strong>
                    </td>

                    <td>{{ $m->fecha }}</td>

                   <td>
    {{ $m->descripcion }}

    @if($m->adelanto_id)
        @php
            $adelanto = $m->adelanto;
        @endphp

        <br>
        <small class="text-muted">
            💰 Adelanto: ${{ number_format($adelanto->monto_total, 0, ',', '.') }}
        </small>

        <br>
        <small class="text-muted">
            📅 Pedido: {{ $adelanto->fecha_solicitud }}
        </small>

        <br>
        <small class="text-muted">
            📊 Cuota {{ $adelanto->movimientos->where('tipo','descuento')->count() }} / {{ $adelanto->cuotas_total }}
        </small>
    @endif
</td>

                    <td>
                        @role('Admin')
                            <button class="btn btn-warning btn-sm">✏️</button>
                            <button class="btn btn-danger btn-sm">🗑️</button>
                        @endrole
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        No hay movimientos registrados
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>

</div>

{{-- MODAL --}}
<div class="modal fade" id="modalMovimiento" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('rrhh.movimientos.store', $empleado->id) }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Nuevo Movimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Tipo</label>
                        <select name="tipo" class="form-control" required>
                            <option value="">Seleccionar</option>
                            <option value="hora_extra">Hora Extra</option>
                            {{-- <option value="viatico">Viático</option> --}}
                            <option value="anticipo">Anticipo</option>
                            <option value="descuento">Descuento</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Monto</label>
                        <input type="number" step="0.01" name="monto" class="form-control" required>
                    </div>

                    {{-- <div class="mb-3">
                        <label>Cantidad (opcional)</label>
                        <input type="number" name="cantidad" class="form-control">
                    </div> --}}  

                    <div class="mb-3">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
<!-- MODAL SUELDO -->
<div class="modal fade" id="modalSueldo" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('rrhh.sueldos.store', $empleado->id) }}" method="POST">
            @csrf

            <div class="modal-content">
                
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Configurar Sueldo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- SUELDO BASE -->
                    <div class="mb-3">
                        <label>Sueldo Base</label>
                        <input type="number" step="0.01" name="sueldo_base" 
                               class="form-control" required>
                    </div>

                    <!-- VALOR HORA -->
                    <div class="mb-3">
                        <label>Valor Hora</label>
                        <input type="number" step="0.01" name="valor_hora" 
                               class="form-control">
                    </div>

                    <!-- PORCENTAJE -->
                    <div class="mb-3">
                        <label>% Hora Extra</label>
                        <input type="number" step="0.01" name="porcentaje_hora_extra" 
                               class="form-control" value="1.5">
                    </div>

                    <!-- FECHA -->
                    <div class="mb-3">
                        <label>Fecha Desde</label>
                        <input type="date" name="fecha_desde" 
                               class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection