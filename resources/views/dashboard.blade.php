<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GestiChalbaud</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f7f7f3; }
        /* Toast */
        #toast-container { position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999; display:flex; flex-direction:column; gap:.75rem; }
        .toast { min-width:280px; max-width:380px; padding:1rem 1.25rem; border-radius:1.25rem; font-weight:700; font-size:.85rem;
            display:flex; align-items:center; gap:.75rem; box-shadow:0 8px 30px rgba(0,0,0,.12);
            animation: slideIn .3s ease; }
        @keyframes slideIn { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .toast-success { background:#f0fdf4; border:2px solid #bbf7d0; color:#15803d; }
        .toast-error   { background:#fef2f2; border:2px solid #fecaca; color:#b91c1c; }
        .toast-warning { background:#fffbeb; border:2px solid #fde68a; color:#92400e; }
        /* Password strength meter */
        .strength-bar { height:4px; border-radius:9999px; transition: width .3s, background .3s; }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-xl flex flex-col">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <img src="/assets/logo.jpeg" alt="Logo" class="w-8 h-8 object-cover rounded-full ring-2 ring-[#1A237E]/20" onerror="this.style.display='none'">
                <h1 class="text-lg font-black text-[#1A237E] leading-tight">GestiChalbaud</h1>
            </div>
            <!-- Detalle rojo sutil debajo del logo -->
            <div class="h-0.5 bg-gradient-to-r from-[#D32F2F] to-transparent mt-2 -mb-2 rounded-full"></div>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-[#1A237E] text-white font-bold' : 'text-gray-600 hover:bg-[#1A237E]/5 font-semibold' }} transition-colors">Dashboard</a>
            
            @if(Auth::user()->isAdmin())
                <a href="{{ route('school-years.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('school-years.*') ? 'bg-[#1A237E] text-white font-bold' : 'text-gray-600 hover:bg-[#1A237E]/5 font-semibold' }} transition-colors">Años Escolares</a>
                <a href="{{ route('students.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('students.*') ? 'bg-[#1A237E] text-white font-bold' : 'text-gray-600 hover:bg-[#1A237E]/5 font-semibold' }} transition-colors">Estudiantes</a>
                <a href="{{ route('representatives.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('representatives.*') ? 'bg-[#FBC02D] text-[#1A237E] font-bold shadow-md shadow-[#FBC02D]/20' : 'text-gray-600 hover:bg-[#FBC02D]/10 font-semibold' }} transition-colors">Representantes</a>
                <a href="{{ route('sections.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('sections.*') ? 'bg-[#FBC02D] text-[#1A237E] font-bold shadow-md shadow-[#FBC02D]/20' : 'text-gray-600 hover:bg-[#FBC02D]/10 font-semibold' }} transition-colors">Aulas y Asignaciones</a>
                <a href="{{ route('subjects.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('subjects.*') ? 'bg-[#1A237E] text-white font-bold' : 'text-gray-600 hover:bg-[#1A237E]/5 font-semibold' }} transition-colors">Materias</a>
                <a href="{{ route('teachers.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('teachers.*') ? 'bg-[#1A237E] text-white font-bold' : 'text-gray-600 hover:bg-[#1A237E]/5 font-semibold' }} transition-colors">Docentes</a>
                <a href="{{ route('graduates.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('graduates.*') ? 'bg-[#1A237E] text-white font-bold' : 'text-gray-600 hover:bg-[#1A237E]/5 font-semibold' }} transition-colors">Egresados</a>
                <div class="pt-2 mt-2 border-t border-gray-100"></div>
                <a href="{{ route('users.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('users.*') ? 'bg-purple-600 text-white font-bold shadow-md shadow-purple-600/20' : 'text-gray-600 hover:bg-gray-50 font-semibold' }} transition-colors">Gestión de Usuarios</a>
            @endif

            @if(Auth::user()->isDocente())
                <a href="{{ route('students.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('students.*') ? 'bg-[#1A237E] text-white font-bold' : 'text-gray-600 hover:bg-[#1A237E]/5 font-semibold' }} transition-colors">Mis Estudiantes</a>
                <a href="{{ route('representatives.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('representatives.*') ? 'bg-[#FBC02D] text-[#1A237E] font-bold shadow-md shadow-[#FBC02D]/20' : 'text-gray-600 hover:bg-[#FBC02D]/10 font-semibold' }} transition-colors">Directorio de Representantes</a>
                <a href="{{ route('sections.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('sections.*') ? 'bg-[#FBC02D] text-[#1A237E] font-bold shadow-md shadow-[#FBC02D]/20' : 'text-gray-600 hover:bg-[#FBC02D]/10 font-semibold' }} transition-colors">Mi Aula Asignada</a>
                <a href="{{ route('graduates.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('graduates.*') ? 'bg-[#1A237E] text-white font-bold' : 'text-gray-600 hover:bg-[#1A237E]/5 font-semibold' }} transition-colors">Egresados</a>
            @endif

            @if(Auth::user()->isRepresentative())
                <a href="{{ route('representative.students.index') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('representative.students.*') ? 'bg-[#032e5e] text-white font-bold' : 'text-gray-600 hover:bg-gray-50 font-semibold' }} transition-colors">Mis Representados</a>
            @endif

            <div class="pt-4 mt-4 border-t border-gray-100">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 rounded-xl {{ request()->routeIs('profile.*') ? 'bg-orange-50 text-[#c56c39] font-bold' : 'text-gray-600 hover:bg-gray-50 font-semibold' }} transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Mi Perfil
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 font-bold transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Bienvenido, {{ Auth::user()->first_name }}</h2>
            <div class="flex items-center space-x-4">
                <span class="px-4 py-2 bg-[#FBC02D]/15 text-[#1A237E] rounded-full text-xs font-bold uppercase tracking-widest border border-[#FBC02D]/40">
                    {{ Auth::user()->role->name }}
                </span>
            </div>
        </header>

        <!-- Alerts and Notifications -->
        @if(Auth::user()->isAdmin() && isset($pendingStudentsCount) && $pendingStudentsCount > 0)
            <div class="mb-8 p-6 bg-blue-600 rounded-[2rem] shadow-xl shadow-blue-200 flex items-center justify-between text-white animate-fade-in relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <div class="flex items-center relative z-10">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mr-4 backdrop-blur-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-lg">Inscripciones Pendientes</h3>
                        <p class="text-white/80 font-bold text-sm">Hay {{ $pendingStudentsCount }} estudiante(s) registrado(s) por representantes que esperan asignación de aula.</p>
                    </div>
                </div>
                <a href="{{ route('students.index', ['filter' => 'pending']) }}" class="relative z-10 px-6 py-3 bg-white text-blue-600 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-50 transition-all shadow-lg">
                    Asignar Ahora
                </a>
            </div>
        @endif

        {{-- Toast container --}}
        <div id="toast-container"></div>

        @if(session('success') || session('warning') || session('error') || $errors->any())
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast({{ json_encode(session('success')) }}, 'success');
            @endif
            @if(session('warning'))
                showToast({{ json_encode(session('warning')) }}, 'warning');
            @endif
            @if(session('error'))
                showToast({{ json_encode(session('error')) }}, 'error');
            @endif
            @if($errors->any())
                showToast('Corrige los errores indicados en el formulario.', 'error');
            @endif
        });
        </script>
        @endif

        @yield('content')
        
        @if(request()->routeIs('dashboard'))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Widget: Students -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-xl transition-all">
                    <div>
                        <h3 class="text-gray-400 font-bold text-[10px] uppercase mb-2 tracking-[0.2em]">Total Estudiantes</h3>
                        <p class="text-4xl font-black text-[#1A237E]">{{ $stats['total_students'] ?? 0 }}</p>
                    </div>
                    <!-- Detalle rojo sutil en hover del icono -->
                    <div class="w-14 h-14 rounded-2xl bg-[#1A237E]/5 flex items-center justify-center group-hover:bg-[#1A237E] group-hover:text-white transition-all border-b-2 border-transparent group-hover:border-[#D32F2F]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>

                <!-- Widget: Teachers -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-xl transition-all">
                    <div>
                        <h3 class="text-gray-400 font-bold text-[10px] uppercase mb-2 tracking-[0.2em]">Cuerpo Docente</h3>
                        <p class="text-4xl font-black text-[#1A237E]">{{ $stats['total_teachers'] ?? 0 }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-[#FBC02D]/10 flex items-center justify-center group-hover:bg-[#FBC02D] group-hover:text-[#1A237E] transition-all text-[#FBC02D] border-b-2 border-transparent group-hover:border-[#D32F2F]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                </div>

                <!-- Widget: Sections -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-xl transition-all">
                    <div>
                        <h3 class="text-gray-400 font-bold text-[10px] uppercase mb-2 tracking-[0.2em]">Aulas Activas</h3>
                        <p class="text-4xl font-black text-[#1A237E]">{{ $stats['total_sections'] ?? 0 }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-[#1A237E]/5 flex items-center justify-center group-hover:bg-[#1A237E] group-hover:text-white transition-all text-[#1A237E] border-b-2 border-transparent group-hover:border-[#D32F2F]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                </div>
            </div>
        @endif
    </main>

<script>
// ── Toast system ──────────────────────────────────────────────
function showToast(message, type = 'success', duration = 4500) {
    const icons = {
        success: '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
        error:   '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        warning: '<svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    };
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `${icons[type]}<span class="flex-1">${message}</span><button onclick="this.parentElement.remove()" class="opacity-60 hover:opacity-100 ml-2">✕</button>`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateY(20px)'; toast.style.transition='all .3s'; setTimeout(() => toast.remove(), 300); }, duration);
}

// ── Password Strength Meter ────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="password"][data-strength]').forEach(input => {
        const bar   = document.getElementById(input.dataset.strength);
        const label = document.getElementById(input.dataset.strengthLabel);
        if (!bar) return;
        input.addEventListener('input', function() {
            const v = this.value;
            let score = 0;
            if (v.length >= 8)           score++;
            if (/[A-Z]/.test(v))         score++;
            if (/[0-9]/.test(v))         score++;
            if (/[^A-Za-z0-9]/.test(v))  score++;
            const levels = [
                { w:'10%',  bg:'#ef4444', text:'Muy débil' },
                { w:'30%',  bg:'#f97316', text:'Débil' },
                { w:'60%',  bg:'#eab308', text:'Moderada' },
                { w:'85%',  bg:'#22c55e', text:'Fuerte' },
                { w:'100%', bg:'#16a34a', text:'Muy fuerte' },
            ];
            const l = levels[Math.max(0, score - (v.length > 0 ? 0 : 1))] || levels[0];
            bar.style.width      = v.length ? l.w  : '0';
            bar.style.background = l.bg;
            if (label) label.textContent = v.length ? l.text : '';
        });
    });
});
</script>
</body>
</html>
