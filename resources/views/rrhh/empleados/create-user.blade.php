@extends('layouts.rrhh')

@section('content')
<div class="container py-4">

    <h3 class="mb-4 text-primary">
        Crear Usuario para: {{ $empleado->nombre }} {{ $empleado->apellido }}
    </h3>

    {{-- FORMULARIO CORRECTO --}}
    <form action="{{ route('rrhh.empleados.storeUser', $empleado) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Email institucional</label>
            <input type="email" name="email" class="form-control" required>
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label>Rol del sistema</label>
            <select name="role" class="form-control" required>
                @foreach($roles as $role)
                    <option value="{{ $role }}">{{ $role }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Contraseña</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required>

                    <button type="button" class="btn btn-outline-secondary"
                        onclick="togglePassword('password', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>

                    <button type="button" class="btn btn-secondary" onclick="generarPass()">
                        Generar
                    </button>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label>Repetir contraseña</label>
                <div class="input-group">
                    <input type="password" id="password_confirmation"
                           name="password_confirmation" class="form-control" required>

                    <button type="button" class="btn btn-outline-secondary"
                        onclick="togglePassword('password_confirmation', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <button class="btn btn-success mt-3">Crear Usuario</button>
        <a href="{{ route('rrhh.empleados.show', $empleado) }}" class="btn btn-secondary mt-3">
            Cancelar
        </a>
    </form>

</div>

{{-- Scripts --}}
<script>
function generarPass() {
    let chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    let pass = "";
    for (let i = 0; i < 12; i++) {
        pass += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    document.getElementById('password').value = pass;
    document.getElementById('password_confirmation').value = pass;
}

function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}
</script>

<link rel="stylesheet" 
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@endsection
