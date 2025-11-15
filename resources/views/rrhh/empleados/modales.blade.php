<!-- Modal Banco -->
<div class="modal fade" id="modalBanco" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formBanco" class="modal-content">
            <div class="modal-header" style="background-color:#a44a20;">
                <h5 class="modal-title text-white">Agregar nuevo banco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label>Nombre del banco</label>
                <input type="text" name="nombre_banco" class="form-control mb-2" required>
                <label>Código</label>
                <input type="text" name="codigo" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn text-white" style="background-color:#a44a20;"
                        onclick="guardarModal('bancos','banco_id','modalBanco')">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Contrato -->
<div class="modal fade" id="modalContrato" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formContrato" class="modal-content">
            <div class="modal-header" style="background-color:#a44a20;">
                <h5 class="modal-title text-white">Agregar tipo de contrato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label>Tipo de contrato</label>
                <input type="text" name="tipo_contrato" class="form-control mb-2" required>
                <label>Estado</label>
                <select name="estado" class="form-select">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn text-white" style="background-color:#a44a20;"
                        onclick="guardarModal('contratos','contrato_id','modalContrato')">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Condición laboral -->
<div class="modal fade" id="modalCondicion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formCondicion" class="modal-content">
            <div class="modal-header" style="background-color:#a44a20;">
                <h5 class="modal-title text-white">Agregar condición laboral</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label>Nombre de condición</label>
                <input type="text" name="nombre_condicion" class="form-control mb-2" required>
                <label>Descripción</label>
                <textarea name="descripcion" class="form-control"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn text-white" style="background-color:#a44a20;"
                        onclick="guardarModal('condiciones','condicion_id','modalCondicion')">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>



<!-- Modal Obra social -->
<div class="modal fade" id="modalObraSocial" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formObraSocial" class="modal-content">

            <div class="modal-header" style="background-color:#a44a20;">
                <h5 class="modal-title text-white">Agregar obra social</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <label>Nombre de la obra social</label>
                <input type="text" name="nombre" class="form-control mb-2" required>

                <label>Código (opcional)</label>
                <input type="text" name="codigo" class="form-control mb-2">

                <label>Vigencia (opcional)</label>
                <input type="date" name="vigencia" class="form-control mb-2">

                <label>Estado</label>
                <select name="estado" class="form-select mb-2" required>
                    <option value="activa">Activa</option>
                    <option value="inactiva">Inactiva</option>
                </select>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn text-white" style="background-color:#a44a20;"
                        onclick="guardarModal('obras-sociales','obra_social_id','modalObraSocial')">
                    Guardar
                </button>
            </div>

        </form>
    </div>
</div>
<!-- Modal Editar Obra Social -->
<div class="modal fade" id="modalEditarObra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditarObra" class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white">Editar obra social</h5>
                <button type="button" class="btn-close"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="editar_obra_id">

                <label>Nombre</label>
                <input type="text" id="editar_nombre" name="nombre" class="form-control mb-2">

                <label>Código</label>
                <input type="text" id="editar_codigo" name="codigo" class="form-control mb-2">

                <label>Vigencia</label>
                <input type="date" id="editar_vigencia" name="vigencia" class="form-control mb-2">

                <label>Estado</label>
                <select id="editar_estado" name="estado" class="form-select">
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning text-white" onclick="guardarEdicionObra()">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Modal eliminar -->
<div class="modal fade" id="modalEliminarObra" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Eliminar obra social</h5>
                <button type="button" class="btn-close"></button>
            </div>
            <div class="modal-body">
                ¿Seguro que deseas eliminar esta obra social?
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" onclick="eliminarObra()">Eliminar</button>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
function guardarModal(endpoint, selectId, modalId) {
    const form = document.querySelector(`#${modalId} form`);
    const data = new FormData(form);

    fetch(`{{ url('rrhh') }}/${endpoint}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: data
    })
    .then(r => r.json())
    .then(result => {
        const select = document.getElementById(selectId);

        const texto =
            result.nombre_banco ??
            result.tipo_contrato ??
            result.nombre_condicion ??
            result.nombre ??
            'Nuevo';

        const option = new Option(texto, result.id, true, true);
        select.add(option);

        bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
        form.reset();
    })
    .catch(err => console.error('ERROR AL GUARDAR:', err));
}
</script>
@endpush
