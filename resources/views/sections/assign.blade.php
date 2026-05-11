@extends('dashboard')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex justify-between items-end">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <a href="{{ route('sections.show', $section->id) }}" class="text-gray-400 hover:text-[#1A237E] transition-colors font-bold text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Volver al Aula
                </a>
            </div>
            <h3 class="text-3xl font-extrabold text-[#1A237E]">Asignación Masiva</h3>
            <p class="text-gray-500 font-semibold mt-1">
                Inscribiendo en: <span class="text-[#1A237E]">{{ $section->grade->name }} "{{ $section->name }}"</span> 
                ({{ $section->shift }})
            </p>
        </div>
        <div class="bg-white px-6 py-4 rounded-xl shadow-sm border border-gray-100">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Periodo</div>
            <div class="text-xl font-black text-[#1A237E]">{{ $currentYear }}</div>
        </div>
    </div>

    <form action="{{ route('sections.assign.store', $section->id) }}" method="POST" id="assignForm" class="space-y-8">
        @csrf
        <input type="hidden" name="school_year" value="{{ $currentYear }}">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Left Pane: Search & Available -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-6">
                <div class="flex justify-between items-center">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Disponibles</h4>
                    <button type="button" onclick="addAll()" class="text-xs font-bold text-[#1A237E] hover:bg-[#1A237E]/5 px-3 py-1 rounded-lg transition-all">Agregar Visibles</button>
                </div>

                <div class="relative">
                    <input type="text" id="studentSearch" placeholder="🔍 Buscar nombre o C.I..." class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none transition-all font-semibold">
                </div>

                <div class="bg-gray-50/50 rounded-2xl border border-gray-100 overflow-hidden h-[450px] overflow-y-auto custom-scrollbar">
                    <div id="availableList" class="divide-y divide-gray-100">
                        @forelse($students as $student)
                            <div class="student-item p-4 flex items-center justify-between hover:bg-white transition-all cursor-pointer group" 
                                 id="student_{{ $student->id }}"
                                 onclick="addStudent({{ $student->id }}, '{{ $student->last_name }}, {{ $student->first_name }}', '{{ $student->cedula ?? 'S/C' }}')"
                                 data-search="{{ strtolower($student->first_name . ' ' . $student->last_name . ' ' . $student->cedula) }}">
                                <div>
                                    <div class="font-bold text-gray-800 text-sm tracking-tight">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase opacity-60">C.I. {{ $student->cedula ?? 'S/C' }}</div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-white border border-gray-100 flex items-center justify-center text-gray-300 group-hover:bg-[#1A237E] group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center text-gray-400 font-bold text-sm">No hay alumnos aptos para este grado.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Pane: List to Enroll -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-orange-100 space-y-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-full -mr-16 -mt-16 opacity-50"></div>
                
                <div class="flex justify-between items-center relative z-10">
                    <h4 class="text-xs font-bold text-[#FBC02D] uppercase tracking-[0.2em]">Inscripción de Hoy</h4>
                    <span id="selectedCount" class="px-3 py-1 bg-[#FBC02D]/10 text-[#FBC02D] rounded-full text-[10px] font-black">0 ALUMNOS</span>
                </div>

                <div class="bg-gray-50/50 rounded-2xl border-2 border-dashed border-[#c56c39]/10 h-[518px] overflow-y-auto custom-scrollbar p-2 relative z-10">
                    <div id="selectionList" class="space-y-2">
                        <!-- Selected students will appear here -->
                    </div>
                    
                    <div id="emptySelection" class="h-full flex flex-col items-center justify-center text-gray-300 pointer-events-none">
                        <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mb-4 shadow-sm">
                            <svg class="w-8 h-8 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <p class="font-bold text-sm">Selecciona alumnos de la izquierda</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 bg-[#1A237E]/5 rounded-xl border border-[#1A237E]/10">
            <div class="flex items-start space-x-6">
                <div class="w-12 h-12 rounded-2xl bg-[#1A237E] text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h5 class="font-black text-[#1A237E] text-lg">Protección de Trayectoria Académica</h5>
                    <p class="text-sm text-gray-500 font-medium leading-relaxed mt-1">El filtro automático impide inscribir estudiantes en grados inferiores a los ya aprobados. Esto garantiza que la jerarquía <span class="text-[#FBC02D] font-bold">Inicial < Primaria</span> se mantenga íntegra.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" id="submitBtn" disabled class="px-12 py-5 rounded-xl font-bold bg-gray-100 text-gray-400 cursor-not-allowed transition-all shadow-xl text-lg flex items-center transform active:scale-95">
                Confirmar e Inscribir
                <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </button>
        </div>
    </form>
</div>

<script>
    const availableList = document.getElementById('availableList');
    const selectionList = document.getElementById('selectionList');
    const emptySelection = document.getElementById('emptySelection');
    const submitBtn = document.getElementById('submitBtn');
    const selectedCount = document.getElementById('selectedCount');

    function addStudent(id, name, cedula) {
        const item = document.getElementById('student_' + id);
        if (!item || item.classList.contains('selected-hidden')) return;

        item.style.display = 'none';
        item.classList.add('selected-hidden');

        const selectedHtml = `
            <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-orange-100 shadow-sm animate-fadeIn" id="selected_${id}">
                <input type="hidden" name="student_ids[]" value="${id}">
                <div>
                    <div class="font-bold text-gray-800 text-sm tracking-tight">${name}</div>
                    <div class="text-[10px] font-bold text-gray-400">C.I. ${cedula}</div>
                </div>
                <button type="button" onclick="removeStudent(${id})" class="w-10 h-10 rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center group/bin">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        `;
        selectionList.insertAdjacentHTML('beforeend', selectedHtml);
        updateUI();
    }

    function removeStudent(id) {
        const selectedItem = document.getElementById('selected_' + id);
        if (selectedItem) selectedItem.remove();
        
        const item = document.getElementById('student_' + id);
        if (item) {
            item.style.display = 'flex';
            item.classList.remove('selected-hidden');
            // Re-apply search filter if currently searching
            const term = document.getElementById('studentSearch').value.toLowerCase();
            const searchData = item.getAttribute('data-search');
            if (term && !searchData.includes(term)) {
                item.style.display = 'none';
            }
        }
        updateUI();
    }

    function addAll() {
        const items = document.querySelectorAll('.student-item');
        items.forEach(item => {
            if (item.style.display !== 'none' && !item.classList.contains('selected-hidden')) {
                const id = item.id.replace('student_', '');
                const name = item.querySelector('.font-bold').innerText;
                const cedula = item.querySelector('.text-[10px]').innerText.replace('C.I. ', '');
                addStudent(id, name, cedula);
            }
        });
    }

    function updateUI() {
        const count = selectionList.querySelectorAll('[name="student_ids[]"]').length;
        selectedCount.innerText = count + " ALUMNOS";
        
        if (count > 0) {
            emptySelection.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-[#1A237E]', 'text-white', 'hover:bg-[#1A237E]/90');
        } else {
            emptySelection.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-[#1A237E]', 'text-white', 'hover:bg-[#1A237E]/90');
        }
    }

    // Search filter
    document.getElementById('studentSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.student-item');
        
        items.forEach(item => {
            if (item.classList.contains('selected-hidden')) return; 

            const searchData = item.getAttribute('data-search');
            item.style.display = searchData.includes(term) ? 'flex' : 'none';
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.2s ease-out forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>
@endsection
