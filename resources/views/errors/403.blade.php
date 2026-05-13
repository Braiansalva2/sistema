<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso no autorizado</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8d3b5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
        }
        .error-box {
            background: #ffffffdd;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            max-width: 500px;
            width: 90%;
            border-left: 8px solid #a04518;
        }
        .error-code {
            font-size: 72px;
            font-weight: bold;
            color: #a04518;
        }
        .error-message {
            font-size: 20px;
            color: #333;
        }
        .btn-custom {
            background-color: #a04518;
            color: white;
        }
        .btn-custom:hover {
            background-color: #e37c45;
            color: white;
        }
    </style>
</head>

<body>

    <div class="error-box">
        <img src="{{ asset('images/logo.webp') }}" alt="Logo" style="width: 80px;" class="mb-3">

        <div class="error-code">403</div>

        <p class="error-message fw-bold">No tenés permisos para acceder a esta sección.</p>
        <p class="text-muted">Si creés que es un error, comunicate con el administrador.</p>

        <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('dashboard') }}"
   class="btn btn-custom mt-3">
    ⬅ Volver atrás
</a>

    </div>

</body>
</html>
