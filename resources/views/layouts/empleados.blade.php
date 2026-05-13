<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GVH Empleado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- ICONOS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to bottom, #eef2f7, #f4f6f9);
            font-family: 'Segoe UI', sans-serif;
        }

        /* NAVBAR GVH */
        .navbar-custom {
            background: linear-gradient(90deg, #0d3c61, #0a2c47);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .logo {
            height: 40px;
        }

        /* BOTÓN MENU */
        .btn-outline-light {
            border-radius: 8px;
        }

        /* TARJETAS */
        .card-opcion {
            border-radius: 15px;
            transition: 0.3s;
            cursor: pointer;
            border: none;
            background: #fff;
        }

        .card-opcion:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .icono {
            font-size: 35px;
            color: #0d3c61;
        }

        /* PERFIL */
        .perfil-box {
            background: #fff;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #0d3c61;
        }

        /* MENU LATERAL */
        .offcanvas {
            border-radius: 0 15px 15px 0;
        }

        .offcanvas a {
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            transition: 0.3s;
            display: block;
            color: #333;
            font-weight: 500;
        }

        .offcanvas a:hover {
            background: #0d3c61;
            color: #fff;
            padding-left: 15px;
        }

        /* FOOTER */
        footer {
            background: #0d3c61;
            color: #fff;
            font-size: 12px;
        }
    </style>
</head>

<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-dark navbar-custom px-3">

    {{-- HAMBURGUESA --}}
    <button class="btn btn-outline-light me-2" data-bs-toggle="offcanvas" data-bs-target="#menu">
        <i class="bi bi-list fs-5"></i>
    </button>

    {{-- LOGO --}}
    <img src="{{ asset('images/LOGO-gvh-26.webp') }}" class="logo">

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}" class="ms-auto">
        @csrf
        <button class="btn btn-sm btn-warning rounded-pill px-3">
            <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>

</nav>

{{-- MENU LATERAL --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="menu">
    <div class="offcanvas-header">
        <h5>Menú</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">

        <a href="{{ route('empleado.index') }}" class="mb-2">
            <i class="bi bi-house-door"></i> Inicio
        </a>

        <a href="{{ route('empleado.perfil') }}" class="mb-2">
            <i class="bi bi-person"></i> Mi perfil
        </a>

       <a href="{{ route('empleado.asistencia') }}" class="mb-2">
            <i class="bi bi-clock"></i> Asistencia
        </a>

        <a href="{{ route('empleado.licencias') }}" class="mb-2">
            <i class="bi bi-calendar-event"></i> Licencias
        </a>

        {{-- <a href="{{ route('empleado.permisos') }}" class="mb-2">
            <i class="bi bi-calendar-check"></i> Permisos
        </a> --}}

        <a href="{{ route('empleado.adelantos') }}" class="mb-2">
            <i class="bi bi-cash-stack"></i> Adelantos
        </a>

    </div>
</div>

{{-- CONTENIDO --}}
<div class="container py-3">
    @yield('content')
</div>

{{-- FOOTER --}}
<footer class="text-center py-2 mt-3">
    <small>GVH Logística Minera © {{ date('Y') }}</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>