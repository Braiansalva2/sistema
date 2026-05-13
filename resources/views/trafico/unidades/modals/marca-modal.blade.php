<div class="modal fade" id="modalMarca" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header" style="background:#e37c45; color:white;">
                <h5 class="modal-title fw-bold">Agregar Marca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formNuevaMarca">
                @csrf

                <div class="modal-body">

                    <div id="erroresMarca" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label class="form-label">Nombre de la marca</label>
                        <input type="text" name="nombre" class="form-control" required>
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
