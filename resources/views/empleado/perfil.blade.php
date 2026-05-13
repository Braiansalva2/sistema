@extends('layouts.empleados')

@section('content')

<div class="container py-3">

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            {{-- HEADER --}}
            <div class="d-flex align-items-center mb-4">
                <img src="{{ $empleado->foto_perfil 
                    ? asset('storage/fotos_empleados/' . $empleado->foto_perfil) 
                    : asset('img/default.png') }}"
                    class="rounded-circle me-3"
                    width="90"
                    height="90"
                    style="object-fit: cover;">

                <div>
                    <h4 class="mb-1">
                        {{ $empleado->nombre }} {{ $empleado->apellido }}
                    </h4>
                    <small class="text-muted d-block">
                        DNI: {{ $empleado->dni }}
                    </small>
                    <small class="text-muted">
                        {{ $empleado->rolPuesto->nombre ?? 'Empleado' }}
                    </small>
                </div>
            </div>

            <hr>

            {{-- DATOS RÁPIDOS --}}
            <div class="row mb-3">
                <div class="col-6">
                    <small class="text-muted">📱 Teléfono</small>
                    <div class="fw-semibold">{{ $empleado->telefono_oculto }}</div>
                </div>

                <div class="col-6">
                    <small class="text-muted">📧 Email</small>
                    <div class="fw-semibold">{{ $empleado->email_oculto }}</div>
                </div>
            </div>

            {{-- ACORDEÓN --}}
            <div class="accordion" id="perfilAccordion">

                {{-- DATOS PERSONALES --}}
                <div class="accordion-item border-0 mb-2">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded-3"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#datosPersonales">
                            👤 Datos personales
                        </button>
                    </h2>

                    <div id="datosPersonales"
                         class="accordion-collapse collapse"
                         data-bs-parent="#perfilAccordion">

                        <div class="accordion-body">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Dirección</label>
                                    <div class="form-control bg-light">
                                        {{ $empleado->direccion_oculta }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Fecha de nacimiento</label>
                                    <div class="form-control bg-light">
                                        {{ $empleado->fecha_nacimiento_oculta }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Estado civil</label>
                                    <div class="form-control bg-light">
                                        {{ $empleado->estado_civil ?? 'No registrado' }}
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                {{-- INFO LABORAL (POR SI DESPUÉS QUERÉS CRECER) --}}
                <div class="accordion-item border-0">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed rounded-3"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#infoLaboral">
            🏢 Información laboral
        </button>
    </h2>

    <div id="infoLaboral"
         class="accordion-collapse collapse"
         data-bs-parent="#perfilAccordion">

        <div class="accordion-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="fw-bold">Puesto</label>
                    <div class="form-control bg-light">
                        {{ $empleado->rolPuesto->nombre ?? 'Empleado' }}
                    </div>
                </div>

            </div>

            <hr>

            {{-- TALLES --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Talles de indumentaria</h6>

                <button class="btn btn-sm btn-outline-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTalles">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>

            <div class="row">

                @foreach($talles as $tipo)

                    @php
                        $talleEmpleado = $empleadoTalles[$tipo->id]->talle->nombre ?? 'No definido';
                    @endphp

                    <div class="col-md-3 mb-2">
                        <div class="border rounded p-2 text-center bg-light">
                            <small class="text-muted d-block">{{ $tipo->nombre }}</small>
                            <strong>{{ $talleEmpleado }}</strong>
                        </div>
                    </div>

                @endforeach

            </div>

        </div>
    </div>
</div>

            </div>
<div class="modal fade" id="modalTalles" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Editar talles</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="{{ route('empleado.talles.guardar') }}">
        @csrf

        <div class="modal-body">

            @foreach($talles as $tipo)

                @php
                    $talleActual = $empleadoTalles[$tipo->id]->tipo_prenda_talle_id ?? null;
                @endphp

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ $tipo->nombre }}</label>

                    <select name="talles[{{ $tipo->id }}]" class="form-select">

                        <option value="">Seleccionar</option>

                        @foreach($tipo->talles as $talle)
                            <option value="{{ $talle->id }}"
                                {{ $talleActual == $talle->id ? 'selected' : '' }}>
                                {{ $talle->nombre }}
                            </option>
                        @endforeach

                    </select>
                </div>

            @endforeach

        </div>

        <div class="modal-footer">
            <button class="btn btn-primary">Guardar</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>

      </form>

    </div>
  </div>
</div>
            {{-- BOTONES --}}
            <div class="mt-4">
                <a href="{{ route('empleado.index') }}" class="btn btn-secondary w-100">
                    Volver
                </a>
            </div>

        </div>
    </div>

</div>

@endsection