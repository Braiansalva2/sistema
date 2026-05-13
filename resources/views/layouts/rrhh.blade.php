<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Sistema de Gestión GVH')
    </title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- ICONOS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
          rel="stylesheet">

    <style>

        /* =========================
           BODY
        ========================== */

        body{
            margin:0;
            overflow-x:hidden;
            background:#f8d3b5;
            font-family:'Segoe UI',sans-serif;
        }

        /* =========================
           LAYOUT
        ========================== */

        .layout{
            display:flex;
            min-height:100vh;
            width:100%;
        }

        /* =========================
           SIDEBAR
        ========================== */

        .sidebar{
            width:260px;
            min-width:260px;
            background:#a04518;
            color:#fff;
            transition:all .3s ease;
            overflow:hidden;
            display:flex;
            flex-direction:column;
            z-index:1000;
        }

        /* SIDEBAR CERRADO */
        .sidebar.collapsed{
            width:80px;
            min-width:80px;
        }

        /* LOGO */
        .sidebar-header{
            background:#b34c1a;
            height:70px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-bottom:1px solid rgba(255,255,255,.1);
        }

        .sidebar-logo{
            width:42px;
            height:42px;
            object-fit:cover;
        }

        .sidebar-title{
            font-weight:bold;
            margin-left:10px;
            white-space:nowrap;
            transition:.2s;
        }

        .sidebar.collapsed .sidebar-title{
            display:none;
        }

        /* USER */
        .sidebar-user{
            padding:15px;
            text-align:center;
            border-bottom:1px solid rgba(255,255,255,.1);
            background:#b34c1a;
        }

        .sidebar-user h6,
        .sidebar-user small{
            margin:0;
            white-space:nowrap;
        }

        .sidebar.collapsed .sidebar-user-info{
            display:none;
        }

        /* MENU */
        .sidebar-menu{
            padding:15px 10px;
            flex:1;
            overflow-y:auto;
        }

        .sidebar-menu .nav-link{
            color:#fff;
            border-radius:10px;
            margin-bottom:8px;
            display:flex;
            align-items:center;
            gap:12px;
            transition:.25s;
            padding:12px;
            white-space:nowrap;
        }

        .sidebar-menu .nav-link:hover{
            background:#e37c45;
            transform:translateX(4px);
        }

        /* SOLO ICONOS */
        .sidebar.collapsed .menu-text{
            display:none;
        }

        .sidebar.collapsed .nav-link{
            justify-content:center;
        }

        /* ICONO */
        .menu-icon{
            font-size:18px;
            min-width:22px;
            text-align:center;
        }

        /* =========================
           MAIN
        ========================== */

        .main-wrapper{
            flex:1;
            min-width:0;
            display:flex;
            flex-direction:column;
            overflow:hidden;
        }

        /* NAVBAR */
        .topbar{
            height:70px;
            background:#e37c45;
            display:flex;
            align-items:center;
            padding:0 20px;
            box-shadow:0 2px 10px rgba(0,0,0,.1);
            flex-shrink:0;
        }

        /* BOTON MENU */
        .toggle-btn{
            border:none;
            background:transparent;
            font-size:26px;
            color:#222;
        }

        /* TITULO */
        .topbar-title{
            font-weight:bold;
            margin-left:15px;
            color:#222;
        }

        /* CONTENIDO */
        .main-content{
            flex:1;
            min-width:0;
            overflow:auto;
            padding:20px;
            background:#f8d3b5;
        }

        /* =========================
           MOBILE
        ========================== */

        @media(max-width:991px){

            .sidebar{
                position:fixed;
                left:-260px;
                top:0;
                height:100vh;
                z-index:2000;
            }

            .sidebar.mobile-show{
                left:0;
            }

            .sidebar.collapsed{
                width:260px;
                min-width:260px;
            }

            .sidebar.collapsed .menu-text{
                display:block;
            }

            .sidebar.collapsed .sidebar-title{
                display:block;
            }

            .sidebar.collapsed .sidebar-user-info{
                display:block;
            }

            .sidebar.collapsed .nav-link{
                justify-content:flex-start;
            }

            /* OVERLAY */
            .overlay{
                position:fixed;
                inset:0;
                background:rgba(0,0,0,.5);
                z-index:1500;
                display:none;
            }

            .overlay.show{
                display:block;
            }

        }

        /* =========================
           SCROLLBAR
        ========================== */

        ::-webkit-scrollbar{
            width:10px;
            height:10px;
        }

        ::-webkit-scrollbar-thumb{
            background:#c77748;
            border-radius:20px;
        }

        ::-webkit-scrollbar-track{
            background:#f1f1f1;
        }

    </style>

</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar"
           id="sidebar">

        <!-- HEADER -->
        <div class="sidebar-header">

            <img src="{{ asset('images/LOGO-gvh-26.webp') }}"
                 class="sidebar-logo">

            <span class="sidebar-title">
                RRHH
            </span>

        </div>

        <!-- USER -->
        <div class="sidebar-user">

            <div class="sidebar-user-info">

                <h6>
                    {{ Auth::user()->name }}
                </h6>

                <small>
                    {{ Auth::user()->email }}
                </small>

                <div class="mt-2">

                    @foreach(Auth::user()->getRoleNames() as $rol)

                        <span class="badge bg-light text-dark">
                            {{ $rol }}
                        </span>

                    @endforeach

                </div>

            </div>

        </div>

        <!-- MENU -->
        <div class="sidebar-menu">

            @if(auth()->check() && auth()->user()->empleado)
    <a href="{{ route('empleado.index') }}"
       class="btn btn-sm btn-primary rounded-pill ms-2">
        <i class="bi bi-person-badge"></i>
        Mi Portal
    </a>
@endif
<br>
            <a href="{{ route('rrhh.dashboard') }}"
               class="nav-link">

                <span class="menu-icon">🏠</span>

                <span class="menu-text">
                    Inicio
                </span>

            </a>

            <a href="{{ route('rrhh.empleados.index') }}"
               class="nav-link">

                <span class="menu-icon">👥</span>

                <span class="menu-text">
                    Empleados
                </span>

            </a>

            <a href="{{ route('rrhh.usuarios.index') }}"
               class="nav-link">

                <span class="menu-icon">👤</span>

                <span class="menu-text">
                    Usuarios
                </span>

            </a>

            <a href="{{ route('rrhh.adelantos.index') }}"
               class="nav-link">

                <span class="menu-icon">💰</span>

                <span class="menu-text">
                    Adelantos
                </span>

            </a>

            <a href="{{ route('rrhh.tipos-prenda.index') }}"
               class="nav-link">

                <span class="menu-icon">👕</span>

                <span class="menu-text">
                    Indumentaria
                </span>

            </a>

  <a href="{{ route('rrhh.rosters.index') }}"
       class="nav-link text-white {{ request()->routeIs('rrhh.rosters.*') ? 'active bg-primary rounded shadow-sm' : '' }}">
       
        <i class="bi bi-calendar3 me-2"></i>
        <span>Roster</span>
    </a>


            <a href="{{ route('rrhh.licencias.index') }}"
               class="nav-link">

                <span class="menu-icon">📋</span>

                <span class="menu-text">
                    Licencias
                </span>

            </a>

           <li class="nav-item">

    <a class="nav-link" data-bs-toggle="collapse" href="#menuAsistencias" role="button">
        <span class="menu-icon">🕒</span>
        <span class="menu-text">Asistencias</span>
    </a>

    <div class="collapse" id="menuAsistencias">

        <ul class="nav flex-column ms-3">

            <li class="nav-item">
                <a href="{{ route('rrhh.asistencias.index') }}" class="nav-link">
                    👤 Base (Asistencias)
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('rrhh.operativo') }}" class="nav-link">
                    📍 Operativo en vivo
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('rrhh.asistencias.matriz') }}" class="nav-link">
                    📊 Matriz
                </a>
            </li>

        </ul>

    </div>

</li>

            <a href="{{ route('rrhh.sucursales.index') }}"
               class="nav-link">

                <span class="menu-icon">🏢</span>

                <span class="menu-text">
                    Sucursales
                </span>

            </a>

            <hr class="text-light">

            <a href="{{ route('profile.show') }}"
               class="nav-link">

                <span class="menu-icon">👤</span>

                <span class="menu-text">
                    Perfil
                </span>

            </a>

            <form method="POST"
                  action="{{ route('logout') }}">

                @csrf

                <button type="submit"
                        class="nav-link border-0 w-100 text-start bg-transparent">

                    <span class="menu-icon">🔒</span>

                    <span class="menu-text">
                        Cerrar sesión
                    </span>

                </button>

            </form>

        </div>

    </aside>

    <!-- MAIN -->
    <div class="main-wrapper">

        <!-- NAVBAR -->
        <div class="topbar">

            <button class="toggle-btn"
                    id="toggleSidebar">

                <i class="bi bi-list"></i>

            </button>

            <div class="topbar-title">
                Sistema de Gestión
            </div>

        </div>

        <!-- CONTENIDO -->
        <main class="main-content">

            @yield('content')

        </main>

    </div>

</div>

<!-- OVERLAY MOBILE -->
<div class="overlay"
     id="overlay"></div>

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- BOOTSTRAP -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DATATABLES -->
<link rel="stylesheet"
      href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>

const sidebar =
    document.getElementById('sidebar');

const toggle =
    document.getElementById('toggleSidebar');

const overlay =
    document.getElementById('overlay');

toggle.addEventListener('click', ()=>{

    // MOBILE
    if(window.innerWidth <= 991){

        sidebar.classList.toggle('mobile-show');

        overlay.classList.toggle('show');

    }

    // DESKTOP
    else{

        sidebar.classList.toggle('collapsed');

    }

});

/* CERRAR MOBILE */
overlay.addEventListener('click', ()=>{

    sidebar.classList.remove('mobile-show');

    overlay.classList.remove('show');

});

</script>

@stack('scripts')

</body>
</html>