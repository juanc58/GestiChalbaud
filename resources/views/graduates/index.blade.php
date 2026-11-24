@extends('dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Libro Histórico de Egresados</h3>
            <p class="text-gray-500 font-semibold text-sm mt-1">Alumnos que han culminado satisfactoriamente su etapa académica en la U.E.B. Coronel Carlos Delgado Chalbaud.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 self-start sm:self-auto">
            <span class="px-4 py-2.5 bg-[#1A237E]/10 text-[#1A237E] rounded-xl text-xs font-extrabold tracking-widest">
                Total Histórico: {{ \App\Models\Graduate::count() }}
            </span>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('students.create', ['historical' => 1]) }}"
                   class="px-5 py-2.5 bg-[#FBC02D] text-[#1A237E] rounded-xl text-xs font-extrabold uppercase tracking-wider hover:bg-[#FBC02D]/90 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Registrar Egresado Histórico
                </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Barra de Filtros -->
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <form action="{{ route('graduates.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, Apellido o Cédula..."
                           class="w-full px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#1A237E] transition-all text-sm font-semibold">
                </div>

                <select name="year" class="w-full px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#1A237E] transition-all text-sm font-semibold appearance-none">
                    <option value="">Todos los Años de Egreso</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>Promoción: {{ $y }}</option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="bg-[#1A237E] text-white px-8 py-2.5 rounded-xl font-bold text-sm hover:bg-[#1A237E]/90 transition-all flex-1">
                        Buscar
                    </button>
                    @if(request()->anyFilled(['search', 'year']))
                        <a href="{{ route('graduates.index') }}" class="bg-white border border-gray-200 text-gray-400 p-2.5 rounded-xl hover:bg-gray-50 hover:text-red-500 transition-all flex items-center justify-center" title="Limpiar Filtros">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Cédula</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Estudiante Egresado</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Promoción</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Observaciones</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($graduates as $graduate)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-gray-600 font-bold text-sm">{{ $graduate->student->cedula ?? 'S/C' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-[#1A237E]">{{ $graduate->student->last_name }} {{ $graduate->student->second_last_name }}, {{ $graduate->student->first_name }} {{ $graduate->student->second_name }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $graduate->promotion_year }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-semibold text-xs max-w-xs truncate" title="{{ $graduate->notes }}">
                            {{ $graduate->notes ?: '—' }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                @if(Route::has('academic-records.show'))
                                    <a href="{{ route('academic-records.show', $graduate->student->id) }}"
                                       class="px-3 py-1.5 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg text-xs font-bold hover:bg-amber-100 transition-all whitespace-nowrap">
                                        Récord Académico
                                    </a>
                                @endif
                                @if(Route::has('documents.preview'))
                                    <a href="{{ route('documents.preview', [$graduate->student->id, 'prosecucion']) }}"
                                       class="px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-all whitespace-nowrap">
                                        Constancia
                                    </a>
                                @endif
                                <a href="{{ route('students.show', ['student' => $graduate->student->id, 'from_graduates' => 1]) }}"
                                   class="px-3 py-1.5 bg-[#1A237E] text-white rounded-lg text-xs font-bold hover:bg-[#1A237E]/90 transition-all shadow-sm whitespace-nowrap">
                                    Ficha
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center text-gray-400 font-bold">
                            Aún no hay registros de estudiantes egresados con los filtros seleccionados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6 border-t border-gray-50 bg-gray-50/30">
            {{ $graduates->links() }}
        </div>
    </div>
</div>
@endsection
