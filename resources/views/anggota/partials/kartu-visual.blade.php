@php
    $prefix = \App\Support\Organisasi::prefix($anggota->organisasi);
    $tahun = date('Y');
    $nomorFormatted = $anggota->nomor_anggota ?? ($prefix . '-' . str_pad($anggota->id, 3, '0', STR_PAD_LEFT));
    $verifKode = $anggota->kode_verifikasi ?? ($anggota->kode_pendaftaran ?? 'NU-' . $anggota->id);
    $hpMasked = $anggota->no_hp_masked ?? ($anggota->no_hp ?? '-');
    $banomLabel = \App\Support\Organisasi::label($anggota->organisasi);
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=" . urlencode(route('verifikasi.show', $verifKode));
    $cardUniqueId = 'standalone-card-' . $anggota->id;
@endphp

<!-- 3D Card Flippable Wrapper (CR80 ID Badge Proportion: 320px x 450px) -->
<div class="card-3d-wrapper relative w-[320px] max-w-full h-[450px] mx-auto shrink-0" 
     id="wrapper-{{ $cardUniqueId }}"
     style="width: 320px; max-width: 100%; min-width: min(320px, 100%); height: 450px; perspective: 1200px; -webkit-perspective: 1200px; flex-shrink: 0;">
    
    <div id="{{ $cardUniqueId }}" 
         class="card-3d-inner w-full h-full relative"
         style="width: 100%; height: 100%; position: relative; transform-style: preserve-3d; -webkit-transform-style: preserve-3d; transition: transform 0.65s cubic-bezier(0.34, 1.25, 0.64, 1);">
        
        <!-- ================= SISI DEPAN (FRONT FACE) ================= -->
        <div class="card-face card-front rounded-3xl bg-white border-2 border-brand-forest shadow-solid-sm hover:shadow-solid-md transition-all flex flex-col justify-between p-5 overflow-hidden member-card-item cursor-pointer text-start"
             style="position: absolute; inset: 0; width: 100%; height: 100%; backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(0deg); z-index: 2;">
            
            <!-- Card Header: Logo NU, Identity & Status -->
            <div class="flex items-center justify-between gap-2.5 pb-3 border-b-2 border-brand-sand">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-brand-sand border border-brand-forest/30 p-1 flex items-center justify-center shrink-0 shadow-2xs">
                        <img src="{{ asset('images/logo-nu-card.webp') }}" alt="Logo NU" class="w-full h-full object-contain">
                    </div>
                    <div class="min-w-0">
                        <span class="text-[9px] font-black uppercase tracking-wider text-brand-forest/70 block leading-none">NAHDLATUL ULAMA</span>
                        <h5 class="text-xs font-black text-brand-deep tracking-tight truncate leading-tight mt-0.5 m-0">
                            {{ $banomLabel }} WANGON
                        </h5>
                    </div>
                </div>

                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-500/40 text-[9px] font-black uppercase tracking-wider shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>AKTIF</span>
                </div>
            </div>

            <!-- Card Center: Foto, Nama, Jabatan, ID Embossed -->
            <div class="text-center space-y-3 my-auto py-2">
                <div class="w-20 h-20 mx-auto rounded-2xl p-1 bg-brand-sand border-2 border-brand-forest overflow-hidden relative shadow-solid-sm group-hover:scale-105 transition-transform">
                    @if ($anggota->foto)
                        <img src="{{ asset('storage/' . $anggota->foto) }}" class="w-full h-full rounded-xl object-cover" alt="{{ $anggota->nama }}">
                    @else
                        <div class="w-full h-full rounded-xl bg-white flex items-center justify-center text-brand-forest/60 text-2xl">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                </div>

                <div class="space-y-0.5">
                    <h4 class="font-black text-base text-brand-deep truncate px-1 group-hover:text-brand-matcha transition-colors m-0" title="{{ $anggota->nama }}">
                        {{ $anggota->nama }}
                    </h4>
                    <p class="text-xs font-bold text-brand-darktext/70 truncate m-0">
                        {{ $anggota->jabatan ?? 'Anggota Aktif' }}
                    </p>
                </div>

                <div class="flex items-center justify-center gap-2 pt-0.5">
                    <span class="inline-block bg-brand-lightmatcha text-brand-forest text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full border border-brand-forest">
                        {{ $banomLabel }}
                    </span>
                    <span class="inline-block font-mono font-black text-[10px] tracking-wider text-brand-forest bg-brand-sand px-2 py-0.5 rounded-lg border border-brand-forest/30">
                        {{ $nomorFormatted }}
                    </span>
                </div>

                <div class="flex items-center justify-center gap-1.5 text-[11px] font-bold text-brand-forest/80 pt-0.5">
                    <i class="bi bi-telephone text-[10px]"></i>
                    <span class="font-mono">{{ $hpMasked }}</span>
                </div>
            </div>

            <!-- Card Bottom Controls: Flip 3D Button -->
            <div class="pt-3 border-t-2 border-brand-sand text-center">
                <button type="button" 
                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-brand-sand hover:bg-brand-forest hover:text-white text-brand-forest text-xs font-black uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all btn-solid-press cursor-pointer"
                        onclick="toggleCardFlip('{{ $cardUniqueId }}')"
                        title="Balik kartu untuk melihat barcode dan QR code">
                    <i class="bi bi-arrow-repeat"></i>
                    <span>Balik ke Sisi Barcode</span>
                </button>
            </div>

        </div>

        <!-- ================= SISI BELAKANG (BACK FACE) ================= -->
        <div class="card-face card-back rounded-3xl bg-[#F6F4EE] border-2 border-brand-forest shadow-solid-sm flex flex-col justify-between p-5 overflow-hidden member-card-item cursor-pointer text-start"
             style="position: absolute; inset: 0; width: 100%; height: 100%; backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(180deg); z-index: 1;">
            
            <!-- Back Header -->
            <div class="flex items-center justify-between pb-2.5 border-b-2 border-brand-sand">
                <div class="flex items-center gap-2 min-w-0">
                    <img src="{{ asset('images/logo-nu-card.webp') }}" alt="Logo NU" class="w-6 h-6 object-contain shrink-0 opacity-80">
                    <div>
                        <span class="text-[8px] font-black uppercase tracking-wider text-brand-forest/70 block leading-none">E-KARTANU RESMI</span>
                        <h6 class="text-[10px] font-black text-brand-deep tracking-tight m-0 uppercase">MWC NU WANGON</h6>
                    </div>
                </div>
                <span class="text-[9px] font-mono font-bold text-brand-forest/60">VALIDASI</span>
            </div>

            <!-- Back Body: Real QR Code + 1D Barcode Pattern -->
            <div class="bg-white rounded-2xl border-2 border-brand-forest p-3 shadow-solid-sm text-center my-auto space-y-2">
                <!-- Real Verification QR Code -->
                <div class="w-24 h-24 mx-auto bg-white rounded-xl border border-brand-forest/20 p-1 flex items-center justify-center">
                    <img src="{{ $qrUrl }}" alt="QR Verifikasi" class="w-full h-full object-contain">
                </div>

                <!-- 1D Barcode Pattern -->
                <div class="space-y-0.5">
                    <div class="h-4 flex items-center justify-center gap-0.5 text-brand-forest opacity-80 px-2 overflow-hidden" aria-hidden="true">
                        <span class="w-1 h-full bg-brand-forest inline-block"></span>
                        <span class="w-0.5 h-full bg-brand-forest inline-block"></span>
                        <span class="w-1.5 h-full bg-brand-forest inline-block"></span>
                        <span class="w-0.5 h-full bg-brand-forest inline-block"></span>
                        <span class="w-2 h-full bg-brand-forest inline-block"></span>
                        <span class="w-0.5 h-full bg-brand-forest inline-block"></span>
                        <span class="w-1 h-full bg-brand-forest inline-block"></span>
                        <span class="w-1.5 h-full bg-brand-forest inline-block"></span>
                        <span class="w-0.5 h-full bg-brand-forest inline-block"></span>
                        <span class="w-1 h-full bg-brand-forest inline-block"></span>
                        <span class="w-2 h-full bg-brand-forest inline-block"></span>
                        <span class="w-0.5 h-full bg-brand-forest inline-block"></span>
                        <span class="w-1.5 h-full bg-brand-forest inline-block"></span>
                        <span class="w-1 h-full bg-brand-forest inline-block"></span>
                    </div>
                    <span class="font-mono text-[11px] font-black text-brand-deep tracking-wider block">
                        {{ $verifKode }}
                    </span>
                </div>

                <p class="text-[9px] font-semibold text-brand-darktext/70 leading-tight m-0">
                    Scan QR untuk memverifikasi keabsahan resmi di portal MWC NU Wangon.
                </p>

                <div class="pt-1.5 border-t border-brand-sand flex items-center justify-between text-[9px] font-bold text-brand-forest/80 px-1">
                    <span class="font-mono">ID: {{ $nomorFormatted }}</span>
                    <span class="font-mono">HP: {{ $hpMasked }}</span>
                </div>
            </div>

            <!-- Back Footer: Flip Button -->
            <div class="pt-3 border-t-2 border-brand-sand text-center">
                <button type="button" 
                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-brand-sand hover:bg-brand-forest hover:text-white text-brand-forest text-xs font-black uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all btn-solid-press cursor-pointer"
                        onclick="toggleCardFlip('{{ $cardUniqueId }}')">
                    <i class="bi bi-arrow-repeat"></i>
                    <span>Balik ke Sisi Depan</span>
                </button>
            </div>

        </div>

    </div>
</div>

@include('anggota.partials.card-3d-flip-engine')

<script>
    if (typeof window.toggleCardFlip !== 'function') {
        window.toggleCardFlip = function(targetId) {
            if (window.Card3DFlipEngine && typeof window.Card3DFlipEngine.flip === 'function') {
                window.Card3DFlipEngine.flip(targetId);
                return;
            }
            const cardInner = document.getElementById(targetId);
            if (!cardInner) return;
            const isFlipped = cardInner.classList.contains('is-flipped');
            if (isFlipped) {
                cardInner.classList.remove('is-flipped');
                cardInner.style.transform = 'rotateY(0deg)';
            } else {
                cardInner.classList.add('is-flipped');
                cardInner.style.transform = 'rotateY(180deg)';
            }
        };
    }
</script>