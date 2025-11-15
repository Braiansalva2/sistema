<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso | Sistema de Gestión GVH</title>

    <!-- Bootstrap -->
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

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            color: #fff;
        }

        .login-container img {
            width: 140px;
            margin-bottom: 15px;
        }

        .login-container h3 {
            margin-bottom: 25px;
            font-weight: 700;
        }

        .form-control {
            border-radius: 10px;
            border: none;
            padding: 12px;
        }

        .btn-login {
            background-color: #b34c1a;
            border: none;
            border-radius: 30px;
            padding: 12px;
            width: 100%;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-login:hover {
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

        .form-check-label {
            color: #fff;
        }
    </style>
</head>
<body>

    <div class="login-container text-center">
        <img src="{{ asset('images/logo.webp') }}" alt="GVH Logo">
        <h3>Sistema de Gestión GVH</h3>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3 text-start">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" type="email" class="form-control" name="email" required autofocus>
            </div>

            <div class="mb-3 text-start">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password" class="form-control" name="password" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label" for="remember_me">Recordarme</label>
                </div>
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn-login">Ingresar</button>
        </form>
    </div>

</body>
</html>
