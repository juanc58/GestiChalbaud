@extends('dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-extrabold text-[#032e5e]">Editar Usuario</h3>
            <p class="text-gray-500 font-semibold">Modifica los accesos, datos o contraseña de {{ $user->full_name }}.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-6 py-2 bg-white border border-gray-100 rounded-xl font-bold text-xs text-gray-500 uppercase tracking-widest hover:bg-gray-50 transition-all">
            Volver
        </a>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl font-semibold text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-6">
            <h4 class="text-xs font-black text-[#c56c39] uppercase tracking-widest flex items-center mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Datos de Identidad (Login)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cédula (Usuario de Acceso)</label>
                    <input type="number" name="cedula" value="{{ old('cedula', $user->cedula) }}" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-bold text-[#032e5e]">
                    @error('cedula')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Nombre</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-bold text-[#032e5e]">
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Nombre</label>
                    <input type="text" name="second_name" value="{{ old('second_name', $user->second_name) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-bold text-[#032e5e]">
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Apellido</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-bold text-[#032e5e]">
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Apellido</label>
                    <input type="text" name="second_last_name" value="{{ old('second_last_name', $user->second_last_name) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-bold text-[#032e5e]">
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-bold text-[#032e5e]">
                </div>

                <div class="group space-y-2 md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-bold text-[#032e5e]">
                    @error('email')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-6">
             <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest flex items-center mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Permisos y Seguridad
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nivel de Acceso (Rol)</label>
                    <select name="role_id" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-blue-200 outline-none transition-all font-bold text-[#032e5e] appearance-none">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Estatus de la Cuenta</label>
                    <div class="flex items-center space-x-4 mt-2 bg-gray-50 px-5 py-3.5 rounded-2xl">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="w-5 h-5 text-[#032e5e] rounded focus:ring-[#032e5e]">
                            <span class="ml-3 text-sm font-bold {{ $user->is_active ? 'text-green-600' : 'text-red-500' }}">
                                Permitir inicio de sesión
                            </span>
                        </label>
                    </div>
                </div>

                <div class="group space-y-2 md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Forzar Nueva Contraseña (Opcional)</label>
                    <input type="password" name="password" id="user_password"
                        data-strength="user-strength-bar" data-strength-label="user-strength-label"
                        placeholder="Dejar en blanco para no cambiar..."
                        class="w-full px-5 py-4 rounded-2xl bg-red-50/50 border-2 border-transparent focus:border-red-200 outline-none transition-all font-bold text-[#032e5e]">
                    <div class="mt-2 flex items-center gap-3">
                        <div class="flex-1 bg-gray-100 rounded-full h-1">
                            <div id="user-strength-bar" class="strength-bar" style="width:0"></div>
                        </div>
                        <span id="user-strength-label" class="text-[10px] font-bold text-gray-400 w-20"></span>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 ml-1 mt-1">Mínimo 8 caracteres, con mayúsculas, números y símbolos.</p>
                    @error('password')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="bg-[#032e5e] text-white px-10 py-4 rounded-2xl font-bold hover:bg-[#032e5e]/90 transition-all shadow-xl shadow-[#032e5e]/20 text-sm">
                Guardar Modificaciones
            </button>
        </div>
    </form>
</div>
@endsection
