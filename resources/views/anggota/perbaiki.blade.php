@extends('layouts.app')

@section('title', 'Perbaiki Data Pendaftaran - Organisasi NU Wangon')

@section('content')
<div class="py-4 py-md-5">
    <div class="container" style="max-width: 680px;">
        
        {{-- HEADER --}}
        <div class="text-center mb-4 reveal-on-scroll">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-warning-subtle text-warning-emphasis fs-xs fw-semibold mb-2">
                <i class="bi bi-pencil-square"></i> Revisi Data Pengajuan
            </div>
            <h1 class="fw-bold text-dark mb-2 fs-3">Perbaiki Data Pendaftaran</h1>
            <p class="text-muted small mb-0">Silakan sesuaikan data Anda berdasarkan catatan dari pengurus di bawah ini.</p>
        </div>

        {{-- REJECTION REASON BOX --}}
        <div class="alert alert-danger rounded-4 border-0 shadow-2xs mb-4 p-3.5">
            <div class="d-flex align-items-center gap-2 mb-1 text-danger fw-bold fs-sm">
                <i class="bi bi-exclamation-triangle-fill"></i> Catatan Penolakan Administrasi:
            </div>
            <div class="text-dark fs-sm ps-4">
                {{ $anggota->alasan_penolakan ?? 'Harap tinjau kembali kelengkapan data atau foto identitas Anda.' }}
            </div>
        </div>

        {{-- ALERT VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="alert alert-danger rounded-4 border-0 shadow-2xs mb-4 p-3.5">
                <div class="d-flex align-items-center gap-2 mb-2 text-danger fw-semibold fs-sm">
                    <i class="bi bi-exclamation-octagon-fill"></i> Terdapat kesalahan pengisian:
                </div>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM CARD --}}
        <div class="card border-0 shadow-2xs rounded-4 bg-white overflow-hidden reveal-on-scroll delay-100">
            <div class="card-body p-3 p-sm-4 p-md-5">
                <form action="{{ route('anggota.perbaiki.update', $anggota->kode_pendaftaran) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- SECTION: IDENTITAS --}}
                    <div class="mb-4 form-section-reveal">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <span class="badge rounded-circle bg-success-subtle text-success-nu p-1 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;">1</span>
                            <h2 class="fw-bold text-dark fs-6 mb-0">Identitas Diri</h2>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold text-dark fs-sm">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="nama" name="nama" class="form-control rounded-3 py-2 px-3 fs-sm" value="{{ old('nama', $anggota->nama) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="nik" class="form-label fw-semibold text-dark fs-sm">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                            <input type="text" id="nik" name="nik" class="form-control rounded-3 py-2 px-3 fs-sm" maxlength="16" value="{{ old('nik', $anggota->nik) }}" required>
                            <div class="form-text fs-xs text-muted">16 digit angka sesuai KTP resmi.</div>
                        </div>
                    </div>

                    {{-- SECTION: ORGANISASI --}}
                    <div class="mb-4 form-section-reveal">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <span class="badge rounded-circle bg-success-subtle text-success-nu p-1 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;">2</span>
                            <h2 class="fw-bold text-dark fs-6 mb-0">Pilihan Organisasi</h2>
                        </div>

                        <div class="mb-3">
                            <label for="organisasi" class="form-label fw-semibold text-dark fs-sm">Pilih Badan Otonom (Banom) <span class="text-danger">*</span></label>
                            <select id="organisasi" name="organisasi" class="form-select rounded-3 py-2 px-3 fs-sm" required>
                                @if(isset($organisasiOptions) && count($organisasiOptions) > 0)
                                    @foreach($organisasiOptions as $opt)
                                        <option value="{{ $opt->slug }}" 
                                                data-has-ketua="{{ $opt->has_ketua ? 'true' : 'false' }}"
                                                data-ketua-name="{{ $opt->ketua_nama ?? '' }}"
                                                data-has-wakil-ketua="{{ ($opt->has_wakil_ketua ?? false) ? 'true' : 'false' }}"
                                                data-wakil-ketua-name="{{ $opt->wakil_ketua_nama ?? '' }}"
                                                {{ old('organisasi', $anggota->organisasi) === $opt->slug ? 'selected' : '' }}>{{ $opt->nama }}</option>
                                    @endforeach
                                @else
                                    @foreach(\App\Support\Organisasi::registrationOptions() as $orgKey => $orgLabel)
                                        <option value="{{ $orgKey }}" {{ old('organisasi', $anggota->organisasi) === $orgKey ? 'selected' : '' }}>{{ $orgLabel }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark fs-sm d-block mb-2">Daftar Sebagai <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="posisi_pendaftaran" id="posisi_anggota" value="anggota" {{ old('posisi_pendaftaran', $anggota->posisi_pendaftaran ?: 'anggota') === 'anggota' ? 'checked' : '' }}>
                                    <label class="form-check-label fs-sm" for="posisi_anggota">
                                        Anggota
                                    </label>
                                </div>
                                <div class="form-check" id="wrapper_posisi_ketua">
                                    <input class="form-check-input" type="radio" name="posisi_pendaftaran" id="posisi_ketua" value="ketua" {{ old('posisi_pendaftaran', $anggota->posisi_pendaftaran) === 'ketua' ? 'checked' : '' }}>
                                    <label class="form-check-label fs-sm" for="posisi_ketua">
                                        Ketua <span class="badge bg-warning-subtle text-warning border ms-1 small">Calon</span>
                                    </label>
                                </div>
                                <div class="form-check" id="wrapper_posisi_wakil_ketua">
                                    <input class="form-check-input" type="radio" name="posisi_pendaftaran" id="posisi_wakil_ketua" value="wakil_ketua" {{ old('posisi_pendaftaran', $anggota->posisi_pendaftaran) === 'wakil_ketua' ? 'checked' : '' }}>
                                    <label class="form-check-label fs-sm" for="posisi_wakil_ketua">
                                        Wakil Ketua <span class="badge bg-success-subtle text-success border ms-1 small">Calon</span>
                                    </label>
                                </div>
                            </div>
                            <div id="ketua_warning_box" class="alert alert-warning py-1.5 px-3 mt-2 small d-none">
                                <i class="bi bi-info-circle me-1"></i><span id="ketua_warning_text">Organisasi ini sudah memiliki ketua.</span>
                            </div>
                            <div id="wakil_ketua_warning_box" class="alert alert-warning py-1.5 px-3 mt-2 small d-none">
                                <i class="bi bi-info-circle me-1"></i><span id="wakil_ketua_warning_text">Organisasi ini sudah memiliki wakil ketua.</span>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: KONTAK & DOMISILI --}}
                    <div class="mb-4 form-section-reveal">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <span class="badge rounded-circle bg-success-subtle text-success-nu p-1 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;">3</span>
                            <h2 class="fw-bold text-dark fs-6 mb-0">Kontak & Domisili</h2>
                        </div>

                        <div class="mb-3">
                            <label for="no_hp" class="form-label fw-semibold text-dark fs-sm">Nomor Handphone / WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0 rounded-start-3 fs-sm">
                                    <i class="bi bi-whatsapp"></i>
                                </span>
                                <input type="text" id="no_hp" name="no_hp" class="form-control border-start-0 rounded-end-3 py-2 px-3 fs-sm" value="{{ old('no_hp', $anggota->no_hp) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label fw-semibold text-dark fs-sm">Alamat Lengkap Domisili <span class="text-danger">*</span></label>
                            <textarea id="alamat" name="alamat" rows="3" class="form-control rounded-3 py-2 px-3 fs-sm" required>{{ old('alamat', $anggota->alamat) }}</textarea>
                        </div>
                    </div>

                    {{-- SECTION: PAS FOTO --}}
                    <div class="mb-4 form-section-reveal">
                        <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                            <span class="badge rounded-circle bg-success-subtle text-success-nu p-1 d-inline-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.75rem;">4</span>
                            <h2 class="fw-bold text-dark fs-6 mb-0">Ganti Pas Foto</h2>
                        </div>

                        @if ($anggota->foto)
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-3 border">
                                <img src="{{ asset('storage/' . $anggota->foto) }}" class="rounded-circle object-fit-cover border" style="width: 54px; height: 54px;" alt="{{ $anggota->nama }}">
                                <div>
                                    <span class="fw-semibold text-dark fs-sm d-block">Foto Sebelumnya</span>
                                    <span class="text-muted fs-xs">Kosongkan input di bawah jika tidak ingin mengganti foto ini.</span>
                                </div>
                            </div>
                        @endif

                        <div class="mb-2">
                            <label for="foto" class="form-label fw-semibold text-dark fs-sm">Unggah Foto Baru (Opsional)</label>
                            <input type="file" id="foto" name="foto" class="form-control rounded-3 py-2 px-3 fs-sm" accept="image/*">
                            <div class="form-text fs-xs text-muted">Format: JPG atau PNG maksimal 2 MB.</div>
                        </div>
                    </div>

                    {{-- CTA BUTTON --}}
                    <div class="pt-2 form-section-reveal">
                        <button type="submit" class="btn btn-nu-primary w-100 py-2.5 rounded-pill fw-semibold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                            <i class="bi bi-arrow-repeat"></i> Kirim Ulang Data Pendaftaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4 reveal-on-scroll delay-200">
            <a href="{{ route('anggota.status', $anggota->kode_pendaftaran) }}" class="text-muted fs-sm text-decoration-none link-underline-effect">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Status Pendaftaran
            </a>
        </div>

    </div>
</div>

<style>
    .fs-xs { font-size: 0.75rem !important; }
    .fs-sm { font-size: 0.875rem !important; }

    .form-section-reveal {
        opacity: 0;
        transform: translateY(18px);
        transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .form-section-reveal:nth-child(1) { transition-delay: 60ms; }
    .form-section-reveal:nth-child(2) { transition-delay: 120ms; }
    .form-section-reveal:nth-child(3) { transition-delay: 180ms; }
    .form-section-reveal:nth-child(4) { transition-delay: 240ms; }
    .form-section-reveal:nth-child(5) { transition-delay: 300ms; }

    .form-section-reveal.is-revealed {
        opacity: 1;
        transform: translateY(0);
    }

    @media (prefers-reduced-motion: reduce) {
        .form-section-reveal {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--nu-primary, #2D4736);
        box-shadow: 0 0 0 0.2rem rgba(45, 71, 54, 0.15);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (!('IntersectionObserver' in window)) {
            document.querySelectorAll('.form-section-reveal').forEach(el => el.classList.add('is-revealed'));
            return;
        }
        const sections = document.querySelectorAll('.form-section-reveal');
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -30px 0px' });
        sections.forEach(el => observer.observe(el));

        // Availability check for Ketua and Wakil Ketua
        const orgSelect = document.getElementById('organisasi');
        const radioKetua = document.getElementById('posisi_ketua');
        const radioWakilKetua = document.getElementById('posisi_wakil_ketua');
        const radioAnggota = document.getElementById('posisi_anggota');
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
                    if (radioKetua.checked && radioAnggota) {
                        radioAnggota.checked = true;
                    }
                    if (warningBox) {
                        warningBox.classList.remove('d-none');
                        warningText.textContent = `Organisasi ini sudah memiliki ketua aktif${ketuaName ? ' (' + ketuaName + ')' : ''}. Pilihan Ketua dinonaktifkan.`;
                    }
                } else {
                    radioKetua.disabled = false;
                    if (warningBox) {
                        warningBox.classList.add('d-none');
                    }
                }
            }

            if (radioWakilKetua) {
                if (hasWakilKetua) {
                    radioWakilKetua.disabled = true;
                    if (radioWakilKetua.checked && radioAnggota) {
                        radioAnggota.checked = true;
                    }
                    if (warningWakilBox) {
                        warningWakilBox.classList.remove('d-none');
                        warningWakilText.textContent = `Organisasi ini sudah memiliki wakil ketua aktif${wakilKetuaName ? ' (' + wakilKetuaName + ')' : ''}. Pilihan Wakil Ketua dinonaktifkan.`;
                    }
                } else {
                    radioWakilKetua.disabled = false;
                    if (warningWakilBox) {
                        warningWakilBox.classList.add('d-none');
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