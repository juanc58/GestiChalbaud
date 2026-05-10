<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GestiChalbaud — Sistema de Gestión Escolar</title>
    <meta name="description" content="Sistema académico de la U.E.B. Coronel Carlos Delgado Chalbaud. Portal estudiantil, docente y representante.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1A237E',
                        gold:    '#FBC02D',
                        danger:  '#D32F2F',
                        cream:   '#FFF59D',
                        slate:   '#4f566b',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* ── Aurora Hero ── */
        .aurora-wrap {
            position: relative;
            background: #0d1547;
            overflow: hidden;
        }
        .aurora-wrap::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 40%, rgba(26,35,126,.9) 0%, transparent 70%),
                radial-gradient(ellipse 60% 80% at 80% 20%, rgba(251,192,45,.25) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 60% 80%, rgba(26,35,126,.6) 0%, transparent 70%);
            filter: blur(40px);
            z-index: 0;
        }
        /* grid texture */
        .aurora-wrap::after {
            content: '';
            position: absolute; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .aurora-content { position: relative; z-index: 1; }

        /* Diagonal clip en la parte inferior del hero */
        .hero-clip {
            clip-path: polygon(0 0, 100% 0, 100% 88%, 0 100%);
        }

        /* Headline gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #FBC02D 0%, #fff 60%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Navbar blur */
        .navbar-blur {
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            background: rgba(13,21,71,.7);
        }

        /* Botón primario */
        .btn-primary {
            background: #FBC02D;
            color: #1A237E;
            border-radius: 8px;
            font-weight: 700;
            transition: filter .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 20px rgba(251,192,45,.35);
        }
        .btn-primary:hover { filter: brightness(1.07); transform: translateY(-1px); box-shadow: 0 8px 28px rgba(251,192,45,.45); }
        .btn-primary:active { transform: scale(.98); }

        /* Botón ghost */
        .btn-ghost {
            border: 1.5px solid rgba(255,255,255,.35);
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
            transition: background .2s;
        }
        .btn-ghost:hover { background: rgba(255,255,255,.08); }

        /* Logo cloud */
        .logo-cloud-item {
            opacity: .45;
            filter: grayscale(100%) brightness(180%);
            transition: opacity .2s;
        }
        .logo-cloud-item:hover { opacity: .7; }

        /* Animaciones */
        @keyframes fadeUp { from { opacity:0; transform:translateY(28px); } to { opacity:1; transform:translateY(0); } }
        .anim-fadeup   { animation: fadeUp .8s ease both; }
        .delay-100     { animation-delay: .1s; }
        .delay-200     { animation-delay: .2s; }
        .delay-300     { animation-delay: .3s; }

        /* Chevron CTA */
        .cta-chevron { transition: transform .2s; display:inline-block; }
        .btn-primary:hover .cta-chevron { transform: translateX(4px); }

        /* Feature cards */
        .feat-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 16px;
            transition: background .25s, border-color .25s;
        }
        .feat-card:hover {
            background: rgba(251,192,45,.07);
            border-color: rgba(251,192,45,.25);
        }

        /* Red accent bar at bottom of navbar */
        .red-bar { height: 2px; background: linear-gradient(to right, #D32F2F 0%, transparent 50%); }
    </style>
</head>
<body class="antialiased bg-[#0d1547]">

    <!-- ═══════════════════════════════ NAVBAR ═══════════════════════════════ -->
    <nav class="fixed w-full z-50 navbar-blur border-b border-white/10">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo"
                         class="w-9 h-9 rounded-full object-cover ring-2 ring-gold/30"
                         onerror="this.style.display='none'">
                    <div>
                        <span class="text-white font-black text-base leading-none block">GestiChalbaud</span>
                        <!-- Punto rojo decorativo -->
                        <span class="text-[9px] text-white/40 font-semibold tracking-widest uppercase flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-danger inline-block"></span>
                            Sistema Escolar
                        </span>
                    </div>
                </div>
                <!-- Links -->
                <div class="hidden md:flex items-center gap-8 text-sm text-white/60 font-medium">
                    <a href="#" class="hover:text-white transition-colors">Inicio</a>
                    <a href="#" class="hover:text-white transition-colors">Institución</a>
                    <a href="#" class="hover:text-white transition-colors">Académico</a>
                    <a href="#" class="hover:text-white transition-colors">Contacto</a>
                </div>
                <!-- CTA -->
                <a href="#login" class="btn-primary px-5 py-2 text-sm font-bold">
                    Portal de Acceso <span class="cta-chevron">›</span>
                </a>
            </div>
        </div>
        <div class="red-bar"></div>
    </nav>

    <!-- ═══════════════════════════════ HERO AURORA ═══════════════════════════════ -->
    <section class="aurora-wrap hero-clip pt-36 pb-48">
        <div class="aurora-content max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center min-h-[75vh]">

                <!-- Texto -->
                <div class="space-y-8">
                    <!-- Badge -->
                    <div class="anim-fadeup inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gold/30 bg-gold/10 text-gold text-xs font-bold uppercase tracking-widest">
                        <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
                        Gestión 2026 Activa
                    </div>

                    <!-- H1 -->
                    <h1 class="anim-fadeup delay-100 text-5xl lg:text-6xl font-black leading-[1.1] tracking-tight text-white">
                        La infraestructura<br>
                        educativa del<br>
                        <span class="gradient-text">futuro.</span>
                    </h1>

                    <!-- Subheadline -->
                    <p class="anim-fadeup delay-200 text-lg text-white/60 max-w-lg leading-relaxed font-normal">
                        Automatizamos cada proceso académico de la
                        <strong class="text-white/90 font-semibold">U.E.B. Coronel Carlos Delgado Chalbaud</strong>
                        para que docentes, alumnos y representantes tengan todo en un solo lugar.
                    </p>

                    <!-- CTAs -->
                    <div class="anim-fadeup delay-300 flex flex-wrap gap-4 pt-2">
                        <a href="#login" class="btn-primary px-7 py-3.5 text-sm font-bold flex items-center gap-2">
                            Acceder al Sistema <span class="cta-chevron">›</span>
                        </a>
                        <a href="{{ route('register.representative') }}" class="btn-ghost px-7 py-3.5 text-sm">
                            Registrarme como Representante
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="anim-fadeup delay-300 flex gap-10 pt-6 border-t border-white/10">
                        <div>
                            <div class="text-2xl font-black text-gold">100%</div>
                            <div class="text-xs text-white/40 font-medium mt-0.5 uppercase tracking-wider">Digitalizado</div>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-gold">24/7</div>
                            <div class="text-xs text-white/40 font-medium mt-0.5 uppercase tracking-wider">Disponible</div>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-white">Laravel 12</div>
                            <div class="text-xs text-white/40 font-medium mt-0.5 uppercase tracking-wider">Backend</div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Login integrado en Hero -->
                <div id="login" class="anim-fadeup delay-200">
                    <div style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:24px; padding:2.5rem; backdrop-filter:blur(20px);">
                        <p class="text-white/50 text-xs font-bold uppercase tracking-widest mb-6">— Portal de Acceso</p>
                        <h2 class="text-2xl font-black text-white mb-1">Iniciar Sesión</h2>
                        <p class="text-white/40 text-sm mb-8">Ingresa tus credenciales institucionales</p>

                        @if ($errors->any())
                            <div class="mb-6 p-4 rounded-xl border-l-4 border-danger bg-danger/10 text-sm text-red-300 font-medium">
                                @foreach ($errors->all() as $error)
                                    <div class="flex items-center gap-2"><span class="text-danger">•</span> {{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                            @csrf
                            <!-- Cédula -->
                            <div>
                                <label class="text-[10px] text-white/40 font-bold uppercase tracking-widest block mb-1.5">Cédula de Identidad</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </span>
                                    <input type="number" name="cedula" placeholder="ej. 12345678" value="{{ old('cedula') }}" required
                                        style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); border-radius:10px; color:white; width:100%; padding:12px 16px 12px 40px; outline:none; font-size:14px; transition:border-color .2s;"
                                        onfocus="this.style.borderColor='rgba(251,192,45,0.5)'"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                                </div>
                            </div>
                            <!-- Password -->
                            <div>
                                <label class="text-[10px] text-white/40 font-bold uppercase tracking-widest block mb-1.5">Contraseña</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/30">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </span>
                                    <input type="password" id="pwd" name="password" placeholder="••••••••" required
                                        style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); border-radius:10px; color:white; width:100%; padding:12px 40px 12px 40px; outline:none; font-size:14px; transition:border-color .2s;"
                                        onfocus="this.style.borderColor='rgba(251,192,45,0.5)'"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                                    <button type="button" onclick="togglePwd()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/30 hover:text-white/60 transition-colors">
                                        <svg id="eye-open-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg id="eye-off-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.012-2.125M12 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.012 2.125M1 1l22 22"/></svg>
                                    </button>
                                </div>
                            </div>
                            <!-- Captcha -->
                            <div>
                                <label class="text-[10px] text-white/40 font-bold uppercase tracking-widest flex items-center justify-between mb-1.5">
                                    Verificación
                                    <button type="button" onclick="document.getElementById('cap-img').src='{{ route('captcha.image') }}?'+Math.random()" class="text-gold hover:underline text-[9px]">↺ Actualizar</button>
                                </label>
                                <div style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:10px; margin-bottom:8px; display:flex; justify-content:center;">
                                    <img id="cap-img" src="{{ route('captcha.image') }}" alt="Captcha" class="h-10 cursor-pointer rounded" onclick="this.src='{{ route('captcha.image') }}?'+Math.random()">
                                </div>
                                <input type="text" name="captcha" placeholder="Escribe el código" required
                                    style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); border-radius:10px; color:white; width:100%; padding:12px 16px; outline:none; font-size:14px; text-align:center; letter-spacing:.2em; text-transform:uppercase; transition:border-color .2s;"
                                    onfocus="this.style.borderColor='rgba(251,192,45,0.5)'"
                                    onblur="this.style.borderColor='rgba(255,255,255,0.1)'">
                            </div>
                            <!-- Recordar / Recuperar -->
                            <div class="flex justify-between items-center text-xs">
                                <label class="flex items-center gap-2 text-white/40 cursor-pointer">
                                    <input type="checkbox" name="remember" class="accent-gold"> Recordarme
                                </label>
                                <a href="#" class="text-danger hover:underline font-medium">¿Olvidaste tu acceso?</a>
                            </div>
                            <!-- Submit -->
                            <button type="submit"
                                class="w-full font-bold text-sm py-3.5 rounded-[8px] transition-all flex items-center justify-center gap-2 group"
                                style="background:linear-gradient(135deg,#FBC02D,#F9A825); color:#1A237E; box-shadow:0 4px 20px rgba(251,192,45,.3);"
                                onmouseover="this.style.filter='brightness(1.07)'; this.style.transform='translateY(-1px)'"
                                onmouseout="this.style.filter=''; this.style.transform=''"
                                onmousedown="this.style.transform='scale(.98)'"
                                onmouseup="this.style.transform='translateY(-1px)'">
                                Entrar al Sistema
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                        </form>

                        <div class="mt-6 pt-5 border-t border-white/10 text-center">
                            <a href="{{ route('register.representative') }}" class="text-white/40 hover:text-white text-xs transition-colors">
                                ¿Sin cuenta? <span class="text-gold font-semibold">Regístrate como representante →</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════ LOGO CLOUD ═══════════════════════════════ -->
    <section class="bg-[#0d1547] py-16 border-t border-white/5">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p class="text-white/25 text-xs font-semibold uppercase tracking-[0.3em] mb-10">Construido con tecnología de primer nivel</p>
            <div class="flex flex-wrap justify-center gap-x-14 gap-y-6 items-center">
                @foreach([
                    ['PHP 8.3',    'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['Laravel 12', 'M4 6h16M4 10h16M4 14h16M4 18h16'],
                    ['MySQL 8',    'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4'],
                    ['Tailwind 4', 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                    ['Vite 7',     'M13 10V3L4 14h7v7l9-11h-7z'],
                ] as [$name, $path])
                    <div class="logo-cloud-item flex items-center gap-2 text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $path }}"/></svg>
                        <span class="text-sm font-bold">{{ $name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════ FEATURES ═══════════════════════════════ -->
    <section class="bg-[#0d1547] py-24">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <p class="text-gold text-xs font-bold uppercase tracking-widest mb-3">Módulos del Sistema</p>
                <h2 class="text-3xl font-black text-white leading-tight">Todo lo que necesitas,<br><span class="text-white/50">en un solo portal.</span></h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['🎓','Gestión Estudiantil','Inscripciones, historial académico, calificaciones y control de asistencia en tiempo real.'],
                    ['👨‍🏫','Portal Docente','Asignación de secciones, registro de notas y comunicación directa con representantes.'],
                    ['👨‍👩‍👧','Representantes','Seguimiento académico de sus representados con acceso seguro y notificaciones.'],
                ] as [$icon, $title, $desc])
                    <div class="feat-card p-7">
                        <div class="text-3xl mb-4">{{ $icon }}</div>
                        <h3 class="text-white font-bold text-base mb-2">{{ $title }}</h3>
                        <p class="text-white/40 text-sm leading-relaxed">{{ $desc }}</p>
                        <!-- Línea roja decorativa inferior -->
                        <div class="mt-5 h-px w-8 bg-danger/60 rounded-full"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════ FOOTER ═══════════════════════════════ -->
    <footer class="bg-[#080e2e] border-t border-white/5 py-12">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo" class="w-12 h-12 object-cover rounded-full mx-auto mb-4 ring-2 ring-white/10" onerror="this.style.display='none'">
            <p class="text-white font-black text-sm mb-1">GESTICHALBAUD</p>
            <!-- Línea roja decorativa -->
            <div class="w-8 h-0.5 bg-danger mx-auto mb-3 rounded-full"></div>
            <p class="text-white/30 text-xs mb-1">U.E.B. Coronel Carlos Delgado Chalbaud</p>
            <p class="text-white/20 text-xs mb-8">Laravel 12 · MySQL 8 · Laragon</p>
            <div class="flex justify-center gap-8 text-white/25 text-xs mb-8">
                <a href="#" class="hover:text-danger transition-colors">Privacidad</a>
                <a href="#" class="hover:text-danger transition-colors">Términos</a>
                <a href="#" class="hover:text-danger transition-colors">Soporte</a>
            </div>
            <p class="text-white/15 text-[10px] uppercase tracking-widest">© 2026 Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        function togglePwd() {
            const p = document.getElementById('pwd');
            const o = document.getElementById('eye-open-icon');
            const f = document.getElementById('eye-off-icon');
            if (p.type === 'password') { p.type = 'text'; o.classList.add('hidden'); f.classList.remove('hidden'); }
            else { p.type = 'password'; o.classList.remove('hidden'); f.classList.add('hidden'); }
        }
    </script>
</body>
</html>
