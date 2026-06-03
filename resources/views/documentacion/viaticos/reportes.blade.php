@extends('layouts.documentacion')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            📊 Estadísticas de Viáticos
        </h2>
    </div>



<div class="card shadow-sm border-0 mt-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">
            <i class="bi bi-graph-up"></i>
            Evolución de viáticos por chofer
        </h5>
    </div>

    <div class="card-body">

        <form method="GET"
              action="{{ route('documentacion.viaticos.reportes') }}"
              class="row g-3 mb-4">

            <div class="col-md-3">
                <label>Desde</label>
                <input type="month"
                       name="desde"
                       class="form-control"
                       value="{{ request('desde') }}">
            </div>

            <div class="col-md-3">
                <label>Hasta</label>
                <input type="month"
                       name="hasta"
                       class="form-control"
                       value="{{ request('hasta') }}">
            </div>

            <div class="col-md-4">
                <label>Choferes</label>

                <select name="choferes[]"
                        class="form-select"
                        multiple>

                    @foreach($empleados as $empleado)

                        <option value="{{ $empleado->id }}"
                            {{ collect(request('choferes'))->contains($empleado->id) ? 'selected' : '' }}>

                            {{ $empleado->apellido }}
                            {{ $empleado->nombre }}

                        </option>

                    @endforeach

                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">
                    Filtrar
                </button>
            </div>

        </form>

        <canvas id="graficoViaticos"
                height="100"></canvas>

    </div>
</div>








    {{-- FILTROS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-3">
                        <label>Mes</label>

                        <select name="mes" class="form-control">

                            @for($i=1; $i<=12; $i++)

                                <option value="{{ $i }}"
                                    {{ $mes == $i ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}

                                </option>

                            @endfor

                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Año</label>

                        <select name="anio" class="form-control">

                            @for($i = now()->year; $i >= 2024; $i--)

                                <option value="{{ $i }}"
                                    {{ $anio == $i ? 'selected' : '' }}>

                                    {{ $i }}

                                </option>

                            @endfor

                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-dark w-100">
                            🔎 Filtrar
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- CARDS --}}
    <div class="row">

        <div class="col-md-4 mb-4">
            <div class="card shadow border-0 bg-success text-white">
                <div class="card-body">

                    <h5>Total Gastado</h5>

                    <h2>
                        ${{ number_format($totalGeneral, 2) }}
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow border-0 bg-primary text-white">
                <div class="card-body">

                    <h5>Cantidad de Viáticos</h5>

                    <h2>{{ $cantidadViaticos }}</h2>

                </div>
            </div>
        </div>

    </div>

    {{-- TOP CHOFERES --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            🏆 Choferes con más gastos
        </div>

        <div class="card-body">

            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>Chofer</th>
                        <th>Total Gastado</th>
                        <th>Cantidad Viáticos</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($topChoferes as $c)

                    <tr>

                        <td>
                            {{ $c->empleado->nombre ?? '' }}
                            {{ $c->empleado->apellido ?? '' }}
                        </td>

                        <td class="text-success fw-bold">
                            ${{ number_format($c->total_gastado, 2) }}
                        </td>

                        <td>
                            {{ $c->cantidad }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

    {{-- GASTOS POR CONCEPTO --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-warning">
            🍽️ Gastos por concepto
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($gastosConcepto as $g)

                    <tr>

                        <td>{{ $g->concepto }}</td>

                        <td class="fw-bold">
                            ${{ number_format($g->total, 2) }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    {{-- DESTINOS --}}
    <div class="card shadow-sm">

        <div class="card-header bg-info text-white">
            📍 Destinos más frecuentes
        </div>

        <div class="card-body">

            <table class="table">

                <thead>
                    <tr>
                        <th>Destino</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($destinos as $d)

                    <tr>

                        <td>{{ $d->destino }}</td>

                        <td>{{ $d->total }}</td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>








<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('graficoViaticos');

new Chart(ctx, {

    type: 'line',

    data: {

        labels: @json($meses),

        datasets: @json($datasets)
    },

    options: {

        responsive: true,

        interaction: {

            mode: 'index',
            intersect: false
        },

        stacked: false,

        plugins: {

            legend: {

                position: 'top'
            }
        },

        scales: {

            y: {

                beginAtZero: true
            }
        }
    }
});
</script>
@endsection