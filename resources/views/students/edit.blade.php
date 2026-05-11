@extends('dashboard')

@section('content')
<div class="max-w-full space-y-6">
    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('students.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#D32F2F]/10 hover:bg-[#D32F2F]/20 transition-colors group" title="Cancelar y volver">
                <svg fill="#D32F2F" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" stroke="#D32F2F" class="group-hover:-translate-x-1 transition-transform">
                    <g><path d="M317.959,115.859H210.158V58.365h-44.864L0,223.66l165.294,165.294h44.864V331.46h136.548 c67.367,0,122.174,54.807,122.174,122.174H512V309.9C512,202.905,424.953,115.859,317.959,115.859z M468.88,342.412 c-30.253-33.206-73.82-54.071-122.174-54.071H167.038v41.378L60.981,223.661l106.057-106.057v41.375h150.921 c83.219,0,150.921,67.703,150.921,150.921V342.412z"></path></g>
                </svg>
            </a>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Editar Estudiante</h3>
        </div>
        <p class="text-gray-500 font-semibold text-sm mt-1">Actualiza los datos de la ficha de inscripción de {{ $student->first_name }}.</p>
    </div>

    {{-- Step Progress Bar --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-8">
        <div class="flex items-center justify-between gap-2">
            @php 
                $steps = ['Identidad','Salud','Ubicación','Académico','Entorno'];
                if(auth()->user()->isAdmin()) {
                    $steps[] = 'Asignación';
                }
            @endphp
            @foreach($steps as $i => $label)
            <div class="flex flex-col items-center flex-1 cursor-pointer group" onclick="handleStepClick({{ $i+1 }})">
                <div id="step-dot-{{ $i+1 }}"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all group-hover:scale-110
                    {{ $i===0 ? 'bg-[#1A237E] text-white shadow-sm' : 'bg-gray-100 text-gray-400' }}">
                    {{ $i+1 }}
                </div>
                <span id="step-label-{{ $i+1 }}" class="text-[10px] font-bold mt-1 transition-all group-hover:text-[#1A237E] {{ $i===0 ? 'text-[#1A237E]' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @if(!$loop->last)
            <div class="h-0.5 flex-1 bg-gray-100 rounded-full -mt-4" id="step-line-{{ $i+1 }}"></div>
            @endif
            @endforeach
        </div>
    </div>

    <form action="{{ route('students.update', $student->id) }}" method="POST" id="student-form" class="space-y-8">
        @csrf
        @method('PUT')

        @include('students.partials.form_sections', ['student' => $student])

        @if(auth()->user()->isAdmin())
        {{-- STEP 6: Asignación --}}
        <div class="wizard-step hidden bg-white p-8 rounded-xl shadow-sm border border-[#1A237E]/20" data-step="6">
            <h4 class="text-[#1A237E] font-bold text-xs uppercase tracking-[0.2em] mb-6 flex items-center">
                <span class="w-8 h-8 rounded-full bg-[#1A237E]/10 flex items-center justify-center mr-3">6</span>
                Asignación de Aula y Representante
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1">Asignar Aula (Opcional)</label>
                    <select name="section_id" id="section_id" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] appearance-none">
                        <option value="">Sin aula asignada (Pendiente)</option>
                        @foreach($sections->groupBy('grade.name') as $gradeName => $gradeSections)
                            <optgroup label="{{ $gradeName }}">
                                @foreach($gradeSections as $s)
                                    <option value="{{ $s->id }}" {{ $currentSectionId == $s->id ? 'selected' : '' }}>
                                        {{ $gradeName }} - "{{ $s->name }}" ({{ $s->shift }})
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('section_id')<p class="text-[10px] font-bold text-red-500 mt-2 ml-1">{{ $message }}</p>@enderror
                </div>
                @include('students.partials.rep_selector', ['student' => $student])
            </div>
            <p class="text-[10px] text-gray-400 font-bold mt-4 ml-1 italic">
                * Para que el representante pueda ver las notas, el alumno debe estar inscrito en un aula.
            </p>
        </div>
        @endif

        {{-- Wizard Navigation --}}
        <div class="flex items-center justify-between mt-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('students.index') }}" class="px-8 py-4 rounded-2xl font-bold bg-[#D32F2F] text-white hover:bg-[#D32F2F]/90 transition-all shadow-lg shadow-[#D32F2F]/20 flex items-center gap-2">
                    <svg fill="currentColor" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
                        <g><path d="M317.959,115.859H210.158V58.365h-44.864L0,223.66l165.294,165.294h44.864V331.46h136.548 c67.367,0,122.174,54.807,122.174,122.174H512V309.9C512,202.905,424.953,115.859,317.959,115.859z M468.88,342.412 c-30.253-33.206-73.82-54.071-122.174-54.071H167.038v41.378L60.981,223.661l106.057-106.057v41.375h150.921 c83.219,0,150.921,67.703,150.921,150.921V342.412z"></path></g>
                    </svg>
                    Cancelar
                </a>
                <button type="button" id="btn-prev" onclick="wizardPrev()" class="hidden px-8 py-4 rounded-2xl font-bold bg-white text-gray-500 hover:bg-gray-50 border border-gray-200 transition-all">
                    ← Anterior
                </button>
            </div>
            
            <div class="flex items-center gap-4">
                <button type="button" id="btn-next" onclick="wizardNext()" class="px-8 py-4 rounded-2xl font-bold bg-[#1A237E] text-white hover:bg-[#1A237E]/90 transition-all shadow-sm">
                    Siguiente →
                </button>
                <button type="submit" id="btn-submit" class="hidden px-8 py-4 rounded-2xl font-bold bg-[#FBC02D] text-[#1A237E] hover:bg-[#FBC02D]/90 transition-all shadow-lg shadow-sm">
                    ✓ Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>

<script>
const TOTAL_STEPS = {{ auth()->user()->isAdmin() ? 6 : 5 }};
let currentStep = 1;
let maxReachedStep = 1;
const isEditMode = true;

function getSteps() { return document.querySelectorAll('.wizard-step'); }

function handleStepClick(targetStep) {
    if(targetStep === currentStep) return;
    
    if(isEditMode) {
        // Free navigation in edit mode
        wizardGo(targetStep);
    } else {
        // In creation mode, can only jump to steps already verified/reached
        if(targetStep <= maxReachedStep) {
            wizardGo(targetStep);
        } else {
            if (typeof showToast === 'function') {
                showToast('Debes completar y validar el paso actual usando el botón "Siguiente" antes de avanzar libremente.', 'warning');
            } else {
                alert('Debes completar y validar el paso actual usando el botón "Siguiente" antes de avanzar libremente.');
            }
        }
    }
}

function wizardGo(n) {
    if(n < 1 || n > TOTAL_STEPS) return;
    getSteps().forEach(s => {
        const sn = parseInt(s.dataset.step);
        s.classList.toggle('hidden', sn !== n);
    });
    // Update dots
    for(let i=1; i<=TOTAL_STEPS; i++){
        const dot   = document.getElementById(`step-dot-${i}`);
        const label = document.getElementById(`step-label-${i}`);
        if(!dot) continue;
        const done    = isEditMode ? (i < n) : (i < maxReachedStep);
        const active  = i === n;
        dot.className = `w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all group-hover:scale-110 ${active ? 'bg-[#1A237E] text-white shadow-sm' : done ? 'bg-[#FBC02D] text-[#1A237E]' : 'bg-gray-100 text-gray-400'}`;
        dot.textContent = done && !active ? '✓' : i;
        if(label) label.className = `text-[10px] font-bold mt-1 transition-all group-hover:text-[#1A237E] ${active ? 'text-[#1A237E]' : done ? 'text-[#FBC02D]' : 'text-gray-400'}`;
    }
    document.getElementById('btn-prev').classList.toggle('hidden', n === 1);
    document.getElementById('btn-next').classList.toggle('hidden', n === TOTAL_STEPS);
    document.getElementById('btn-submit').classList.toggle('hidden', n !== TOTAL_STEPS);
    currentStep = n;
    window.scrollTo({top:0, behavior:'smooth'});
}

function validateCurrentStep() {
    const currentPanel = document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
    if (!currentPanel) return true;

    // Find all required fields in the current step
    const requiredFields = currentPanel.querySelectorAll('input[required], select[required]');
    let isValid = true;
    let firstInvalidField = null;

    // Clear previous custom error styles
    currentPanel.querySelectorAll('.border-red-500').forEach(el => {
        if (!el.classList.contains('section-req')) {
             el.classList.remove('border-red-500');
             el.classList.add('border-transparent');
        }
    });

    requiredFields.forEach(field => {
        // For select elements, empty string is invalid
        if (!field.value.trim()) {
            isValid = false;
            field.classList.remove('border-transparent');
            field.classList.add('border-red-500');
            if (!firstInvalidField) firstInvalidField = field;
        }
    });

    if (!isValid) {
        if (typeof showToast === 'function') {
            showToast('Por favor, completa todos los campos obligatorios marcados en rojo antes de continuar.', 'error');
        } else {
            alert('Por favor, completa todos los campos obligatorios marcados en rojo antes de continuar.');
        }
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
    }

    return isValid;
}

function wizardNext() { 
    if (validateCurrentStep()) {
        const nextStep = currentStep + 1;
        if (!isEditMode && nextStep > maxReachedStep) {
            maxReachedStep = nextStep;
        }
        wizardGo(nextStep); 
    }
}
function wizardPrev() { wizardGo(currentStep - 1); }

wizardGo(1);
</script>
@endsection
