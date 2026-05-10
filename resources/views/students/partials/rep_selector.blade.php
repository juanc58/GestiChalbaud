<div class="space-y-2 relative" id="rep-search-container">
    <label class="text-xs font-bold text-gray-400 uppercase ml-1">Vincular Representante (Buscador)</label>
    
    <!-- Search Input -->
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-[#032e5e] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" id="rep-search-input" placeholder="Buscar por Nombre o Cédula..." 
            class="w-full pl-11 pr-5 py-4 rounded-2xl bg-gray-50 border-2 border-transparent focus:border-[#032e5e]/20 outline-none transition-all font-bold text-[#032e5e]"
            autocomplete="off">
        <input type="hidden" name="representative_id" id="selected-rep-id" value="{{ $currentRepId ?? old('representative_id') }}">
    </div>

    <!-- Results Dropdown -->
    <div id="rep-results-container" class="absolute z-50 left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 hidden max-h-64 overflow-y-auto">
        <div id="rep-results-list" class="p-2 space-y-1">
            <!-- Results will be injected here -->
        </div>
        <div id="no-results" class="p-6 text-center hidden">
            <p class="text-sm font-bold text-gray-400 italic">No se encontraron representantes con ese criterio.</p>
        </div>
    </div>

    @if(isset($student) && $student->representative)
        <div id="current-selection-badge" class="mt-2 inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-xl border border-blue-100">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span class="text-xs font-black uppercase tracking-wider" id="rep-display-name">
                {{ $student->representative->first_name }} {{ $student->representative->last_name }}
            </span>
        </div>
    @elseif(old('representative_id'))
        @php $oldRep = $representatives->firstWhere('id', old('representative_id')); @endphp
        @if($oldRep)
            <div id="current-selection-badge" class="mt-2 inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-xl border border-blue-100">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-xs font-black uppercase tracking-wider" id="rep-display-name">
                    {{ $oldRep->user->first_name ?? '' }} {{ $oldRep->user->last_name ?? '' }}
                </span>
            </div>
        @endif
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const representatives = @json($representatives);
    const searchInput = document.getElementById('rep-search-input');
    const resultsContainer = document.getElementById('rep-results-container');
    const resultsList = document.getElementById('rep-results-list');
    const noResults = document.getElementById('no-results');
    const hiddenInput = document.getElementById('selected-rep-id');
    const displayName = document.getElementById('rep-display-name');
    const badge = document.getElementById('current-selection-badge');

    function filterReps(query) {
        if (!query) return [];
        const normalizedQuery = query.toLowerCase().trim();
        return representatives.filter(rep => {
            const u = rep.user || {};
            const fullName = `${u.first_name || ''} ${u.last_name || ''}`.toLowerCase();
            const cedula = String(u.cedula || '');
            return fullName.includes(normalizedQuery) || cedula.includes(normalizedQuery);
        });
    }

    function updateList(filtered) {
        resultsList.innerHTML = '';
        if (filtered.length === 0 && searchInput.value.length > 0) {
            resultsContainer.classList.remove('hidden');
            noResults.classList.remove('hidden');
            return;
        }

        if (filtered.length === 0) {
            resultsContainer.classList.add('hidden');
            return;
        }

        noResults.classList.add('hidden');
        resultsContainer.classList.remove('hidden');

        filtered.forEach(rep => {
            const u = rep.user || {};
            const repFullName = `${u.first_name || ''} ${u.last_name || ''}`;
            const div = document.createElement('div');
            div.className = 'p-3 hover:bg-blue-50 rounded-xl cursor-pointer transition-all border border-transparent hover:border-blue-100 group';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-extrabold text-[#032e5e] group-hover:text-blue-700">${repFullName}</p>
                        <p class="text-[10px] font-bold text-gray-400 group-hover:text-blue-500 uppercase tracking-widest">${rep.relationship || 'Representante'}</p>
                    </div>
                    <span class="text-[10px] font-black text-gray-300 group-hover:text-blue-300">CI: ${u.cedula || 'N/A'}</span>
                </div>
            `;
            div.onclick = () => {
                hiddenInput.value = rep.id;
                searchInput.value = '';
                resultsContainer.classList.add('hidden');
                
                // Update Badge UI
                if (!document.getElementById('current-selection-badge')) {
                    const newBadge = document.createElement('div');
                    newBadge.id = 'current-selection-badge';
                    newBadge.className = 'mt-2 inline-flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-xl border border-green-100';
                    newBadge.innerHTML = `
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-xs font-black uppercase tracking-wider" id="rep-display-name">${repFullName}</span>
                    `;
                    document.getElementById('rep-search-container').appendChild(newBadge);
                } else {
                    document.getElementById('rep-display-name').innerText = repFullName;
                    const existingBadge = document.getElementById('current-selection-badge');
                    existingBadge.classList.remove('bg-blue-50', 'text-blue-700', 'border-blue-100');
                    existingBadge.classList.add('bg-green-50', 'text-green-700', 'border-green-100');
                    existingBadge.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
                }
            };
            resultsList.appendChild(div);
        });
    }

    searchInput.addEventListener('input', (e) => {
        const filtered = filterReps(e.target.value);
        updateList(filtered);
    });

    // Close on click outside
    document.addEventListener('click', (e) => {
        if (!document.getElementById('rep-search-container').contains(e.target)) {
            resultsContainer.classList.add('hidden');
        }
    });

    // Open on focus if there is a query
    searchInput.addEventListener('focus', (e) => {
        if (e.target.value.length > 0) {
            const filtered = filterReps(e.target.value);
            updateList(filtered);
        }
    });
});
</script>
