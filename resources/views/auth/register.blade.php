<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Usuario — Infochalbaud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f4f6fb; }

        .diag-header {
            background: linear-gradient(135deg, #1A237E 0%, #283593 100%);
            clip-path: polygon(0 0, 100% 0, 100% 82%, 0 100%);
            padding-bottom: 80px;
            position: relative;
            overflow: hidden;
        }
        .diag-header::after {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        .form-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(26,35,126,.12), 0 4px 20px rgba(26,35,126,.06);
            margin-top: -60px;
            position: relative;
            z-index: 10;
        }

        .field {
            background: #f7f8fc;
            border: 1.5px solid #e8eaf0;
            border-radius: 12px;
            padding: 12px 16px 12px 44px;
            width: 100%;
            font-size: 14px;
            font-weight: 500;
            color: #1A237E;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .field:focus {
            border-color: #FBC02D;
            box-shadow: 0 0 0 3px rgba(251,192,45,.15);
            background: #fff;
        }
        .field-no-icon {
            padding-left: 16px;
        }
        .field-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #9fa8c0;
            pointer-events: none;
        }

        .sec-label {
            font-size: 10px;
            font-weight: 800;
            color: #1A237E;
            text-transform: uppercase;
            letter-spacing: .25em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sec-label::before {
            content: '';
            display: block;
            width: 20px;
            height: 2px;
            background: #D32F2F;
            border-radius: 99px;
            flex-shrink: 0;
        }

        .btn-submit {
            background: linear-gradient(135deg, #FBC02D, #F9A825);
            color: #1A237E;
            border-radius: 12px;
            font-weight: 800;
            font-size: 15px;
            width: 100%;
            padding: 16px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 24px rgba(251,192,45,.35);
            display: flex; align-items:center; justify-content:center; gap:8px;
            transition: filter .2s, transform .15s, box-shadow .2s;
        }
        .btn-submit:hover { filter: brightness(1.06); transform: translateY(-1px); box-shadow: 0 10px 30px rgba(251,192,45,.4); }
        .btn-submit:active { transform: scale(.98); }

        .error-box {
            background: #fff5f5;
            border-left: 4px solid #D32F2F;
            border-radius: 10px;
            padding: 14px 16px;
            color: #D32F2F;
            font-size: 13px;
            font-weight: 600;
        }

        @keyframes slideUp { from { opacity:0; transform:translateY(32px); } to { opacity:1; transform:translateY(0); } }
        .slide-up { animation: slideUp .7s ease both; }
        .delay-100 { animation-delay: .1s; }
    </style>
</head>
<body class="min-h-screen">

    <!-- ══ HEADER DIAGONAL ══ -->
    <div class="diag-header px-6 pt-10 pb-24 relative z-0">
        <div class="relative z-10 max-w-6xl mx-auto">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-semibold mb-8 transition-colors">
                <span class="bg-white/10 rounded-xl px-3 py-1.5 text-xs font-bold">← Volver al Inicio de Sesión</span>
            </a>

            <div class="flex items-center gap-4 mb-5">
                <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo"
                     class="w-16 h-16 rounded-full object-cover ring-2 ring-white/30 shadow-lg"
                     onerror="this.style.display='none'">
                <div>
                    <p class="text-white/50 text-sm font-bold uppercase tracking-widest">Infochalbaud</p>
                    <h1 class="text-3xl font-black text-white leading-tight">Solicitud de Registro de Usuario</h1>
                </div>
            </div>
            <p class="text-white/60 text-base leading-relaxed max-w-3xl">
                Formulario institucional para personal docente, administrativo y directivo de la U.E.B. Coronel Carlos Delgado Chalbaud. Una vez enviado, un administrador verificará tu solicitud y asignará tu rol de acceso.
            </p>
        </div>
    </div>

    <!-- ══ FORM CARD FLOTANTE ══ -->
    <div class="max-w-6xl mx-auto px-4 pb-20">
        <div class="form-card p-10 lg:p-14 slide-up delay-100">

            <div class="mb-8 p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs sm:text-sm font-semibold text-amber-900">
                    <strong>Activación sujeta a aprobación:</strong> Al registrarte, tu cuenta quedará en espera hasta que un administrador valide tus datos y te asigne el rol correspondiente (Docente, Personal Administrativo o Administrador).
                </p>
            </div>

            @if ($errors->any())
                <div class="error-box mb-8">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-2 mb-1">
                            <span style="width:6px;height:6px;background:#D32F2F;border-radius:50%;flex-shrink:0;display:inline-block;"></span>
                            <span class="text-sm">{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="flex flex-col lg:flex-row gap-12">
                    
                    <!-- ── COLUMNA IZQUIERDA: Datos de Identidad y Cargo Solicitado ── -->
                    <div class="w-full lg:w-1/2 space-y-8">
                        <div class="space-y-6">
                            <p class="sec-label">Datos de Identidad y Cargo</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Cédula *</label>
                                    <div class="relative">
                                        <svg class="field-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                                        <input type="text" inputmode="numeric" name="cedula" value="{{ old('cedula') }}" required placeholder="ej. 12345678" oninput="this.value=this.value.replace(/\D/g,'')" class="field text-base py-3.5 pl-12">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Cargo Solicitado *</label>
                                    <select name="requested_role" required class="field field-no-icon text-base py-3.5" style="padding-left:16px;">
                                        <option value="">Seleccione perfil...</option>
                                        <option value="docente" {{ old('requested_role') === 'docente' ? 'selected' : '' }}>Docente de Aula</option>
                                        <option value="worker" {{ old('requested_role') === 'worker' ? 'selected' : '' }}>Personal Administrativo / Secretaría</option>
                                        <option value="admin" {{ old('requested_role') === 'admin' ? 'selected' : '' }}>Directivo / Administrador</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                @foreach([['first_name','Primer Nombre','text',true],['second_name','Segundo Nombre','text',false],['last_name','Primer Apellido','text',true],['second_last_name','Segundo Apellido','text',false]] as [$n,$l,$t,$r])
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $l }} {{ $r ? '*' : '' }}</label>
                                    <input type="{{ $t }}" name="{{ $n }}" value="{{ old($n) }}" {{ $r ? 'required' : '' }} oninput="this.value = this.value.toUpperCase()" class="field field-no-icon text-base py-3.5 uppercase">
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-6 pt-6 border-t border-gray-100">
                            <p class="sec-label">Información de Contacto</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Teléfono / WhatsApp *</label>
                                    <div class="relative">
                                        <svg class="field-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="04141234567" class="field text-base py-3.5 pl-12">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Correo Electrónico *</label>
                                    <div class="relative">
                                        <svg class="field-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="usuario@correo.com" class="field text-base py-3.5 pl-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── COLUMNA DERECHA: Seguridad y Pregunta Secreta ── -->
                    <div class="w-full lg:w-1/2 flex flex-col justify-between space-y-8">
                        <div class="space-y-8">
                            <div class="space-y-6">
                                <p class="sec-label">Seguridad de la Cuenta</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Contraseña *</label>
                                        <div class="relative">
                                            <svg class="field-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            <input type="password" name="password" required class="field text-base py-3.5 pl-12">
                                        </div>
                                        <p class="text-[10px] text-gray-400 font-medium ml-1">Mínimo 8 caracteres, Mayúscula, Número y Especial.</p>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Confirmar Clave *</label>
                                        <div class="relative">
                                            <svg class="field-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            <input type="password" name="password_confirmation" required class="field text-base py-3.5 pl-12">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6 pt-6 border-t border-gray-100">
                                <p class="sec-label">Pregunta de Seguridad</p>
                                <p class="text-xs text-gray-400 font-medium -mt-4">Usada para recuperar tu acceso desde el portal si olvidas tu contraseña.</p>
                                <div class="grid grid-cols-1 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pregunta Secreta *</label>
                                        <select name="security_question_id" required class="field field-no-icon text-base py-3.5" style="padding-left:16px;">
                                            <option value="">Seleccione una pregunta...</option>
                                            @foreach($securityQuestions as $question)
                                                <option value="{{ $question->id }}" {{ old('security_question_id') == $question->id ? 'selected' : '' }}>
                                                    {{ $question->question }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Tu Respuesta *</label>
                                        <div class="relative">
                                            <svg class="field-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z"/></svg>
                                            <input type="text" name="security_answer" value="{{ old('security_answer') }}" required placeholder="Tu respuesta secreta" class="field text-base py-3.5 pl-12" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ── SUBMIT ── -->
                        <div class="pt-6 mt-auto">
                            <button type="submit" class="btn-submit text-lg py-4">
                                Enviar Solicitud de Registro
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                            <p class="text-center text-sm text-gray-500 font-medium mt-5">
                                ¿Ya tienes cuenta activa? <a href="{{ route('login') }}#login" class="font-black hover:underline" style="color:#1A237E;">Inicia sesión →</a>
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 font-bold uppercase tracking-widest mt-10">
            © 2026 Infochalbaud — U.E.B. Coronel Carlos Delgado Chalbaud
        </p>
    </div>
</body>
</html>

