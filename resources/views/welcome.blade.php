<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión GVH</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0d3c61, #b34c1a);
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
        }

        .container-login {
            text-align: center;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
            max-width: 420px;
            width: 90%;
            animation: fadeIn 0.9s ease;
        }

        .logo {
            width: 180px;
            margin-bottom: 25px;
            transition: transform 0.4s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        h1 {
            font-weight: 700;
            font-size: 1.9rem;
            margin-bottom: 10px;
        }

        p {
            font-size: 1rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .btn-login {
            background-color: #b34c1a;
            border: none;
            border-radius: 50px;
            padding: 12px 40px;
            font-size: 18px;
            font-weight: 600;
            color: #fff;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-login:hover {
            background-color: #8f3d14;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(179, 76, 26, 0.4);
        }

        footer {
            position: absolute;
            bottom: 15px;
            text-align: center;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="container-login">
        <img src="{{ asset('images/logo.webp') }}" alt="GVH Logo" class="logo">
        <h1>Sistema de Gestión</h1>
        <p>Acceso restringido para personal autorizado</p>
        <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
    </div>

    <footer>
        © {{ date('Y') }} GVH | Todos los derechos reservados
    </footer>

</body>
</html>
