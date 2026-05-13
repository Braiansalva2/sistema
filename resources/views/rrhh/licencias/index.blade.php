@extends('layouts.rrhh')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">📄 Gestión de Licencias</h4>
    </div>

    {{-- FILTROS --}}
    <div class="card filtro-card mb-4">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-6">
                    <input type="text" id="buscador" class="form-control"
                        placeholder="🔍 Buscar empleado o tipo...">
                </div>

                <div class="col-md-3">
                    <select id="filtroEstado" class="form-select">
                        <option value="">Todos</option>
                        <option value="pendiente">Pendientes</option>
                        <option value="aprobada">Aprobadas</option>
                        <option value="rechazada">Rechazadas</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- CARDS --}}
    <div class="row">
        @forelse($licencias as $l)

        @php
            $horasTexto = '-';

            if (is_numeric($l->horas) && $l->horas > 0) {
                $totalMinutos = (int) $l->horas;
                $horasParte = intdiv($totalMinutos, 60);
                $minutosParte = $totalMinutos % 60;
                $horasTexto = ($horasParte > 0 ? $horasParte . 'h ' : '') . $minutosParte . 'm';
            }

            $horarioTexto = '-';
            if ($l->hora_desde && $l->hora_hasta) {
                $horarioTexto = \Carbon\Carbon::parse($l->hora_desde)->format('H:i') . ' → ' . \Carbon\Carbon::parse($l->hora_hasta)->format('H:i');
            }
        @endphp

        <div class="col-lg-6 mb-4 licencia-item"
            data-nombre="{{ strtolower($l->empleado->nombre . ' ' . $l->empleado->apellido) }}"
            data-tipo="{{ strtolower($l->tipo) }}"
            data-estado="{{ $l->estado }}">

            <div class="card licencia-card h-100">

                {{-- HEADER --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold">
                            <i class="bi bi-person-fill me-1 text-primary"></i>
                            {{ $l->empleado->nombre }} {{ $l->empleado->apellido }}
                        </div>
                        <small class="text-muted">
                            Tipo de Licencia: {{ ucfirst($l->tipo) }}
                        </small>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="button"
                            class="btn btn-detalle btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalDetalleLicencia{{ $l->id }}"
                            title="Ver detalle">
                            <i class="bi bi-eye"></i>
                        </button>

                        <span class="estado {{ $l->estado }}">
                            {{ ucfirst($l->estado) }}
                        </span>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="card-body">

                    <div class="row text-center mb-3 g-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">📅 Fecha</small>
                            <div class="dato">
                                {{ $l->fecha_desde ? $l->fecha_desde->format('d/m/Y') : '-' }}
                                @if($l->fecha_hasta)
                                    <br>
                                    <span class="text-muted small">→ {{ $l->fecha_hasta->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">📊 Días</small>
                            <div class="dato">{{ $l->dias ?? '-' }}</div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">⏱ Tiempo</small>
                            <div class="dato">{{ $horasTexto }}</div>
                        </div>
                    </div>

                    @if($l->tipo === 'ordinaria' && $l->hora_desde && $l->hora_hasta)
                        <div class="info-extra mb-3">
                            <small class="text-muted d-block">🕒 Horario solicitado</small>
                            <div class="fw-semibold">{{ $horarioTexto }}</div>
                        </div>
                    @endif

                    @if($l->observaciones)
                        <div class="info-extra mb-3">
                            <small class="text-muted d-block">📝 Motivo / Observaciones</small>
                            <div class="fw-semibold">{{ $l->observaciones }}</div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            Solicitud: {{ $l->created_at ? $l->created_at->format('d/m/Y') : '-' }}
                        </small>

                        @if($l->archivo)
                            <a href="{{ asset('storage/' . $l->archivo) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-paperclip"></i> Certificado
                            </a>
                        @endif
                    </div>

                </div>

                {{-- FOOTER --}}
                @if($l->estado == 'pendiente')
                    <div class="card-footer text-center bg-white border-0 pt-0 pb-3">
                        <button class="btn btn-aprobar"
                            onclick="confirmarAccion('aprobar', '{{ route('rrhh.licencias.aprobar', $l->id) }}')">
                            <i class="bi bi-check-circle me-1"></i> Aprobar
                        </button>

                        <button class="btn btn-rechazar"
                            onclick="confirmarAccion('rechazar', '{{ route('rrhh.licencias.rechazar', $l->id) }}')">
                            <i class="bi bi-x-circle me-1"></i> Rechazar
                        </button>
                    </div>
                @endif

            </div>
        </div>

        {{-- MODAL DETALLE --}}
        <div class="modal fade" id="modalDetalleLicencia{{ $l->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content modal-detalle">

                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold mb-1">
                                <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                                Detalle de Licencia
                            </h5>
                            <small class="text-muted">
                                {{ $l->empleado->nombre }} {{ $l->empleado->apellido }}
                            </small>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body pt-3">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Empleado</small>
                                    <strong>{{ $l->empleado->nombre }} {{ $l->empleado->apellido }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Tipo de licencia</small>
                                    <strong>{{ ucfirst($l->tipo) }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Estado</small>
                                    <span class="estado {{ $l->estado }}">
                                        {{ ucfirst($l->estado) }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Fecha de solicitud</small>
                                    <strong>{{ $l->created_at ? $l->created_at->format('d/m/Y H:i') : '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Fecha desde</small>
                                    <strong>{{ $l->fecha_desde ? $l->fecha_desde->format('d/m/Y') : '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Fecha hasta</small>
                                    <strong>{{ $l->fecha_hasta ? $l->fecha_hasta->format('d/m/Y') : '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Días</small>
                                    <strong>{{ $l->dias ?? '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Tiempo total</small>
                                    <strong>{{ $horasTexto }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Hora desde</small>
                                    <strong>
                                        {{ $l->hora_desde ? \Carbon\Carbon::parse($l->hora_desde)->format('H:i') : '-' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Hora hasta</small>
                                    <strong>
                                        {{ $l->hora_hasta ? \Carbon\Carbon::parse($l->hora_hasta)->format('H:i') : '-' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Motivo / Observaciones</small>
                                    <strong>{{ $l->observaciones ?: '-' }}</strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Fecha de aprobación</small>
                                    <strong>
                                        {{ $l->fecha_aprobacion ? \Carbon\Carbon::parse($l->fecha_aprobacion)->format('d/m/Y') : '-' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="detalle-box">
                                    <small class="text-muted d-block">Aprobado por</small>
                                    <strong>{{ $l->aprobador->name ?? '-' }}</strong>
                                </div>
                            </div>

                            @if($l->archivo)
                                <div class="col-12">
                                    <div class="detalle-box text-center">
                                        <a href="{{ asset('storage/' . $l->archivo) }}"
                                           target="_blank"
                                           class="btn btn-outline-secondary">
                                            <i class="bi bi-paperclip me-1"></i>
                                            Ver certificado adjunto
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                    </div>

                </div>
            </div>
        </div>

        @empty
            <div class="alert alert-info">No hay licencias registradas</div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $licencias->links() }}
    </div>

</div>

{{-- MODAL CONFIRMACION --}}
<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-detalle">
            <div class="modal-body text-center p-4">
                <h5 id="mensajeModal" class="mb-3"></h5>

                <form id="formAccion" method="POST" class="mt-3">
                    @csrf
                    <button class="btn btn-primary">Confirmar</button>
                </form>

                <button class="btn btn-secondary mt-2" data-bs-dismiss="modal">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let buscador = document.getElementById('buscador');
    let filtroEstado = document.getElementById('filtroEstado');

    buscador.addEventListener('keyup', filtrar);
    filtroEstado.addEventListener('change', filtrar);

    function filtrar() {
        let texto = buscador.value.toLowerCase();
        let estado = filtroEstado.value;

        document.querySelectorAll('.licencia-item').forEach(item => {
            let nombre = item.dataset.nombre;
            let tipo = item.dataset.tipo;
            let est = item.dataset.estado;

            let okTexto = nombre.includes(texto) || tipo.includes(texto);
            let okEstado = estado === '' || estado === est;

            item.style.display = (okTexto && okEstado) ? 'block' : 'none';
        });
    }
});

function confirmarAccion(tipo, url) {
    let mensaje = tipo === 'aprobar'
        ? '¿Aprobar esta licencia?'
        : '¿Rechazar esta licencia?';

    document.getElementById('mensajeModal').innerText = mensaje;
    document.getElementById('formAccion').action = url;

    let modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    modal.show();
}
</script>

<style>
.licencia-card {
    border-radius: 14px;
    border: none;
    transition: all 0.2s ease;
    background: #fff;
}

.licencia-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
}

.filtro-card {
    border-radius: 12px;
    border: none;
}

.card-header {
    background: #fff;
    border-bottom: 1px solid #ececec;
    border-radius: 14px 14px 0 0 !important;
}

.estado {
    padding: 7px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
}

.estado.pendiente {
    background: #ffe08a;
    color: #7a5d00;
}

.estado.aprobada {
    background: #c7f7d4;
    color: #166534;
}

.estado.rechazada {
    background: #ffc9c9;
    color: #991b1b;
}

.dato {
    font-weight: 700;
    font-size: 18px;
    color: #212529;
}

.info-extra {
    background: #f8f9fa;
    border-left: 4px solid #e37c45;
    border-radius: 10px;
    padding: 10px 12px;
}

.btn-aprobar {
    background: #22c55e;
    color: white;
    border-radius: 10px;
    padding: 7px 14px;
    border: none;
}

.btn-aprobar:hover {
    background: #16a34a;
    color: white;
}

.btn-rechazar {
    background: #ef4444;
    color: white;
    border-radius: 10px;
    padding: 7px 14px;
    border: none;
}

.btn-rechazar:hover {
    background: #dc2626;
    color: white;
}

.btn-detalle {
    background: #f3f4f6;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    color: #374151;
}

.btn-detalle:hover {
    background: #e5e7eb;
    color: #111827;
}

.modal-detalle {
    border-radius: 16px;
    border: none;
}

.detalle-box {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 12px;
    height: 100%;
}
</style>
@endpush