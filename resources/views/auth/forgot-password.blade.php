<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | GVH</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d3c61, #b34c1a);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Segoe UI", sans-serif;
        }

        .reset-container {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            color: #fff;
            text-align: center;
        }

        .reset-container img {
            width: 130px;
            margin-bottom: 20px;
        }

        h3 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        p {
            font-size: 0.95rem;
            color: #f2f2f2;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 10px;
            border: none;
            padding: 12px;
        }

        .btn-reset {
            background-color: #b34c1a;
            border: none;
            border-radius: 30px;
            padding: 12px;
            width: 100%;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-reset:hover {
            background-color: #8f3d14;
            box-shadow: 0 5px 15px rgba(179,76,26,0.4);
        }

        a {
            color: #ffd9c2;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="reset-container">
        <img src="{{ asset('images/logo.webp') }}" alt="GVH Logo">
        <h3>¿Olvidaste tu contraseña?</h3>
        <p>Ingresá tu correo electrónico y te enviaremos un enlace para restablecerla.</p>

        <!-- Mensaje de confirmación -->
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3 text-start">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" type="email" name="email" class="form-control" required autofocus>
            </div>

            <button type="submit" class="btn-reset">Enviar enlace de recuperación</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">← Volver al inicio de sesión</a>
    </div>

</body>
</html>
