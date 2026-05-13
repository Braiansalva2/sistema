@extends('layouts.empleados')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="mb-3">Historial de licencias</h5>
 <a href="{{ route('empleado.licencias') }}" class="btn btn-secondary btn-sm">Volver</a>
 <br>
        @if($licencias->isEmpty())
            <div class="alert alert-info">
                No tenés licencias registradas.
            </div>
        @else

        {{-- 📱 MOBILE / MODERNO --}}
        @foreach($licencias as $l)
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">

                    {{-- HEADER --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-primary">
                            {{ ucfirst($l->tipo) }}
                        </strong>

                        @if($l->estado == 'pendiente')
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        @elseif($l->estado == 'aprobada')
                            <span class="badge bg-success">Aprobada</span>
                        @elseif($l->estado == 'rechazada')
                            <span class="badge bg-danger">Rechazada</span>
                        @endif
                    </div>

                    <hr class="my-2">

                    {{-- FECHA --}}
                    <div class="d-flex justify-content-between">
                        <span>📅 Fecha:</span>
                        <strong>
                            {{ $l->fecha_desde->format('d/m/Y') }}
                            @if($l->fecha_hasta)
                                - {{ $l->fecha_hasta->format('d/m/Y') }}
                            @endif
                        </strong>
                    </div>

                    {{-- DÍAS --}}
                    @if($l->dias)
                    <div class="d-flex justify-content-between mt-2">
                        <span>📊 Días:</span>
                        <strong>{{ $l->dias }}</strong>
                    </div>
                    @endif

                    {{-- HORAS --}}
                    @if($l->horas)
                    <div class="d-flex justify-content-between mt-2">
                        <span>⏱ Horas:</span>
                        <strong>{{ $l->horas }} min</strong>
                    </div>
                    @endif
                    @if($l->observaciones)
                        <div class="mt-2 p-2 bg-light rounded">
                            <small class="text-muted">📝 Observaciones</small>
                            <div class="fw-semibold">
                                {{ $l->observaciones }}
                            </div>
                        </div>
                    @endif                    
                    {{-- ARCHIVO --}}
                    @if($l->archivo)
                    <div class="mt-2">
                        <a href="{{ asset('storage/'.$l->archivo) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                         Ver certificado
                        </a>
                    </div>
                    @endif

                    {{-- SOLICITUD --}}
                    <div class="mt-2 text-muted">
                        Solicitud: {{ $l->created_at->format('d/m/Y') }}
                    </div>

                </div>
            </div>
        @endforeach

        {{-- PAGINACIÓN --}}
        <div class="mt-3">
            {{ $licencias->links() }}
        </div>

        @endif

    </div>
</div>

@endsection 