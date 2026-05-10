@extends('dashboard')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-extrabold text-[#032e5e]">Editar Docente</h3>
            <p class="text-gray-500 font-semibold">Actualizar información profesional de {{ $teacher->first_name }}.</p>
        </div>
        <a href="{{ route('teachers.show', $teacher->id) }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">Volver al Perfil</a>
    </div>

    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Status Bar -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-2xl bg-[#c56c39]/10 flex items-center justify-center text-[#c56c39] mr-4">
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
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h4 class="text-xs font-black text-[#c56c39] uppercase tracking-[0.2em] mb-8 flex items-center">
                <span class="w-8 h-8 rounded-xl bg-[#c56c39]/10 flex items-center justify-center mr-3 text-[12px]">1</span>
                Datos Personales
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Cédula de Identidad</label>
                    <input type="number" name="cedula" value="{{ old('cedula', $teacher->cedula) }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Género</label>
                    <select name="gender" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700 appearance-none">
                        <option value="M" {{ old('gender', $teacher->gender) == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('gender', $teacher->gender) == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Primer Nombre</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $teacher->first_name) }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Segundo Nombre</label>
                    <input type="text" name="second_name" value="{{ old('second_name', $teacher->user?->second_name) }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Primer Apellido</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $teacher->last_name) }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Segundo Apellido</label>
                    <input type="text" name="second_last_name" value="{{ old('second_last_name', $teacher->user?->second_last_name) }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Fecha de Nacimiento</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $teacher->birth_date) }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $teacher->email) }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Teléfono de Contacto</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
            </div>
        </div>

        <!-- Labor Info -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h4 class="text-xs font-black text-[#c56c39] uppercase tracking-[0.2em] mb-8 flex items-center">
                <span class="w-8 h-8 rounded-xl bg-[#c56c39]/10 flex items-center justify-center mr-3 text-[12px]">2</span>
                Información Laboral
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Fecha de Ingreso</label>
                    <input type="date" name="entry_date" value="{{ old('entry_date', $teacher->entry_date) }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Título Académico</label>
                    <input type="text" name="academic_degree" value="{{ old('academic_degree', $teacher->academic_degree) }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 outline-none transition-all font-bold text-gray-700">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 gap-4">
            <button type="submit" class="bg-[#032e5e] text-white px-16 py-5 rounded-3xl font-black text-sm hover:bg-[#032e5e]/90 transition-all shadow-2xl shadow-[#032e5e]/30 uppercase tracking-widest">
                Actualizar Datos
            </button>
        </div>
    </form>
</div>
@endsection
