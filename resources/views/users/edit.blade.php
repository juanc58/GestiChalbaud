@extends('dashboard')

@section('content')
<div class="max-w-full space-y-6 space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Editar Usuario</h3>
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

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
            <h4 class="text-xs font-black text-[#FBC02D] uppercase tracking-widest flex items-center mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                Datos de Identidad (Login)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cédula (Usuario de Acceso)</label>
                    <input type="number" name="cedula" value="{{ old('cedula', $user->cedula) }}" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                    @error('cedula')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Nombre</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Nombre</label>
                    <input type="text" name="second_name" value="{{ old('second_name', $user->second_name) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Apellido</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Apellido</label>
                    <input type="text" name="second_last_name" value="{{ old('second_last_name', $user->second_last_name) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>

                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Teléfono</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>

                <div class="group space-y-2 md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                    @error('email')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
             <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest flex items-center mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Permisos y Seguridad
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nivel de Acceso (Rol)</label>
                    <select name="role_id" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-blue-200 outline-none transition-all font-bold text-[#1A237E] appearance-none">
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
                            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="w-5 h-5 text-[#1A237E] rounded focus:ring-[#1A237E]">
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
                        class="w-full px-5 py-4 rounded-2xl bg-red-50/50 border-2 border-transparent focus:border-red-200 outline-none transition-all font-bold text-[#1A237E]">
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

        <!-- Security Question Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
            <h4 class="text-xs font-black text-amber-600 uppercase tracking-widest flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Pregunta de Seguridad
            </h4>

            @if($userSecurityAnswer)
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 text-sm font-semibold text-blue-700">
                    Pregunta actual: <span class="font-black">{{ $userSecurityAnswer->question->question ?? 'No configurada' }}</span>
                </div>
            @else
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-sm font-semibold text-amber-700">
                    ⚠ Este usuario no tiene configurada una pregunta de seguridad.
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group space-y-2 md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nueva Pregunta (Opcional)</label>
                    <select name="security_question_id" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-amber-200 outline-none transition-all font-bold text-[#1A237E] appearance-none">
                        <option value="">-- Sin cambios --</option>
                        @foreach($securityQuestions as $q)
                            <option value="{{ $q->id }}" {{ (old('security_question_id', $userSecurityAnswer?->security_question_id) == $q->id) ? 'selected' : '' }}>
                                {{ $q->question }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="group space-y-2 md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nueva Respuesta <span class="text-amber-500">(dejar en blanco para no cambiar)</span></label>
                    <input type="text" name="security_answer" placeholder="Restablecer respuesta del usuario..." autocomplete="off"
                        class="w-full px-5 py-4 rounded-2xl bg-amber-50/50 border-2 border-transparent focus:border-amber-200 outline-none transition-all font-bold text-[#1A237E]">
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="bg-[#1A237E] text-white px-10 py-4 rounded-2xl font-bold hover:bg-[#1A237E]/90 transition-all shadow-xl shadow-[#1A237E]/20 text-sm">
                Guardar Modificaciones
            </button>
        </div>
    </form>
</div>
@endsection
