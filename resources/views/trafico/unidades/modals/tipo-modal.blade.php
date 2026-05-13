<div class="modal fade" id="modalTipo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header" style="background:#e37c45; color:white;">
                <h5 class="modal-title fw-bold">Agregar Tipo de Vehículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formNuevoTipo">
                @csrf

                <div class="modal-body">
                    <label class="form-label">Nombre del tipo</label>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           placeholder="Ej: Camioneta, Camión, Ambulancia"
                           required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>
