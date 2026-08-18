@extends('layouts.home.template')
@section('title', 'Accreditation - LPM UII Dalwa')
@section('content')

    <style>
        .custom-detail-section {
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
            padding-bottom: 80px;
        }

        .section-header-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
            margin-bottom: 28px;
        }

        .search-box-wrapper {
            position: relative;
        }

        .search-box-wrapper .search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .search-input-custom {
            padding-left: 50px !important;
            padding-right: 20px !important;
            height: 54px;
            border-radius: 14px !important;
            border: 1.5px solid #e2e8f0 !important;
            background: #ffffff !important;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .search-input-custom:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12) !important;
        }

        .search-input-custom:focus + .search-icon {
            color: #4f46e5;
        }

        .btn-modern-action {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        }

        .btn-modern-secondary {
            background: #f1f5f9;
            color: #475569 !important;
            border: 1px solid #e2e8f0;
        }

        .btn-modern-secondary:hover {
            background: #e2e8f0;
            color: #1e293b !important;
            transform: translateY(-2px);
        }

        /* Modern Accordion Customization */
        .modern-accordion-item {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            overflow: hidden;
            background: #ffffff;
            margin-bottom: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .modern-accordion-item:hover {
            border-color: #cbd5e1 !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        .modern-accordion-button {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%) !important;
            padding: 20px 24px !important;
            font-size: 1.05rem;
            color: #1e293b !important;
            border: none !important;
            box-shadow: none !important;
            transition: all 0.3s ease;
        }

        .modern-accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 100%) !important;
            color: #312e81 !important;
            border-bottom: 1px solid #e0e7ff !important;
        }

        .modern-accordion-button::after {
            background-size: 1.1rem;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .badge-file-count {
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }

        .modern-accordion-button:not(.collapsed) .badge-file-count {
            background: #4f46e5;
            color: #ffffff;
        }

        /* Table Styling */
        .table-modern-wrapper {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }

        .table-modern {
            margin-bottom: 0 !important;
        }

        .table-modern thead {
            background: #f8fafc;
        }

        .table-modern thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            font-weight: 700;
            padding: 14px 18px;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-modern tbody tr {
            transition: background 0.2s ease;
        }

        .table-modern tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-modern tbody td {
            padding: 16px 18px;
            vertical-align: middle;
            color: #334155;
            font-size: 0.925rem;
            border-color: #f1f5f9;
        }

        /* Action Buttons Pill */
        .btn-table-action {
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 0.825rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }

        .btn-table-preview {
            background: rgba(14, 165, 233, 0.1);
            color: #0284c7;
            border: 1px solid rgba(14, 165, 233, 0.2);
        }

        .btn-table-preview:hover {
            background: #0284c7;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-table-download {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .btn-table-download:hover {
            background: #059669;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-table-copy {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .btn-table-copy:hover {
            background: #475569;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .shortlink-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-weight: 600;
            color: #4f46e5;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }

        .shortlink-pill:hover {
            background: #eef2ff;
            border-color: #c7d2fe;
            color: #3730a3;
            transform: translateY(-1px);
        }

        /* File Icon Badges */
        .file-type-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .file-icon-pdf { background: #fee2e2; color: #dc2626; }
        .file-icon-word { background: #dbeafe; color: #2563eb; }
        .file-icon-excel { background: #dcfce7; color: #16a34a; }
        .file-icon-default { background: #f1f5f9; color: #64748b; }

        .section-badge-header {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: rgba(79, 70, 229, 0.08);
            color: #4f46e5;
            border-radius: 30px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .badge-kategori {
            background: rgba(14, 165, 233, 0.1);
            color: #0284c7;
            border: 1px solid rgba(14, 165, 233, 0.2);
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
    </style>

    <div class="multicolumn mt-100 section-padding custom-detail-section">
        <div class="container">

            <!-- Header Banner Card -->
            <div class="section-header-card text-center" data-aos="fade-up">
                <div class="section-badge-header">
                    <i class="bi bi-shield-check"></i>
                    <span>Accreditation Documents</span>
                </div>
                <h2 class="heading text-50 mb-3" style="font-weight: 800; color: #0f172a;">
                    Dokumen & Instrument Akreditasi
                </h2>
                <p class="text-muted max-w-600 mx-auto" style="font-size: 1rem; color: #64748b;">
                    Akses dan unduh berkas akreditasi prodi serta dokumen instrumen pendukung (SAPTO) secara transparan dan terstruktur.
                </p>

                {{-- Search & Controls (Disabled)
                <div class="mt-4 pt-3 border-top">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-8">
                            <div class="search-box-wrapper">
                                <input type="text" id="searchReq" class="form-control search-input-custom" placeholder="Cari kode, nama instrumen, atau judul dokumen...">
                                <i class="bi bi-search search-icon"></i>
                            </div>
                        </div>
                        <div class="col-lg-4 d-flex justify-content-lg-end gap-2">
                            <button id="expandAll" class="btn-modern-action btn-modern-primary w-100 w-lg-auto justify-content-center">
                                <i class="bi bi-arrows-angle-expand"></i> Expand All
                            </button>
                            <button id="collapseAll" class="btn-modern-action btn-modern-secondary w-100 w-lg-auto justify-content-center">
                                <i class="bi bi-arrows-angle-contract"></i> Collapse All
                            </button>
                        </div>
                    </div>
                </div>
                --}}
            </div>

            {{-- Requirements Section (Disabled)
            <div class="section-header-card p-4 mb-4" data-aos="fade-up">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="file-type-icon file-icon-word m-0" style="width: 32px; height: 32px;">
                        <i class="bi bi-folder-symlink" style="font-size: 0.95rem;"></i>
                    </div>
                    <h4 class="m-0" style="font-weight: 700; color: #1e293b; font-size: 1.25rem;">Standard Requirements</h4>
                </div>

                <div class="multicolumn-inner">
                    @if ($requirements->count())
                        @include('components.requirement-tree', ['requirements' => $requirements])
                    @else
                        <div class="alert alert-light border border-dashed text-center p-4 rounded-3 text-muted mb-0">
                            <i class="bi bi-inbox text-30 mb-2 d-block"></i>
                            Belum ada dokumen requirements tersedia.
                        </div>
                    @endif
                </div>
            </div>
            --}}

            <!-- Dakung Prodi (SABTO) Section -->
            <div class="section-header-card p-4" data-aos="fade-up">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="file-type-icon file-icon-pdf m-0" style="width: 36px; height: 36px; background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                            <i class="bi bi-journal-text" style="font-size: 1.1rem;"></i>
                        </div>
                        <div>
                            <h4 class="m-0" style="font-weight: 700; color: #1e293b; font-size: 1.25rem;">Dakung Prodi</h4>
                            <span class="text-muted small">Kelompok dokumen instrumen berdasarkan kriteria akreditasi prodi</span>
                        </div>
                    </div>
                    <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-semibold">
                        {{ $dakungProdiCategories->count() }} Categories
                    </span>
                </div>

                <div class="multicolumn-inner">
                    @if ($dakungProdiCategories->count())
                        @php
                            $groupedCategories = $dakungProdiCategories->groupBy(function($item) {
                                return $item->kategori ?: 'Lainnya';
                            });
                        @endphp

                        @foreach($groupedCategories as $kategoriName => $categories)
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-3 pb-2" style="border-bottom: 2px solid #e2e8f0;">
                                    <h5 class="m-0 fw-bold" style="color: #334155;">
                                        <i class="bi bi-tags-fill me-2" style="color: #4f46e5;"></i>Kategori: {{ $kategoriName }}
                                    </h5>
                                    <span class="badge rounded-pill bg-light text-dark border ms-3 px-2 py-1" style="font-size: 0.75rem;">
                                        {{ $categories->count() }} Instrumen
                                    </span>
                                </div>
                                
                                <div class="accordion accordion-flush" id="accordionSabto{{ Str::slug($kategoriName) }}">
                                    @foreach ($categories->sortBy('order_index') as $idx => $category)
                                        <div class="accordion-item modern-accordion-item">
                                            <h2 class="accordion-header" id="headingSabto{{ $category->id }}">
                                                <button class="accordion-button modern-accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSabto{{ $category->id }}" aria-expanded="false" aria-controls="collapseSabto{{ $category->id }}">
                                                    <div class="d-flex align-items-center justify-content-between w-100 me-3">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <i class="bi bi-file-earmark-code text-primary me-1"></i>
                                                            <span class="fw-bold">{{ $category->name }}</span>
                                                            @if($category->description)
                                                                <span class="text-muted small fw-normal ms-2 d-none d-md-inline">— {{ $category->description }}</span>
                                                            @endif
                                                        </div>
                                                <span class="badge badge-file-count me-2">
                                                    <i class="bi bi-paperclip me-1"></i>{{ $category->files->count() }} Files
                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapseSabto{{ $category->id }}" class="accordion-collapse collapse" aria-labelledby="headingSabto{{ $category->id }}" data-bs-parent="#accordionSabto{{ Str::slug($kategoriName) }}">
                                        <div class="accordion-body p-4 bg-white">
                                            @if($category->description)
                                                <p class="text-muted small mb-3 d-md-none bg-light p-2 rounded">
                                                    <i class="bi bi-info-circle me-1"></i> {{ $category->description }}
                                                </p>
                                            @endif

                                            @if($category->files->count())
                                                <div class="table-responsive table-modern-wrapper">
                                                    <table class="table table-modern align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%" class="text-center">No</th>
                                                                <th>Nama Berkas</th>
                                                                <th width="11%" class="text-center">Preview</th>
                                                                <th width="11%" class="text-center">Unduh</th>
                                                                <th width="20%" class="text-center">Link Dokumen</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($category->files as $fidx => $file)
                                                                @php
                                                                    $ext = strtolower(pathinfo($file->original_name ?? $file->name, PATHINFO_EXTENSION));
                                                                    $iconClass = 'file-icon-default';
                                                                    $biIcon = 'bi-file-earmark-text';
                                                                    if (in_array($ext, ['pdf'])) {
                                                                        $iconClass = 'file-icon-pdf';
                                                                        $biIcon = 'bi-file-earmark-pdf';
                                                                    } elseif (in_array($ext, ['doc', 'docx'])) {
                                                                        $iconClass = 'file-icon-word';
                                                                        $biIcon = 'bi-file-earmark-word';
                                                                    } elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) {
                                                                        $iconClass = 'file-icon-excel';
                                                                        $biIcon = 'bi-file-earmark-excel';
                                                                    }
                                                                    $serverDocUrl = $file->path ? asset($file->path) : null;
                                                                    $shortCode = $file->short_code;
                                                                    $shortLinkUrl = url('/s/' . $shortCode);
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center fw-semibold text-muted">{{ $fidx + 1 }}</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="file-type-icon {{ $iconClass }}">
                                                                                <i class="bi {{ $biIcon }}"></i>
                                                                            </div>
                                                                            <div>
                                                                                <div class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $file->name }}</div>
                                                                                @if($file->original_name && $file->original_name !== $file->name)
                                                                                    <span class="text-muted extra-small" style="font-size: 0.775rem;">
                                                                                        <i class="bi bi-paperclip me-1"></i>{{ $file->original_name }}
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <a href="{{ route('dakung-prodi.preview', $file->id) }}" target="_blank" class="btn-table-action btn-table-preview" title="Preview File">
                                                                            <i class="bi bi-eye-fill"></i> Review
                                                                        </a>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <a href="{{ route('dakung-prodi.download', $file->id) }}" class="btn-table-action btn-table-download" title="Download File">
                                                                            <i class="bi bi-cloud-arrow-down-fill"></i> Download
                                                                        </a>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                                                            <a href="{{ $shortLinkUrl }}" target="_blank" class="shortlink-pill" title="Buka Dokumen (Short Link): {{ $shortLinkUrl }}">
                                                                                <i class="bi bi-link-45deg"></i>
                                                                                <span>/s/{{ $shortCode }}</span>
                                                                            </a>
                                                                            <button type="button" class="btn-table-action btn-table-copy btn-copy-link" data-link="{{ $shortLinkUrl }}" data-server-link="{{ $serverDocUrl }}" title="Salin Short Link ({{ $shortLinkUrl }})">
                                                                                <i class="bi bi-clipboard"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-4 bg-light rounded-3">
                                                    <i class="bi bi-folder2-open text-muted" style="font-size: 2rem;"></i>
                                                    <p class="text-muted small mb-0 mt-2">Belum ada file yang diunggah pada instrumen ini.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                            <div class="alert alert-light border text-center p-4 rounded-3 text-muted mb-0">
                                <i class="bi bi-journal-x text-30 mb-2 d-block"></i>
                                Belum ada instrumen SABTO yang didaftarkan untuk akreditasi ini.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById("searchReq");
            const items = document.querySelectorAll(".modern-accordion-item, .req-tree-item");

            if (searchInput) {
                searchInput.addEventListener("input", function() {
                    const q = this.value.toLowerCase().trim();

                    items.forEach(item => {
                        const text = item.innerText.toLowerCase();
                        if (!q) {
                            item.style.display = "";
                        } else {
                            item.style.display = text.includes(q) ? "" : "none";
                        }
                    });
                });
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const expandAllBtn = document.getElementById("expandAll");
            const collapseAllBtn = document.getElementById("collapseAll");
            const collapses = document.querySelectorAll(".accordion-collapse");
            const toggleButtons = document.querySelectorAll(".req-toggle-btn[aria-expanded]");

            if (expandAllBtn) {
                expandAllBtn.addEventListener("click", () => {
                    collapses.forEach(el => {
                        el.classList.add("show");
                        el.style.height = "auto";
                    });
                    toggleButtons.forEach(btn => {
                        btn.setAttribute("aria-expanded", "true");
                    });
                });
            }

            if (collapseAllBtn) {
                collapseAllBtn.addEventListener("click", () => {
                    collapses.forEach(el => {
                        el.classList.remove("show");
                        el.style.height = "0";
                    });
                    toggleButtons.forEach(btn => {
                        btn.setAttribute("aria-expanded", "false");
                    });
                });
            }
            
            // Script for copy link feedback
            const copyButtons = document.querySelectorAll('.btn-copy-link');
            copyButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const link = this.getAttribute('data-link');
                    if (!link) return;
                    navigator.clipboard.writeText(link).then(() => {
                        const originalHtml = this.innerHTML;
                        this.innerHTML = '<i class="bi bi-check2 text-white"></i>';
                        this.style.background = '#10b981';
                        this.style.color = '#ffffff';
                        this.style.borderColor = '#10b981';

                        setTimeout(() => {
                            this.innerHTML = originalHtml;
                            this.style.background = '';
                            this.style.color = '';
                            this.style.borderColor = '';
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy link: ', err);
                    });
                });
            });
        });
    </script>
@endpush
