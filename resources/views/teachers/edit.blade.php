@extends('dashboard')

@section('content')
<div class="max-w-full space-y-6">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Editar Docente</h3>
            <p class="text-gray-500 font-semibold">Actualizar información profesional de {{ $teacher->first_name }}.</p>
        </div>
        <a href="{{ route('teachers.show', $teacher->id) }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">Volver al Perfil</a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs font-bold">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Status Bar -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-2xl bg-[#FBC02D]/10 flex items-center justify-center text-[#FBC02D] mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Estatus Laboral</p>
                    <select name="status" class="bg-transparent font-bold text-gray-700 outline-none cursor-pointer">
                        <option value="Activo" {{ $teacher->status == 'Activo' ? 'selected' : '' }}>Docente Activo</option>
                        <option value="Inactivo" {{ $teacher->status == 'Inactivo' ? 'selected' : '' }}>Docente Inactivo</option>
                    </select>
                </div>
            </div>
            <p class="text-[10px] font-bold text-gray-400 italic">Última actualización: {{ $teacher->updated_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Personal Info -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h4 class="text-xs font-black text-[#FBC02D] uppercase tracking-[0.2em] mb-8 flex items-center">
                <span class="w-8 h-8 rounded-xl bg-[#FBC02D]/10 flex items-center justify-center mr-3 text-[12px]">1</span>
                Datos Personales
            </h4>

            <div class="mb-6 p-4 rounded-2xl bg-gray-50 border border-gray-200 flex flex-col sm:flex-row items-center gap-5">
                <div class="w-20 h-20 rounded-full overflow-hidden bg-white border-2 border-[#1A237E]/20 flex items-center justify-center flex-shrink-0">
                    <img id="teacher_photo_preview" src="{{ $teacher->photo_url ?? '' }}" alt="Foto" class="w-full h-full object-cover {{ $teacher->photo_url ? '' : 'hidden' }}">
                    <span id="teacher_photo_placeholder" class="text-xs font-extrabold text-gray-400 uppercase {{ $teacher->photo_url ? 'hidden' : '' }}">Sin Foto</span>
                </div>
                <div class="flex-1 space-y-2 text-center sm:text-left">
                    <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest">Fotografía del Docente (Opcional · JPG/PNG/WEBP, máx. 2 MB)</label>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
                           onchange="if(this.files && this.files[0]){ const img=document.getElementById('teacher_photo_preview'); img.src=URL.createObjectURL(this.files[0]); img.classList.remove('hidden'); document.getElementById('teacher_photo_placeholder').classList.add('hidden'); }"
                           class="block w-full text-xs text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1A237E] file:text-white hover:file:bg-[#1A237E]/90 cursor-pointer">
                    @if($teacher->photo_path)
                        <label class="inline-flex items-center gap-2 text-xs font-bold text-red-600 cursor-pointer">
                            <input type="checkbox" name="remove_photo" value="1" class="rounded text-red-600">
                            Eliminar fotografía actual
                        </label>
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Cédula de Identidad</label>
                    <input type="number" name="cedula" value="{{ old('cedula', $teacher->cedula) }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Género</label>
                    <select name="gender" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700 appearance-none">
                        <option value="">Sin especificar</option>
                        <option value="M" {{ old('gender', $teacher->gender) == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('gender', $teacher->gender) == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Primer Nombre</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $teacher->first_name) }}" required oninput="this.value = this.value.toUpperCase()" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700 uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Segundo Nombre</label>
                    <input type="text" name="second_name" value="{{ old('second_name', $teacher->user?->second_name) }}" oninput="this.value = this.value.toUpperCase()" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700 uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Primer Apellido</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $teacher->last_name) }}" required oninput="this.value = this.value.toUpperCase()" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700 uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Segundo Apellido</label>
                    <input type="text" name="second_last_name" value="{{ old('second_last_name', $teacher->user?->second_last_name) }}" oninput="this.value = this.value.toUpperCase()" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700 uppercase">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Fecha de Nacimiento (Día-Mes-Año) <span class="text-red-500">*</span></label>
                    <x-date-input-es name="birth_date" :value="old('birth_date', $teacher->birth_date)" :required="true" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700" />
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Correo Electrónico <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $teacher->email) }}" required placeholder="nombre@correo.com" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Teléfono de Contacto <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" required placeholder="Ej: 04121234567" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
            </div>
        </div>

        <!-- Labor Info -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h4 class="text-xs font-black text-[#FBC02D] uppercase tracking-[0.2em] mb-8 flex items-center">
                <span class="w-8 h-8 rounded-xl bg-[#FBC02D]/10 flex items-center justify-center mr-3 text-[12px]">2</span>
                Información Laboral
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Fecha de Ingreso (Día-Mes-Año) <span class="text-red-500">*</span></label>
                    <x-date-input-es name="entry_date" :value="old('entry_date', $teacher->entry_date)" :required="true" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700" />
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Título Académico</label>
                    <input type="text" name="academic_degree" value="{{ old('academic_degree', $teacher->academic_degree) }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 gap-4">
            <button type="submit" class="bg-[#1A237E] text-white px-16 py-5 rounded-xl font-black text-sm hover:bg-[#1A237E]/90 transition-all shadow-2xl shadow-[#1A237E]/30 uppercase tracking-widest">
                Actualizar Datos
            </button>
        </div>
    </form>
</div>
@endsection
