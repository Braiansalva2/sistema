@extends('layouts.comercial')

@section('title', 'Nueva Empresa')

@section('content')

<div class="py-3">

<div class="container">

{{-- ================= TITULO ================= --}}
<div class="row mb-4 align-items-center">
<div class="col">
<h2 class="fw-bold mb-0">Nueva Empresa</h2>
<small class="text-muted">Gestión comercial y legajo documental</small>
</div>

<div class="col text-end">
<a href="{{ route('comercial.clientes.index') }}" class="btn btn-outline-secondary btn-sm">
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


<form method="POST"
action="{{ route('comercial.clientes.store') }}"
enctype="multipart/form-data">

@csrf

<div id="documentosInputs"></div>

<div class="card shadow-sm border-0">


{{-- ================= DATOS EMPRESA ================= --}}
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
placeholder="Ingrese CUIT"
required>
</div>


{{-- RAZON SOCIAL --}}
<div class="col-md-6">
<label class="form-label">Razón Social</label>
<input type="text"
name="razon_social"
class="form-control"
placeholder="Ingrese razón social">
</div>


{{-- TIPO PERSONA --}}
{{-- TIPO PERSONA --}}
<div class="col-md-3">
<label class="form-label">Tipo de persona</label>

<select name="tipo_persona" class="form-select">

<option value="">Seleccione</option>

<option value="Persona Humana">Persona Humana</option>
<option value="Persona Jurídica">Persona Jurídica</option>
<option value="Sucesión Indivisa">Sucesión Indivisa</option>

</select>
</div>


{{-- CONDICION IVA --}}

<div class="col-md-4">

<label class="form-label">Condición IVA</label>

<select name="condicion_iva" class="form-select">

<option value="">Seleccione</option>

<option value="Responsable Inscripto">Responsable Inscripto</option>

<option value="Monotributista">Monotributista</option>

<option value="Consumidor Final">Consumidor Final</option>

<option value="Exento">Exento</option>

<option value="No Responsable">No Responsable</option>

<option value="Sujeto No Categorizado">Sujeto No Categorizado</option>

<option value="Monotributo Social">Monotributo Social</option>

<option value="IVA Liberado Ley 19640">IVA Liberado Ley 19640</option>

</select>

</div>


{{-- ESTADO FISCAL --}}
{{-- ESTADO FISCAL --}}
<div class="col-md-4">

<label class="form-label">Estado fiscal</label>

<select name="estado_fiscal" class="form-select">

<option value="">Seleccione</option>

<option value="Activo">Activo</option>

<option value="Inactivo">Inactivo</option>

<option value="Baja">Baja</option>

<option value="Suspendido">Suspendido</option>

</select>

</div>

{{-- ACTIVIDAD --}}
<div class="col-md-4">
<label class="form-label">Actividad principal</label>
<input type="text"
name="actividad_principal"
class="form-control">
</div>


{{-- LOGO --}}
<div class="col-md-4">
<label class="form-label">Logo</label>
<input type="file"
name="logo"
class="form-control"
accept="image/*">
</div>


{{-- OBSERVACIONES --}}
<div class="col-12">
<label class="form-label">Observaciones</label>
<textarea name="observaciones"
class="form-control"
rows="2"></textarea>
</div>

</div>
</div>


{{-- ================= REFERENTES ================= --}}
<div class="card-header fw-semibold bg-light d-flex justify-content-between">
Referentes

<button type="button"
class="btn btn-outline-primary btn-sm"
data-bs-toggle="modal"
data-bs-target="#modalReferente">
Agregar
</button>

</div>

<div class="card-body">

<table class="table table-sm table-bordered mb-0">

<thead class="table-light">

<tr>
<th>Nombre</th>
<th>Cargo</th>
<th>Contacto</th>
</tr>

</thead>

<tbody id="tablaReferentes"></tbody>

</table>

<div id="referentesHidden"></div>

</div>



{{-- ================= DOCUMENTOS ================= --}}
<div class="card-header fw-semibold bg-light d-flex justify-content-between">

Documentación

<button type="button"
class="btn btn-outline-dark btn-sm"
data-bs-toggle="modal"
data-bs-target="#modalDocumento">
Agregar
</button>

</div>


<div class="card-body">

<table class="table table-sm table-bordered mb-0">

<thead class="table-light">

<tr>
<th>Tipo</th>
<th>Documento</th>
<th>Vencimiento</th>
</tr>

</thead>

<tbody id="tablaDocumentos"></tbody>

</table>

</div>



<div class="card-footer text-end bg-light">

<button type="submit"
class="btn btn-success btn-sm px-4">

Guardar empresa

</button>

</div>

</div>
</form>
</div>
</div>



{{-- ================= MODAL REFERENTE ================= --}}
<div class="modal fade" id="modalReferente">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h6 class="modal-title">Nuevo referente</h6>

<button type="button"
class="btn-close"
data-bs-dismiss="modal"></button>

</div>


<div class="modal-body">

<input id="ref_nombre"
class="form-control mb-2"
placeholder="Nombre">

<input id="ref_apellido"
class="form-control mb-2"
placeholder="Apellido">

<input id="ref_cargo"
class="form-control mb-2"
placeholder="Cargo">

<input id="ref_telefono"
class="form-control mb-2"
placeholder="Teléfono">

<input id="ref_correo"
class="form-control mb-2"
placeholder="Correo">

</div>

<div class="modal-footer">

<button type="button"
class="btn btn-primary btn-sm"
onclick="agregarReferente()">

Agregar

</button>

</div>

</div>
</div>
</div>




{{-- ================= MODAL DOCUMENTO ================= --}}
<div class="modal fade" id="modalDocumento">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h6 class="modal-title">Nuevo documento</h6>

<button type="button"
class="btn-close"
data-bs-dismiss="modal"></button>

</div>


<div class="modal-body">

<select id="doc_tipo"
class="form-select mb-2">

<option value="">Tipo de documento</option>

<option value="constancia_arca">
Constancia ARCA
</option>

<option value="estatuto">
Estatuto
</option>

<option value="cbu">
CBU
</option>

<option value="seguro">
Seguro
</option>

<option value="otro">
Otro
</option>

</select>


<input id="doc_nombre"
class="form-control mb-2"      
placeholder="Nombre del documento">


<input id="doc_vencimiento"
type="date"
class="form-control mb-2">


<input id="doc_archivo"
type="file"
class="form-control"
accept=".pdf,image/*">

</div>


<div class="modal-footer">

<button type="button"
class="btn btn-dark btn-sm"
onclick="agregarDocumento()">

Agregar

</button>

</div>

</div>
</div>
</div>




{{-- ================= JS ================= --}}
<script>

let refIndex = 0;
let docIndex = 0;


/* REFERENTES */

function agregarReferente(){

const nombre = ref_nombre.value;
const apellido = ref_apellido.value;
const cargo = ref_cargo.value;
const telefono = ref_telefono.value;
const correo = ref_correo.value;

if(!nombre || !apellido){

alert('Nombre y apellido obligatorios');
return;

}


tablaReferentes.insertAdjacentHTML('beforeend',`

<tr>
<td>${nombre} ${apellido}</td>
<td>${cargo || ''}</td>
<td>${telefono || ''}<br>${correo || ''}</td>
</tr>

`);



referentesHidden.insertAdjacentHTML('beforeend',`

<input type="hidden" name="referentes[${refIndex}][nombre]" value="${nombre}">
<input type="hidden" name="referentes[${refIndex}][apellido]" value="${apellido}">
<input type="hidden" name="referentes[${refIndex}][cargo]" value="${cargo}">
<input type="hidden" name="referentes[${refIndex}][telefono]" value="${telefono}">
<input type="hidden" name="referentes[${refIndex}][correo]" value="${correo}">

`);

refIndex++;

ref_nombre.value='';
ref_apellido.value='';
ref_cargo.value='';
ref_telefono.value='';
ref_correo.value='';

bootstrap.Modal.getInstance(document.getElementById('modalReferente')).hide();

}



/* DOCUMENTOS */

function agregarDocumento(){

const tipo = doc_tipo.value;
const nombre = doc_nombre.value;
const vencimiento = doc_vencimiento.value;
const archivoInput = document.getElementById('doc_archivo');


if(!archivoInput.files.length){

alert('Seleccione un archivo');
return;

}


archivoInput.name = `documentos[${docIndex}][archivo]`;
archivoInput.style.display='none';

document.getElementById('documentosInputs').appendChild(archivoInput);


document.getElementById('documentosInputs').insertAdjacentHTML('beforeend',`

<input type="hidden" name="documentos[${docIndex}][tipo]" value="${tipo}">
<input type="hidden" name="documentos[${docIndex}][nombre]" value="${nombre}">
<input type="hidden" name="documentos[${docIndex}][fecha_vencimiento]" value="${vencimiento}">

`);



tablaDocumentos.insertAdjacentHTML('beforeend',`

<tr>
<td>${tipo}</td>
<td>${nombre || archivoInput.files[0].name}</td>
<td>${vencimiento || '-'}</td>
</tr>

`);


const nuevoInput = document.createElement('input');

nuevoInput.type='file';
nuevoInput.id='doc_archivo';
nuevoInput.className='form-control';
nuevoInput.accept='.pdf,image/*';


document.querySelector('#modalDocumento .modal-body').appendChild(nuevoInput);

docIndex++;

doc_tipo.value='';
doc_nombre.value='';
doc_vencimiento.value='';

bootstrap.Modal.getInstance(document.getElementById('modalDocumento')).hide();

}

</script>

@endsection