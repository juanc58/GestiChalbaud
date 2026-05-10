@extends('dashboard')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('representative.students.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#f83a3a]/10 hover:bg-[#f83a3a]/20 transition-colors group" title="Cancelar y volver">
                <svg fill="#f83a3a" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" stroke="#f83a3a" class="group-hover:-translate-x-1 transition-transform">
                    <g><path d="M317.959,115.859H210.158V58.365h-44.864L0,223.66l165.294,165.294h44.864V331.46h136.548 c67.367,0,122.174,54.807,122.174,122.174H512V309.9C512,202.905,424.953,115.859,317.959,115.859z M468.88,342.412 c-30.253-33.206-73.82-54.071-122.174-54.071H167.038v41.378L60.981,223.661l106.057-106.057v41.375h150.921 c83.219,0,150.921,67.703,150.921,150.921V342.412z"></path></g>
                </svg>
            </a>
            <h3 class="text-2xl font-extrabold text-[#032e5e]">Editar Datos del Estudiante</h3>
        </div>
        <p class="text-gray-500 font-semibold text-sm mt-1">Actualiza la información de tu hijo(a) paso a paso.</p>
    </div>

    {{-- Step Progress Bar --}}
    <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-gray-100 mb-8">
        <div class="flex items-center justify-between gap-2">
            @php $steps = ['Identidad','Salud','Ubicación','Académico','Entorno']; @endphp
            @foreach($steps as $i => $label)
            <div class="flex flex-col items-center flex-1 cursor-pointer group" onclick="handleStepClick({{ $i+1 }})">
                <div id="step-dot-{{ $i+1 }}"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all group-hover:scale-110
                    {{ $i===0 ? 'bg-[#032e5e] text-white shadow-lg shadow-[#032e5e]/30' : 'bg-gray-100 text-gray-400' }}">
                    {{ $i+1 }}
                </div>
                <span id="step-label-{{ $i+1 }}" class="text-[10px] font-bold mt-1 transition-all group-hover:text-[#032e5e] {{ $i===0 ? 'text-[#032e5e]' : 'text-gray-400' }}">{{ $label }}</span>
            </div>
            @if(!$loop->last)
            <div class="h-0.5 flex-1 bg-gray-100 rounded-full -mt-4" id="step-line-{{ $i+1 }}"></div>
            @endif
            @endforeach
        </div>
    </div>

    <form action="{{ route('representative.students.update', $student->id) }}" method="POST" id="student-form" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Steps 1-5 from partial --}}
        @include('students.partials.form_sections')

        {{-- Wizard Navigation --}}
        <div class="flex items-center justify-between mt-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('representative.students.index') }}" class="px-8 py-4 rounded-2xl font-bold bg-[#f83a3a] text-white hover:bg-[#f83a3a]/90 transition-all shadow-lg shadow-[#f83a3a]/20 flex items-center gap-2">
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
                <button type="button" id="btn-next" onclick="wizardNext()" class="px-8 py-4 rounded-2xl font-bold bg-[#032e5e] text-white hover:bg-[#032e5e]/90 transition-all shadow-lg shadow-[#032e5e]/20">
                    Siguiente →
                </button>
                <button type="submit" id="btn-submit" class="hidden px-8 py-4 rounded-2xl font-bold bg-[#c56c39] text-white hover:bg-[#c56c39]/90 transition-all shadow-lg shadow-[#c56c39]/20">
                    ✓ Actualizar Datos
                </button>
            </div>
        </div>
    </form>
</div>

<script>
const TOTAL_STEPS = 5;
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
        dot.className = `w-8 h-8 rounded-full flex items-center justify-center text-xs font-black transition-all group-hover:scale-110 ${active ? 'bg-[#032e5e] text-white shadow-lg shadow-[#032e5e]/30' : done ? 'bg-[#c56c39] text-white' : 'bg-gray-100 text-gray-400'}`;
        dot.textContent = done && !active ? '✓' : i;
        if(label) label.className = `text-[10px] font-bold mt-1 transition-all group-hover:text-[#032e5e] ${active ? 'text-[#032e5e]' : done ? 'text-[#c56c39]' : 'text-gray-400'}`;
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
