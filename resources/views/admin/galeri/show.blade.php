@extends('layouts.admin')

@section('title', 'Dokumentasi: ' . $kegiatan->title)

@section('content')
@php
    $fotos = $kegiatan->fotos->filter(fn($f) => $f->isFoto());
    $videos = $kegiatan->fotos->filter(fn($f) => $f->isVideo());
@endphp

<!-- TOP BREADCRUMB & TITLE -->
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
        <a href="{{ route('admin.galeri.index') }}" class="btn-solid-light btn-solid-sm btn-solid-press">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Galeri</span>
        </a>
        <span class="badge-solid badge-solid-forest">
            {{ \App\Support\Organisasi::badgeLabel($kegiatan->kategori) }}
        </span>
        <span class="badge-solid badge-solid-matcha">
            Kegiatan Selesai
        </span>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="fw-black font-serif text-safe mb-1" style="font-size: 1.85rem; color: var(--brand-forest); letter-spacing: -0.015em;">
                {{ $kegiatan->title }}
            </h1>
            <div class="d-flex align-items-center gap-3 text-muted fs-8 fw-semibold flex-wrap">
                <span>
                    <i class="bi bi-calendar-event me-1 text-forest"></i>
                    {{ $kegiatan->event_date ? \Carbon\Carbon::parse($kegiatan->event_date)->translatedFormat('l, d F Y') : '-' }}
                </span>
                <span>&bull;</span>
                <span>
                    <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                    {{ $kegiatan->location }}
                </span>
                @if(method_exists($kegiatan, 'hasCoordinates') && $kegiatan->hasCoordinates())
                    <span>&bull;</span>
                    <a href="{{ $kegiatan->google_maps_url }}" target="_blank" class="fw-bold" style="color: var(--brand-forest); text-decoration: none;">
                        <i class="bi bi-map me-1"></i> Buka Maps
                    </a>
                @endif
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('galeri.show', $kegiatan->id) }}" target="_blank" class="btn-solid-light btn-solid-press" title="Lihat di Web Publik">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Lihat Publik</span>
            </a>
            <a href="{{ route('admin.kegiatan.edit', $kegiatan->id) }}" class="btn-solid-light btn-solid-press">
                <i class="bi bi-pencil"></i>
                <span>Edit Kegiatan</span>
            </a>
        </div>
    </div>
</div>

<!-- INFORMASI DESKRIPSI KEGIATAN -->
<div class="card-solid-cream p-3 p-md-4 mb-4">
    <div class="d-flex align-items-start gap-3">
        <div class="sidebar-user-avatar-solid" style="width: 40px; height: 40px; background: var(--brand-forest); color: #FFFFFF;">
            <i class="bi bi-info-circle-fill fs-5"></i>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <h6 class="fw-bold font-serif mb-0" style="color: var(--brand-forest);">Deskripsi Resmi Kegiatan</h6>
                <span class="badge-solid badge-solid-forest" style="font-size: 0.65rem;">DATABASE ASLI</span>
            </div>
            <p class="text-muted fs-8 mb-2" style="white-space: pre-line; line-height: 1.6;">
                {{ $kegiatan->description }}
            </p>
            <div class="badge-solid badge-solid-matcha" style="font-size: 0.72rem;">
                <i class="bi bi-check-circle-fill"></i> Data diambil langsung dari tabel kegiatan dan terhubung ke halaman galeri publik.
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- PANEL KIRI: UPLOAD FOTO & VIDEO -->
    <div class="col-12 col-lg-5">
        <div class="card-solid p-4 h-100">
            <!-- Tabs Toggle: Upload Foto / Upload Video -->
            <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom border-2 border-dark">
                <button type="button" class="btn-solid-light btn-solid-sm btn-solid-primary" id="btnTabFoto" onclick="switchUploadTab('foto')">
                    <i class="bi bi-images me-1"></i> Upload Foto Dokumentasi
                </button>
                <button type="button" class="btn-solid-light btn-solid-sm" id="btnTabVideo" onclick="switchUploadTab('video')">
                    <i class="bi bi-film me-1"></i> Upload Video Dokumentasi
                </button>
            </div>

            <!-- FORM 1: UPLOAD FOTO DOKUMENTASI -->
            <div id="formFotoWrapper">
                <p class="text-muted fs-8 mb-3">
                    Pilih atau seret beberapa foto sekaligus untuk ditambahkan ke dokumentasi kegiatan ini.
                </p>

                <form action="{{ route('admin.galeri.upload', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Dropzone Foto -->
                    <div class="card-solid-cream p-4 text-center mb-3 cursor-pointer position-relative" style="border-style: dashed;" onclick="document.getElementById('fotosInput').click();">
                        <input type="file" 
                               id="fotosInput" 
                               name="fotos[]" 
                               multiple 
                               accept="image/jpeg,image/png,image/jpg,image/webp,image/gif" 
                               class="d-none"
                               onchange="handleFilesSelected(this.files)">
                        
                        <div class="sidebar-user-avatar-solid mx-auto mb-2" style="width: 44px; height: 44px; background: #FFFFFF; color: var(--brand-forest);">
                            <i class="bi bi-images fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--brand-forest); font-size: 0.92rem;">Klik untuk Memilih Foto</h6>
                        <p class="text-muted fs-8 mb-2">Bisa memilih banyak foto sekaligus (Maks. 5 MB/foto)</p>
                        <span class="badge-solid badge-solid-forest" style="font-size: 0.65rem;">
                            JPG, PNG, WEBP, GIF
                        </span>
                        <div id="fileSelectedCount" class="mt-2 text-forest fw-bold fs-8 d-none">
                            <i class="bi bi-check-circle-fill me-1"></i> <span id="countText">0</span> file foto dipilih
                        </div>
                    </div>

                    @error('fotos')
                        <div class="alert-solid-danger fs-8 py-2 px-3 mb-3">{{ $message }}</div>
                    @enderror
                    @error('fotos.*')
                        <div class="alert-solid-danger fs-8 py-2 px-3 mb-3">{{ $message }}</div>
                    @enderror

                    <!-- Caption Foto -->
                    <div class="mb-3">
                        <label class="form-label-solid">Keterangan / Caption Foto (Opsional)</label>
                        <input type="text" 
                               name="caption" 
                               class="form-control-solid" 
                               placeholder="Contoh: Suasana pembukaan acara..."
                               value="{{ old('caption') }}">
                    </div>

                    <button type="submit" class="btn-solid-primary w-100 justify-content-center btn-solid-press">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                        <span>Unggah Foto ke Galeri</span>
                    </button>
                </form>
            </div>

            <!-- FORM 2: UPLOAD VIDEO DOKUMENTASI -->
            <div id="formVideoWrapper" class="d-none">
                <p class="text-muted fs-8 mb-3">
                    Unggah video dokumentasi kegiatan (MP4, WebM, MOV) hingga 50 MB. Rasio portrait maupun landscape akan dipertahankan.
                </p>

                <form action="{{ route('admin.galeri.upload-video', $kegiatan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Dropzone Video -->
                    <div class="card-solid-cream p-4 text-center mb-3 cursor-pointer position-relative" style="border-style: dashed;" onclick="document.getElementById('videoInput').click();">
                        <input type="file" 
                               id="videoInput" 
                               name="video" 
                               accept="video/mp4,video/webm,video/quicktime,video/ogg" 
                               class="d-none"
                               onchange="handleVideoSelected(this.files)">
                        
                        <div class="sidebar-user-avatar-solid mx-auto mb-2" style="width: 44px; height: 44px; background: #FFFFFF; color: var(--brand-orange);">
                            <i class="bi bi-film fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--brand-forest); font-size: 0.92rem;">Klik untuk Memilih File Video</h6>
                        <p class="text-muted fs-8 mb-2">Ukuran maksimal file: 50 MB</p>
                        <span class="badge-solid badge-solid-orange" style="font-size: 0.65rem;">
                            MP4, WebM, MOV, OGG
                        </span>
                        <div id="videoSelectedName" class="mt-2 text-danger fw-bold fs-8 d-none">
                            <i class="bi bi-play-circle-fill me-1"></i> <span id="videoNameText">File video</span>
                        </div>
                    </div>

                    @error('video')
                        <div class="alert-solid-danger fs-8 py-2 px-3 mb-3">{{ $message }}</div>
                    @enderror

                    <!-- Caption Video -->
                    <div class="mb-3">
                        <label class="form-label-solid">Keterangan / Judul Video (Opsional)</label>
                        <input type="text" 
                               name="caption" 
                               class="form-control-solid" 
                               placeholder="Contoh: Video kilas balik kegiatan..."
                               value="{{ old('caption') }}">
                    </div>

                    <button type="submit" class="btn-solid-accent w-100 justify-content-center btn-solid-press">
                        <i class="bi bi-camera-video-fill"></i>
                        <span>Unggah Video ke Galeri</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- PANEL KANAN: KOLEKSI MEDIA -->
    <div class="col-12 col-lg-7">
        <div class="card-solid p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-2 border-dark">
                <h5 class="fw-bold font-serif mb-0" style="color: var(--brand-forest);">Koleksi Dokumentasi</h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge-solid badge-solid-forest">
                        <i class="bi bi-images"></i> {{ $fotos->count() }} Foto
                    </span>
                    <span class="badge-solid badge-solid-orange">
                        <i class="bi bi-film"></i> {{ $videos->count() }} Video
                    </span>
                </div>
            </div>

            @if($kegiatan->fotos->isEmpty())
                <div class="text-center py-5">
                    <div class="sidebar-user-avatar-solid mx-auto mb-3" style="width: 56px; height: 56px; background: var(--brand-sand); color: var(--brand-forest);">
                        <i class="bi bi-camera-reels fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1 font-serif" style="color: var(--brand-forest);">Belum Ada Media Dokumentasi</h6>
                    <p class="text-muted fs-8 mb-0 max-w-sm mx-auto">
                        Gunakan formulir di sebelah kiri untuk mengunggah foto atau video dokumentasi untuk kegiatan ini.
                    </p>
                </div>
            @else
                <!-- SECTION 1: VIDEO (JIKA ADA) -->
                @if($videos->isNotEmpty())
                    <div class="mb-4">
                        <h6 class="fw-bold font-serif fs-8 mb-2 d-flex align-items-center gap-1.5" style="color: var(--brand-forest); text-transform: uppercase;">
                            <i class="bi bi-film text-danger"></i>
                            <span>Video Dokumentasi ({{ $videos->count() }})</span>
                        </h6>
                        <div class="row g-3">
                            @foreach($videos as $video)
                                <div class="col-12 col-sm-6">
                                    <div class="card-solid-sm bg-white p-0 overflow-hidden d-flex flex-column justify-content-between h-100">
                                        <div class="position-relative bg-black" style="min-height: 160px; display: flex; align-items: center; justify-content: center;">
                                            <video controls playsinline preload="metadata" 
                                                   class="w-100 h-auto" 
                                                   style="max-height: 200px; object-fit: contain; background: #000;">
                                                <source src="{{ asset('storage/' . $video->file_path) }}">
                                                Browser Anda tidak mendukung video HTML5.
                                            </video>

                                            <div class="position-absolute top-0 end-0 m-2">
                                                <form action="{{ route('admin.galeri.foto.destroy', $video->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus video dokumentasi ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-solid-danger btn-solid-sm btn-solid-press" title="Hapus Video">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="p-2 border-top border-2 border-dark">
                                            @if($video->caption)
                                                <p class="text-dark fw-bold fs-8 mb-1 text-truncate" title="{{ $video->caption }}">
                                                    {{ $video->caption }}
                                                </p>
                                            @endif
                                            <div class="text-muted fs-8 d-flex justify-content-between align-items-center">
                                                <span>{{ $video->created_at->diffForHumans() }}</span>
                                                <span class="badge-solid badge-solid-orange" style="font-size: 0.6rem;">Video</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- SECTION 2: FOTO (JIKA ADA) - MASONRY REACT BITS STYLE -->
                @if($fotos->isNotEmpty())
                    <div class="history-photo-masonry" data-item-count="{{ $fotos->count() }}">
                        <div class="history-photo-masonry__header">
                            <h6 class="history-photo-masonry__title">
                                <i class="bi bi-images text-forest"></i>
                                <span>Foto Dokumentasi ({{ $fotos->count() }})</span>
                            </h6>
                            <span class="badge-solid badge-solid-forest" style="font-size: 0.65rem;">
                                Masonry Showcase
                            </span>
                        </div>

                        <div class="history-photo-masonry__list">
                            @foreach($fotos as $index => $foto)
                                <div class="history-photo-masonry__item" data-id="{{ $foto->id }}" data-index="{{ $index + 1 }}">
                                    <div class="history-photo-masonry__card">
                                        <div class="history-photo-masonry__media" 
                                             data-full-src="{{ asset('storage/' . $foto->file_path) }}" 
                                             data-caption="{{ $foto->caption ?? $kegiatan->title }}">
                                            <img src="{{ asset('storage/' . $foto->file_path) }}" 
                                                 alt="{{ $foto->caption ?? $kegiatan->title }}" 
                                                 class="history-photo-masonry__img" 
                                                 loading="lazy">
                                            
                                            <!-- Floating Badge / Counter -->
                                            <span class="history-photo-masonry__badge-counter">
                                                #{{ $index + 1 }}
                                            </span>

                                            <!-- Floating Actions: Preview and Delete -->
                                            <div class="history-photo-masonry__actions">
                                                <button type="button" 
                                                        class="history-photo-masonry__btn-preview" 
                                                        title="Perbesar Foto"
                                                        aria-label="Perbesar Foto">
                                                    <i class="bi bi-arrows-fullscreen"></i>
                                                </button>
                                                <form action="{{ route('admin.galeri.foto.destroy', $foto->id) }}" 
                                                      method="POST" 
                                                      class="m-0 p-0 d-inline"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto dokumentasi ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="history-photo-masonry__btn-delete" 
                                                            title="Hapus Foto"
                                                            aria-label="Hapus Foto">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="history-photo-masonry__footer">
                                            @if($foto->caption)
                                                <p class="history-photo-masonry__footer-caption" title="{{ $foto->caption }}">
                                                    {{ $foto->caption }}
                                                </p>
                                            @endif
                                            <div class="history-photo-masonry__footer-meta">
                                                <span>{{ $foto->created_at->diffForHumans() }}</span>
                                                <span class="history-photo-masonry__footer-badge">Foto</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- MODAL PREVIEW FOTO INSPECT / ZOOM IMAGE -->
<div id="historyPhotoModal" class="history-photo-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Peninjau Foto">
    <div class="history-photo-modal__backdrop" title="Klik di luar foto untuk menutup"></div>
    <div class="history-photo-modal__dialog">
        <button type="button" class="history-photo-modal__close" aria-label="Tutup preview (Escape)">
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="history-photo-modal__body">
            <img id="historyPhotoModalImg" src="" alt="Preview Dokumentasi" class="history-photo-modal__img">
        </div>
        <div class="history-photo-modal__footer">
            <p id="historyPhotoModalCaption" class="history-photo-modal__caption"></p>
            <a id="historyPhotoModalDownload" href="" target="_blank" download class="history-photo-modal__download">
                <i class="bi bi-download"></i>
                <span>Unduh HD</span>
            </a>
        </div>
    </div>
</div>

<script>
    function switchUploadTab(type) {
        const btnFoto = document.getElementById('btnTabFoto');
        const btnVideo = document.getElementById('btnTabVideo');
        const formFoto = document.getElementById('formFotoWrapper');
        const formVideo = document.getElementById('formVideoWrapper');

        if (type === 'foto') {
            btnFoto.classList.add('btn-solid-primary');
            btnVideo.classList.remove('btn-solid-primary');
            formFoto.classList.remove('d-none');
            formVideo.classList.add('d-none');
        } else {
            btnVideo.classList.add('btn-solid-primary');
            btnFoto.classList.remove('btn-solid-primary');
            formVideo.classList.remove('d-none');
            formFoto.classList.add('d-none');
        }
    }

    function handleFilesSelected(files) {
        const countBox = document.getElementById('fileSelectedCount');
        const countText = document.getElementById('countText');
        if (files && files.length > 0) {
            countText.textContent = files.length;
            countBox.classList.remove('d-none');
        } else {
            countBox.classList.add('d-none');
        }
    }

    function handleVideoSelected(files) {
        const nameBox = document.getElementById('videoSelectedName');
        const nameText = document.getElementById('videoNameText');
        if (files && files.length > 0) {
            nameText.textContent = files[0].name;
            nameBox.classList.remove('d-none');
        } else {
            nameBox.classList.add('d-none');
        }
</script>

@push('scripts')
    @vite(['resources/js/history-photo-masonry.js'])
@endpush
@endsection
