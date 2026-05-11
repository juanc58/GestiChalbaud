@extends('dashboard')

@section('content')
<div class="mb-8">
    <h3 class="text-2xl font-extrabold text-[#1A237E]">Directorio de Representantes</h3>
    <p class="text-gray-500 font-semibold">Base de datos de padres y representantes registrados en S.E. Páez.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex flex-col md:flex-row gap-4 bg-gray-50/50 items-center justify-between">
        <form action="{{ route('representatives.index') }}" method="GET" class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
            <div class="relative md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, Apellido o Cédula..." class="w-full px-5 py-2.5 rounded-xl bg-white border border-gray-200 outline-none focus:border-[#c56c39]/20 transition-all text-sm font-semibold">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-[#FBC02D] text-[#1A237E] px-6 py-2 rounded-xl font-bold text-sm hover:bg-[#FBC02D]/90 transition-all flex-1 shadow-md shadow-sm">
                    Buscar
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('representatives.index') }}" class="bg-white border border-gray-200 text-gray-400 p-2.5 rounded-xl hover:bg-gray-50 transition-all hover:text-red-500" title="Limpiar Búsqueda">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Identificación</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Contacto</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Estudiantes a Cargo</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($representatives as $rep)
                <tr class="hover:bg-orange-50/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-orange-100 text-[#FBC02D] flex items-center justify-center font-bold mr-3 border border-orange-200">
                                {{ substr($rep->first_name, 0, 1) }}{{ substr($rep->last_name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-[#1A237E]">{{ $rep->first_name }} {{ $rep->last_name }}</span>
                                <span class="text-xs font-semibold text-gray-400">CI: {{ $rep->cedula }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col space-y-1">
                            <div class="flex items-center text-sm font-semibold text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $rep->phone_whatsapp }}
                            </div>
                            @if($rep->email)
                            <div class="flex items-center text-[10px] font-bold text-gray-400">
                                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $rep->email }}
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-200">
                            {{ $rep->enrollments->count() }} Estudiante(s)
                        </span>
                    </td>
                    <td class="px-6 py-4 flex items-center space-x-3">
                        <a href="{{ route('representatives.edit', $rep->id) }}" class="p-2 text-[#FBC02D] hover:text-[#1A237E] hover:bg-orange-50 rounded-xl transition-all" title="Ver y Editar Ficha">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 font-semibold">
                        No se encontraron representantes asociados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-gray-50 bg-gray-50/30">
        {{ $representatives->links() }}
    </div>
</div>
@endsection
