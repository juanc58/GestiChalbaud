@extends('dashboard')

@section('content')
@php
    $roleLabels = [
        'admin'   => 'Administrador (Acceso Total)',
        'docente' => 'Docente (Crea ficha docente automáticamente)',
        'worker'  => 'Personal Administrativo / Secretaría',
    ];
@endphp

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Registrar Nuevo Usuario</h3>
            <p class="text-gray-500 font-semibold">Crea directamente una cuenta activa en Infochalbaud y asígnale su rol institucional.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-6 py-2 bg-white border border-gray-200 rounded-xl font-bold text-xs text-gray-500 uppercase tracking-widest hover:bg-gray-50 transition-all">
            Volver
        </a>
    </div>

    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 text-xs sm:text-sm font-semibold text-[#1A237E]">
        💡 Si seleccionas el rol <strong>Docente</strong>, el sistema creará automáticamente su perfil docente para que puedas asignarlo de inmediato a un aula sin registrarlo dos veces.
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl font-semibold text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
            <h4 class="text-xs font-black text-[#FBC02D] uppercase tracking-widest">Datos de Identidad</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cédula de Identidad *</label>
                    <input type="text" inputmode="numeric" name="cedula" value="{{ old('cedula') }}" required placeholder="Ej: 18543210"
                        oninput="this.value = this.value.replace(/\D/g, '')"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Rol Institucional *</label>
                    <select name="role_id" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] appearance-none">
                        <option value="">Seleccione un rol...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $roleLabels[$role->name] ?? ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Nombre *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                        oninput="this.value = this.value.toUpperCase()"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] uppercase">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Nombre</label>
                    <input type="text" name="second_name" value="{{ old('second_name') }}"
                        oninput="this.value = this.value.toUpperCase()"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] uppercase">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Apellido *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required
                        oninput="this.value = this.value.toUpperCase()"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] uppercase">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Apellido</label>
                    <input type="text" name="second_last_name" value="{{ old('second_last_name') }}"
                        oninput="this.value = this.value.toUpperCase()"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] uppercase">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Teléfono *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="Ej: 04141234567"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Correo Electrónico *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="usuario@correo.com"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Contraseña Inicial (Opcional)</label>
                    <input type="password" name="password" placeholder="Si lo dejas en blanco, la clave inicial será su número de cédula"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
                    <p class="text-[10px] font-bold text-gray-400 ml-1">Si ingresas una contraseña personalizada, debe tener mínimo 8 caracteres, mayúscula, número y símbolo.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#1A237E] text-white px-10 py-4 rounded-2xl font-bold hover:bg-[#1A237E]/90 transition-all shadow-xl shadow-[#1A237E]/20 text-sm">
                Crear y Activar Usuario
            </button>
        </div>
    </form>
</div>
@endsection

