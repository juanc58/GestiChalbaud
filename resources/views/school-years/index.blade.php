@extends('dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#1A237E]">Años Escolares</h2>
            <p class="text-sm text-gray-500 mt-1">Administra los periodos académicos del sistema.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- New Year Form -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl p-6">
                <h3 class="text-sm font-semibold text-[#1A237E] mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#FBC02D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Aperturar Nuevo Periodo
                </h3>
                <form action="{{ route('school-years.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Año Escolar (ej: 2026-2027)</label>
                        <input type="text" name="year" required placeholder="YYYY-YYYY" value="{{ old('year') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#1A237E]/20 focus:border-[#1A237E] outline-none transition-all {{ $errors->has('year') ? 'border-[#D32F2F]' : '' }}">
                        @error('year')
                            <p class="text-[10px] text-[#D32F2F] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-[#1A237E] text-white text-sm font-medium py-2.5 rounded-lg hover:bg-[#1A237E]/90 transition-colors flex items-center justify-center gap-2">
                        Registrar Año
                    </button>
                </form>
            </div>
            
            <!-- Info Box -->
            <div class="mt-6 bg-[#FFF59D]/30 border border-[#FFF59D] rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-[#FBC02D] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <h4 class="text-xs font-bold text-[#1A237E]">Sobre el Año Activo</h4>
                    <p class="text-xs text-gray-600 mt-1 leading-relaxed">El periodo marcado como <strong>ACTIVO</strong> es el que se asocia por defecto a las inscripciones y vistas generales de aulas. Activa un nuevo año solo al iniciar un nuevo ciclo.</p>
                </div>
            </div>
        </div>

        <!-- Years List -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Año Escolar</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Estatus Global</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($years as $y)
                            <tr class="hover:bg-gray-50 transition-colors {{ $y->is_active ? 'bg-[#1A237E]/5' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-[#1A237E]">{{ $y->year }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($y->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#FBC02D]/20 text-[#1A237E] border border-[#FBC02D]/50">
                                            ACTIVO
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            Cerrado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if(!$y->is_active)
                                        <div class="flex items-center justify-end gap-3">
                                            <form action="{{ route('school-years.activate', $y->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-[#1A237E] hover:text-[#1A237E]/80 transition-colors bg-[#1A237E]/10 px-3 py-1.5 rounded-md text-xs font-semibold">
                                                    Establecer Activo
                                                </button>
                                            </form>
                                            <form action="{{ route('school-years.destroy', $y->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que deseas eliminar este periodo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-[#D32F2F] transition-colors p-1" title="Eliminar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">En curso</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <h3 class="text-sm font-medium text-gray-900">No hay periodos académicos</h3>
                                        <p class="text-sm text-gray-500 mt-1">Registra el primer año escolar para comenzar.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
