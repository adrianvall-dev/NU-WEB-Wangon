@extends('layouts.admin')

@section('title', 'Tambah Anggota Baru')

@section('content')
<!-- PAGE TITLE BAR -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-solid badge-solid-forest">
                <i class="bi bi-shield-check"></i> ADMIN PORTAL
            </span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem;">•</span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Direktori Anggota</span>
        </div>
        <h1 class="fw-black font-serif text-safe mb-1" style="font-size: 1.85rem; color: var(--brand-forest); letter-spacing: -0.015em;">
            Tambah Anggota Baru
        </h1>
        <p class="text-muted text-safe mb-0" style="font-size: 0.86rem; font-weight: 500;">
            Tambahkan satu atau beberapa data anggota jam'iyyah sekaligus secara praktis.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.anggota.index') }}" class="btn-solid-light btn-solid-press">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>
</div>

<div class="card-solid p-4 p-md-5 mb-5">
    @if ($errors->any())
        <div class="alert-solid-danger mb-4">
            <div class="d-flex align-items-center gap-2 fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <span>Terdapat kesalahan pada formulir:</span>
            </div>
            <ul class="mb-0 ps-4 fs-8">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.anggota.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- PILIHAN ORGANISASI -->
        <div class="card-solid-cream p-4 mb-4">
            <div class="row align-items-center g-3">
                <div class="col-md-5">
                    <label class="form-label-solid mb-1">
                        Target Organisasi / Badan Otonom <span class="text-danger">*</span>
                    </label>
                    <p class="text-muted fs-8 mb-0">Anggota yang ditambahkan akan terhubung ke organisasi ini.</p>
                </div>
                <div class="col-md-7">
                    <select name="organisasi" class="form-select-solid @error('organisasi') is-invalid @enderror" required>
                        @foreach(\App\Support\Organisasi::labels() as $orgKey => $orgLabel)
                            <option value="{{ $orgKey }}" {{ old('organisasi', 'muslimat') === $orgKey ? 'selected' : '' }}>
                                {{ $orgLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
            <h6 class="fw-bold font-serif mb-0" style="color: var(--brand-forest); font-size: 1.1rem;">
                <i class="bi bi-people me-1"></i> Daftar Input Anggota
            </h6>
        </div>

        <!-- DYNAMIC ROWS CONTAINER -->
        <div id="anggota-rows" class="d-flex flex-column gap-3 mb-4">
            <div class="anggota-row card-solid bg-white p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-2 border-dark">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-solid badge-solid-forest row-number-badge" style="font-size: 0.72rem;">1</span>
                        <span class="fw-bold font-serif" style="color: var(--brand-forest);">Data Anggota</span>
                    </div>
                    <button type="button" class="btn-solid-danger btn-solid-sm btn-remove-row btn-solid-press" style="display:none;">
                        <i class="bi bi-trash"></i> Hapus Baris
                    </button>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label-solid">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama[]" class="form-control-solid" placeholder="Nama anggota..." required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-solid">
                            Jabatan / Amanah
                        </label>
                        <input type="text" name="jabatan[]" class="form-control-solid" placeholder="Contoh: Pengurus / Anggota">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-solid">
                            No. HP / WhatsApp
                        </label>
                        <input type="text" name="no_hp[]" class="form-control-solid" placeholder="08123456789">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-solid">
                            Foto Profil (Opsional)
                        </label>
                        <input type="file" name="foto[]" class="form-control-solid" accept="image/*">
                    </div>

                    <div class="col-12">
                        <label class="form-label-solid">
                            Alamat Domisili
                        </label>
                        <textarea name="alamat[]" rows="2" class="form-control-solid" placeholder="Alamat domisili / desa anggota..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- BUTTON TAMBAH BARIS -->
        <button type="button" id="btn-tambah-baris" class="btn-solid-light w-100 justify-content-center py-2.5 mb-4 btn-solid-press">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Tambah Form Anggota Lainnya</span>
        </button>

        <!-- ACTION BUTTONS -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top border-2 border-dark">
            <a href="{{ route('admin.anggota.index') }}" class="btn-solid-light btn-solid-press">
                <i class="bi bi-x-circle"></i> Batal
            </a>
            <button type="submit" class="btn-solid-primary btn-solid-press">
                <i class="bi bi-check-circle-fill"></i> Simpan Semua Anggota
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('anggota-rows');
    const btnTambah = document.getElementById('btn-tambah-baris');

    function updateRowNumbers() {
        const rows = container.querySelectorAll('.anggota-row');
        rows.forEach((row, index) => {
            const badge = row.querySelector('.row-number-badge');
            if (badge) {
                badge.textContent = index + 1;
            }
            const btnRemove = row.querySelector('.btn-remove-row');
            if (btnRemove) {
                btnRemove.style.display = rows.length > 1 ? 'inline-flex' : 'none';
            }
        });
    }

    btnTambah.addEventListener('click', function () {
        const rows = container.querySelectorAll('.anggota-row');
        const newRow = rows[0].cloneNode(true);

        newRow.querySelectorAll('input, textarea').forEach(function (input) {
            input.value = '';
        });

        container.appendChild(newRow);
        attachRemoveHandler(newRow.querySelector('.btn-remove-row'));
        updateRowNumbers();
    });

    function attachRemoveHandler(button) {
        button.addEventListener('click', function () {
            const rows = container.querySelectorAll('.anggota-row');
            if (rows.length > 1) {
                button.closest('.anggota-row').remove();
                updateRowNumbers();
            }
        });
    }

    container.querySelectorAll('.btn-remove-row').forEach(attachRemoveHandler);
    updateRowNumbers();
});
</script>
@endsection