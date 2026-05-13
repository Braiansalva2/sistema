<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Administrador - Sistema GVH')</title>

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

    <!-- NAVBAR SUPERIOR -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #e37c45;">
        <div class="container-fluid">
            <button class="btn text-dark d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                <i class="bi bi-list" style="font-size: 1.6rem;"></i>
            </button>

            <span class="navbar-brand fw-bold text-dark mb-0">
                Panel Administrador
            </span>
        </div>
    </nav>

    <div class="d-flex flex-grow-1" style="min-height: calc(100vh - 56px);">

        <!-- SIDEBAR ESCRITORIO -->
        <aside class="d-none d-lg-flex flex-column text-dark shadow-sm"
               style="width: 250px; background-color: #a04518; flex-shrink: 0; min-height: 100vh;">

            <!-- LOGO -->
            <div class="d-flex align-items-center justify-content-center py-3 border-bottom"
                 style="background-color: #b34c1a;">
                <img src="{{ asset('images/LOGO-gvh-26.webp') }}" class="rounded me-2" style="width: 40px;">
                <span class="fw-bold text-uppercase text-white">Panel</span>


                
            </div>

<!-- INFORMACIÓN DEL USUARIO LOGUEADO -->
<div class="text-center py-3 border-bottom" style="background-color: #b34c1a;">
    <h6 class="text-white mb-0">{{ Auth::user()->name }}</h6>
    <small class="text-white-50">{{ Auth::user()->email }}</small>

    <div class="mt-1">
        @foreach (Auth::user()->getRoleNames() as $rol)
            <span class="badge bg-light text-dark small">Rol: {{ $rol }}</span>
        @endforeach
    </div>
</div>
            <nav class="flex-grow-1 p-3">
                <ul class="nav flex-column fw-semibold">

                    @if(auth()->check() && auth()->user()->empleado)
    <a href="{{ route('empleado.index') }}"
       class="btn btn-sm btn-primary rounded-pill ms-2">
        <i class="bi bi-person-badge"></i>
        Mi Portal
    </a>
@endif
<br>
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.panel') }}"
                           class="nav-link text-dark bg-white bg-opacity-50 rounded px-3 py-2 hover-link">
                           🏠 Inicio
                        </a>
                    </li>

                    <!-- 🔐 PANEL ACL -->
                    <li class="mt-3 mb-1 ms-2 text-white-50 text-uppercase small">
                        Control de accesos
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('acl.usuarios.index') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">
                           👤 Usuarios
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('acl.roles.index') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">
                           🛡 Roles
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('acl.permisos.index') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">
                           🔑 Permisos
                        </a>
                    </li>

                    <hr class="my-3 text-light">

                    <li class="nav-item mb-2">
                        <a href="{{ route('profile.show') }}"
                            class="nav-link text-dark rounded px-3 py-2 hover-link">
                            👤 Perfil
                        </a>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="btn w-100 text-start bg-white bg-opacity-50 hover-link">
                                🔒 Cerrar sesión
                            </button>
                        </form>
                    </li>

                </ul>
            </nav>
        </aside>

        <!-- SIDEBAR MÓVIL -->
        <div class="offcanvas offcanvas-start" id="sidebarMobile" tabindex="-1" style="background-color: #a04518;">

            <div class="offcanvas-header" style="background-color: #b34c1a;">
                <img src="{{ asset('images/LOGO-gvh-26.webp') }}" class="rounded me-2" style="width: 40px;">
                <h5 class="offcanvas-title fw-bold text-dark">Panel</h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"></button>
            </div>
<!-- INFORMACIÓN DEL USUARIO LOGUEADO -->
<div class="text-center py-3 border-bottom" style="background-color: #b34c1a;">
    <h6 class="text-white mb-0">{{ Auth::user()->name }}</h6>
    <small class="text-white-50">{{ Auth::user()->email }}</small>

    <div class="mt-1">
        @foreach (Auth::user()->getRoleNames() as $rol)
            <span class="badge bg-light text-dark small">Rol: {{ $rol }}</span>
        @endforeach
    </div>
</div>
            <div class="offcanvas-body d-flex flex-column justify-content-between">
                <ul class="nav flex-column fw-semibold">


                    @if(auth()->check() && auth()->user()->empleado)
    <a href="{{ route('empleado.index') }}"
       class="btn btn-sm btn-primary rounded-pill ms-2">
        <i class="bi bi-person-badge"></i>
        Mi Portal
    </a>
@endif
<br>
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.panel') }}"
                           class="nav-link text-dark bg-white bg-opacity-50 rounded px-3 py-2 hover-link">
                           🏠 Inicio
                        </a>
                    </li>

                    <li class="mt-3 mb-1 ms-2 text-white-50 text-uppercase small">
                        Control de accesos
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('acl.usuarios.index') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">
                           👤 Usuarios
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('acl.roles.index') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">
                           🛡 Roles
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('acl.permisos.index') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">
                           🔑 Permisos
                        </a>
                    </li>

                    <hr class="my-3 text-light">

                    <li class="nav-item mb-2">
                        <a href="{{ route('profile.show') }}"
                            class="nav-link text-dark rounded px-3 py-2 hover-link">
                            👤 Perfil
                        </a>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="btn w-100 text-start bg-white bg-opacity-50 hover-link">
                                🔒 Cerrar sesión
                            </button>
                        </form>
                    </li>

                </ul>
            </div>
        </div>

        <!-- CONTENIDO -->
        <main class="flex-grow-1 p-4" style="background-color: #f8d3b5;">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
