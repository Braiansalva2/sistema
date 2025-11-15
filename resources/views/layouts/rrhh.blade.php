<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestión GVH')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .hover-link {
            transition: all 0.3s ease-in-out;
        }

        .hover-link:hover {
            background-color: #e37c45 !important;
            color: white !important;
            transform: scale(1.05);
        }

        @media (min-width: 992px) {
            aside {
                position: sticky;
                top: 0;
                height: 100vh;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex flex-column min-vh-100 bg-light">
        <!-- Navbar superior -->
        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #e37c45;">
            <div class="container-fluid">
                <button class="btn text-dark d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarMobile" aria-controls="sidebarMobile">
                    <i class="bi bi-list" style="font-size: 1.6rem;"></i>
                </button>
                <span class="navbar-brand fw-bold text-dark mb-0">Sistema de Gestión</span>
            </div>
        </nav>

        <div class="d-flex flex-grow-1" style="min-height: calc(100vh - 56px);">
            <!-- Sidebar -->
            <aside class="d-none d-lg-flex flex-column text-dark shadow-sm"
                style="width: 250px; background-color: #a04518; flex-shrink: 0; min-height: 100vh; overflow-y: auto;">
                <div class="d-flex align-items-center justify-content-center py-3 border-bottom"
                    style="background-color: #b34c1a;">
                    <img src="{{ asset('images/logo.webp') }}" alt="GVH Logo" class="rounded me-2" style="width: 40px;">
                    <span class="fw-bold text-uppercase text-white">Panel</span>
                </div>

                <nav class="flex-grow-1 p-3">
                    <ul class="nav flex-column fw-semibold">
                        <li class="nav-item mb-2"><a href="{{ route('dashboard') }}" class="nav-link text-dark bg-white bg-opacity-50 rounded px-3 py-2 hover-link">🏠 Inicio</a></li>
                        <li class="nav-item mb-2"><a href="{{ route('empleados.index') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">👥 Empleados</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">📄 Documentación</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">🏦 Bancos</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">🏥 Obra social</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">📑 Contratos</a></li>
                        <hr class="my-3 text-light">
                        <li class="nav-item mb-2"><a href="{{ route('profile.show') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">👤 Perfil</a></li>
                        <li class="nav-item mb-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn w-100 text-start bg-white bg-opacity-50 hover-link">🔒 Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </aside>

            <!-- Offcanvas móvil -->
            <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile"
                aria-labelledby="sidebarMobileLabel" style="background-color: #a04518;">
                <div class="offcanvas-header border-bottom" style="background-color: #b34c1a;">
                    <img src="{{ asset('images/logo.webp') }}" alt="GVH Logo" class="rounded me-2" style="width: 40px;">
                    <h5 class="offcanvas-title fw-bold text-uppercase text-dark">Panel</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body d-flex flex-column justify-content-between">
                    <ul class="nav flex-column fw-semibold">
                        <li class="nav-item mb-2"><a href="{{ route('dashboard') }}" class="nav-link text-dark bg-white bg-opacity-50 rounded px-3 py-2 hover-link">🏠 Inicio</a></li>
                        <li class="nav-item mb-2"><a href="{{ route('empleados.index') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">👥 Empleados</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">📄 Documentación</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">🏦 Bancos</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">🏥 Obra social</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">📑 Contratos</a></li>
                        <hr class="my-3 text-light">
                        <li class="nav-item mb-2"><a href="{{ route('profile.show') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">👤 Perfil</a></li>
                        <li class="nav-item mb-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn w-100 text-start bg-white bg-opacity-50 hover-link">🔒 Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contenido dinámico -->
            <main class="flex-grow-1 p-4" style="background-color: #f8d3b5;">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>
</html>
