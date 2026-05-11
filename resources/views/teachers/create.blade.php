@extends('dashboard')

@section('content')
<div class="max-w-full space-y-6">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Registro de Docente</h3>
            <p class="text-gray-500 font-semibold">Crea un nuevo perfil académico y su cuenta de acceso.</p>
        </div>
        <a href="{{ route('teachers.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">Volver al Listado</a>
    </div>

    <form action="{{ route('teachers.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Personal Info -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h4 class="text-xs font-black text-[#FBC02D] uppercase tracking-[0.2em] mb-8 flex items-center">
                <span class="w-8 h-8 rounded-xl bg-[#FBC02D]/10 flex items-center justify-center mr-3 text-[12px]">1</span>
                Datos Personales
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Cédula de Identidad</label>
                    <input type="number" name="cedula" value="{{ old('cedula') }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Género</label>
                    <select name="gender" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700 appearance-none">
                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Primer Nombre</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Segundo Nombre</label>
                    <input type="text" name="second_name" value="{{ old('second_name') }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Primer Apellido</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Segundo Apellido</label>
                    <input type="text" name="second_last_name" value="{{ old('second_last_name') }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Fecha de Nacimiento</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Teléfono de Contacto</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
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
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Fecha de Ingreso</label>
                    <input type="date" name="entry_date" value="{{ old('entry_date') }}" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Título Académico</label>
                    <input type="text" name="academic_degree" value="{{ old('academic_degree') }}" required placeholder="Ej: Lic. en Educación Integral" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 ml-1">Asignar Aula Inicial (Año {{ $currentYear }})</label>
                    <select name="section_id" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 outline-none transition-all font-bold text-gray-700 appearance-none">
                        <option value="">Sin asignar por ahora</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}">{{ $sec->grade->name }} "{{ $sec->name }}"</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-[#1A237E] text-white px-16 py-5 rounded-xl font-black text-sm hover:bg-[#1A237E]/90 transition-all shadow-2xl shadow-[#1A237E]/30 uppercase tracking-widest">
                Guardar Docente y Crear Usuario
            </button>
        </div>
    </form>
</div>
@endsection
