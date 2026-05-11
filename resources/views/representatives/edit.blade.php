@extends('dashboard')

@section('content')
<div class="max-w-full space-y-6 space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Editar Ficha de Representante</h3>
            <p class="text-gray-500 font-semibold">Cédula: {{ $representative->cedula }}</p>
        </div>
        <a href="{{ route('representatives.index') }}" class="px-6 py-2 bg-white border border-gray-100 rounded-xl font-bold text-xs text-gray-500 uppercase tracking-widest hover:bg-gray-50 transition-all">
            Volver
        </a>
    </div>

    <form action="{{ route('representatives.update', $representative->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Datos Personales -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
            <h4 class="text-xs font-black text-[#1A237E] uppercase tracking-widest flex items-center mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Datos Personales Básicos
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Nombre</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $representative->first_name) }}" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                    @error('first_name')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Nombre</label>
                    <input type="text" name="second_name" value="{{ old('second_name', $representative->second_name) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Primer Apellido</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $representative->last_name) }}" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                    @error('last_name')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Segundo Apellido</label>
                    <input type="text" name="second_last_name" value="{{ old('second_last_name', $representative->second_last_name) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>
            </div>
        </div>

        <!-- Contacto -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
            <h4 class="text-xs font-black text-[#FBC02D] uppercase tracking-widest flex items-center mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                Información de Contacto
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Teléfono Principal (Whatsapp)</label>
                    <input type="text" name="phone_whatsapp" value="{{ old('phone_whatsapp', $representative->phone_whatsapp) }}" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#c56c39]/20 outline-none transition-all font-bold text-[#1A237E]">
                    @error('phone_whatsapp')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Teléfono Secundario (Opcional)</label>
                    <input type="text" name="phone_local" value="{{ old('phone_local', $representative->phone_local) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#c56c39]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $representative->email) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#c56c39]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Facebook / RS</label>
                    <input type="text" name="facebook" value="{{ old('facebook', $representative->facebook) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#c56c39]/20 outline-none transition-all font-bold text-[#1A237E]">
                </div>
            </div>
        </div>

        <!-- Laboral -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
            <h4 class="text-xs font-black text-green-600 uppercase tracking-widest flex items-center mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Información Socioeconómica
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Oficio o Profesión</label>
                    <input type="text" name="job_title" value="{{ old('job_title', $representative->job_title) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-green-200 outline-none transition-all font-bold text-[#1A237E]">
                </div>
                <div class="group space-y-2 md:col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Lugar de Trabajo / Dirección Laboral</label>
                    <input type="text" name="workplace_address" value="{{ old('workplace_address', $representative->workplace_address) }}"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-green-200 outline-none transition-all font-bold text-[#1A237E]">
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
