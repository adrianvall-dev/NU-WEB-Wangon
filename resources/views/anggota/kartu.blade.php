@extends('layouts.app')

@section('title', 'Kartu Anggota Digital - ' . $anggota->nama)

@section('content')
<div class="py-8 md:py-12 min-h-[calc(100vh-80px)]" style="background-color: var(--brand-cream, #F6F4EE);">
    <div class="container mx-auto px-4 text-center max-w-xl">
        
        <!-- Header Page -->
        <div class="mb-6 space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider border border-brand-forest/30" style="background-color: #D9E8DD; color: #142E25;">
                <i class="bi bi-card-heading"></i> Kartu Tanda Anggota Resmi
            </div>
            <h1 class="font-black text-2xl sm:text-3xl tracking-tight m-0" style="color: #0E221B;">
                Kartu Anggota Digital
            </h1>
            <p class="text-xs font-bold text-[#2D4736] m-0">
                Jam'iyyah Nahdlatul Ulama Majelis Wakil Cabang Wangon
            </p>
        </div>

        <!-- VISUAL KARTU (NON-COLLAPSING CENTERED CONTAINER) -->
        <div class="flex justify-center mb-6">
            <div class="w-[320px] max-w-full flex justify-center shrink-0" style="width: 320px; max-width: 100%; min-width: min(320px, 100%); flex-shrink: 0;">
                @include('anggota.partials.kartu-visual', ['anggota' => $anggota])
            </div>
        </div>

        <!-- ACTION BUTTONS (BALANCED 2 BUTTONS) -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mt-6 print-hide w-full max-w-[340px] mx-auto">
            <button type="button" 
                    onclick="toggleCardFlip('standalone-card-{{ $anggota->id }}')" 
                    class="w-full sm:w-1/2 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider bg-brand-sand hover:bg-brand-forest hover:text-white text-brand-forest border-2 border-brand-forest shadow-solid-sm btn-solid-press cursor-pointer transition-all">
                <i class="bi bi-arrow-repeat text-sm"></i>
                <span>Putar Kartu (3D Flip)</span>
            </button>
            <a href="{{ route('anggota.kartu.pdf', $anggota->kode_pendaftaran ?? ($anggota->nomor_anggota ?? $anggota->id)) }}" 
               class="w-full sm:w-1/2 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider bg-brand-forest hover:bg-brand-deep text-white border-2 border-brand-deep shadow-solid-sm btn-solid-press text-decoration-none transition-all">
                <i class="bi bi-download text-sm"></i>
                <span>Unduh File PDF</span>
            </a>
        </div>

        <!-- BACK LINK -->
        <div class="text-center mt-6 print-hide">
            <a href="{{ route('anggota.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-forest/80 hover:text-brand-forest text-decoration-none">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Direktori Anggota</span>
            </a>
        </div>
    </div>
</div>

<style>
    @media print {
        header, footer, nav, .print-hide { display: none !important; }
        body { background: #ffffff !important; padding: 0 !important; margin: 0 !important; }
        .py-8, .md\:py-12 { padding-top: 0 !important; padding-bottom: 0 !important; }
        .container { max-width: 100% !important; padding: 0 !important; }
    }
</style>
@endsection