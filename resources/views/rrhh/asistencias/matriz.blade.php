@extends('layouts.rrhh')

@section('content')

<div class="container-fluid py-4">

<a href="{{ route('rrhh.asistencias.index') }}"
   class="btn btn-outline-secondary rounded-pill mb-3">
    <i class="bi bi-arrow-left"></i> Volver
</a>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="fw-bold text-primary mb-0">
            <i class="bi bi-calendar3"></i> Matriz RRHH
        </h2>
        <small class="text-muted">Control mensual de asistencias, base y viajes</small>
    </div>
</div>

{{-- FILTROS GENERALES --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-3 align-items-end">

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Mes</label>
                    <select name="mes" class="form-select">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $mes == $m ? 'selected' : '' }}>
                                {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Año</label>
                    <select name="anio" class="form-select">
                        @for($a = now()->year - 2; $a <= now()->year + 2; $a++)
                            <option value="{{ $a }}" {{ $anio == $a ? 'selected' : '' }}>
                                {{ $a }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- LEYENDA --}}
<div class="d-flex gap-3 flex-wrap mb-4">
    <div class="legend-item"><span class="estado base">B</span> Base</div>
    <div class="legend-item"><span class="estado viaje">V</span> Viaje</div>
    <div class="legend-item"><span class="estado ausente">A</span> Ausente</div>
    <div class="legend-item"><span class="estado licencia">L</span> Licencia</div>
    <div class="legend-item"><span class="estado domingo">D</span> Domingo</div>
</div>

{{-- ================= MATRIZ BASE ================= --}}
<div class="mb-5">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-0 text-primary">
                <i class="bi bi-building"></i> Personal de Base
            </h4>
            <small class="text-muted">Empleados con tipo_empleado base</small>
        </div>

        <div style="max-width:300px;">
            <input type="text"
                   id="buscarBase"
                   class="form-control"
                   placeholder="Buscar empleado de base...">
        </div>
    </div>

    <div class="matriz-wrapper shadow rounded-4">

        <div class="empleados-fixed">
            <div class="empleado-header base-header">Empleado</div>

            <div id="empleadosBaseBody">
                @forelse($empleadosBase as $empleado)
                    <div class="empleado-row fila-base-empleado">
                        <div class="empleado-box">
                            <img src="{{ $empleado->foto_perfil
                                ? asset('storage/fotos_empleados/'.$empleado->foto_perfil)
                                : asset('img/default.png') }}"
                                class="avatar-mini">

                            <div>
                                <div class="fw-semibold">
                                    {{ $empleado->apellido }} {{ $empleado->nombre }}
                                </div>
                                <small class="text-muted">DNI: {{ $empleado->dni }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-muted">No hay empleados de base.</div>
                @endforelse
            </div>
        </div>

        <div class="dias-container">
    <div class="dias-scroll">

        <div class="dias-header">
            @for($dia = 1; $dia <= $diasMes; $dia++)
                @php
                    $fecha = Carbon\Carbon::create($anio, $mes, $dia);
                    $domingo = $fecha->isSunday();
                @endphp

                <div class="dia-box {{ $domingo ? 'domingo-header' : 'dia-header' }}">
                    <div class="dia-numero">{{ $dia }}</div>
                    <div class="dia-letra">
                        {{ strtoupper(substr($fecha->translatedFormat('D'), 0, 1)) }}
                    </div>
                </div>
            @endfor
        </div>

        <div id="diasBaseBody">
            @foreach($empleadosBase as $empleado)
                <div class="dias-row fila-base-dias">

                    @for($dia = 1; $dia <= $diasMes; $dia++)
                        @php
                            $dato = $matriz[$empleado->id][$dia] ?? ['estado' => 'ausente'];
                            $estado = $dato['estado'];
                        @endphp

                        <div class="dia-cell">

                            @if(in_array($estado, ['base', 'presente']))
                                <div class="estado base">B</div>

                            @elseif($estado == 'viaje')
                                <div class="estado viaje">V</div>

                            @elseif($estado == 'licencia')
                                <div class="estado licencia">L</div>

                            @elseif($estado == 'domingo')
                                <div class="estado domingo">D</div>

                            @else
                                <div class="estado ausente">A</div>
                            @endif

                        </div>
                    @endfor

                </div>
            @endforeach
        </div>

    </div>
</div>

    </div>

</div>

{{-- ================= MATRIZ OPERATIVA ================= --}}
<div class="mb-5">

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-0 text-success">
                <i class="bi bi-truck"></i> Operativo - Choferes / Mixtos
            </h4>
            <small class="text-muted">Muestra días en viaje y días trabajados en base</small>
        </div>

        <div style="max-width:300px;">
            <input type="text"
                   id="buscarOperativo"
                   class="form-control"
                   placeholder="Buscar chofer o mixto...">
        </div>
    </div>

    <div class="matriz-wrapper shadow rounded-4">

        <div class="empleados-fixed">
            <div class="empleado-header operativo-header">Empleado</div>

            <div id="empleadosOperativoBody">
                @forelse($empleadosOperativo as $empleado)
                    <div class="empleado-row fila-operativo-empleado">
                        <div class="empleado-box">
                            <img src="{{ $empleado->foto_perfil
                                ? asset('storage/fotos_empleados/'.$empleado->foto_perfil)
                                : asset('img/default.png') }}"
                                class="avatar-mini">

                            <div>
                                <div class="fw-semibold">
                                    {{ $empleado->apellido }} {{ $empleado->nombre }}
                                </div>
                                <small class="text-muted">
                                    DNI: {{ $empleado->dni }} |
                                    {{ ucfirst($empleado->tipo_empleado) }}
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-muted">No hay empleados operativos.</div>
                @endforelse
            </div>
        </div>

        <div class="dias-container">
            <div class="dias-scroll">

                <div class="dias-header">
                    @for($dia = 1; $dia <= $diasMes; $dia++)
                        @php
                            $fecha = Carbon\Carbon::create($anio, $mes, $dia);
                            $domingo = $fecha->isSunday();
                        @endphp

                        <div class="dia-box {{ $domingo ? 'domingo-header' : 'dia-header' }}">
                            <div class="dia-numero">{{ $dia }}</div>
                            <div class="dia-letra">{{ strtoupper(substr($fecha->translatedFormat('D'),0,1)) }}</div>
                        </div>
                    @endfor
                </div>

                <div id="diasOperativoBody">
                    @foreach($empleadosOperativo as $empleado)
                        <div class="dias-row fila-operativo-dias">

                            @for($dia = 1; $dia <= $diasMes; $dia++)
                                @php
                                    $dato = $matriz[$empleado->id][$dia] ?? ['estado' => 'ausente'];
                                    $estado = $dato['estado'];
                                @endphp

                                <div class="dia-cell">
                                    @if($estado == 'viaje')
                                        <div class="estado viaje">V</div>
                                    @elseif($estado == 'base' || $estado == 'presente')
                                        <div class="estado base">B</div>
                                    @elseif($estado == 'licencia')
                                        <div class="estado licencia">L</div>
                                    @elseif($estado == 'domingo')
                                        <div class="estado domingo">D</div>
                                    @else
                                        <div class="estado ausente">A</div>
                                    @endif
                                </div>
                            @endfor

                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>

</div>

</div>

<style>
body{
    background:#f4f6f9;
}

.matriz-wrapper{
    display:flex;
    background:#fff;
    overflow:hidden;
}

.empleados-fixed{
    width:300px;
    min-width:300px;
    background:#fff;
    border-right:2px solid #e5e7eb;
    z-index:10;
}

.empleado-header{
    height:60px;
    color:#fff;
    font-weight:bold;
    display:flex;
    align-items:center;
    justify-content:center;
}

.base-header{
    background:#2563eb;
}

.operativo-header{
    background:#16a34a;
}

.empleado-row{
    height:58px;
    border-bottom:1px solid #f1f5f9;
    padding:0 12px;
    display:flex;
    align-items:center;
    background:#fff;
}

.empleado-box{
    display:flex;
    align-items:center;
    gap:10px;
}

.avatar-mini{
    width:38px;
    height:38px;
    border-radius:50%;
    object-fit:cover;
}

.dias-container{
    flex:1;
    min-width:0;
    overflow:hidden;
}

.dias-scroll{
    overflow-x:auto;
    overflow-y:hidden;
    width:100%;
}

.dias-header{
    display:grid;
    grid-template-columns:repeat({{ $diasMes }}, 50px);
    width:max-content;
}

.dia-header{
    background:#3b82f6;
    color:#fff;
}

.domingo-header{
    background:#94a3b8;
    color:#fff;
}

.dia-box{
    height:60px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    border-right:1px solid rgba(255,255,255,0.1);
}

.dia-numero{
    font-size:13px;
    font-weight:bold;
}

.dia-letra{
    font-size:10px;
}

.dias-row{
    display:grid;
    grid-template-columns:repeat({{ $diasMes }}, 50px);
    width:max-content;
    height:58px;
    border-bottom:1px solid #f1f5f9;
}

.dia-cell{
    display:flex;
    align-items:center;
    justify-content:center;
}

.estado{
    width:28px;
    height:28px;
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:12px;
    font-weight:bold;
}

.base{
    background:#22c55e;
}

.viaje{
    background:#f97316;
}

.ausente{
    background:#ef4444;
}

.licencia{
    background:#3b82f6;
}

.domingo{
    background:#64748b;
}

.legend-item{
    display:flex;
    align-items:center;
    gap:8px;
}

.dias-scroll::-webkit-scrollbar{
    height:14px;
}

.dias-scroll::-webkit-scrollbar-track{
    background:#e5e7eb;
    border-radius:20px;
}

.dias-scroll::-webkit-scrollbar-thumb{
    background:#94a3b8;
    border-radius:20px;
}

@media(max-width:768px){
    .empleados-fixed{
        width:220px;
        min-width:220px;
    }

    .dias-header{
        grid-template-columns:repeat({{ $diasMes }}, 44px);
    }

    .dias-row{
        grid-template-columns:repeat({{ $diasMes }}, 44px);
    }
}
</style>

<script>
function activarBuscador(inputId, filasEmpleadoSelector, filasDiasSelector){
    const input = document.getElementById(inputId);

    if(!input) return;

    input.addEventListener('keyup', function(){

        let valor = this.value.toLowerCase();

        let filasIzquierda = document.querySelectorAll(filasEmpleadoSelector);
        let filasDerecha = document.querySelectorAll(filasDiasSelector);

        filasIzquierda.forEach((fila, index)=>{

            let texto = fila.innerText.toLowerCase();
            let visible = texto.includes(valor);

            fila.style.display = visible ? 'flex' : 'none';

            if(filasDerecha[index]){
                filasDerecha[index].style.display = visible ? 'grid' : 'none';
            }

        });
    });
}

activarBuscador('buscarBase', '.fila-base-empleado', '.fila-base-dias');
activarBuscador('buscarOperativo', '.fila-operativo-empleado', '.fila-operativo-dias');
</script>

@endsection