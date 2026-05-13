<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Documentación - Sistema GVH')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --gvh-orange: #e37c45;
            --gvh-dark: #a04518;
            --gvh-light: #f8d3b5;
        }

        .hover-link {
            transition: all 0.3s ease-in-out;
        }

        .hover-link:hover {
            background-color: var(--gvh-orange) !important;
            color: white !important;
            transform: scale(1.05);
        }

        aside {
            position: sticky;
            top: 0;
            height: 100vh;
        }

        body {
            overflow-x: hidden;
        }
    </style>
</head>

<body>

<div class="d-flex flex-column min-vh-100" style="background-color: #f4f4f4;">

    <!-- NAVBAR -->
    <nav class="navbar navbar-dark shadow-sm" style="background-color: var(--gvh-orange);">
        <div class="container-fluid">
            <button class="btn text-white d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMobile">
                <i class="bi bi-list" style="font-size: 1.6rem;"></i>
            </button>

            <span class="navbar-brand fw-bold">
                DOCUMENTACION
            </span>
        </div>
    </nav>  

    <div class="d-flex flex-grow-1">

        <!-- SIDEBAR -->
        <aside class="d-none d-lg-flex flex-column shadow-sm"
            style="width: 250px; background-color: var(--gvh-dark);">

            <!-- HEADER -->
            <div class="text-center py-3 border-bottom" style="background-color: var(--gvh-orange);">
                <img src="{{ asset('images/LOGO-gvh-26.webp') }}" width="60">
              
            </div>

            <!-- USER -->
            <div class="text-center py-3 border-bottom text-white">
                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                <small>{{ Auth::user()->email }}</small>

                <div class="mt-1">
                    @foreach (Auth::user()->getRoleNames() as $rol)
                        <span class="badge bg-light text-dark small">ROL:{{ $rol }}</span>
                    @endforeach
                </div>
            </div>

            <!-- MENU -->
            <nav class="flex-grow-1 p-3">
                <ul class="nav flex-column fw-semibold">

                    @if(auth()->check() && auth()->user()->empleado)
                        <a href="{{ route('empleado.index') }}"
                        class="btn btn-sm btn-primary rounded-pill ms-2">
                            <i class="bi bi-person-badge"></i>
                            Mi Portal
                        </a>
                    @endif
                    <!-- DASHBOARD -->
                    <li class="nav-item mb-2">
                        <a href="{{ route('dashboard') }}" class="nav-link text-white hover-link">
                            🏠 Inicio
                        </a>
                    </li>

                    <!-- VIATICOS -->
                    <li class="nav-item mb-2">
                        <a href="{{ route('documentacion.viaticos.index') }}" class="nav-link text-white hover-link">
                            💸 Gestión de Viáticos
                        </a>
                    </li>

                    <!-- FUTURO -->
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-white hover-link">
                            📂 Documentos
                        </a>
                    </li>

                    <hr class="text-light">

                    <!-- PERFIL -->
                    <li class="nav-item mb-2">
                        <a href="{{ route('profile.show') }}" class="nav-link text-white hover-link">
                            👤 Perfil
                        </a>
                    </li>

                    <!-- LOGOUT -->
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn w-100 text-start text-white hover-link">
                                🔒 Cerrar sesión
                            </button>
                        </form>
                    </li>

                </ul>
            </nav>

        </aside>

        <!-- CONTENIDO -->
        <main class="flex-grow-1 p-4" style="background-color: #f4f4f4;">
            @yield('content')
        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>