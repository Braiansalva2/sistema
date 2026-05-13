<div class="modal fade" id="modalEmpresaTercerizada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Nueva Empresa Tercerizada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formNuevaEmpresaTercerizada">
                @csrf

                <div class="modal-body">

                    <div id="erroresEmpresaTercerizada" class="alert alert-danger d-none">
                        {{-- Aquí se mostrarán errores de validación --}}
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre de la empresa *</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">CUIT</label>
                        <input type="text" name="cuit" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Correo electrónico</label>
                        <input type="email" name="correo" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Responsable / Contacto</label>
                        <input type="text" name="responsable" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-dark">
                        Guardar Empresa
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
