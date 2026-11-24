@php
    $rep = isset($student) ? ($student->representative ?? $student->enrollments->first()?->representative) : null;
@endphp

<div class="wizard-step hidden bg-white p-8 rounded-xl shadow-sm border border-[#1A237E]/20 space-y-6" data-step="6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 pb-4">
        <h4 class="text-[#1A237E] font-bold text-xs uppercase tracking-[0.2em] flex items-center">
            <span class="w-8 h-8 rounded-full bg-[#1A237E]/10 flex items-center justify-center mr-3">6</span>
            Datos del Representante Legal (Contacto del Estudiante)
        </h4>
        <span id="rep-autofill-badge" class="hidden px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-[10px] font-extrabold uppercase tracking-wider">
            ✓ Datos cargados de representante existente
        </span>
    </div>

    <p class="text-xs text-gray-500 font-semibold">
        Ingresa la información de contacto del padre, madre o representante legal vinculado a este estudiante. Si ya representa a otro hermano en la institución, ingresa su cédula para autocompletar sus datos.
    </p>

    {{-- Identificación y Parentesco --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="space-y-2 md:col-span-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Cédula del Representante <span class="text-red-500 rep-req">*</span></label>
            <div class="flex gap-2">
                <input type="text" name="rep_cedula" id="rep_cedula" inputmode="numeric"
                    value="{{ old('rep_cedula', $rep?->cedula) }}"
                    placeholder="Ej: 15432890" required
                    oninput="this.value = this.value.replace(/\D/g, '')"
                    class="rep-req-input flex-1 px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] transition-all">
                <button type="button" onclick="lookupRepresentativeByCedula()"
                    class="px-5 py-4 rounded-2xl bg-[#1A237E]/10 text-[#1A237E] hover:bg-[#1A237E] hover:text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2 flex-shrink-0"
                    title="Autocompletar si ya existe en el directorio">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Verificar C.I.
                </button>
            </div>
            @error('rep_cedula')<p class="text-[10px] font-bold text-red-500 ml-1">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Parentesco <span class="text-red-500 rep-req">*</span></label>
            @php $currentRel = old('rep_relationship', $rep?->relationship ?? 'Madre'); @endphp
            <select name="rep_relationship" id="rep_relationship" required
                class="rep-req-input w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] appearance-none">
                @foreach(['Madre', 'Padre', 'Abuela', 'Abuelo', 'Tía', 'Tío', 'Hermana/o Mayor', 'Representante Legal'] as $rel)
                    <option value="{{ $rel }}" {{ $currentRel === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                @endforeach
            </select>
            @error('rep_relationship')<p class="text-[10px] font-bold text-red-500 ml-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Nombres y Apellidos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Primer Nombre <span class="text-red-500 rep-req">*</span></label>
            <input type="text" name="rep_first_name" id="rep_first_name"
                value="{{ old('rep_first_name', $rep?->first_name) }}"
                placeholder="EJ: MARÍA" required
                oninput="this.value = this.value.toUpperCase()"
                class="rep-req-input w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] uppercase">
            @error('rep_first_name')<p class="text-[10px] font-bold text-red-500 ml-1">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Segundo Nombre</label>
            <input type="text" name="rep_second_name" id="rep_second_name"
                value="{{ old('rep_second_name', $rep?->second_name) }}"
                placeholder="OPCIONAL"
                oninput="this.value = this.value.toUpperCase()"
                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] uppercase">
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Primer Apellido <span class="text-red-500 rep-req">*</span></label>
            <input type="text" name="rep_last_name" id="rep_last_name"
                value="{{ old('rep_last_name', $rep?->last_name) }}"
                placeholder="EJ: PÉREZ" required
                oninput="this.value = this.value.toUpperCase()"
                class="rep-req-input w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] uppercase">
            @error('rep_last_name')<p class="text-[10px] font-bold text-red-500 ml-1">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Segundo Apellido</label>
            <input type="text" name="rep_second_last_name" id="rep_second_last_name"
                value="{{ old('rep_second_last_name', $rep?->second_last_name) }}"
                placeholder="OPCIONAL"
                oninput="this.value = this.value.toUpperCase()"
                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E] uppercase">
        </div>
    </div>

    {{-- Teléfonos y Correo --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Teléfono Principal / WhatsApp <span class="text-red-500 rep-req">*</span></label>
            <input type="text" name="rep_phone_whatsapp" id="rep_phone_whatsapp"
                value="{{ old('rep_phone_whatsapp', $rep?->phone_whatsapp) }}"
                placeholder="Ej: 04141234567" required
                class="rep-req-input w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
            @error('rep_phone_whatsapp')<p class="text-[10px] font-bold text-red-500 ml-1">{{ $message }}</p>@enderror
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Teléfono Secundario / Local</label>
            <input type="text" name="rep_phone_local" id="rep_phone_local"
                value="{{ old('rep_phone_local', $rep?->phone_local) }}"
                placeholder="Opcional"
                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Correo Electrónico <span class="text-red-500 rep-req">*</span></label>
            <input type="email" name="rep_email" id="rep_email" required
                value="{{ old('rep_email', $rep?->email) }}"
                placeholder="correo@ejemplo.com"
                class="rep-req-input w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
            @error('rep_email')<p class="text-[10px] font-bold text-red-500 ml-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Información Laboral / Socioeconómica --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2 border-t border-gray-50">
        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Ocupación / Oficio</label>
            <input type="text" name="rep_job_title" id="rep_job_title"
                value="{{ old('rep_job_title', $rep?->job_title) }}"
                placeholder="Opcional"
                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Lugar de Trabajo / Dirección Laboral</label>
            <input type="text" name="rep_workplace_address" id="rep_workplace_address"
                value="{{ old('rep_workplace_address', $rep?->workplace_address) }}"
                placeholder="Opcional"
                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
        </div>

        <div class="space-y-2">
            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Red Social / Facebook</label>
            <input type="text" name="rep_facebook" id="rep_facebook"
                value="{{ old('rep_facebook', $rep?->facebook) }}"
                placeholder="Opcional"
                class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#1A237E]/20 outline-none font-bold text-[#1A237E]">
        </div>
    </div>
</div>

<script>
async function lookupRepresentativeByCedula() {
    const cedulaInput = document.getElementById('rep_cedula');
    const badge = document.getElementById('rep-autofill-badge');
    const cedula = (cedulaInput?.value || '').trim();
    if (!cedula) {
        if (typeof showToast === 'function') showToast('Ingresa primero el número de cédula del representante.', 'warning');
        return;
    }

    try {
        const res = await fetch(`{{ url('/api/representatives/by-cedula') }}/${encodeURIComponent(cedula)}`, {
            headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) {
            badge?.classList.add('hidden');
            if (typeof showToast === 'function') showToast('No existe un representante previo con esa cédula. Puedes completar sus datos debajo.', 'info');
            return;
        }
        const data = await res.json();
        if (data && data.found) {
            const map = {
                rep_first_name: data.first_name || '',
                rep_second_name: data.second_name || '',
                rep_last_name: data.last_name || '',
                rep_second_last_name: data.second_last_name || '',
                rep_phone_whatsapp: data.phone_whatsapp || '',
                rep_phone_local: data.phone_local || '',
                rep_email: data.email || '',
                rep_job_title: data.job_title || '',
                rep_workplace_address: data.workplace_address || '',
                rep_facebook: data.facebook || '',
            };
            Object.entries(map).forEach(([id, val]) => {
                const el = document.getElementById(id);
                if (el) el.value = val;
            });
            if (data.relationship) {
                const relSelect = document.getElementById('rep_relationship');
                if (relSelect) relSelect.value = data.relationship;
            }
            badge?.classList.remove('hidden');
            if (typeof showToast === 'function') showToast('Datos del representante cargados automáticamente.', 'success');
        }
    } catch (e) {
        console.error(e);
    }
}
</script>

