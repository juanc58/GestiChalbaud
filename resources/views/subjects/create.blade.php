@extends('dashboard')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/50">
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Nueva Materia</h3>
            <p class="text-gray-500 font-semibold mt-1">Registra una nueva asignatura para el currículo escolar.</p>
        </div>

        <form action="{{ route('subjects.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div>
                <label class="block text-xs font-extra-bold text-gray-400 uppercase tracking-[2px] mb-3 ml-1">Nombre de la Materia</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Eje: Matemática, Lengua y Ciudadanía..." 
                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 focus:bg-white outline-none transition-all font-semibold text-gray-700">
                @error('name') <p class="text-red-500 text-xs mt-2 ml-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-extra-bold text-gray-400 uppercase tracking-[2px] mb-3 ml-1">Descripción (Opcional)</label>
                <textarea name="description" rows="4" placeholder="Breve resumen de los contenidos..." 
                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/10 focus:bg-white outline-none transition-all font-semibold text-gray-700">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-2 ml-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-extra-bold text-gray-400 uppercase tracking-[2px] mb-3 ml-1">Grados/Niveles que verán esta materia</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 bg-gray-50 p-6 rounded-xl border-2 border-transparent focus-within:border-[#1A237E]/10 transition-all">
                    @foreach($grades as $grade)
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input type="checkbox" name="grade_ids[]" value="{{ $grade->id }}" 
                            class="w-5 h-5 rounded text-[#1A237E] focus:ring-[#1A237E]/20 border-gray-300">
                        <span class="text-sm font-bold text-gray-600 group-hover:text-[#1A237E] transition-colors">{{ $grade->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('grade_ids') <p class="text-red-500 text-xs mt-2 ml-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 flex space-x-4">
                <a href="{{ route('subjects.index') }}" class="flex-1 px-8 py-4 rounded-2xl bg-gray-50 text-gray-500 font-bold hover:bg-gray-100 transition-all text-center">
                    Cancelar
                </a>
                <button type="submit" class="flex-[2] bg-[#1A237E] text-white px-8 py-4 rounded-2xl font-bold hover:bg-[#1A237E]/90 transition-all shadow-sm">
                    Guardar Materia
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
