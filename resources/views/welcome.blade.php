<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión GVH</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            text-align: center;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: #fff;
        }
        .logo {
            width: 200px;
            margin-bottom: 20px;
           
        }
        h1 {
            color: #b34c1a;
            font-weight: bold;
        }
        .btn-login {
            background-color: #b34c1a;
            color: white;
            border-radius: 30px;
            padding: 10px 30px;
            font-size: 18px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .btn-login:hover {
            background-color: #8f3d14;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ asset('images/logo.webp') }}" alt="GVH Logo" class="logo" >
        <h1>Sistema de Gestión GVH</h1>
        <p class="mb-4">Acceso restringido para personal autorizado</p>
        <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
    </div>
</body>
</html>
