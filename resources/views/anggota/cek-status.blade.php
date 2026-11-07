@extends('layouts.app')

@section('title', 'Cek Status Pendaftaran - Organisasi NU Wangon')

@section('content')

<!-- FONTS & TAILWIND UTILITIES -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    nu: {
                        bg: '#f9f8f4',          /* Background Netral Utama */
                        card: '#FFFFFF',        /* Putih Card */
                        input: '#EDF2EE',       /* Warna Input Muted */
                        border: '#E7E8E1',      /* Border Garis Tipis */
                        dark: '#2D4736',        /* Hijau NU Wangon */
                        darkHover: '#23382B',   /* Hover Tombol */
                        text: '#191E1A',        /* Teks Utama */
                        muted: '#636B65',       /* Teks Muted */
                        badgeBg: '#EDF2EE'      /* Badge Background */
                    }
                },
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    serif: ['"Playfair Display"', 'serif'],
                }
            }
        }
    }
</script>

<div class="min-h-screen bg-nu-bg text-nu-text font-sans antialiased pb-20 pt-10 sm:pt-14">

    <div class="max-w-md mx-auto px-4 sm:px-6 space-y-6">

        <!-- HEADER BANNER -->
        <div class="text-center space-y-3">
            <div data-text-animate="blurInUp" class="inline-flex items-center gap-2 bg-nu-badgeBg text-nu-dark px-3.5 py-1 rounded-full text-[11px] font-bold tracking-widest uppercase">
                <i class="bi bi-search text-xs"></i>
                <span>LACAK PENGAJUAN</span>
            </div>

            <h1 data-text-animate="blurInUp" class="font-serif text-3xl sm:text-4xl font-normal text-nu-text leading-tight">
                Cek Status Pendaftaran
            </h1>

            <p class="text-xs sm:text-sm text-nu-muted leading-relaxed max-w-sm mx-auto">
                Masukkan kode pendaftaran unik yang Anda dapatkan saat mengirimkan formulir.
            </p>
        </div>

        <!-- ALERT ERROR -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200/80 rounded-2xl p-4 flex items-center gap-3 text-red-800 text-xs shadow-xs">
                <i class="bi bi-exclamation-circle-fill text-base text-red-600 shrink-0"></i>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <!-- FORM CARD -->
        <div class="bg-nu-card border border-nu-border/80 rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-8 lg:p-10 shadow-xs space-y-6">
            <form action="{{ route('anggota.cek-status.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- INPUT KODE PENDAFTARAN -->
                <div class="space-y-2">
                    <label for="kode_pendaftaran" class="block text-xs font-semibold text-nu-text">
                        Kode Pendaftaran <span class="text-red-500">*</span>
                    </label>

                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-nu-muted text-xs pointer-events-none">
                            <i class="bi bi-ticket-perforated"></i>
                        </span>
                        <input type="text" id="kode_pendaftaran" name="kode_pendaftaran" required autofocus value="{{ old('kode_pendaftaran') }}"
                               placeholder="Contoh: REG-XXXXXXXX"
                               class="w-full bg-nu-input/70 border-0 rounded-full pl-10 pr-5 py-3.5 text-xs text-nu-text font-mono uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-nu-dark/20 transition-all placeholder:text-nu-muted/60 placeholder:font-sans">
                    </div>

                    <p class="text-[10px] text-nu-muted px-2">
                        Format kode berupa huruf kapital dan angka dengan awalan <span class="font-mono font-bold">REG-</span>.
                    </p>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" 
                        class="w-full bg-nu-dark hover:bg-nu-darkHover text-white text-xs font-semibold py-3.5 rounded-full transition-all duration-200 flex items-center justify-center gap-2 shadow-xs">
                    <i class="bi bi-search text-xs"></i>
                    <span>Periksa Status Sekarang</span>
                </button>
            </form>
        </div>

        <!-- FOOTER LINK -->
        <div class="text-center pt-2">
            <p class="text-xs text-nu-muted">
                Belum pernah mendaftar? 
                <a href="{{ route('anggota.daftar') }}" class="font-semibold text-nu-dark hover:underline transition-all">
                    Daftar sekarang
                </a>
            </p>
        </div>

    </div>

</div>

@endsection