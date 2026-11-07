<!-- SOLID-WEIGHT FOOTER (MATCHING REFERENCE DESIGN PALETTE) -->
<footer class="bg-brand-deep text-brand-lightmatcha border-t-2 border-brand-forest pt-16 pb-12 mt-auto relative z-20 overflow-hidden" id="kontak">
    <!-- Abstract Geometric Background (PixelOS Inspiration - Layer 1: Base Dark Grid & Large Emerald Auras 540-650px) -->
    <div class="absolute inset-0 pixel-grid-blueprint-dark opacity-65 pointer-events-none -z-0"></div>
    <div class="absolute -top-28 right-8 w-[650px] h-[650px] rounded-full bg-gradient-to-bl from-emerald-600/[0.24] via-brand-matcha/[0.28] to-transparent pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>
    <div class="absolute -bottom-24 -left-16 w-[540px] h-[540px] rounded-full bg-emerald-950/70 pointer-events-none blur-3xl -z-0" data-parallax="bg-slow"></div>

    <!-- Layer 2: Oversized Dark Concentric Ring Geometry (620-700px with 6px Stroke) & Textures -->
    <div class="pointer-events-none absolute -bottom-24 -left-28 w-[620px] h-[620px] lg:w-[700px] lg:h-[700px] -z-0 hidden sm:block" data-parallax="bg-slow" data-parallax-rotate="3">
        <div class="ambient-float-slow-1 w-full h-full rounded-full border-[6px] border-brand-lightmatcha/[0.24] flex items-center justify-center p-16 sm:p-20">
            <div class="w-full h-full rounded-full border-4 border-dashed border-emerald-400/[0.28] flex items-center justify-center p-14">
                <div class="w-full h-full rounded-full border-[3px] border-brand-lightmatcha/[0.18] bg-gradient-to-br from-emerald-600/[0.14] via-brand-matcha/[0.10] to-transparent"></div>
            </div>
        </div>
    </div>
    <div class="pointer-events-none absolute top-10 right-16 w-44 h-44 pixel-dot-matrix-light opacity-65 hidden md:block -z-0" data-parallax="fore"></div>
    <div class="pointer-events-none absolute bottom-12 right-1/4 text-brand-lightmatcha/50 text-base font-mono font-bold select-none hidden lg:block -z-0" data-parallax="fore">+</div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 floating-reveal">
        <!-- 4-Column Grid (Mobile 2-Column Adaptation, Desktop 12-Column Locked) -->
        <div class="grid grid-cols-2 lg:grid-cols-12 gap-8 sm:gap-10 pb-12 border-b-2 border-brand-border">
            
            <!-- Col 1: Institutional Identity & Social Media (Full width on mobile, 4 cols on desktop) -->
            <div class="col-span-2 lg:col-span-4 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-brand-forest p-1.5 rounded-xl flex items-center justify-center shrink-0 border-2 border-brand-matcha shadow-solid-sm">
                        <img alt="Logo Resmi MWC NU Wangon" 
                             class="w-full h-full object-contain logo-contrast-subtle" 
                             src="{{ asset('images/landing/logo-nu.png') }}">
                    </div>
                    <div>
                        <span class="font-black text-sm tracking-tight text-white block">ORGANISASI NU WANGON</span>
                        <span class="text-[10px] uppercase tracking-[0.14em] text-brand-lightmatcha/70 font-black block mt-0.5">Majelis Wakil Cabang</span>
                    </div>
                </div>

                <p class="text-xs font-semibold text-brand-lightmatcha/80 leading-relaxed max-w-sm">
                    Wadah resmi pembinaan, syiar dakwah Ahlussunnah wal Jama'ah an-Nahdliyyah, dan khidmah kemaslahatan masyarakat se-Kecamatan Wangon, Kabupaten Banyumas, Jawa Tengah.
                </p>

                <!-- Social Icons with Solid 2px buttons -->
                <div class="flex items-center gap-2 pt-2">
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-xl bg-brand-forest border-2 border-brand-matcha hover:bg-brand-matcha hover:text-white text-brand-lightmatcha flex items-center justify-center text-sm transition-all shadow-solid-sm btn-solid-press text-decoration-none" 
                       title="Instagram NU Wangon">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-xl bg-brand-forest border-2 border-brand-matcha hover:bg-brand-matcha hover:text-white text-brand-lightmatcha flex items-center justify-center text-sm transition-all shadow-solid-sm btn-solid-press text-decoration-none" 
                       title="YouTube NU Wangon">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="{{ $pengaturan->whatsapp_url ?? (isset($pengaturan) && $pengaturan->whatsapp ? 'https://wa.me/'.\App\Models\Pengaturan::normalizeWhatsappNumber($pengaturan->whatsapp) : 'https://wa.me/6281234567890') }}" target="_blank" rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-xl bg-brand-forest border-2 border-brand-matcha hover:bg-brand-matcha hover:text-white text-brand-lightmatcha flex items-center justify-center text-sm transition-all shadow-solid-sm btn-solid-press text-decoration-none" 
                       title="WhatsApp Sekretariat">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" 
                       class="w-9 h-9 rounded-xl bg-brand-forest border-2 border-brand-matcha hover:bg-brand-matcha hover:text-white text-brand-lightmatcha flex items-center justify-center text-sm transition-all shadow-solid-sm btn-solid-press text-decoration-none" 
                       title="Facebook NU Wangon">
                        <i class="bi bi-facebook"></i>
                    </a>
                </div>

                <div class="text-[11px] font-semibold text-brand-lightmatcha/70 pt-1">
                    SK Kemenkumham: <span class="font-mono text-white font-bold">AHU-119.AH.01.08.Tahun 2013</span>
                </div>
            </div>

            <!-- Col 2: Badan Otonom (Col 1 of 2 on mobile, 2 cols on desktop) -->
            <div class="col-span-1 lg:col-span-2 space-y-3 font-bold">
                <h4 class="text-xs font-black uppercase tracking-[0.14em] text-white">Badan Otonom</h4>
                <ul class="space-y-1 sm:space-y-2 text-xs text-brand-lightmatcha/80 list-none p-0 m-0">
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('anggota.index', ['organisasi' => 'ansor']) }}">GP Ansor</a></li>
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('anggota.index', ['organisasi' => 'fatayat']) }}">Fatayat NU</a></li>
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('anggota.index', ['organisasi' => 'muslimat']) }}">Muslimat NU</a></li>
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('anggota.index', ['organisasi' => 'ipnu']) }}">IPNU</a></li>
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('anggota.index', ['organisasi' => 'ippnu']) }}">IPPNU</a></li>
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('anggota.index', ['organisasi' => 'pagar_nusa']) }}">Pagar Nusa</a></li>
                </ul>
            </div>

            <!-- Col 3: Lembaga Otonom (Col 2 of 2 on mobile, 3 cols on desktop) -->
            <div class="col-span-1 lg:col-span-3 space-y-3 font-bold">
                <h4 class="text-xs font-black uppercase tracking-[0.14em] text-white">Lembaga Otonom</h4>
                <ul class="space-y-1 sm:space-y-2 text-xs text-brand-lightmatcha/80 list-none p-0 m-0">
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('home') }}#tentang">LAZISNU Wangon (ZISWAF)</a></li>
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('home') }}#tentang">LP Ma'arif NU Wangon</a></li>
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('home') }}#tentang">LKNU (Kesehatan Warga)</a></li>
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('home') }}#tentang">LPBI NU (Bencana &amp; Iklim)</a></li>
                    <li><a class="py-1 sm:py-0.5 block hover:text-white transition-colors text-decoration-none" href="{{ route('home') }}#tentang">LWP NU (Wakaf &amp; Pertanahan)</a></li>
                </ul>
            </div>

            <!-- Col 4: Sekretariat & Layanan (Full width on mobile, 3 cols on desktop) -->
            <div class="col-span-2 lg:col-span-3 space-y-3 font-bold">
                <h4 class="text-xs font-black uppercase tracking-[0.14em] text-white">Sekretariat</h4>
                <address class="not-italic text-xs text-brand-lightmatcha/80 space-y-2 font-normal">
                    <p class="leading-relaxed text-white">
                        <strong class="block font-black">{{ $pengaturan->nama_kantor ?? 'Kantor MWC Nahdlatul Ulama Wangon' }}</strong>
                        @if(!empty($pengaturan->maps_url))
                            <a href="{{ $pengaturan->maps_url }}" target="_blank" rel="noopener noreferrer" class="text-brand-lightmatcha/80 hover:text-white transition-colors text-decoration-none block" title="Buka Petunjuk Arah di Google Maps">
                                {{ $pengaturan->alamat_lengkap_formatted }}
                            </a>
                        @else
                            <span class="block">{{ $pengaturan->alamat_lengkap_formatted }}</span>
                        @endif
                    </p>
                    <p>
                        <span class="font-black text-white">WhatsApp:</span> 
                        @if(!empty($pengaturan->whatsapp_url))
                            <a href="{{ $pengaturan->whatsapp_url }}" target="_blank" rel="noopener noreferrer" class="text-brand-lightmatcha/80 hover:text-white transition-colors text-decoration-none">
                                {{ $pengaturan->whatsapp ?? $pengaturan->telepon ?? '+62 812-3456-7890' }}
                            </a>
                        @else
                            <span>{{ $pengaturan->whatsapp ?? $pengaturan->telepon ?? '+62 812-3456-7890' }}</span>
                        @endif
                    </p>
                    <p>
                        <span class="font-black text-white">Email:</span> 
                        @if(!empty($pengaturan->email))
                            <a href="mailto:{{ $pengaturan->email }}" class="text-brand-lightmatcha/80 hover:text-white transition-colors text-decoration-none">
                                {{ $pengaturan->email }}
                            </a>
                        @else
                            <span>{{ $pengaturan->email ?? 'sekretariat@nuwangon.or.id' }}</span>
                        @endif
                    </p>
                    <p class="text-[11px] text-brand-lightmatcha/70">Jam Layanan: {{ $pengaturan->jam_operasional_formatted }}</p>
                </address>

                <div class="pt-2">
                    <button type="button" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 bg-brand-forest hover:bg-brand-matcha text-white border-2 border-brand-matcha text-xs font-black rounded-xl shadow-solid-sm btn-solid-press transition-colors cursor-pointer" 
                            onclick="copySiteLink()">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                        </svg>
                        <span id="copy-btn-text">Salin Tautan Web</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sub-footer Bar -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs font-bold text-brand-lightmatcha/60 gap-4 text-center sm:text-left">
            <p class="m-0">&copy; 2026 Majelis Wakil Cabang NU Wangon. Seluruh hak cipta dilindungi.</p>
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-x-3 sm:gap-x-4 gap-y-2 text-[11px]">
                <a href="{{ route('home') }}#tentang" class="hover:text-white transition-colors text-decoration-none">Pedoman Jam'iyyah</a>
                <span>&bull;</span>
                <a href="#kontak" class="hover:text-white transition-colors text-decoration-none">Kebijakan Privasi</a>
                <span>&bull;</span>
                <span class="font-bold text-white">“Merawat Tradisi, Meneguhkan Aswaja”</span>
            </div>
        </div>
    </div>
</footer>

<!-- Toast notification for copy action -->
<div class="fixed bottom-4 sm:bottom-6 right-4 sm:right-6 max-w-[calc(100vw-2rem)] sm:max-w-md z-50 transform translate-y-24 opacity-0 transition-all duration-300 bg-white text-brand-forest border-2 border-brand-forest px-4 py-3 rounded-2xl shadow-solid-md text-xs font-bold flex items-center gap-3 pointer-events-none" id="copy-toast">
    <div class="w-6 h-6 rounded-lg bg-brand-lightmatcha border border-brand-forest text-brand-forest flex items-center justify-center shrink-0">
        <i class="bi bi-check text-base"></i>
    </div>
    <span id="toast-message">Tautan portal berhasil disalin ke papan klip.</span>
</div>

<script>
    function copySiteLink() {
        const url = window.location.href;
        const toast = document.getElementById('copy-toast');
        const copyBtnText = document.getElementById('copy-btn-text');

        function triggerToast() {
            if (copyBtnText) copyBtnText.textContent = 'Tersalin!';
            if (toast) {
                toast.classList.remove('translate-y-24', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');

                setTimeout(() => {
                    toast.classList.add('translate-y-24', 'opacity-0');
                    toast.classList.remove('translate-y-0', 'opacity-100');
                    if (copyBtnText) copyBtnText.textContent = 'Salin Tautan Web';
                }, 3000);
            }
        }

        function manualCopy(text) {
            const input = document.createElement('input');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            try {
                document.execCommand('copy');
                triggerToast();
            } catch (e) {
                prompt('Salin tautan resmi ini:', text);
            }
            document.body.removeChild(input);
        }

        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: url
            }).catch(() => {});
        } else if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(triggerToast).catch(() => manualCopy(url));
        } else {
            manualCopy(url);
        }
    }
</script>