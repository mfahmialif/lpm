@extends('layouts.home.template')
@section('title', $activity->title . ' - LPM UII Dalwa')
@push('meta')
    <meta property="og:title" content="{{ $activity->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($activity->body), 150) }}">
    <meta property="og:image" content="{{ $activity->url_image }}">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:type" content="article">
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-…" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@push('css')
    <style>
        /* Semua ikon di dalam .blog-share-item jadi hitam */
        .blog-share-item .social-icons i {
            color: #000;
            /* warna hitam */
            font-size: 1.2rem;
            /* atur ukuran jika perlu */
        }

        .blog-share-item .social-icons button {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }
    </style>
@endpush
@section('content')

    <div style="height: 50px"></div>

    <!-- Blog List -->
    <div class="page-blog-details mt-100" style="margin-bottom: 50px">
        <div class="container">
            <drawer-opener class="open-sidebar svg-wrapper text text-20 fw-500 d-lg-none" data-drawer=".drawer-blog-sidebar"
                data-aos="fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                </svg>
                Filter
            </drawer-opener>
            <div class="row">
                <div class="col-12 col-lg-7">
                    <div class="blog-details">
                        <div class="card-blog-list" data-aos="fade-up">
                            <div class="card-blog-list-media radius18">
                                <div class="media">
                                    <img src="{{ $activity->url_image }}" alt="blog image" width="1000" height="707"
                                        loading="lazy" />
                                </div>
                            </div>

                            <div class="card-blog-content">
                                <div class="card-blog-meta">
                                    <div class="card-blog-meta-item text text-18">
                                        <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.00098 0.650391C11.499 0.650391 13.5437 2.69437 13.5439 5.19238C13.5439 7.69056 11.4992 9.73535 9.00098 9.73535C6.50299 9.73517 4.45898 7.69045 4.45898 5.19238C4.45919 2.69448 6.50308 0.650569 9.00098 0.650391Z"
                                                stroke="currentColor" stroke-width="1.3" />
                                            <path
                                                d="M5.2041 11.4092C5.22954 11.4041 5.2933 11.4126 5.34375 11.4502L5.34863 11.4531C6.41552 12.2405 7.68474 12.6464 8.99902 12.6465C10.3135 12.6465 11.5834 12.2407 12.6504 11.4531L12.6553 11.4502C12.6717 11.4383 12.7412 11.4086 12.8506 11.418C14.4691 11.6454 15.9118 12.559 16.8516 13.9482L16.8555 13.9531C17.0155 14.1843 17.152 14.4246 17.2607 14.6719C17.1428 14.8756 17.0147 15.073 16.8711 15.2705L16.7158 15.4775L16.708 15.4883C16.4195 15.8798 16.0836 16.2387 15.7285 16.5938C15.4317 16.8905 15.0922 17.1871 14.7559 17.4395C13.0785 18.6922 11.0607 19.3506 8.97656 19.3506C6.89732 19.3505 4.88498 18.6944 3.20996 17.4473C2.84577 17.1514 2.51261 16.8807 2.22559 16.5938L2.21875 16.5859L2.21094 16.5801L1.95215 16.3242C1.69963 16.0639 1.46736 15.7886 1.24609 15.4883L1.24316 15.4834L0.944336 15.0703C0.86428 14.9535 0.788425 14.8348 0.71875 14.7178C0.835661 14.4569 0.982086 14.185 1.14258 13.9531L1.14355 13.9541L1.15137 13.9424C2.06835 12.5567 3.53571 11.6401 5.16504 11.416L5.18457 11.4131L5.2041 11.4092Z"
                                                stroke="currentColor" stroke-width="1.3" />
                                        </svg>
                                        {{ $activity->author->name }}
                                    </div>
                                    <div class="card-blog-meta-item text text-18" aria-label="Blog Comments">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_9060_14106)">
                                                <path
                                                    d="M16.1251 3H14.5001V4H16.0001V15H2.00013V4H3.50013V3H1.87513C1.75825 3.00195 1.6429 3.02691 1.53566 3.07345C1.42843 3.11999 1.33141 3.1872 1.25016 3.27125C1.1689 3.35529 1.105 3.45451 1.0621 3.56325C1.0192 3.67199 0.998142 3.78812 1.00013 3.905V15.095C0.998142 15.2119 1.0192 15.328 1.0621 15.4367C1.105 15.5455 1.1689 15.6447 1.25016 15.7288C1.33141 15.8128 1.42843 15.88 1.53566 15.9265C1.6429 15.9731 1.75825 15.998 1.87513 16H16.1251C16.242 15.998 16.3574 15.9731 16.4646 15.9265C16.5718 15.88 16.6688 15.8128 16.7501 15.7288C16.8314 15.6447 16.8953 15.5455 16.9382 15.4367C16.9811 15.328 17.0021 15.2119 17.0001 15.095V3.905C17.0021 3.78812 16.9811 3.67199 16.9382 3.56325C16.8953 3.45451 16.8314 3.35529 16.7501 3.27125C16.6688 3.1872 16.5718 3.11999 16.4646 3.07345C16.3574 3.02691 16.242 3.00195 16.1251 3Z"
                                                    fill="currentColor" />
                                                <path d="M4 7H5V8H4V7Z" fill="currentColor" />
                                                <path d="M7 7H8V8H7V7Z" fill="currentColor" />
                                                <path d="M10 7H11V8H10V7Z" fill="currentColor" />
                                                <path d="M13 7H14V8H13V7Z" fill="currentColor" />
                                                <path d="M4 9.5H5V10.5H4V9.5Z" fill="currentColor" />
                                                <path d="M7 9.5H8V10.5H7V9.5Z" fill="currentColor" />
                                                <path d="M10 9.5H11V10.5H10V9.5Z" fill="currentColor" />
                                                <path d="M13 9.5H14V10.5H13V9.5Z" fill="currentColor" />
                                                <path d="M4 12H5V13H4V12Z" fill="currentColor" />
                                                <path d="M7 12H8V13H7V12Z" fill="currentColor" />
                                                <path d="M10 12H11V13H10V12Z" fill="currentColor" />
                                                <path d="M13 12H14V13H13V12Z" fill="currentColor" />
                                                <path
                                                    d="M5 5C5.13261 5 5.25979 4.94732 5.35355 4.85355C5.44732 4.75979 5.5 4.63261 5.5 4.5V1.5C5.5 1.36739 5.44732 1.24021 5.35355 1.14645C5.25979 1.05268 5.13261 1 5 1C4.86739 1 4.74021 1.05268 4.64645 1.14645C4.55268 1.24021 4.5 1.36739 4.5 1.5V4.5C4.5 4.63261 4.55268 4.75979 4.64645 4.85355C4.74021 4.94732 4.86739 5 5 5Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M13 5C13.1326 5 13.2598 4.94732 13.3536 4.85355C13.4473 4.75979 13.5 4.63261 13.5 4.5V1.5C13.5 1.36739 13.4473 1.24021 13.3536 1.14645C13.2598 1.05268 13.1326 1 13 1C12.8674 1 12.7402 1.05268 12.6464 1.14645C12.5527 1.24021 12.5 1.36739 12.5 1.5V4.5C12.5 4.63261 12.5527 4.75979 12.6464 4.85355C12.7402 4.94732 12.8674 5 13 5Z"
                                                    fill="currentColor" />
                                                <path d="M6.5 3H11.5V4H6.5V3Z" fill="currentColor" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_9060_14106">
                                                    <rect width="18" height="18" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        {{ $activity->published_at_formatted }}
                                    </div>

                                </div>

                                <h2 class="card-blog-heading heading text-50">
                                    {{ $activity->title }}
                                </h2>

                                <div class="blog-description">
                                {{-- {!! $activity->body !!} --}}
                                {!! Purifier::clean($activity->body) !!}

                                </div>

                                <div class="blog-description">
                                    @php
                                        // Mapping tipe dokumen → label & ikon
                                        $docTypes = [
                                            'undangan' => ['label' => 'Undangan', 'icon' => 'fa-envelope-open-text'],
                                            'absensi' => ['label' => 'Absensi', 'icon' => 'fa-user-check'],
                                            'notulen' => ['label' => 'Notulen', 'icon' => 'fa-file-lines'],
                                            'gambar' => ['label' => 'Gambar', 'icon' => 'fa-image'],
                                        ];

                                        // Kelompokkan & hitung per tipe
                                        $docsByType = $activity->document->groupBy('type');
                                        $firstType = array_key_first($docTypes);

                                        // Helper sederhana
                                        $humanSize = function ($bytes = null) {
                                            if (!$bytes || !is_numeric($bytes)) {
                                                return '—';
                                            }
                                            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                                            $i = 0;
                                            while ($bytes >= 1024 && $i < count($units) - 1) {
                                                $bytes /= 1024;
                                                $i++;
                                            }
                                            return number_format($bytes, $i ? 1 : 0) . ' ' . $units[$i];
                                        };
                                        $fileExt = function ($url) {
                                            $ext = strtolower(
                                                pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION),
                                            );
                                            return $ext ?: 'file';
                                        };
                                        $isImage = function ($url, $mime = null) {
                                            if ($mime && str_starts_with(strtolower($mime), 'image/')) {
                                                return true;
                                            }
                                            $ext = strtolower(
                                                pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION),
                                            );
                                            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
                                        };
                                    @endphp

                                    <style>
                                        /* Sedikit polesan */
                                        .docs-section .nav-pills .nav-link {
                                            border-radius: 999px;
                                            padding: .5rem 1rem;
                                        }

                                        .doc-card {
                                            transition: transform .15s ease, box-shadow .15s ease;
                                            border: 1px solid #e9ecef;
                                            height: 100%;
                                        }

                                        .doc-card:hover {
                                            transform: translateY(-2px);
                                            box-shadow: 0 10px 24px rgba(0, 0, 0, .06);
                                        }

                                        .doc-ext-badge {
                                            font-size: .75rem;
                                            border: 1px solid #dee2e6;
                                            padding: .125rem .5rem;
                                            border-radius: .5rem;
                                            text-transform: uppercase;
                                            letter-spacing: .02em;
                                            background: #f8f9fa;
                                        }

                                        .empty-state {
                                            border: 1px dashed #cfd4da;
                                            border-radius: 1rem;
                                            padding: 2rem;
                                            background: #fcfcfd;
                                        }

                                        .doc-actions .btn {
                                            --bs-btn-padding-y: .35rem;
                                            --bs-btn-padding-x: .75rem;
                                        }
                                    </style>

                                    <section class="docs-section mt-5">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <i class="fa-solid fa-folder-open"></i>
                                            <h3 class="h4 m-0">Dokumen Kegiatan</h3>
                                        </div>

                                        {{-- Tabs kategori --}}
                                        <ul class="nav nav-pills gap-2 mb-4 flex-wrap" id="docTypeTabs" role="tablist">
                                            @foreach ($docTypes as $key => $meta)
                                                @php $count = $docsByType->has($key) ? $docsByType[$key]->count() : 0; @endphp
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if ($loop->first) active @endif"
                                                        id="tab-{{ $key }}" data-bs-toggle="pill"
                                                        data-bs-target="#pane-{{ $key }}" type="button"
                                                        role="tab" aria-controls="pane-{{ $key }}"
                                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                        <i class="fa-solid {{ $meta['icon'] }} me-2"></i>
                                                        {{ $meta['label'] }}
                                                        <span
                                                            class="badge bg-dark-subtle text-dark ms-2">{{ $count }}</span>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>

                                        {{-- Isi tab --}}
                                        <div class="tab-content" id="docTypeTabContent">
                                            @foreach ($docTypes as $key => $meta)
                                                @php $docs = $docsByType->get($key, collect()); @endphp
                                                <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                    id="pane-{{ $key }}" role="tabpanel"
                                                    aria-labelledby="tab-{{ $key }}">
                                                    @if ($docs->isEmpty())
                                                        <div class="empty-state text-center">
                                                            <div class="mb-2"><i
                                                                    class="fa-regular fa-folder-open fa-2x text-muted"></i>
                                                            </div>
                                                            <div class="fw-semibold">Belum ada
                                                                {{ strtolower($meta['label']) }} untuk aktivitas ini.</div>
                                                            <div class="text-muted small">Dokumen yang diunggah akan tampil
                                                                di sini.</div>
                                                        </div>
                                                    @else
                                                        <div class="row g-3">
                                                            @foreach ($docs as $doc)
                                                                @php
                                                                    // Asumsikan kolom yang ada: $doc->name, $doc->url, $doc->size (bytes, optional), $doc->mime (optional), $doc->created_at
                                                                    $ext = $fileExt($doc->url ?? '');
                                                                    $img = $isImage(
                                                                        $doc->url ?? '',
                                                                        $doc->mime ?? null,
                                                                    );
                                                                @endphp
                                                                <div class="col-12 col-sm-6 col-lg-4">
                                                                    <div class="card doc-card">
                                                                        @if ($img)
                                                                            <a href="#" class="ratio ratio-16x9"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#previewModal-{{ $doc->id }}">
                                                                                <img src="{{ $doc->url }}"
                                                                                    alt="{{ $doc->name }}"
                                                                                    class="img-fluid rounded-top"
                                                                                    style="object-fit: cover;">
                                                                            </a>
                                                                        @else
                                                                            <div
                                                                                class="ratio ratio-16x9 d-flex align-items-center justify-content-center bg-light rounded-top">
                                                                                <i
                                                                                    class="fa-regular fa-file fa-2x text-muted"></i>
                                                                            </div>
                                                                        @endif

                                                                        <div class="card-body">
                                                                            <div
                                                                                class="d-flex align-items-start justify-content-between gap-2">
                                                                                <div class="me-2">
                                                                                    <div class="fw-semibold text-truncate"
                                                                                        title="{{ $doc->name }}">
                                                                                        {{ $doc->name }}</div>
                                                                                    <div class="text-muted small">
                                                                                        <span
                                                                                            class="doc-ext-badge me-2">{{ $ext }}</span>
                                                                                        {{ $humanSize($doc->size ?? null) }}
                                                                                        @if (!empty($doc->created_at))
                                                                                            •
                                                                                            {{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d M Y') }}
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                <div class="text-nowrap">
                                                                                    @switch($key)
                                                                                        @case('undangan')
                                                                                            @php $typeIcon = 'fa-envelope-open-text'; @endphp
                                                                                        @break

                                                                                        @case('absensi')
                                                                                            @php $typeIcon = 'fa-user-check'; @endphp
                                                                                        @break

                                                                                        @case('notulen')
                                                                                            @php $typeIcon = 'fa-file-lines'; @endphp
                                                                                        @break

                                                                                        @default
                                                                                            @php $typeIcon = 'fa-image'; @endphp
                                                                                    @endswitch
                                                                                    <span
                                                                                        class="badge rounded-pill text-bg-light"
                                                                                        title="{{ $meta['label'] }}">
                                                                                        <i
                                                                                            class="fa-solid {{ $typeIcon }}"></i>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="card-footer bg-white border-0 pt-0">
                                                                            <div class="d-flex gap-2 doc-actions">
                                                                                <a href="{{ $doc->url }}"
                                                                                    target="_blank" rel="noopener"
                                                                                    class="btn btn-outline-primary btn-sm">
                                                                                    <i
                                                                                        class="fa-solid fa-up-right-from-square me-1"></i>
                                                                                    Lihat
                                                                                </a>
                                                                                <a href="{{ $doc->url }}" download
                                                                                    class="btn btn-primary btn-sm">
                                                                                    <i
                                                                                        class="fa-solid fa-download me-1"></i>
                                                                                    Unduh
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </section>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="blog-share" data-aos="fade-up">
                        <div class="blog-share-item">
                            <h2 class="label heading text-16 fw-500">Unit:</h2>
                            <ul class="sidebar-tags list-unstyled">
                                @foreach ($activity->unit as $item)
                                    <li>
                                        <a class="subheading subheading-bg text-18"
                                            href="{{ route('activity.index', ['unit' => $item->name]) }}"
                                            aria-label="tag">
                                            {{ ucfirst($item->name) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @php
                            $shareUrl = $activity->url ?? request()->fullUrl();
                            $encodedUrl = urlencode($shareUrl);
                            $shareTitle = urlencode($activity->title ?? ($title ?? config('app.name')));
                        @endphp

                        <div class="blog-share-item">
                            <h2 class="label heading text-16 fw-500">Share:</h2>

                            <ul class="social-icons list-unstyled d-flex gap-3">
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
                                        target="_blank" rel="noopener">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $encodedUrl }}"
                                        target="_blank" rel="noopener">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $shareTitle }}"
                                        target="_blank" rel="noopener">
                                        <i class="fab fa-x-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $encodedUrl }}"
                                        target="_blank" rel="noopener">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/uii_dalwa" target="_blank" rel="noopener">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                                {{-- Tombol copy link --}}
                                <li>
                                    <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $shareUrl }}'); this.innerHTML='<i class=&quot;fas fa-clipboard-check&quot;></i>'; setTimeout(()=>this.innerHTML='<i class=&quot;fas fa-link&quot;></i>',1500)">
                                        <i class="fas fa-link"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
                @include('activity.side-right')
            </div>
        </div>
    </div>
@endsection
