@extends('layouts.admin')

@section('title', 'Edit Anggota: ' . $anggota->nama)

@section('content')
<!-- PAGE TITLE BAR -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-solid badge-solid-forest">
                <i class="bi bi-shield-check"></i> ADMIN PORTAL
            </span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem;">•</span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Edit Anggota</span>
        </div>
        <h1 class="fw-black font-serif text-safe mb-1" style="font-size: 1.85rem; color: var(--brand-forest); letter-spacing: -0.015em;">
            Edit Profil Anggota
        </h1>
        <p class="text-muted text-safe mb-0" style="font-size: 0.86rem; font-weight: 500;">
            Perbarui data identitas, amanah jabatan, nomor kontak, atau foto kader.
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

    <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- FOTO PROFIL -->
            <div class="col-12">
                <label class="form-label-solid">
                    Foto Profil Anggota
                </label>

                <div class="d-flex align-items-center gap-3 p-3 card-solid-cream mb-3">
                    @if ($anggota->foto)
                        <img src="{{ asset('storage/' . $anggota->foto) }}" alt="{{ $anggota->nama }}" class="rounded-circle border-2 border-dark" style="width: 70px; height: 70px; object-fit: cover;">
                    @else
                        <div class="sidebar-user-avatar-solid" style="width: 70px; height: 70px; border-radius: 50%; font-size: 1.5rem; background: var(--brand-forest); color: #FFFFFF;">
                            {{ strtoupper(substr($anggota->nama, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <span class="badge-solid badge-solid-forest mb-1">Foto Anggota Saat Ini</span>
                        <p class="text-muted fs-8 mb-0">Pilih file foto baru di bawah jika ingin memperbarui foto profil.</p>
                    </div>
                </div>

                <input type="file" name="foto" class="form-control-solid" accept="image/*">
            </div>

            <!-- NAMA LENGKAP & ORGANISASI -->
            <div class="col-md-6">
                <label class="form-label-solid">
                    Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="nama" 
                       class="form-control-solid @error('nama') is-invalid @enderror" 
                       value="{{ old('nama', $anggota->nama) }}" 
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label-solid">
                    Organisasi / Banom <span class="text-danger">*</span>
                </label>
                <select name="organisasi" class="form-select-solid @error('organisasi') is-invalid @enderror" required>
                    @foreach(\App\Support\Organisasi::labels() as $orgKey => $orgLabel)
                        <option value="{{ $orgKey }}" {{ old('organisasi', $anggota->organisasi) === $orgKey ? 'selected' : '' }}>
                            {{ $orgLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- JABATAN, STATUS & NO HP -->
            <div class="col-md-4">
                <label class="form-label-solid">
                    Jabatan / Amanah
                </label>
                <input type="text" 
                       name="jabatan" 
                       class="form-control-solid @error('jabatan') is-invalid @enderror" 
                       value="{{ old('jabatan', $anggota->jabatan) }}" 
                       placeholder="Contoh: Ketua / Pengurus">
            </div>

            <div class="col-md-4">
                <label class="form-label-solid">
                    Status Keanggotaan <span class="text-danger">*</span>
                </label>
                <select name="status" class="form-select-solid @error('status') is-invalid @enderror" required>
                    <option value="aktif" {{ old('status', $anggota->status) === 'aktif' ? 'selected' : '' }}>🟢 Aktif</option>
                    <option value="nonaktif" {{ old('status', $anggota->status) === 'nonaktif' ? 'selected' : '' }}>⚪ Nonaktif</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label-solid">
                    Nomor WhatsApp / HP
                </label>
                <input type="text" 
                       name="no_hp" 
                       class="form-control-solid @error('no_hp') is-invalid @enderror" 
                       value="{{ old('no_hp', $anggota->no_hp) }}" 
                       placeholder="08123456789">
            </div>

            <!-- ALAMAT -->
            <div class="col-12">
                <label class="form-label-solid">
                    Alamat Domisili Lengkap
                </label>
                <textarea name="alamat" 
                          rows="3" 
                          class="form-control-solid @error('alamat') is-invalid @enderror" 
                          placeholder="Alamat domisili RT/RW, Dusun, Desa...">{{ old('alamat', $anggota->alamat) }}</textarea>
            </div>

            <div class="col-12 pt-3 border-top border-2 border-dark d-flex flex-wrap align-items-center justify-content-between gap-3">
                <a href="{{ route('admin.anggota.index') }}" class="btn-solid-light btn-solid-press">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit" class="btn-solid-primary btn-solid-press">
                    <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection