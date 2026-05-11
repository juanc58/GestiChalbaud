@extends('dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <h3 class="text-2xl font-extrabold text-[#032e5e]">Configuración de Perfil</h3>
        <p class="text-gray-500 font-semibold">Gestiona tus datos personales y seguridad de la cuenta.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl font-semibold text-sm">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="md:col-span-2 space-y-6">
            <form action="{{ route('profile.update') }}" method="POST" class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-6">
                @csrf
                @method('PUT')

                <h4 class="text-xs font-black text-[#032e5e] uppercase tracking-widest mb-4">Datos de Identidad</h4>

                <!-- Cedula readonly -->
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cédula de Identidad (No editable)</label>
                    <input type="text" value="{{ $user->cedula }}" disabled
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent outline-none font-bold text-gray-400 cursor-not-allowed">
                </div>

                <!-- Name fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Nombre <span class="text-red-400">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        @error('first_name')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="group space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Nombre</label>
                        <input type="text" name="second_name" value="{{ old('second_name', $user->second_name) }}"
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                    </div>
                    <div class="group space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Apellido <span class="text-red-400">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        @error('last_name')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="group space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Apellido</label>
                        <input type="text" name="second_last_name" value="{{ old('second_last_name', $user->second_last_name) }}"
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                    </div>
                    <div class="group space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                    </div>
                    <div class="group space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        @error('email')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- DOCENTE extended fields --}}
                @if(Auth::user()->isDocente() && Auth::user()->teacher)
                <div class="pt-4 border-t border-gray-100">
                    <h5 class="text-[10px] font-black text-[#032e5e] uppercase tracking-widest mb-4">Datos Profesionales</h5>
                    <div class="group space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Título Académico</label>
                        <input type="text" name="academic_degree" value="{{ old('academic_degree', $user->teacher->academic_degree) }}"
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                    </div>
                </div>
                @endif

                {{-- REPRESENTATIVE extended fields --}}
                @if(Auth::user()->isRepresentative() && Auth::user()->representative)
                <div class="pt-4 border-t border-gray-100 space-y-4">
                    <h5 class="text-[10px] font-black text-[#c56c39] uppercase tracking-widest">Contacto Adicional</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="group space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">WhatsApp / Celular <span class="text-red-400">*</span></label>
                            <input type="text" name="phone_whatsapp" value="{{ old('phone_whatsapp', $user->representative->phone_whatsapp) }}" required
                                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                            @error('phone_whatsapp')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="group space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Teléfono Alternativo</label>
                            <input type="text" name="phone_local" value="{{ old('phone_local', $user->representative->phone_local) }}"
                                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        </div>
                        <div class="group space-y-2 md:col-span-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Facebook / Red Social</label>
                            <input type="text" name="facebook" value="{{ old('facebook', $user->representative->facebook) }}"
                                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        </div>
                    </div>
                    <h5 class="text-[10px] font-black text-green-600 uppercase tracking-widest pt-2">Datos Laborales</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="group space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Profesión u Oficio</label>
                            <input type="text" name="job_title" value="{{ old('job_title', $user->representative->job_title) }}"
                                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-green-100 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        </div>
                        <div class="group space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Lugar de Trabajo</label>
                            <input type="text" name="workplace_address" value="{{ old('workplace_address', $user->representative->workplace_address) }}"
                                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-green-100 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        </div>
                    </div>
                </div>
                @endif

                <div class="pt-4">
                    <button type="submit" class="bg-[#032e5e] text-white px-8 py-4 rounded-2xl font-bold hover:bg-[#032e5e]/90 transition-all shadow-lg shadow-[#032e5e]/20 text-sm">
                        Guardar Cambios
                    </button>
                </div>
            </form>

            <!-- Password Change -->
            <form action="{{ route('profile.password.update') }}" method="POST" class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-6">
                @csrf
                @method('PUT')

                <h4 class="text-xs font-black text-red-500 uppercase tracking-widest mb-4">Seguridad / Cambiar Contraseña</h4>

                <div class="space-y-4">
                    <div class="group space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Contraseña Actual</label>
                        <input type="password" name="current_password" required
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-red-100 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        @error('current_password')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nueva Contraseña</label>
                            <input type="password" name="password" required
                                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-red-100 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                            @error('password')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="group space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-red-100 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="bg-red-500 text-white px-8 py-4 rounded-2xl font-bold hover:bg-red-600 transition-all shadow-lg shadow-red-200 text-sm">
                        Actualizar Contraseña
                    </button>
                </div>
            </form>

            <!-- Security Question Management -->
            <form action="{{ route('profile.update') }}" method="POST" class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <h4 class="text-xs font-black text-amber-600 uppercase tracking-widest mb-1">Pregunta de Seguridad</h4>
                    <p class="text-[11px] text-gray-400 font-semibold">Usada para recuperar tu acceso desde el portal de login.</p>
                </div>

                @if($userSecurityAnswer)
                    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 text-sm font-semibold text-blue-700">
                        Pregunta actual: <span class="font-black">{{ $userSecurityAnswer->question->question ?? 'No configurada' }}</span>
                    </div>
                @else
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-sm font-semibold text-amber-700">
                        ⚠ Aún no tienes configurada una pregunta de seguridad. Sin ella no podrás recuperar tu acceso.
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="group space-y-2 md:col-span-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nueva Pregunta</label>
                        <select name="security_question_id" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-amber-200 outline-none transition-all font-bold text-[#032e5e] appearance-none">
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
                        <input type="text" name="security_answer" placeholder="Tu nueva respuesta secreta" autocomplete="off"
                            class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-amber-200 focus:bg-white outline-none transition-all font-bold text-[#032e5e]">
                        @error('security_answer')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Re-send all other hidden required profile fields so the same route works --}}
                <input type="hidden" name="first_name" value="{{ $user->first_name }}">
                <input type="hidden" name="last_name" value="{{ $user->last_name }}">

                <div class="pt-2">
                    <button type="submit" class="bg-amber-500 text-white px-8 py-4 rounded-2xl font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-200 text-sm">
                        Actualizar Pregunta de Seguridad
                    </button>
                </div>
            </form>
        </div>

        <!-- Role Badge & Summary -->
        <div class="space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-orange-50 flex items-center justify-center text-[#c56c39] mb-4 border-2 border-orange-100 text-3xl font-black">
                    {{ substr($user->first_name ?? '?', 0, 1) }}{{ substr($user->last_name ?? '', 0, 1) }}
                </div>
                <h5 class="font-extrabold text-[#032e5e]">{{ $user->full_name }}</h5>
                <span class="mt-2 px-4 py-1.5 bg-[#c56c39]/10 text-[#c56c39] rounded-full text-[10px] font-black uppercase tracking-widest border border-orange-100">
                    {{ $user->role->name }}
                </span>
                <p class="mt-4 text-xs text-gray-400 font-semibold leading-relaxed">
                    Tu cuenta está vinculada como <b>{{ $user->role->name }}</b>. Algunos datos críticos están protegidos por el administrador.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
