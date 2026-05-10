@extends('dashboard')

@section('content')
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="text-2xl font-extrabold text-[#032e5e]">Materias</h3>
                <p class="text-gray-500 font-semibold mt-1">Gestión global de materias impartidas en la institución.</p>
            </div>
            <a href="{{ route('subjects.create') }}"
                class="bg-[#032e5e] text-white px-8 py-3 rounded-2xl font-bold text-sm hover:bg-[#032e5e]/90 transition-all shadow-lg shadow-[#032e5e]/20 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Materia
            </a>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($subjects as $subject)
                    <div
                        class="group bg-white p-6 rounded-3xl border-2 {{ $subject->is_active ? 'border-gray-50 hover:border-[#032e5e]/10' : 'border-red-50 opacity-75' }} transition-all relative overflow-hidden shadow-sm hover:shadow-md">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="p-3 rounded-2xl {{ $subject->is_active ? 'bg-blue-50 text-[#032e5e]' : 'bg-red-50 text-red-400' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('subjects.edit', $subject->id) }}"
                                    class="p-2 text-gray-400 hover:text-[#032e5e] hover:bg-gray-50 rounded-xl transition-all"
                                    title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M11 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V13"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16.04 3.02001L8.16 10.9C7.86 11.2 7.56 11.79 7.5 12.22L7.07 15.23C6.91 16.32 7.68 17.08 8.77 16.93L11.78 16.5C12.2 16.44 12.79 16.14 13.1 15.84L20.98 7.96001C22.34 6.60001 22.98 5.02001 20.98 3.02001C18.98 1.02001 17.4 1.66001 16.04 3.02001Z">
                                        </path>
                                    </svg>
                                </a>
                                <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('¿Estás seguro de eliminar esta materia?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                        title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M21 5.97998C17.67 5.64998 14.32 5.47998 10.98 5.47998C9 5.47998 7.02 5.57998 5.04 5.77998L3 5.97998">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M8.5 4.97L8.72 3.66C8.88 2.71 9 2 10.69 2H13.31C15 2 15.13 2.75 15.28 3.67L15.5 4.97">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M18.85 9.14001L18.2 19.21C18.1 20.78 18 22 15.21 22H8.79002C6.00002 22 5.90002 20.78 5.80002 19.21L5.15002 9.14001">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M10.33 16.5H13.66"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.5 12.5H14.5"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2 truncate" title="{{ $subject->name }}">
                            {{ $subject->name }}</h4>
                        <p class="text-gray-500 text-sm line-clamp-2 mb-4 h-10">
                            {{ $subject->description ?? 'Sin descripción.' }}</p>

                        <!-- Display Assigned Grades/Levels -->
                        <div class="mb-4 flex flex-wrap gap-1">
                            @foreach($subject->grades as $grade)
                                <span
                                    class="text-[9px] font-bold bg-gray-100 text-gray-500 px-2 py-0.5 rounded-md uppercase tracking-tighter">{{ $grade->name }}</span>
                            @endforeach
                        </div>

                        <div class="flex items-center">
                            @if($subject->is_active)
                                <span
                                    class="flex items-center text-[10px] font-bold uppercase tracking-widest text-green-500 bg-green-50 px-3 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                    Activa
                                </span>
                            @else
                                <span
                                    class="flex items-center text-[10px] font-bold uppercase tracking-widest text-red-400 bg-red-50 px-3 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1.5"></span>
                                    Inactiva
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100">
                        <div
                            class="p-4 bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 border border-gray-100 italic text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-gray-400 font-bold tracking-widest uppercase text-xs">No hay materias registradas</h4>
                        <p class="text-gray-400 text-xs mt-1">Comienza agregando una para poder evaluar el desempeño escolar.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection