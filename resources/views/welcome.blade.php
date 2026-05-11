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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                <div class="flex items-center gap-4">
                    <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo"
                         class="w-14 h-14 rounded-full object-cover ring-2 ring-gold/30"
                         onerror="this.style.display='none'">
                    <div>
                        <span class="text-white font-black text-2xl leading-none block">GestiChalbaud</span>
                        <!-- Punto rojo decorativo -->
                        <span class="text-xs text-white/40 font-semibold tracking-widest uppercase flex items-center gap-1.5 mt-0.5">
                            <span class="w-2 h-2 rounded-full bg-danger inline-block"></span>
                            Sistema Escolar
                        </span>
                    </div>
                </div>
                <!-- Desktop Links -->
                <div class="hidden lg:flex items-center gap-8 text-base text-white/60 font-medium">
                    <a href="#" class="hover:text-white transition-colors">Inicio</a>
                    <a href="#" class="hover:text-white transition-colors">Direccion</a>
                    <a href="#" class="hover:text-white transition-colors">Secretaría</a>
                    <a href="#" class="hover:text-white transition-colors">Contacto</a>
                </div>
                <!-- Desktop CTA -->
                <div class="hidden lg:block">
                    <a href="#login" class="btn-primary inline-flex items-center px-6 py-3 text-base font-bold rounded-xl">
                        Portal de Acceso <span class="cta-chevron ml-2">›</span>
                    </a>
                </div>
                <!-- Mobile Hamburger Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-white/80 hover:text-white p-2 focus:outline-none">
                    <svg id="menu-icon-open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                    <svg id="menu-icon-close" class="w-8 h-8 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden lg:hidden bg-[#0d1547] border-t border-white/10 absolute w-full shadow-[0_20px_50px_rgba(0,0,0,0.5)] origin-top transition-all duration-300">
            <div class="flex flex-col px-6 py-8 gap-6">
                <a href="#" class="mobile-link text-white/80 hover:text-white text-xl font-medium">Inicio</a>
                <a href="#" class="mobile-link text-white/80 hover:text-white text-xl font-medium">Direccion</a>
                <a href="#" class="mobile-link text-white/80 hover:text-white text-xl font-medium">Secretaría</a>
                <a href="#" class="mobile-link text-white/80 hover:text-white text-xl font-medium">Contacto</a>
                <hr class="border-white/10 my-2">
                <a href="#login" class="mobile-link btn-primary w-full flex justify-center py-4 text-xl font-bold rounded-xl">
                    Portal de Acceso
                </a>
            </div>
        </div>
        <div class="red-bar"></div>
    </nav>

    <!-- ═══════════════════════════════ HERO AURORA ═══════════════════════════════ -->
    <section class="aurora-wrap hero-clip pt-36 pb-56">
        <div class="aurora-content max-w-5xl mx-auto px-6 text-center">
            <div class="space-y-8 py-20">
                
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
                    <div><div class="text-3xl font-black text-gold">Totalmente</div><div class="text-xs text-white/40 mt-1 uppercase tracking-wider">Digitalizado</div></div>
                    <div><div class="text-3xl font-black text-gold">Siempre</div><div class="text-xs text-white/40 mt-1 uppercase tracking-wider">Disponible</div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════ LOGIN SPLIT-SCREEN ═══════════════════════════════ -->
    <section id="login" class="bg-[#f0f2f8] py-12 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-6">
                <p class="text-danger text-sm font-bold uppercase tracking-widest mb-1">— Portal Institucional</p>
                <h2 class="text-3xl font-black text-primary">Accede a tu cuenta</h2>
            </div>
            <!-- Card split-screen -->
            <div class="bg-white rounded-[2rem] shadow-2xl shadow-primary/10 flex overflow-hidden" style="min-height:420px">

                <!-- ── Izquierda: Carrusel ── -->
                <div class="hidden md:flex md:w-1/2 relative overflow-hidden" id="carousel-panel">
                    <!-- Slides -->
                    <div class="carousel-slides w-full h-full relative">

                        <!-- Slide 1 (PRIMERO): Logo GestiChalbaud -->
                        <div class="carousel-slide absolute inset-0 flex flex-col items-center justify-center p-8 transition-opacity duration-700" style="background:linear-gradient(135deg,#1B1F6E 0%,#1A237E 100%); opacity:1;">
                            <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo" class="w-24 h-24 rounded-full object-cover ring-4 ring-gold/40 shadow-2xl mb-4" onerror="this.style.display='none'">
                            <h3 class="text-white font-black text-3xl text-center mb-2">GestiChalbaud</h3>
                            <p class="text-white/60 text-sm text-center leading-relaxed">U.E.B. Coronel Carlos Delgado Chalbaud<br>Sistema académico moderno y seguro.</p>
                            <div class="mt-4 w-12 h-1.5 bg-danger rounded-full"></div>
                        </div>

                        <!-- Slide 2: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide2.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-10 left-0 right-0 px-8 text-center">
                                <h3 class="text-white font-black text-2xl mb-2">Gestión Estudiantil</h3>
                                <p class="text-white/80 text-sm leading-relaxed">Control total de inscripciones y calificaciones.</p>
                            </div>
                        </div>

                        <!-- Slide 3: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide3.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-10 left-0 right-0 px-8 text-center">
                                <h3 class="text-white font-black text-2xl mb-2">Portal Docente</h3>
                                <p class="text-white/80 text-sm leading-relaxed">Registro de notas y gestión de secciones.</p>
                            </div>
                        </div>

                        <!-- Slide 4: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide4.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-10 left-0 right-0 px-8 text-center">
                                <h3 class="text-white font-black text-2xl mb-2">Área de Representantes</h3>
                                <p class="text-white/80 text-sm leading-relaxed">Seguimiento académico 24/7 de sus representados.</p>
                            </div>
                        </div>

                        <!-- Slide 5: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide5.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-10 left-0 right-0 px-8 text-center">
                                <h3 class="text-white font-black text-2xl mb-2">Años Escolares</h3>
                                <p class="text-white/80 text-sm leading-relaxed">Planificación y control del calendario académico.</p>
                            </div>
                        </div>

                        <!-- Slide 6: Foto institucional -->
                        <div class="carousel-slide absolute inset-0 transition-opacity duration-700" style="opacity:0;">
                            <img src="{{ asset('assets/slide6.jpeg') }}" alt="Institucional" class="w-full h-full object-cover">
                            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(26,35,126,0.85) 0%, rgba(26,35,126,0.3) 50%, transparent 100%);"></div>
                            <div class="absolute bottom-10 left-0 right-0 px-8 text-center">
                                <h3 class="text-white font-black text-2xl mb-2">Comunidad Escolar</h3>
                                <p class="text-white/80 text-sm leading-relaxed">Unidos por la educación de calidad.</p>
                            </div>
                        </div>

                    </div>
                    <!-- Dots -->
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                        <button class="carousel-dot w-2 h-2 rounded-full bg-white transition-all" data-index="0"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-white/30 transition-all" data-index="1"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-white/30 transition-all" data-index="2"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-white/30 transition-all" data-index="3"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-white/30 transition-all" data-index="4"></button>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-white/30 transition-all" data-index="5"></button>
                    </div>
                    <!-- Línea roja decorativa inferior izquierda -->
                    <div class="absolute bottom-0 left-0 right-0 h-1.5" style="background:linear-gradient(to right,#D32F2F,transparent)"></div>
                </div>

                <!-- ── Derecha: Formulario ── -->
                <div class="w-full md:w-1/2 px-12 py-6 flex flex-col justify-center bg-white">
                    <!-- Header del form -->
                    <div class="mb-5">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-[0.25em] mb-1">Bienvenido de vuelta</p>
                        <h2 class="text-3xl font-black text-primary leading-tight">Iniciar Sesión</h2>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded-xl border-l-4 border-danger bg-red-50 text-sm text-danger font-semibold">
                            @foreach ($errors->all() as $error)
                                <div class="flex items-center gap-2"><span class="text-danger font-black">•</span> {{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                        @csrf
                        <!-- Cédula -->
                        <div>
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-1">Cédula de Identidad</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                                <input type="number" name="cedula" placeholder="ej. 12345678" value="{{ old('cedula') }}" required
                                    class="w-full bg-gray-50 border-2 border-transparent rounded-xl py-2.5 pl-11 pr-4 text-base font-medium text-gray-800 outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10">
                            </div>
                        </div>
                        <!-- Contraseña -->
                        <div>
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-1">Contraseña</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></span>
                                <input type="password" id="pwd2" name="password" placeholder="••••••••" required
                                    class="w-full bg-gray-50 border-2 border-transparent rounded-xl py-2.5 pl-11 pr-12 text-base font-medium text-gray-800 outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10">
                                <button type="button" onclick="togglePwd2()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors">
                                    <svg id="eye2-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eye2-off" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.012-2.125M12 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.012 2.125M1 1l22 22"/></svg>
                                </button>
                            </div>
                        </div>
                        <!-- Captcha -->
                        <div>
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest flex items-center justify-between mb-1">
                                Verificación Humana
                                <button type="button" onclick="document.getElementById('cap2').src='{{ route('captcha.image') }}?'+Math.random()" class="text-gold hover:underline text-[10px] font-bold">↺ Actualizar</button>
                            </label>
                            <div class="flex gap-3 items-center">
                                <div class="bg-gray-50 border-2 border-transparent rounded-xl p-2 flex justify-center w-1/3">
                                    <img id="cap2" src="{{ route('captcha.image') }}?v={{ time() }}" alt="Captcha" class="h-9 rounded cursor-pointer" onclick="this.src='{{ route('captcha.image') }}?'+Math.random()">
                                </div>
                                <input type="text" name="captcha" placeholder="Código" required
                                    class="w-2/3 bg-gray-50 border-2 border-transparent rounded-xl py-2.5 text-lg font-bold text-gray-800 text-center tracking-[.3em] uppercase outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10">
                            </div>
                        </div>
                        <!-- Remember / Forgot -->
                        <div class="flex justify-between items-center text-sm pt-1">
                            <label class="flex items-center gap-2 text-gray-500 cursor-pointer font-medium">
                                <input type="checkbox" name="remember" class="accent-gold w-4 h-4 rounded"> Recordarme
                            </label>
                            <a href="#" x-data @click.prevent="$dispatch('open-recovery')" class="text-danger hover:underline font-semibold">¿Olvidaste tu acceso?</a>
                        </div>
                        <!-- Botón Submit -->
                        <button type="submit"
                            class="w-full font-bold text-base py-3.5 mt-2 rounded-xl flex items-center justify-center gap-2 group transition-all active:scale-[.98]"
                            style="background:linear-gradient(135deg,#FBC02D,#F9A825); color:#1A237E; box-shadow:0 4px 15px rgba(251,192,45,.3);"
                            onmouseover="this.style.filter='brightness(1.06)';this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.filter='';this.style.transform=''">
                            Entrar al Sistema
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </form>

                    <!-- Divisor -->
                    <div class="flex items-center gap-4 my-4">
                        <div class="flex-1 h-px bg-gray-100"></div>
                        <span class="text-xs text-gray-400 font-semibold uppercase tracking-widest">o</span>
                        <div class="flex-1 h-px bg-gray-100"></div>
                    </div>

                    <!-- Registro -->
                    <a href="{{ route('register.representative') }}"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl border-2 border-gray-100 text-[15px] font-bold text-gray-500 hover:border-primary/20 hover:text-primary hover:bg-gray-50 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Registrarme como Representante
                    </a>
                    <!-- Detalle rojo decorativo -->
                    <div class="mt-4 flex items-center justify-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Conexión segura — GestiChalbaud</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- ═══════════════════════════════ LOGO CLOUD ═══════════════════════════════ -->
    <section class="bg-[#0d1547] py-16 border-t border-white/5">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <p class="text-white/25 text-sm font-bold uppercase tracking-[0.3em] mb-12">Construido con tecnología de primer nivel</p>
            <div class="flex flex-wrap justify-center gap-x-16 gap-y-8 items-center">
                @foreach([
                    ['PHP 8.3',    'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['Laravel 12', 'M4 6h16M4 10h16M4 14h16M4 18h16'],
                    ['MySQL 8',    'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4'],
                    ['Tailwind 4', 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
            ] as [$name, $path])
                    <div class="logo-cloud-item flex items-center gap-3 text-white">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $path }}"/></svg>
                        <span class="text-lg font-black">{{ $name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════ FEATURES ═══════════════════════════════ -->
    <section class="bg-[#0d1547] py-24">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-20">
                <p class="text-gold text-base font-bold uppercase tracking-widest mb-4">Módulos del Sistema</p>
                <h2 class="text-5xl font-black text-white leading-tight">Todo lo que necesitas,<br><span class="text-white/50">en un solo portal.</span></h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach([
                    ['<svg class="w-14 h-14" fill="#D32F2F" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 295.784 295.784" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M240.109,36.813h-0.14c3.376-2.322,2.584-5.222-2.408-7.003L158.093,1.527c-5.898-2.104-15.426-2.022-21.285,0.179 L55.087,32.451c-5.859,2.211-5.875,5.815-0.037,8.07L84.269,51.77v8.109c0,4.797,2.757,10.079,6.628,13.414 c-1.771,3.601-3.12,7.785-4.153,11.702c-2.296,8.687-3.436,19.656,5.445,22.287c0,0,16.104-3.689,25.637-12.185 c3.257-2.902,7.425-3.505,10.993-1.002c9.926,6.973,33.034,18.403,75.924,16.15c0,0,2.387-14.338,1.486-28.883 c-0.223-3.653-0.958-7.156-2.051-10.514c2.579-3.177,4.277-7.216,4.277-10.965v-9.512l27.517-11.602v48.581l-4.826,16.096h13.955 l-4.997-16.267V36.813H240.109z"></path> <path d="M65.715,233.124c-0.08,0.762-0.267,1.512-0.334,2.253c-0.58,6.027,1.186,11.744,4.979,16.072 c3.392,3.884,7.353,6.276,11.653,7.524c2.237,0.693,4.572,1.046,6.962,1.134v25.072c0,5.852,4.751,10.604,10.605,10.604h94.677 c5.851,0,10.604-4.753,10.604-10.604v-25.108c0.409,0.021,0.828,0.099,1.228,0.099c7.259,0,13.996-2.486,19.335-8.586 c3.79-4.329,5.556-10.035,4.976-16.067l-0.435-0.047c-0.072-0.834-0.254-1.595-0.398-2.341 c-7.565-44.562-57.12-67.419-60.532-68.941c19.594-7.818,33.74-26.336,35.278-48.335c-3.589,0.168-7.161,0.285-10.615,0.285 c-37.386,0-58.256-10.748-67.378-17.153c-1.929-1.357-4.108-1.046-6.092,0.714c-10.175,9.062-26.688,12.928-27.387,13.093 l-0.826,0.178c0.45,23.535,15.322,43.48,36.141,51.45C121.655,167.279,73.083,190.068,65.715,233.124z M104.225,257.456 c8.859-3.133,17.683-8.652,25.064-14.835c5.626-4.718,6.364-13.086,1.654-18.719c-4.709-5.635-13.095-6.391-18.739-1.688 c-2.449,2.045-5.222,3.981-7.979,5.675v-28.003l15.669,6.758c0.005,0,0.01-0.016,0.016-0.016l28.956,12.5l44.635-19.273v28.936 c-3.236-1.89-6.586-4.174-9.491-6.587c-5.634-4.691-14.022-3.936-18.734,1.688c-4.702,5.639-3.972,14.012,1.651,18.724 c7.799,6.535,17.207,12.309,26.569,15.328v8.725l-44.636,17.461l-44.64-17.461v-9.212H104.225z"></path> </g> </g> </g></svg>','Gestión Estudiantil','Inscripciones, historial académico, calificaciones y control de asistencia en tiempo real.'],
                    ['<svg class="w-14 h-14" fill="#D32F2F" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 279.063 279.063" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M66.968,97.353c-3.928-0.171-8.961,0.212-15.112,1.145c-6.144,0.948-12.078,3.076-17.795,6.405 c-5.727,3.33-10.672,8.285-14.856,14.856c-4.179,6.571-6.273,15.488-6.273,26.761c0,8.704,1.284,16,3.845,21.888 s6.056,10.589,10.493,14.09c4.438,3.495,9.559,5.888,15.366,7.166c5.805,1.284,11.951,1.668,18.442,1.149 c5.465-0.507,10.584-1.62,15.366-3.329c4.777-1.709,9.559-4.438,14.338-8.191c1.701,3.583,3.754,6.488,6.149,8.704 c2.387,2.222,5.287,3.335,8.702,3.335c1.709,0,3.542-0.519,5.504-1.538c1.967-1.025,3.806-2.393,5.509-4.102 c1.706-1.703,3.027-3.536,3.972-5.509c0.938-1.963,1.315-3.879,1.155-5.759l-0.264-0.507c-1.37-6.146-2.302-11.397-2.822-15.752 c-0.507-4.354-0.764-9.093-0.764-14.214c0-3.242,0-6.737,0-10.496c0-3.925,0.085-7.597,0.256-11.014 c0.168-2.222,0.254-4.355,0.254-6.4c0-2.216,0-4.267,0-6.146v-2.045c0-2.563-0.036-5.758-0.122-9.611 c-0.096-3.837-0.642-7.809-1.67-11.904c-1.025-4.096-2.61-8.104-4.735-12.039c-2.136-3.925-5.166-7.425-9.088-10.501 c-3.93-3.071-8.924-5.51-14.98-7.296c-6.066-1.792-13.453-2.429-22.154-1.916c-5.981,0.342-11.617,1.232-16.904,2.688 c-5.289,1.444-10.074,3.407-14.335,5.882c-4.274,2.48-7.858,5.386-10.76,8.71c-2.905,3.329-4.782,7.042-5.631,11.138 c0.166,2.393,0.424,3.754,0.769,4.102c0.854,1.874,1.877,3.93,3.071,6.146c1.199,2.216,2.822,3.324,4.868,3.324h0.253 c1.031,0.166,2.478,0.218,4.355,0.124c1.882-0.083,3.42-0.471,4.614-1.149c0.678-0.342,1.356-0.813,2.045-1.408 c0.678-0.596,1.362-1.16,2.053-1.667c2.046-1.708,4.813-3.583,8.319-5.628c3.495-2.051,9.087-3.33,16.777-3.848 c4.777-0.171,8.658,0.254,11.654,1.279c2.98,1.025,5.287,2.434,6.916,4.225c1.623,1.792,2.726,3.801,3.324,6.017 c0.593,2.216,0.896,4.354,0.896,6.4c-2.39-0.854-5.248-1.574-8.572-2.175C76.055,98.161,71.916,97.695,66.968,97.353z M87.711,130.897c0.339,0.86,0.593,1.921,0.774,3.206c0.163,1.273,0.254,2.688,0.254,4.225c0,2.729-0.35,5.67-1.028,8.839 c-0.684,3.158-2.136,6.188-4.355,9.087c-2.91,2.222-6.188,4.096-9.862,5.629c-3.674,1.538-7.894,2.31-12.673,2.31 c-6.149,0-10.97-1.667-14.465-4.997c-3.503-3.324-5.253-7.809-5.253-13.442c0-5.805,2.646-10.884,7.943-15.239 c5.292-4.35,12.205-6.524,20.744-6.524c3.588,0,6.866,0.684,9.856,2.045C82.631,127.402,85.314,129.022,87.711,130.897z"></path> <path d="M133.83,128.479c1.025,0.684,1.993,1.15,2.913,1.424c0.764,0.932,1.742,1.548,2.931,1.864 c1.193,0.306,2.41,0.384,3.685,0.208c1.247-0.176,2.495-0.57,3.748-1.207c1.238-0.616,2.227-1.435,2.973-2.429 c1.854,3.791,4.412,6.902,7.685,9.326c3.272,2.423,7.368,4.737,12.287,6.948c6.281,2.812,12.671,4.205,19.19,4.174 c6.52-0.025,12.733-1.273,18.646-3.723c5.893-2.455,11.288-6.048,16.156-10.822c4.877-4.759,8.766-10.403,11.691-16.928l2.154-4.8 c2.982-6.639,4.547-13.188,4.702-19.63c0.145-6.432-0.86-12.423-3.024-17.979c-2.159-5.562-5.406-10.444-9.714-14.675 c-4.318-4.221-9.414-7.358-15.296-9.409c-4.102-1.403-8.161-2.071-12.169-2.024c-4.019,0.057-7.399,0.342-10.159,0.886 c0.543-1.543,1.139-3.04,1.802-4.521c0.663-1.476,1.352-3.009,2.076-4.614l5.629-12.557c0.994-2.216,2.206-4.66,3.63-7.348 c1.424-2.683,2.65-4.836,3.671-6.441c0.564-0.927,1.243-1.958,2.046-3.076c0.191-1.098-0.037-2.314-0.699-3.646 c-0.663-1.325-1.367-2.304-2.113-2.946c-1.134-1.087-2.688-2.087-4.654-2.967c-1.968-0.88-4.106-1.403-6.4-1.543 c-2.305-0.14-4.614,0.3-6.918,1.331c-2.325,1.031-4.252,2.967-5.826,5.815c-0.963,1.491-2.454,4.22-4.442,8.202 c-2.015,3.977-4.277,8.616-6.794,13.908c-2.531,5.298-5.209,11.02-8.025,17.14c-2.828,6.131-5.557,12.148-8.208,18.046 c-1.103,2.476-2.501,5.422-4.199,8.881c-1.698,3.454-3.526,7.031-5.479,10.739c-2.185,4.199-4.308,8.373-6.4,12.531 c-2.092,4.168-3.853,7.845-5.289,11.05c-0.663,1.476-1.204,2.744-1.608,3.827c-0.404,1.077-0.769,2.133-1.087,3.169 c-1.341,2.651-2.071,4.877-2.216,6.659c-0.132,1.792,0.096,3.262,0.683,4.417C131.997,126.884,132.797,127.801,133.83,128.479z M173.277,89.969l0.502-1.114c1.269-2.822,2.641-5.39,4.106-7.694c1.48-2.289,3.076-4.531,4.795-6.727 c4.137-1.968,8.182-3.313,12.102-3.987c3.93-0.678,7.679-0.223,11.246,1.383c3.946,1.761,7.095,4.516,9.497,8.249 c2.397,3.733,2.858,8.373,1.403,13.934c0.202,0.528,0.228,1.362,0.104,2.475c-0.14,1.124-0.398,2.377-0.792,3.749 c-0.389,1.388-0.927,2.812-1.585,4.277c-0.668,1.48-1.273,2.837-1.828,4.064c-3.5,7.151-8.29,12.138-14.354,14.955 c-6.068,2.822-12.241,2.833-18.521,0.016c-3.206-1.45-5.66-3.615-7.379-6.53c-1.729-2.915-3.314-5.888-4.77-8.906l-0.099-0.259 C168.596,102.205,170.471,96.24,173.277,89.969z"></path> <path d="M218.197,191.864c-5.629,1.543-10.973,4.044-16.036,7.534c-5.055,3.49-9.409,7.871-13.07,13.137 c-3.646,5.256-6.219,10.693-7.674,16.316c-1.46,5.623-1.822,11.138-1.062,16.528c0.73,5.396,2.584,10.485,5.546,15.266 c2.946,4.784,7.068,8.87,12.392,12.271c3.298,2.123,6.907,3.708,10.827,4.743c3.936,1.041,7.736,1.497,11.443,1.388 c3.697-0.124,7.001-0.885,9.9-2.299c2.9-1.408,4.987-3.511,6.256-6.275c0.74-1.528,0.974-3.227,0.704-5.085 c-0.28-1.875-1.201-3.418-2.786-4.655c-0.839-0.652-1.693-1.057-2.553-1.212c-0.864-0.14-2.17-0.145-3.93-0.016 c-3.014,0.197-6.173,0.099-9.471-0.29c-3.299-0.398-6.002-1.273-8.104-2.609c-2.682-1.952-4.737-4.23-6.162-6.835 c-1.424-2.615-2.227-5.422-2.423-8.44c-0.218-3.014,0.232-6.131,1.315-9.337c1.077-3.215,2.827-6.358,5.229-9.444 c2.04-2.61,4.453-4.977,7.234-7.089c2.77-2.112,5.607-3.707,8.455-4.774c2.869-1.071,5.649-1.6,8.357-1.6 c2.693,0.01,5.075,0.808,7.125,2.413c1.87,1.455,3.087,3.313,3.661,5.566c0.575,2.247,0.896,4.676,0.958,7.291 c-0.735,1.527-0.673,3.075,0.197,4.654c0.869,1.58,1.864,2.812,2.998,3.692c0.367,0.29,1.201,0.564,2.49,0.823 c1.305,0.264,2.745,0.378,4.355,0.347c1.595-0.025,3.169-0.259,4.722-0.704c1.549-0.435,2.838-1.232,3.879-2.377 c1.807-1.9,2.827-4.37,3.086-7.399c0.265-3.029-0.187-6.198-1.356-9.518c-1.181-3.318-2.962-6.555-5.386-9.719 c-2.418-3.164-5.354-5.883-8.844-8.155c-4.681-3.056-9.828-4.889-15.421-5.51C229.454,189.871,223.826,190.326,218.197,191.864z"></path> <path d="M141.763,190.947c-0.253-0.03-0.538-0.041-0.849-0.041c-0.948,0-1.897,0-2.846,0c-0.95,0-1.851,0.108-2.705,0.326 c-0.854,0.223-1.611,0.632-2.273,1.232c-0.663,0.601-1.188,1.507-1.559,2.703c-0.318,0.502-0.56,0.932-0.717,1.284 c-0.158,0.342-0.176,0.839-0.046,1.46c0.375,1.522,1.022,2.548,1.944,3.086c0.911,0.544,2.037,0.809,3.363,0.809 c0.885,0,1.737,0,2.561,0c0.88,0,1.766-0.031,2.651-0.094c0.885-0.062,1.739-0.108,2.558-0.14 c0.818-0.026,1.678-0.047,2.558-0.047h1.326v5.313c0,0.186,0,0.439,0,0.75c0,0.259-0.026,0.544-0.094,0.854 c-0.062,0.508-0.113,1.03-0.145,1.563c-0.031,0.539-0.042,1.15-0.042,1.85c0,1.703,0.265,3.257,0.798,4.649 c0.538,1.393,1.662,2.247,3.366,2.558c1.838,0.192,3.448-0.238,4.836-1.284c1.393-1.04,2.087-2.511,2.087-4.411 c0-1.14-0.016-2.289-0.042-3.459c-0.036-1.17-0.088-2.289-0.145-3.366c-0.062-0.886-0.099-1.703-0.099-2.465 c0-0.756,0-1.548,0-2.371h7.586c1.077,0,2.02-0.031,2.849-0.099c0.812-0.057,1.574-0.238,2.268-0.518 c0.699-0.29,1.325-0.762,1.9-1.424c0.569-0.663,1.103-1.596,1.61-2.791l0.099-0.099c0.502-0.886,0.761-1.74,0.761-2.558 c0-0.57-0.129-1.062-0.383-1.477c-0.254-0.408-0.564-0.725-0.948-0.947c-0.383-0.223-0.787-0.394-1.232-0.523 c-0.445-0.124-0.849-0.223-1.231-0.284c-0.508-0.062-1.062-0.104-1.657-0.14c-0.601-0.031-1.284-0.047-2.04-0.047 c-1.016,0-2.082,0.016-3.221,0.047c-1.14,0.036-2.248,0.082-3.314,0.14c-0.513,0.067-1.015,0.093-1.522,0.093 c-0.435,0-0.891,0-1.325,0c0.057-0.502,0.077-0.688,0.052-0.564c-0.042,0.125-0.052-0.062-0.052-0.569v-5.494 c0-0.124,0.01-0.326,0.052-0.61c0.025-0.291,0.041-0.523,0.041-0.715c0.062-0.818,0.083-1.734,0.047-2.744 c-0.031-1.016-0.16-1.963-0.378-2.849s-0.622-1.626-1.187-2.227c-0.563-0.601-1.393-0.896-2.464-0.896 c-0.513-0.197-1.187-0.316-2.04-0.383c-0.85-0.062-1.471-0.062-1.85,0c-1.128,0.191-1.895,0.684-2.272,1.47 c-0.378,0.798-0.606,1.595-0.668,2.424c-0.058,0.502-0.114,1.056-0.146,1.657c-0.031,0.601-0.041,1.284-0.041,2.04 c0,1.015,0.021,2.081,0.041,3.221c0.031,1.134,0.088,2.242,0.146,3.313c0,0.321,0.016,0.793,0.052,1.43 c0.031,0.632,0.052,1.103,0.052,1.419h-5.313C142.271,190.994,142.017,190.984,141.763,190.947z"></path> </g> </g> </g></svg>','Portal Docente','Asignación de secciones, registro de notas y comunicación directa con representantes.'],
                    ['<svg class="w-14 h-14" fill="#D32F2F" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 503.303 503.303" xml:space="preserve" stroke="#D32F2F"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M317.684,135.826c18.111,0,34.726-11.714,40.791-28.789c6.133-17.265,0.432-37.017-13.901-48.398 c-14.353-11.397-34.982-12.409-50.374-2.447c-15.346,9.933-22.909,28.971-18.541,46.731c4.31,17.514,19.624,30.839,37.565,32.675 C314.705,135.751,316.194,135.826,317.684,135.826z"></path> <path d="M450.886,243.572c8.98,0,17.565-4.444,22.766-11.765c5.273-7.424,6.576-17.219,3.435-25.769 c-3.054-8.31-10.088-14.831-18.609-17.234c-8.757-2.47-18.39-0.438-25.392,5.369c-6.956,5.767-10.729,14.719-10.018,23.726 c0.705,8.898,5.792,17.077,13.433,21.683C440.827,242.191,445.835,243.572,450.886,243.572z"></path> <path d="M503.282,332.769c-0.021-3.182,0.063-6.366-0.112-9.545c-0.179-3.244-0.488-6.479-0.765-9.718 c-0.546-6.404-2.084-12.688-3.602-18.916c-0.76-3.11-1.91-6.261-3.079-9.242c-1.164-2.971-2.521-5.896-4.076-8.682 c-3.083-5.521-6.577-10.664-11.011-15.206c-2.941-3.014-6.211-5.62-9.632-8.059c-2.679-1.908-5.469-3.677-8.477-5.022 c-3.469-1.551-7.208-2.508-11.021-2.532c-4.657-0.029-9.188,1.351-13.244,3.588c-3.443,1.899-6.359,4.429-9.377,6.917 c-3.12,2.57-6.34,5.019-9.648,7.342c-6.038,4.241-12.834,8.857-20.249,10.136c0.132-13.097-0.249-26.291-2.229-39.253 c-0.973-6.36-2.005-12.801-3.654-19.025c-1.626-6.139-3.543-12.141-5.854-18.061c-4.339-11.114-10.012-21.553-17.612-30.782 c-6.819-8.279-14.945-15.516-24.202-20.967c-10.021-5.9-21.427-9.503-33.146-8.285c-11.331,1.178-21.236,7.098-30.146,13.863 c-12.393,9.41-22.752,21.518-29.917,35.344c-7.488-18.776-20.322-35.944-37.94-46.263c-9.682-5.67-20.917-9.606-32.213-9.762 c-11.894-0.165-23.754,3.942-33.892,9.972c-20.059,11.931-33.539,32.2-40.536,54.127c-7.348,23.023-8.255,47.768-6.295,71.697 c0.192,2.35,0.414,4.696,0.664,7.041c-5.769-1.399-11.159-4.537-16.117-7.7c-5.278-3.367-10.556-6.972-15.211-11.166 c-3.89-3.504-8.604-6.083-13.825-6.839c-4.061-0.589-8.188-0.068-12.092,1.132c-21.281,6.546-35.32,26.732-40.576,47.558 c-1.581,6.266-2.846,12.543-3.445,18.985C0.145,321.97,0.047,328.449,0,334.998c-0.018,2.483,0.375,4.708,2.061,6.639 c1.492,1.709,3.652,2.782,5.927,2.862c4.659,0.163,8.122-3.533,8.57-7.97c0.563-5.568,1.054-11.124,2.06-16.633 c1.04-5.693,2.509-11.18,4.406-16.645c-0.538,9.938-1.076,19.876-1.614,29.814c-0.068,1.265-0.137,2.528-0.205,3.794 c-0.142,2.62,0.056,5.262,0.568,7.833c0.279,1.398,0.653,2.779,1.12,4.128c0.234,0.675,0.491,1.342,0.771,2 c0.222,0.521,0.882,1.399,0.91,1.947c0.091,1.76,0.183,3.52,0.273,5.279c0.547,10.563,1.094,21.125,1.641,31.688 c0.655,12.646,1.31,25.291,1.965,37.938c0.226,4.362,0.451,8.727,0.678,13.09c0.1,1.935,0.04,3.973,0.373,5.89 c0.777,4.475,4.792,7.974,9.343,8.071c4.606,0.098,8.779-3.315,9.673-7.822c0.459-2.312,0.337-4.836,0.45-7.176 c0.561-11.592,1.122-23.186,1.683-34.777c0.491-10.146,0.982-20.291,1.474-30.438c0.032-0.659,0.063-1.318,0.096-1.979 c0.38,7.338,0.76,14.676,1.14,22.014c0.652,12.6,1.305,25.197,1.957,37.797c0.2,3.858,0.4,7.718,0.6,11.575 c0.033,0.64,0.051,1.281,0.104,1.918c0.385,4.53,4.106,8.34,8.625,8.834c4.594,0.505,9.021-2.556,10.304-6.973 c0.302-1.041,0.358-2.083,0.41-3.151c0.146-3.029,0.293-6.059,0.439-9.089c0.568-11.751,1.138-23.503,1.706-35.254 c0.575-11.886,1.15-23.771,1.726-35.657c0.154-3.196,0.31-6.395,0.464-9.592c0.037-0.77,0.02-1.177,0.379-1.859 c2.618-4.97,3.845-10.612,3.541-16.221c-0.498-9.199-0.996-18.398-1.494-27.598c-0.401-7.409-0.803-14.818-1.204-22.228 c5.635,2.257,11.787,4.303,17.862,4.881c3.08,0.293,6.302,0.349,9.347-0.284c1.414-0.294,2.853-0.698,4.185-1.262 c0.343-0.145,0.677-0.309,1.007-0.479c0.277,0.042,0.556,0.075,0.835,0.099c0.638,0.055,1.28,0.059,1.919,0.014 c4.426-0.313,8.48-3.046,10.511-6.978c1.072-2.075,1.462-4.32,1.364-6.639c-0.128-3.059-0.211-6.118-0.239-9.179 c-0.114-12.078,0.601-24.202,2.746-36.102c2.156-11.958,5.701-23.863,11.647-34.512c-0.845,6.979-1.69,13.958-2.535,20.937 c-1.538,12.696-3.075,25.392-4.613,38.088c-0.561,4.629-1.121,9.259-1.682,13.888c-0.386,3.187-0.823,6.354-0.862,9.571 c-0.069,5.695,0.831,11.399,2.673,16.79c0.922,2.698,2.078,5.316,3.456,7.813c0.665,1.206,1.383,2.384,2.148,3.528 c0.186,0.275,0.188,0.181,0.196,0.341c0.069,1.466,0.139,2.933,0.208,4.398c0.401,8.452,0.802,16.904,1.203,25.356 c0.595,12.555,1.19,25.108,1.786,37.664c0.604,12.75,1.209,25.5,1.813,38.25c0.429,9.038,0.857,18.076,1.286,27.115 c0.09,1.905,0.181,3.812,0.271,5.718c0.302,6.362,4.513,12.023,10.669,13.885c9.01,2.725,18.543-4.176,18.963-13.541 c0.07-1.573,0.142-3.146,0.212-4.719c0.44-9.818,0.881-19.636,1.32-29.455c0.584-13.015,1.168-26.03,1.751-39.045 c0.421-9.374,0.841-18.748,1.262-28.121c0.054-1.208,0.108-2.417,0.162-3.626c0.262,5.605,0.522,11.212,0.783,16.818 c0.571,12.259,1.143,24.518,1.713,36.775c0.563,12.071,1.125,24.142,1.688,36.211c0.178,3.827,0.356,7.654,0.534,11.481 c0.104,2.221,0.066,4.542,0.641,6.702c2.436,9.167,13.192,13.938,21.515,9.244c4.783-2.698,7.418-7.585,7.664-12.972 c0.281-6.157,0.563-12.312,0.844-18.47c0.529-11.589,1.059-23.178,1.588-34.769c0.6-13.134,1.199-26.269,1.8-39.401 c0.493-10.792,0.985-21.584,1.479-32.377c0.209-4.563,0.417-9.125,0.625-13.688c0.01-0.196,0.433-0.667,0.541-0.839 c0.355-0.562,0.699-1.129,1.032-1.704c0.721-1.248,1.388-2.527,1.998-3.833c1.181-2.528,2.146-5.157,2.88-7.849 c1.512-5.542,2.03-11.34,1.543-17.063c-0.207-2.434-0.557-4.86-0.851-7.285c-1.367-11.292-2.734-22.585-4.103-33.877 c-1.321-10.913-2.644-21.826-3.965-32.738c-0.173-1.424-0.345-2.849-0.518-4.274c5.556,10.266,8.989,21.555,11.035,33.013 c2.077,11.628,2.88,23.488,2.606,35.287c-0.074,3.212-0.005,6.426,0.026,9.639c-0.072,2.109-0.025,4.113,0.715,6.126 c1.563,4.25,5.374,7.522,9.857,8.275c4.504,0.756,9.178-1.062,12.115-4.529c3.415-4.029,3.167-9.314,3.576-14.291 c0.786-9.553,2.335-19.086,4.522-28.413c2.155-9.189,4.879-18.354,8.89-26.919c0,2.432,0,4.864,0,7.296 c0,0.962,0.146,2.018-0.053,2.967c-0.416,2-0.832,3.999-1.248,5.998c-2.026,9.737-4.052,19.475-6.078,29.212 c-2.664,12.805-5.329,25.609-7.993,38.414c-2.158,10.37-4.314,20.74-6.474,31.11c-0.531,2.554-1.063,5.106-1.594,7.659 c-0.133,0.639-0.279,1.271-0.33,1.922c-0.381,4.877,3.604,9.013,8.442,9.013c5.256,0,15.517,0,15.517,0l2.515,0.025 c0,0,0.071,1.627,0.107,2.367c0.441,9.153,0.886,18.309,1.327,27.461c0.628,12.962,1.251,25.925,1.883,38.886 c0.417,8.625,0.834,17.249,1.252,25.873c0.024,0.523,0.05,1.048,0.075,1.571c0.198,4.103,2.199,7.993,5.309,10.653 c3.489,2.986,8.237,4.271,12.744,3.303c4.383-0.941,8.202-3.913,10.261-7.888c1.501-2.901,1.629-5.902,1.791-9.046 c0.198-3.813,0.396-7.625,0.593-11.438c0.635-12.246,1.269-24.49,1.902-36.736c0.602-11.614,1.203-23.229,1.805-34.842 c0.157-3.022,0.312-6.047,0.47-9.069c0.03-0.573-0.185-1.119,0.442-1.119c1.353,0,1.307-0.239,1.36,0.893 c0.39,8.017,0.776,16.031,1.164,24.047c0.625,12.923,1.251,25.848,1.876,38.771c0.47,9.686,0.938,19.371,1.406,29.057 c0.137,2.818,0.153,5.576,1.249,8.249c3.619,8.826,14.769,12.24,22.565,6.629c3.666-2.639,6.062-6.869,6.298-11.396 c0.026-0.439,0.046-0.878,0.067-1.317c0.162-3.131,0.324-6.261,0.486-9.392c0.606-11.707,1.213-23.413,1.818-35.119 c0.631-12.175,1.262-24.351,1.892-36.525c0.192-3.717,0.385-7.433,0.578-11.148c0.042-0.82,0.127-2.708,0.127-2.708 s12.411-0.037,17.521-0.037c5.225,0,9.224-4.875,8.23-10.001c-0.065-0.363-0.146-0.724-0.221-1.084 c-1.474-7.231-2.945-14.462-4.418-21.694c-2.512-12.331-5.022-24.662-7.533-36.993c-2.421-11.888-4.842-23.774-7.263-35.663 c-1.202-5.901-2.403-11.802-3.605-17.703c-0.169-0.832-0.029-1.792-0.029-2.635c0-1.988,0-3.976,0-5.963 c2.464,5.846,4.362,11.851,6.01,17.973c1.695,6.311,3.107,12.682,4.051,19.152c0.938,6.438,1.683,12.875,2.22,19.358 c0.194,2.347,0.291,4.653,1.258,6.842c0.901,2.039,2.346,3.823,4.132,5.154c2.464,1.836,5.552,2.771,8.621,2.543 c0.877-0.065,1.528,0.594,2.304,1.005c0.893,0.475,1.825,0.872,2.781,1.201c2.001,0.688,4.049,1.072,6.159,1.172 c8.529,0.397,17.008-2.376,24.747-5.74l-0.014,18.764l-7.659,32.564c-2.614,11.113-5.229,22.226-7.843,33.339 c-0.616,2.621-2.005,6-0.576,8.546c1.674,2.982,4.789,2.729,7.69,2.729c2.994,0,5.986,0,8.98,0c0.104,0,1.253-0.047,1.257,0.029 c0.013,0.247,0.023,0.494,0.035,0.741c0.066,1.369,0.133,2.738,0.199,4.107c0.252,5.211,0.504,10.421,0.756,15.632 c0.585,12.089,1.17,24.179,1.755,36.269c0.125,2.582-0.021,5.375,0.489,7.925c0.893,4.455,4.986,7.86,9.544,7.838 c4.604-0.023,8.695-3.534,9.473-8.063c0.437-2.535,0.338-5.238,0.471-7.798c0.288-5.563,0.576-11.129,0.863-16.693 c0.607-11.739,1.217-23.479,1.823-35.219c0.07-1.348,0.141-2.697,0.21-4.045c0.013-0.234,0.024-0.469,0.036-0.703 c-0.003,0.06,0.958-0.25,0.976,0.098c0.142,2.917,0.281,5.834,0.424,8.751c0.598,12.365,1.195,24.73,1.795,37.096 c0.229,4.75,0.46,9.499,0.688,14.249c0.048,0.989,0.097,1.978,0.145,2.966c0.16,3.31,1.981,6.345,4.842,8.031 c4.021,2.372,9.211,1.404,12.206-2.147c1.521-1.802,2.181-3.965,2.301-6.277c0.183-3.514,0.363-7.025,0.546-10.538 c0.647-12.502,1.295-25.004,1.941-37.506c0.223-4.29,0.444-8.578,0.667-12.865c0.005-0.101,0.077-1.876,0.102-1.876 c2.382,0,4.766,0,7.147,0c2.427,0,5.703,0.515,7.811-0.943c2.039-1.41,2.564-3.771,2.064-6.056 c-0.697-3.188-1.395-6.376-2.091-9.564c-2.649-12.117-5.3-24.235-7.948-36.352c-1.77-8.085-5.516-24.414-5.516-24.414l0.018-6.208 c5.259,11.762,6.726,24.753,8.002,37.428c0.213,2.112,0.717,4.016,2.177,5.636c1.506,1.671,3.667,2.708,5.92,2.78 c4.588,0.146,8.54-3.75,8.516-8.329C503.295,335.032,503.288,333.9,503.282,332.769z"></path> <path d="M52.407,243.572c8.981,0,17.567-4.443,22.768-11.765c5.272-7.424,6.575-17.219,3.434-25.769 c-3.054-8.31-10.087-14.831-18.61-17.234c-8.757-2.47-18.388-0.438-25.39,5.369c-6.956,5.767-10.73,14.719-10.018,23.726 c0.705,8.898,5.792,17.077,13.433,21.683C42.35,242.191,47.357,243.572,52.407,243.572z"></path> <path d="M181.604,128.256c18.138,0,34.726-11.88,40.545-29.067c5.881-17.37-0.267-37.044-14.935-48.018 c-14.693-10.993-35.415-11.25-50.374-0.619c-14.929,10.609-21.55,30.111-16.112,47.617c5.374,17.304,21.642,29.604,39.75,30.072 C180.854,128.252,181.229,128.256,181.604,128.256z"></path> </g> </g> </g></svg>','Representantes','Seguimiento académico de sus representados con acceso seguro y notificaciones.'],
                ] as [$icon, $title, $desc])
                    <div class="feat-card p-10">
                        <div class="mb-6 flex justify-start">{!! $icon !!}</div>
                        <h3 class="text-white font-black text-xl mb-3">{{ $title }}</h3>
                        <p class="text-white/50 text-base leading-relaxed">{{ $desc }}</p>
                        <!-- Línea roja decorativa inferior -->
                        <div class="mt-6 h-1 w-10 bg-danger/60 rounded-full"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    <!-- ═══════════════════════════════ FOOTER ═══════════════════════════════ -->
    <footer class="bg-[#080e2e] border-t border-white/5 py-16">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <img src="{{ asset('assets/logo.jpeg') }}" alt="Logo" class="w-20 h-20 object-cover rounded-full mx-auto mb-5 ring-4 ring-white/10" onerror="this.style.display='none'">
            <p class="text-white font-black text-3xl mb-3 tracking-wide">GESTICHALBAUD</p>
            <!-- Línea roja decorativa -->
            <div class="w-12 h-1.5 bg-danger mx-auto mb-5 rounded-full"></div>
            <p class="text-white/50 text-lg mb-2 font-bold">U.E.B. Coronel Carlos Delgado Chalbaud</p>
            <p class="text-white/30 text-base mb-10 font-medium">Laravel 12 · MySQL 8 · Laragon</p>
            <div class="flex justify-center gap-10 text-white/40 text-lg mb-10 font-bold">
                <a href="#" class="hover:text-danger transition-colors">Privacidad</a>
                <a href="#" class="hover:text-danger transition-colors">Términos</a>
                <a href="#" class="hover:text-danger transition-colors">Soporte</a>
            </div>
            <p class="text-white/20 text-sm uppercase tracking-[0.2em] font-black">© 2026 Todos los derechos reservados.</p>
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

        // ── Menú Móvil (Hamburger) ──
        const btnMenu = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('menu-icon-open');
        const iconClose = document.getElementById('menu-icon-close');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        function toggleMenu() {
            mobileMenu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        }

        if (btnMenu) {
            btnMenu.addEventListener('click', toggleMenu);
        }

        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (!mobileMenu.classList.contains('hidden')) {
                    toggleMenu();
                }
            });
        });

        // ── AlpineJS: Password Recovery Modal ──
        function passwordRecovery() {
            return {
                showModal: false,
                step: 1,
                cedula: '',
                question: '',
                answer: '',
                password: '',
                password_confirmation: '',
                loading: false,
                errorMessage: '',

                resetData() {
                    this.cedula = '';
                    this.question = '';
                    this.answer = '';
                    this.password = '';
                    this.password_confirmation = '';
                    this.errorMessage = '';
                },

                closeModal() {
                    this.showModal = false;
                    setTimeout(() => this.resetData(), 300);
                },

                async getQuestion() {
                    this.loading = true;
                    this.errorMessage = '';
                    try {
                        const response = await fetch('{{ route("password.recovery.question") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ cedula: this.cedula })
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Error al obtener pregunta');
                        
                        this.question = data.question;
                        this.step = 2;
                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async verifyAnswer() {
                    this.loading = true;
                    this.errorMessage = '';
                    try {
                        const response = await fetch('{{ route("password.recovery.verify") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ cedula: this.cedula, answer: this.answer })
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Respuesta incorrecta');
                        
                        this.step = 3;
                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async resetPassword() {
                    this.loading = true;
                    this.errorMessage = '';
                    try {
                        const response = await fetch('{{ route("password.recovery.reset") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                password: this.password, 
                                password_confirmation: this.password_confirmation 
                            })
                        });
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Error al restablecer la contraseña');
                        
                        this.step = 4;
                    } catch (error) {
                        this.errorMessage = error.message;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>

    <!-- ═══════════════════════════════ RECOVERY MODAL (Alpine.js) ═══════════════════════════════ -->
    <div x-data="passwordRecovery()" 
         x-show="showModal" 
         @open-recovery.window="showModal = true; step = 1; resetData()"
         style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-[#080e2e]/80 backdrop-blur-sm transition-opacity" 
             x-show="showModal" 
             x-transition.opacity 
             @click="closeModal()"></div>

        <!-- Modal Content -->
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md relative z-10 overflow-hidden"
             x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-black text-primary">Recuperación de Acceso</h3>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Verificación de Identidad</p>
                </div>
                <button @click="closeModal()" class="text-gray-400 hover:text-danger transition-colors p-2 rounded-full hover:bg-red-50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8">
                
                <!-- Alerta de Error General -->
                <div x-show="errorMessage" x-text="errorMessage" x-transition class="mb-6 p-4 rounded-xl border-l-4 border-danger bg-red-50 text-sm text-danger font-semibold"></div>

                <!-- STEP 1: Cédula -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-2">Ingresa tu Cédula</label>
                    <input type="number" x-model="cedula" @keydown.enter="getQuestion()" placeholder="ej. 12345678" 
                           class="w-full bg-gray-50 border-2 border-transparent rounded-xl py-3 px-4 text-base font-medium text-gray-800 outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10 mb-6">
                    <button @click="getQuestion()" :disabled="loading || !cedula" 
                            class="w-full font-bold text-base py-3.5 rounded-xl flex items-center justify-center gap-2 group transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background:linear-gradient(135deg,#1A237E,#283593); color:white; box-shadow:0 4px 15px rgba(26,35,126,.3);">
                        <span x-show="!loading">Continuar</span>
                        <span x-show="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                        <svg x-show="!loading" class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </button>
                </div>

                <!-- STEP 2: Pregunta de Seguridad -->
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-100">
                        <p class="text-xs font-black text-blue-400 uppercase tracking-widest mb-1">Pregunta de Seguridad</p>
                        <p class="text-base font-bold text-primary" x-text="question"></p>
                    </div>
                    <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-2">Tu Respuesta</label>
                    <input type="text" x-model="answer" @keydown.enter="verifyAnswer()" placeholder="Respuesta secreta" 
                           class="w-full bg-gray-50 border-2 border-transparent rounded-xl py-3 px-4 text-base font-medium text-gray-800 outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10 mb-6">
                    <button @click="verifyAnswer()" :disabled="loading || !answer" 
                            class="w-full font-bold text-base py-3.5 rounded-xl flex items-center justify-center gap-2 group transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background:linear-gradient(135deg,#1A237E,#283593); color:white; box-shadow:0 4px 15px rgba(26,35,126,.3);">
                        <span x-show="!loading">Validar Respuesta</span>
                        <span x-show="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                    </button>
                </div>

                <!-- STEP 3: Nueva Contraseña -->
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-2">Nueva Contraseña</label>
                            <input type="password" x-model="password" placeholder="Min. 8 caracteres" 
                                   class="w-full bg-gray-50 border-2 border-transparent rounded-xl py-3 px-4 text-base font-medium text-gray-800 outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10">
                        </div>
                        <div>
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest block mb-2">Confirmar Contraseña</label>
                            <input type="password" x-model="password_confirmation" @keydown.enter="resetPassword()" placeholder="Min. 8 caracteres" 
                                   class="w-full bg-gray-50 border-2 border-transparent rounded-xl py-3 px-4 text-base font-medium text-gray-800 outline-none transition-all focus:border-gold focus:bg-white focus:ring-4 focus:ring-gold/10">
                        </div>
                    </div>
                    <button @click="resetPassword()" :disabled="loading || !password || password !== password_confirmation" 
                            class="w-full font-bold text-base py-3.5 rounded-xl flex items-center justify-center gap-2 group transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background:linear-gradient(135deg,#D32F2F,#B71C1C); color:white; box-shadow:0 4px 15px rgba(211,47,47,.3);">
                        <span x-show="!loading">Restablecer Contraseña</span>
                        <span x-show="loading" class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></span>
                    </button>
                </div>

                <!-- STEP 4: Éxito -->
                <div x-show="step === 4" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;" class="text-center">
                    <div class="w-16 h-16 rounded-full bg-green-100 text-green-500 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h4 class="text-2xl font-black text-primary mb-2">¡Completado!</h4>
                    <p class="text-gray-500 mb-6">Tu contraseña ha sido actualizada correctamente. Ya puedes iniciar sesión con tu nueva credencial.</p>
                    <button @click="closeModal()" 
                            class="w-full font-bold text-base py-3.5 rounded-xl transition-all border-2 border-gray-200 text-gray-600 hover:border-primary/30 hover:text-primary">
                        Volver al Login
                    </button>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
