<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Área Comercial - GVH')</title>

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

        /* ================= FIX CRÍTICO ================= */
        main {
            min-width: 0;          /* CLAVE: evita que flex rompa el layout */
            overflow-x: hidden;
        }

        table {
            max-width: 100%;
        }
        /* ================================================= */

    </style>

    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
</head>

<body>
<div class="d-flex flex-column min-vh-100" style="background-color: #f8d3b5;">
  
    <!-- NAVBAR SUPERIOR -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #e37c45;">
        <div class="container-fluid">
            <button class="btn text-dark d-lg-none me-2"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarMobile">
                <i class="bi bi-list" style="font-size: 1.6rem;"></i>
            </button>

            <span class="navbar-brand fw-bold text-dark mb-0">
                Sistema de Gestion GVH
            </span>
        </div>
    </nav>

    <div class="d-flex flex-grow-1">

        <!-- SIDEBAR ESCRITORIO -->
        <aside class="d-none d-lg-flex flex-column shadow-sm"
               style="width: 250px; background-color: #a04518; overflow-y: auto;">
            
            <div class="d-flex align-items-center justify-content-center py-3 border-bottom"
                 style="background-color: #b34c1a;">
                <img src="{{ asset('images/logo.webp') }}"
                     alt="GVH Logo"
                     class="rounded me-2"
                     style="width: 40px;">
                <span class="fw-bold text-uppercase text-white">Comercial</span>
            </div>

            <!-- USUARIO -->
            <div class="text-center py-3 border-bottom" style="background-color: #b34c1a;">
                <h6 class="text-white mb-0">{{ Auth::user()->name }}</h6>
                <small class="text-white-50">{{ Auth::user()->email }}</small>

                <div class="mt-1">
                    @foreach (Auth::user()->getRoleNames() as $rol)
                        <span class="badge bg-light text-dark small">{{ $rol }}</span>
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

                    <li class="nav-item mb-2">
                        <a href="{{ route('comercial.dashboard') }}"
                           class="nav-link text-dark bg-white bg-opacity-50 rounded px-3 py-2 hover-link">
                            🏠 Inicio
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('comercial.clientes.index') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">
                            👥 Clientes
                        </a>
                    </li>

                    {{-- <li class="nav-item mb-2">
                        <a href="{{ route('comercial.cod_product') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">
                             📄Cod-product
                        </a>
                    </li> --}}

                    <li class="nav-item mb-2">
                        <a href="{{ route('comercial.tramos.index') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">
                            🚚 Tramos
                        </a>
                    </li> 
                  
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">
                            📑Contratos
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">
                            💲Cotizaciones
                        </a>
                    </li>

                    <hr class="text-light my-3">

                    <li class="nav-item mb-2">
                        <a href="{{ route('profile.show') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">
                            👤 Perfil
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="btn text-start bg-white bg-opacity-50 hover-link w-100">
                                🔒 Cerrar sesión
                            </button>
                        </form>
                    </li>

                </ul>
            </nav>
        </aside>

        <!-- SIDEBAR MÓVIL -->
        <div class="offcanvas offcanvas-start"
             tabindex="-1"
             id="sidebarMobile"
             style="background-color: #a04518;">

            <div class="offcanvas-header border-bottom" style="background-color: #b34c1a;">
                <img src="{{ asset('images/LOGO-gvh-26.webp') }}"
                     style="width: 40px;"
                     class="rounded me-2">
                <h5 class="offcanvas-title fw-bold text-uppercase text-dark">Comercial</h5>
                <button class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"></button>
            </div>

            <div class="offcanvas-body">
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
                        <a href="{{ route('comercial.dashboard') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">🏠 Inicio</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('comercial.clientes.index') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">👥 Clientes</a>
                    </li>

                    {{-- <li class="nav-item mb-2">
                        <a href="{{ route('comercial.cod_product') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">📄Cod-product</a>
                    </li> --}}

                    <li class="nav-item mb-2">
                        <a href="{{ route('comercial.tramos.index') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">🚚 Tramos</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">📑 Contratos</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-dark rounded px-3 py-2 hover-link">💲 Cotizaciones</a>
                    </li>

                    <hr class="text-light my-3">

                    <li class="nav-item mb-2">
                        <a href="{{ route('profile.show') }}"
                           class="nav-link text-dark rounded px-3 py-2 hover-link">👤 Perfil</a>
                    </li>

                    <li class="nav-item mb-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="btn text-start bg-white bg-opacity-50 hover-link w-100">
                                🔒 Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- CONTENIDO -->
        <main class="flex-grow-1 p-4" style="min-width:0;">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>

    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('scripts')



@yield('scripts')


</body>
</html>