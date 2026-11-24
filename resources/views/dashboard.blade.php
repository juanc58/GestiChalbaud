<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infochalbaud — Panel Principal</title>
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
    <!-- Backdrop Móvil -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="appSidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 flex-col flex-shrink-0 hidden md:flex md:static transition-transform">
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-gray-200 flex-shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo Infochalbaud" class="w-8 h-8 rounded-full object-cover" onerror="this.style.display='none'">
                <div class="leading-tight">
                    <span class="font-extrabold text-lg text-[#1A237E] tracking-tight block">Infochalbaud</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Gestión Escolar</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        @php
            $navActive = 'bg-indigo-50/80 text-[#1A237E] font-bold border-l-[3px] border-[#1A237E] pl-[9px]';
            $navIdle   = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';
        @endphp
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <div class="mb-4">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">General</p>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Panel Principal
                </a>
            </div>

            @if(Auth::user()->isAdmin())
            <div class="mb-5 pt-3 border-t border-gray-200 space-y-0.5">
                <p class="px-3 py-1.5 rounded-lg bg-[#1A237E]/10 text-[#1A237E] border-l-4 border-[#1A237E] text-[11px] font-black uppercase tracking-wider mb-2">
                    Control de Estudio (Académico)
                </p>
                <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('students.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Inscripción de Estudiantes
                    @if(isset($pendingStudentsCount) && $pendingStudentsCount > 0)
                        <span class="ml-auto bg-[#FBC02D] text-[#1A237E] text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingStudentsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('sections.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('sections.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Aulas, Notas y Prosecución
                </a>
                <a href="{{ route('attendance.students') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('attendance.students*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Asistencia Estudiantil
                </a>
                <a href="{{ route('academic-records.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ (request()->routeIs('academic-records.*') && request('tab') !== 'matrix') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Récord Académico
                </a>
                <a href="{{ route('graduates.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('graduates.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    Libro de Egresados
                </a>
            </div>

            <div class="mb-5 pt-3 border-t border-gray-200 space-y-0.5">
                <p class="px-3 py-1.5 rounded-lg bg-[#FBC02D]/25 text-[#78350F] border-l-4 border-[#FBC02D] text-[11px] font-black uppercase tracking-wider mb-2">
                    Administración (Gestión)
                </p>
                <a href="{{ route('teachers.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('teachers.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Registro y Asignación Docente
                </a>
                <a href="{{ route('attendance.teachers') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('attendance.teachers*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Asistencia Docente
                </a>
                <a href="{{ route('subjects.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('subjects.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Materias y Plan de Estudio
                </a>
                <a href="{{ route('school-years.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('school-years.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Años Escolares
                </a>
                <a href="{{ route('representatives.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('representatives.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Directorio de Representantes
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('users.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Gestión de Usuarios
                    @if(isset($pendingUsersCount) && $pendingUsersCount > 0)
                        <span class="ml-auto bg-[#D32F2F] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingUsersCount }}</span>
                    @endif
                </a>
                @if(Auth::user()->isSuperAdmin())
                <a href="{{ route('settings.smtp.edit') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('settings.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Configuración Institucional
                </a>
                @endif
            </div>

            <div class="mb-5 pt-3 border-t border-gray-200 space-y-0.5">
                <p class="px-3 py-1.5 rounded-lg bg-[#D32F2F]/10 text-[#B71C1C] border-l-4 border-[#D32F2F] text-[11px] font-black uppercase tracking-wider mb-2">
                    Reportes y Documentos
                </p>
                <a href="{{ route('documents.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('documents.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Emisión de Constancias
                </a>
                <a href="{{ route('academic-records.index', ['tab' => 'matrix']) }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ (request()->routeIs('academic-records.*') && request('tab') === 'matrix') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Sábana de Notas
                </a>
                <a href="{{ route('mail.compose') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('mail.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Correo Institucional
                </a>
            </div>
            @endif

            @if(Auth::user()->isDocente())
            <div class="mb-5 pt-3 border-t border-gray-200 space-y-0.5">
                <p class="px-3 py-1.5 rounded-lg bg-[#1A237E]/10 text-[#1A237E] border-l-4 border-[#1A237E] text-[11px] font-black uppercase tracking-wider mb-2">
                    Control de Estudio
                </p>
                <a href="{{ route('sections.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('sections.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Mi Aula (Notas y Prosecución)
                </a>
                <a href="{{ route('students.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('students.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Mis Estudiantes
                </a>
                <a href="{{ route('attendance.students') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('attendance.students*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Asistencia Estudiantil
                </a>
                <a href="{{ route('academic-records.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('academic-records.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Récord Académico
                </a>
                <a href="{{ route('graduates.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('graduates.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    Libro de Egresados
                </a>
            </div>

            <div class="mb-5 pt-3 border-t border-gray-200 space-y-0.5">
                <p class="px-3 py-1.5 rounded-lg bg-[#D32F2F]/10 text-[#B71C1C] border-l-4 border-[#D32F2F] text-[11px] font-black uppercase tracking-wider mb-2">
                    Administración y Reportes
                </p>
                <a href="{{ route('attendance.teachers') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('attendance.teachers*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Mi Asistencia
                </a>
                <a href="{{ route('representatives.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('representatives.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Directorio de Representantes
                </a>
                <a href="{{ route('documents.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('documents.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Emisión de Constancias
                </a>
                <a href="{{ route('mail.compose') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('mail.*') ? $navActive : $navIdle }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Correo Institucional
                </a>
            </div>
            @endif
        </div>

        <!-- Footer / Perfil -->
        <div class="p-4 border-t border-gray-200 mt-auto flex-shrink-0">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('profile.*') ? $navActive : $navIdle }} transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Mi Perfil y Cuenta
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
        <!-- Filete Tricolor Institucional (3px) -->
        <div class="h-[3px] w-full flex flex-shrink-0">
            <div class="flex-1 bg-[#FBC02D]"></div>
            <div class="flex-1 bg-[#1A237E]"></div>
            <div class="flex-1 bg-[#D32F2F]"></div>
        </div>
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 lg:px-8 flex-shrink-0 z-10">
            <!-- Breadcrumb / Título en Español -->
            @php
                $routePrefix = explode('.', request()->route()?->getName() ?? 'dashboard')[0];
                $breadcrumbLabels = [
                    'dashboard'        => 'Panel Principal',
                    'students'         => 'Control de Estudio · Inscripción de Estudiantes',
                    'teachers'         => 'Administración · Registro y Asignación Docente',
                    'sections'         => 'Control de Estudio · Aulas, Notas y Prosecución',
                    'school-years'     => 'Administración · Años Escolares',
                    'subjects'         => 'Administración · Materias y Plan de Estudio',
                    'users'            => 'Administración · Gestión de Usuarios',
                    'representatives'  => 'Administración · Directorio de Representantes',
                    'graduates'        => 'Control de Estudio · Libro de Egresados',
                    'attendance'       => 'Asistencia Institucional',
                    'academic-records' => 'Control de Estudio · Récord Académico',
                    'documents'        => 'Reportes y Documentos · Emisión de Constancias',
                    'mail'             => 'Reportes y Documentos · Correo Institucional',
                    'settings'         => 'Administración · Configuración Institucional',
                    'profile'          => 'Mi Perfil y Seguridad',
                    'reports'          => 'Boletín Informativo',
                ];
                $currentBreadcrumb = $breadcrumbLabels[$routePrefix] ?? 'Sección Activa';
            @endphp
            <div class="flex items-center gap-2 text-sm text-gray-500 min-w-0">
                <button type="button" onclick="toggleSidebar()" class="md:hidden inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-gray-200 text-gray-700 font-bold text-xs hover:bg-gray-50 flex-shrink-0" aria-label="Abrir menú">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span>Menú</span>
                </button>
                <a href="{{ route('dashboard') }}" class="font-medium hover:text-[#1A237E] transition-colors truncate">Panel Principal</a>
                @if(!request()->routeIs('dashboard'))
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="font-semibold text-gray-900">{{ $currentBreadcrumb }}</span>
                @endif
            </div>

            <!-- Acciones / Perfil -->
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-gray-50 border border-gray-200 text-xs font-bold text-[#1A237E]">
                    <span>Período:</span>
                    <span>{{ $currentYear ?? \App\Models\SchoolYear::current() }}</span>
                </div>
                <div class="hidden sm:flex border border-gray-200 rounded-md px-3 py-1.5 text-xs font-medium bg-white items-center gap-2 text-gray-600">
                    <span class="w-2 h-2 rounded-full bg-[#1A237E]"></span>
                    Sesión Activa
                </div>
                <div class="flex items-center gap-3 ml-2 border-l border-gray-200 pl-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900 leading-tight">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->role_label }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-[#1A237E] flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ mb_substr(Auth::user()->first_name, 0, 1, 'UTF-8') }}{{ mb_substr(Auth::user()->last_name, 0, 1, 'UTF-8') }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto bg-[#F9FAFB]">
            <div class="p-6 md:p-8 max-w-[1600px] mx-auto w-full">
                
                <!-- Alerts -->
                @if(Auth::user()->isAdmin() && request()->routeIs('dashboard') && isset($pendingUsersCount) && $pendingUsersCount > 0)
                    <div class="mb-6 bg-white border border-[#1A237E]/20 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center gap-4 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-[#D32F2F]"></div>
                        <div class="w-10 h-10 rounded-full bg-[#D32F2F]/10 flex items-center justify-center flex-shrink-0 text-[#D32F2F]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-[#1A237E]">Solicitudes de Registro de Usuario Pendientes</h3>
                            <p class="text-sm text-gray-600 mt-0.5">Hay <strong>{{ $pendingUsersCount }}</strong> usuario(s) esperando aprobación y asignación de rol (Docente, Administrador o Personal Administrativo).</p>
                        </div>
                        <a href="{{ route('users.index', ['status' => 'pending']) }}" class="text-xs font-bold bg-[#1A237E] text-white px-4 py-2.5 rounded-lg hover:bg-[#1A237E]/90 transition-colors flex-shrink-0 self-start sm:self-auto whitespace-nowrap">
                            Aprobar Solicitudes
                        </a>
                    </div>
                @endif

                @if(Auth::user()->isAdmin() && isset($pendingStudentsCount) && $pendingStudentsCount > 0)
                    <div class="mb-6 bg-white border border-[#FBC02D] rounded-xl p-4 flex flex-col sm:flex-row sm:items-center gap-4 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-[#FBC02D]"></div>
                        <div class="w-10 h-10 rounded-full bg-[#FBC02D]/10 flex items-center justify-center flex-shrink-0 text-[#FBC02D]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-gray-900">Inscripciones Pendientes</h3>
                            <p class="text-sm text-gray-600 mt-1">Hay {{ $pendingStudentsCount }} estudiante(s) activo(s) esperando asignación de aula en el período {{ $currentYear ?? '' }}.</p>
                        </div>
                        <a href="{{ route('students.index', ['filter' => 'pending']) }}" class="text-xs font-semibold bg-[#FBC02D] text-[#1A237E] px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors flex-shrink-0 self-start sm:self-auto whitespace-nowrap">
                            Revisar Ahora
                        </a>
                    </div>
                @endif

                @yield('content')

                <!-- Default Dashboard Widgets -->
                @if(request()->routeIs('dashboard'))
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h2 class="text-2xl font-extrabold text-[#1A237E]">Bienvenido a Infochalbaud</h2>
                            <p class="text-sm text-gray-500 font-medium">U.E.B. Coronel Carlos Delgado Chalbaud — Período Escolar Activo: <strong class="text-[#1A237E]">{{ $currentYear ?? \App\Models\SchoolYear::current() }}</strong></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-sm font-semibold text-gray-700">Matrícula Activa</h3>
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-[#1A237E]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <p class="text-3xl font-bold text-gray-900 tabular-nums">{{ $stats['total_students'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Estudiantes activos en plantel</p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-sm font-semibold text-gray-700">Cuerpo Docente</h3>
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-[#1A237E]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <p class="text-3xl font-bold text-gray-900 tabular-nums">{{ $stats['total_teachers'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Personal académico registrado</p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-sm font-semibold text-gray-700">Aulas y Secciones</h3>
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-[#1A237E]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <p class="text-3xl font-bold text-gray-900 tabular-nums">{{ $stats['total_sections'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Aulas configuradas</p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-sm font-semibold text-gray-700">Egresados</h3>
                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-[#1A237E]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <p class="text-3xl font-bold text-gray-900 tabular-nums">{{ $stats['total_graduates'] ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">Promociones históricas</p>
                            </div>
                        </div>
                    </div>

                    <!-- Mapa Diagnóstico de Procesos Institucionales -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- 1. Procesos Académicos -->
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <div>
                                    <span class="text-[10px] font-black text-[#1A237E] uppercase tracking-widest block">Control de Estudio</span>
                                    <h3 class="text-base font-extrabold text-gray-900">Procesos Académicos</h3>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-[#1A237E] text-[10px] font-black uppercase">Académico</span>
                            </div>
                            <div class="space-y-2 text-xs">
                                <a href="{{ route('students.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                    <span>Inscripción de Estudiantes</span>
                                    <span class="text-[#1A237E]">→</span>
                                </a>
                                <a href="{{ route('sections.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                    <span>Aulas, Evaluación y Prosecución</span>
                                    <span class="text-[#1A237E]">→</span>
                                </a>
                                <a href="{{ route('academic-records.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                    <span>Récord Académico y Trayectoria</span>
                                    <span class="text-[#1A237E]">→</span>
                                </a>
                                <a href="{{ route('graduates.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                    <span>Libro de Egresados y Promoción</span>
                                    <span class="text-[#1A237E]">→</span>
                                </a>
                            </div>
                        </div>

                        <!-- 2. Procesos Administrativos -->
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <div>
                                    <span class="text-[10px] font-black text-[#854D0E] uppercase tracking-widest block">Gestión Interna</span>
                                    <h3 class="text-base font-extrabold text-gray-900">Procesos Administrativos</h3>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-[#854D0E] text-[10px] font-black uppercase">Administrativo</span>
                            </div>
                            <div class="space-y-2 text-xs">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('teachers.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                        <span>Registro y Asignación de Docentes</span>
                                        <span class="text-[#854D0E]">→</span>
                                    </a>
                                    <a href="{{ route('attendance.teachers') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                        <span>Asistencia del Personal Docente</span>
                                        <span class="text-[#854D0E]">→</span>
                                    </a>
                                    <a href="{{ route('subjects.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                        <span>Materias y Años Escolares</span>
                                        <span class="text-[#854D0E]">→</span>
                                    </a>
                                @else
                                    <a href="{{ route('attendance.teachers') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                        <span>Mi Asistencia Laboral</span>
                                        <span class="text-[#854D0E]">→</span>
                                    </a>
                                @endif
                                <a href="{{ route('representatives.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                    <span>Directorio de Representantes</span>
                                    <span class="text-[#854D0E]">→</span>
                                </a>
                            </div>
                        </div>

                        <!-- 3. Reportes y Documentos -->
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-4">
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                <div>
                                    <span class="text-[10px] font-black text-[#D32F2F] uppercase tracking-widest block">Salidas Oficiales</span>
                                    <h3 class="text-base font-extrabold text-gray-900">Reportes y Documentos</h3>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-red-50 text-[#D32F2F] text-[10px] font-black uppercase">Emisión</span>
                            </div>
                            <div class="space-y-2 text-xs">
                                <a href="{{ route('documents.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                    <span>Emisión de Constancias (PDF)</span>
                                    <span class="text-[#D32F2F]">→</span>
                                </a>
                                <a href="{{ route('academic-records.index', ['tab' => 'matrix']) }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                    <span>Sábana de Notas por Sección</span>
                                    <span class="text-[#D32F2F]">→</span>
                                </a>
                                <a href="{{ route('mail.compose') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                    <span>Correo Institucional (Adjunto Directo)</span>
                                    <span class="text-[#D32F2F]">→</span>
                                </a>
                                <a href="{{ route('attendance.students') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors font-bold text-gray-800">
                                    <span>Asistencia Estudiantil del Día</span>
                                    <span class="text-[#D32F2F]">→</span>
                                </a>
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
    function toggleSidebar() {
        const aside = document.getElementById('appSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (!aside) return;
        const isHidden = aside.classList.contains('hidden');
        if (isHidden) {
            aside.classList.remove('hidden');
            aside.classList.add('flex');
            backdrop?.classList.remove('hidden');
        } else {
            aside.classList.add('hidden');
            aside.classList.remove('flex');
            backdrop?.classList.add('hidden');
        }
    }
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
