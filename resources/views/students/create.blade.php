@extends('dashboard')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('students.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#f83a3a]/10 hover:bg-[#f83a3a]/20 transition-colors group" title="Cancelar y volver">
                    <svg fill="#f83a3a" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" stroke="#f83a3a" class="group-hover:-translate-x-1 transition-transform">
                        <g><path d="M317.959,115.859H210.158V58.365h-44.864L0,223.66l165.294,165.294h44.864V331.46h136.548 c67.367,0,122.174,54.807,122.174,122.174H512V309.9C512,202.905,424.953,115.859,317.959,115.859z M468.88,342.412 c-30.253-33.206-73.82-54.071-122.174-54.071H167.038v41.378L60.981,223.661l106.057-106.057v41.375h150.921 c83.219,0,150.921,67.703,150.921,150.921V342.412z"></path></g>
                    </svg>
                </a>
                <h3 class="text-2xl font-extrabold text-[#032e5e]">Nueva Inscripción</h3>
            </div>
            <p class="text-gray-500 font-semibold text-sm mt-1">Completa la ficha paso a paso.</p>
        </div>
        <label class="flex items-center gap-3 bg-white border-2 border-gray-100 px-4 py-3 rounded-2xl cursor-pointer hover:border-amber-300 transition-all flex-shrink-0">
            <input type="checkbox" id="historic_toggle" class="rounded text-amber-500 focus:ring-amber-400 w-4 h-4">
            <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Egresado Histórico</span>
        </label>
    </div>

    {{-- Historic Banner --}}
    <div id="historic-banner" class="hidden mb-6 p-5 bg-amber-50 border-2 border-amber-200 rounded-2xl flex items-center gap-4">
        <svg class="w-6 h-6 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm font-bold text-amber-800">Modo Egresado Histórico: la restricción de edad y la asignación de aula son opcionales.</p>
    </div>

    {{-- Step Progress Bar --}}
    <div class="bg-white rounded-[2rem] p-5 shadow-sm border border-gray-100 mb-8">
        <div class="flex items-center justify-between gap-2">
            @php $steps = ['Identidad','Salud','Ubicación','Académico','Entorno','Asignación']; @endphp
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

    <form action="{{ route('students.store') }}" method="POST" id="student-form" class="space-y-8">
        @csrf
        <input type="hidden" name="is_historical_graduate" id="is_historical_graduate" value="0">

        {{-- Steps 1-5 from partial --}}
        @include('students.partials.form_sections')

        {{-- STEP 6: Asignación --}}
        <div class="wizard-step hidden bg-white p-8 rounded-[2.5rem] shadow-sm border border-[#032e5e]/20" data-step="6">
            <h4 class="text-[#032e5e] font-bold text-xs uppercase tracking-[0.2em] mb-6 flex items-center">
                <span class="w-8 h-8 rounded-full bg-[#032e5e]/10 flex items-center justify-center mr-3">6</span>
                Asignación de Aula y Representante
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1">Aula de Destino <span class="text-red-500 section-req">*</span></label>
                    <select name="section_id" id="section_id" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none font-bold text-[#032e5e] appearance-none">
                        <option value="" disabled selected>Seleccione el aula...</option>
                        @foreach($sections as $s)
                            <option value="{{ $s->id }}" {{ old('section_id')==$s->id?'selected':'' }}>{{ $s->grade->name }} – "{{ $s->name }}" ({{ $s->shift }})</option>
                        @endforeach
                    </select>
                    @error('section_id')<p class="text-[10px] font-bold text-red-500 mt-2 ml-1">{{ $message }}</p>@enderror
                </div>
                @include('students.partials.rep_selector')
            </div>
        </div>

        {{-- Wizard Navigation --}}
        <div class="flex items-center justify-between mt-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('students.index') }}" class="px-8 py-4 rounded-2xl font-bold bg-[#f83a3a] text-white hover:bg-[#f83a3a]/90 transition-all shadow-lg shadow-[#f83a3a]/20 flex items-center gap-2">
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
                    ✓ Guardar Inscripción
                </button>
            </div>
        </div>
    </form>
</div>

<script>
const TOTAL_STEPS = 6;
let currentStep = 1;
let maxReachedStep = 1;
const isEditMode = false;

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
        
        // A step is considered "done" if it's strictly before the max reached step in creation,
        // or just strictly before the current step visually.
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
        if (!el.classList.contains('section-req')) { // Don't remove from the * span
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

// Historic graduate toggle
document.getElementById('historic_toggle').addEventListener('change', function(){
    const on = this.checked;
    document.getElementById('is_historical_graduate').value = on ? '1' : '0';
    document.getElementById('historic-banner').classList.toggle('hidden', !on);
    const birthField = document.getElementById('birth_date_field');
    if(on){ birthField?.removeAttribute('max'); birthField?.removeAttribute('min'); document.getElementById('section_id')?.removeAttribute('required'); document.querySelectorAll('.section-req').forEach(e=>e.classList.add('hidden'));
    } else { birthField?.setAttribute('max','{{ now()->subYears(3)->format("Y-m-d") }}'); birthField?.setAttribute('min','{{ now()->subYears(18)->addDay()->format("Y-m-d") }}'); document.getElementById('section_id')?.setAttribute('required',''); document.querySelectorAll('.section-req').forEach(e=>e.classList.remove('hidden')); }
});

wizardGo(1);
</script>
@endsection
