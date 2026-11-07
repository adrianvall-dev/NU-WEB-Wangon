@extends('layouts.app')

@section('title', 'Status Pendaftaran - ' . $anggota->kode_pendaftaran)

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
                        input: '#EDF2EE',       /* Muted Card & Pill */
                        border: '#E7E8E1',      /* Border Garis Tipis */
                        dark: '#2D4736',        /* Hijau NU Wangon */
                        darkHover: '#23382B',   /* Hover Tombol */
                        text: '#191E1A',        /* Teks Utama */
                        muted: '#636B65',       /* Teks Muted */
                        alertBg: '#EDF2EE',     /* Success Banner Background */
                        alertIcon: '#2D4736',   /* Circle Icon Dark */
                        badgeBg: '#EDF2EE'
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

    <div class="max-w-xl mx-auto px-4 sm:px-6 space-y-6">

        <!-- ALERT SUCCESS (STATUS HANYA SAAT BARU DAFTAR) -->
        @if (session('baru_daftar'))
            <div class="bg-nu-alertBg border border-nu-border/50 rounded-3xl p-5 sm:p-6 transition-all duration-300">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-nu-alertIcon text-white flex items-center justify-center shrink-0 mt-0.5 text-xs">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-sm font-bold text-nu-text">Pendaftaran Berhasil Dikirim!</h2>
                        <p class="text-xs text-nu-muted leading-relaxed">
                            Simpan kode pendaftaran berikut untuk mengecek perkembangan status keanggotaan Anda:
                        </p>
                        <div class="pt-1">
                            <div class="inline-flex items-center gap-2 bg-nu-card border border-nu-border px-3.5 py-1.5 rounded-full text-xs shadow-xs">
                                <span class="font-mono font-bold text-nu-text tracking-wide">{{ $anggota->kode_pendaftaran }}</span>
                                <button type="button" class="text-nu-muted hover:text-nu-text transition-colors" onclick="copyCode(this, '{{ $anggota->kode_pendaftaran }}')" title="Salin Kode">
                                    <i class="bi bi-copy text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- MAIN CARD -->
        <div class="bg-nu-card border border-nu-border/80 rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-10 lg:p-12 shadow-xs text-center space-y-6">

            <!-- KODE PENDAFTARAN BADGE -->
            <div class="space-y-2">
                <span class="text-[10px] font-bold tracking-widest text-nu-muted uppercase block">KODE PENDAFTARAN</span>
                <div class="inline-flex items-center gap-2.5 bg-nu-input/60 border border-nu-border/60 px-4 py-1.5 rounded-full text-xs">
                    <span class="font-mono font-bold text-nu-text tracking-wider">{{ $anggota->kode_pendaftaran }}</span>
                    <button type="button" class="text-nu-muted hover:text-nu-text transition-colors" onclick="copyCode(this, '{{ $anggota->kode_pendaftaran }}')" title="Salin Kode">
                        <i class="bi bi-copy text-[11px]"></i>
                    </button>
                </div>
            </div>

            <!-- STATUS: PENDING -->
            @if ($anggota->status_pendaftaran === 'pending')
                <div class="space-y-6 pt-2">
                    <!-- ICON HOURGLASS -->
                    <div class="flex justify-center">
                        <div class="w-14 h-14 rounded-full bg-nu-badgeBg text-nu-dark flex items-center justify-center text-xl">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>

                    <!-- TITLE & DESC -->
                    <div class="space-y-3 max-w-md mx-auto">
                        <h2 class="font-serif text-2xl sm:text-3xl text-nu-text font-normal">Menunggu Verifikasi Admin</h2>
                        <p class="text-xs sm:text-[13px] text-nu-muted leading-relaxed px-2">
                            Data pendaftaran Anda telah masuk ke sistem dan sedang dalam antrean pemeriksaan oleh pengurus. Silakan cek kembali halaman ini dalam 1 hingga 2 hari kerja.
                        </p>
                    </div>

                    <!-- DATA SUMMARY TABLE CARD -->
                    <div class="bg-nu-input/50 border border-nu-border/50 rounded-2xl p-4 sm:p-5 text-left text-xs space-y-3">
                        <div class="flex justify-between items-center pb-2.5 border-b border-nu-border/60">
                            <span class="text-nu-muted">Nama Pendaftar</span>
                            <span class="font-semibold text-nu-text">{{ $anggota->nama }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2.5 border-b border-nu-border/60">
                            <span class="text-nu-muted">Badan Otonom</span>
                            <span class="font-semibold text-nu-text">{{ \App\Support\Organisasi::fullName($anggota->organisasi) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-2.5 border-b border-nu-border/60">
                            <span class="text-nu-muted">Posisi Diajukan</span>
                            <span class="font-semibold text-nu-text">
                                @if ($anggota->posisi_pendaftaran === 'ketua')
                                    <span class="inline-flex items-center gap-1 text-emerald-800 bg-emerald-100/90 px-2.5 py-0.5 rounded-full font-bold text-[11px]">
                                        <i class="bi bi-star-fill text-[9px] text-emerald-600"></i> Calon Ketua
                                    </span>
                                @elseif ($anggota->posisi_pendaftaran === 'wakil_ketua')
                                    <span class="inline-flex items-center gap-1 text-emerald-800 bg-emerald-100/90 px-2.5 py-0.5 rounded-full font-bold text-[11px]">
                                        <i class="bi bi-award-fill text-[9px] text-emerald-600"></i> Calon Wakil Ketua
                                    </span>
                                @else
                                    <span>Anggota Biasa</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-0.5">
                            <span class="text-nu-muted">Tanggal Pengajuan</span>
                            <span class="font-semibold text-nu-text">{{ $anggota->created_at ? $anggota->created_at->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                    </div>

                    <!-- BACK BUTTON -->
                    <div class="pt-2">
                        <a href="{{ route('anggota.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-white border border-nu-border text-nu-text hover:bg-nu-input text-xs font-semibold px-7 py-3 rounded-full transition-all">
                            Kembali ke Direktori Anggota
                        </a>
                    </div>
                </div>

            <!-- STATUS: REJECTED -->
            @elseif ($anggota->status_pendaftaran === 'rejected')
                <div class="space-y-6 pt-2">
                    <div class="flex justify-center">
                        <div class="w-14 h-14 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-xl">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                    </div>

                    <div class="space-y-3 max-w-md mx-auto">
                        <h2 class="font-serif text-2xl sm:text-3xl text-nu-text font-normal">Pendaftaran Perlu Perbaikan</h2>
                        <p class="text-xs sm:text-[13px] text-nu-muted leading-relaxed px-2">
                            Terdapat data yang belum sesuai dengan kriteria verifikasi administrasi pengurus.
                        </p>
                    </div>

                    <div class="bg-red-50 border border-red-200/80 rounded-2xl p-4 text-left text-xs space-y-1 text-red-900">
                        <div class="font-bold flex items-center gap-1.5 text-red-800">
                            <i class="bi bi-info-circle"></i>
                            <span>Catatan Penolakan:</span>
                        </div>
                        <p class="text-red-700 leading-relaxed pt-0.5">
                            {{ $anggota->alasan_penolakan ?? 'Harap periksa kembali foto atau kelengkapan data NIK Anda.' }}
                        </p>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('anggota.perbaiki', $anggota->kode_pendaftaran) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-nu-dark hover:bg-nu-darkHover text-white text-xs font-semibold px-7 py-3 rounded-full transition-all shadow-xs">
                            <i class="bi bi-pencil-square"></i>
                            <span>Perbaiki & Kirim Ulang Data</span>
                        </a>
                    </div>
                </div>

            <!-- STATUS: APPROVED -->
            @elseif ($anggota->status_pendaftaran === 'approved')
                <div class="space-y-6 pt-2">
                    <div class="flex justify-center">
                        <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                    </div>

                    <div class="space-y-2 max-w-md mx-auto">
                        <h2 class="font-serif text-2xl sm:text-3xl text-nu-text font-normal">Pendaftaran Disetujui</h2>
                        <span class="inline-block bg-emerald-100/80 text-emerald-800 border border-emerald-200 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            Status: Anggota Resmi
                        </span>
                    </div>

                    <div class="bg-nu-input/50 border border-nu-border/50 rounded-2xl p-5 space-y-2">
                        <span class="text-[10px] font-bold tracking-widest text-nu-muted uppercase block">NOMOR INDUK ANGGOTA</span>
                        <h3 class="font-mono font-bold text-xl sm:text-2xl text-nu-text tracking-wider">{{ $anggota->nomor_anggota }}</h3>
                        @if($anggota->jabatan)
                            <div class="pt-1 border-t border-nu-border/40 flex items-center justify-between text-xs">
                                <span class="text-nu-muted">Jabatan / Amanah:</span>
                                <span class="font-bold text-nu-dark">{{ $anggota->jabatan }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                        <a href="{{ route('anggota.kartu', $anggota->kode_pendaftaran) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-nu-dark hover:bg-nu-darkHover text-white text-xs font-semibold px-6 py-3 rounded-full transition-all shadow-xs">
                            <i class="bi bi-card-heading"></i>
                            <span>Cetak Kartu Anggota</span>
                        </a>
                        <a href="{{ route('anggota.kartu.pdf', $anggota->kode_pendaftaran) }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white border border-nu-border text-nu-text hover:bg-nu-input text-xs font-semibold px-6 py-3 rounded-full transition-all">
                            <i class="bi bi-download"></i>
                            <span>Unduh File PDF</span>
                        </a>
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>

<script>
    function copyCode(btn, text) {
        navigator.clipboard.writeText(text).then(function() {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg text-emerald-600"></i>';
            setTimeout(() => { btn.innerHTML = orig; }, 1600);
        });
    }
</script>

@endsection