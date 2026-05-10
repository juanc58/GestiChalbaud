@extends('dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-3xl font-extrabold text-[#032e5e]">Gestión de Periodos Académicos</h3>
            <p class="text-gray-500 font-semibold mt-1">Administra los años escolares del sistema Sepaez.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- New Year Form -->
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Nuevo Periodo</h4>
                <form action="{{ route('school-years.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#032e5e] ml-1">Año (ej: 2026-2027)</label>
                        <input type="text" name="year" required placeholder="YYYY-YYYY" value="{{ old('year') }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 {{ $errors->has('year') ? 'border-red-300' : 'border-transparent' }} focus:border-[#032e5e]/20 outline-none transition-all font-bold">
                        @error('year')
                            <p class="text-[10px] font-bold text-red-500 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full py-4 rounded-2xl font-bold bg-[#032e5e] text-white hover:bg-[#032e5e]/90 transition-all shadow-lg">
                        Crear Año
                    </button>
                </form>
            </div>
        </div>

        <!-- Years List -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Año Escolar</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Estatus Global</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($years as $y)
                        <tr class="{{ $y->is_active ? 'bg-blue-50/30' : '' }}">
                            <td class="px-6 py-4 font-black text-[#032e5e] tracking-tight text-lg">{{ $y->year }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($y->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest">ACTIVO</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-full text-[10px] font-bold uppercase tracking-widest">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex justify-end space-x-2">
                                @if(!$y->is_active)
                                    <form action="{{ route('school-years.activate', $y->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all text-xs font-bold">
                                            Activar Periodo
                                        </button>
                                    </form>
                                    <form action="{{ route('school-years.destroy', $y->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs font-bold text-gray-300 px-4 py-2">Sin acciones</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 p-6 bg-orange-50/50 rounded-3xl border border-orange-100">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h5 class="font-bold text-orange-900 leading-tight">¿Para qué sirve el Año Activo?</h5>
                        <p class="text-xs text-orange-700/80 leading-relaxed mt-1">El año marcado como **ACTIVO** es el que se asocia por defecto a las nuevas inscripciones de alumnos y el que se muestra en la vista principal de Aulas. Cambia el año activo solo cuando inicies un nuevo ciclo escolar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
