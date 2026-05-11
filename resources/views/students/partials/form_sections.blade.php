{{-- STEP 1: Identificación --}}
<div class="wizard-step bg-white p-6 rounded-xl shadow-sm border border-gray-200" data-step="1">
    <h4 class="text-[#FBC02D] font-bold text-xs uppercase tracking-[0.2em] mb-6 flex items-center">
        <span class="w-8 h-8 rounded-full bg-[#FBC02D]/10 flex items-center justify-center mr-3">1</span>
        Identificación del Estudiante
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Primer Nombre <span class="text-red-500">*</span></label>
            <input type="text" name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}" required class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
            @error('first_name')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Segundo Nombre</label>
            <input type="text" name="second_name" value="{{ old('second_name', $student->second_name ?? '') }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Primer Apellido <span class="text-red-500">*</span></label>
            <input type="text" name="last_name" value="{{ old('last_name', $student->last_name ?? '') }}" required class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
            @error('last_name')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Segundo Apellido</label>
            <input type="text" name="second_last_name" value="{{ old('second_last_name', $student->second_last_name ?? '') }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Cédula (Opcional)</label>
            <input type="text" id="cedula_input" name="cedula" value="{{ old('cedula', $student->cedula ?? '') }}" placeholder="Ej: 12.345.678" maxlength="10" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Fecha de Nacimiento <span class="text-red-500">*</span></label>
            <input type="date" id="birth_date_field" name="birth_date" value="{{ old('birth_date', isset($student->birth_date) ? \Carbon\Carbon::parse($student->birth_date)->format('Y-m-d') : '') }}" max="{{ now()->subYears(3)->format('Y-m-d') }}" min="{{ now()->subYears(18)->addDay()->format('Y-m-d') }}" required class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
            @error('birth_date')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Estado de Nacimiento <span class="text-red-500">*</span></label>
            <select id="select_birth_estado" required class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold appearance-none">
                <option value="">Cargando estados...</option>
            </select>
            <input type="hidden" name="birth_place_state" id="birth_place_state" value="{{ old('birth_place_state', $student->birth_place_state ?? '') }}">
            @error('birth_place_state')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Localidad de Nacimiento <span class="text-red-500">*</span></label>
            <select id="select_birth_municipio" required disabled class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold appearance-none disabled:opacity-50">
                <option value="">Seleccione un estado primero...</option>
            </select>
            <input type="hidden" name="birth_place_locality" id="birth_place_locality" value="{{ old('birth_place_locality', $student->birth_place_locality ?? '') }}">
            @error('birth_place_locality')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Sexo <span class="text-red-500">*</span></label>
            <select name="gender" required class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold appearance-none">
                <option value="M" {{ old('gender',$student->gender??'')==='M'?'selected':'' }}>Masculino</option>
                <option value="F" {{ old('gender',$student->gender??'')==='F'?'selected':'' }}>Femenino</option>
            </select>
        </div>
    </div>
</div>

{{-- STEP 2: Salud y Tallas --}}
<div class="wizard-step hidden bg-white p-6 rounded-xl shadow-sm border border-gray-200" data-step="2">
    <h4 class="text-[#FBC02D] font-bold text-xs uppercase tracking-[0.2em] mb-6 flex items-center">
        <span class="w-8 h-8 rounded-full bg-[#FBC02D]/10 flex items-center justify-center mr-3">2</span>
        Antropometría y Salud
    </h4>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        @foreach(['shirt_size'=>'Camisa','pants_size'=>'Pantalón','shoes_size'=>'Calzado'] as $field=>$lbl)
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">{{ $lbl }}</label>
            @if($field==='shoes_size')
            <select name="{{ $field }}" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold appearance-none">
                <option value="">—</option>
                @foreach(range(18,42) as $s)<option value="{{ $s }}" {{ old($field,$student->$field??'')==$s?'selected':'' }}>{{ $s }}</option>@endforeach
            </select>
            @else
            <select name="{{ $field }}" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold appearance-none">
                <option value="">—</option>
                @foreach(['2','4','6','8','10','12','14','XS','S','M','L','XL','XXL'] as $s)<option value="{{ $s }}" {{ old($field,$student->$field??'')===$s?'selected':'' }}>{{ $s }}</option>@endforeach
            </select>
            @endif
        </div>
        @endforeach
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Peso (kg)</label>
            <input type="number" step="0.1" name="weight" min="5" max="200" value="{{ old('weight',$student->weight??'') }}" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
            @error('weight')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Altura (m)</label>
            <input type="number" step="0.01" name="height" min="0.5" max="2.5" value="{{ old('height',$student->height??'') }}" class="w-full px-4 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
            @error('height')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Allergies: Custom dropdown --}}
    <div class="space-y-3">
        <label class="text-xs font-bold text-gray-400 uppercase ml-1">Alergias Conocidas</label>
        <div class="relative" id="allergy-dropdown-wrapper">
            <button type="button" id="allergy-btn" onclick="toggleAllergyDropdown()"
                class="w-full flex items-center justify-between px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent hover:border-red-200 transition-all font-semibold text-gray-600 text-left">
                <span id="allergy-label">Seleccionar alergias...</span>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div id="allergy-panel" class="hidden absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="max-h-72 overflow-y-auto p-3 space-y-1">
                    @php $allergyCategories = ($allergies ?? collect())->groupBy('category'); @endphp
                    @foreach($allergyCategories as $cat => $items)
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2 pt-2 pb-1">{{ $cat }}</p>
                        @foreach($items as $a)
                        @php $chk = in_array($a->id, old('allergies', $studentAllergies ?? [])); @endphp
                        <label class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-red-50 cursor-pointer transition-colors allergy-item">
                            <input type="checkbox" name="allergies[]" value="{{ $a->id }}" {{ $chk?'checked':'' }} class="rounded text-red-500 focus:ring-red-300 allergy-check" onchange="updateAllergyLabel()">
                            <span class="text-sm font-semibold text-gray-700">{{ $a->name }}</span>
                        </label>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Otra alergia no listada</label>
            <input type="text" name="other_allergies" value="{{ old('other_allergies',$student->other_allergies??'') }}" placeholder="Describe otras alergias o condiciones de salud..." class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-red-200 outline-none font-semibold">
        </div>
    </div>
</div>

{{-- STEP 3: Ubicación --}}
<div class="wizard-step hidden bg-white p-6 rounded-xl shadow-sm border border-gray-200" data-step="3">
    <h4 class="text-[#FBC02D] font-bold text-xs uppercase tracking-[0.2em] mb-6 flex items-center">
        <span class="w-8 h-8 rounded-full bg-[#FBC02D]/10 flex items-center justify-center mr-3">3</span>
        Ubicación de Residencia
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Estado <span class="text-red-500">*</span></label>
            <select id="select_estado" name="estado_id" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold appearance-none">
                <option value="">Cargando estados...</option>
            </select>
            <input type="hidden" name="estado_name" id="estado_name" value="{{ old('estado_name',$student->address->state??'') }}">
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Municipio <span class="text-red-500">*</span></label>
            <select id="select_municipio" name="municipio_id" disabled class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold appearance-none disabled:opacity-50">
                <option value="">Seleccione un estado primero...</option>
            </select>
            <input type="hidden" name="municipality" id="municipality_name" value="{{ old('municipality',$student->address->municipality??'') }}">
            @error('municipality')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Parroquia <span class="text-red-500">*</span></label>
            <select id="select_parroquia" name="parroquia_id" disabled class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold appearance-none disabled:opacity-50">
                <option value="">Seleccione un municipio primero...</option>
            </select>
            <input type="hidden" name="parish" id="parish_name" value="{{ old('parish',$student->address->parish??'') }}">
            @error('parish')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Sector / Calle <span class="text-red-500">*</span></label>
            <input type="text" name="sector" value="{{ old('sector',$student->address->sector??'') }}" required placeholder="Ej: Sector Las Flores, Calle 4" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
            @error('sector')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="space-y-2 md:col-span-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Nro Casa / Apto <span class="text-red-500">*</span></label>
            <input type="text" name="house_apt_number" value="{{ old('house_apt_number',$student->address->house_apt_number??'') }}" required placeholder="Ej: Casa 45 o Apto 2B" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
            @error('house_apt_number')<p class="text-[10px] font-bold text-red-500 ml-1 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- STEP 4: Antecedentes --}}
<div class="wizard-step hidden bg-white p-6 rounded-xl shadow-sm border border-gray-200" data-step="4">
    <h4 class="text-[#FBC02D] font-bold text-xs uppercase tracking-[0.2em] mb-6 flex items-center">
        <span class="w-8 h-8 rounded-full bg-[#FBC02D]/10 flex items-center justify-center mr-3">4</span>
        Antecedentes Académicos
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Plantel de Procedencia</label>
            <input type="text" name="previous_school_name" value="{{ old('previous_school_name',$student->academicBackground->previous_school_name??'') }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Código DEA</label>
            <input type="text" name="previous_school_dea_code" value="{{ old('previous_school_dea_code',$student->academicBackground->previous_school_dea_code??'') }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
        </div>
    </div>
</div>

{{-- STEP 5: Entorno y Actividades --}}
<div class="wizard-step hidden bg-white p-6 rounded-xl shadow-sm border border-gray-200" data-step="5">
    <h4 class="text-[#FBC02D] font-bold text-xs uppercase tracking-[0.2em] mb-6 flex items-center">
        <span class="w-8 h-8 rounded-full bg-[#FBC02D]/10 flex items-center justify-center mr-3">5</span>
        Entorno Familiar y Actividades Recreativas
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">¿Con quién vive el niño?</label>
            <input type="text" name="lives_with" value="{{ old('lives_with',$student->lives_with??'') }}" placeholder="Ej: Ambos padres, Abuelos..." class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
        </div>
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Cantidad de Hermanos</label>
            <input type="number" name="siblings_count" min="0" max="30" value="{{ old('siblings_count',$student->siblings_count??0) }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/30 outline-none font-semibold">
        </div>
    </div>

    {{-- Activities: Category tabs --}}
    <div class="space-y-4">
        <label class="text-xs font-bold text-gray-400 uppercase ml-1">Intereses y Actividades Recreativas</label>
        @php $activityCategories = ($activities ?? collect())->groupBy('category'); @endphp
        {{-- Category tabs --}}
        <div class="flex flex-wrap gap-2" id="activity-tabs">
            @foreach($activityCategories->keys() as $cat)
            <button type="button" onclick="switchActivityTab('{{ $cat }}')"
                class="activity-tab px-5 py-2 rounded-xl text-xs font-bold border-2 transition-all
                {{ $loop->first ? 'bg-[#1A237E] text-white border-[#1A237E]' : 'bg-white text-gray-500 border-gray-200 hover:border-[#1A237E]/40' }}"
                data-cat="{{ $cat }}">
                {{ $cat }}
            </button>
            @endforeach
        </div>
        {{-- Activities per category --}}
        @foreach($activityCategories as $cat => $items)
        <div class="activity-panel {{ !$loop->first ? 'hidden' : '' }} grid grid-cols-2 md:grid-cols-3 gap-3" data-cat="{{ $cat }}">
            @foreach($items as $act)
            @php $chk = in_array($act->id, old('activities', $studentActivities ?? [])); @endphp
            <label class="flex items-center gap-2 bg-gray-50 px-4 py-2.5 rounded-xl cursor-pointer hover:bg-blue-50 transition-colors {{ $chk ? 'bg-blue-50 ring-2 ring-blue-200' : '' }}">
                <input type="checkbox" name="activities[]" value="{{ $act->id }}" {{ $chk?'checked':'' }} class="rounded text-blue-500 focus:ring-blue-300">
                <span class="text-sm font-semibold text-gray-700">{{ $act->name }}</span>
            </label>
            @endforeach
        </div>
        @endforeach
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Otra actividad no listada</label>
            <input type="text" name="other_activities" value="{{ old('other_activities',$student->other_activities??'') }}" placeholder="Describe otras actividades..." class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-blue-200 outline-none font-semibold">
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
// --- Activity category tabs ---
function switchActivityTab(cat) {
    document.querySelectorAll('.activity-tab').forEach(btn => {
        const active = btn.dataset.cat === cat;
        btn.classList.toggle('bg-[#1A237E]', active);
        btn.classList.toggle('text-white', active);
        btn.classList.toggle('border-[#1A237E]', active);
        btn.classList.toggle('bg-white', !active);
        btn.classList.toggle('text-gray-500', !active);
        btn.classList.toggle('border-gray-200', !active);
    });
    document.querySelectorAll('.activity-panel').forEach(p => p.classList.toggle('hidden', p.dataset.cat !== cat));
}

// --- Allergy dropdown ---
function toggleAllergyDropdown() {
    document.getElementById('allergy-panel').classList.toggle('hidden');
}
function updateAllergyLabel() {
    const checked = document.querySelectorAll('.allergy-check:checked');
    const btn = document.getElementById('allergy-label');
    btn.textContent = checked.length ? `${checked.length} alergia(s) seleccionada(s)` : 'Seleccionar alergias...';
}
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('allergy-dropdown-wrapper');
    if (wrapper && !wrapper.contains(e.target)) document.getElementById('allergy-panel')?.classList.add('hidden');
});
updateAllergyLabel();

// --- AJAX Location ---
(function(){
    const selE = document.getElementById('select_estado');
    const selM = document.getElementById('select_municipio');
    const selP = document.getElementById('select_parroquia');
    const hidE = document.getElementById('estado_name');
    const hidM = document.getElementById('municipality_name');
    const hidP = document.getElementById('parish_name');
    const savedE = "{{ old('estado_id',$student->address->estado_id??'') }}";
    const savedM = "{{ old('municipio_id',$student->address->municipio_id??'') }}";
    const savedP = "{{ old('parroquia_id',$student->address->parroquia_id??'') }}";

    function fill(sel, data, savedId, placeholder) {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
        data.forEach(i => { const o = new Option(i.name, i.id); if(String(i.id)===String(savedId)) o.selected=true; sel.add(o); });
        sel.disabled = false;
    }

    function fillByName(sel, data, savedName, placeholder) {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
        let selectedId = null;
        data.forEach(i => { 
            const o = new Option(i.name, i.id); 
            if(i.name === savedName) { o.selected=true; selectedId=i.id; }
            sel.add(o); 
        });
        sel.disabled = false;
        return selectedId;
    }

    function normalizeStr(str) {
        if (!str) return '';
        return String(str).normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().trim();
    }

    function fillFallback(sel, data, savedId, savedName, placeholder) {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
        let selectedId = null;
        const normSavedName = normalizeStr(savedName);
        data.forEach(i => {
            const o = new Option(i.name, i.id);
            if (String(i.id) === String(savedId) || (!savedId && normSavedName && normalizeStr(i.name) === normSavedName)) {
                o.selected = true;
                selectedId = i.id;
            }
            sel.add(o);
        });
        sel.disabled = false;
        return selectedId;
    }

    const savedEName = hidE.value;
    const savedMName = hidM.value;
    const savedPName = hidP.value;

    // Step 3: Address Locations
    fetch("{{ route('api.locations.states') }}").then(r=>r.json()).then(d=>{
        const sid = fillFallback(selE, d, savedE, savedEName, 'Seleccione un estado...');
        if(sid) {
            selE.value = sid; // ensure value is set if matched by name
            selE.dispatchEvent(new Event('change'));
        }
    });
    selE.addEventListener('change', function(){
        hidE.value = this.options[this.selectedIndex]?.text || '';
        selM.disabled=true; selP.disabled=true;
        selM.innerHTML='<option value="">Cargando...</option>'; selP.innerHTML='<option value="">Seleccione un municipio...</option>';
        if(!this.value) return;
        fetch(`{{ url('/api/locations/states') }}/${this.value}/municipalities`).then(r=>r.json()).then(d=>{
            const mid = fillFallback(selM, d, savedM, savedMName, 'Seleccione un municipio...');
            if(mid) {
                selM.value = mid;
                selM.dispatchEvent(new Event('change'));
            }
        });
    });
    selM.addEventListener('change', function(){
        hidM.value = this.options[this.selectedIndex]?.text || '';
        selP.disabled=true; selP.innerHTML='<option value="">Cargando...</option>';
        if(!this.value) return;
        fetch(`{{ url('/api/locations/municipalities') }}/${this.value}/parishes`).then(r=>r.json()).then(d=>{
            const pid = fillFallback(selP, d, savedP, savedPName, 'Seleccione una parroquia...');
            if(pid) selP.value = pid;
        });
    });
    selP.addEventListener('change', function(){ hidP.value = this.options[this.selectedIndex]?.text || ''; });

    // Step 1: Birth Locations
    const selBirthE = document.getElementById('select_birth_estado');
    const selBirthM = document.getElementById('select_birth_municipio');
    const hidBirthE = document.getElementById('birth_place_state');
    const hidBirthM = document.getElementById('birth_place_locality');
    const savedBirthEName = hidBirthE.value;
    const savedBirthMName = hidBirthM.value;

    fetch("{{ route('api.locations.states') }}").then(r=>r.json()).then(d=>{
        const sid = fillByName(selBirthE, d, savedBirthEName, 'Seleccione estado de nacimiento...');
        if(sid) selBirthE.dispatchEvent(new Event('change'));
    });
    selBirthE.addEventListener('change', function(){
        hidBirthE.value = this.options[this.selectedIndex]?.text || '';
        selBirthM.disabled=true;
        selBirthM.innerHTML='<option value="">Cargando...</option>';
        if(!this.value) return;
        fetch(`{{ url('/api/locations/states') }}/${this.value}/municipalities`).then(r=>r.json()).then(d=>{
            fillByName(selBirthM, d, savedBirthMName, 'Seleccione municipio de nacimiento...');
        });
    });
    selBirthM.addEventListener('change', function(){ hidBirthM.value = this.options[this.selectedIndex]?.text || ''; });

    // Cedula autoformat
    const cInput = document.getElementById('cedula_input');
    if(cInput) cInput.addEventListener('input', function(){
        let d = this.value.replace(/\D/g,'').slice(0,8);
        this.value = d.replace(/\B(?=(\d{3})+(?!\d))/g,'.');
    });
})();
</script>
