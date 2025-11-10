<x-app-layout>
    <div class="flex h-screen bg-[#b34c1a] text-black">
        <!-- Sidebar -->
        <aside class="w-64 bg-[#a04518] text-black flex flex-col">
            <div class="flex items-center justify-center h-16 bg-[#b34c1a] border-b border-black/10">
                <img src="{{ asset('images/logo.webp') }}" alt="GVH Logo" class="w-10 h-auto mr-2 rounded">
                <span class="text-base font-bold uppercase tracking-wide"> Panel</span>
            </div>

            <nav class="flex-1 px-4 py-6">
                <ul class="space-y-2 font-semibold">
                    <li>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-lg bg-white/40 hover:bg-white/60 transition">
                            🏠 Inicio
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-3 rounded-lg hover:bg-white/50 transition">
                            👥 Empleados
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-3 rounded-lg hover:bg-white/50 transition">
                            📄 Documentación
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-3 rounded-lg hover:bg-white/50 transition">
                            ⚙️ Configuración
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-black/10 bg-[#b34c1a]/90">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left px-4 py-3 rounded-lg bg-white/40 hover:bg-white/60 font-semibold transition">
                        🔒 Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Top bar -->
            <header class="bg-[#e37c45] shadow-lg flex items-center justify-between px-6 h-16 text-black">
                <h1 class="text-2xl font-bold">Sistema de Gestión </h1>
            
            </header>

            <!-- Dashboard content -->
            <main class="flex-1 overflow-y-auto p-8 bg-[#f8d3b5]">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:scale-105 transform transition">
                        <h2 class="text-xl font-bold mb-2 text-[#b34c1a]">Recursos Humanos</h2>
                        <p class="text-gray-800 mb-4">Gestión de legajos, empleados y asistencia.</p>
                        <a href="#" class="bg-[#b34c1a] text-white px-4 py-2 rounded-full font-semibold hover:bg-[#933a10]">Entrar</a>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:scale-105 transform transition">
                        <h2 class="text-xl font-bold mb-2 text-[#b34c1a]">Operaciones</h2>
                        <p class="text-gray-800 mb-4">Control de maquinaria y personal activo.</p>
                        <a href="#" class="bg-[#b34c1a] text-white px-4 py-2 rounded-full font-semibold hover:bg-[#933a10]">Entrar</a>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 text-center hover:scale-105 transform transition">
                        <h2 class="text-xl font-bold mb-2 text-[#b34c1a]">Documentación</h2>
                        <p class="text-gray-800 mb-4">Gestión de archivos y reportes internos.</p>
                        <a href="#" class="bg-[#b34c1a] text-white px-4 py-2 rounded-full font-semibold hover:bg-[#933a10]">Entrar</a>
                    </div>
                </div>

                <div class="mt-12 text-center text-black/80 text-sm font-medium">
                    © {{ date('Y') }} <strong>GVH Logística Minera</strong> — Todos los derechos reservados.
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
