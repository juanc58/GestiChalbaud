@extends('dashboard')

@section('content')
<div class="max-w-full space-y-6">

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('students.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#D32F2F]/10 hover:bg-[#D32F2F]/20 transition-colors group" title="Cancelar y volver">
                    <svg fill="#D32F2F" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" stroke="#D32F2F" class="group-hover:-translate-x-1 transition-transform">
                        <g><path d="M317.959,115.859H210.158V58.365h-44.864L0,223.66l165.294,165.294h44.864V331.46h136.548 c67.367,0,122.174,54.807,122.174,122.174H512V309.9C512,202.905,424.953,115.859,317.959,115.859z M468.88,342.412 c-30.253-33.206-73.82-54.071-122.174-54.071H167.038v41.378L60.981,223.661l106.057-106.057v41.375h150.921 c83.219,0,150.921,67.703,150.921,150.921V342.412z"></path></g>
                    </svg>
                </a>
                <h3 class="text-2xl font-extrabold text-[#1A237E]">Nueva Inscripción</h3>
            </div>
            <p class="text-gray-500 font-semibold text-sm mt-1">Completa la ficha del estudiante y los datos de contacto de su representante.</p>
        </div>
        <label class="flex items-center gap-3 bg-white border-2 border-gray-100 px-4 py-3 rounded-2xl cursor-pointer hover:border-amber-300 transition-all flex-shrink-0">
            <input type="checkbox" id="historic_toggle" class="rounded text-amber-500 focus:ring-amber-400 w-4 h-4" {{ (old('is_historical_graduate') == '1' || request('historical') == '1') ? 'checked' : '' }}>
            <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Egresado Histórico</span>
        </label>
    </div>

    @if($errors->any())
    <div class="p-5 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-xs font-bold space-y-1">
        <p class="uppercase tracking-wider text-red-800 font-extrabold">Por favor corrige los siguientes campos:</p>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Historic Banner --}}
    <div id="historic-banner" class="hidden mb-6 p-5 bg-amber-50 border-2 border-amber-200 rounded-2xl flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <svg class="w-6 h-6 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-bold text-amber-800">Modo Egresado Histórico activo: el estudiante quedará registrado directamente en el Módulo de Egresados. La restricción de edad y los datos del representante son opcionales; en el Paso 7 indicarás su Año Escolar de Promoción.</p>
        </div>
        <a href="{{ route('graduates.index') }}" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-extrabold uppercase tracking-wider hover:bg-amber-700 transition-all flex-shrink-0">
            Ver Egresados
        </a>
    </div>

    {{-- Step Progress Bar --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-8">
        <div class="flex items-center justify-between gap-2">
            @php $steps = ['Identidad','Salud','Ubicación','Académico','Entorno','Representante','Asignación']; @endphp
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

    <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" id="student-form" class="space-y-8" novalidate>
        @csrf
        <input type="hidden" name="is_historical_graduate" id="is_historical_graduate" value="{{ old('is_historical_graduate', '0') }}">

        {{-- Steps 1-5 from partial --}}
        @include('students.partials.form_sections')

        {{-- STEP 6: Datos del Representante --}}
        @include('students.partials.rep_form_fields')

        {{-- STEP 7: Asignación de Aula o Datos de Egreso Histórico --}}
        <div class="wizard-step hidden bg-white p-8 rounded-xl shadow-sm border border-[#1A237E]/20" data-step="7">
            <h4 class="text-[#1A237E] font-bold text-xs uppercase tracking-[0.2em] mb-6 flex items-center">
                <span class="w-8 h-8 rounded-full bg-[#1A237E]/10 flex items-center justify-center mr-3">7</span>
                <span id="step-7-title">Asignación de Aula</span>
            </h4>

            {{-- Bloque para inscripción regular --}}
            <div id="regular-section-box" class="max-w-xl space-y-2">
                <label class="text-xs font-bold text-gray-400 uppercase ml-1">Aula de Destino <span class="text-red-500 section-req">*</span></label>
                <select name="section_id" id="section_id" required class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] appearance-none">
                    <option value="">Seleccione el aula...</option>
                    @foreach($sections as $s)
                        <option value="{{ $s->id }}" {{ old('section_id')==$s->id?'selected':'' }}>{{ $s->grade->name }} – "{{ $s->name }}" ({{ $s->shift }})</option>
                    @endforeach
                </select>
                @error('section_id')<p class="text-[10px] font-bold text-red-500 mt-2 ml-1">{{ $message }}</p>@enderror
                <p class="text-[11px] text-gray-400 font-semibold pt-2">
                    Selecciona la sección correspondiente al año escolar vigente para completar la inscripción formal del estudiante.
                </p>
            </div>

            {{-- Bloque para Egresado Histórico --}}
            <div id="historic-graduation-box" class="hidden max-w-2xl space-y-6">
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs font-bold text-amber-900">
                    Indica el periodo escolar en que el alumno culminó su etapa académica en la institución para incorporarlo al Libro Histórico de Egresados.
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Año Escolar de Promoción / Egreso <span class="text-red-500">*</span></label>
                        <input type="text" name="promotion_year" id="promotion_year"
                               value="{{ old('promotion_year', date('Y') - 1 . '-' . date('Y')) }}"
                               placeholder="Ej: 2023-2024"
                               class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
                        @error('promotion_year')<p class="text-[10px] font-bold text-red-500 mt-1 ml-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Observaciones de Egreso (Opcional)</label>
                        <input type="text" name="graduate_notes" id="graduate_notes"
                               value="{{ old('graduate_notes') }}"
                               placeholder="Ej: Egresado de 6to Grado con honores"
                               class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-semibold text-gray-700">
                    </div>
                </div>
            </div>
        </div>

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
                    ✓ Guardar Inscripción
                </button>
            </div>
        </div>
    </form>
</div>

<script>
const TOTAL_STEPS = 7;
let currentStep = 1;
let maxReachedStep = {{ $errors->any() ? 7 : 1 }};
const isEditMode = false;

function getSteps() { return document.querySelectorAll('.wizard-step'); }

function handleStepClick(targetStep) {
    if(targetStep === currentStep) return;
    if(isEditMode || targetStep <= maxReachedStep) {
        wizardGo(targetStep);
    } else {
        if (typeof showToast === 'function') {
            showToast('Debes completar y validar el paso actual usando el botón "Siguiente" antes de avanzar libremente.', 'warning');
        } else {
            alert('Debes completar y validar el paso actual usando el botón "Siguiente" antes de avanzar libremente.');
        }
    }
}

function wizardGo(n) {
    if(n < 1 || n > TOTAL_STEPS) return;
    
    getSteps().forEach(s => {
        const sn = parseInt(s.dataset.step);
        s.classList.toggle('hidden', sn !== n);
    });
    
    for(let i=1; i<=TOTAL_STEPS; i++){
        const dot   = document.getElementById(`step-dot-${i}`);
        const label = document.getElementById(`step-label-${i}`);
        if(!dot) continue;
        
        const done   = isEditMode ? (i < n) : (i < maxReachedStep);
        const active = i === n;
        
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

    const requiredFields = currentPanel.querySelectorAll('input[required], select[required]');
    let isValid = true;
    let firstInvalidField = null;

    currentPanel.querySelectorAll('.border-red-500').forEach(el => {
        if (!el.classList.contains('section-req') && !el.classList.contains('rep-req')) {
             el.classList.remove('border-red-500');
             el.classList.add('border-transparent');
        }
    });

    requiredFields.forEach(field => {
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

document.getElementById('student-form').addEventListener('submit', function (e) {
    if (!validateCurrentStep()) {
        e.preventDefault();
    }
});

// Historic graduate toggle
function applyHistoricMode(on) {
    document.getElementById('is_historical_graduate').value = on ? '1' : '0';
    document.getElementById('historic-banner').classList.toggle('hidden', !on);
    document.getElementById('regular-section-box')?.classList.toggle('hidden', on);
    document.getElementById('historic-graduation-box')?.classList.toggle('hidden', !on);

    const step7Label = document.getElementById('step-label-7');
    const step7Title = document.getElementById('step-7-title');
    const promInput  = document.getElementById('promotion_year');
    const birthField = document.getElementById('birth_date_field');

    if (on) {
        if (step7Label) step7Label.textContent = 'Egreso';
        if (step7Title) step7Title.textContent = 'Datos de Promoción y Egreso Histórico';
        birthField?.removeAttribute('max');
        birthField?.removeAttribute('min');
        document.getElementById('section_id')?.removeAttribute('required');
        promInput?.setAttribute('required', '');
        document.querySelectorAll('.section-req, .rep-req').forEach(e => e.classList.add('hidden'));
        document.querySelectorAll('.rep-req-input').forEach(e => e.removeAttribute('required'));
    } else {
        if (step7Label) step7Label.textContent = 'Asignación';
        if (step7Title) step7Title.textContent = 'Asignación de Aula';
        birthField?.setAttribute('max', '{{ now()->subYears(3)->format("Y-m-d") }}');
        birthField?.setAttribute('min', '{{ now()->subYears(18)->addDay()->format("Y-m-d") }}');
        document.getElementById('section_id')?.setAttribute('required', '');
        promInput?.removeAttribute('required');
        document.querySelectorAll('.section-req, .rep-req').forEach(e => e.classList.remove('hidden'));
        document.querySelectorAll('.rep-req-input').forEach(e => e.setAttribute('required', ''));
    }
}

document.getElementById('historic_toggle').addEventListener('change', function () {
    applyHistoricMode(this.checked);
});

applyHistoricMode(document.getElementById('historic_toggle').checked);
wizardGo(1);
</script>
@endsection
