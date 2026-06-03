@extends('layouts.documentacion')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">💸 Gestión de Viáticos</h3>

       <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalViatico">
    ➕ Nuevo Viático
</button>
    </div>

<div class="card shadow-sm">
    <div class="card-body">

        {{-- 🔍 FILTROS --}}
        <div class="row mb-3">

            <!-- BUSCADOR -->
            <div class="col-md-4">
                <input type="text" id="buscar" class="form-control" placeholder="🔍 Buscar empleado o DNI">
            </div>

            <!-- ESTADO -->
            <div class="col-md-3">
                <select id="filtroEstado" class="form-control">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </div>

            <!-- FECHA -->
            <div class="col-md-3">
                <input type="date" id="filtroFecha" class="form-control">
            </div>

        </div>

        {{--  TABLA --}}
       <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Codigo</th>
                    <th>Empleado</th>
                    <th>Fecha</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Total</th>        
                    <th>Estado</th>
                    <th width="200">Acciones</th>
                </tr>
            </thead>

            <tbody>
@forelse($viaticos as $v)

    {{-- 🔹 VIÁTICO ORIGINAL --}}
    <tr 
        style="background:#f8f9fa; font-weight:600;"
        data-nombre="{{ strtolower($v->empleado->nombre.' '.$v->empleado->apellido) }}"
        data-dni="{{ $v->empleado->dni }}"
        data-estado="{{ $v->estado }}"
        data-fecha_salida="{{ optional($v->fecha_salida)->format('Y-m-d H:i:s') }}"
        data-fecha_regreso="{{ optional($v->fecha_regreso)->format('Y-m-d H:i:s') }}"
    >
        <td>{{ $v->codigo }}</td>

        <td>
            <div class="d-flex align-items-center">
                <img src="{{ $v->empleado->foto_perfil 
                    ? asset('storage/fotos_empleados/'.$v->empleado->foto_perfil) 
                    : asset('img/default.png') }}"
                    width="40" height="40"
                    class="rounded-circle me-2">

                <div>
                    <strong>{{ $v->empleado->nombre }} {{ $v->empleado->apellido }}</strong>
                    <br>
                    <small>DNI: {{ $v->empleado->dni }}</small>
                </div>
            </div>
        </td>

        <td>{{ $v->fecha_salida }}</td>
        <td>{{ $v->origen }}</td>
        <td>{{ $v->destino }}</td>

        <td class="text-success">
            ${{ number_format($v->total, 2) }}
        </td>

        <td>
            @if($v->estado == 'pendiente')
                <span class="badge bg-warning">Pendiente</span>
            @elseif($v->estado == 'aprobado')
                <span class="badge bg-success">Aprobado</span>
            @else
                <span class="badge bg-danger">Rechazado</span>
            @endif

            @if($v->extensiones->count())
                <br><span class="badge bg-dark mt-1">EXTENDIDO</span>
            @endif
        </td>

        <td>

            <a href="{{ route('documentacion.viaticos.show', $v->id) }}" 
               class="btn btn-info btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg></a>

<button
    type="button"
    class="btn btn-warning btn-sm btn-editar"
    data-bs-toggle="modal"
    data-bs-target="#modalEditar"
    data-id="{{ $v->id }}"
    data-codigo="{{ $v->codigo }}"
    data-empleado="{{ $v->empleado->nombre }} {{ $v->empleado->apellido }}"
    data-origen="{{ $v->origen }}"
    data-destino="{{ $v->destino }}"
    data-movil="{{ $v->movil }}"
    data-fecha_salida="{{ optional($v->fecha_salida)->format('Y-m-d H:i:s') }}"
    data-fecha_regreso="{{ optional($v->fecha_regreso)->format('Y-m-d H:i:s') }}"
    data-dias="{{ $v->dias }}"
    data-dias_extra="{{ $v->dias_extra }}"
    data-es_extension="{{ $v->es_extension }}"
    data-observaciones="{{ e($v->observaciones) }}"
>
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
</svg>
</button>

            <button 
                type="button"
                class="btn btn-success btn-sm btn-extender"
                data-bs-toggle="modal"
                data-bs-target="#modalExtension"
                data-id="{{ $v->id }}"
                data-codigo="{{ $v->codigo }}"
                data-empleado="{{ $v->empleado->nombre }} {{ $v->empleado->apellido }}"
                data-origen="{{ $v->origen }}"
                data-destino="{{ $v->destino }}"
                data-movil="{{ $v->movil }}"
                
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-plus" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 7a.5.5 0 0 1 .5.5V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5A.5.5 0 0 1 8 7"/>
                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                </svg>
            </button>

        </td>
    </tr>

    {{-- 🔁 EXTENSIONES --}}
    @foreach($v->extensiones as $ext)
    <tr style="background:#fff3cd;">
        <td>↳ {{ $ext->codigo }}</td>

        <td></td>

        <td>{{ $ext->fecha_salida }}</td>

        <td colspan="2">
            Extensión ({{ $ext->dias_extra }} días)
        </td>

        <td class="text-primary">
            +${{ number_format($ext->total, 2) }}
        </td>

        <td>
            <span class="badge bg-secondary">Extensión</span>
        </td>

        <td>

                <button 
                    type="button"
                    class="btn btn-warning btn-sm btn-editar-extension"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditarExtension"
                    data-id="{{ $ext->id }}"
                    data-codigo="{{ $ext->codigo }}"
                    data-empleado="{{ $v->empleado->nombre }} {{ $v->empleado->apellido }}"
                    data-origen="{{ $ext->origen }}"
                    data-destino="{{ $ext->destino }}"
                    data-movil="{{ $ext->movil }}"
                    data-dias="{{ $ext->dias_extra }}"
                    data-observaciones="{{ e($ext->observaciones) }}"
                >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                            </svg>
                </button> 
                 <a href="{{ route('documentacion.viaticos.printExtension', $ext->id) }}" 
                class="btn btn-info btn-sm" target="_blank">
               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>
                </svg>
                </a>
        </td> 
     
    </tr>
    @endforeach

    {{-- TOTAL FINAL --}}
    <tr style="background:#d1ecf1; font-weight:bold;">
        <td colspan="5" class="text-end">TOTAL FINAL</td>
        <td>
            ${{ number_format($v->total + $v->extensiones->sum('total'), 2) }}
        </td>
        <td colspan="2"></td>
    </tr>

@empty
    <tr>
        <td colspan="8" class="text-center">
            No hay viáticos registrados
        </td>
    </tr>
@endforelse
</tbody>
        </table>

    </div>
</div>

{{-- 🔥 SCRIPT FILTRO --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

    let buscar = document.getElementById("buscar");
    let estado = document.getElementById("filtroEstado");
    let fecha = document.getElementById("filtroFecha");

    function filtrar() {

        let texto = buscar.value.toLowerCase();
        let estadoVal = estado.value;
        let fechaVal = fecha.value;

        let filas = document.querySelectorAll("tbody tr");

        filas.forEach(fila => {

            let nombre = fila.dataset.nombre || "";
            let dni = fila.dataset.dni || "";
            let estadoFila = fila.dataset.estado || "";
            let fechaFila = fila.dataset.fecha || "";

            let coincideTexto =
                nombre.includes(texto) || dni.includes(texto);

            let coincideEstado =
                !estadoVal || estadoFila === estadoVal;

            let coincideFecha =
                !fechaVal || fechaFila === fechaVal;

            if (coincideTexto && coincideEstado && coincideFecha) {
                fila.style.display = "";
            } else {
                fila.style.display = "none";
            }

        });
    }

    buscar.addEventListener("keyup", filtrar);
    estado.addEventListener("change", filtrar);
    fecha.addEventListener("change", filtrar);

});
</script>

<!-- MODAL CREAR VIÁTICO -->
<div class="modal fade" id="modalViatico" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form action="{{ route('documentacion.viaticos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- HEADER -->
                <div class="modal-header text-white" style="background-color: #e37c45;">
                    <h5 class="modal-title">💸 Nuevo Viático</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <!-- 🔹 DATOS GENERALES -->
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label>Chofer</label>
                            <select name="empleado_id"
                                    id="empleado_id"
                                    class="form-control select2"
                                    required>
                                <option value="">Buscar chofer...</option>

                                @foreach($empleados as $emp)
                                    <option value="{{ $emp->id }}">
                                        {{ $emp->apellido }}, {{ $emp->nombre }} - DNI: {{ $emp->dni }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="col-md-4 mb-3">
                            <label>Chofer</label>
                            <input type="text" name="chofer" class="form-control" required>
                        </div> --}}

                        <div class="col-md-4 mb-3">
                            <label>Móvil</label>
                            <input type="text" name="movil" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Origen</label>
                            <input type="text" name="origen" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Destino</label>
                            <input type="text" name="destino" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Fecha y hora de Salida</label>
                          <input type="datetime-local"
                                name="fecha_salida"
                                id="fecha_salida"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Fecha y hora de Regreso </label>
                           <input type="datetime-local"
                                    name="fecha_regreso"
                                    id="fecha_regreso"
                                    class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Días</label>
                           <input type="number"
                                    name="dias"
                                    id="dias"
                                    class="form-control"
                                    value="1"
                                    readonly>
                        </div>
                        <div class="col-md-8 mb-3">
                            <div id="resumenViatico" class="alert alert-info d-none mb-0">
                                <strong>🍽️ Cálculo automático:</strong>
                                <div id="detalleViatico"></div>
                            </div>
</div>

                    </div>

                    <hr>

                    <!-- 🔹 DETALLE DINÁMICO -->
                    <h5 class="mb-3">🧾 Detalle de Gastos</h5>

                    <table class="table table-bordered" id="tablaDetalles">
                        <thead style="background-color: #a04518; color:white;">
                            <tr>
                                <th>Concepto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th>Observaciones</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="text" name="concepto[]" class="form-control" placeholder="Ej: Almuerzo">
                                </td>

                                <td>
                                    <input type="number" name="cantidad[]" class="form-control cantidad" value="1">
                                </td>

                                <td>
                                    <input type="number" step="0.01" name="precio[]" class="form-control precio">
                                </td>

                                <td>
                                    <input type="text" name="subtotal[]" class="form-control subtotal" readonly>
                                </td>

                                <td>
                                    <input type="text" name="observaciones[]" class="form-control" placeholder="Detalle...">
                                </td>

                                <td>
                                    <button type="button" class="btn btn-danger btn-sm eliminarFila">X</button>
                                </td>
                            </tr>
                               
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-sm text-white mb-3"
                        style="background-color:#e37c45;"
                        id="agregarFila">
                        ➕ Agregar fila
                    </button>

                    <!-- 🔹 TOTAL -->
                    <div class="text-end">
                        <h5>Total: $ <span id="totalGeneral">0.00</span></h5>
                        <input type="hidden" name="total" id="inputTotal">
                    </div>

                    <hr>
                    
                  
                      <!-- 🔹 ARCHIVO -->
                    <div class="mb-3">
                        <label>Observaciones: </label><br>
                         <textarea name="observacion_general" class="form-control"></textarea>
                    </div>
                    <!-- 🔹 ARCHIVO -->
                    <div class="mb-3">
                        <label>Comprobante firmado</label>
                        <input type="file" name="archivo_firmado" class="form-control">
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button class="btn text-white" style="background-color:#e37c45;">
                        Guardar
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>




<div class="modal fade" id="modalExtension" tabindex="-1" aria-labelledby="modalExtensionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <form id="formExtension" method="POST">
                @csrf

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalExtensionLabel">Extender viático</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- DATOS DEL VIÁTICO ORIGINAL --}}
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Código original</label>
                            <input type="text" id="ext_codigo" class="form-control" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Empleado</label>
                            <input type="text" id="ext_empleado" class="form-control" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Origen</label>
                            <input type="text" id="ext_origen" class="form-control" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Destino</label>
                            <input type="text" id="ext_destino" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Móvil</label>
                            <input type="text" id="ext_movil" class="form-control" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Días extra</label>
                            <input type="number" name="dias_extra" class="form-control" min="1" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Observación general</label>
                            <input type="text" name="observacion_general" class="form-control" placeholder="Motivo de la extensión (opcional)">
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Detalles de la extensión</h6>
                        <button type="button" class="btn btn-primary btn-sm" id="agregarFilaExtension">
                            + Agregar detalle
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tablaDetallesExtension">
                            <thead class="table-secondary">
                                <tr>
                                    <th style="width: 22%;">Concepto</th>
                                    <th style="width: 10%;">Cantidad</th>
                                    <th style="width: 15%;">Precio</th>
                                    <th style="width: 15%;">Subtotal</th>
                                    <th style="width: 28%;">Observaciones</th>
                                    <th style="width: 10%;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="concepto[]" class="form-control" placeholder="Ej: Cena, hospedaje, peaje">
                                    </td>
                                    <td>
                                        <input type="number" name="cantidad[]" class="form-control cantidad-ext" min="1" value="1">
                                    </td>
                                    <td>
                                        <input type="number" name="precio[]" class="form-control precio-ext" min="0" step="0.01" value="0">
                                    </td>
                                    <td>
                                        <input type="number" name="subtotal[]" class="form-control subtotal-ext" value="0" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="observaciones[]" class="form-control" placeholder="Detalle adicional">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm eliminarFilaExtension">
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                 <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total extensión</th>
                                    <th>
                                        <input type="text" id="totalExtension" class="form-control fw-bold" value="0.00" readonly>
                                    </th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar extensión</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- EDIT MODAL EXTENCION=========== --}}
<div class="modal fade" id="modalEditarExtension" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <form id="formEditarExtension" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Editar extensión</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- DATOS -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Código</label>
                            <input type="text" id="edit_ext_codigo" class="form-control" readonly>
                        </div>

                        <div class="col-md-3">
                            <label>Empleado</label>
                            <input type="text" id="edit_ext_empleado" class="form-control" readonly>
                        </div>

                        <div class="col-md-3">
                            <label>Origen</label>
                            <input type="text" id="edit_ext_origen" class="form-control" readonly>
                        </div>

                        <div class="col-md-3">
                            <label>Destino</label>
                            <input type="text" id="edit_ext_destino" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label>Móvil</label>
                            <input type="text" id="edit_ext_movil" class="form-control" readonly>
                        </div>

                        <div class="col-md-3">
                            <label>Días extra</label>
                            <input type="number" id="edit_ext_dias" name="dias_extra" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Observación</label>
                            <input type="text" id="edit_ext_observaciones" name="observacion_general" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <h6>Detalles</h6>
                        <button type="button" class="btn btn-primary btn-sm" id="agregarFilaEdit">
                            + Agregar detalle
                        </button>
                    </div>

                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>Concepto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th>Observaciones</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody id="bodyEditExtension"></tbody>

                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th>
                                    <input type="text" id="totalEditExtension" class="form-control" readonly>
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- modal edit --}}

<div class="modal fade" id="modalEditar" tabindex="-1">
<div class="modal-dialog modal-xl">
<div class="modal-content">

<form id="formEditar" method="POST">
@csrf
@method('PUT')

<div class="modal-header bg-warning">
    <h5 class="modal-title">Editar Viático</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="row mb-3">
   <div class="row">

    <div class="col-md-3 mb-3">
        <label>Código</label>
        <input type="text" id="edit_codigo" class="form-control" readonly>
    </div>

    <div class="col-md-3 mb-3">
        <label>Empleado</label>
        <input type="text" id="edit_empleado" class="form-control" readonly>
    </div>

    <div class="col-md-3 mb-3">
        <label>Móvil</label>
        <input type="text"
               name="movil"
               id="edit_movil"
               class="form-control">
    </div>

    <div class="col-md-3 mb-3">
        <label>Origen</label>
        <input type="text"
               name="origen"
               id="edit_origen"
               class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label>Destino</label>
        <input type="text"
               name="destino"
               id="edit_destino"
               class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label>Fecha y hora de Salida</label>
        <input type="datetime-local"
               name="fecha_salida"
               id="edit_fecha_salida"
               class="form-control"
               required>
    </div>

    <div class="col-md-4 mb-3">
        <label>Fecha y hora de Regreso</label>
        <input type="datetime-local"
               name="fecha_regreso"
               id="edit_fecha_regreso"
               class="form-control">
    </div>

    <div class="col-md-4 mb-3">
        <label>Días</label>
        <input type="number"
               name="dias"
               id="edit_dias"
               class="form-control"
               readonly>
    </div>

    <div class="col-md-8 mb-3">
        <div id="edit_resumenViatico"
             class="alert alert-info d-none mb-0">
            <strong>🍽️ Cálculo automático:</strong>
            <div id="edit_detalleViatico"></div>
        </div>
    </div>

    <div class="col-md-12 mb-3">
        <label>Observaciones</label>
        <textarea id="edit_observaciones"
                  name="observacion_general"
                  class="form-control"></textarea>
    </div>

</div>

<hr>

<h6>Detalle de gastos</h6>

<table class="table table-bordered" id="tablaEdit">
<thead>
<tr>
<th>Concepto</th>
<th>Cantidad</th>
<th>Precio</th>
<th>Subtotal</th>
<th>Obs</th>
<th></th>
</tr>
</thead>

<tbody id="bodyEdit"></tbody>
</table>

<button type="button" class="btn btn-primary btn-sm" id="addFilaEdit">
+ Agregar
</button>

</div>

<div class="modal-footer">
<button type="submit" class="btn btn-success">Guardar cambios</button>
</div>

</form>
</div>
</div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function () {

    const formEditar = document.getElementById('formEditar');
    const bodyEdit = document.getElementById('bodyEdit');

    const formExtension = document.getElementById('formExtension');
    const tablaBodyExtension = document.querySelector('#tablaDetallesExtension tbody');
    const totalExtension = document.getElementById('totalExtension');
    const btnAgregarFilaExtension = document.getElementById('agregarFilaExtension');
    const modalExtension = document.getElementById('modalExtension');

    const tablaCreate = document.querySelector('#tablaDetalles tbody');
    const btnAgregarFilaCreate = document.getElementById('agregarFila');
    const totalGeneralSpan = document.getElementById('totalGeneral');
    const inputTotal = document.getElementById('inputTotal');

    function calcularFila(row, cantidadClass, precioClass, subtotalClass) {
        const cantidad = parseFloat(row.querySelector('.' + cantidadClass)?.value) || 0;
        const precio = parseFloat(row.querySelector('.' + precioClass)?.value) || 0;
        const subtotal = cantidad * precio;

        const subtotalInput = row.querySelector('.' + subtotalClass);
        if (subtotalInput) {
            subtotalInput.value = subtotal.toFixed(2);
        }
    }

    function calcularTotalCreate() {
        let total = 0;
        document.querySelectorAll('#tablaDetalles .subtotal').forEach(el => {
            total += parseFloat(el.value) || 0;
        });

        if (totalGeneralSpan) totalGeneralSpan.innerText = total.toFixed(2);
        if (inputTotal) inputTotal.value = total.toFixed(2);
    }

    function calcularTotalEdit() {
        let total = 0;
        document.querySelectorAll('#tablaEdit .subtotal').forEach(el => {
            total += parseFloat(el.value) || 0;
        });
    }

    function calcularTotalExtension() {
        let total = 0;
        document.querySelectorAll('#tablaDetallesExtension .subtotal-ext').forEach(el => {
            total += parseFloat(el.value) || 0;
        });

        if (totalExtension) {
            totalExtension.value = total.toFixed(2);
        }
    }

    function agregarFilaCreate() {
        if (!tablaCreate) return;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="concepto[]" class="form-control" placeholder="Ej: Almuerzo"></td>
            <td><input type="number" name="cantidad[]" class="form-control cantidad" value="1"></td>
            <td><input type="number" step="0.01" name="precio[]" class="form-control precio"></td>
            <td><input type="text" name="subtotal[]" class="form-control subtotal" readonly></td>
            <td><input type="text" name="observaciones[]" class="form-control" placeholder="Detalle..."></td>
            <td><button type="button" class="btn btn-danger btn-sm eliminarFila">X</button></td>
        `;
        tablaCreate.appendChild(tr);
    }

    function agregarFilaEdit(data = {}) {
        if (!bodyEdit) return;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input name="concepto[]" value="${data.concepto ?? ''}" class="form-control"></td>
            <td><input name="cantidad[]" value="${data.cantidad ?? 1}" class="form-control cantidad"></td>
            <td><input name="precio[]" value="${data.precio ?? ''}" class="form-control precio"></td>
            <td><input name="subtotal[]" value="${data.subtotal ?? ''}" class="form-control subtotal" readonly></td>
            <td><input name="observaciones[]" value="${data.observaciones ?? ''}" class="form-control"></td>
            <td><button type="button" class="btn btn-danger btn-sm remove">X</button></td>
        `;
        bodyEdit.appendChild(tr);
    }

    function agregarFilaExtension(data = {}) {
        if (!tablaBodyExtension) return;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="concepto[]" value="${data.concepto ?? ''}" class="form-control" placeholder="Ej: Cena, hospedaje, peaje"></td>
            <td><input type="number" name="cantidad[]" value="${data.cantidad ?? 1}" class="form-control cantidad-ext" min="1"></td>
            <td><input type="number" name="precio[]" value="${data.precio ?? 0}" class="form-control precio-ext" min="0" step="0.01"></td>
            <td><input type="number" name="subtotal[]" value="${data.subtotal ?? 0}" class="form-control subtotal-ext" readonly></td>
            <td><input type="text" name="observaciones[]" value="${data.observaciones ?? ''}" class="form-control" placeholder="Detalle adicional"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm eliminarFilaExtension">🗑</button>
            </td>
        `;
        tablaBodyExtension.appendChild(tr);
    }

    if (btnAgregarFilaCreate) {
        btnAgregarFilaCreate.addEventListener('click', agregarFilaCreate);
    }

    const btnAddFilaEdit = document.getElementById('addFilaEdit');
    if (btnAddFilaEdit) {
        btnAddFilaEdit.addEventListener('click', function () {
            agregarFilaEdit();
        });
    }

    if (btnAgregarFilaExtension) {
        btnAgregarFilaExtension.addEventListener('click', function () {
            agregarFilaExtension();
        });
    }

    document.addEventListener('input', function (e) {
        const row = e.target.closest('tr');
        if (!row) return;  

        if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
            calcularFila(row, 'cantidad', 'precio', 'subtotal');

            if (row.closest('#tablaDetalles')) calcularTotalCreate();
            if (row.closest('#tablaEdit')) calcularTotalEdit();
        }

        if (e.target.classList.contains('cantidad-ext') || e.target.classList.contains('precio-ext')) {
            calcularFila(row, 'cantidad-ext', 'precio-ext', 'subtotal-ext');
            calcularTotalExtension();
        }
    });

    document.addEventListener('click', function (e) {
        const btnRemove = e.target.closest('.remove');
        const btnEliminarFila = e.target.closest('.eliminarFila');
        const btnEliminarFilaExtension = e.target.closest('.eliminarFilaExtension');

        if (btnRemove) {
            btnRemove.closest('tr')?.remove();
            calcularTotalEdit();
        }

        if (btnEliminarFila) {
            const filas = document.querySelectorAll('#tablaDetalles tbody tr');
            if (filas.length > 1) {
                btnEliminarFila.closest('tr')?.remove();
                calcularTotalCreate();
            }
        }

        if (btnEliminarFilaExtension) {
            const filas = document.querySelectorAll('#tablaDetallesExtension tbody tr');
            if (filas.length > 1) {
                btnEliminarFilaExtension.closest('tr')?.remove();
                calcularTotalExtension();
            }
        }
    });

   document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;

        formEditar.action = `/documentacion/viaticos/${id}`;

        document.getElementById('edit_codigo').value = this.dataset.codigo || '';
        document.getElementById('edit_empleado').value = this.dataset.empleado || '';
        document.getElementById('edit_origen').value = this.dataset.origen || '';
        document.getElementById('edit_destino').value = this.dataset.destino || '';
        document.getElementById('edit_movil').value = this.dataset.movil || '';
        document.getElementById('edit_observaciones').value = this.dataset.observaciones || '';
        document.getElementById('edit_dias').value =
            this.dataset.dias || this.dataset.dias_extra || '';

        function formatearFecha(fecha) {
            if (!fecha) return '';
            return fecha.replace(' ', 'T').substring(0, 16);
        }

        console.log('Salida:', this.dataset.fecha_salida);
        console.log('Regreso:', this.dataset.fecha_regreso);

        document.getElementById('edit_fecha_salida').value =
            formatearFecha(this.dataset.fecha_salida);

        document.getElementById('edit_fecha_regreso').value =
            formatearFecha(this.dataset.fecha_regreso);

        fetch(`/documentacion/viaticos/${id}/json`)
            .then(res => res.json())
            .then(data => {
                bodyEdit.innerHTML = '';

                if (data.detalles && data.detalles.length) {
                    data.detalles.forEach(d => agregarFilaEdit(d));
                } else {
                    agregarFilaEdit();
                }

                calcularTotalEdit();
            })
            .catch(error => {
                console.error(error);
                bodyEdit.innerHTML = '';
                agregarFilaEdit();
            });
    });
});

    document.querySelectorAll('.btn-extender').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            document.getElementById('ext_codigo').value = this.dataset.codigo || '';
            document.getElementById('ext_empleado').value = this.dataset.empleado || '';
            document.getElementById('ext_origen').value = this.dataset.origen || '';
            document.getElementById('ext_destino').value = this.dataset.destino || '';
            document.getElementById('ext_movil').value = this.dataset.movil || '';

            formExtension.action = `/documentacion/viaticos/${id}/extension`;

            tablaBodyExtension.innerHTML = '';

            fetch(`/documentacion/viaticos/${id}/json`)
                .then(res => {
                    if (!res.ok) throw new Error('No se pudo obtener el viático base');
                    return res.json();
                })
                .then(data => {
                    if (data.detalles && data.detalles.length) {
                        data.detalles.forEach(d => {
                            agregarFilaExtension({
                                concepto: d.concepto,
                                cantidad: d.cantidad,
                                precio: d.precio,
                                subtotal: d.subtotal,
                                observaciones: d.observaciones
                            });
                        });
                    } else {
                        agregarFilaExtension();
                    }

                    calcularTotalExtension();
                })
                .catch(error => {
                    console.error(error);
                    agregarFilaExtension();
                    calcularTotalExtension();
                });
        });
    });

    if (modalExtension) {
        modalExtension.addEventListener('hidden.bs.modal', function () {
            if (formExtension) formExtension.reset();
            if (tablaBodyExtension) tablaBodyExtension.innerHTML = '';
            if (totalExtension) totalExtension.value = '0.00';
        });
    }

   
// ABRIR MODAL Y CARGAR DATOS
document.querySelectorAll('.btn-editar-extension').forEach(btn => {
    btn.addEventListener('click', function () {

        const id = this.dataset.id;

        // RUTA CORRECTA PARA EDITAR EXTENSIÓN
        document.getElementById('formEditarExtension').action = `/documentacion/viaticos/${id}/extension`;

        // DATOS GENERALES
        document.getElementById('edit_ext_codigo').value = this.dataset.codigo;
        document.getElementById('edit_ext_empleado').value = this.dataset.empleado;
        document.getElementById('edit_ext_origen').value = this.dataset.origen;
        document.getElementById('edit_ext_destino').value = this.dataset.destino;
        document.getElementById('edit_ext_movil').value = this.dataset.movil;1
        document.getElementById('edit_ext_dias').value = this.dataset.dias;
        document.getElementById('edit_ext_observaciones').value = this.dataset.observaciones;

        // TRAER DETALLES
        fetch(`/documentacion/viaticos/${id}/json`)
            .then(res => res.json())
            .then(data => {

                const body = document.getElementById('bodyEditExtension');
                body.innerHTML = '';

                let total = 0;

                data.detalles.forEach(d => {

                    const subtotal = parseFloat(d.subtotal) || 0;
                    total += subtotal;

                    const tr = document.createElement('tr');

                    tr.innerHTML = `
                        <td><input name="concepto[]" value="${d.concepto}" class="form-control"></td>
                        <td><input name="cantidad[]" value="${d.cantidad}" class="form-control cantidad"></td>
                        <td><input name="precio[]" value="${d.precio}" class="form-control precio"></td>
                        <td><input name="subtotal[]" value="${subtotal.toFixed(2)}" class="form-control subtotal" readonly></td>
                        <td><input name="observaciones[]" value="${d.observaciones ?? ''}" class="form-control"></td>
                        <td><button type="button" class="btn btn-danger remove">X</button></td>
                    `;

                    body.appendChild(tr);
                });

                document.getElementById('totalEditExtension').value = total.toFixed(2);
            });
    });
});


// AGREGAR FILA
document.getElementById('agregarFilaEdit').addEventListener('click', function () {

    const tr = document.createElement('tr');

    tr.innerHTML = `
        <td><input name="concepto[]" class="form-control"></td>
        <td><input name="cantidad[]" value="1" class="form-control cantidad"></td>
        <td><input name="precio[]" value="0" class="form-control precio"></td>
        <td><input name="subtotal[]" value="0" class="form-control subtotal" readonly></td>
        <td><input name="observaciones[]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger remove">X</button></td>
    `;

    document.getElementById('bodyEditExtension').appendChild(tr);
});


// ❌ ELIMINAR FILA
document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove')){
        e.target.closest('tr').remove();
        calcularTotal();
    }
});


// 🔢 CALCULAR SUBTOTAL Y TOTAL
document.addEventListener('input', function(e){

    if(e.target.classList.contains('cantidad') || e.target.classList.contains('precio')){

        const tr = e.target.closest('tr');

        const cantidad = parseFloat(tr.querySelector('.cantidad').value) || 0;
        const precio = parseFloat(tr.querySelector('.precio').value) || 0;

        const subtotal = cantidad * precio;

        tr.querySelector('.subtotal').value = subtotal.toFixed(2);

        calcularTotal();
    }
});


// TOTAL GENERAL
function calcularTotal(){

    let total = 0;

    document.querySelectorAll('#bodyEditExtension .subtotal').forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    document.getElementById('totalEditExtension').value = total.toFixed(2);
}

// AGREGAR FILA
document.getElementById('agregarFilaEdit').addEventListener('click', function () {
    const tr = document.createElement('tr');

    tr.innerHTML = `
        <td><input name="concepto[]" class="form-control"></td>
        <td><input name="cantidad[]" value="1" class="form-control cantidad"></td>
        <td><input name="precio[]" value="0" class="form-control precio"></td>
        <td><input name="subtotal[]" value="0" class="form-control subtotal" readonly></td>
        <td><input name="observaciones[]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger remove">X</button></td>
    `;

    document.getElementById('bodyEditExtension').appendChild(tr);
});


// ELIMINAR FILA
document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove')){
        e.target.closest('tr').remove();
        calcularTotal();
    }
});


// CALCULAR SUBTOTAL Y TOTAL
document.addEventListener('input', function(e){

    if(e.target.classList.contains('cantidad') || e.target.classList.contains('precio')){

        const tr = e.target.closest('tr');

        const cantidad = parseFloat(tr.querySelector('.cantidad').value) || 0;
        const precio = parseFloat(tr.querySelector('.precio').value) || 0;

        const subtotal = cantidad * precio;

        tr.querySelector('.subtotal').value = subtotal.toFixed(2);

        calcularTotal();
    }
});

function calcularTotal(){
    let total = 0;

    document.querySelectorAll('#bodyEditExtension .subtotal').forEach(input => {
        total += parseFloat(input.value) || 0;
    });

    document.getElementById('totalEditExtension').value = total.toFixed(2);
}
   

});
</script>

<script>
$(document).ready(function () {
    $('.select2').select2({
        dropdownParent: $('#modalViatico'),
        width: '100%',
        placeholder: 'Buscar chofer...',
        allowClear: true,
        language: {
            noResults: function () {
                return "No se encontraron resultados";
            }
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const fechaSalida = document.getElementById('fecha_salida');
    const fechaRegreso = document.getElementById('fecha_regreso');
    const diasInput = document.getElementById('dias');

    const resumen = document.getElementById('resumenViatico');
    const detalle = document.getElementById('detalleViatico');

    function calcularViatico() {
        if (!fechaSalida.value || !fechaRegreso.value) {
            resumen.classList.add('d-none');
            return;
        }

        const salida = new Date(fechaSalida.value);
        const regreso = new Date(fechaRegreso.value);

        if (regreso <= salida) {
            resumen.classList.add('d-none');
            return;
        }

        // Diferencia en días (redondeando hacia arriba)
        const diffMs = regreso - salida;
        const dias = Math.ceil(diffMs / (1000 * 60 * 60 * 24));

        diasInput.value = dias;

        // Cálculo de almuerzos y cenas  
        let almuerzos = 0;
        let cenas = 0;

        let fecha = new Date(salida);

        while (fecha <= regreso) {

            // Almuerzo (12:00)
            let almuerzo = new Date(fecha);
            almuerzo.setHours(12, 0, 0, 0);

            if (almuerzo >= salida && almuerzo <= regreso) {
                almuerzos++;
            }

            // Cena (20:00)
            let cena = new Date(fecha);
            cena.setHours(20, 0, 0, 0);

            if (cena >= salida && cena <= regreso) {
                cenas++;
            }

            fecha.setDate(fecha.getDate() + 1);
        }

        detalle.innerHTML = `
            <div>📅 Días calculados: <strong>${dias}</strong></div>
            <div>🍽️ Almuerzos: <strong>${almuerzos}</strong></div>
            <div>🌙 Cenas: <strong>${cenas}</strong></div>
        `;

        resumen.classList.remove('d-none');
    }

    fechaSalida.addEventListener('change', calcularViatico);
    fechaRegreso.addEventListener('change', calcularViatico);
});
</script>
@endsection 