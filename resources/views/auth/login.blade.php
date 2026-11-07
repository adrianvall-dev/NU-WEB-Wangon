@extends('layouts.app')

@section('title', 'Login Admin - Organisasi NU Wangon')

@section('content')

<!-- GOOGLE FONTS & TAILWIND UTILITIES -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    est: {
                        bg: '#f9f8f4',
                        card: '#ffffff',
                        cardHover: '#EDF2EE',
                        olive: '#2D4736',
                        oliveDark: '#23382B',
                        dark: '#191E1A',
                        muted: '#636B65',
                        border: '#E7E8E1',
                    }
                },
                fontFamily: {
                    serif: ['"Playfair Display"', 'serif'],
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                }
            }
        }
    }
</script>

<!-- WRAPPER: Diubah dari items-center menjadi items-start + pt-6/10/12 agar posisi naik ke atas -->
<div class="login-wrapper min-h-screen bg-[#f9f8f4] text-[#191E1A] font-sans flex items-start justify-center pt-6 sm:pt-10 lg:pt-12 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-6xl">
        
        <!-- CARD UTAMA -->
        <div class="bg-white border border-[#E7E8E1] rounded-2xl sm:rounded-[2.5rem] lg:rounded-[3rem] shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[620px] lg:min-h-[680px]">
            
            <!-- ================= SISI KIRI: PANEL ILUSTRASI KARAKTER ================= -->
            <div class="lg:col-span-6 bg-gradient-to-br from-[#2D4736] via-[#23382B] to-[#191E1A] p-4 sm:p-12 lg:p-14 relative flex flex-col justify-between overflow-hidden min-h-[260px] sm:min-h-[400px] lg:min-h-full">
                
                <!-- Ambient Glow Background -->
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-[#2D4736]/30 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Header Teks -->
                <div class="text-center relative z-10 space-y-3">
                    <span class="inline-flex items-center gap-2 bg-white/90 backdrop-blur-md text-[#191E1A] text-xs font-semibold px-4 py-1.5 rounded-full shadow-xs">
                        <i class="bi bi-shield-lock-fill text-[#2D4736]"></i> Area Administrator
                    </span>
                    <h2 class="font-est-serif text-3xl sm:text-4xl lg:text-5xl font-normal text-white leading-tight">
                        Selamat Datang!
                    </h2>
                    <p class="text-white/80 text-xs sm:text-sm font-light tracking-wide">
                        Sistem Informasi Organisasi NU Wangon
                    </p>
                </div>

                <!-- ILUSTRASI 5 KARAKTER SVG -->
                <div class="character-box relative z-10 w-full my-auto py-4 flex items-center justify-center">
                    <svg id="interactive-characters" viewBox="0 0 600 320" class="w-full h-auto max-w-[520px] drop-shadow-lg">
                        <defs>
                            <filter id="soft-shadow" x="-10%" y="-10%" width="120%" height="120%">
                                <feDropShadow dx="0" dy="6" stdDeviation="5" flood-opacity="0.25"/>
                            </filter>
                            <filter id="back-shadow" x="-10%" y="-10%" width="120%" height="120%">
                                <feDropShadow dx="0" dy="4" stdDeviation="4" flood-opacity="0.3"/>
                            </filter>
                        </defs>

                        <!-- LAYER BELAKANG -->
                        <g id="char-back-left" filter="url(#back-shadow)" opacity="0.95">
                            <path d="M 20,320 C 20,235 45,220 80,220 C 115,220 140,235 140,320 Z" fill="#2D312E" />
                            <ellipse cx="80" cy="175" rx="30" ry="32" fill="#EADBC8" />
                            <path d="M 50,160 C 50,130 62,125 80,125 C 98,125 110,130 110,160 Z" fill="#1C1E1B" />
                            <circle cx="68" cy="172" r="4.5" fill="#222521" />
                            <circle cx="92" cy="172" r="4.5" fill="#222521" />
                            <circle class="pupil-bl smooth-anim" cx="69" cy="171" r="1.5" fill="#fff" />
                            <circle class="pupil-bl smooth-anim" cx="93" cy="171" r="1.5" fill="#fff" />
                            
                            <path id="mouth-bl-normal" class="smooth-anim" d="M 74,188 Q 80,188 86,188" fill="none" stroke="#7A7E75" stroke-width="2" stroke-linecap="round" />
                            <circle id="mouth-bl-surprised" class="smooth-anim" cx="80" cy="188" r="5" fill="#222521" opacity="0" />
                        </g>

                        <g id="char-back-right" filter="url(#back-shadow)" opacity="0.95">
                            <path d="M 460,320 C 460,230 485,215 520,215 C 555,215 580,230 580,320 Z" fill="#9E7B66" />
                            <path d="M 465,175 C 465,115 495,110 520,110 C 545,110 575,115 575,175 C 575,225 555,245 520,245 C 485,245 465,225 465,175 Z" fill="#C4A482" />
                            <ellipse cx="520" cy="170" rx="30" ry="32" fill="#F3E5D8" />
                            <circle cx="502" cy="180" r="4" fill="#D98880" opacity="0.5" />
                            <circle cx="538" cy="180" r="4" fill="#D98880" opacity="0.5" />
                            
                            <g id="eyes-br-open">
                                <circle cx="508" cy="166" r="5" fill="#222521" />
                                <circle cx="532" cy="166" r="5" fill="#222521" />
                                <circle class="pupil-br smooth-anim" cx="509" cy="165" r="1.8" fill="#fff" />
                                <circle class="pupil-br smooth-anim" cx="533" cy="165" r="1.8" fill="#fff" />
                            </g>
                            
                            <path id="mouth-br-normal" class="smooth-anim" d="M 512,190 Q 520,186 528,190" fill="none" stroke="#6E473B" stroke-width="2" stroke-linecap="round" />
                            <g id="mouth-br-teeth" class="smooth-anim" opacity="0">
                                <path d="M 508,186 Q 520,202 532,186 Z" fill="#8B0000" />
                                <rect x="510" y="186" width="20" height="4" fill="#ffffff" rx="1" />
                            </g>
                        </g>

                        <!-- LAYER DEPAN -->
                        <g id="char-front-left" filter="url(#soft-shadow)">
                            <path d="M 100,320 C 100,225 135,205 180,205 C 225,205 260,225 260,320 Z" fill="#23382B" />
                            <path d="M 105,165 C 105,90 150,80 180,80 C 210,80 255,90 255,165 C 255,225 225,245 180,245 C 135,245 105,225 105,165 Z" fill="#2D4736" />
                            <ellipse cx="180" cy="160" rx="40" ry="42" fill="#F7EBE1" />
                            <circle cx="157" cy="173" r="6" fill="#E8A0BF" opacity="0.5" />
                            <circle cx="203" cy="173" r="6" fill="#E8A0BF" opacity="0.5" />
                            
                            <path id="mouth-fl" class="smooth-anim" d="M 172,183 Q 180,189 188,183" fill="none" stroke="#8C4A4A" stroke-width="2.5" stroke-linecap="round" />

                            <g id="eyes-fl-open" class="smooth-anim">
                                <circle cx="163" cy="157" r="8" fill="#222521" />
                                <circle cx="197" cy="157" r="8" fill="#222521" />
                                <circle class="pupil-fl smooth-anim" cx="165" cy="155" r="2.8" fill="#ffffff" />
                                <circle class="pupil-fl smooth-anim" cx="199" cy="155" r="2.8" fill="#ffffff" />
                            </g>
                            <g id="eyes-fl-closed" class="smooth-anim" opacity="0">
                                <path d="M 155,159 Q 163,149 171,159" fill="none" stroke="#222521" stroke-width="3" stroke-linecap="round" />
                                <path d="M 189,159 Q 197,149 205,159" fill="none" stroke="#222521" stroke-width="3" stroke-linecap="round" />
                            </g>
                            <g id="hands-fl-cover" class="smooth-anim" transform="translate(0, 50)" opacity="0">
                                <ellipse cx="160" cy="160" rx="15" ry="11" fill="#F7EBE1" stroke="#D3B49F" stroke-width="2" />
                                <ellipse cx="200" cy="160" rx="15" ry="11" fill="#F7EBE1" stroke="#D3B49F" stroke-width="2" />
                            </g>
                        </g>

                        <g id="char-front-center" filter="url(#soft-shadow)">
                            <path d="M 245,320 C 245,255 265,240 300,240 C 335,240 355,255 355,320 Z" fill="#4A6B6C" />
                            <ellipse cx="300" cy="205" rx="28" ry="30" fill="#FBEEE0" />
                            <path d="M 272,190 C 272,160 282,155 300,155 C 318,155 328,160 328,190 Z" fill="#222521" />
                            <rect x="272" y="185" width="56" height="7" rx="3" fill="#78A1A3" />
                            <circle cx="283" cy="213" r="4" fill="#E8A0BF" opacity="0.5" />
                            <circle cx="317" cy="213" r="4" fill="#E8A0BF" opacity="0.5" />
                            
                            <g id="fc-normal" class="smooth-anim">
                                <circle cx="288" cy="203" r="4.5" fill="#222521" />
                                <circle cx="312" cy="203" r="4.5" fill="#222521" />
                                <circle class="pupil-fc smooth-anim" cx="289" cy="202" r="1.5" fill="#fff" />
                                <circle class="pupil-fc smooth-anim" cx="313" cy="202" r="1.5" fill="#fff" />
                                <path d="M 294,217 Q 300,221 306,217" fill="none" stroke="#8C4A4A" stroke-width="2" stroke-linecap="round" />
                            </g>
                            <g id="fc-happy" class="smooth-anim" opacity="0">
                                <path d="M 284,203 Q 289,195 294,203" fill="none" stroke="#222521" stroke-width="2.5" stroke-linecap="round" />
                                <path d="M 306,203 Q 311,195 316,203" fill="none" stroke="#222521" stroke-width="2.5" stroke-linecap="round" />
                                <path d="M 292,214 Q 300,227 308,214 Z" fill="#A83232" />
                                <rect x="294" y="214" width="12" height="3" fill="#ffffff" />
                            </g>
                        </g>

                        <g id="char-front-right" filter="url(#soft-shadow)">
                            <path d="M 340,320 C 340,225 375,205 425,205 C 475,205 510,225 510,320 Z" fill="#2C332B" />
                            <ellipse cx="425" cy="160" rx="40" ry="42" fill="#F5E5D6" />
                            <path d="M 385,140 C 385,95 402,90 425,90 C 448,90 465,95 465,140 Z" fill="#1C1E1B" />
                            <rect x="385" y="132" width="80" height="11" rx="4" fill="#2D4736" />
                            <circle cx="402" cy="172" r="6" fill="#E8A0BF" opacity="0.4" />
                            <circle cx="448" cy="172" r="6" fill="#E8A0BF" opacity="0.4" />
                            
                            <path id="mouth-fr-normal" class="smooth-anim" d="M 416,184 Q 425,184 434,184" fill="none" stroke="#8C4A4A" stroke-width="2.5" stroke-linecap="round" />

                            <g id="eyes-fr-open" class="smooth-anim">
                                <circle cx="408" cy="156" r="8" fill="#222521" />
                                <circle cx="442" cy="156" r="8" fill="#222521" />
                                <circle class="pupil-fr smooth-anim" cx="410" cy="154" r="2.5" fill="#ffffff" />
                                <circle class="pupil-fr smooth-anim" cx="444" cy="154" r="2.5" fill="#ffffff" />
                            </g>
                            <g id="eyes-fr-suspicious" class="smooth-anim" opacity="0">
                                <path d="M 400,152 Q 408,158 416,152" fill="none" stroke="#222521" stroke-width="3" stroke-linecap="round" />
                                <path d="M 434,152 Q 442,158 450,152" fill="none" stroke="#222521" stroke-width="3" stroke-linecap="round" />
                            </g>
                        </g>

                    </svg>
                </div>

                <div class="text-white/70 text-xs text-center relative z-10 flex items-center justify-center gap-1.5 font-light">
                    <i class="bi bi-shield-check text-[#2D4736] bg-white/20 rounded-full p-1 text-[10px]"></i> 
                    <span>Keamanan akun Anda adalah prioritas kami.</span>
                </div>
            </div>

            <!-- ================= SISI KANAN: FORM LOGIN ================= -->
            <div class="lg:col-span-6 p-5 sm:p-12 lg:p-16 flex flex-col justify-center bg-[#fcfcfb]">
                
                <div class="max-w-md mx-auto w-full space-y-6">
                    
                    <div class="space-y-2 text-start">
                        <h3 class="font-est-serif text-3xl sm:text-4xl font-bold text-[#191E1A]">
                            Login Admin
                        </h3>
                        <p class="text-xs sm:text-sm text-[#636B65]">
                            Masukkan kredensial akun untuk mengakses *dashboard*
                        </p>
                    </div>

                    <!-- ERROR ALERTS -->
                    @if ($errors->any())
                        <div class="bg-red-500/10 border border-red-500/20 text-red-700 p-4 rounded-2xl text-xs sm:text-sm space-y-1">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- STATUS ALERT -->
                    @if (session('status'))
                        <div class="bg-[#EDF2EE] border border-[#2D4736]/20 text-[#2D4736] p-4 rounded-2xl text-xs sm:text-sm font-medium">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-5">
                        @csrf

                        <!-- EMAIL FIELD -->
                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-semibold text-[#191E1A] uppercase tracking-wider">
                                Alamat Email
                            </label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-[#636B65]">
                                    <i class="bi bi-envelope text-base"></i>
                                </span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                                       class="w-full bg-white border border-[#E7E8E1] rounded-full py-3.5 pl-11 pr-4 text-xs sm:text-sm text-[#191E1A] placeholder-[#636B65]/60 focus:outline-none focus:border-[#2D4736] focus:ring-2 focus:ring-[#2D4736]/20 transition-all shadow-xs" 
                                       placeholder="nama@domain.com" required autofocus>
                            </div>
                        </div>

                        <!-- PASSWORD FIELD -->
                        <div class="space-y-1.5">
                            <label for="password" class="block text-xs font-semibold text-[#191E1A] uppercase tracking-wider">
                                Password
                            </label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-[#636B65]">
                                    <i class="bi bi-key text-base"></i>
                                </span>
                                <input id="password" type="password" name="password" 
                                       class="w-full bg-white border border-[#E7E8E1] rounded-full py-3.5 pl-11 pr-12 text-xs sm:text-sm text-[#191E1A] placeholder-[#636B65]/60 focus:outline-none focus:border-[#2D4736] focus:ring-2 focus:ring-[#2D4736]/20 transition-all shadow-xs" 
                                       placeholder="••••••••" required>
                                
                                <button type="button" id="btnTogglePassword" class="absolute right-4 text-[#636B65] hover:text-[#191E1A] focus:outline-none transition-colors">
                                    <i class="bi bi-eye-slash text-base" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- REMEMBER ME -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="inline-flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox" name="remember" id="remember" 
                                       class="w-4 h-4 rounded border-[#E7E8E1] text-[#2D4736] focus:ring-[#2D4736]/30 accent-[#2D4736] cursor-pointer">
                                <span class="text-xs text-[#636B65] font-medium select-none">Ingat saya</span>
                            </label>
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <button type="submit" class="w-full bg-[#2D4736] hover:bg-[#23382B] text-white py-3.5 px-6 rounded-full font-semibold text-xs sm:text-sm transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 group mt-2">
                            <span>Masuk ke Dashboard</span>
                            <i class="bi bi-arrow-right text-base group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>
</div>

<style>
    body {
        background-color: #f9f8f4 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        color: #191E1A;
    }
    .font-est-serif {
        font-family: 'Playfair Display', serif !important;
    }
    .smooth-anim {
        transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), 
                    transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
                    d 0.35s ease;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const btnTogglePassword = document.getElementById('btnTogglePassword');
    const toggleIcon = document.getElementById('toggleIcon');

    const mouthBlNormal = document.getElementById('mouth-bl-normal');
    const mouthBlSurprised = document.getElementById('mouth-bl-surprised');

    const mouthBrNormal = document.getElementById('mouth-br-normal');
    const mouthBrTeeth = document.getElementById('mouth-br-teeth');

    const eyesFlOpen = document.getElementById('eyes-fl-open');
    const eyesFlClosed = document.getElementById('eyes-fl-closed');
    const handsFlCover = document.getElementById('hands-fl-cover');
    
    const fcNormal = document.getElementById('fc-normal');
    const fcHappy = document.getElementById('fc-happy');

    const eyesFrOpen = document.getElementById('eyes-fr-open');
    const eyesFrSuspicious = document.getElementById('eyes-fr-suspicious');
    const mouthFrNormal = document.getElementById('mouth-fr-normal');

    const allPupils = document.querySelectorAll('.pupil-fl, .pupil-fr, .pupil-fc, .pupil-bl, .pupil-br');

    emailInput.addEventListener('focus', () => {
        resetCharacters();
        allPupils.forEach(p => p.style.transform = 'translate(3px, 1px)');
    });

    emailInput.addEventListener('blur', () => {
        allPupils.forEach(p => p.style.transform = 'translate(0, 0)');
    });

    passwordInput.addEventListener('focus', () => {
        if (passwordInput.type === 'password') {
            setCoverEyesMode();
        }
    });

    passwordInput.addEventListener('blur', () => {
        resetCharacters();
    });

    btnTogglePassword.addEventListener('click', function () {
        const isPassword = passwordInput.type === 'password';
        
        if (isPassword) {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
            setPeekMode();
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
            setCoverEyesMode();
        }
    });

    function setCoverEyesMode() {
        eyesFlOpen.style.opacity = '0';
        eyesFlClosed.style.opacity = '1';
        handsFlCover.style.opacity = '1';
        handsFlCover.style.transform = 'translate(0, 0)';

        mouthBlNormal.style.opacity = '1';
        mouthBlSurprised.style.opacity = '0';

        mouthBrNormal.style.opacity = '1';
        mouthBrTeeth.style.opacity = '0';

        fcNormal.style.opacity = '1';
        fcHappy.style.opacity = '0';

        eyesFrOpen.style.opacity = '1';
        eyesFrSuspicious.style.opacity = '0';
        mouthFrNormal.setAttribute('d', 'M 416,184 Q 425,184 434,184');

        allPupils.forEach(p => p.style.transform = 'translate(-2px, 2px)');
    }

    function setPeekMode() {
        eyesFlOpen.style.opacity = '1';
        eyesFlClosed.style.opacity = '0';
        handsFlCover.style.opacity = '0.35';
        handsFlCover.style.transform = 'translate(0, 22px)';

        mouthBlNormal.style.opacity = '0';
        mouthBlSurprised.style.opacity = '1';

        mouthBrNormal.style.opacity = '0';
        mouthBrTeeth.style.opacity = '1';

        fcNormal.style.opacity = '0';
        fcHappy.style.opacity = '1';

        eyesFrOpen.style.opacity = '0';
        eyesFrSuspicious.style.opacity = '1';
        mouthFrNormal.setAttribute('d', 'M 416,186 Q 425,181 434,186');

        allPupils.forEach(p => p.style.transform = 'translate(0, 0)');
    }

    function resetCharacters() {
        eyesFlOpen.style.opacity = '1';
        eyesFlClosed.style.opacity = '0';
        handsFlCover.style.opacity = '0';
        handsFlCover.style.transform = 'translate(0, 50px)';

        mouthBlNormal.style.opacity = '1';
        mouthBlSurprised.style.opacity = '0';

        mouthBrNormal.style.opacity = '1';
        mouthBrTeeth.style.opacity = '0';

        fcNormal.style.opacity = '1';
        fcHappy.style.opacity = '0';

        eyesFrOpen.style.opacity = '1';
        eyesFrSuspicious.style.opacity = '0';
        mouthFrNormal.setAttribute('d', 'M 416,184 Q 425,184 434,184');

        allPupils.forEach(p => p.style.transform = 'translate(0, 0)');
    }
});
</script>
@endsection