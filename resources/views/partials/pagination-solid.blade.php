@if ($paginator->hasPages())
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 w-100 py-1">
        <!-- Info Ringkasan Jumlah Data -->
        <div class="text-muted fw-bold" style="font-size: 0.8rem; color: var(--brand-forest);">
            Menampilkan 
            <span class="text-dark fw-extrabold">{{ $paginator->firstItem() }}</span>&ndash;<span class="text-dark fw-extrabold">{{ $paginator->lastItem() }}</span> 
            dari <span class="text-dark fw-extrabold">{{ $paginator->total() }}</span> {{ $paginationLabel ?? 'data' }}
        </div>

        <!-- Tombol Navigasi Halaman -->
        <nav role="navigation" aria-label="Pagination Navigation" class="d-flex align-items-center justify-content-center justify-content-md-end flex-wrap gap-1">
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <span class="btn-solid-light btn-solid-sm opacity-50 pe-none" aria-disabled="true" style="cursor: not-allowed;">
                    <i class="bi bi-arrow-left me-1"></i> Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn-solid-light btn-solid-sm btn-solid-press" aria-label="Previous">
                    <i class="bi bi-arrow-left me-1"></i> Previous
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                {{-- Separator "..." --}}
                @if (is_string($element))
                    <span class="btn-solid-light btn-solid-sm pe-none px-2" style="background: transparent; border-style: dashed;">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="btn-solid-primary btn-solid-sm fw-bold px-3" aria-current="page" style="min-width: 36px; text-align: center;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="btn-solid-light btn-solid-sm btn-solid-press px-3" style="min-width: 36px; text-align: center;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn-solid-light btn-solid-sm btn-solid-press" aria-label="Next">
                    Next <i class="bi bi-arrow-right ms-1"></i>
                </a>
            @else
                <span class="btn-solid-light btn-solid-sm opacity-50 pe-none" aria-disabled="true" style="cursor: not-allowed;">
                    Next <i class="bi bi-arrow-right ms-1"></i>
                </span>
            @endif
        </nav>
    </div>
@else
    {{-- Halaman tunggal: Tampilkan info jumlah data yang rapi --}}
    @if ($paginator->total() > 0)
        <div class="d-flex justify-content-between align-items-center w-100 py-1">
            <span class="text-muted fw-bold" style="font-size: 0.8rem; color: var(--brand-forest);">
                Menampilkan <span class="text-dark fw-extrabold">1</span>&ndash;<span class="text-dark fw-extrabold">{{ $paginator->total() }}</span> dari <span class="text-dark fw-extrabold">{{ $paginator->total() }}</span> {{ $paginationLabel ?? 'data' }}
            </span>
        </div>
    @endif
@endif
