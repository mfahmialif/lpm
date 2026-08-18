@extends('layouts.home.template')
@section('title', 'Dokumen Institusi - LPM UII Dalwa')

@push('css')
{{-- Include DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
{{-- Include FontAwesome for action buttons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
{{-- Include Select2 CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

<style>
    .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .accordion-button:not(.collapsed)::after {
        filter: brightness(0) invert(1);
    }

    .accordion-button {
        font-size: 1.1rem;
        padding: 1rem 1.25rem;
        background-color: #fff;
        border: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: transparent;
    }

    .accordion-item {
        border-radius: 10px !important;
        overflow: hidden;
    }

    .accordion-body {
        background-color: #f8f9fa;
    }

    .table thead th {
        background-color: #e9ecef;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .table tbody tr:hover {
        background-color: #f1f3f5;
    }

    .dataTables_wrapper .dataTables_filter input {
        border-radius: 20px;
        padding: 5px 15px;
    }

    .dataTables_wrapper .dataTables_length select {
        border-radius: 5px;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    /* Badge styles for Bootstrap 4 class names from API */
    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }

    .badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .shortlink-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
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
        border-color: #cbd5e1;
        color: #3730a3;
        transform: translateY(-1px);
    }

    .btn-table-copy {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }

    .btn-table-copy:hover {
        background: #475569;
        color: #ffffff;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')

<div class="multicolumn mt-100 section-padding">
    <div class="media media-bg">
        <img src="{{ asset('home') }}/assets/img/contact/contact-bg.jpg" width="1920" height="883" loading="lazy"
            alt="Background image">
    </div>
    <div class="container">
        <div class="multicolumn-header section-headings mb-5">
            <div class="subheading text-20 subheading-bg aos-init aos-animate" data-aos="fade-up">
                <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                    viewBox="0 0 14 14" fill="none">
                    <g clip-path="url(#clip0_9088_4143)">
                        <path
                            d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                            fill="CurrentColor"></path>
                    </g>
                    <defs>
                        <clipPath>
                            <rect width="14" height="14" fill="CurrentColor"></rect>
                        </clipPath>
                    </defs>
                </svg>
                <span>Dokumen Institusi</span>
                <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                    viewBox="0 0 14 14" fill="none">
                    <g clip-path="url(#clip0_9088_4143)">
                        <path
                            d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                            fill="CurrentColor"></path>
                    </g>
                    <defs>
                        <clipPath>
                            <rect width="14" height="14" fill="CurrentColor"></rect>
                        </clipPath>
                    </defs>
                </svg>
            </div>
        </div>

        {{-- Accordion Document Categories --}}
        <div class="accordion" id="accordionDocuments">

            {{-- 0. SK Pendirian Prodi --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up">
                <h2 class="accordion-header" id="headingSkPendirianProdi">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseSkPendirianProdi" aria-expanded="false"
                        aria-controls="collapseSkPendirianProdi" data-table="sk-pendirian-prodi">
                        <i class="ti ti-file-certificate me-2 fs-4"></i>
                        <strong>Keputusan Menteri Agama</strong>
                    </button>
                </h2>
                <div id="collapseSkPendirianProdi" class="accordion-collapse collapse"
                    aria-labelledby="headingSkPendirianProdi" data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-sk-pendirian-prodi" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th>Status</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 1. Keputusan Rektor --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up">
                <h2 class="accordion-header" id="headingKeputusanRektor">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseKeputusanRektor" aria-expanded="false"
                        aria-controls="collapseKeputusanRektor" data-table="keputusan-rektor">
                        <i class="ti ti-file-text me-2 fs-4"></i>
                        <strong>Keputusan Rektor/Direktur/Dekan</strong>
                    </button>
                </h2>
                <div id="collapseKeputusanRektor" class="accordion-collapse collapse"
                    aria-labelledby="headingKeputusanRektor" data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-unit-keputusan" class="form-label">Filter Unit:</label>
                                <select class="form-select" id="filter-unit-keputusan">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-keputusan-rektor" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Unit</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. SPMI --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="50">
                <h2 class="accordion-header" id="headingSpmi">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseSpmi" aria-expanded="false" aria-controls="collapseSpmi"
                        data-table="spmi">
                        <i class="ti ti-certificate me-2 fs-4"></i>
                        <strong>SPMI (Sistem Penjaminan Mutu Internal)</strong>
                    </button>
                </h2>
                <div id="collapseSpmi" class="accordion-collapse collapse" aria-labelledby="headingSpmi"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-spmi" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Siklus PPEPP --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="100">
                <h2 class="accordion-header" id="headingSiklusPpepp">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseSiklusPpepp" aria-expanded="false"
                        aria-controls="collapseSiklusPpepp" data-table="siklus-ppepp">
                        <i class="ti ti-refresh me-2 fs-4"></i>
                        <strong>Siklus PPEPP</strong>
                    </button>
                </h2>
                <div id="collapseSiklusPpepp" class="accordion-collapse collapse"
                    aria-labelledby="headingSiklusPpepp" data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-unit-siklus-ppepp" class="form-label">Filter Unit (Prodi):</label>
                                <select class="form-select" id="filter-unit-siklus-ppepp">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($unit_ppepp as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-siklus-ppepp" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Statuta --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="150">
                <h2 class="accordion-header" id="headingStatuta">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseStatuta" aria-expanded="false" aria-controls="collapseStatuta"
                        data-table="statuta">
                        <i class="ti ti-book me-2 fs-4"></i>
                        <strong>Statuta</strong>
                    </button>
                </h2>
                <div id="collapseStatuta" class="accordion-collapse collapse" aria-labelledby="headingStatuta"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-statuta" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. Renstra --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="200">
                <h2 class="accordion-header" id="headingRenstra">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseRenstra" aria-expanded="false" aria-controls="collapseRenstra"
                        data-table="renstra">
                        <i class="ti ti-target me-2 fs-4"></i>
                        <strong>Renstra (Rencana Strategis)</strong>
                    </button>
                </h2>
                <div id="collapseRenstra" class="accordion-collapse collapse" aria-labelledby="headingRenstra"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-unit-renstra" class="form-label">Filter Unit (Fakultas):</label>
                                <select class="form-select" id="filter-unit-renstra">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($unit_renstra as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-renstra" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th>Unit</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5b. RIP (Rencana Induk Pengembangan) --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="220">
                <h2 class="accordion-header" id="headingRip">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseRip" aria-expanded="false" aria-controls="collapseRip"
                        data-table="rip">
                        <i class="ti ti-map-2 me-2 fs-4"></i>
                        <strong>RIP (Rencana Induk Pengembangan)</strong>
                    </button>
                </h2>
                <div id="collapseRip" class="accordion-collapse collapse" aria-labelledby="headingRip"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-unit-rip" class="form-label">Filter Unit:</label>
                                <select class="form-select" id="filter-unit-rip">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($unit_rip as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-rip" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th>Unit</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. Renop --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="250">
                <h2 class="accordion-header" id="headingRenop">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseRenop" aria-expanded="false" aria-controls="collapseRenop"
                        data-table="renop">
                        <i class="ti ti-calendar me-2 fs-4"></i>
                        <strong>Renop (Rencana Operasional)</strong>
                    </button>
                </h2>
                <div id="collapseRenop" class="accordion-collapse collapse" aria-labelledby="headingRenop"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-unit-renop" class="form-label">Filter Unit:</label>
                                <select class="form-select" id="filter-unit-renop">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($unit_renop as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-renop" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th>Unit</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 7. SOTK --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="300">
                <h2 class="accordion-header" id="headingSotk">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseSotk" aria-expanded="false" aria-controls="collapseSotk"
                        data-table="sotk">
                        <i class="ti ti-hierarchy me-2 fs-4"></i>
                        <strong>SOTK (Struktur Organisasi dan Tata Kerja)</strong>
                    </button>
                </h2>
                <div id="collapseSotk" class="accordion-collapse collapse" aria-labelledby="headingSotk"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-sotk" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 8. Kurikulum Prodi --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="350">
                <h2 class="accordion-header" id="headingKurikulumProdi">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseKurikulumProdi" aria-expanded="false"
                        aria-controls="collapseKurikulumProdi" data-table="kurikulum-prodi">
                        <i class="ti ti-school me-2 fs-4"></i>
                        <strong>Kurikulum Prodi</strong>
                    </button>
                </h2>
                <div id="collapseKurikulumProdi" class="accordion-collapse collapse"
                    aria-labelledby="headingKurikulumProdi" data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-prodi-kurikulum" class="form-label">Filter Program Studi:</label>
                                <select class="form-select" id="filter-prodi-kurikulum">
                                    <option value="">-- Semua Program Studi --</option>
                                    @foreach ($list_prodi as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-kurikulum-prodi" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 9. Laporan Benchmarking --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="400">
                <h2 class="accordion-header" id="headingLaporanBenchmarking">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseLaporanBenchmarking" aria-expanded="false"
                        aria-controls="collapseLaporanBenchmarking" data-table="laporan-benchmarking">
                        <i class="ti ti-chart-bar me-2 fs-4"></i>
                        <strong>Laporan Benchmarking</strong>
                    </button>
                </h2>
                <div id="collapseLaporanBenchmarking" class="accordion-collapse collapse"
                    aria-labelledby="headingLaporanBenchmarking" data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-unit-benchmarking" class="form-label">Filter Unit:</label>
                                <select class="form-select" id="filter-unit-benchmarking">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($unit_benchmarking as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-laporan-benchmarking" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th>Unit</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 10. Laporan Evaluasi PPEPP --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="450">
                <h2 class="accordion-header" id="headingLaporanEvaluasiPpepp">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseLaporanEvaluasiPpepp" aria-expanded="false"
                        aria-controls="collapseLaporanEvaluasiPpepp" data-table="laporan-evaluasi-ppepp">
                        <i class="ti ti-report-analytics me-2 fs-4"></i>
                        <strong>Laporan Evaluasi PPEPP</strong>
                    </button>
                </h2>
                <div id="collapseLaporanEvaluasiPpepp" class="accordion-collapse collapse"
                    aria-labelledby="headingLaporanEvaluasiPpepp" data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-unit-evaluasi-ppepp" class="form-label">Filter Unit (Prodi):</label>
                                <select class="form-select" id="filter-unit-evaluasi-ppepp">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($unit_ppepp as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-laporan-evaluasi-ppepp" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th>Unit</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- 11. Buku (Publikasi) --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="500">
                <h2 class="accordion-header" id="headingBuku">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseBuku" aria-expanded="false" aria-controls="collapseBuku"
                        data-table="buku">
                        <i class="ti ti-book-2 me-2 fs-4"></i>
                        <strong>Buku (Publikasi)</strong>
                    </button>
                </h2>
                <div id="collapseBuku" class="accordion-collapse collapse" aria-labelledby="headingBuku"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-prodi-buku" class="form-label">Filter Program Studi:</label>
                                <select class="form-select" id="filter-prodi-buku">
                                    <option value="">-- Semua Program Studi --</option>
                                    @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-buku" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Judul</th>
                                        <th>Nama Dosen</th>
                                        <th>Program Studi</th>
                                        <th>Publisher</th>
                                        <th>Tahun</th>
                                        <th>Klasifikasi</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 12. Prosiding (Publikasi) --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="550">
                <h2 class="accordion-header" id="headingProsiding">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseProsiding" aria-expanded="false" aria-controls="collapseProsiding"
                        data-table="prosiding">
                        <i class="ti ti-files me-2 fs-4"></i>
                        <strong>Prosiding (Publikasi)</strong>
                    </button>
                </h2>
                <div id="collapseProsiding" class="accordion-collapse collapse" aria-labelledby="headingProsiding"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-prodi-prosiding" class="form-label">Filter Program Studi:</label>
                                <select class="form-select" id="filter-prodi-prosiding">
                                    <option value="">-- Semua Program Studi --</option>
                                    @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-prosiding" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Judul</th>
                                        <th>Nama Dosen</th>
                                        <th>Program Studi</th>
                                        <th>Nama Konferensi</th>
                                        <th>Tahun</th>
                                        <th>Klasifikasi</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 13. Artikel (Publikasi) --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="600">
                <h2 class="accordion-header" id="headingArtikel">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseArtikel" aria-expanded="false" aria-controls="collapseArtikel"
                        data-table="artikel">
                        <i class="ti ti-article me-2 fs-4"></i>
                        <strong>Artikel (Publikasi)</strong>
                    </button>
                </h2>
                <div id="collapseArtikel" class="accordion-collapse collapse" aria-labelledby="headingArtikel"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-prodi-artikel" class="form-label">Filter Program Studi:</label>
                                <select class="form-select" id="filter-prodi-artikel">
                                    <option value="">-- Semua Program Studi --</option>
                                    @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-artikel" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Judul</th>
                                        <th>Nama Dosen</th>
                                        <th>Program Studi</th>
                                        <th>Tahun</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 14. Jurnal (Publikasi) --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="650">
                <h2 class="accordion-header" id="headingJurnal">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseJurnal" aria-expanded="false" aria-controls="collapseJurnal"
                        data-table="jurnal">
                        <i class="ti ti-notebook me-2 fs-4"></i>
                        <strong>Jurnal (Publikasi)</strong>
                    </button>
                </h2>
                <div id="collapseJurnal" class="accordion-collapse collapse" aria-labelledby="headingJurnal"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-prodi-jurnal" class="form-label">Filter Program Studi:</label>
                                <select class="form-select" id="filter-prodi-jurnal">
                                    <option value="">-- Semua Program Studi --</option>
                                    @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-jurnal" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Judul</th>
                                        <th>Nama Dosen</th>
                                        <th>Program Studi</th>
                                        <th>Publisher</th>
                                        <th>Tahun</th>
                                        <th>SINTA</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 15. RPS (Rencana Pembelajaran Semester) --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="700">
                <h2 class="accordion-header" id="headingRps">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseRps" aria-expanded="false" aria-controls="collapseRps"
                        data-table="rps">
                        <i class="ti ti-clipboard-list me-2 fs-4"></i>
                        <strong>RPS (Rencana Pembelajaran Semester)</strong>
                    </button>
                </h2>
                <div id="collapseRps" class="accordion-collapse collapse" aria-labelledby="headingRps"
                    data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-prodi-rps" class="form-label">Filter Program Studi:</label>
                                <select class="form-select" id="filter-prodi-rps">
                                    <option value="">-- Semua Program Studi --</option>
                                    @foreach ($prodiRps as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-rps" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Mata Kuliah</th>
                                        <th>Program Studi</th>
                                        <th>Dosen</th>
                                        <th>Kurikulum</th>
                                        <th>Tahun Akademik</th>
                                        <th>Status</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 11. Pedoman --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="500">
                <h2 class="accordion-header" id="headingPedoman">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsePedoman" aria-expanded="false"
                        aria-controls="collapsePedoman" data-table="pedoman">
                        <i class="ti ti-book me-2 fs-4"></i>
                        <strong>Pedoman-Pedoman</strong>
                    </button>
                </h2>
                <div id="collapsePedoman" class="accordion-collapse collapse"
                    aria-labelledby="headingPedoman" data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="filter-unit-pedoman" class="form-label">Filter Unit:</label>
                                <select class="form-select" id="filter-unit-pedoman">
                                    <option value="">-- Semua Unit --</option>
                                    @foreach ($unit_pedoman as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-pedoman" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th>Unit</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 12. Diferensiasi Misi --}}
            <div class="accordion-item mb-3 border-0 shadow-sm rounded" data-aos="fade-up" data-aos-delay="550">
                <h2 class="accordion-header" id="headingDiferensiasiMisi">
                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseDiferensiasiMisi" aria-expanded="false"
                        aria-controls="collapseDiferensiasiMisi" data-table="diferensiasi-misi">
                        <i class="ti ti-compass me-2 fs-4"></i>
                        <strong>Diferensiasi Misi</strong>
                    </button>
                </h2>
                <div id="collapseDiferensiasiMisi" class="accordion-collapse collapse"
                    aria-labelledby="headingDiferensiasiMisi" data-bs-parent="#accordionDocuments">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="table-diferensiasi-misi" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Dokumen</th>
                                        <th>No Surat</th>
                                        <th>Perihal</th>
                                        <th>Yang Mengeluarkan</th>
                                        <th>Status</th>
                                        <th width="10%" class="text-center">File</th>
                                        <th width="18%" class="text-center">Link Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('script')
{{-- Include jQuery (required for DataTables) --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
{{-- Include DataTables JS --}}
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
{{-- Include Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 on prodi filter dropdowns
        $('#filter-prodi-buku, #filter-prodi-jurnal, #filter-prodi-prosiding, #filter-prodi-artikel, #filter-prodi-rps, #filter-unit-siklus-ppepp, #filter-prodi-kurikulum, #filter-unit-renstra, #filter-unit-rip, #filter-unit-renop, #filter-unit-benchmarking, #filter-unit-evaluasi-ppepp, #filter-unit-pedoman').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Filter --',
            allowClear: true,
            width: '100%'
        });

        // Store initialized DataTables
        var initializedTables = {};

        // DataTable configurations for each document type
        var tableConfigs = {
            'sk-pendirian-prodi': {
                url: "{{ route('institution-document.data.sk-pendirian-prodi') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'keputusan-rektor': {
                url: "{{ route('institution-document.data.keputusan-rektor') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'unit_nama',
                        name: 'units_dokument.nama'
                    },
                    {
                        data: 'nama',
                        name: 'document_keputusan_rektor.nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'document_keputusan_rektor.no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'document_keputusan_rektor.perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'document_keputusan_rektor.yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'spmi': {
                url: "{{ route('institution-document.data.spmi') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'siklus-ppepp': {
                url: "{{ route('institution-document.data.siklus-ppepp') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'statuta': {
                url: "{{ route('institution-document.data.statuta') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'renstra': {
                url: "{{ route('institution-document.data.renstra') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'rip': {
                url: "{{ route('institution-document.data.rip') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'renop': {
                url: "{{ route('institution-document.data.renop') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'sotk': {
                url: "{{ route('institution-document.data.sotk') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'kurikulum-prodi': {
                url: "{{ route('institution-document.data.kurikulum-prodi') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'laporan-benchmarking': {
                url: "{{ route('institution-document.data.laporan-benchmarking') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'laporan-evaluasi-ppepp': {
                url: "{{ route('institution-document.data.laporan-evaluasi-ppepp') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'pedoman': {
                url: "{{ route('institution-document.data.pedoman') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        defaultContent: '-'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'diferensiasi-misi': {
                url: "{{ route('institution-document.data.diferensiasi-misi') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat',
                        defaultContent: '-'
                    },
                    {
                        data: 'perihal',
                        name: 'perihal',
                        defaultContent: '-'
                    },
                    {
                        data: 'yang_mengeluarkan',
                        name: 'yang_mengeluarkan',
                        defaultContent: '-'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'file',
                        name: 'file',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false
                    }
                ]
            },
            'buku': {
                url: "{{ route('institution-document.data.buku') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_dosen',
                        name: 'nama_dosen',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_prodi',
                        name: 'nama_prodi',
                        defaultContent: '-'
                    },
                    {
                        data: 'publisher',
                        name: 'publisher',
                        defaultContent: '-'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        defaultContent: '-'
                    },
                    {
                        data: 'klasifikasi',
                        name: 'klasifikasi',
                        defaultContent: '-'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    }
                ]
            },
            'jurnal': {
                url: "{{ route('institution-document.data.jurnal') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_dosen',
                        name: 'nama_dosen',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_prodi',
                        name: 'nama_prodi',
                        defaultContent: '-'
                    },
                    {
                        data: 'publisher',
                        name: 'publisher',
                        defaultContent: '-'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        defaultContent: '-'
                    },
                    {
                        data: 'sinta',
                        name: 'sinta',
                        defaultContent: '-'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    }
                ]
            },
            'prosiding': {
                url: "{{ route('institution-document.data.prosiding') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_dosen',
                        name: 'nama_dosen',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_prodi',
                        name: 'nama_prodi',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_konferensi',
                        name: 'nama_konferensi',
                        defaultContent: '-'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        defaultContent: '-'
                    },
                    {
                        data: 'klasifikasi',
                        name: 'klasifikasi',
                        defaultContent: '-'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    }
                ]
            },
            'artikel': {
                url: "{{ route('institution-document.data.artikel') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        name: 'judul',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_dosen',
                        name: 'nama_dosen',
                        defaultContent: '-'
                    },
                    {
                        data: 'nama_prodi',
                        name: 'nama_prodi',
                        defaultContent: '-'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun',
                        defaultContent: '-'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    }
                ]
            },
            'rps': {
                url: "{{ route('institution-document.data.rps') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'matakuliah_nama',
                        name: 'matakuliah_nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'prodi_nama',
                        name: 'prodi_nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'dosen_nama',
                        name: 'dosen_nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'kurikulum_nama',
                        name: 'kurikulum_nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'tahun_akademik',
                        name: 'tahun_akademik',
                        defaultContent: '-'
                    },
                    {
                        data: 'status_validasi',
                        name: 'status_validasi',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data ? data : '-';
                        }
                    }
                ]
            }
        };

        // Initialize DataTable when accordion is shown
        $('.accordion-collapse').on('shown.bs.collapse', function() {
            var tableId = $(this).find('table').attr('id');
            var tableKey = tableId.replace('table-', '');

            if (!initializedTables[tableKey] && tableConfigs[tableKey]) {
                var config = tableConfigs[tableKey];
                var ajaxConfig;

                // Special handling for keputusan-rektor with filter
                if (tableKey === 'keputusan-rektor') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.unit_dokument_id = $('#filter-unit-keputusan').val();
                        }
                    };
                }
                // Special handling for siklus-ppepp with unit filter
                else if (tableKey === 'siklus-ppepp') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.unit_dokumen_id = $('#filter-unit-siklus-ppepp').val();
                        }
                    };
                }
                // Special handling for kurikulum-prodi with prodi filter
                else if (tableKey === 'kurikulum-prodi') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.prodi_id = $('#filter-prodi-kurikulum').val();
                        }
                    };
                }
                // Special handling for renstra with unit filter
                else if (tableKey === 'renstra') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.unit_id = $('#filter-unit-renstra').val();
                        }
                    };
                }
                // Special handling for rip with unit filter
                else if (tableKey === 'rip') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.unit_id = $('#filter-unit-rip').val();
                        }
                    };
                }
                // Special handling for renop with unit filter
                else if (tableKey === 'renop') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.unit_id = $('#filter-unit-renop').val();
                        }
                    };
                }
                // Special handling for laporan-benchmarking with unit filter
                else if (tableKey === 'laporan-benchmarking') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.unit_id = $('#filter-unit-benchmarking').val();
                        }
                    };
                }
                // Special handling for laporan-evaluasi-ppepp with unit filter
                else if (tableKey === 'laporan-evaluasi-ppepp') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.unit_dokument_id = $('#filter-unit-evaluasi-ppepp').val();
                        }
                    };
                }
                // Special handling for pedoman with unit filter
                else if (tableKey === 'pedoman') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.unit_dokument_id = $('#filter-unit-pedoman').val();
                        }
                    };
                }
                // Special handling for buku with prodi filter
                else if (tableKey === 'buku') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.prodi_id = $('#filter-prodi-buku').val();
                        }
                    };
                }
                // Special handling for jurnal with prodi filter
                else if (tableKey === 'jurnal') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.prodi_id = $('#filter-prodi-jurnal').val();
                        }
                    };
                }
                // Special handling for prosiding with prodi filter
                else if (tableKey === 'prosiding') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.prodi_id = $('#filter-prodi-prosiding').val();
                        }
                    };
                }
                // Special handling for artikel with prodi filter
                else if (tableKey === 'artikel') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.prodi_id = $('#filter-prodi-artikel').val();
                        }
                    };
                }
                // Special handling for rps with prodi filter
                else if (tableKey === 'rps') {
                    ajaxConfig = {
                        url: config.url,
                        data: function(d) {
                            d.prodi_id = $('#filter-prodi-rps').val();
                        }
                    };
                } else {
                    ajaxConfig = config.ajax ? config.ajax : config.url;
                }

                initializedTables[tableKey] = $('#' + tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: ajaxConfig,
                    columns: config.columns,
                    language: {
                        processing: "Memproses...",
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Tidak ada data yang cocok",
                        emptyTable: "Tidak ada data tersedia",
                        paginate: {
                            first: "Pertama",
                            previous: "Sebelumnya",
                            next: "Selanjutnya",
                            last: "Terakhir"
                        }
                    },
                    responsive: true,
                    order: [
                        [1, 'asc']
                    ]
                });
            }
        });

        // Event handler for unit filter on keputusan-rektor
        $('#filter-unit-keputusan').on('change', function() {
            if (initializedTables['keputusan-rektor']) {
                initializedTables['keputusan-rektor'].ajax.reload();
            }
        });

        // Event handler for unit filter on siklus-ppepp
        $('#filter-unit-siklus-ppepp').on('change', function() {
            if (initializedTables['siklus-ppepp']) {
                initializedTables['siklus-ppepp'].ajax.reload();
            }
        });

        // Event handler for prodi filter on kurikulum-prodi
        $('#filter-prodi-kurikulum').on('change', function() {
            if (initializedTables['kurikulum-prodi']) {
                initializedTables['kurikulum-prodi'].ajax.reload();
            }
        });

        // Event handler for unit filter on renstra
        $('#filter-unit-renstra').on('change', function() {
            if (initializedTables['renstra']) {
                initializedTables['renstra'].ajax.reload();
            }
        });

        // Event handler for unit filter on rip
        $('#filter-unit-rip').on('change', function() {
            if (initializedTables['rip']) {
                initializedTables['rip'].ajax.reload();
            }
        });

        // Event handler for unit filter on renop
        $('#filter-unit-renop').on('change', function() {
            if (initializedTables['renop']) {
                initializedTables['renop'].ajax.reload();
            }
        });

        // Event handler for unit filter on laporan-benchmarking
        $('#filter-unit-benchmarking').on('change', function() {
            if (initializedTables['laporan-benchmarking']) {
                initializedTables['laporan-benchmarking'].ajax.reload();
            }
        });

        // Event handler for unit filter on laporan-evaluasi-ppepp
        $('#filter-unit-evaluasi-ppepp').on('change', function() {
            if (initializedTables['laporan-evaluasi-ppepp']) {
                initializedTables['laporan-evaluasi-ppepp'].ajax.reload();
            }
        });

        // Event handler for unit filter on pedoman
        $('#filter-unit-pedoman').on('change', function() {
            if (initializedTables['pedoman']) {
                initializedTables['pedoman'].ajax.reload();
            }
        });

        // Event handler for prodi filter on buku
        $('#filter-prodi-buku').on('change', function() {
            if (initializedTables['buku']) {
                initializedTables['buku'].ajax.reload();
            }
        });

        // Event handler for prodi filter on jurnal
        $('#filter-prodi-jurnal').on('change', function() {
            if (initializedTables['jurnal']) {
                initializedTables['jurnal'].ajax.reload();
            }
        });

        // Event handler for prodi filter on prosiding
        $('#filter-prodi-prosiding').on('change', function() {
            if (initializedTables['prosiding']) {
                initializedTables['prosiding'].ajax.reload();
            }
        });

        // Event handler for prodi filter on artikel
        $('#filter-prodi-artikel').on('change', function() {
            if (initializedTables['artikel']) {
                initializedTables['artikel'].ajax.reload();
            }
        });

        // Event handler for prodi filter on rps
        $('#filter-prodi-rps').on('change', function() {
            if (initializedTables['rps']) {
                initializedTables['rps'].ajax.reload();
            }
        });

        // Copy short link handler with interactive feedback
        $(document).on('click', '.btn-copy-link', function(e) {
            e.preventDefault();
            const link = $(this).attr('data-link');
            if (!link) return;
            const btn = $(this);
            navigator.clipboard.writeText(link).then(() => {
                const originalHtml = btn.html();
                btn.html('<i class="ti ti-check text-white"></i>');
                btn.css({
                    'background': '#10b981',
                    'color': '#ffffff',
                    'border-color': '#10b981'
                });

                setTimeout(() => {
                    btn.html(originalHtml);
                    btn.css({
                        'background': '',
                        'color': '',
                        'border-color': ''
                    });
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy link: ', err);
            });
        });
    });
</script>
@endpush