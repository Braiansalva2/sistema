<div class="modal fade" id="modalModelo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header" style="background:#e37c45; color:white;">
                <h5 class="modal-title fw-bold">Agregar Modelo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

           <form id="formNuevoModelo">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nombre del modelo</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Marca</label>
                        <select name="marca_id" id="marca_id_modal" class="form-select" required>
                            <option value="">Seleccione una marca</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>


        </div>
    </div>
</div>
