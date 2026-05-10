@extends('dashboard')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center space-x-4">
        <a href="{{ route('sections.index') }}" class="p-3 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-500 hover:text-[#032e5e] transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div>
            <h3 class="text-2xl font-extrabold text-[#032e5e]">Crear Nueva Sección</h3>
            <p class="text-gray-500 font-semibold">Agrega un aula para un grado específico.</p>
        </div>
    </div>

    <form action="{{ route('sections.store') }}" method="POST" class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 space-y-6">
        @csrf

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Grado Académico</label>
            <select name="grade_id" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 {{ $errors->has('grade_id') ? 'border-red-300' : 'border-transparent' }} focus:border-[#032e5e]/20 outline-none transition-all font-semibold appearance-none">
                <option value="" disabled {{ !old('grade_id') ? 'selected' : '' }}>Seleccione un grado...</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                @endforeach
            </select>
            @error('grade_id')
                <p class="text-[10px] font-bold text-red-500 ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase ml-1">Letra de la Sección</label>
                <input type="text" name="name" placeholder="Ej: A, B, U" required maxlength="2" value="{{ old('name') }}" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 {{ $errors->has('name') ? 'border-red-300' : 'border-transparent' }} focus:border-[#032e5e]/20 outline-none transition-all font-bold text-xl uppercase text-center text-[#032e5e]">
                @error('name')
                    <p class="text-[10px] font-bold text-red-500 text-center">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase ml-1">Turno</label>
                <select name="shift" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-semibold appearance-none">
                    <option value="Mañana">Mañana</option>
                    <option value="Tarde">Tarde</option>
                    <option value="Integral">Integral</option>
                </select>
            </div>
        </div>

        <div class="pt-4 mt-6 border-t border-gray-100">
            <button type="submit" class="w-full py-4 rounded-2xl font-bold bg-[#032e5e] text-white hover:bg-[#032e5e]/90 transition-all shadow-xl shadow-[#032e5e]/20 text-lg">
                Registrar Sección
            </button>
        </div>
    </form>
</div>
@endsection
