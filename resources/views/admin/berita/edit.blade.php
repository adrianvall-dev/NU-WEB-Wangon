@extends('layouts.admin')

@section('title', 'Edit Berita: ' . $news->title)

@section('content')
<!-- HEADER BAR -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-2">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-solid badge-solid-forest">
                <i class="bi bi-shield-check"></i> ADMIN PORTAL
            </span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem;">•</span>
            <span class="text-muted fw-bold" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Edit Berita</span>
        </div>
        <h1 class="fw-black font-serif text-safe mb-1" style="font-size: 1.85rem; color: var(--brand-forest); letter-spacing: -0.015em;">
            Edit Warta &amp; Artikel
        </h1>
        <p class="text-muted text-safe mb-0" style="font-size: 0.86rem; font-weight: 500;">
            Perbarui data warta, judul, kategori, atau lampiran foto artikel.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.berita.index') }}" class="btn-solid-light btn-solid-press">
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
                <span>Terdapat kesalahan pada pengisian formulir:</span>
            </div>
            <ul class="mb-0 ps-4 fs-8">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.berita.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-12">
                <label class="form-label-solid">
                    Judul Berita <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       class="form-control-solid @error('title') is-invalid @enderror" 
                       value="{{ old('title', $news->title) }}" 
                       required>
            </div>

            <div class="col-md-6">
                <label class="form-label-solid">
                    Kategori Organisasi <span class="text-danger">*</span>
                </label>
                <select name="kategori" class="form-select-solid @error('kategori') is-invalid @enderror" required>
                    @foreach(\App\Support\Organisasi::badgeLabelsWithUmum() as $kategoriKey => $kategoriName)
                        <option value="{{ $kategoriKey }}" {{ old('kategori', $news->kategori) === $kategoriKey ? 'selected' : '' }}>
                            {{ $kategoriName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label-solid">
                    Status Publikasi <span class="text-danger">*</span>
                </label>
                <select name="status" class="form-select-solid @error('status') is-invalid @enderror" required>
                    <option value="draft" {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>Draft (Simpan Konsep)</option>
                    <option value="published" {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>Published (Terbit)</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label-solid">
                    Gambar Sampul / Thumbnail
                </label>
                
                @if ($news->image)
                    <div class="mb-3 d-flex align-items-center gap-3 p-3 card-solid-cream">
                        <img src="{{ asset('storage/' . $news->image) }}" alt="Sampul Saat Ini" class="rounded-3 border-2 border-dark" style="width: 100px; height: 70px; object-fit: cover;">
                        <div>
                            <span class="badge-solid badge-solid-forest mb-1">Sampul Saat Ini Aktif</span>
                            <p class="text-muted fs-8 mb-0">Unggah foto baru di bawah jika ingin mengganti gambar ini.</p>
                        </div>
                    </div>
                @endif

                <div class="card-solid-cream p-4 text-center position-relative" style="border-style: dashed;">
                    <input type="file" 
                           name="image" 
                           id="imageInput" 
                           class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" 
                           accept="image/*">
                    <div id="uploadPlaceholder">
                        <div class="sidebar-user-avatar-solid mx-auto mb-2" style="width: 48px; height: 48px; background: #FFFFFF; color: var(--brand-forest);">
                            <i class="bi bi-cloud-arrow-up fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--brand-forest); font-size: 0.92rem;">Pilih foto baru atau seret ke sini</h6>
                        <p class="text-muted fs-8 mb-0">Biarkan kosong jika tidak ingin mengubah gambar sampul</p>
                    </div>
                    <div id="imagePreviewContainer" class="d-none mt-2">
                        <img id="imagePreview" src="#" alt="Preview" class="rounded-3 border-2 border-dark" style="max-height: 180px; max-width: 100%; object-fit: cover;">
                        <div class="mt-2">
                            <span class="badge-solid badge-solid-forest" id="fileNameBadge">File Baru Terpilih</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label-solid">
                    Isi Konten Berita <span class="text-danger">*</span>
                </label>
                <textarea name="content" 
                          rows="10" 
                          class="form-control-solid @error('content') is-invalid @enderror" 
                          required>{{ old('content', $news->content) }}</textarea>
            </div>

            <div class="col-12 pt-3 border-top border-2 border-dark d-flex flex-wrap align-items-center justify-content-between gap-3">
                <a href="{{ route('admin.berita.index') }}" class="btn-solid-light btn-solid-press">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit" class="btn-solid-primary btn-solid-press">
                    <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('imageInput');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        const badge = document.getElementById('fileNameBadge');

        if (input) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                        placeholder.classList.add('d-none');
                        badge.textContent = file.name;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endsection