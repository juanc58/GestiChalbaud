@extends('dashboard')

@section('content')
@php
    $roleLabels = [
        'admin'   => 'Administrador',
        'docente' => 'Docente',
        'worker'  => 'Personal Administrativo',
    ];
    $roleBadgeClasses = [
        'admin'   => 'bg-purple-100 text-purple-700 border-purple-200',
        'docente' => 'bg-blue-100 text-blue-700 border-blue-200',
        'worker'  => 'bg-orange-100 text-orange-700 border-orange-200',
    ];
@endphp

<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h3 class="text-2xl font-extrabold text-[#1A237E]">Gestión y Aprobación de Usuarios</h3>
        <p class="text-gray-500 font-semibold">Aprueba solicitudes de ingreso, asigna roles y administra accesos en Infochalbaud.</p>
    </div>
    <a href="{{ route('users.create') }}" class="px-6 py-3 bg-[#1A237E] text-white rounded-2xl font-bold hover:bg-[#1A237E]/90 transition-all shadow-sm flex items-center gap-2 self-start sm:self-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nuevo Usuario
    </a>
</div>

{{-- Quick Status Filter Pills --}}
<div class="flex flex-wrap items-center gap-2 mb-6">
    @php $currentStatus = request('status', ''); @endphp
    <a href="{{ route('users.index', request()->except('status', 'page')) }}"
       class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all border {{ $currentStatus === '' ? 'bg-[#1A237E] text-white border-[#1A237E]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
        Todos
    </a>
    <a href="{{ route('users.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}"
       class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all border flex items-center gap-2 {{ $currentStatus === 'pending' ? 'bg-[#D32F2F] text-white border-[#D32F2F]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
        Pendientes de Aprobación
        @if($pendingCount > 0)
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $currentStatus === 'pending' ? 'bg-white text-[#D32F2F]' : 'bg-[#D32F2F] text-white' }}">
                {{ $pendingCount }}
            </span>
        @endif
    </a>
    <a href="{{ route('users.index', array_merge(request()->except('page'), ['status' => 'active'])) }}"
       class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all border {{ $currentStatus === 'active' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
        Activos
    </a>
    <a href="{{ route('users.index', array_merge(request()->except('page'), ['status' => 'suspended'])) }}"
       class="px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider transition-all border {{ $currentStatus === 'suspended' ? 'bg-gray-700 text-white border-gray-700' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
        Suspendidos
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row gap-4 bg-gray-50/50 items-center justify-between">
        <form action="{{ route('users.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4 w-full">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, Cédula o Correo..." class="w-full px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#1A237E]/20 transition-all text-sm font-semibold">
            </div>

            <select name="role" class="w-full px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#1A237E]/20 transition-all text-sm font-semibold appearance-none">
                <option value="">Todos los Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                        {{ $roleLabels[$role->name] ?? ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-[#1A237E] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-[#1A237E]/90 transition-all flex-1 shadow-md shadow-[#1A237E]/20">
                    Buscar
                </button>
                @if(request()->anyFilled(['search', 'role', 'status']))
                    <a href="{{ route('users.index') }}" class="bg-white border border-gray-200 text-gray-400 p-2.5 rounded-xl hover:bg-gray-50 transition-all hover:text-red-500 flex items-center justify-center" title="Limpiar Filtros">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Identificación</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Rol / Asignación</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Estatus</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Registrado</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-blue-50/30 transition-colors {{ $user->isPending() ? 'bg-amber-50/40' : (!$user->is_active ? 'opacity-75 bg-red-50/20' : '') }}">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-[#1A237E]">{{ $user->full_name }}</span>
                            <span class="text-xs font-semibold text-gray-400">CI: V-{{ $user->cedula }} {{ $user->phone ? '· ' . $user->phone : '' }}</span>
                            @if($user->email)
                                <span class="text-[10px] font-bold text-gray-400">{{ $user->email }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role)
                            @php $rName = $user->role->name; @endphp
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $roleBadgeClasses[$rName] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                {{ $roleLabels[$rName] ?? $rName }}
                            </span>
                            @if($rName === 'docente' && $user->teacher)
                                <a href="{{ route('teachers.show', $user->teacher->id) }}" class="ml-2 text-[10px] font-bold text-[#1A237E] underline hover:text-[#FBC02D]">
                                    Ver Ficha Docente
                                </a>
                            @endif
                        @else
                            {{-- Inline Approval Selector --}}
                            <div class="space-y-1.5">
                                @if($user->requested_role)
                                    <span class="inline-block text-[10px] font-extrabold text-amber-700 uppercase tracking-wider">
                                        Solicitó: {{ $roleLabels[$user->requested_role] ?? ucfirst($user->requested_role) }}
                                    </span>
                                @endif
                                <form action="{{ route('users.approve', $user->id) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    <select name="role_id" required class="px-3 py-1.5 rounded-lg bg-white border border-amber-300 text-xs font-bold text-[#1A237E] outline-none focus:border-[#1A237E]">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->requested_role === $role->name ? 'selected' : '' }}>
                                                {{ $roleLabels[$role->name] ?? ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm whitespace-nowrap">
                                        ✓ Aprobar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->isPending())
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-[10px] font-black uppercase tracking-widest border border-amber-200">
                                Pendiente Aprobación
                            </span>
                        @elseif($user->is_active)
                            <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-200">
                                Cuenta Activa
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-200">
                                Suspendida
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs font-bold text-gray-400">
                        {{ $user->created_at?->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="inline-flex items-center justify-end space-x-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="p-2 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-xl transition-all" title="Editar Usuario y Rol">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>

                            @if($user->id !== auth()->id() && !$user->isPending())
                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-2 {{ $user->is_active ? 'text-amber-500 hover:text-amber-700 hover:bg-amber-50' : 'text-green-500 hover:text-green-700 hover:bg-green-50' }} rounded-xl transition-all" title="{{ $user->is_active ? 'Suspender Acceso' : 'Reactivar Acceso' }}">
                                    @if($user->is_active)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </button>
                            </form>
                            @endif

                            @if($user->id !== auth()->id() && $user->isPending())
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Rechazar y eliminar esta solicitud de registro?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Rechazar Solicitud">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-semibold">
                        No se encontraron usuarios con los criterios seleccionados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-gray-50 bg-gray-50/30">
        {{ $users->links() }}
    </div>
</div>
@endsection
