<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletín Informativo - {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fff; color: #1a202c; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; }
            .print-container { box-shadow: none !important; border: none !important; width: 100% !important; max-width: none !important; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body class="bg-gray-50 p-4 md:p-12">

    <!-- Print Control -->
    <div class="max-w-4xl mx-auto no-print flex justify-between items-center mb-8 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        @php
            $backRoute = auth()->user()->isAdmin() 
                ? route('students.show', $enrollment->student_id) 
                : route('representative.students.index');
        @endphp
        <a href="{{ $backRoute }}" class="text-sm font-bold text-gray-500 hover:text-[#032e5e] transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver
        </a>
        <button onclick="window.print()" class="bg-[#032e5e] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#032e5e]/90 transition-all shadow-lg flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir Boletín
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-12 rounded-[2rem] shadow-xl border border-gray-100 print-container">
        <!-- Header -->
        <header class="flex justify-between items-start border-b-2 border-gray-100 pb-8 mb-8">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-[#032e5e] rounded-2xl flex items-center justify-center text-white mr-4 shadow-lg">
                    <span class="font-black text-xl">SEP</span>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-[#032e5e] uppercase tracking-tight">S.E.U.E.D PÁEZ</h1>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">Ministerio del Poder Popular para la Educación</p>
                    <p class="text-[10px] font-bold text-[#c56c39] uppercase tracking-[0.1em]">BOLETÍN INFORMATIVO DE DESEMPEÑO ACADÉMICO</p>
                </div>
            </div>
            <div class="text-right">
                <div class="inline-block bg-[#032e5e] px-4 py-2 rounded-xl text-white text-xs font-black uppercase tracking-widest">
                    AÑO ESCOLAR: {{ $enrollment->school_year }}
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-2">ID Reporte: #{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </header>

        <!-- Student Basic Info -->
        <section class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-10">
            <div>
                <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Nombre Completo</p>
                <p class="text-sm font-extrabold text-[#032e5e]">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</p>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Cédula de Identidad</p>
                <p class="text-sm font-extrabold text-[#032e5e]">{{ $enrollment->student->cedula ?? 'S/C' }}</p>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Grado / Nivel</p>
                <p class="text-sm font-extrabold text-[#032e5e] uppercase">{{ $enrollment->section->grade->name }}</p>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Sección / Turno</p>
                <p class="text-sm font-extrabold text-[#032e5e] uppercase">"{{ $enrollment->section->name }}" ({{ $enrollment->section->shift }})</p>
            </div>
        </section>

        <!-- Grades Table -->
        <section class="mb-10">
            <h2 class="text-xs font-black text-[#c56c39] uppercase tracking-[0.2em] mb-4 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Control de Calificaciones Académicas
            </h2>
            <div class="overflow-hidden border border-gray-100 rounded-2xl">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-[9px] font-black text-gray-400 uppercase tracking-[0.1em]">
                            <th class="px-6 py-4">Asignatura / Área de Formación</th>
                            <th class="px-6 py-4 text-center">Momento I</th>
                            <th class="px-6 py-4 text-center">Momento II</th>
                            <th class="px-6 py-4 text-center">Momento III</th>
                            <th class="px-6 py-4 text-center bg-gray-100/50">Final</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($subjects as $subject)
                            @php
                                $subjectAssessments = $assessments->get($subject->id, collect());
                                $m1 = $subjectAssessments->firstWhere('term', 1)?->score;
                                $m2 = $subjectAssessments->firstWhere('term', 2)?->score;
                                $m3 = $subjectAssessments->firstWhere('term', 3)?->score;
                            @endphp
                            <tr class="hover:bg-gray-50/30 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-700 text-sm italic">{{ $subject->name }}</td>
                                <td class="px-6 py-4 text-center font-black text-[#032e5e]">{{ $m1 ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-black text-[#032e5e]">{{ $m2 ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-black text-[#032e5e]">{{ $m3 ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-black text-[#c56c39] bg-gray-50/50 text-base">
                                    {{ $m3 ?? ($m2 ?? ($m1 ?? '-')) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-[8px] font-bold text-gray-400 uppercase mt-4 text-right">Escala de Calificación Literaria: A (Excelente), B (Muy Bueno), C (Bueno), D (Regular), E (Deficiente)</p>
        </section>

        <!-- Pedagogical Observations -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="space-y-6">
                <div>
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-[#032e5e] mr-2"></span>
                        Diagnóstico Momento I
                    </h3>
                    <div class="p-4 bg-gray-50 rounded-xl text-xs leading-relaxed text-gray-600 font-medium italic border border-transparent hover:border-gray-200 transition-all">
                        {{ $enrollment->diagnostic_1 ?: 'Observación no registrada todavía.' }}
                    </div>
                </div>
                <div>
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-[#032e5e] mr-2"></span>
                        Diagnóstico Momento II
                    </h3>
                    <div class="p-4 bg-gray-50 rounded-xl text-xs leading-relaxed text-gray-600 font-medium italic border border-transparent hover:border-gray-200 transition-all">
                        {{ $enrollment->diagnostic_2 ?: 'Proceso en evaluación.' }}
                    </div>
                </div>
                <div>
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-[#032e5e] mr-2"></span>
                        Diagnóstico Momento III
                    </h3>
                    <div class="p-4 bg-gray-50 rounded-xl text-xs leading-relaxed text-gray-600 font-medium italic border border-transparent hover:border-gray-200 transition-all">
                        {{ $enrollment->diagnostic_3 ?: 'Pendiente por cierre de ciclo.' }}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h3 class="text-[10px] font-black text-[#c56c39] uppercase tracking-[0.2em] mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        Proyecto de Aprendizaje
                    </h3>
                    <div class="p-4 bg-orange-50/30 rounded-xl text-xs leading-relaxed text-gray-700 font-bold italic border border-orange-100">
                        {{ $enrollment->learning_projects ?: 'No definido.' }}
                    </div>
                </div>
                <div>
                    <h3 class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em] mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Recomendaciones Finales
                    </h3>
                    <div class="p-4 bg-blue-50/30 rounded-xl text-xs leading-relaxed text-gray-700 font-medium italic border border-blue-100">
                        {{ $enrollment->recommendations ?: 'Siga esforzándose para lograr sus metas académicas.' }}
                    </div>
                </div>
                
                <!-- Final Result -->
                <div class="pt-4">
                    <div class="p-6 rounded-2xl border-4 border-double {{ $enrollment->status == 'Promovido' ? 'border-green-200 bg-green-50/50' : 'border-blue-100 bg-blue-50' }} text-center">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Resultado Final del Año</p>
                        <p class="text-2xl font-black {{ $enrollment->status == 'Promovido' ? 'text-green-600' : 'text-[#032e5e]' }} uppercase italic">
                            {{ $enrollment->status }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer / Signatures -->
        <footer class="mt-20">
            <div class="grid grid-cols-3 gap-12 text-center">
                <div>
                    <div class="border-t border-gray-300 pt-3">
                        <p class="text-xs font-black text-gray-700 uppercase">Docente</p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ Auth::user()->full_name }}</p>
                    </div>
                </div>
                <div>
                    <div class="border-t border-gray-300 pt-3 text-center flex flex-col items-center">
                        <div class="w-16 h-16 border-2 border-gray-100 rounded-full flex items-center justify-center mb-2">
                            <p class="text-[8px] text-gray-300 font-black uppercase">Sello</p>
                        </div>
                        <p class="text-xs font-black text-gray-700 uppercase">Dirección</p>
                    </div>
                </div>
                <div>
                    <div class="border-t border-gray-300 pt-3">
                        <p class="text-xs font-black text-gray-700 uppercase">Representante</p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $enrollment->representative?->full_name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-16 pt-8 border-t border-gray-100 text-center">
                <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest">Generado automáticamente por el Sistema Escolar U.E.D PÁEZ &copy; {{ date('Y') }}</p>
            </div>
        </footer>
    </div>
</body>
</html>
