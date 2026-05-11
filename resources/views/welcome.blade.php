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
    <section class="aurora-wrap hero-clip pt-36 pb-56">
        <div class="aurora-content max-w-5xl mx-auto px-6 text-center">
            <div class="space-y-8 py-20">
                <div class="anim-fadeup inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gold/30 bg-gold/10 text-gold text-xs font-bold uppercase tracking-widest">
                    <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
                    Gestión 2026 Activa
                </div>
                <h1 class="anim-fadeup delay-100 text-5xl lg:text-7xl font-black leading-[1.08] tracking-tight text-white">
                    La infraestructura<br>educativa del<br><span class="gradient-text">futuro.</span>
                </h1>
                <p class="anim-fadeup delay-200 text-xl text-white/55 max-w-2xl mx-auto leading-relaxed">
                    Automatizamos cada proceso académico de la <strong class="text-white/90">U.E.B. Coronel Carlos Delgado Chalbaud</strong> para docentes, alumnos y representantes.
                </p>
                <div class="anim-fadeup delay-300 flex flex-wrap gap-4 justify-center pt-2">
                    <a href="#login" class="btn-primary px-8 py-4 text-sm font-bold flex items-center gap-2">Acceder al Sistema <span class="cta-chevron">›</span></a>
                    <a href="{{ route('register.representative') }}" class="btn-ghost px-8 py-4 text-sm">Registrarme como Representante</a>
                </div>
                <div class="anim-fadeup delay-300 flex gap-12 justify-center pt-8 border-t border-white/10">
                    <div><div class="text-3xl font-black text-gold">100%</div><div class="text-xs text-white/40 mt-1 uppercase tracking-wider">Digitalizado</div></div>
                    <div><div class="text-3xl font-black text-gold">24/7</div><div class="text-xs text-white/40 mt-1 uppercase tracking-wider">Disponible</div></div>
                    <div><div class="text-3xl font-black text-white">Laravel 12</div><div class="text-xs text-white/40 mt-1 uppercase tracking-wider">Backend</div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════ LOGIN SPLIT-SCREEN ═══════════════════════════════ -->
    <section id="login" class="bg-[#f0f2f8] py-12 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-6">
                <p class="text-danger text-xs font-bold uppercase tracking-widest mb-1">— Portal Institucional</p>
                <h2 class="text-2xl font-black text-primary">Accede a tu cuenta</h2>
            </div>
            <!-- Card split-screen -->
            <div class="bg-white rounded-[2rem] shadow-2xl shadow-primary/10 flex overflow-hidden" style="min-height:420px">

                <!-- ── Izquierda: Carrusel ── -->
                <div class="hidden md:flex md:w-1/2 relative overflow-hidden" id="carousel-panel">
                    <!-- Slides -->
                    <div class="carousel-slides w-full h-full relative">

                        <!-- Slide 1 (PRIMERO): Logo GestiChalbaud -->
                        <div class="carousel-slide absolute inset-0 flex flex-col items-center justify-center p-6 transition-opacity duration-700" style="background:linear-gradient(135deg,#1B1F6E 0%,#1A237E 100%); opacity:1;">
                            <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo" class="w-16 h-16 rounded-full object-cover ring-4 ring-gold/40 shadow-2xl mb-3" onerror="this.style.display='none'">
                            <h3 class="text-white font-black text-lg text-center mb-1">GestiChalbaud</h3>
                            <p class="text-white/60 text-[11px] text-center leading-relaxed">U.E.B. Coronel Carlos Delgado Chalbaud<br>Sistema académico moderno y seguro.</p>
                            <div class="mt-3 w-8 h-1 bg-danger rounded-full"></div>
                        </div>

                        <!-- Slide 2: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide2.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-8 left-0 right-0 px-6 text-center">
                                <h3 class="text-white font-black text-base mb-1">Gestión Estudiantil</h3>
                                <p class="text-white/70 text-[11px] leading-relaxed">Control total de inscripciones y calificaciones.</p>
                            </div>
                        </div>

                        <!-- Slide 3: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide3.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-8 left-0 right-0 px-6 text-center">
                                <h3 class="text-white font-black text-base mb-1">Portal Docente</h3>
                                <p class="text-white/70 text-[11px] leading-relaxed">Registro de notas y gestión de secciones.</p>
                            </div>
                        </div>

                        <!-- Slide 4: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide4.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-8 left-0 right-0 px-6 text-center">
                                <h3 class="text-white font-black text-base mb-1">Área de Representantes</h3>
                                <p class="text-white/70 text-[11px] leading-relaxed">Seguimiento académico 24/7 de sus representados.</p>
                            </div>
                        </div>

                        <!-- Slide 5: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide5.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-8 left-0 right-0 px-6 text-center">
                                <h3 class="text-white font-black text-base mb-1">Años Escolares</h3>
                                <p class="text-white/70 text-[11px] leading-relaxed">Planificación y control del calendario académico.</p>
                            </div>
                        </div>

                        <!-- Slide 6: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide6.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-8 left-0 right-0 px-6 text-center">
                                <h3 class="text-white font-black text-base mb-1">Comunidad Escolar</h3>
                                <p class="text-white/70 text-[11px] leading-relaxed">Unidos por la educación de calidad.</p>
                            </div>
                        </div>

                    </div>
                    <!-- Dots -->
                    <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5 z-10">
                        <button class="carousel-dot w-1.5 h-1.5 rounded-full bg-white transition-all" data-index="0"></button>
                        <button class="carousel-dot w-1.5 h-1.5 rounded-full bg-white/30 transition-all" data-index="1"></button>
                        <button class="carousel-dot w-1.5 h-1.5 rounded-full bg-white/30 transition-all" data-index="2"></button>
                        <button class="carousel-dot w-1.5 h-1.5 rounded-full bg-white/30 transition-all" data-index="3"></button>
                        <button class="carousel-dot w-1.5 h-1.5 rounded-full bg-white/30 transition-all" data-index="4"></button>
                        <button class="carousel-dot w-1.5 h-1.5 rounded-full bg-white/30 transition-all" data-index="5"></button>
                    </div>
                    <!-- Línea roja decorativa inferior izquierda -->
                    <div class="absolute bottom-0 left-0 right-0 h-1" style="background:linear-gradient(to right,#D32F2F,transparent)"></div>
                </div>

                <!-- ── Derecha: Formulario ── -->
                <div class="w-full md:w-1/2 px-10 py-6 flex flex-col justify-center bg-white">
                    <!-- Header del form -->
                    <div class="mb-4">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.25em] mb-1">Bienvenido de vuelta</p>
                        <h2 class="text-xl font-black text-primary leading-tight">Iniciar Sesión</h2>
                    </div>

                    @if ($errors->any())
                        <div class="mb-3 p-2 rounded-xl border-l-4 border-danger bg-red-50 text-[11px] text-danger font-semibold">
                            @foreach ($errors->all() as $error)
                                <div class="flex items-center gap-1.5"><span class="text-danger font-black">•</span> {{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" class="space-y-2.5">
                        @csrf
                        <!-- Cédula -->
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Cédula de Identidad</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-300"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                                <input type="number" name="cedula" placeholder="ej. 12345678" value="{{ old('cedula') }}" required
                                    class="w-full bg-gray-50 border-2 border-transparent rounded-xl py-2 pl-9 pr-3 text-sm font-medium text-gray-800 outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10">
                            </div>
                        </div>
                        <!-- Contraseña -->
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Contraseña</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-300"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                                <input type="password" id="pwd2" name="password" placeholder="••••••••" required
                                    class="w-full bg-gray-50 border-2 border-transparent rounded-xl py-2 pl-9 pr-10 text-sm font-medium text-gray-800 outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10">
                                <button type="button" onclick="togglePwd2()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 hover:text-primary transition-colors">
                                    <svg id="eye2-open" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eye2-off" class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.012-2.125M12 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.012 2.125M1 1l22 22"/></svg>
                                </button>
                            </div>
                        </div>
                        <!-- Captcha -->
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-between mb-1">
                                Verificación Humana
                                <button type="button" onclick="document.getElementById('cap2').src='{{ route('captcha.image') }}?'+Math.random()" class="text-gold hover:underline text-[8px] font-bold">↺ Actualizar</button>
                            </label>
                            <div class="flex gap-2 items-center">
                                <div class="bg-gray-50 border-2 border-transparent rounded-xl p-1.5 flex justify-center w-1/3">
                                    <img id="cap2" src="{{ route('captcha.image') }}" alt="Captcha" class="h-7 rounded cursor-pointer" onclick="this.src='{{ route('captcha.image') }}?'+Math.random()">
                                </div>
                                <input type="text" name="captcha" placeholder="Escribe el código" required
                                    class="w-2/3 bg-gray-50 border-2 border-transparent rounded-xl py-2 text-sm font-bold text-gray-800 text-center tracking-[.2em] uppercase outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10">
                            </div>
                        </div>
                        <!-- Remember / Forgot -->
                        <div class="flex justify-between items-center text-[11px] pt-1">
                            <label class="flex items-center gap-1.5 text-gray-400 cursor-pointer font-medium">
                                <input type="checkbox" name="remember" class="accent-gold w-3 h-3 rounded"> Recordarme
                            </label>
                            <a href="#" class="text-danger hover:underline font-semibold">¿Olvidaste tu acceso?</a>
                        </div>
                        <!-- Botón Submit -->
                        <button type="submit"
                            class="w-full font-bold text-[13px] py-2.5 mt-1 rounded-xl flex items-center justify-center gap-2 group transition-all active:scale-[.98]"
                            style="background:linear-gradient(135deg,#FBC02D,#F9A825); color:#1A237E; box-shadow:0 4px 15px rgba(251,192,45,.3);"
                            onmouseover="this.style.filter='brightness(1.06)';this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.filter='';this.style.transform=''">
                            Entrar al Sistema
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </form>

                    <!-- Divisor -->
                    <div class="flex items-center gap-3 my-3">
                        <div class="flex-1 h-px bg-gray-100"></div>
                        <span class="text-[9px] text-gray-300 font-semibold">o</span>
                        <div class="flex-1 h-px bg-gray-100"></div>
                    </div>

                    <!-- Registro -->
                    <a href="{{ route('register.representative') }}"
                        class="w-full flex items-center justify-center gap-1.5 py-2.5 rounded-xl border-2 border-gray-100 text-[12px] font-semibold text-gray-500 hover:border-primary/20 hover:text-primary hover:bg-gray-50 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Registrarme como Representante
                    </a>
                    <!-- Detalle rojo decorativo -->
                    <div class="mt-3 flex items-center justify-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                        <p class="text-[8px] text-gray-300 font-semibold uppercase tracking-widest">Conexión segura — GestiChalbaud</p>
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
        // ── Password toggle ──
        function togglePwd2() {
            const p = document.getElementById('pwd2');
            const o = document.getElementById('eye2-open');
            const f = document.getElementById('eye2-off');
            if (p.type === 'password') { p.type = 'text'; o.classList.add('hidden'); f.classList.remove('hidden'); }
            else { p.type = 'password'; o.classList.remove('hidden'); f.classList.add('hidden'); }
        }

        // ── Carrusel automático ──
        (function () {
            const slides = document.querySelectorAll('.carousel-slide');
            const dots   = document.querySelectorAll('.carousel-dot');
            if (!slides.length) return;
            let current = 0;

            function goTo(index) {
                slides[current].style.opacity = '0';
                dots[current].classList.remove('bg-white');
                dots[current].classList.add('bg-white/30');
                current = (index + slides.length) % slides.length;
                slides[current].style.opacity = '1';
                dots[current].classList.add('bg-white');
                dots[current].classList.remove('bg-white/30');
            }

            dots.forEach(dot => dot.addEventListener('click', () => goTo(parseInt(dot.dataset.index))));
            setInterval(() => goTo(current + 1), 5000);
        })();
    </script>
</body>
</html>
