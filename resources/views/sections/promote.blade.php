@extends('dashboard')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <a href="{{ route('sections.show', $section->id) }}" class="text-gray-400 hover:text-[#032e5e] transition-colors font-bold text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Volver al Aula
                </a>
            </div>
            <h3 class="text-3xl font-extrabold text-[#032e5e]">Cierre de Año Escolar</h3>
            <p class="text-gray-500 font-semibold mt-1">
                Promoviendo desde: <span class="text-[#c56c39]">{{ $section->grade->name }} "{{ $section->name }}"</span>
            </p>
        </div>
        <div class="bg-white px-8 py-5 rounded-3xl shadow-sm border border-gray-100 text-right">
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1 text-center">Próximo Periodo</div>
            <div class="text-2xl font-black text-[#032e5e]">{{ $nextYear }}</div>
        </div>
    </div>

    <!-- Alert about Year Switching -->
    <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100/50 flex items-start space-x-4">
        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <h5 class="font-bold text-blue-900 leading-tight">Proceso de Promoción Seguro</h5>
            <p class="text-xs text-blue-700/80 leading-relaxed mt-1">Este proceso **NO** cerrará el sistema globalmente. Podrás seguir trabajando en otras aulas del {{ $currentYear }}. Cuando termines con todas, activa manualmente el año {{ $nextYear }} en el menú de Años Escolares.</p>
        </div>
    </div>

    <!-- Main Form -->
    <form action="{{ route('sections.promote.store', $section->id) }}" method="POST" id="promoteForm">
        @csrf
        <input type="hidden" name="next_school_year" value="{{ $nextYear }}">

        <!-- Multi-Pane Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 h-[650px]">
            
            <!-- Left Pane: Students to Process -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col space-y-6">
                <div class="flex justify-between items-center">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">Pendientes por Evaluar</h4>
                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-full text-[10px] font-black" id="pendingCount">{{ $section->enrollments->count() }} ALUMNOS</span>
                </div>

                <div class="relative">
                    <input type="text" id="studentSearch" placeholder="🔍 Buscar por apellido o C.I..." class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-semibold">
                </div>

                <div class="flex-1 bg-gray-50/50 rounded-2xl border border-gray-50 overflow-y-auto custom-scrollbar">
                    <div id="pendingList" class="divide-y divide-gray-100">
                        @foreach($section->enrollments as $enrollment)
                            <div class="student-item p-4 hover:bg-white transition-all cursor-pointer group flex items-center justify-between" 
                                 id="enrollment_{{ $enrollment->id }}"
                                 onclick="openConfig({{ $enrollment->id }}, '{{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }}', '{{ $enrollment->student->cedula ?? 'S/C' }}')"
                                 data-search="{{ strtolower($enrollment->student->last_name . ' ' . $enrollment->student->cedula) }}">
                                <div>
                                    <div class="font-bold text-gray-800 text-sm tracking-tight">{{ $enrollment->student->last_name }}, {{ $enrollment->student->first_name }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter opacity-70">C.I. {{ $enrollment->student->cedula ?? 'S/C' }}</div>
                                </div>
                                <div class="w-8 h-8 rounded-lg bg-white border border-gray-100 flex items-center justify-center text-gray-300 group-hover:bg-[#032e5e] group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Pane: Summary of Promotion -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-orange-100 flex flex-col space-y-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-48 h-48 bg-orange-50/40 rounded-full -mr-24 -mt-24 z-0"></div>
                
                <div class="flex justify-between items-center relative z-10">
                    <h4 class="text-xs font-bold text-[#c56c39] uppercase tracking-[0.2em]">Listos para Aplicar</h4>
                    <span id="readyCount" class="px-3 py-1 bg-[#c56c39]/10 text-[#c56c39] rounded-full text-[10px] font-black">0 PROCESADOS</span>
                </div>

                <div class="flex-1 bg-gray-50/50 rounded-2xl border-2 border-dashed border-[#c56c39]/10 overflow-y-auto custom-scrollbar p-2 relative z-10">
                    <div id="readyList" class="space-y-2">
                        <!-- Ready students go here -->
                    </div>
                    
                    <div id="emptyReady" class="h-full flex flex-col items-center justify-center text-gray-300 pointer-events-none">
                        <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center mb-4 shadow-sm">
                            <svg class="w-10 h-10 opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <p class="font-bold text-sm">Configura alumnos de la izquierda</p>
                    </div>
                </div>

                <!-- Submit Button inside context -->
                <div class="pt-4 relative z-10">
                    <button type="submit" id="submitBtn" disabled class="w-full py-5 rounded-[2rem] font-bold bg-gray-100 text-gray-400 cursor-not-allowed transition-all shadow-xl text-lg flex items-center justify-center group active:scale-95">
                        <span class="mr-2">Confirmar Cierre de Aula</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Configuration Modal -->
<div id="configOverlay" class="fixed inset-0 bg-[#032e5e]/40 backdrop-blur-sm z-50 flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <div id="configModal" class="bg-white w-[500px] rounded-[3rem] shadow-2xl overflow-hidden transform scale-90 transition-all duration-300">
        <div class="bg-[#032e5e] p-8 text-white relative">
            <h4 class="text-xs font-bold uppercase tracking-widest opacity-60 mb-1">Configurar Promoción</h4>
            <h3 id="modalStudentName" class="text-2xl font-black tracking-tight">Nombre del Estudiante</h3>
            <div class="absolute top-8 right-8 cursor-pointer text-white/50 hover:text-white" onclick="closeConfig()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
        </div>
        
        <div class="p-10 space-y-8">
            <!-- Hidden context -->
            <input type="hidden" id="currentEnrollmentId">
            <input type="hidden" id="currentStudentCedula">

            <!-- Status Choice -->
            <div class="space-y-4">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Estatus del Año</label>
                <div class="grid grid-cols-2 gap-4">
                    <div id="statusPass" onclick="toggleStatus('Aprobado')" class="p-4 rounded-2xl border-2 border-[#032e5e] bg-blue-50/50 cursor-pointer transition-all flex flex-col items-center text-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="font-bold text-[#032e5e] text-sm italic">APROBADO</span>
                    </div>
                    <div id="statusFail" onclick="toggleStatus('Reprobado')" class="p-4 rounded-2xl border-2 border-transparent bg-gray-50 cursor-pointer hover:bg-red-50/30 transition-all flex flex-col items-center text-center">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <span class="font-bold text-gray-400 text-sm">REPROBADO</span>
                    </div>
                </div>
            </div>

            <!-- Target Section -->
            <div class="space-y-4">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Sección Destino ({{ $nextYear }})</label>
                <select id="modalTargetSection" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-bold text-[#032e5e] appearance-none">
                    <option value="" data-rank="0">No asignar (Egresado/Retirado)</option>
                    @foreach($allGrades as $g)
                        @foreach($g->sections as $s)
                            <option value="{{ $s->id }}" data-rank="{{ $g->rank }}">
                                {{ $g->name }} - "{{ $s->name }}" ({{ $s->shift }})
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <button type="button" onclick="confirmStudentConfig()" class="w-full py-5 rounded-2xl font-bold bg-[#c56c39] text-white hover:bg-[#c56c39]/90 transition-all shadow-xl shadow-[#c56c39]/20 text-lg">
                Procesar Estudiante
            </button>
        </div>
    </div>
</div>

<script>
    const currentRank = {{ $currentRank }};
    const configOverlay = document.getElementById('configOverlay');
    const configModal = document.getElementById('configModal');
    let currentStatus = 'Aprobado';

    function openConfig(id, name, cedula) {
        document.getElementById('modalStudentName').innerText = name;
        document.getElementById('currentEnrollmentId').value = id;
        document.getElementById('currentStudentCedula').value = cedula;
        
        // Reset defaults
        toggleStatus('Aprobado');
        
        configOverlay.classList.remove('opacity-0', 'pointer-events-none');
        configModal.classList.remove('scale-90');
    }

    function closeConfig() {
        configOverlay.classList.add('opacity-0', 'pointer-events-none');
        configModal.classList.add('scale-90');
    }

    function toggleStatus(status) {
        currentStatus = status;
        const passCard = document.getElementById('statusPass');
        const failCard = document.getElementById('statusFail');
        const targetSelect = document.getElementById('modalTargetSection');
        
        if (status === 'Aprobado') {
            passCard.className = "p-4 rounded-2xl border-2 border-[#032e5e] bg-blue-50/50 cursor-pointer transition-all flex flex-col items-center text-center";
            failCard.className = "p-4 rounded-2xl border-2 border-transparent bg-gray-50 cursor-pointer hover:bg-red-50/30 transition-all flex flex-col items-center text-center";
        } else {
            failCard.className = "p-4 rounded-2xl border-2 border-red-500 bg-red-50/30 cursor-pointer transition-all flex flex-col items-center text-center";
            passCard.className = "p-4 rounded-2xl border-2 border-transparent bg-gray-50 cursor-pointer hover:bg-blue-50/30 transition-all flex flex-col items-center text-center";
        }

        // Filter valid target sections in modal select
        Array.from(targetSelect.options).forEach(opt => {
            if (opt.value === "") return;
            const rank = parseInt(opt.getAttribute('data-rank'));
            if (status === 'Aprobado') {
                opt.disabled = rank <= currentRank;
                opt.style.display = rank <= currentRank ? 'none' : 'block';
            } else {
                opt.disabled = rank !== currentRank;
                opt.style.display = rank !== currentRank ? 'none' : 'block';
            }
        });
        
        if (targetSelect.selectedOptions[0].disabled) targetSelect.value = "";
    }

    function confirmStudentConfig() {
        const id = document.getElementById('currentEnrollmentId').value;
        const name = document.getElementById('modalStudentName').innerText;
        const cedula = document.getElementById('currentStudentCedula').value;
        const targetId = document.getElementById('modalTargetSection').value;
        const targetName = document.getElementById('modalTargetSection').selectedOptions[0].text;
        
        // Remove from pending
        document.getElementById('enrollment_' + id).style.display = 'none';
        document.getElementById('enrollment_' + id).classList.add('processed-hidden');

        // Add to ready
        const readyHtml = `
            <div class="p-4 bg-white rounded-2xl border border-orange-100 shadow-sm animate-fadeIn flex flex-col space-y-2" id="ready_${id}">
                <input type="hidden" name="students[${id}][status]" value="${currentStatus}">
                <input type="hidden" name="students[${id}][target_section_id]" value="${targetId}">
                
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-[10px] font-black tracking-widest ${currentStatus === 'Aprobado' ? 'text-green-500' : 'text-red-500'} uppercase mb-1">${currentStatus}</div>
                        <div class="font-bold text-gray-800 text-sm tracking-tight">${name}</div>
                    </div>
                    <button type="button" onclick="revertStudent(${id})" class="text-gray-300 hover:text-orange-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </button>
                </div>
                
                <div class="pt-2 border-t border-gray-50 flex items-center text-[10px] font-bold text-gray-400">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    ${targetId ? targetName : 'Sin asignación (Egreso)'}
                </div>
            </div>
        `;
        document.getElementById('readyList').insertAdjacentHTML('beforeend', readyHtml);
        
        updateUI();
        closeConfig();
    }

    function revertStudent(id) {
        document.getElementById('ready_' + id).remove();
        const item = document.getElementById('enrollment_' + id);
        item.style.display = 'flex';
        item.classList.remove('processed-hidden');
        updateUI();
    }

    function updateUI() {
        const readyCountVal = document.getElementById('readyList').children.length;
        const total = {{ $section->enrollments->count() }};
        const pendingCountVal = total - readyCountVal;
        
        document.getElementById('readyCount').innerText = readyCountVal + " PROCESADOS";
        document.getElementById('pendingCount').innerText = pendingCountVal + " ALUMNOS";
        
        const emptyReady = document.getElementById('emptyReady');
        const submitBtn = document.getElementById('submitBtn');
        
        if (readyCountVal > 0) {
            emptyReady.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-[#032e5e]', 'text-white', 'hover:bg-[#032e5e]/90');
        } else {
            emptyReady.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-[#032e5e]', 'text-white', 'hover:bg-[#032e5e]/90');
        }
    }

    // Search
    document.getElementById('studentSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.student-item');
        items.forEach(item => {
            if (item.classList.contains('processed-hidden')) return;
            const searchData = item.getAttribute('data-search');
            item.style.display = searchData.includes(term) ? 'flex' : 'none';
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn { animation: fadeIn 0.3s ease-out forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
</style>
@endsection
