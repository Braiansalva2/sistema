@extends('layouts.empleados')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Mis licencias</h4>
        <a href="{{ route('empleado.index') }}" class="btn btn-secondary btn-sm">Volver</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige los siguientes errores:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- DATOS DEL EMPLEADO --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="mb-3">Datos del empleado</h5>

            <div class="row">
                <div class="col-md-4 mb-2">
                    <strong>Nombre:</strong><br>
                    {{ $empleado->nombre }} {{ $empleado->apellido }}
                </div>

                <div class="col-md-4 mb-2">
                    <strong>DNI:</strong><br>
                    {{ $empleado->dni }}
                </div>

                <div class="col-md-4 mb-2">
                    <strong>Puesto:</strong><br>
                    {{ $empleado->rolPuesto->nombre ?? 'Empleado' }}
                </div>
            </div>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="mb-3">Solicitar vacaciones</h5>

            <form action="{{ route('empleado.vacaciones.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha inicio</label>
                        <input type="date"
                               name="fecha_inicio"
                               id="fecha_inicio"
                               class="form-control"
                               value="{{ old('fecha_inicio') }}"
                               onchange="calcularDias()"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha fin</label>
                        <input type="date"
                               name="fecha_fin"
                               id="fecha_fin"
                               class="form-control"
                               value="{{ old('fecha_fin') }}"
                               onchange="calcularDias()"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="dias_calculados" class="form-label">Cantidad de días</label>
                        <input type="text"
                               id="dias_calculados"
                               class="form-control bg-light"
                               readonly>
                    </div>
                </div>

                {{-- <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea name="observaciones"
                              id="observaciones"
                              rows="3"
                              class="form-control"
                              placeholder="Opcional">{{ old('observaciones') }}</textarea>
                </div> --}}

                <button type="submit" class="btn btn-primary">
                    Enviar solicitud
                </button>
            </form>
        </div>
    </div>

   {{-- HISTORIAL --}}
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="mb-3">Historial de solicitudes</h5>

        @if($vacaciones->isEmpty())
            <div class="alert alert-info mb-0">
                Todavía no tenés solicitudes registradas.
            </div>
        @else

        {{-- 📱 MOBILE (cards) --}}
        <div class="d-md-none">
            @foreach($vacaciones as $vacacion)
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex justify-content-between">
                            <strong>{{ $vacacion->fecha_inicio->format('d/m/Y') }}</strong>
                            <span class="text-muted">a</span>
                            <strong>{{ $vacacion->fecha_fin->format('d/m/Y') }}</strong>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between">
                            <span>Días:</span>
                            <strong>{{ $vacacion->dias_tomados }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span>Estado:</span>
                            @if($vacacion->estado == 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif($vacacion->estado == 'aprobadas')
                                <span class="badge bg-success">Aprobada</span>
                            @elseif($vacacion->estado == 'rechazadas')
                                <span class="badge bg-danger">Rechazada</span>
                            @elseif($vacacion->estado == 'finalizadas')
                                <span class="badge bg-primary">Finalizada</span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span>Aprobación:</span>
                            <span>
                                {{ $vacacion->fecha_aprobacion 
                                    ? $vacacion->fecha_aprobacion->format('d/m/Y') 
                                    : '-' }}
                            </span>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- 💻 DESKTOP (tabla) --}}
        <div class="table-responsive d-none d-md-block">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Días</th>
                        <th>Estado</th>
                        <th>Fecha aprobación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vacaciones as $vacacion)
                        <tr>
                            <td>{{ $vacacion->fecha_inicio->format('d/m/Y') }}</td>
                            <td>{{ $vacacion->fecha_fin->format('d/m/Y') }}</td>
                            <td>{{ $vacacion->dias_tomados }}</td>
                            <td>
                                @if($vacacion->estado == 'pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($vacacion->estado == 'aprobadas')
                                    <span class="badge bg-success">Aprobada</span>
                                @elseif($vacacion->estado == 'rechazadas')
                                    <span class="badge bg-danger">Rechazada</span>
                                @elseif($vacacion->estado == 'finalizadas')
                                    <span class="badge bg-primary">Finalizada</span>
                                @endif
                            </td>
                            <td>
                                {{ $vacacion->fecha_aprobacion 
                                    ? $vacacion->fecha_aprobacion->format('d/m/Y') 
                                    : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @endif
    </div>
</div>

</div>

<script>
    function calcularDias() {
        const inicio = document.getElementById('fecha_inicio').value;
        const fin = document.getElementById('fecha_fin').value;
        const campoDias = document.getElementById('dias_calculados');

        if (inicio && fin) {
            const fechaInicio = new Date(inicio);
            const fechaFin = new Date(fin);

            if (fechaFin >= fechaInicio) {
                const diferencia = fechaFin - fechaInicio;
                const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24)) + 1;
                campoDias.value = dias + ' día(s)';
            } else {
                campoDias.value = 'Rango inválido';
            }
        } else {
            campoDias.value = '';
        }
    }

    calcularDias();
</script>

@endsection