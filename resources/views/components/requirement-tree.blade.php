@php
    $key = $parentKey ?? 'root';
@endphp

<style>
    /* put in a global css if you prefer */
    .req-toggle-btn {
        width: 28px;
        height: 28px;
    }

    .req-toggle-btn i {
        transition: transform .2s ease;
    }

    .req-toggle-btn[aria-expanded="true"] i {
        transform: rotate(180deg);
    }
</style>

<div class="accordion accordion-flush" id="acc-{{ $key }}">
    @foreach ($requirements as $i => $req)
        @php
            $itemKey = $key . '-' . $i . '-' . $req->id;
            $hasChildren = $req->childrenRecursive && $req->childrenRecursive->count() > 0;
        @endphp

        <div class="accordion-item mt-2">
            <h4 class="accordion-header" id="h-{{ $itemKey }}">
                <div class="d-flex align-items-center gap-2 position-relative py-1">
                    {{-- tombol dropdown kiri --}}
                    @if ($hasChildren)
                        <button
                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center rounded-circle req-toggle-btn"
                            type="button" data-bs-toggle="collapse" data-bs-target="#c-{{ $itemKey }}"
                            aria-expanded="false" aria-controls="c-{{ $itemKey }}" title="Buka/tutup">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    @else
                        <span class="btn btn-sm btn-outline-secondary disabled rounded-circle req-toggle-btn"
                            style="opacity:.5; cursor:not-allowed;" title="Tidak ada sub-item">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    @endif


                    {{-- badge urutan --}}
                    <span class="badge bg-secondary small">{{ $req->order_index ?? '-' }}</span>
                    
                    {{-- kode (opsional) --}}
                    @if (!empty($req->code))
                        <a href="{{ $req->link ?: 'javascript:void(0)' }}" target="_blank" rel="noopener"
                            class="ms-1 fw-semibold text-decoration-none small">
                            {{ $req->code }}
                        </a>
                    @endif

                    {{-- judul -> klik ke link (BUKAN toggle) --}}
                    <a href="{{ $req->link ?: 'javascript:void(0)' }}" target="_blank" rel="noopener"
                        class="fw-semibold text-decoration-none small">
                        {{ $req->title }}
                    </a>


                    {{-- jumlah anak di sisi kanan --}}
                    @if ($hasChildren)
                        <span class="ms-auto badge bg-primary small">{{ $req->childrenRecursive->count() }}</span>
                    @endif
                </div>
                </h2>

                <div id="c-{{ $itemKey }}" class="accordion-collapse collapse"
                    aria-labelledby="h-{{ $itemKey }}" data-bs-parent="#acc-{{ $key }}">
                    <div class="accordion-body pt-2 pb-3">
                        @if (!empty($req->description))
                            <p class="mb-2 text-muted small">{{ $req->description }}</p>
                        @endif

                        @if ($hasChildren)
                            @include('components.requirement-tree', [
                                'requirements' => $req->childrenRecursive,
                                'parentKey' => $itemKey,
                            ])
                        @else
                            <div class="text-muted small">Tidak ada sub-requirement.</div>
                        @endif
                    </div>
                </div>
        </div>
    @endforeach
</div>
