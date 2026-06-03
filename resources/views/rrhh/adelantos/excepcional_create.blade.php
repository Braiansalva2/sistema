@extends('layouts.rrhh')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold">
                Adelanto Excepcional
            </h2>

            <p class="text-muted mb-0">
                Crear adelantos manuales para empleados.
            </p>
        </div>

        <a href="{{ route('rrhh.adelantos.index') }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Volver

        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-header bg-success text-white">

            <h5 class="mb-0">

                Datos del adelanto

            </h5>

        </div>

        <div class="card-body">

            <form
                action="{{ route('rrhh.adelantos.excepcional.store') }}"
                method="POST">

                @csrf

               <div class="row">

    <div class="col-md-6">

        <label class="form-label fw-bold">
            Buscar empleado
        </label>

        <input
            type="text"
            id="buscarEmpleado"
            class="form-control"
            placeholder="Buscar por nombre o DNI">

        <div
            id="resultadoBusqueda"
            class="list-group mt-2">
        </div>

        <input
            type="hidden"
            name="empleado_id"
            id="empleadoSeleccionado">

    </div>

    <div class="col-md-6">

        <div
            id="resumenEmpleado"
            style="display:none">

            <div class="card border-primary">

                <div class="card-header bg-primary text-white">

                    Información del empleado

                </div>

                <div
                    class="card-body"
                    id="contenidoResumen">

                </div>

            </div>

        </div>

    </div>

</div>

                {{-- RESUMEN DEL EMPLEADO --}}
                <div id="resumenEmpleado"
                     style="display:none">

                    <div class="alert alert-info">

                        <div id="contenidoResumen"></div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Monto

                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="monto_total"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Cantidad de cuotas

                        </label>

                        <input
                            type="number"
                            name="cuotas_total"
                            min="1"
                            max="24"
                            class="form-control"
                            required>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Motivo

                    </label>

                    <textarea
                        name="motivo"
                        rows="4"
                        class="form-control"
                        required></textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Observación RRHH

                    </label>

                    <textarea
                        name="observacion_rrhh"
                        rows="3"
                        class="form-control"></textarea>

                </div>

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="bi bi-check-circle"></i>
                    Crear adelanto

                </button>

            </form>

        </div>

    </div>

</div>


<script>

document
.getElementById('buscarEmpleado')
.addEventListener('keyup', function(){

    let texto = this.value;

    if(texto.length < 2){

        document
            .getElementById('resultadoBusqueda')
            .innerHTML = '';

        return;
    }

    fetch(
        "{{ route('rrhh.adelantos.empleados.buscar') }}"
        + "?buscar=" + texto
    )

    .then(res => res.json())

    .then(data => {

        let html = '';

        data.forEach(emp => {

            html += `

                <button
                    type="button"
                    class="list-group-item list-group-item-action"
                    onclick="seleccionarEmpleado(
                        ${emp.id}
                    )">

                    ${emp.apellido}
                    ${emp.nombre}

                    - DNI:
                    ${emp.dni}

                </button>

            `;
        });

        document
            .getElementById('resultadoBusqueda')
            .innerHTML = html;

    });

});


function seleccionarEmpleado(id)
{
    document
        .getElementById('empleadoSeleccionado')
        .value = id;

    document
        .getElementById('resultadoBusqueda')
        .innerHTML = '';

    cargarHistorial(id);
}


function cargarHistorial(id)
{
    fetch(
        "{{ url('rrhh/adelantos/empleados') }}/"
        + id +
        "/historial"
    )

    .then(res => res.json())

    .then(emp => {

        let html = `

            <h5>

                ${emp.nombre}
                ${emp.apellido}

            </h5>

            <p>

                DNI:
                ${emp.dni}

            </p>

            <hr>

        `;

        if(emp.adelantos.length == 0){

            html += `

                <div class="alert alert-success">

                    Sin adelantos registrados

                </div>

            `;
        }

        emp.adelantos.forEach(a => {

            let pagadas =
                a.cuotas.filter(
                    c => c.estado == 'pagada'
                ).length;

            let pendientes =
                a.cuotas.filter(
                    c => c.estado == 'pendiente'
                ).length;

            html += `

                <div class="card mb-2">

                    <div class="card-body">

                        <strong>

                            $${a.monto_total}

                        </strong>

                        <br>

                        Estado:
                        ${a.estado}

                        <br>

                        Cuotas:
                        ${pagadas}
                        /
                        ${a.cuotas_total}

                        <br>

                        Pendientes:
                        ${pendientes}

                    </div>

                </div>

            `;
        });

        document
            .getElementById('contenidoResumen')
            .innerHTML = html;

        document
            .getElementById('resumenEmpleado')
            .style.display = 'block';

    });

}

</script>
@endsection