@extends('layouts.app')

@section('title', 'Pendaftaran Anggota - Organisasi NU Wangon')

@section('content')

<!-- FONTS & TAILWIND UTILITIES -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                        badge: '#EDF2EE'        /* Badge Nomor */
                    }
                },
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                }
            }
        }
    }
</script>

<div class="min-h-screen bg-nu-bg text-nu-text font-sans antialiased selection:bg-nu-dark selection:text-white pb-16">

    <!-- MAIN CONTENT CONTAINERS -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-14 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

            <!-- LEFT COLUMN: HEADER & INFORMASI -->
            <div class="lg:col-span-5 space-y-8 lg:sticky lg:top-8">
                <div class="space-y-4">
                    <div data-text-animate="blurInUp" class="inline-flex items-center gap-2 text-[11px] font-bold tracking-widest text-nu-muted uppercase">
                        <span class="w-1.5 h-1.5 rounded-full bg-nu-dark"></span>
                        <span>PENDAFTARAN ANGGOTA</span>
                    </div>

                    <h1 data-text-animate="blurInUp" class="text-4xl sm:text-5xl lg:text-[3.25rem] font-serif font-normal leading-[1.12] text-nu-text tracking-tight">
                        Bergabung <br>
                        menjadi bagian <br>
                        dari NU <br>
                        Wangon.
                    </h1>

                    <p class="text-nu-muted text-xs sm:text-sm leading-relaxed max-w-md pt-2">
                        Lengkapi data diri Anda untuk mengajukan pendaftaran sebagai anggota Organisasi NU Wangon. Satu langkah tertib dalam merawat persaudaraan dan khidmah keumatan.
                    </p>
                </div>

                <!-- ALERT ERROR -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 space-y-2">
                        <div class="flex items-center gap-2 text-red-700 font-semibold text-xs">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span>Mohon periksa kembali isian Anda:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- CARD INFORMASI BANTUAN -->
                <div class="bg-nu-card border border-nu-border rounded-2xl sm:rounded-[2rem] p-4 sm:p-6 shadow-xs space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-nu-input text-nu-dark flex items-center justify-center shrink-0 text-sm">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div class="space-y-1 pt-0.5">
                            <h4 class="text-xs font-bold text-nu-text">Privasi & Keamanan Data</h4>
                            <p class="text-[11px] text-nu-muted leading-relaxed">
                                Data terenkripsi dan dijaga kerahasiaannya untuk keperluan tertib administrasi keanggotaan MWC NU Wangon.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: FORMULARIO -->
            <div class="lg:col-span-7 bg-nu-card border border-nu-border rounded-2xl sm:rounded-[2.5rem] p-4 sm:p-10 lg:p-12 shadow-xs">
                <form action="{{ route('anggota.daftar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                    @csrf

                    <!-- 01. DATA DIRI -->
                    <div class="space-y-5">
                        <div class="flex items-center justify-between pb-2 border-b border-nu-border/60">
                            <div class="flex items-center gap-3">
                                <span class="bg-nu-badge text-nu-text text-[11px] font-mono font-bold px-2.5 py-0.5 rounded-full">01</span>
                                <h3 class="font-serif text-lg text-nu-text font-normal">Identitas Diri</h3>
                            </div>
                            <span class="text-[10px] font-bold tracking-wider text-nu-muted uppercase">IDENTITAS POKOK</span>
                        </div>

                        <div class="space-y-4">
                            <!-- NAMA LENGKAP -->
                            <div class="space-y-1.5">
                                <label for="nama" class="block text-xs font-semibold text-nu-text">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama" name="nama" required value="{{ old('nama') }}"
                                       placeholder="Contoh: Ahmad Fauzi / Siti Aminah"
                                       class="w-full bg-nu-input/70 border-0 rounded-full px-5 py-3 text-xs text-nu-text focus:outline-none focus:ring-2 focus:ring-nu-dark/20 transition-all placeholder:text-nu-muted/60">
                                <p class="text-[10px] text-nu-muted px-2">Tulis nama lengkap sesuai dengan kartu identitas (KTP).</p>
                            </div>

                            <!-- NIK -->
                            <div class="space-y-1.5">
                                <label for="nik" class="block text-xs font-semibold text-nu-text">
                                    Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nik" name="nik" maxlength="16" required value="{{ old('nik') }}"
                                       placeholder="16 digit NIK sesuai KTP"
                                       class="w-full bg-nu-input/70 border-0 rounded-full px-5 py-3 text-xs text-nu-text focus:outline-none focus:ring-2 focus:ring-nu-dark/20 transition-all placeholder:text-nu-muted/60">
                                <p class="text-[10px] text-nu-muted px-2">NIK hanya digunakan untuk verifikasi data kependudukan resmi.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 02. PILIHAN ORGANISASI -->
                    <div class="space-y-5">
                        <div class="flex items-center justify-between pb-2 border-b border-nu-border/60">
                            <div class="flex items-center gap-3">
                                <span class="bg-nu-badge text-nu-text text-[11px] font-mono font-bold px-2.5 py-0.5 rounded-full">02</span>
                                <h3 class="font-serif text-lg text-nu-text font-normal">Pilihan Organisasi &amp; Posisi</h3>
                            </div>
                            <span class="text-[10px] font-bold tracking-wider text-nu-muted uppercase">BADAN OTONOM</span>
                        </div>

                        <div class="space-y-1.5">
                            <label for="organisasi" class="block text-xs font-semibold text-nu-text">
                                Pilih Badan Otonom (Banom) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="organisasi" name="organisasi" required
                                        class="w-full bg-nu-input/70 border-0 rounded-full px-5 py-3 text-xs text-nu-text focus:outline-none focus:ring-2 focus:ring-nu-dark/20 transition-all appearance-none cursor-pointer">
                                    @if(isset($organisasiOptions) && $organisasiOptions->count() > 0)
                                        @foreach($organisasiOptions as $opt)
                                            <option value="{{ $opt->slug }}" 
                                                    data-has-ketua="{{ $opt->has_ketua ? 'true' : 'false' }}"
                                                    data-ketua-name="{{ $opt->ketua_nama ?? '' }}"
                                                    data-has-wakil-ketua="{{ ($opt->has_wakil_ketua ?? false) ? 'true' : 'false' }}"
                                                    data-wakil-ketua-name="{{ $opt->wakil_ketua_nama ?? '' }}"
                                                    {{ old('organisasi', 'muslimat') === $opt->slug ? 'selected' : '' }}>
                                                {{ $opt->nama }}
                                            </option>
                                        @endforeach
                                    @else
                                        @foreach(\App\Support\Organisasi::registrationOptions() as $orgKey => $orgLabel)
                                            <option value="{{ $orgKey }}" {{ old('organisasi', 'muslimat') === $orgKey ? 'selected' : '' }}>{{ $orgLabel }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-nu-muted text-xs">
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <!-- DAFTAR SEBAGAI: KETUA / ANGGOTA -->
                        <div class="space-y-2 pt-2">
                            <label class="block text-xs font-semibold text-nu-text">
                                Daftar Sebagai <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Option Anggota -->
                                <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border border-nu-border/80 bg-nu-input/40 hover:bg-nu-input/70 cursor-pointer transition-all">
                                    <input type="radio" name="posisi_pendaftaran" id="posisi_anggota" value="anggota" 
                                           {{ old('posisi_pendaftaran', 'anggota') === 'anggota' ? 'checked' : '' }}
                                           class="text-[#2D4736] focus:ring-[#2D4736] w-4 h-4">
                                    <div>
                                        <div class="text-xs font-bold text-nu-text">Anggota</div>
                                        <div class="text-[10px] text-nu-muted">Anggota biasa jam'iyyah.</div>
                                    </div>
                                </label>

                                <!-- Option Ketua -->
                                <label id="label_posisi_ketua" class="relative flex items-center gap-3 p-3.5 rounded-2xl border border-nu-border/80 bg-nu-input/40 hover:bg-nu-input/70 cursor-pointer transition-all">
                                    <input type="radio" name="posisi_pendaftaran" id="posisi_ketua" value="ketua"
                                           {{ old('posisi_pendaftaran') === 'ketua' ? 'checked' : '' }}
                                           class="text-[#2D4736] focus:ring-[#2D4736] w-4 h-4">
                                    <div>
                                        <div class="text-xs font-bold text-nu-text flex items-center gap-1.5">
                                            <span>Ketua</span>
                                            <span class="text-[9px] uppercase px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-semibold">Calon</span>
                                        </div>
                                        <div class="text-[10px] text-nu-muted">Calon Ketua organisasi.</div>
                                    </div>
                                </label>

                                <!-- Option Wakil Ketua -->
                                <label id="label_posisi_wakil_ketua" class="relative flex items-center gap-3 p-3.5 rounded-2xl border border-nu-border/80 bg-nu-input/40 hover:bg-nu-input/70 cursor-pointer transition-all">
                                    <input type="radio" name="posisi_pendaftaran" id="posisi_wakil_ketua" value="wakil_ketua"
                                           {{ old('posisi_pendaftaran') === 'wakil_ketua' ? 'checked' : '' }}
                                           class="text-[#2D4736] focus:ring-[#2D4736] w-4 h-4">
                                    <div>
                                        <div class="text-xs font-bold text-nu-text flex items-center gap-1.5">
                                            <span>Wakil Ketua</span>
                                            <span class="text-[9px] uppercase px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 font-semibold">Calon</span>
                                        </div>
                                        <div class="text-[10px] text-nu-muted">Calon Wakil Ketua organisasi.</div>
                                    </div>
                                </label>
                            </div>

                            <!-- Alert jika organisasi sudah memiliki ketua aktif -->
                            <div id="ketua_warning_box" class="hidden p-3 rounded-2xl bg-amber-50/90 border border-amber-200 text-amber-800 text-[11px] flex items-center gap-2">
                                <i class="bi bi-info-circle-fill text-amber-600 shrink-0"></i>
                                <span id="ketua_warning_text">Organisasi ini sudah memiliki ketua.</span>
                            </div>

                            <!-- Alert jika organisasi sudah memiliki wakil ketua aktif -->
                            <div id="wakil_ketua_warning_box" class="hidden p-3 rounded-2xl bg-amber-50/90 border border-amber-200 text-amber-800 text-[11px] flex items-center gap-2">
                                <i class="bi bi-info-circle-fill text-amber-600 shrink-0"></i>
                                <span id="wakil_ketua_warning_text">Organisasi ini sudah memiliki wakil ketua.</span>
                            </div>
                        </div>
                    </div>

                    <!-- 03. KONTAK & DOMISILI -->
                    <div class="space-y-5">
                        <div class="flex items-center justify-between pb-2 border-b border-nu-border/60">
                            <div class="flex items-center gap-3">
                                <span class="bg-nu-badge text-nu-text text-[11px] font-mono font-bold px-2.5 py-0.5 rounded-full">03</span>
                                <h3 class="font-serif text-lg text-nu-text font-normal">Kontak & Domisili</h3>
                            </div>
                            <span class="text-[10px] font-bold tracking-wider text-nu-muted uppercase">WILAYAH WANGON</span>
                        </div>

                        <div class="space-y-4">
                            <!-- NO HP -->
                            <div class="space-y-1.5">
                                <label for="no_hp" class="block text-xs font-semibold text-nu-text">
                                    Nomor Handphone / WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="no_hp" name="no_hp" required value="{{ old('no_hp') }}"
                                       placeholder="Contoh: 081234567890"
                                       class="w-full bg-nu-input/70 border-0 rounded-full px-5 py-3 text-xs text-nu-text focus:outline-none focus:ring-2 focus:ring-nu-dark/20 transition-all placeholder:text-nu-muted/60">
                                <p class="text-[10px] text-nu-muted px-2">Pastikan nomor aktif untuk konfirmasi pendaftaran dari pengurus.</p>
                            </div>

                            <!-- ALAMAT LENGKAP -->
                            <div class="space-y-1.5">
                                <label for="alamat" class="block text-xs font-semibold text-nu-text">
                                    Alamat Lengkap Domisili <span class="text-red-500">*</span>
                                </label>
                                <textarea id="alamat" name="alamat" rows="3" required
                                          placeholder="Sebutkan RT/RW, Dusun, dan Desa Sindang"
                                          class="w-full bg-nu-input/70 border-0 rounded-3xl p-4 text-xs text-nu-text focus:outline-none focus:ring-2 focus:ring-nu-dark/20 transition-all placeholder:text-nu-muted/60 resize-none">{{ old('alamat') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- 04. PAS FOTO -->
                    <div class="space-y-5">
                        <div class="flex items-center justify-between pb-2 border-b border-nu-border/60">
                            <div class="flex items-center gap-3">
                                <span class="bg-nu-badge text-nu-text text-[11px] font-mono font-bold px-2.5 py-0.5 rounded-full">04</span>
                                <h3 class="font-serif text-lg text-nu-text font-normal">Pas Foto</h3>
                            </div>
                            <span class="text-[10px] font-bold tracking-wider text-nu-muted uppercase">BERKAS PENDUKUNG</span>
                        </div>

                        <div class="space-y-1.5">
                            <label for="foto" class="block text-xs font-semibold text-nu-text">
                                Upload Pas Foto Formal <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="foto" name="foto" accept="image/*" required
                                   class="w-full bg-nu-input/70 border-0 rounded-full p-2 text-xs text-nu-text file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-nu-dark file:text-white hover:file:bg-nu-darkHover cursor-pointer transition-all">
                            <p class="text-[10px] text-nu-muted px-2">Format foto: JPG, JPEG, atau PNG dengan ukuran maksimal 2 MB. Gunakan foto yang jelas dan sopan.</p>
                        </div>
                    </div>

                    <!-- CTA BUTTON & INFO -->
                    <div class="pt-6 border-t border-nu-border/60 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-nu-muted text-[11px]">
                            <i class="bi bi-info-circle text-xs"></i>
                            <span>Pastikan data yang Anda masukkan sudah benar sebelum mengirimkan pendaftaran.</span>
                        </div>

                        <button type="submit" 
                                class="w-full sm:w-auto bg-nu-dark hover:bg-nu-darkHover text-white text-xs font-semibold px-8 py-3.5 rounded-full transition-all duration-200 flex items-center justify-center gap-2 shadow-sm shrink-0">
                            <span>Kirim Formulir Pendaftaran</span>
                            <i class="bi bi-arrow-right text-xs"></i>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orgSelect = document.getElementById('organisasi');
    const radioKetua = document.getElementById('posisi_ketua');
    const radioWakilKetua = document.getElementById('posisi_wakil_ketua');
    const radioAnggota = document.getElementById('posisi_anggota');
    const labelKetua = document.getElementById('label_posisi_ketua');
    const labelWakilKetua = document.getElementById('label_posisi_wakil_ketua');
    const warningBox = document.getElementById('ketua_warning_box');
    const warningText = document.getElementById('ketua_warning_text');
    const warningWakilBox = document.getElementById('wakil_ketua_warning_box');
    const warningWakilText = document.getElementById('wakil_ketua_warning_text');

    function checkAvailability() {
        if (!orgSelect) return;
        const selectedOpt = orgSelect.options[orgSelect.selectedIndex];
        if (!selectedOpt) return;

        const hasKetua = selectedOpt.getAttribute('data-has-ketua') === 'true';
        const ketuaName = selectedOpt.getAttribute('data-ketua-name');
        const hasWakilKetua = selectedOpt.getAttribute('data-has-wakil-ketua') === 'true';
        const wakilKetuaName = selectedOpt.getAttribute('data-wakil-ketua-name');

        if (radioKetua) {
            if (hasKetua) {
                radioKetua.disabled = true;
                if (labelKetua) {
                    labelKetua.classList.add('opacity-50', 'cursor-not-allowed', 'bg-stone-100');
                }
                if (radioKetua.checked && radioAnggota) {
                    radioAnggota.checked = true;
                }
                if (warningBox) {
                    warningBox.classList.remove('hidden');
                    warningText.textContent = `Organisasi ini sudah memiliki ketua aktif${ketuaName ? ' (' + ketuaName + ')' : ''}. Pilihan Ketua dinonaktifkan.`;
                }
            } else {
                radioKetua.disabled = false;
                if (labelKetua) {
                    labelKetua.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-stone-100');
                }
                if (warningBox) {
                    warningBox.classList.add('hidden');
                }
            }
        }

        if (radioWakilKetua) {
            if (hasWakilKetua) {
                radioWakilKetua.disabled = true;
                if (labelWakilKetua) {
                    labelWakilKetua.classList.add('opacity-50', 'cursor-not-allowed', 'bg-stone-100');
                }
                if (radioWakilKetua.checked && radioAnggota) {
                    radioAnggota.checked = true;
                }
                if (warningWakilBox) {
                    warningWakilBox.classList.remove('hidden');
                    warningWakilText.textContent = `Organisasi ini sudah memiliki wakil ketua aktif${wakilKetuaName ? ' (' + wakilKetuaName + ')' : ''}. Pilihan Wakil Ketua dinonaktifkan.`;
                }
            } else {
                radioWakilKetua.disabled = false;
                if (labelWakilKetua) {
                    labelWakilKetua.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-stone-100');
                }
                if (warningWakilBox) {
                    warningWakilBox.classList.add('hidden');
                }
            }
        }
    }

    if (orgSelect) {
        orgSelect.addEventListener('change', checkAvailability);
        checkAvailability();
    }
});
</script>

@endsection