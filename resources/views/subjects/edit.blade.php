@extends('dashboard')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/50">
            <h3 class="text-2xl font-extrabold text-[#032e5e]">Editar Materia</h3>
            <p class="text-gray-500 font-semibold mt-1">Actualiza la información de la asignatura seleccionada.</p>
        </div>

        <form action="{{ route('subjects.update', $subject->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-extra-bold text-gray-400 uppercase tracking-[2px] mb-3 ml-1">Nombre de la Materia</label>
                <input type="text" name="name" value="{{ old('name', $subject->name) }}" required 
                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-semibold text-gray-700">
                @error('name') <p class="text-red-500 text-xs mt-2 ml-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-extra-bold text-gray-400 uppercase tracking-[2px] mb-3 ml-1">Descripción</label>
                <textarea name="description" rows="4" 
                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/10 focus:bg-white outline-none transition-all font-semibold text-gray-700">{{ old('description', $subject->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-2 ml-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-extra-bold text-gray-400 uppercase tracking-[2px] mb-3 ml-1">Grados/Niveles que verán esta materia</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 bg-gray-50 p-6 rounded-3xl border-2 border-transparent focus-within:border-[#032e5e]/10 transition-all">
                    @php $assignedIds = $subject->grades->pluck('id')->toArray(); @endphp
                    @foreach($grades as $grade)
                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input type="checkbox" name="grade_ids[]" value="{{ $grade->id }}" {{ in_array($grade->id, $assignedIds) ? 'checked' : '' }}
                            class="w-5 h-5 rounded text-[#032e5e] focus:ring-[#032e5e]/20 border-gray-300">
                        <span class="text-sm font-bold text-gray-600 group-hover:text-[#032e5e] transition-colors">{{ $grade->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('grade_ids') <p class="text-red-500 text-xs mt-2 ml-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center space-x-3 ml-1">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $subject->is_active ? 'checked' : '' }} 
                    class="w-5 h-5 rounded text-[#032e5e] focus:ring-[#032e5e]/20 border-gray-300">
                <label for="is_active" class="text-sm font-bold text-gray-700">Materia Activa</label>
            </div>

            <div class="pt-4 flex space-x-4">
                <a href="{{ route('subjects.index') }}" class="flex-1 px-8 py-4 rounded-2xl bg-gray-50 text-gray-500 font-bold hover:bg-gray-100 transition-all text-center">
                    Cancelar
                </a>
                <button type="submit" class="flex-[2] bg-[#032e5e] text-white px-8 py-4 rounded-2xl font-bold hover:bg-[#032e5e]/90 transition-all shadow-lg shadow-[#032e5e]/20">
                    Actualizar Materia
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
