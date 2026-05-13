<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tráfico - Sistema GVH')</title>

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
            transform: scale(1.03);
        }

        /* Sidebar fijo solo para DESKTOP */
        @media (min-width: 992px) {
            aside.sidebar-gvh {
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                height: 100vh;
                overflow-y: auto;
                background-color: #a04518;
            }

            main.content-gvh {
                margin-left: 250px !important;
            }
        }

        /* En móvil, el sidebar NO debe ocupar espacio */
        @media (max-width: 991px) {
            aside.sidebar-gvh {
                display: none !important;
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="bg-light">

    <!-- NAVBAR SUPERIOR -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #e37c45;">
        <div class="container-fluid">
            <button class="btn text-dark d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMobile" aria-controls="sidebarMobile">
                <i class="bi bi-list" style="font-size: 1.6rem;"></i>
            </button>

            <span class="navbar-brand fw-bold text-dark mb-0">Tráfico - Sistema GVH</span>
        </div>
    </nav>

    <div class="d-flex">

        <!-- SIDEBAR DESKTOP -->
        <aside class="sidebar-gvh d-none d-lg-flex flex-column text-dark shadow-sm">

            <!-- Encabezado -->
            <div class="d-flex align-items-center justify-content-center py-3 border-bottom"
                style="background-color: #b34c1a;">
                <img src="{{ asset('images/logo.webp') }}" alt="GVH Logo" class="rounded me-2" style="width: 40px;">
                <span class="fw-bold text-uppercase text-white">TRÁFICO</span>
            </div>

            <!-- Usuario -->
            <div class="text-center py-3 border-bottom" style="background-color: #b34c1a;">
                <h6 class="text-white mb-0">{{ Auth::user()->name }}</h6>
                <small class="text-white-50">{{ Auth::user()->email }}</small>
                <div class="mt-1">
                    @foreach (Auth::user()->getRoleNames() as $rol)
                        <span class="badge bg-light text-dark small">Rol: {{ $rol }}</span>
                    @endforeach
                </div>
            </div>

            <!-- Menú -->
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
                        <a href="{{ route('trafico.panel') }}" class="nav-link text-dark bg-white bg-opacity-50 rounded px-3 py-2 hover-link">
                            🚦 Inicio Tráfico
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('trafico.unidades.index') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">
                            🚛 Unidades
                        </a>
                    </li>

                   <li class="nav-item mb-2">
                        <a href="{{ route('trafico.unidades.monitoreo') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">
                          📡  Monitoreo
                        </a>
                    </li>

                    
                  

                    <hr class="my-3 text-light">

                    <li class="nav-item mb-2">
                        <a href="{{ route('profile.show') }}" class="nav-link text-dark rounded px-3 py-2 hover-link">
                            👤 Perfil
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn w-100 text-start bg-white bg-opacity-50 hover-link">
                                🔒 Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- OFFCANVAS MOBILE -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMobile" style="background-color: #a04518;">
            <div class="offcanvas-header border-bottom" style="background-color: #b34c1a;">
                <img src="{{ asset('images/logo.webp') }}" alt="GVH Logo" class="rounded me-2" style="width: 40px;">
                <h5 class="offcanvas-title fw-bold text-uppercase text-dark">Tráfico</h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"></button>
            </div>

            <!-- Usuario móvil -->
            <div class="text-center py-3 border-bottom" style="background-color: #b34c1a;">
                <h6 class="text-white mb-0">{{ Auth::user()->name }}</h6>
                <small class="text-white-50">{{ Auth::user()->email }}</small>
                <div class="mt-1">
                    @foreach (Auth::user()->getRoleNames() as $rol)
                        <span class="badge bg-light text-dark small">{{ $rol }}</span>
                    @endforeach
                </div>
            </div>

            <div class="offcanvas-body">
                <ul class="nav flex-column fw-semibold">
                    <li class="nav-item mb-2"><a href="{{ route('trafico.panel') }}" class="nav-link text-dark hover-link">🚦 Inicio Tráfico</a></li>
                    <li class="nav-item mb-2"><a href="{{ route('trafico.unidades.index') }}" class="nav-link text-dark hover-link">🚛 Unidades</a></li>
                    {{-- <li class="nav-item mb-2"><a href="{{ route('trafico.tipo_vehiculo.index') }}" class="nav-link text-dark hover-link">🚘 Tipos de Vehículo</a></li> --}}
                    {{-- <li class="nav-item mb-2"><a href="{{ route('trafico.viajes.index') }}" class="nav-link text-dark hover-link">📍 Viajes</a></li>
                    <li class="nav-item mb-2"><a href="{{ route('trafico.legajos_vehiculo.index') }}" class="nav-link text-dark hover-link">📂 Legajos Vehiculares</a></li> --}}

                    <hr class="my-3">

                    <li class="nav-item mb-2"><a href="{{ route('profile.show') }}" class="nav-link text-dark hover-link">👤 Perfil</a></li>
                    <li class="nav-item mb-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-light w-100">🔒 Cerrar sesión</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- CONTENIDO -->
        <main class="content-gvh flex-grow-1 p-3" style="background-color: #f8d3b5;">
            @yield('content')
        </main>

    </div>

  <!-- jQuery (obligatorio y debe ir primero!) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables CSS + JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap (AL FINAL SIEMPRE) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    console.log("Test jQuery:", typeof $);
</script>

@stack('scripts')


</body>
</html>
