<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GestiChalbaud Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Toasts */
        #toast-container { position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999; display:flex; flex-direction:column; gap:.75rem; }
        .toast { min-width:280px; max-width:380px; padding:1rem 1.25rem; border-radius:12px; font-weight:600; font-size:14px;
            display:flex; align-items:center; gap:.75rem; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);
            animation: slideIn .3s ease; }
        @keyframes slideIn { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .toast-success { background:#fff; border-left:4px solid #10B981; color:#111827; }
        .toast-error   { background:#fff; border-left:4px solid #EF4444; color:#111827; }
        .toast-warning { background:#fff; border-left:4px solid #F59E0B; color:#111827; }
        .strength-bar { height:4px; border-radius:9999px; transition: width .3s, background .3s; }
        
        /* Scrollbar styles */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }
    </style>
</head>
<body class="flex h-screen bg-[#F9FAFB] text-gray-900 overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col flex-shrink-0">
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo" class="w-8 h-8 rounded-full object-cover" onerror="this.style.display='none'">
                <span class="font-bold text-lg text-[#1A237E] tracking-tight">GestiChalbaud</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <div class="mb-4">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">General</p>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
            </div>

            @if(Auth::user()->isAdmin())
            <div class="mb-4">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Administración</p>
                <a href="{{ route('school-years.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('school-years.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Años Escolares
                </a>
                <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('students.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Estudiantes
                    @if(isset($pendingStudentsCount) && $pendingStudentsCount > 0)
                        <span class="ml-auto bg-[#FBC02D] text-[#1A237E] text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingStudentsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('representatives.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('representatives.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Representantes
                </a>
                <a href="{{ route('sections.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('sections.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Aulas
                </a>
                <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('subjects.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Materias
                </a>
                <a href="{{ route('teachers.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('teachers.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Docentes
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Usuarios
                </a>
            </div>
            @endif

            @if(Auth::user()->isDocente())
            <div class="mb-4">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Docencia</p>
                <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('students.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Mis Estudiantes
                </a>
                <a href="{{ route('representatives.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('representatives.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Directorio
                </a>
                <a href="{{ route('sections.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('sections.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Mi Aula
                </a>
            </div>
            @endif

            @if(Auth::user()->isRepresentative())
            <div class="mb-4">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Representante</p>
                <a href="{{ route('representative.students.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('representative.students.*') ? 'bg-indigo-50 text-[#1A237E]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Mis Representados
                </a>
            </div>
            @endif
        </div>

        <!-- Footer / Perfil -->
        <div class="p-4 border-t border-gray-200 mt-auto flex-shrink-0">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Configuración
            </a>
            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 lg:px-8 flex-shrink-0 z-10">
            <!-- Breadcrumb / Titulo Area -->
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="font-medium">Dashboard</span>
                @if(!request()->routeIs('dashboard'))
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="font-medium text-gray-900 capitalize">{{ str_replace(['.index', '.edit', '.create'], '', request()->route()->getName()) }}</span>
                @endif
            </div>

            <!-- Acciones / Perfil -->
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex border border-gray-200 rounded-md px-3 py-1.5 text-xs font-medium bg-white items-center gap-2 text-gray-600">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    Sesión Activa
                </div>
                <div class="flex items-center gap-3 ml-2 border-l border-gray-200 pl-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900 leading-tight">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->role->name }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-[#1A237E] flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto bg-[#F9FAFB]">
            <div class="p-6 md:p-8 max-w-[1600px] mx-auto w-full">
                
                <!-- Alerts -->
                @if(Auth::user()->isAdmin() && isset($pendingStudentsCount) && $pendingStudentsCount > 0)
                    <div class="mb-6 bg-white border border-[#FBC02D] rounded-xl p-4 flex items-start gap-4 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-[#FBC02D]"></div>
                        <div class="w-10 h-10 rounded-full bg-[#FBC02D]/10 flex items-center justify-center flex-shrink-0 text-[#FBC02D]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-gray-900">Inscripciones Pendientes</h3>
                            <p class="text-sm text-gray-600 mt-1">Hay {{ $pendingStudentsCount }} estudiante(s) esperando asignación de aula.</p>
                        </div>
                        <a href="{{ route('students.index', ['filter' => 'pending']) }}" class="text-xs font-semibold bg-[#FBC02D] text-[#1A237E] px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors">
                            Revisar Ahora
                        </a>
                    </div>
                @endif

                @yield('content')

                <!-- Default Dashboard Widgets -->
                @if(request()->routeIs('dashboard'))
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-sm font-semibold text-gray-900">Estudiantes</h3>
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_students'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Registrados en el sistema</p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-sm font-semibold text-gray-900">Cuerpo Docente</h3>
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_teachers'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Personal académico activo</p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-sm font-semibold text-gray-900">Aulas</h3>
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_sections'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Aulas configuradas</p>
                            </div>
                        </div>
                        
                        <!-- Gráfico Vacío (Ejemplo de Empty State) -->
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 lg:col-span-3 flex flex-col">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Actividad Reciente</h3>
                            <div class="bg-gray-50 border border-gray-100 rounded-lg h-48 flex items-center justify-center">
                                <p class="text-sm text-gray-400 font-medium">No hay suficientes datos para generar gráficos.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Toasts -->
    <div id="toast-container"></div>
    @if(session('success') || session('warning') || session('error') || $errors->any())
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success')) showToast({{ json_encode(session('success')) }}, 'success'); @endif
        @if(session('warning')) showToast({{ json_encode(session('warning')) }}, 'warning'); @endif
        @if(session('error')) showToast({{ json_encode(session('error')) }}, 'error'); @endif
        @if($errors->any()) showToast('Corrige los errores indicados.', 'error'); @endif
    });
    </script>
    @endif
    <script>
    function showToast(message, type = 'success', duration = 4500) {
        const icons = {
            success: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
            error:   '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            warning: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        };
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `<div class="flex-shrink-0">${icons[type]}</div><span class="flex-1">${message}</span><button onclick="this.parentElement.remove()" class="opacity-40 hover:opacity-100 transition-opacity">✕</button>`;
        container.appendChild(toast);
        setTimeout(() => { toast.style.opacity='0'; toast.style.transform='translateY(20px)'; setTimeout(() => toast.remove(), 300); }, duration);
    }
    // Password Strength Meter
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="password"][data-strength]').forEach(input => {
            const bar = document.getElementById(input.dataset.strength);
            const label = document.getElementById(input.dataset.strengthLabel);
            if (!bar) return;
            input.addEventListener('input', function() {
                const v = this.value;
                let score = 0;
                if (v.length >= 8) score++;
                if (/[A-Z]/.test(v)) score++;
                if (/[0-9]/.test(v)) score++;
                if (/[^A-Za-z0-9]/.test(v)) score++;
                const levels = [
                    { w:'10%', bg:'#EF4444', text:'Muy débil' },
                    { w:'30%', bg:'#F97316', text:'Débil' },
                    { w:'60%', bg:'#F59E0B', text:'Moderada' },
                    { w:'85%', bg:'#10B981', text:'Fuerte' },
                    { w:'100%', bg:'#059669', text:'Muy fuerte' }
                ];
                const l = levels[Math.max(0, score - (v.length > 0 ? 0 : 1))] || levels[0];
                bar.style.width = v.length ? l.w : '0';
                bar.style.background = l.bg;
                if (label) label.textContent = v.length ? l.text : '';
            });
        });
    });
    </script>
</body>
</html>
