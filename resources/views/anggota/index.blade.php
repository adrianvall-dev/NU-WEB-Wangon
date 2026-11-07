@extends('layouts.app')

@section('title', 'Direktori Anggota & KARTANU - Organisasi NU Wangon')

@section('content')

<div class="min-h-screen bg-brand-cream text-brand-darktext font-sans antialiased pb-24 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- ================= 1. HERO SECTION (LUXURY EDITORIAL NU ARCHITECTURE) ================= -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest p-4 sm:p-12 lg:p-14 shadow-solid-md relative overflow-hidden">
            <!-- Abstract Geometric Background (PixelOS Inspiration - Large Header Geometry with 5px Stroke & Translucent Fill) -->
            <div class="absolute inset-0 pixel-grid-blueprint opacity-60 pointer-events-none -z-0"></div>
            <div class="pointer-events-none absolute -bottom-24 -right-24 w-80 h-80 sm:w-96 sm:h-96 rounded-full border-[5px] border-brand-forest/[0.24] flex items-center justify-center p-14 hidden sm:flex -z-0" data-parallax="bg-slow" data-parallax-rotate="4">
                <div class="w-full h-full rounded-full border-[3px] border-dashed border-brand-matcha/[0.28] bg-brand-lightmatcha/[0.15]"></div>
            </div>
            <div class="pointer-events-none absolute top-6 right-8 text-brand-forest/[0.35] text-sm font-mono font-bold select-none hidden sm:block -z-0">+</div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
                <div class="lg:col-span-8 space-y-5">
                    <div data-text-animate="blurInUp" class="inline-flex items-center gap-2.5 px-4 py-1.5 bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest rounded-full text-xs font-black uppercase tracking-wider shadow-solid-sm">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-forest animate-pulse"></span>
                        <span>DIREKTORI &amp; INVENTARISASI KADER RESMI</span>
                    </div>

                    <h1 data-text-animate="blurInUp" class="text-3xl sm:text-5xl lg:text-6xl font-black text-brand-deep tracking-tight leading-[1.1]">
                        Direktori Anggota <br>
                        <span class="inline-block bg-brand-lightmatcha text-brand-forest px-4 py-1 rounded-2xl border-2 border-brand-forest shadow-solid-sm mt-2 text-2xl sm:text-4xl lg:text-5xl">
                            Organisasi NU Wangon
                        </span>
                    </h1>

                    <p class="text-sm sm:text-base font-medium text-brand-darktext/80 max-w-xl leading-relaxed">
                        Sistem basis data terpadu keanggotaan dan e-KARTANU bagi seluruh kader Badan Otonom dan Lembaga Nahdlatul Ulama se-Kecamatan Wangon.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                        <a href="{{ route('anggota.daftar') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-brand-forest hover:bg-brand-deep text-white px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all btn-solid-press border-2 border-brand-deep shadow-solid-sm text-decoration-none">
                            <i class="bi bi-person-plus-fill text-sm"></i>
                            <span>Daftar Anggota (KARTANU)</span>
                        </a>

                        <a href="{{ route('anggota.cek-status') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-brand-sand hover:bg-[#DDD7C8] text-brand-forest border-2 border-brand-forest px-6 py-3.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all btn-solid-press shadow-solid-sm text-decoration-none">
                            <i class="bi bi-shield-check text-base"></i>
                            <span>Cek Status Terdaftar</span>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-4 flex flex-col gap-4">
                    <div class="bg-brand-cream border-2 border-brand-forest p-6 rounded-2xl space-y-2 shadow-solid-sm">
                        <div class="flex items-center justify-between text-brand-forest/80 text-xs font-black uppercase tracking-wider">
                            <span>Total Anggota Aktif</span>
                            <i class="bi bi-people-fill text-brand-forest text-lg"></i>
                        </div>
                        <div class="text-4xl font-black text-brand-deep tracking-tight">
                            <span data-number-ticker="{{ $stats['total'] }}">{{ number_format($stats['total']) }}</span> <span class="text-xs font-bold text-brand-darktext/70 uppercase">Kader Terdata</span>
                        </div>
                        <p class="text-[11px] font-medium text-brand-darktext/70 m-0 pt-1 border-t border-brand-forest/20">
                            Sistem database terintegrasi keanggotaan internal MWC NU Wangon.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= 2. RINGKASAN ORGANISASI / BANOM ================= -->
        @php
            $banomCards = [
                [
                    'key' => null,
                    'icon' => 'bi-grid-fill',
                    'label' => 'SEMUA',
                    'count' => $stats['total'],
                    'desc' => 'Total seluruh banom',
                ],
                [
                    'key' => 'ansor',
                    'icon' => 'bi-shield-check',
                    'label' => 'GP ANSOR',
                    'count' => $stats['ansor'],
                    'desc' => 'Pemuda & Banser',
                ],
                [
                    'key' => 'fatayat',
                    'icon' => 'bi-flower1',
                    'label' => 'FATAYAT NU',
                    'count' => $stats['fatayat'],
                    'desc' => 'Pemudi Nahdlatul Ulama',
                ],
                [
                    'key' => 'muslimat',
                    'icon' => 'bi-heart-fill',
                    'label' => 'MUSLIMAT NU',
                    'count' => $stats['muslimat'],
                    'desc' => 'Kaum Ibu Nahdlatul Ulama',
                ],
                [
                    'key' => 'ipnu',
                    'icon' => 'bi-book-fill',
                    'label' => 'IPNU',
                    'count' => $stats['ipnu'],
                    'desc' => 'Pelajar Putra NU',
                ],
                [
                    'key' => 'ippnu',
                    'icon' => 'bi-mortarboard-fill',
                    'label' => 'IPPNU',
                    'count' => $stats['ippnu'],
                    'desc' => 'Pelajar Putri NU',
                ],
                [
                    'key' => 'pagar_nusa',
                    'icon' => 'bi-shield-shaded',
                    'label' => 'PAGAR NUSA',
                    'count' => $stats['pagar_nusa'],
                    'desc' => 'Pencak Silat NU',
                ],
            ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-3 sm:gap-4">
            @foreach($banomCards as $item)
                @php
                    $isActive = ($item['key'] && request('organisasi') === $item['key']) || (!$item['key'] && !request('organisasi'));
                    $filterUrl = $item['key']
                        ? route('anggota.index', array_filter(['organisasi' => $item['key'], 'search' => request('search')]))
                        : route('anggota.index', array_filter(['search' => request('search')]));
                @endphp
                <a href="{{ $filterUrl }}"
                   class="rounded-2xl p-3 sm:p-5 flex flex-col justify-between transition-all duration-200 border-2 border-brand-forest text-decoration-none btn-solid-press min-h-[170px] sm:min-h-[200px] {{ $isActive ? 'bg-brand-lightmatcha shadow-solid-md' : 'bg-white hover:bg-brand-sand shadow-solid-sm' }}"
                   data-parallax="card-small"
                   title="Filter {{ $item['label'] }}">
                    <div>
                        <!-- Icon -->
                        <div class="w-10 h-10 rounded-xl {{ $isActive ? 'bg-brand-forest text-white' : 'bg-brand-sand text-brand-forest' }} border-2 border-brand-forest flex items-center justify-center text-base mb-3 shadow-solid-sm">
                            <i class="bi {{ $item['icon'] }}"></i>
                        </div>

                        <!-- Label Kategori -->
                        <span class="text-[11px] font-black uppercase tracking-wider text-brand-forest leading-tight block mb-1">
                            {{ $item['label'] }}
                        </span>

                        <!-- Count -->
                        <span class="text-2xl font-black text-brand-deep leading-none block">
                            <span data-number-ticker="{{ $item['count'] }}">{{ number_format($item['count']) }}</span>
                        </span>
                    </div>

                    <!-- Description -->
                    <div class="pt-2.5 border-t-2 border-brand-sand mt-2">
                        <p class="text-[11px] font-medium text-brand-darktext/70 leading-snug m-0">
                            {{ $item['desc'] }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- ================= 2.5 DETAIL ORGANISASI: STRUKTUR, VISI & MISI ================= -->
        @if(!empty($currentOrganisasi))
            @php
                $ketua = $currentOrganisasi->getActiveKetua();
                $wakilKetua = $currentOrganisasi->getActiveWakilKetua();
                $misiList = $currentOrganisasi->getMisiList();
            @endphp
            <div class="bg-white rounded-3xl border-2 border-brand-forest p-6 sm:p-10 lg:p-12 shadow-solid-md space-y-10">
                
                <!-- HEADER ORGANISASI (Logo, Nama, Deskripsi) -->
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-8 border-b-2 border-brand-sand">
                    <div class="flex items-center gap-5 sm:gap-6">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-brand-sand border-2 border-brand-forest p-2 flex items-center justify-center shrink-0 shadow-solid-sm overflow-hidden">
                            @if($currentOrganisasi->logo_url)
                                <img src="{{ $currentOrganisasi->logo_url }}" alt="{{ $currentOrganisasi->nama }}" class="w-full h-full object-contain">
                            @else
                                <div class="w-full h-full rounded-xl bg-brand-forest flex items-center justify-center text-white text-3xl font-black">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-1.5">
                            <div data-text-animate="blurInUp" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-lightmatcha text-brand-forest text-[11px] font-black uppercase tracking-wider border border-brand-forest">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-forest"></span>
                                <span>Badan Otonom NU Wangon</span>
                            </div>
                            <h2 data-text-animate="blurInUp" class="text-2xl sm:text-3xl lg:text-4xl font-black text-brand-deep leading-tight">
                                {{ $currentOrganisasi->nama }}
                            </h2>
                            <p class="text-xs sm:text-sm font-medium text-brand-darktext/75 max-w-2xl leading-relaxed m-0">
                                {{ $currentOrganisasi->deskripsi ?: 'Profil keorganisasian resmi, susunan struktur kepengurusan, visi dan misi Jam\'iyyah Nahdlatul Ulama tingkat Wangon.' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('anggota.index') }}" 
                           class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider px-4 py-2.5 rounded-xl bg-brand-sand text-brand-forest border-2 border-brand-forest hover:bg-brand-forest hover:text-white transition-all btn-solid-press shadow-solid-sm text-decoration-none">
                            <i class="bi bi-x-lg text-xs"></i>
                            <span>Tutup Detail</span>
                        </a>
                    </div>
                </div>

                <!-- STRUKTUR KEPENGURUSAN -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-brand-matcha block">STRUKTUR KEPENGURUSAN</span>
                            <h3 class="text-2xl font-black text-brand-deep tracking-tight mt-0.5">Pimpinan Organisasi</h3>
                        </div>
                        <span class="text-xs font-bold text-brand-darktext/70 hidden sm:inline-block">Periode Khidmah Aktif</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- KETUA -->
                        <div class="bg-brand-cream border-2 border-brand-forest rounded-2xl p-5 sm:p-6 flex items-center gap-4 shadow-solid-sm">
                            <div class="w-16 h-16 rounded-full bg-brand-sand border-2 border-brand-forest overflow-hidden shrink-0 shadow-solid-sm flex items-center justify-center">
                                @if($ketua && $ketua->foto)
                                    <img src="{{ asset('storage/' . $ketua->foto) }}" alt="{{ $ketua->nama }}" class="w-full h-full object-cover">
                                @else
                                    <i class="bi bi-person-fill text-2xl text-brand-forest/60"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest block">Ketua</span>
                                @if($ketua)
                                    <h4 class="font-black text-lg text-brand-deep truncate">{{ $ketua->nama }}</h4>
                                    <p class="text-xs font-medium text-brand-darktext/70 m-0">{{ $ketua->nomor_anggota ?? 'Kader Aktif' }}</p>
                                @else
                                    <h4 class="font-bold text-base text-brand-darktext/60 italic">Ketua belum ditentukan</h4>
                                @endif
                            </div>
                        </div>

                        <!-- WAKIL KETUA -->
                        <div class="bg-brand-cream border-2 border-brand-forest rounded-2xl p-5 sm:p-6 flex items-center gap-4 shadow-solid-sm">
                            <div class="w-16 h-16 rounded-full bg-brand-sand border-2 border-brand-forest overflow-hidden shrink-0 shadow-solid-sm flex items-center justify-center">
                                @if($wakilKetua && $wakilKetua->foto)
                                    <img src="{{ asset('storage/' . $wakilKetua->foto) }}" alt="{{ $wakilKetua->nama }}" class="w-full h-full object-cover">
                                @else
                                    <i class="bi bi-person-fill text-2xl text-brand-forest/60"></i>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest block">Wakil Ketua</span>
                                @if($wakilKetua)
                                    <h4 class="font-black text-lg text-brand-deep truncate">{{ $wakilKetua->nama }}</h4>
                                    <p class="text-xs font-medium text-brand-darktext/70 m-0">{{ $wakilKetua->nomor_anggota ?? 'Kader Aktif' }}</p>
                                @else
                                    <h4 class="font-bold text-base text-brand-darktext/60 italic">Wakil Ketua belum ditentukan</h4>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- HIERARKI DIAGRAM -->
                    <div class="bg-brand-forest text-brand-lightmatcha rounded-2xl p-5 sm:p-6 font-mono text-xs sm:text-sm border-2 border-brand-deep shadow-solid-sm overflow-x-auto">
                        <div class="flex items-center justify-between pb-3 mb-3 border-b border-white/10 text-white/70 text-xs">
                            <span class="flex items-center gap-2 font-black">
                                <i class="bi bi-diagram-3-fill text-brand-lightmatcha"></i>
                                <span>DIAGRAM HIERARKI KEPENGURUSAN</span>
                            </span>
                            <span class="text-[10px] uppercase tracking-wider font-bold text-brand-lightmatcha">STRUKTUR RESMI</span>
                        </div>
                        <pre class="m-0 font-mono leading-relaxed text-brand-lightmatcha whitespace-pre">[{{ $currentOrganisasi->nama }}]
  ├── Ketua: {{ $ketua ? $ketua->nama : 'Belum ditentukan' }}
  ├── Wakil Ketua: {{ $wakilKetua ? $wakilKetua->nama : 'Belum ditentukan' }}
  ├── Visi: {{ $currentOrganisasi->visi ? Str::limit($currentOrganisasi->visi, 50) : 'Belum ditambahkan' }}
  └── Misi: {{ count($misiList) > 0 ? count($misiList) . ' Butir Misi' : 'Belum ditambahkan' }}</pre>
                    </div>
                </div>

                <!-- VISI & MISI -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-4 border-t-2 border-brand-sand">
                    <!-- VISI -->
                    <div class="lg:col-span-5 space-y-4">
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-brand-matcha block">VISI ORGANISASI</span>
                            <h3 class="text-2xl font-black text-brand-deep tracking-tight mt-0.5">Arah &amp; Cita-cita</h3>
                        </div>

                        @if($currentOrganisasi->visi)
                            <div class="bg-brand-cream border-2 border-brand-forest rounded-2xl p-6 shadow-solid-sm relative">
                                <i class="bi bi-quote text-3xl text-brand-forest/20 absolute top-4 right-5"></i>
                                <p class="text-sm sm:text-base font-bold text-brand-deep leading-relaxed italic m-0 relative z-10">
                                    &ldquo;{{ $currentOrganisasi->visi }}&rdquo;
                                </p>
                            </div>
                        @else
                            <div class="bg-brand-cream border-2 border-dashed border-brand-forest rounded-2xl p-6 text-center space-y-2">
                                <i class="bi bi-file-earmark-text text-brand-forest/40 text-2xl"></i>
                                <p class="text-xs text-brand-darktext/60 italic m-0">Visi belum ditambahkan.</p>
                            </div>
                        @endif
                    </div>

                    <!-- MISI -->
                    <div class="lg:col-span-7 space-y-4">
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-brand-matcha block">MISI ORGANISASI</span>
                            <h3 class="text-2xl font-black text-brand-deep tracking-tight mt-0.5">Langkah &amp; Program Khidmah</h3>
                        </div>

                        @if(count($misiList) > 0)
                            <ol class="space-y-3 m-0 p-0 list-none">
                                @foreach($misiList as $idx => $misiItem)
                                    <li class="flex items-start gap-3 bg-brand-cream border-2 border-brand-forest rounded-xl p-3.5 shadow-solid-sm">
                                        <span class="w-6 h-6 rounded-lg bg-brand-forest text-white flex items-center justify-center shrink-0 text-xs font-black mt-0.5">
                                            {{ $idx + 1 }}
                                        </span>
                                        <p class="text-xs sm:text-sm font-bold text-brand-deep leading-relaxed m-0 pt-0.5">
                                            {{ $misiItem }}
                                        </p>
                                    </li>
                                @endforeach
                            </ol>
                        @else
                            <div class="bg-brand-cream border-2 border-dashed border-brand-forest rounded-2xl p-6 text-center space-y-2">
                                <i class="bi bi-list-check text-brand-forest/40 text-2xl"></i>
                                <p class="text-xs text-brand-darktext/60 italic m-0">Misi belum ditambahkan.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @endif

        <!-- ================= 3. SECTION HEADER ANGGOTA & SEARCH ================= -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pt-2">
            <div class="space-y-1">
                <span data-text-animate="blurInUp" class="text-xs font-black uppercase tracking-wider text-brand-matcha">DIREKTORI DATA</span>
                <h2 data-text-animate="blurInUp" class="text-3xl sm:text-4xl font-black text-brand-deep tracking-tight">
                    Daftar Anggota &amp; Kader
                </h2>
                <p class="text-xs sm:text-sm font-medium text-brand-darktext/75 m-0">
                    Kenali anggota dan keluarga besar Organisasi NU Wangon yang aktif berkhidmah.
                </p>
            </div>

            <div class="w-full md:w-auto shrink-0">
                <form action="{{ route('anggota.index') }}" method="GET" class="m-0">
                    @if(request('organisasi'))
                        <input type="hidden" name="organisasi" value="{{ request('organisasi') }}">
                    @endif
                    <div class="flex items-center bg-white rounded-2xl p-1.5 border-2 border-brand-forest shadow-solid-sm focus-within:shadow-solid-md w-full md:w-[340px] transition-all">
                        <span class="pl-3.5 pr-2 text-brand-forest text-base">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               class="w-full bg-transparent border-0 py-2 px-1 text-xs sm:text-sm font-bold text-brand-darktext focus:outline-none placeholder:text-brand-darktext/50" 
                               placeholder="Cari nama atau ID anggota...">
                        @if(request('search'))
                            <a href="{{ route('anggota.index', array_filter(['organisasi' => request('organisasi')])) }}" 
                               class="px-2 text-brand-darktext/60 hover:text-rose-600 transition-colors" 
                               title="Hapus pencarian">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                        <button type="submit" 
                                class="bg-brand-forest hover:bg-brand-deep text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all btn-solid-press border border-brand-deep shadow-solid-sm shrink-0">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= 4. DAFTAR KARTU ANGGOTA (3D 2-SIDED IDENTITY CARDS) ================= -->
        @if ($anggotaList->isEmpty())
            <div class="bg-white border-2 border-dashed border-brand-forest rounded-3xl p-10 sm:p-14 text-center space-y-4 my-4 shadow-solid-sm">
                <div class="w-16 h-16 rounded-2xl bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest flex items-center justify-center text-2xl mx-auto shadow-solid-sm">
                    <i class="bi bi-search"></i>
                </div>
                <div class="space-y-1.5 max-w-md mx-auto">
                    <h3 class="font-black text-2xl text-brand-deep tracking-tight">Tidak Ada Anggota Ditemukan</h3>
                    <p class="text-xs sm:text-sm text-brand-darktext/75 leading-relaxed">
                        @if(request('search'))
                            Tidak ada anggota yang cocok dengan kata kunci &ldquo;{{ request('search') }}&rdquo;.
                        @else
                            Belum ada data anggota yang terdaftar pada kriteria organisasi ini.
                        @endif
                    </p>
                </div>
                @if(request('search') || request('organisasi'))
                    <a href="{{ route('anggota.index') }}" 
                       class="inline-flex items-center gap-2 bg-brand-forest hover:bg-brand-deep text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-solid-sm btn-solid-press text-decoration-none">
                        <span>Tampilkan Semua Anggota</span>
                    </a>
                @endif
            </div>
        @else
            <!-- GRID CARDS WITH 3D FLIP ARCHITECTURE -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($anggotaList as $item)
                    @php
                        $prefix = \App\Support\Organisasi::prefix($item->organisasi);
                        $formattedId = $item->nomor_anggota ?? ($prefix . '-' . str_pad($item->id, 3, '0', STR_PAD_LEFT));
                        $verifKode = $item->kode_verifikasi ?? ($item->kode_pendaftaran ?? 'NU-' . $item->id);
                        $kodeKartu = $item->kode_pendaftaran ?? $item->id;
                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=" . urlencode(route('verifikasi.show', $verifKode));
                        $namaOrganisasiLengkap = \App\Support\Organisasi::fullName($item->organisasi);
                    @endphp

                    <!-- 3D Card Flippable Wrapper (CR80 ID Badge Proportion) -->
                    <div class="card-3d-wrapper relative w-full h-[450px]"
                         id="member-card-{{ $item->id }}"
                         data-shared-card="member-card-{{ $item->id }}"
                         data-member-id="{{ $item->id }}"
                         style="view-transition-name: member-card-{{ $item->id }};">
                        
                        <div id="member-flip-{{ $item->id }}" 
                             class="card-3d-inner w-full h-full relative">
                            
                            <!-- ================= SISI DEPAN (FRONT FACE) ================= -->
                            <div class="card-face card-front rounded-3xl bg-white border-2 border-brand-forest shadow-solid-sm hover:shadow-solid-md transition-all flex flex-col justify-between p-5 overflow-hidden group member-card-item cursor-pointer">
                                
                                <!-- Card Header: Logo NU, Identity & Status -->
                                <div class="flex items-center justify-between gap-2.5 pb-3 border-b-2 border-brand-sand">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-9 h-9 rounded-xl bg-brand-sand border border-brand-forest/30 p-1 flex items-center justify-center shrink-0 shadow-2xs">
                                            <img src="{{ asset('images/logo-nu-card.webp') }}" alt="Logo NU" class="w-full h-full object-contain">
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[9px] font-black uppercase tracking-wider text-brand-forest/70 block leading-none">NAHDLATUL ULAMA</span>
                                            <h5 class="text-xs font-black text-brand-deep tracking-tight truncate leading-tight mt-0.5 m-0">
                                                {{ \App\Support\Organisasi::label($item->organisasi) }} WANGON
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
                                    <div class="w-20 h-20 mx-auto rounded-2xl p-1 bg-brand-sand border-2 border-brand-forest overflow-hidden relative shadow-solid-sm group-hover:scale-105 transition-transform"
                                         data-shared-image="member-image-{{ $item->id }}"
                                         style="view-transition-name: member-image-{{ $item->id }};">
                                        @if ($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" class="w-full h-full rounded-xl object-cover" alt="{{ $item->nama }}">
                                        @else
                                            <div class="w-full h-full rounded-xl bg-white flex items-center justify-center text-brand-forest/60 text-2xl">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="space-y-0.5">
                                        <h4 class="font-black text-base text-brand-deep truncate px-1 group-hover:text-brand-matcha transition-colors" title="{{ $item->nama }}">
                                            {{ $item->nama }}
                                        </h4>
                                        <p class="text-xs font-bold text-brand-darktext/70 truncate m-0">
                                            {{ $item->jabatan ?? 'Anggota Aktif' }}
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-center gap-2 pt-0.5">
                                        <span class="inline-block bg-brand-lightmatcha text-brand-forest text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full border border-brand-forest">
                                            {{ \App\Support\Organisasi::badgeLabel($item->organisasi) }}
                                        </span>
                                        <span class="inline-block font-mono font-black text-[10px] tracking-wider text-brand-forest bg-brand-sand px-2 py-0.5 rounded-lg border border-brand-forest/30">
                                            {{ $formattedId }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-center gap-1.5 text-[11px] font-bold text-brand-forest/80 pt-0.5">
                                        <i class="bi bi-telephone text-[10px]"></i>
                                        <span class="font-mono">{{ $item->no_hp_masked ?? ($item->no_hp ?? '-') }}</span>
                                    </div>
                                </div>

                                <!-- Card Bottom Controls: Flip 3D & Open Profile -->
                                <div class="pt-3 border-t-2 border-brand-sand flex items-center justify-between gap-2">
                                    <button type="button" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brand-sand hover:bg-brand-forest hover:text-white text-brand-forest text-xs font-black uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all btn-solid-press cursor-pointer"
                                            data-flip-target="member-flip-{{ $item->id }}"
                                            title="Balik kartu untuk melihat barcode resmi">
                                        <i class="bi bi-arrow-repeat text-xs"></i>
                                        <span>Balik</span>
                                    </button>

                                    <button type="button" 
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-brand-forest hover:bg-brand-deep text-white text-xs font-black uppercase tracking-wider border-2 border-brand-deep shadow-solid-sm transition-all btn-solid-press cursor-pointer" 
                                            data-open-member="{{ $item->id }}"
                                            data-bs-target="#modalProfil{{ $item->id }}"
                                            aria-label="Lihat profil detail {{ $item->nama }}">
                                        <span>Profil</span>
                                        <i class="bi bi-arrow-up-right text-xs"></i>
                                    </button>
                                </div>

                            </div>

                            <!-- ================= SISI BELAKANG (BACK FACE) ================= -->
                            <div class="card-face card-back rounded-3xl bg-[#F6F4EE] border-2 border-brand-forest shadow-solid-sm flex flex-col justify-between p-5 overflow-hidden member-card-item cursor-pointer">
                                
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

                                    <!-- 1D Barcode Pattern & Verification Code -->
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
                                        <span class="font-mono text-[10px] font-black text-brand-deep tracking-wider block">
                                            {{ $verifKode }}
                                        </span>
                                    </div>

                                    <p class="text-[9px] font-semibold text-brand-darktext/70 leading-tight m-0">
                                        Scan QR untuk validasi keaslian di basis data MWC NU Wangon.
                                    </p>

                                    <div class="pt-1 border-t border-brand-sand flex items-center justify-between text-[9px] font-bold text-brand-forest/80 px-1">
                                        <span class="font-mono">ID: {{ $formattedId }}</span>
                                        <span class="font-mono">HP: {{ $item->no_hp_masked ?? ($item->no_hp ?? '-') }}</span>
                                    </div>
                                </div>

                                <!-- Back Bottom Controls -->
                                <div class="pt-3 border-t-2 border-brand-sand flex items-center justify-between gap-2">
                                    <button type="button" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brand-sand hover:bg-brand-forest hover:text-white text-brand-forest text-xs font-black uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all btn-solid-press cursor-pointer"
                                            data-flip-target="member-flip-{{ $item->id }}"
                                            title="Kembali ke sisi depan">
                                        <i class="bi bi-arrow-repeat text-xs"></i>
                                        <span>Depan</span>
                                    </button>

                                    <button type="button" 
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-brand-forest hover:bg-brand-deep text-white text-xs font-black uppercase tracking-wider border-2 border-brand-deep shadow-solid-sm transition-all btn-solid-press cursor-pointer" 
                                            data-open-member="{{ $item->id }}"
                                            data-bs-target="#modalProfil{{ $item->id }}"
                                            aria-label="Lihat profil detail {{ $item->nama }}">
                                        <span>Rincian</span>
                                        <i class="bi bi-arrow-up-right text-xs"></i>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- PAGINATION -->
            <div class="mt-12 pt-6 border-t-2 border-brand-sand flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-bold text-brand-darktext/70">
                <div>
                    Menampilkan <strong>{{ $anggotaList->firstItem() ?? 0 }} - {{ $anggotaList->lastItem() ?? 0 }}</strong> dari <strong>{{ $anggotaList->total() }}</strong> Anggota
                </div>
                <div>
                    {{ $anggotaList->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- ================= 5. MODAL PROFIL DETAIL (EXPANDED 2-COLUMN DOSSIER DENGAN SHARED MORPH) ================= -->
@if (!$anggotaList->isEmpty())
    @foreach ($anggotaList as $item)
        @php
            $prefix = \App\Support\Organisasi::prefix($item->organisasi);
            $formattedId = $item->nomor_anggota ?? ($prefix . '-' . str_pad($item->id, 3, '0', STR_PAD_LEFT));
            $verifKode = $item->kode_verifikasi ?? ($item->kode_pendaftaran ?? 'NU-' . $item->id);
            $kodeKartu = $item->kode_pendaftaran ?? $item->id;
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=140x140&data=" . urlencode(route('verifikasi.show', $verifKode));

            $cleanHp = preg_replace('/[^0-9]/', '', $item->no_hp ?? '');
            if (str_starts_with($cleanHp, '0')) {
                $cleanHp = '62' . substr($cleanHp, 1);
            }
            $waUrl = $cleanHp ? "https://wa.me/{$cleanHp}" : '#';
            $namaOrganisasiLengkap = \App\Support\Organisasi::fullName($item->organisasi);
        @endphp

        <div id="modalProfil{{ $item->id }}" 
             class="member-morph-modal fixed inset-0 z-[10005] hidden items-center justify-center p-2 sm:p-5 lg:p-8 overflow-y-auto" 
             tabindex="-1" 
             role="dialog"
             aria-modal="true"
             aria-labelledby="memberModalTitle{{ $item->id }}"
             data-member-id="{{ $item->id }}">
            
            <!-- Fullscreen Backdrop Overlay -->
            <div class="member-morph-backdrop fixed inset-0 bg-brand-deep/80 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-auto" 
                 data-close-member="true"
                 aria-hidden="true"></div>

            <!-- Centered Expanded Detail Dialog (Shared Target Container) -->
            <div class="member-detail-dialog bg-[#FCFCFB] rounded-2xl sm:rounded-3xl border-2 border-brand-forest shadow-solid-lg overflow-hidden w-full max-w-4xl lg:max-w-5xl my-auto relative z-10 pointer-events-auto flex flex-col max-h-[94vh]"
                 data-shared-card="member-card-{{ $item->id }}"
                 data-detail-dialog="{{ $item->id }}"
                 style="view-transition-name: member-card-{{ $item->id }};">
                
                <!-- Modal Top Header -->
                <div class="member-detail-header bg-brand-forest p-4 sm:p-5 text-white flex items-center justify-between border-b-2 border-brand-deep shrink-0">
                    <div class="flex items-center gap-2.5 sm:gap-3">
                        <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 p-1 flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/logo-nu-card.webp') }}" alt="Logo NU" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="text-[10px] font-mono font-black text-brand-lightmatcha uppercase tracking-wider block">ID: {{ $formattedId }}</span>
                            <h3 id="memberModalTitle{{ $item->id }}" class="font-black text-sm sm:text-base text-white mt-0.5 mb-0">{{ $namaOrganisasiLengkap }}</h3>
                        </div>
                    </div>

                    <button type="button" 
                            class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white text-white hover:text-brand-forest border border-white/30 flex items-center justify-center text-sm transition-all btn-solid-press cursor-pointer" 
                            data-close-member="true" 
                            data-bs-dismiss="modal"
                            aria-label="Tutup Detail">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Modal Body: 2-Column Experience (Desktop) / Scrollable (Mobile) -->
                <div class="member-detail-body p-4 sm:p-8 overflow-y-auto flex-grow">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        
                        <!-- Left Column: Interactive 3D Card (Flippable Front & Back) -->
                        <div class="lg:col-span-5 flex flex-col items-center gap-4">
                            <span class="text-[11px] font-black uppercase tracking-wider text-brand-forest/70 self-start">
                                Kartu Identitas Digital (e-KARTANU)
                            </span>

                            <div class="card-3d-wrapper relative w-full max-w-[320px] h-[450px]">
                                <div id="detail-flip-{{ $item->id }}" 
                                     class="card-3d-inner w-full h-full relative">
                                    
                                    <!-- Detail Card Front -->
                                    <div class="card-face card-front rounded-3xl bg-white border-2 border-brand-forest shadow-solid-md flex flex-col justify-between p-5 overflow-hidden">
                                        <div class="flex items-center justify-between gap-2.5 pb-3 border-b-2 border-brand-sand">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <div class="w-8 h-8 rounded-xl bg-brand-sand border border-brand-forest/30 p-1 flex items-center justify-center shrink-0 shadow-2xs">
                                                    <img src="{{ asset('images/logo-nu-card.webp') }}" alt="Logo NU" class="w-full h-full object-contain">
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="text-[8px] font-black uppercase tracking-wider text-brand-forest/70 block leading-none">NAHDLATUL ULAMA</span>
                                                    <h5 class="text-[11px] font-black text-brand-deep tracking-tight truncate leading-tight mt-0.5 m-0">
                                                        {{ \App\Support\Organisasi::label($item->organisasi) }} WANGON
                                                    </h5>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-500/40 text-[9px] font-black uppercase">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                AKTIF
                                            </span>
                                        </div>

                                        <div class="text-center space-y-3 my-auto py-2">
                                            <div class="w-24 h-24 mx-auto rounded-2xl p-1 bg-brand-sand border-2 border-brand-forest overflow-hidden relative shadow-solid-sm"
                                                 data-shared-image="member-image-{{ $item->id }}"
                                                 data-detail-image="{{ $item->id }}"
                                                 style="view-transition-name: member-image-{{ $item->id }};">
                                                @if ($item->foto)
                                                    <img src="{{ asset('storage/' . $item->foto) }}" class="w-full h-full rounded-xl object-cover" alt="{{ $item->nama }}">
                                                @else
                                                    <div class="w-full h-full rounded-xl bg-white flex items-center justify-center text-brand-forest/60 text-3xl">
                                                        <i class="bi bi-person-fill"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <div>
                                                <h4 class="font-black text-base text-brand-deep truncate px-1 m-0">
                                                    {{ $item->nama }}
                                                </h4>
                                                <p class="text-xs font-bold text-brand-darktext/70 truncate m-0">
                                                    {{ $item->jabatan ?? 'Anggota Aktif' }}
                                                </p>
                                            </div>

                                            <div class="flex items-center justify-center gap-2">
                                                <span class="inline-block bg-brand-lightmatcha text-brand-forest text-[10px] font-black uppercase px-2 py-0.5 rounded-full border border-brand-forest">
                                                    {{ \App\Support\Organisasi::badgeLabel($item->organisasi) }}
                                                </span>
                                                <span class="font-mono text-[10px] font-black text-brand-forest bg-brand-sand px-2 py-0.5 rounded-lg border border-brand-forest/30">
                                                    {{ $formattedId }}
                                                </span>
                                            </div>

                                            <div class="flex items-center justify-center gap-1.5 text-[11px] font-bold text-brand-forest/80 pt-0.5">
                                                <i class="bi bi-telephone text-[10px]"></i>
                                                <span class="font-mono">{{ $item->no_hp_masked ?? ($item->no_hp ?? '-') }}</span>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-t-2 border-brand-sand text-center">
                                            <button type="button" 
                                                    class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-brand-sand hover:bg-brand-forest hover:text-white text-brand-forest text-xs font-black uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all btn-solid-press cursor-pointer"
                                                    data-flip-target="detail-flip-{{ $item->id }}">
                                                <i class="bi bi-arrow-repeat"></i>
                                                <span>Balik ke Sisi Barcode</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Detail Card Back -->
                                    <div class="card-face card-back rounded-3xl bg-[#F6F4EE] border-2 border-brand-forest shadow-solid-md flex flex-col justify-between p-5 overflow-hidden">
                                        <div class="flex items-center justify-between pb-2.5 border-b-2 border-brand-sand">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ asset('images/logo-nu-card.webp') }}" alt="Logo NU" class="w-6 h-6 object-contain opacity-80">
                                                <div>
                                                    <span class="text-[8px] font-black uppercase text-brand-forest/70 block leading-none">E-KARTANU RESMI</span>
                                                    <h6 class="text-[10px] font-black text-brand-deep m-0 uppercase">MWC NU WANGON</h6>
                                                </div>
                                            </div>
                                            <span class="text-[9px] font-mono font-bold text-brand-forest/60">VALIDASI</span>
                                        </div>

                                        <div class="bg-white rounded-2xl border-2 border-brand-forest p-3.5 shadow-solid-sm text-center my-auto space-y-2.5">
                                            <div class="w-28 h-28 mx-auto bg-white rounded-xl border border-brand-forest/20 p-1 flex items-center justify-center">
                                                <img src="{{ $qrUrl }}" alt="QR Verifikasi" class="w-full h-full object-contain">
                                            </div>

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
                                                <span class="font-mono">ID: {{ $formattedId }}</span>
                                                <span class="font-mono">HP: {{ $item->no_hp_masked ?? ($item->no_hp ?? '-') }}</span>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-t-2 border-brand-sand text-center">
                                            <button type="button" 
                                                    class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-brand-sand hover:bg-brand-forest hover:text-white text-brand-forest text-xs font-black uppercase tracking-wider border-2 border-brand-forest shadow-solid-sm transition-all btn-solid-press cursor-pointer"
                                                    data-flip-target="detail-flip-{{ $item->id }}">
                                                <i class="bi bi-arrow-repeat"></i>
                                                <span>Balik ke Sisi Depan</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Fast Action Links Under Card -->
                            <div class="w-full max-w-[320px] grid grid-cols-2 gap-2 pt-1">
                                <a href="{{ route('anggota.kartu', $kodeKartu) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider bg-brand-forest hover:bg-brand-deep text-white border-2 border-brand-deep shadow-solid-sm btn-solid-press text-decoration-none text-center">
                                    <i class="bi bi-card-heading"></i>
                                    <span>Kartu Web</span>
                                </a>
                                <a href="{{ route('anggota.kartu.pdf', $kodeKartu) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider bg-white hover:bg-brand-cream text-brand-forest border-2 border-brand-forest shadow-solid-sm btn-solid-press text-decoration-none text-center">
                                    <i class="bi bi-download"></i>
                                    <span>Unduh PDF</span>
                                </a>
                            </div>
                        </div>

                        <!-- Right Column: Full Member Profile Dossier -->
                        <div class="lg:col-span-7 space-y-5">
                            
                            <!-- Header Profile Title -->
                            <div class="bg-white rounded-2xl p-5 border-2 border-brand-forest shadow-solid-sm space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="inline-block bg-brand-lightmatcha text-brand-forest text-[11px] font-black uppercase px-3 py-0.5 rounded-full border border-brand-forest">
                                        {{ $item->jabatan ?? 'Anggota Terdaftar' }}
                                    </span>
                                    <span class="text-xs font-black text-brand-forest uppercase">
                                        {{ \App\Support\Organisasi::label($item->organisasi) }}
                                    </span>
                                </div>
                                <h3 class="text-2xl sm:text-3xl font-black text-brand-deep leading-tight m-0">
                                    {{ $item->nama }}
                                </h3>
                                <p class="text-xs font-bold text-brand-darktext/70 m-0">
                                    Kader resmi Jam'iyyah Nahdlatul Ulama Majelis Wakil Cabang Wangon.
                                </p>
                            </div>

                            <!-- Detail Dossier Cards -->
                            <div class="space-y-3 text-xs font-medium">
                                
                                <!-- Status Card -->
                                <div class="bg-brand-cream rounded-2xl p-4 border-2 border-brand-forest flex items-center justify-between shadow-solid-sm">
                                    <div class="space-y-0.5">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest/70 block">Status Keanggotaan</span>
                                        <span class="text-sm font-black text-brand-forest flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Aktif &amp; Terverifikasi
                                        </span>
                                    </div>
                                    <a href="{{ route('verifikasi.show', $verifKode) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-900 border border-emerald-600/40 text-[11px] font-black uppercase tracking-wider text-decoration-none shadow-2xs">
                                        <i class="bi bi-patch-check-fill"></i>
                                        <span>Cek Validitas</span>
                                    </a>
                                </div>

                                <!-- Identity & NIK -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="bg-brand-cream rounded-2xl p-4 border-2 border-brand-forest space-y-1 shadow-solid-sm">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest/70 block">Nomor Anggota (KARTANU)</span>
                                        <span class="font-mono text-sm font-black text-brand-deep block">{{ $formattedId }}</span>
                                    </div>
                                    <div class="bg-brand-cream rounded-2xl p-4 border-2 border-brand-forest space-y-1 shadow-solid-sm">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest/70 block">NIK Terdaftar</span>
                                        <span class="font-mono text-sm font-black text-brand-deep block">{{ $item->nik_masked ?? '-' }}</span>
                                    </div>
                                </div>

                                <!-- WhatsApp Contact -->
                                <div class="bg-brand-cream rounded-2xl p-4 border-2 border-brand-forest flex items-center justify-between shadow-solid-sm">
                                    <div class="space-y-0.5">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest/70 block">Kontak WhatsApp</span>
                                        <span class="text-sm font-black text-brand-deep block">{{ $item->no_hp ?? '-' }}</span>
                                    </div>
                                    @if($item->no_hp)
                                        <a href="{{ $waUrl }}" 
                                           target="_blank" 
                                           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider shadow-solid-sm text-decoration-none transition-colors">
                                            <i class="bi bi-whatsapp text-sm"></i>
                                            <span>Chat WA</span>
                                        </a>
                                    @endif
                                </div>

                                <!-- Address -->
                                <div class="bg-brand-cream rounded-2xl p-4 border-2 border-brand-forest space-y-1 shadow-solid-sm">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-brand-forest/70 block">Alamat Domisili</span>
                                    <span class="text-sm font-black text-brand-deep block capitalize leading-relaxed">{{ $item->alamat ?? '-' }}</span>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

                <!-- Modal Bottom Footer -->
                <div class="member-detail-footer p-4 sm:p-5 bg-brand-sand border-t-2 border-brand-forest flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left shrink-0">
                    <span class="text-xs font-bold text-brand-forest/70">
                        Sistem Informasi Keanggotaan Terpadu MWC NU Wangon
                    </span>

                    <button type="button" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-brand-forest hover:bg-brand-deep text-white text-xs font-black uppercase tracking-wider border-2 border-brand-deep shadow-solid-sm btn-solid-press cursor-pointer" 
                            data-close-member="true"
                            data-bs-dismiss="modal">
                        <span>Tutup Profil</span>
                    </button>
                </div>

            </div>
        </div>
    @endforeach
@endif

{{-- SHARED LAYOUT MORPH & 3D CARD FLIP ENGINE (MOTION-V / ANIMATEVIEW PATTERN) --}}
@include('anggota.partials.morph-profile-engine')

@endsection