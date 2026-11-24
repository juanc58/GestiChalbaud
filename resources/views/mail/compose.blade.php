@extends('dashboard')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Encabezado Limpio -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Correo Institucional y Envío de Documentos</h3>
            <p class="text-sm text-gray-500 font-semibold mt-1">
                Completa destinatario, asunto y cuerpo, y busca directamente al estudiante para adjuntar documentos del sistema sin descargas manuales.
            </p>
        </div>
        @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('settings.smtp.edit') }}"
               class="px-4 py-2.5 bg-white text-[#1A237E] border border-gray-200 rounded-xl text-xs font-bold hover:bg-gray-50 transition-all self-start md:self-auto whitespace-nowrap inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Configuración Institucional
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario Limpio en Flujo Natural: Destinatario -> Asunto -> Cuerpo -> Búsqueda y Adjuntos -->
        <div class="lg:col-span-2">
            <form action="{{ route('mail.send') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 md:p-8 space-y-5">
                @csrf

                <!-- 1. Destinatario -->
                <div>
                    <label class="flex items-center gap-2 text-[10px] font-extrabold text-gray-600 uppercase tracking-widest mb-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#1A237E] text-white text-[10px] font-black">1</span>
                        <span>Destinatario (Correo Electrónico) <span class="text-[#D32F2F]">*</span></span>
                    </label>
                    <input type="email" name="recipient_email" id="composer_recipient_email" required
                           value="{{ old('recipient_email') }}"
                           placeholder="nombre@correo.com"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm outline-none focus:border-[#1A237E] focus:bg-white">
                    @error('recipient_email')<p class="text-xs font-bold text-[#D32F2F] mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- 2. Asunto / Título -->
                <div>
                    <label class="flex items-center gap-2 text-[10px] font-extrabold text-gray-600 uppercase tracking-widest mb-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#1A237E] text-white text-[10px] font-black">2</span>
                        <span>Asunto / Título del Correo <span class="text-[#D32F2F]">*</span></span>
                    </label>
                    <input type="text" name="subject" id="composer_subject" required
                           value="{{ old('subject', 'Comunicación Oficial — U.E.B. Coronel Carlos Delgado Chalbaud') }}"
                           placeholder="Ej: Entrega de Boletín y Constancia de Inscripción"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-gray-800 text-sm outline-none focus:border-[#1A237E] focus:bg-white">
                    @error('subject')<p class="text-xs font-bold text-[#D32F2F] mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- 3. Cuerpo del Mensaje -->
                <div>
                    <label class="flex items-center gap-2 text-[10px] font-extrabold text-gray-600 uppercase tracking-widest mb-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#1A237E] text-white text-[10px] font-black">3</span>
                        <span>Cuerpo del Mensaje <span class="text-[#D32F2F]">*</span></span>
                    </label>
                    <textarea name="body" id="composer_body" rows="5" required
                              placeholder="Escriba el mensaje para el representante o destinatario..."
                              class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-medium text-gray-700 text-sm outline-none focus:border-[#1A237E] focus:bg-white leading-relaxed">{{ old('body', "Estimado(a) representante,\n\nReciba un cordial saludo de parte de la U.E.B. \"Coronel Carlos Delgado Chalbaud\". Por medio de la presente le remitimos la documentación oficial correspondiente al período escolar {$currentYear}.\n\nAtentamente,\nControl de Estudio y Dirección.") }}</textarea>
                    @error('body')<p class="text-xs font-bold text-[#D32F2F] mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- 4. Buscador Dinámico de Estudiante y Documentos del Sistema -->
                <div class="p-5 rounded-xl bg-gray-50/70 border border-gray-200 space-y-4">
                    <input type="hidden" name="student_id" id="composer_student_id" value="{{ $preselectedStudentId ?: '' }}">

                    <div>
                        <label class="flex items-center gap-2 text-[10px] font-extrabold text-[#1A237E] uppercase tracking-widest mb-2">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-[#1A237E] text-white text-[10px] font-black">4</span>
                            <span>Buscar Estudiante para Adjuntar Documentos del Sistema (Opcional)</span>
                        </label>

                        <!-- Tarjeta de Estudiante Seleccionado -->
                        <div id="selected_student_badge" class="{{ $preselectedStudent ? '' : 'hidden' }} mb-3 p-3 rounded-xl bg-white border border-gray-200 flex items-center justify-between gap-3 shadow-sm">
                            <div class="min-w-0">
                                <div id="selected_student_name" class="text-sm font-extrabold text-[#1A237E] truncate">
                                    {{ $preselectedStudent?->full_name }}
                                </div>
                                <div id="selected_student_meta" class="text-[11px] font-semibold text-gray-500 truncate">
                                    @if($preselectedStudent)
                                        {{ $preselectedStudent->cedula ? 'V-' . $preselectedStudent->cedula : 'S/C' }} · {{ $preselectedStudent->status }}
                                    @endif
                                </div>
                            </div>
                            <button type="button" onclick="clearSelectedStudent()"
                                    class="px-3 py-1.5 rounded-lg bg-gray-100 border border-gray-200 hover:bg-red-50 text-gray-600 hover:text-[#D32F2F] text-xs font-bold transition-colors flex-shrink-0 whitespace-nowrap">
                                Cambiar
                            </button>
                        </div>

                        <!-- Caja de Búsqueda Instantánea -->
                        <div id="student_search_container" class="relative {{ $preselectedStudent ? 'hidden' : '' }}">
                            <div class="relative">
                                <input type="text" id="student_doc_search"
                                       placeholder="Buscar por nombre, apellido o cédula del estudiante o representante..."
                                       autocomplete="off"
                                       class="w-full pl-10 pr-10 py-3 rounded-xl bg-white border border-indigo-200 font-semibold text-sm text-[#1A237E] outline-none focus:border-[#1A237E]">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span id="search_spinner" class="hidden text-[10px] font-bold text-[#1A237E] absolute right-3.5 top-1/2 -translate-y-1/2">Buscando...</span>
                            </div>

                            <!-- Resultados de Búsqueda -->
                            <div id="student_search_results" class="hidden mt-2 bg-white rounded-xl border border-gray-200 shadow-lg max-h-64 overflow-y-auto divide-y divide-gray-100"></div>
                        </div>
                    </div>

                    <!-- Documentos Disponibles del Estudiante Seleccionado -->
                    <div id="student_docs_box" class="hidden space-y-3 pt-2 border-t border-indigo-100">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <span class="text-[10px] font-extrabold text-gray-600 uppercase tracking-widest">
                                Documentos Disponibles para Adjuntar en PDF:
                            </span>
                            <input type="text" id="doc_filter_input" oninput="filterAttachedDocs(this.value)"
                                   placeholder="Filtrar documento (ej: boletín, prosecución)..."
                                   class="px-3 py-1.5 rounded-lg bg-white border border-indigo-200 text-xs font-semibold text-gray-700 outline-none focus:border-[#1A237E] sm:w-64">
                        </div>
                        <span id="rep_autofill_info" class="block text-[11px] font-bold text-[#1A237E]"></span>
                        <div id="student_docs_list" class="grid grid-cols-1 md:grid-cols-2 gap-2.5"></div>
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit"
                            class="px-7 py-3.5 rounded-xl bg-[#1A237E] text-white font-bold text-sm hover:bg-[#1A237E]/90 transition-all shadow-md flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Enviar Correo Ahora
                    </button>
                </div>
                </div>
            </form>
        </div>

        <!-- Panel Lateral: Historial Reciente de Envíos -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
                <h4 class="text-xs font-black text-[#1A237E] uppercase tracking-widest border-b border-gray-100 pb-3">
                    Últimos Documentos Enviados
                </h4>
                <div class="space-y-3">
                    @forelse($recentSent as $sent)
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-[#1A237E] uppercase">{{ str_replace('_', ' ', $sent->document_type) }}</span>
                                <span class="text-[10px] font-bold text-gray-400">{{ $sent->folio_code }}</span>
                            </div>
                            <div class="font-semibold text-gray-700">{{ $sent->student?->full_name }}</div>
                            <div class="text-[11px] text-gray-500">Para: {{ $sent->recipient_email }}</div>
                            <div class="text-[10px] text-gray-400">{{ $sent->created_at?->format('d/m/Y H:i') }}</div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic text-center py-6">Aún no se han registrado envíos de documentos por correo.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const preselectedDoc = @json($preselectedDoc ?? '');
let searchTimeout = null;

const searchInput = document.getElementById('student_doc_search');
const resultsBox  = document.getElementById('student_search_results');
const spinner     = document.getElementById('search_spinner');

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const q = this.value.trim();
        clearTimeout(searchTimeout);

        if (q.length < 2) {
            resultsBox.classList.add('hidden');
            resultsBox.innerHTML = '';
            return;
        }

        spinner.classList.remove('hidden');
        searchTimeout = setTimeout(async () => {
            try {
                const res = await fetch(`{{ route('api.mail.search-students') }}?q=${encodeURIComponent(q)}`);
                if (!res.ok) return;
                const items = await res.json();
                renderSearchResults(items);
            } catch (e) {
                console.error(e);
            } finally {
                spinner.classList.add('hidden');
            }
        }, 260);
    });
}

function renderSearchResults(items) {
    resultsBox.innerHTML = '';
    if (!items.length) {
        resultsBox.innerHTML = '<div class="p-4 text-xs text-gray-400 font-semibold text-center">No se encontraron estudiantes o representantes con ese criterio.</div>';
        resultsBox.classList.remove('hidden');
        return;
    }

    items.forEach(item => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'w-full text-left px-4 py-3 hover:bg-indigo-50/70 transition-colors flex items-center justify-between gap-3';
        btn.innerHTML = `
            <div class="min-w-0">
                <div class="text-xs font-extrabold text-[#1A237E] truncate">${item.full_name}</div>
                <div class="text-[11px] text-gray-500 font-semibold truncate">${item.cedula} · ${item.grade_label}${item.rep_name ? ' · Rep: ' + item.rep_name : ''}</div>
            </div>
            <span class="px-2.5 py-1 rounded-lg bg-[#1A237E]/10 text-[#1A237E] text-[10px] font-bold flex-shrink-0">Seleccionar</span>
        `;
        btn.onclick = () => selectStudentForDocs(item);
        resultsBox.appendChild(btn);
    });
    resultsBox.classList.remove('hidden');
}

function selectStudentForDocs(item) {
    document.getElementById('composer_student_id').value = item.id;
    document.getElementById('selected_student_name').textContent = item.full_name;
    document.getElementById('selected_student_meta').textContent = `${item.cedula} · ${item.grade_label}`;
    document.getElementById('selected_student_badge').classList.remove('hidden');
    document.getElementById('student_search_container').classList.add('hidden');
    resultsBox.classList.add('hidden');
    loadStudentDocuments(item.id);
}

function clearSelectedStudent() {
    document.getElementById('composer_student_id').value = '';
    document.getElementById('selected_student_badge').classList.add('hidden');
    document.getElementById('student_search_container').classList.remove('hidden');
    document.getElementById('student_docs_box').classList.add('hidden');
    document.getElementById('student_docs_list').innerHTML = '';
    document.getElementById('rep_autofill_info').textContent = '';
    if (searchInput) {
        searchInput.value = '';
        searchInput.focus();
    }
}

function filterAttachedDocs(term) {
    const q = term.trim().toLowerCase();
    document.querySelectorAll('#student_docs_list label').forEach(el => {
        const text = el.textContent.toLowerCase();
        el.style.display = text.includes(q) ? 'flex' : 'none';
    });
}

async function loadStudentDocuments(studentId) {
    const box = document.getElementById('student_docs_box');
    const list = document.getElementById('student_docs_list');
    const info = document.getElementById('rep_autofill_info');
    const emailInput = document.getElementById('composer_recipient_email');
    const subjectInput = document.getElementById('composer_subject');

    if (!studentId) {
        box.classList.add('hidden');
        list.innerHTML = '';
        info.textContent = '';
        return;
    }

    try {
        const res = await fetch(`{{ url('/api/mail/students') }}/${studentId}/documents`);
        if (!res.ok) return;
        const data = await res.json();

        if (data.rep_email && (!emailInput.value || emailInput.dataset.autofilled === '1')) {
            emailInput.value = data.rep_email;
            emailInput.dataset.autofilled = '1';
        }
        info.textContent = data.rep_name ? `Representante: ${data.rep_name}` : '';
        subjectInput.value = `Documentos Oficiales — ${data.student_name} (Infochalbaud)`;

        list.innerHTML = '';
        data.documents.forEach(doc => {
            const disabledAttr = doc.available ? '' : 'disabled';
            const checkedAttr = (doc.available && preselectedDoc && doc.key === preselectedDoc) ? 'checked' : '';
            const cardCls = doc.available
                ? 'bg-white border-gray-200 hover:border-[#1A237E] cursor-pointer'
                : 'bg-gray-100 border-gray-200 opacity-60 cursor-not-allowed';

            const item = document.createElement('label');
            item.className = `flex items-start gap-2.5 p-3 rounded-xl border text-xs transition-all ${cardCls}`;
            item.title = doc.reason || 'Adjuntar PDF generado automáticamente';
            item.innerHTML = `
                <input type="checkbox" name="attachments[]" value="${doc.key}" ${disabledAttr} ${checkedAttr} class="mt-0.5 rounded text-[#1A237E]">
                <div>
                    <div class="font-bold text-gray-800">${doc.label}</div>
                    ${doc.reason ? `<div class="text-[10px] text-red-600 font-semibold mt-0.5">${doc.reason}</div>` : `<div class="text-[10px] text-[#1A237E] font-semibold mt-0.5">Disponible para adjuntar en PDF</div>`}
                </div>
            `;
            list.appendChild(item);
        });

        box.classList.remove('hidden');
    } catch (e) {
        console.error(e);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const initialId = document.getElementById('composer_student_id').value;
    if (initialId) {
        loadStudentDocuments(initialId);
    }
});
</script>
@endsection
