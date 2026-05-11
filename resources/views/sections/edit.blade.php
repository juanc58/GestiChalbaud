@extends('dashboard')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center space-x-4">
        <a href="{{ route('sections.index') }}" class="p-3 bg-white rounded-xl shadow-sm border border-gray-200 text-gray-500 hover:text-[#1A237E] transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Editar Sección</h3>
            <p class="text-gray-500 font-semibold">Modifica los detalles del aula: {{ $section->grade->name }} "{{ $section->name }}"</p>
        </div>
    </div>

    <form action="{{ route('sections.update', $section->id) }}" method="POST" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Grado Académico</label>
            <select name="grade_id" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 {{ $errors->has('grade_id') ? 'border-red-300' : 'border-transparent' }} focus:border-[#1A237E]/20 outline-none transition-all font-semibold appearance-none">
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" {{ (old('grade_id', $section->grade_id) == $grade->id) ? 'selected' : '' }}>{{ $grade->name }}</option>
                @endforeach
            </select>
            @error('grade_id')
                <p class="text-[10px] font-bold text-red-500 ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase ml-1">Letra de la Sección</label>
                <input type="text" name="name" placeholder="Ej: A, B, U" required maxlength="2" value="{{ old('name', $section->name) }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 {{ $errors->has('name') ? 'border-red-300' : 'border-transparent' }} focus:border-[#1A237E]/20 outline-none transition-all font-bold text-xl uppercase text-center text-[#1A237E]">
                @error('name')
                    <p class="text-[10px] font-bold text-red-500 text-center">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase ml-1">Turno</label>
                <select name="shift" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-semibold appearance-none">
                    <option value="Mañana" {{ old('shift', $section->shift) == 'Mañana' ? 'selected' : '' }}>Mañana</option>
                    <option value="Tarde" {{ old('shift', $section->shift) == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                    <option value="Integral" {{ old('shift', $section->shift) == 'Integral' ? 'selected' : '' }}>Integral</option>
                </select>
            </div>
        </div>

        @if(auth()->user()->role->name === 'admin')
        <div class="space-y-2 pt-4">
            <label class="text-xs font-black text-[#FBC02D] uppercase ml-1 tracking-widest">Profesor Encargado (Año {{ $currentYear }})</label>
            <select name="teacher_id" class="w-full px-5 py-4 rounded-2xl bg-orange-50/50 border-2 border-orange-100 focus:border-[#c56c39]/30 outline-none transition-all font-bold text-gray-700 appearance-none">
                <option value="">Sin profesor asignado</option>
                @foreach($teachers as $teacher)
                    @php 
                        $isCurrent = $section->currentTeacherAssignment($currentYear)->first()?->teacher_id == $teacher->id;
                    @endphp
                    <option value="{{ $teacher->id }}" {{ $isCurrent ? 'selected' : '' }}>
                        {{ $teacher->first_name }} {{ $teacher->last_name }} (V-{{ $teacher->cedula }})
                    </option>
                @endforeach
            </select>
            <p class="text-[9px] font-bold text-gray-400 ml-1 italic">* Solo se muestran docentes disponibles para este año escolar.</p>
        </div>
        @endif

        <div class="pt-4 mt-6 border-t border-gray-100">
            <button type="submit" class="w-full py-4 rounded-2xl font-bold bg-[#FBC02D] text-[#1A237E] hover:bg-[#FBC02D]/90 transition-all shadow-xl shadow-sm text-lg">
                Actualizar Sección
            </button>
        </div>
    </form>
</div>
@endsection
