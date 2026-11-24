@extends('dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-extrabold text-[#1A237E]">Configuración Institucional y Servicio de Correo</h3>
            <p class="text-sm text-gray-500 font-semibold mt-1">
                Administra los datos de firma oficial de la Dirección del Plantel. La configuración técnica del servidor de correo está oculta para el usuario final.
            </p>
        </div>
        <a href="{{ route('mail.compose') }}"
           class="px-5 py-2.5 bg-[#1A237E] text-white rounded-xl text-xs font-bold hover:bg-[#1A237E]/90 transition-all self-start md:self-auto">
            ✉ Ir al Módulo de Envío de Correo
        </a>
    </div>

    <form action="{{ route('settings.smtp.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Bloque Visible: Datos Institucionales y Remitente -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-5">
            <h4 class="text-xs font-black text-[#1A237E] uppercase tracking-widest border-b border-gray-100 pb-3">
                Datos Oficiales de Dirección y Remitente
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Nombre del Director(a) (Para Firmas)</label>
                    <input type="text" name="director_name"
                           value="{{ old('director_name', $settings['director_name']) }}"
                           oninput="this.value = this.value.toUpperCase()"
                           placeholder="DIRECCIÓN DEL PLANTEL"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm uppercase outline-none focus:border-[#1A237E]">
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Cédula del Director(a)</label>
                    <input type="text" name="director_cedula"
                           value="{{ old('director_cedula', $settings['director_cedula']) }}"
                           placeholder="V-12345678"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-semibold text-gray-700 text-sm outline-none focus:border-[#1A237E]">
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Correo Remitente Oficial *</label>
                    <input type="email" name="mail_from_address" required
                           value="{{ old('mail_from_address', $settings['mail_from_address']) }}"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm outline-none focus:border-[#1A237E]">
                </div>

                <div>
                    <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Nombre Visible del Remitente *</label>
                    <input type="text" name="mail_from_name" required
                           value="{{ old('mail_from_name', $settings['mail_from_name']) }}"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-semibold text-gray-700 text-sm outline-none focus:border-[#1A237E]">
                </div>
            </div>
        </div>

        <!-- Bloque Técnico Oculto / Colapsable (Separado de la vista del usuario final) -->
        <details class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <summary class="px-6 py-4 cursor-pointer text-xs font-black text-gray-600 uppercase tracking-widest bg-gray-50 hover:bg-gray-100 transition-colors flex items-center justify-between">
                <span>⚙ Configuración Técnica Avanzada del Servidor de Correo (Solo Soporte)</span>
                <span class="text-[10px] font-semibold text-gray-400">Oculto al usuario final</span>
            </summary>

            <div class="p-6 space-y-5 border-t border-gray-100">
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="applyPreset('local')" class="px-3.5 py-2 rounded-lg bg-[#1A237E] text-white text-xs font-bold">
                        Servidor Local de Pruebas (127.0.0.1:1025)
                    </button>
                    <button type="button" onclick="applyPreset('gmail')" class="px-3.5 py-2 rounded-lg bg-red-50 text-red-700 border border-red-200 text-xs font-bold">
                        Correo Gmail (Puerto 587)
                    </button>
                    <button type="button" onclick="applyPreset('outlook')" class="px-3.5 py-2 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold">
                        Correo Institucional / Outlook (Puerto 587)
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Modo de Envío</label>
                        <select name="mail_mailer" id="mail_mailer" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm">
                            <option value="smtp" {{ ($settings['mail_mailer'] ?? 'smtp') === 'smtp' ? 'selected' : '' }}>Servidor de Correo Externo / Local</option>
                            <option value="log" {{ ($settings['mail_mailer'] ?? '') === 'log' ? 'selected' : '' }}>Registro Interno de Pruebas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Dirección del Servidor *</label>
                        <input type="text" name="mail_host" id="mail_host" required value="{{ old('mail_host', $settings['mail_host']) }}"
                               class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Puerto *</label>
                        <input type="number" name="mail_port" id="mail_port" required value="{{ old('mail_port', $settings['mail_port']) }}"
                               class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Seguridad de Conexión</label>
                        <select name="mail_encryption" id="mail_encryption" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-bold text-[#1A237E] text-sm">
                            <option value="" {{ empty($settings['mail_encryption']) ? 'selected' : '' }}>Sin cifrado (Solo pruebas locales)</option>
                            <option value="tls" {{ ($settings['mail_encryption'] ?? '') === 'tls' ? 'selected' : '' }}>Cifrado Estándar (Puerto 587)</option>
                            <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>Cifrado Seguro Directo (Puerto 465)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Usuario de Acceso</label>
                        <input type="text" name="mail_username" id="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}"
                               autocomplete="off" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-semibold text-gray-700 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">
                            Clave de Acceso @if(!empty($settings['mail_password'])) <span class="text-emerald-600">(Cifrada)</span> @endif
                        </label>
                        <input type="password" name="mail_password" id="mail_password" placeholder="Dejar vacío para conservar"
                               autocomplete="new-password" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-semibold text-gray-700 text-sm">
                        <label class="inline-flex items-center gap-1.5 mt-1.5 text-[11px] text-gray-500 cursor-pointer">
                            <input type="checkbox" name="clear_password" value="1"> Borrar clave almacenada
                        </label>
                    </div>
                </div>
            </div>
        </details>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 rounded-xl bg-[#1A237E] text-white font-bold text-sm hover:bg-[#1A237E]/90 transition-all shadow-sm">
                Guardar Configuración
            </button>
        </div>
    </form>

    <!-- Prueba Rápida de Conexión -->
    <form action="{{ route('settings.smtp.test') }}" method="POST" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col sm:flex-row gap-4 items-end">
        @csrf
        <div class="flex-1 w-full">
            <label class="block text-[10px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Verificar Envío de Prueba a Correo</label>
            <input type="email" name="test_email" required placeholder="prueba@correo.com"
                   class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 font-semibold text-sm outline-none focus:border-[#1A237E]">
        </div>
        <button type="submit" class="px-5 py-3 rounded-xl bg-[#FBC02D] text-[#1A237E] font-extrabold text-xs uppercase tracking-wider hover:bg-yellow-400 transition-all">
            Enviar Correo de Prueba
        </button>
    </form>
</div>

<script>
function applyPreset(type) {
    const presets = {
        local:   { host: '127.0.0.1', port: '1025', enc: '' },
        gmail:   { host: 'smtp.gmail.com', port: '587', enc: 'tls' },
        outlook: { host: 'smtp.office365.com', port: '587', enc: 'tls' },
    };
    const p = presets[type];
    if (!p) return;
    document.getElementById('mail_mailer').value = 'smtp';
    document.getElementById('mail_host').value = p.host;
    document.getElementById('mail_port').value = p.port;
    document.getElementById('mail_encryption').value = p.enc;
}
</script>
@endsection
