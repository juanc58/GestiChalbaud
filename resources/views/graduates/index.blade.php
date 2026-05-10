@extends('dashboard')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
        <div class="mb-8">
        <h3 class="text-2xl font-extrabold text-[#032e5e]">Egresados del Plantel</h3>
        <p class="text-gray-500 font-semibold italic">Historial de alumnos que han culminado satisfactoriamente su etapa escolar.</p>
    </div>
        <span class="px-4 py-1.5 bg-[#032e5e]/10 text-[#032e5e] rounded-xl text-xs font-extrabold tracking-widest">
            Total Histórico: {{ \App\Models\Graduate::count() }}
        </span>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 mb-6">
        <form action="{{ route('graduates.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre o Cédula..." class="w-full px-5 py-2.5 rounded-xl bg-gray-50 border border-transparent outline-none focus:border-[#032e5e]/10 transition-all text-sm font-semibold">
            </div>
            
            <select name="year" class="px-5 py-2.5 rounded-xl bg-gray-50 border border-transparent outline-none focus:border-[#032e5e]/10 transition-all text-sm font-semibold appearance-none">
                <option value="">Todos los Años de Egreso</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>Año Escolar: {{ $y }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="bg-[#032e5e] text-white px-8 py-2 rounded-xl font-bold text-sm hover:bg-[#032e5e]/90 transition-all flex-1">
                    Buscar
                </button>
                @if(request()->anyFilled(['search', 'year']))
                    <a href="{{ route('graduates.index') }}" class="bg-gray-100 text-gray-400 p-2.5 rounded-xl hover:bg-gray-200 transition-all" title="Limpiar Filtros">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-100">
                    <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Cédula</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Estudiante</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-center">Año de Promoción</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Observaciones</th>
                    <th class="px-8 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($graduates as $graduate)
                <tr class="hover:bg-gray-50/50 transition-colors group">
                    <td class="px-8 py-5 text-gray-500 font-bold text-sm">{{ $graduate->student->cedula ?? 'S/C' }}</td>
                    <td class="px-8 py-5">
                        <span class="font-extrabold text-gray-800">{{ $graduate->student->last_name }}, {{ $graduate->student->first_name }}</span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold uppercase tracking-widest">{{ $graduate->promotion_year }}</span>
                    </td>
                    <td class="px-8 py-5 text-gray-500 font-semibold text-xs max-w-xs truncate" title="{{ $graduate->notes }}">
                        {{ $graduate->notes ?? '-' }}
                    </td>
                    <td class="px-8 py-5 text-right">
                            <a href="{{ route('students.show', ['student' => $graduate->student->id, 'from_graduates' => 1]) }}" class="px-4 py-2 bg-[#032e5e] text-white rounded-lg text-xs font-bold hover:bg-[#032e5e]/90 transition-all shadow-sm">
                                Ver Ficha Histórica
                            </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-16 text-center text-gray-400 font-bold">
                        Aún no hay registros de estudiantes egresados en el sistema.
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
@endsection
