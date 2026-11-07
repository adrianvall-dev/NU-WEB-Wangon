@php
    $i = $index ?? 0;
    $total = $totalNews ?? 1;
    $m = $i % 6;
    $pubDate = \Carbon\Carbon::parse($newsItem->published_at ?? $newsItem->created_at ?? now());
    $isFallback = $isFallback ?? false;
    $url = $isFallback ? route('berita.index') : route('berita.show', $newsItem->slug);
    $imgSrc = !empty($newsItem->image) 
        ? ($isFallback ? asset($newsItem->image) : asset('storage/' . $newsItem->image))
        : asset('images/landing/gallery-' . (($i % 3) + 1) . '.jpg');
    $badgeText = \App\Support\Organisasi::badgeLabel($newsItem->kategori ?? 'BERITA');
    $excerpt = strip_tags($newsItem->content ?? $newsItem->excerpt ?? '');

    // DESKTOP IS STRICTLY LOCKED: 12-Column Grid (7 + 5 | 12 | 4 + 4 + 4)
    // MOBILE IS 2-COLUMN ASYMMETRIC:
    // Item 1 (i=0): full (col-span-2)
    // Item 2 (i=1): half (col-span-1)
    // Item 3 (i=2): half (col-span-1)
    // Item 4 (i=3): full (col-span-2)
    // Item 5 (i=4): half (col-span-1)
    // Item 6 (i=5): half (col-span-1)
    if ($total === 1) {
        $variant = 'featured';
        $gridSpan = 'col-span-2 lg:col-span-12';
        $isMobileCompact = false;
    } elseif ($total === 2) {
        if ($i === 0) {
            $variant = 'featured';
            $gridSpan = 'col-span-2 lg:col-span-6';
            $isMobileCompact = false;
        } else {
            $variant = 'editorial';
            $gridSpan = 'col-span-2 lg:col-span-6';
            $isMobileCompact = false;
        }
    } elseif ($total === 3) {
        if ($i === 0) {
            $variant = 'featured';
            $gridSpan = 'col-span-2 lg:col-span-7';
            $isMobileCompact = false;
        } elseif ($i === 1) {
            $variant = 'editorial';
            $gridSpan = 'col-span-1 lg:col-span-5';
            $isMobileCompact = true;
        } else {
            $variant = 'horizontal';
            $gridSpan = 'col-span-1 lg:col-span-12';
            $isMobileCompact = true;
        }
    } elseif ($total === 4) {
        if ($i === 0) {
            $variant = 'featured';
            $gridSpan = 'col-span-2 lg:col-span-7';
            $isMobileCompact = false;
        } elseif ($i === 1) {
            $variant = 'editorial';
            $gridSpan = 'col-span-1 lg:col-span-5';
            $isMobileCompact = true;
        } elseif ($i === 2) {
            $variant = 'editorial';
            $gridSpan = 'col-span-1 lg:col-span-6';
            $isMobileCompact = true;
        } else {
            $variant = 'featured';
            $gridSpan = 'col-span-2 lg:col-span-6';
            $isMobileCompact = false;
        }
    } elseif ($total === 5) {
        if ($i === 0) {
            $variant = 'featured';
            $gridSpan = 'col-span-2 lg:col-span-7';
            $isMobileCompact = false;
        } elseif ($i === 1) {
            $variant = 'editorial';
            $gridSpan = 'col-span-1 lg:col-span-5';
            $isMobileCompact = true;
        } elseif ($i === 2) {
            $variant = 'horizontal';
            $gridSpan = 'col-span-1 lg:col-span-12';
            $isMobileCompact = true;
        } elseif ($i === 3) {
            $variant = 'editorial';
            $gridSpan = 'col-span-1 lg:col-span-6';
            $isMobileCompact = true;
        } else {
            $variant = 'featured';
            $gridSpan = 'col-span-2 lg:col-span-6';
            $isMobileCompact = false;
        }
    } else {
        // total >= 6: 6-item cyclic rhythm
        if ($m === 0) {
            $variant = 'featured';
            $gridSpan = 'col-span-2 lg:col-span-7';
            $isMobileCompact = false;
        } elseif ($m === 1) {
            $variant = 'editorial';
            $gridSpan = 'col-span-1 lg:col-span-5';
            $isMobileCompact = true;
        } elseif ($m === 2) {
            $variant = 'horizontal';
            $gridSpan = 'col-span-1 lg:col-span-12';
            $isMobileCompact = true;
        } elseif ($m === 3) {
            $variant = 'featured';
            $gridSpan = 'col-span-2 lg:col-span-4';
            $isMobileCompact = false;
        } elseif ($m === 4) {
            $variant = 'editorial';
            $gridSpan = 'col-span-1 lg:col-span-4';
            $isMobileCompact = true;
        } else {
            $variant = 'editorial';
            $gridSpan = 'col-span-1 lg:col-span-4';
            $isMobileCompact = true;
        }
    }
@endphp

@if($variant === 'horizontal')
    {{-- ========================================================================================= --}}
    {{-- ITEM 3: COMPACT ON MOBILE (50% WIDTH), HORIZONTAL MAGAZINE BANNER ON DESKTOP (100% WIDTH) --}}
    {{-- ========================================================================================= --}}
    <article class="{{ $gridSpan }} bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest shadow-solid-md hover:shadow-solid-lg transition-all flex flex-col lg:flex-row justify-between group floating-frame-card floating-reveal h-auto w-full text-left overflow-hidden isolate" 
             data-parallax="card-large"
             data-bento-variant="horizontal">
        
        <!-- 1. Dedicated Image Wrapper (4:3 on mobile, left 5/12 on desktop) -->
        <div class="relative w-full lg:w-5/12 aspect-[4/3] lg:aspect-auto min-h-[140px] sm:min-h-[180px] lg:min-h-full overflow-hidden rounded-t-[14px] lg:rounded-t-none lg:rounded-l-[22px] bg-stone-100 border-b-2 lg:border-b-0 lg:border-r-2 border-brand-forest shrink-0">
            <img src="{{ $imgSrc }}" 
                 alt="{{ $newsItem->title }}" 
                 class="w-full h-full object-cover block rounded-t-[14px] lg:rounded-t-none lg:rounded-l-[22px] group-hover:scale-103 transition-transform duration-700 ease-out"
                 loading="lazy">
            <div class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3 lg:top-4 lg:left-4 z-10 hidden sm:block">
                <span class="inline-flex items-center px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full bg-brand-forest text-white border border-brand-deep text-[10px] sm:text-xs font-black uppercase tracking-wider shadow-solid-xs">
                    Kilas Liputan
                </span>
            </div>
        </div>

        <!-- 2. Content Details (Compact on mobile, Spacious on desktop) -->
        <div class="p-3.5 sm:p-4.5 lg:px-8 lg:py-7 flex flex-col justify-between flex-1 text-left w-full lg:w-7/12">
            <div>
                <!-- Metadata Row -->
                <div class="flex items-start sm:items-center justify-between gap-1.5 sm:gap-2 w-full text-left flex-wrap lg:flex-nowrap">
                    <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 lg:px-4 lg:py-1.5 rounded-md sm:rounded-lg bg-brand-lightmatcha text-brand-forest border border-brand-forest lg:border-2 text-[10px] sm:text-xs lg:text-[13px] font-black uppercase tracking-wider shadow-solid-xs lg:shadow-solid-sm shrink-0">
                        {{ $badgeText }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] sm:text-xs lg:text-sm font-bold text-brand-darktext/75 shrink-0">
                        <i class="bi bi-calendar3 text-brand-forest text-[10px] sm:text-xs"></i>
                        {{ $pubDate->translatedFormat('d M Y') }}
                    </span>
                </div>

                <!-- Title (15-18px on mobile compact, 24px on desktop) -->
                <h3 class="text-left text-[14px] sm:text-[15px] lg:text-2xl font-black text-brand-deep leading-snug lg:leading-[1.24] group-hover:text-brand-matcha transition-colors line-clamp-3 mt-2 sm:mt-2.5 lg:mt-4 mb-0">
                    <a href="{{ $url }}" class="text-inherit text-decoration-none block text-left">
                        {{ $newsItem->title }}
                    </a>
                </h3>

                <!-- Excerpt (Hidden on mobile compact, Visible on desktop) -->
                <p class="hidden lg:block text-left text-sm sm:text-[15px] font-medium text-brand-darktext/80 leading-[1.6] line-clamp-2 sm:line-clamp-3 mt-2.5 mb-0">
                    {{ Str::limit($excerpt, 160) }}
                </p>
            </div>

            <!-- Footer Action (Compact CTA on mobile, Spacious on desktop) -->
            <div class="pt-2 sm:pt-2.5 lg:pt-4 mt-2 sm:mt-2.5 lg:mt-4 border-t-2 border-brand-sand bg-transparent flex items-center justify-between w-full">
                <div class="text-left leading-tight shrink-0">
                    <span class="block text-[10px] sm:text-xs font-black uppercase tracking-wider text-brand-forest/75 leading-tight">WARTA NU</span>
                    <span class="hidden lg:block text-xs font-black uppercase tracking-wider text-brand-forest/90 leading-tight">WANGON</span>
                </div>
                <a href="{{ $url }}" 
                   class="inline-flex items-center justify-center gap-1 sm:gap-1.5 h-8 sm:h-9 lg:h-11 px-2.5 sm:px-3.5 lg:px-7 rounded-lg sm:rounded-xl bg-brand-forest hover:bg-brand-deep text-white font-black text-[10px] sm:text-xs lg:text-sm uppercase tracking-wider border border-brand-deep lg:border-2 shadow-solid-xs lg:shadow-solid-sm transition-all btn-solid-press text-decoration-none shrink-0 ml-auto">
                    <span>BACA</span>
                    <span class="text-xs lg:text-base leading-none">&rarr;</span>
                </a>
            </div>
        </div>
    </article>

@elseif(!$isMobileCompact)
    {{-- ========================================================================================= --}}
    {{-- FEATURED CARD (MOBILE: FULL-WIDTH ANCHOR, DESKTOP: PROMINENT HERO / TRIO)                 --}}
    {{-- ========================================================================================= --}}
    <article class="{{ $gridSpan }} bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest shadow-solid-md hover:shadow-solid-lg transition-all flex flex-col justify-between group floating-frame-card floating-reveal h-auto w-full text-left overflow-hidden isolate" 
             data-parallax="card-large"
             data-bento-variant="{{ $variant }}">
        
        <div class="flex flex-col text-left w-full">
            <!-- 1. Large Image (16:9 mobile, 16:10 desktop, rounded top radius) -->
            <div class="relative w-full aspect-[16/9] sm:aspect-[16/10] overflow-hidden rounded-t-[14px] sm:rounded-t-[22px] bg-stone-100 border-b-2 border-brand-forest shrink-0">
                <img src="{{ $imgSrc }}" 
                     alt="{{ $newsItem->title }}" 
                     class="w-full h-full object-cover block rounded-t-[14px] sm:rounded-t-[22px] group-hover:scale-103 transition-transform duration-700 ease-out"
                     loading="lazy">
                
                @if($variant === 'featured')
                    <div class="absolute top-3 left-3 sm:top-4 sm:left-4 z-10">
                        <span class="inline-flex items-center px-3 py-1 sm:px-3.5 sm:py-1 rounded-full bg-brand-forest text-white border border-brand-deep text-[11px] sm:text-xs font-black uppercase tracking-wider shadow-solid-xs">
                            Berita Utama
                        </span>
                    </div>
                @endif
            </div>

            <!-- 2. Content Details (Full 20-24px padding on mobile) -->
            <div class="px-5 sm:px-6 pt-5 sm:pt-6 pb-6 sm:pb-7 flex flex-col flex-1 text-left w-full">
                <!-- Metadata Row -->
                <div class="flex items-center justify-between gap-3 w-full text-left flex-wrap">
                    <span class="inline-flex items-center px-3.5 py-1.5 sm:px-4 sm:py-1.5 rounded-lg bg-brand-lightmatcha text-brand-forest border-2 border-brand-forest text-xs sm:text-[13px] font-black uppercase tracking-wider shadow-solid-sm shrink-0">
                        {{ $badgeText }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[13px] sm:text-sm font-bold text-brand-darktext/70 shrink-0">
                        <i class="bi bi-calendar3 text-brand-forest text-xs"></i>
                        {{ $pubDate->translatedFormat('d M Y') }}
                    </span>
                </div>

                <!-- Prominent Title -->
                <h3 class="text-left text-[20px] sm:text-[22px] {{ $variant === 'featured' ? 'lg:text-2xl' : 'lg:text-[21px]' }} font-black text-brand-deep leading-[1.22] sm:leading-snug group-hover:text-brand-matcha transition-colors line-clamp-3 mt-3.5 sm:mt-4 mb-0">
                    <a href="{{ $url }}" class="text-inherit text-decoration-none block text-left">
                        {{ $newsItem->title }}
                    </a>
                </h3>

                <!-- Excerpt (Visible on full-width mobile card) -->
                <p class="text-left text-sm sm:text-[15px] font-medium text-brand-darktext/80 leading-[1.55] sm:leading-[1.6] line-clamp-2 sm:line-clamp-3 mt-2 sm:mt-2.5 mb-0">
                    {{ Str::limit($excerpt, $variant === 'featured' ? 150 : 130) }}
                </p>
            </div>
        </div>

        <!-- 3. Footer Action: Full Width, 44px Touch Target -->
        <div class="px-5 py-3.5 sm:px-6 sm:py-4 border-t-2 border-brand-sand bg-brand-cream/50 flex items-center justify-between w-full mt-auto">
            <div class="text-left leading-tight shrink-0">
                <span class="block text-[11px] sm:text-xs font-black uppercase tracking-wider text-brand-forest/75 leading-tight">WARTA NU</span>
                <span class="block text-[11px] sm:text-xs font-black uppercase tracking-wider text-brand-forest/90 leading-tight">WANGON</span>
            </div>
            <a href="{{ $url }}" 
               class="inline-flex items-center justify-center gap-2 h-11 px-6 sm:px-7 rounded-xl bg-brand-forest hover:bg-brand-deep text-white font-black text-xs sm:text-sm uppercase tracking-wider border-2 border-brand-deep shadow-solid-sm transition-all btn-solid-press text-decoration-none shrink-0 ml-auto">
                <span>BACA</span>
                <span class="text-base leading-none">&rarr;</span>
            </a>
        </div>
    </article>

@else
    {{-- ========================================================================================= --}}
    {{-- COMPACT CARD (MOBILE: 50% WIDTH 2-COLUMN PAIR, DESKTOP: LOCKED EDITORIAL/TRIO)            --}}
    {{-- ========================================================================================= --}}
    <article class="{{ $gridSpan }} bg-white rounded-2xl sm:rounded-3xl border-2 border-brand-forest shadow-solid-md hover:shadow-solid-lg transition-all flex flex-col justify-between group floating-frame-card floating-reveal h-auto w-full text-left overflow-hidden isolate" 
             data-parallax="card"
             data-bento-variant="{{ $variant }}">
        
        <div class="flex flex-col text-left w-full">
            <!-- 1. Image: 4:3 on mobile compact, 16:10 on desktop -->
            <div class="relative w-full aspect-[4/3] lg:aspect-[16/10] overflow-hidden rounded-t-[14px] sm:rounded-t-[22px] bg-stone-100 border-b-2 border-brand-forest shrink-0">
                <img src="{{ $imgSrc }}" 
                     alt="{{ $newsItem->title }}" 
                     class="w-full h-full object-cover block rounded-t-[14px] sm:rounded-t-[22px] group-hover:scale-103 transition-transform duration-700 ease-out"
                     loading="lazy">
            </div>

            <!-- 2. Content Details (Compact 14-16px padding on mobile, 24px on desktop) -->
            <div class="p-3.5 sm:p-4.5 lg:px-6 lg:pt-6 lg:pb-7 flex flex-col flex-1 text-left w-full">
                <!-- Metadata Row -->
                <div class="flex items-start sm:items-center justify-between gap-1.5 sm:gap-2 w-full text-left flex-wrap lg:flex-nowrap">
                    <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 lg:px-4 lg:py-1.5 rounded-md sm:rounded-lg bg-brand-lightmatcha text-brand-forest border border-brand-forest lg:border-2 text-[10px] sm:text-xs lg:text-[13px] font-black uppercase tracking-wider shadow-solid-xs lg:shadow-solid-sm shrink-0">
                        {{ $badgeText }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] sm:text-xs lg:text-sm font-bold text-brand-darktext/75 shrink-0">
                        <i class="bi bi-calendar3 text-brand-forest text-[10px] sm:text-xs"></i>
                        {{ $pubDate->translatedFormat('d M Y') }}
                    </span>
                </div>

                <!-- Title (14-15px on mobile compact, 21px on desktop) -->
                <h3 class="text-left text-[14px] sm:text-[15px] lg:text-[21px] font-black text-brand-deep leading-snug lg:leading-tight group-hover:text-brand-matcha transition-colors line-clamp-3 mt-2 sm:mt-2.5 lg:mt-3.5 mb-0">
                    <a href="{{ $url }}" class="text-inherit text-decoration-none block text-left">
                        {{ $newsItem->title }}
                    </a>
                </h3>

                <!-- Excerpt (Hidden on mobile compact, Visible on desktop) -->
                <p class="hidden lg:block text-left text-sm sm:text-[15px] font-medium text-brand-darktext/80 leading-[1.55] sm:leading-[1.6] line-clamp-2 sm:line-clamp-3 mt-2 sm:mt-2.5 mb-0">
                    {{ Str::limit($excerpt, 130) }}
                </p>
            </div>
        </div>

        <!-- 3. Footer Action (Compact on mobile, Spacious on desktop) -->
        <div class="px-3 py-2 sm:px-4 sm:py-2.5 lg:px-6 lg:py-3.5 border-t-2 border-brand-sand bg-brand-cream/50 flex items-center justify-between w-full mt-auto">
            <div class="text-left leading-tight shrink-0">
                <span class="block text-[10px] sm:text-xs font-black uppercase tracking-wider text-brand-forest/75 leading-tight">WARTA NU</span>
                <span class="hidden lg:block text-[11px] sm:text-xs font-black uppercase tracking-wider text-brand-forest/90 leading-tight">WANGON</span>
            </div>
            <a href="{{ $url }}" 
               class="inline-flex items-center justify-center gap-1 sm:gap-1.5 h-8 sm:h-9 lg:h-11 px-2.5 sm:px-3.5 lg:px-6 rounded-lg sm:rounded-xl bg-brand-forest hover:bg-brand-deep text-white font-black text-[10px] sm:text-xs lg:text-sm uppercase tracking-wider border border-brand-deep lg:border-2 shadow-solid-xs lg:shadow-solid-sm transition-all btn-solid-press text-decoration-none shrink-0 ml-auto">
                <span>BACA</span>
                <span class="text-xs lg:text-base leading-none">&rarr;</span>
            </a>
        </div>
    </article>
@endif
