@extends('layouts.comercial')

@section('title', 'Editar Empresa')

@section('content')

<div class="container-fluid py-3">

    {{-- ================= TITULO ================= --}}
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-bold mb-0">Editar Empresa</h2>
            <small class="text-muted">Actualización de datos comerciales y legajo</small>
        </div>
        <div class="col text-end">
            <a href="{{ route('comercial.clientes.index') }}"
               class="btn btn-outline-secondary btn-sm">
                Volver
            </a>
        </div>
    </div>

    {{-- ================= ERRORES ================= --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errores en el formulario</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================= FORM EMPRESA ================= --}}
    <form method="POST"
          action="{{ route('comercial.clientes.update', $empresa) }}"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header fw-semibold bg-light">
                Datos de la empresa
            </div>

            <div class="card-body">
                <div class="row g-3">

                    {{-- CUIT --}}
                    <div class="col-md-3">
                        <label class="form-label">CUIT</label>
                        <input type="text"
                               name="cuit"
                               class="form-control"
                               value="{{ old('cuit', $empresa->cuit) }}"
                               required>
                    </div>

                    {{-- RAZON SOCIAL --}}
                    <div class="col-md-6">
                        <label class="form-label">Razón Social</label>
                        <input type="text"
                               name="razon_social"
                               class="form-control"
                               value="{{ old('razon_social', $empresa->razon_social) }}">
                    </div>

                    {{-- TIPO PERSONA --}}
                    <div class="col-md-3">
                        <label class="form-label">Tipo de persona</label>
                        <select name="tipo_persona" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Persona Humana" {{ old('tipo_persona', $empresa->tipo_persona) == 'Persona Humana' ? 'selected' : '' }}>
                                Persona Humana
                            </option>
                            <option value="Persona Jurídica" {{ old('tipo_persona', $empresa->tipo_persona) == 'Persona Jurídica' ? 'selected' : '' }}>
                                Persona Jurídica
                            </option>
                            <option value="Sucesión Indivisa" {{ old('tipo_persona', $empresa->tipo_persona) == 'Sucesión Indivisa' ? 'selected' : '' }}>
                                Sucesión Indivisa
                            </option>
                        </select>
                    </div>

                    {{-- CONDICION IVA --}}
                    <div class="col-md-4">
                        <label class="form-label">Condición IVA</label>
                        <select name="condicion_iva" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Responsable Inscripto" {{ old('condicion_iva', $empresa->condicion_iva) == 'Responsable Inscripto' ? 'selected' : '' }}>
                                Responsable Inscripto
                            </option>
                            <option value="Monotributista" {{ old('condicion_iva', $empresa->condicion_iva) == 'Monotributista' ? 'selected' : '' }}>
                                Monotributista
                            </option>
                            <option value="Consumidor Final" {{ old('condicion_iva', $empresa->condicion_iva) == 'Consumidor Final' ? 'selected' : '' }}>
                                Consumidor Final
                            </option>
                            <option value="Exento" {{ old('condicion_iva', $empresa->condicion_iva) == 'Exento' ? 'selected' : '' }}>
                                Exento
                            </option>
                            <option value="No Responsable" {{ old('condicion_iva', $empresa->condicion_iva) == 'No Responsable' ? 'selected' : '' }}>
                                No Responsable
                            </option>
                            <option value="Sujeto No Categorizado" {{ old('condicion_iva', $empresa->condicion_iva) == 'Sujeto No Categorizado' ? 'selected' : '' }}>
                                Sujeto No Categorizado
                            </option>
                            <option value="Monotributo Social" {{ old('condicion_iva', $empresa->condicion_iva) == 'Monotributo Social' ? 'selected' : '' }}>
                                Monotributo Social
                            </option>
                            <option value="IVA Liberado Ley 19640" {{ old('condicion_iva', $empresa->condicion_iva) == 'IVA Liberado Ley 19640' ? 'selected' : '' }}>
                                IVA Liberado Ley 19640
                            </option>
                        </select>
                    </div>

                    {{-- ESTADO FISCAL --}}
                    <div class="col-md-4">
                        <label class="form-label">Estado fiscal</label>
                        <select name="estado_fiscal" class="form-select">
                            <option value="">Seleccione</option>
                            <option value="Activo" {{ old('estado_fiscal', $empresa->estado_fiscal) == 'Activo' ? 'selected' : '' }}>
                                Activo
                            </option>
                            <option value="Inactivo" {{ old('estado_fiscal', $empresa->estado_fiscal) == 'Inactivo' ? 'selected' : '' }}>
                                Inactivo
                            </option>
                            <option value="Baja" {{ old('estado_fiscal', $empresa->estado_fiscal) == 'Baja' ? 'selected' : '' }}>
                                Baja
                            </option>
                            <option value="Suspendido" {{ old('estado_fiscal', $empresa->estado_fiscal) == 'Suspendido' ? 'selected' : '' }}>
                                Suspendido
                            </option>
                        </select>
                    </div>

                    {{-- ACTIVIDAD PRINCIPAL --}}
                    <div class="col-md-4">
                        <label class="form-label">Actividad principal</label>
                        <input type="text"
                               name="actividad_principal"
                               class="form-control"
                               value="{{ old('actividad_principal', $empresa->actividad_principal) }}">
                    </div>

                    {{-- LOGO --}}
                    <div class="col-md-4">
                        <label class="form-label">Logo</label>
                        <input type="file"
                               name="logo"
                               class="form-control"
                               accept="image/*">

                        @if($empresa->logo)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $empresa->logo) }}"
                                     alt="Logo actual"
                                     width="80"
                                     class="img-thumbnail">
                            </div>
                        @endif
                    </div>

                    {{-- OBSERVACIONES --}}
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observaciones"
                                  class="form-control"
                                  rows="3">{{ old('observaciones', $empresa->observaciones) }}</textarea>
                    </div>

                </div>
            </div>

            <div class="card-footer text-end bg-light">
                <button type="submit" class="btn btn-primary btn-sm px-4">
                    Guardar cambios
                </button>
            </div>

        </div>
    </form>

    {{-- ================= REFERENTES ================= --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header fw-semibold bg-light d-flex justify-content-between">
            Referentes
            <button class="btn btn-outline-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalNuevoReferente">
                Agregar
            </button>
        </div>

        <div class="card-body p-0">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Contacto</th>
                        <th width="140">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($empresa->referentes as $ref)
                    <tr>
                        <td>{{ $ref->nombre }} {{ $ref->apellido }}</td>
                        <td>{{ $ref->cargo ?? '—' }}</td>
                        <td>
                            {{ $ref->telefono ?? '—' }}<br>
                            {{ $ref->correo ?? '—' }}
                        </td>
                        <td class="text-center">

                            {{-- EDITAR --}}
                            <button class="btn btn-sm btn-outline-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarReferente{{ $ref->id }}"
                                    title="Editar referente">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                            </button>

                            {{-- ELIMINAR --}}
                            <form method="POST"
                                  action="{{ route('comercial.referentes.destroy', $ref) }}"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="confirmarEliminacion(this)"
                                        title="Eliminar referente">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                    </svg>
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            Sin referentes
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= DOCUMENTOS ================= --}}
    <div class="card shadow-sm border-0">

        <div class="card-header fw-semibold bg-light d-flex justify-content-between">
            Documentación
            <button class="btn btn-outline-dark btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalNuevoDocumento">
                Agregar
            </button>
        </div>

        <div class="card-body p-0">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tipo</th>
                        <th>Documento</th>
                        <th>Vencimiento</th>
                        <th width="160">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($empresa->documentos as $doc)
                    <tr>
                        <td>{{ $doc->tipo_documento }}</td>
                        <td>{{ $doc->nombre_documento }}</td>
                        <td>{{ $doc->fecha_vencimiento ? \Carbon\Carbon::parse($doc->fecha_vencimiento)->format('Y-m-d') : '—' }}</td>
                        <td class="text-center">

                            {{-- VER --}}
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    onclick="verDocumento('{{ asset('storage/'.$doc->archivo) }}', '{{ $doc->nombre_documento }}')"
                                    title="Ver documento">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                </svg>
                            </button>

                            {{-- EDITAR --}}
                            <button class="btn btn-sm btn-outline-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarDocumento{{ $doc->id }}"
                                    title="Editar documento">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                </svg>
                            </button>

                            {{-- ELIMINAR --}}
                            <form method="POST"
                                  action="{{ route('comercial.documentos.destroy', $doc) }}"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="confirmarEliminacion(this)"
                                        title="Eliminar documento">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                    </svg>
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">
                            Sin documentos
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ================= MODALES EDITAR REFERENTES ================= --}}
@foreach($empresa->referentes as $ref)
<div class="modal fade" id="modalEditarReferente{{ $ref->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('comercial.referentes.update', $ref) }}"
              class="modal-content">

            @csrf
            @method('PUT')

            <div class="modal-header">
                <h6 class="modal-title">Editar Referente</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input class="form-control mb-2"
                       name="nombre"
                       value="{{ old('nombre', $ref->nombre) }}"
                       placeholder="Nombre"
                       required>

                <input class="form-control mb-2"
                       name="apellido"
                       value="{{ old('apellido', $ref->apellido) }}"
                       placeholder="Apellido"
                       required>

                <input class="form-control mb-2"
                       name="cargo"
                       value="{{ old('cargo', $ref->cargo) }}"
                       placeholder="Cargo">

                <input class="form-control mb-2"
                       name="telefono"
                       value="{{ old('telefono', $ref->telefono) }}"
                       placeholder="Teléfono">

                <input class="form-control mb-2"
                       name="correo"
                       value="{{ old('correo', $ref->correo) }}"
                       placeholder="Correo">
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary btn-sm">
                    Guardar
                </button>
            </div>

        </form>
    </div>
</div>
@endforeach

{{-- ================= MODALES EDITAR DOCUMENTOS ================= --}}
@foreach($empresa->documentos as $doc)
<div class="modal fade" id="modalEditarDocumento{{ $doc->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('comercial.documentos.update', $doc) }}"
              enctype="multipart/form-data"
              class="modal-content">

            @csrf
            @method('PUT')

            <div class="modal-header">
                <h6 class="modal-title">Editar Documento</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <select name="tipo_documento" class="form-select mb-2">
                    <option value="constancia_arca" {{ old('tipo_documento', $doc->tipo_documento) == 'constancia_arca' ? 'selected' : '' }}>
                        Constancia ARCA
                    </option>
                    <option value="estatuto" {{ old('tipo_documento', $doc->tipo_documento) == 'estatuto' ? 'selected' : '' }}>
                        Estatuto
                    </option>
                    <option value="cbu" {{ old('tipo_documento', $doc->tipo_documento) == 'cbu' ? 'selected' : '' }}>
                        CBU
                    </option>
                    <option value="seguro" {{ old('tipo_documento', $doc->tipo_documento) == 'seguro' ? 'selected' : '' }}>
                        Seguro
                    </option>
                    <option value="otro" {{ old('tipo_documento', $doc->tipo_documento) == 'otro' ? 'selected' : '' }}>
                        Otro
                    </option>
                </select>

                <input class="form-control mb-2"
                       name="nombre_documento"
                       value="{{ old('nombre_documento', $doc->nombre_documento) }}"
                       placeholder="Nombre del documento">

                <input type="date"
                       class="form-control mb-2"
                       name="fecha_vencimiento"
                       value="{{ old('fecha_vencimiento', $doc->fecha_vencimiento) }}">

                @if($doc->archivo)
                    <div class="mb-2">
                        <button type="button"
                                class="btn btn-outline-primary btn-sm"
                                onclick="verDocumento('{{ asset('storage/'.$doc->archivo) }}', '{{ $doc->nombre_documento }}')">
                            Ver documento actual
                        </button>
                    </div>
                @endif

                <input type="file" name="archivo" class="form-control">
                <small class="text-muted">Dejar vacío para no reemplazar el archivo</small>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary btn-sm">
                    Guardar
                </button>
            </div>

        </form>
    </div>
</div>
@endforeach

{{-- ================= MODAL NUEVO REFERENTE ================= --}}
<div class="modal fade" id="modalNuevoReferente" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('comercial.referentes.store', $empresa->id) }}"
              class="modal-content">

            @csrf

            <div class="modal-header">
                <h6 class="modal-title">Nuevo Referente</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input class="form-control mb-2"
                       name="nombre"
                       placeholder="Nombre"
                       required>

                <input class="form-control mb-2"
                       name="apellido"
                       placeholder="Apellido"
                       required>

                <input class="form-control mb-2"
                       name="cargo"
                       placeholder="Cargo">

                <input class="form-control mb-2"
                       name="telefono"
                       placeholder="Teléfono">

                <input class="form-control mb-2"
                       name="correo"
                       placeholder="Correo">
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary btn-sm">
                    Agregar
                </button>
            </div>

        </form>
    </div>
</div>

{{-- ================= MODAL NUEVO DOCUMENTO ================= --}}
<div class="modal fade" id="modalNuevoDocumento" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST"
              action="{{ route('comercial.documentos.store', $empresa->id) }}"
              enctype="multipart/form-data"
              class="modal-content">

            @csrf

            <div class="modal-header">
                <h6 class="modal-title">Nuevo Documento</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <select name="tipo_documento" class="form-select mb-2">
                    <option value="constancia_arca">Constancia ARCA</option>
                    <option value="estatuto">Estatuto</option>
                    <option value="cbu">CBU</option>
                    <option value="seguro">Seguro</option>
                    <option value="otro">Otro</option>
                </select>

                <input class="form-control mb-2"
                       name="nombre_documento"
                       placeholder="Nombre del documento">

                <input type="date"
                       class="form-control mb-2"
                       name="fecha_vencimiento">

                <input type="file"
                       name="archivo"
                       class="form-control"
                       required>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary btn-sm">
                    Agregar
                </button>
            </div>

        </form>
    </div>
</div>

{{-- ================= MODAL CONFIRMAR ELIMINACION ================= --}}
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h6 class="modal-title">Confirmar eliminación</h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0">
                    Esta acción <strong>no se puede deshacer</strong>.<br>
                    ¿Deseás continuar?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button"
                        class="btn btn-danger btn-sm"
                        id="btnConfirmarEliminar">
                    Sí, eliminar
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ================= MODAL VER DOCUMENTO ================= --}}
<div class="modal fade" id="modalVerDocumento" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h6 class="modal-title" id="tituloDocumento">Documento</h6>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        onclick="cerrarDocumento()">
                </button>
            </div>

            <div class="modal-body p-0">
                <iframe id="iframeDocumento"
                        src=""
                        style="width:100%; height:75vh; border:0;">
                </iframe>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let formAEliminar = null;
    let modalDocumento = null;

    function confirmarEliminacion(boton) {
        formAEliminar = boton.closest('form');

        const modal = new bootstrap.Modal(
            document.getElementById('modalConfirmarEliminar')
        );
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const btnConfirmar = document.getElementById('btnConfirmarEliminar');

        if (btnConfirmar) {
            btnConfirmar.addEventListener('click', function () {
                if (formAEliminar) {
                    formAEliminar.submit();
                }
            });
        }
    });

    function verDocumento(url, nombre) {
        document.getElementById('iframeDocumento').src = url;
        document.getElementById('tituloDocumento').innerText = nombre || 'Documento';

        if (!modalDocumento) {
            modalDocumento = new bootstrap.Modal(
                document.getElementById('modalVerDocumento')
            );
        }

        modalDocumento.show();
    }

    function cerrarDocumento() {
        document.getElementById('iframeDocumento').src = '';
    }
</script>
@endpush