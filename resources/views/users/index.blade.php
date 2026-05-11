@extends('dashboard')

@section('content')
<div class="mb-8">
    <h3 class="text-2xl font-extrabold text-[#1A237E]">Gestión Global de Usuarios</h3>
    <p class="text-gray-500 font-semibold">Administra todos los accesos al sistema S.E. Páez.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row gap-4 bg-gray-50/50 items-center justify-between">
        <form action="{{ route('users.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4 w-full">
            <div class="relative md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, Cédula o Email..." class="w-full px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#1A237E]/20 transition-all text-sm font-semibold">
            </div>
            
            <select name="role" class="px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#1A237E]/20 transition-all text-sm font-semibold appearance-none">
                <option value="">Todos los Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-[#1A237E] text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-[#1A237E]/90 transition-all flex-1 shadow-md shadow-[#1A237E]/20">
                    Buscar
                </button>
                @if(request()->anyFilled(['search', 'role', 'status']))
                    <a href="{{ route('users.index') }}" class="bg-white border border-gray-200 text-gray-400 p-2.5 rounded-xl hover:bg-gray-50 transition-all hover:text-red-500" title="Limpiar Filtros">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
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
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Rol de Acceso</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Estatus</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Registrado</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-blue-50/30 transition-colors {{ !$user->is_active ? 'opacity-70 bg-red-50/30' : '' }}">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-[#1A237E]">{{ $user->full_name }}</span>
                            <span class="text-xs font-semibold text-gray-400">CI: {{ $user->cedula }}</span>
                            @if($user->email)
                                <span class="text-[10px] font-bold text-gray-400">{{ $user->email }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-{{ $user->role->name === 'admin' ? 'purple' : ($user->role->name === 'docente' ? 'blue' : 'orange') }}-100 text-{{ $user->role->name === 'admin' ? 'purple' : ($user->role->name === 'docente' ? 'blue' : 'orange') }}-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-{{ $user->role->name === 'admin' ? 'purple' : ($user->role->name === 'docente' ? 'blue' : 'orange') }}-200">
                            {{ $user->role->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_active)
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
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 flex items-center space-x-3">
                        <a href="{{ route('users.edit', $user->id) }}" class="p-2 text-blue-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Editar y Permisos">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                        
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 {{ $user->is_active ? 'text-red-400 hover:text-red-600 hover:bg-red-50' : 'text-green-400 hover:text-green-600 hover:bg-green-50' }} rounded-xl transition-all" title="{{ $user->is_active ? 'Suspender Acceso' : 'Reactivar Acceso' }}">
                                @if($user->is_active)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-semibold">
                        No se encontraron usuarios.
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
