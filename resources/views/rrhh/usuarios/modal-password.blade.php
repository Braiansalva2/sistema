<div class="modal fade" id="modalPassword{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">Cambiar contraseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ route('rrhh.usuarios.password', $user) }}">
                @csrf @method('PUT')

                <div class="modal-body">

                    <label>Nueva contraseña</label>
                    <div class="input-group mb-3">
                        <input type="password" id="password{{$user->id}}" name="password" class="form-control" required>

                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password{{$user->id}}', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>

                        <button type="button" class="btn btn-secondary"
                            onclick="generarPass('password{{$user->id}}','password_confirmation{{$user->id}}')">
                            Generar
                        </button>
                    </div>

                    <label>Repetir contraseña</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation{{$user->id}}" name="password_confirmation" class="form-control" required>
                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password_confirmation{{$user->id}}', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
function generarPass(passId, confirmId) {
    let chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    let pass = "";
    for (let i = 0; i < 12; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    document.getElementById(passId).value = pass;
    document.getElementById(confirmId).value = pass;
}

function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>
