@extends('layouts.admin.template')
@section('title', 'Dashboard AMI')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">AMI /</span> Dashboard</h4>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-xl-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Periode Aktif</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2">{{ $periodeAktif }}</h4>
                        </div>
                        <small class="mb-0">Periode berjalan</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti ti-calendar-event ti-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">Total SK</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2">{{ $totalSk }}</h4>
                        </div>
                        <small class="mb-0">SK Auditor terdaftar</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ti ti-file-certificate ti-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">SK Aktif</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2">{{ $skAktif }}</h4>
                        </div>
                        <small class="mb-0">Sedang berjalan</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ti ti-clock ti-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-heading">{{ $isAdmin ? 'SK Selesai' : 'SK Saya' }}</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2">{{ $isAdmin ? $skSelesai : $mySk }}</h4>
                        </div>
                        <small class="mb-0">{{ $isAdmin ? 'Audit selesai' : 'Terkait dengan Anda' }}</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ti ti-check ti-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- ALUR PROSES AMI --}}
{{-- ============================================================ --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="ti ti-timeline ti-22px me-2 text-primary"></i>
                <h5 class="card-title mb-0">Alur Proses AMI</h5>
            </div>
            <div class="card-body">
                @if($isAdmin)
                {{-- ALUR ADMIN --}}
                <div class="alert alert-primary border-0 mb-3" role="alert">
                    <i class="ti ti-info-circle me-1"></i>
                    Berikut adalah alur lengkap proses AMI yang dikelola oleh <strong>Admin / LPM</strong>.
                </div>
                <div class="ami-timeline">
                    <div class="ami-step">
                        <div class="ami-step-number bg-primary">1</div>
                        <div class="ami-step-content">
                            <strong>Setup Periode</strong>
                            <small class="d-block text-muted">Buat & aktifkan periode AMI</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-primary">2</div>
                        <div class="ami-step-content">
                            <strong>Buat Unit Audit</strong>
                            <small class="d-block text-muted">Daftarkan unit yang akan diaudit</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-primary">3</div>
                        <div class="ami-step-content">
                            <strong>Buat SK Auditor</strong>
                            <small class="d-block text-muted">Tentukan ketua, anggota & auditee</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-info">4</div>
                        <div class="ami-step-content">
                            <strong>Target AMI</strong>
                            <small class="d-block text-muted">Tentukan standar & indikator target</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-success">5</div>
                        <div class="ami-step-content">
                            <strong>Evaluasi Diri</strong>
                            <small class="d-block text-muted">Auditee mengisi evaluasi diri</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-success">6</div>
                        <div class="ami-step-content">
                            <strong>Asesmen Auditor</strong>
                            <small class="d-block text-muted">Auditor melakukan asesmen</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-warning">7</div>
                        <div class="ami-step-content">
                            <strong>Temuan & Hasil</strong>
                            <small class="d-block text-muted">Dokumentasi temuan audit</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-warning">8</div>
                        <div class="ami-step-content">
                            <strong>Laporan Kinerja</strong>
                            <small class="d-block text-muted">Laporan kinerja unit</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-danger">9</div>
                        <div class="ami-step-content">
                            <strong>RTM</strong>
                            <small class="d-block text-muted">Rapat Tinjauan Manajemen</small>
                        </div>
                    </div>
                </div>

                @elseif($currentMode === 'auditee')
                {{-- ALUR AUDITEE --}}
                <div class="alert alert-info border-0 mb-3" role="alert">
                    <i class="ti ti-info-circle me-1"></i>
                    Berikut adalah alur proses AMI untuk <strong>Auditee</strong>.
                </div>
                <div class="ami-timeline">
                    <div class="ami-step">
                        <div class="ami-step-number bg-primary">1</div>
                        <div class="ami-step-content">
                            <strong>Terima SK</strong>
                            <small class="d-block text-muted">Cek SK Auditor yang ditugaskan</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-success">2</div>
                        <div class="ami-step-content">
                            <strong>Isi Evaluasi Diri</strong>
                            <small class="d-block text-muted">Lengkapi data evaluasi diri unit</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-warning">3</div>
                        <div class="ami-step-content">
                            <strong>Review Temuan</strong>
                            <small class="d-block text-muted">Lihat temuan dari auditor</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-info">4</div>
                        <div class="ami-step-content">
                            <strong>Tindak Lanjut</strong>
                            <small class="d-block text-muted">Laksanakan perbaikan & laporan kinerja</small>
                        </div>
                    </div>
                </div>

                @elseif($currentMode === 'auditor')
                {{-- ALUR AUDITOR --}}
                <div class="alert alert-success border-0 mb-3" role="alert">
                    <i class="ti ti-info-circle me-1"></i>
                    Berikut adalah alur proses AMI untuk <strong>Auditor</strong>.
                </div>
                <div class="ami-timeline">
                    <div class="ami-step">
                        <div class="ami-step-number bg-primary">1</div>
                        <div class="ami-step-content">
                            <strong>Terima SK</strong>
                            <small class="d-block text-muted">Cek penugasan SK Auditor</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-info">2</div>
                        <div class="ami-step-content">
                            <strong>Review Evaluasi Diri</strong>
                            <small class="d-block text-muted">Periksa evaluasi diri auditee</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-success">3</div>
                        <div class="ami-step-content">
                            <strong>Isi Asesmen</strong>
                            <small class="d-block text-muted">Lakukan asesmen terhadap unit</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-warning">4</div>
                        <div class="ami-step-content">
                            <strong>Buat Temuan</strong>
                            <small class="d-block text-muted">Dokumentasikan temuan audit</small>
                        </div>
                    </div>
                    <div class="ami-step-connector"></div>
                    <div class="ami-step">
                        <div class="ami-step-number bg-danger">5</div>
                        <div class="ami-step-content">
                            <strong>Laporan Kinerja</strong>
                            <small class="d-block text-muted">Buat laporan kinerja audit</small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- PANDUAN PENGISIAN --}}
{{-- ============================================================ --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="ti ti-book ti-22px me-2 text-warning"></i>
                <h5 class="card-title mb-0">Panduan Pengisian</h5>
            </div>
            <div class="card-body">
                <div class="accordion" id="panduanAccordion">

                    @if($isAdmin)
                    {{-- PANDUAN ADMIN --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panduanPeriode">
                                <i class="ti ti-calendar me-2 text-primary"></i> Periode AMI
                            </button>
                        </h2>
                        <div id="panduanPeriode" class="accordion-collapse collapse show" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka menu <strong>Periode</strong>, lalu klik <strong>Tambah Periode</strong>.</li>
                                    <li>Isi nama periode (contoh: "Semester Ganjil 2025/2026"), tanggal mulai & selesai.</li>
                                    <li>Set status ke <strong>Aktif</strong> untuk memulai siklus AMI.</li>
                                    <li>Hanya <strong>1 periode aktif</strong> yang diizinkan pada satu waktu.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanUnitAudit">
                                <i class="ti ti-building me-2 text-secondary"></i> Unit Audit
                            </button>
                        </h2>
                        <div id="panduanUnitAudit" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka menu <strong>Unit Audit</strong>, klik <strong>Tambah Unit</strong>.</li>
                                    <li>Pilih prodi/unit yang akan diaudit beserta periode.</li>
                                    <li>Unit audit ini akan menjadi pilihan saat membuat SK Auditor.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanSkAdmin">
                                <i class="ti ti-file-certificate me-2 text-primary"></i> SK Auditor
                            </button>
                        </h2>
                        <div id="panduanSkAdmin" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka menu <strong>SK Auditor</strong>, klik <strong>Tambah SK</strong>.</li>
                                    <li>Isi nomor SK, pilih periode, dan set status.</li>
                                    <li>Setelah SK dibuat, masuk ke detail SK untuk <strong>assign Ketua Auditor, Anggota, dan Auditee</strong>.</li>
                                    <li>Pastikan setiap SK memiliki unit audit yang ditugaskan.</li>
                                    <li>Aktifkan SK agar auditee & auditor bisa mulai bekerja.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanTargetAmi">
                                <i class="ti ti-target me-2 text-dark"></i> Target AMI
                            </button>
                        </h2>
                        <div id="panduanTargetAmi" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka menu <strong>Target AMI</strong>, pilih SK yang ingin dikelola.</li>
                                    <li>Tambahkan standar dan indikator yang menjadi target audit.</li>
                                    <li>Target ini akan digunakan sebagai acuan saat evaluasi diri dan asesmen.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanRtm">
                                <i class="ti ti-gavel me-2 text-warning"></i> RTM (Rapat Tinjauan Manajemen)
                            </button>
                        </h2>
                        <div id="panduanRtm" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>RTM dilakukan setelah semua proses audit selesai.</li>
                                    <li>Buka menu <strong>RTM</strong>, buat notulen rapat tinjauan.</li>
                                    <li>Dokumentasikan hasil keputusan, rekomendasi, dan tindak lanjut.</li>
                                    <li>RTM menjadi penutup siklus AMI pada periode tersebut.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    @elseif($currentMode === 'auditee')
                    {{-- PANDUAN AUDITEE --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panduanEvaldi">
                                <i class="ti ti-clipboard-text me-2 text-info"></i> Evaluasi Diri
                            </button>
                        </h2>
                        <div id="panduanEvaldi" class="accordion-collapse collapse show" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka menu <strong>Evaluasi Diri</strong>, pilih SK yang ditugaskan kepada Anda.</li>
                                    <li>Isi data evaluasi diri sesuai standar dan indikator yang telah ditentukan.</li>
                                    <li>Upload dokumen pendukung / bukti yang diperlukan.</li>
                                    <li>Pastikan semua indikator sudah terisi sebelum auditor melakukan kunjungan.</li>
                                    <li><span class="badge bg-label-warning">Penting</span> Evaluasi diri hanya bisa diisi selama SK berstatus <strong>Aktif</strong>.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanTemuanAuditee">
                                <i class="ti ti-alert-triangle me-2 text-warning"></i> Review Temuan
                            </button>
                        </h2>
                        <div id="panduanTemuanAuditee" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Setelah auditor selesai, buka menu <strong>Temuan Audit</strong> atau <strong>Hasil Temuan</strong>.</li>
                                    <li>Periksa temuan yang diberikan oleh auditor.</li>
                                    <li>Temuan bersifat <strong>read-only</strong> untuk auditee — Anda tidak bisa mengedit.</li>
                                    <li>Gunakan temuan ini sebagai dasar untuk melakukan tindak lanjut perbaikan.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanLaporanAuditee">
                                <i class="ti ti-report me-2 text-success"></i> Laporan Kinerja
                            </button>
                        </h2>
                        <div id="panduanLaporanAuditee" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka menu <strong>Laporan Kinerja</strong>, pilih SK terkait.</li>
                                    <li>Isi laporan kinerja unit sebagai bentuk tindak lanjut dari temuan audit.</li>
                                    <li>Laporan ini akan di-review oleh auditor dan admin.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    @elseif($currentMode === 'auditor')
                    {{-- PANDUAN AUDITOR --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panduanEvaldiAuditor">
                                <i class="ti ti-clipboard-text me-2 text-info"></i> Review Evaluasi Diri
                            </button>
                        </h2>
                        <div id="panduanEvaldiAuditor" class="accordion-collapse collapse show" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka menu <strong>Evaluasi Diri</strong>, pilih SK yang ditugaskan.</li>
                                    <li>Periksa data evaluasi diri yang telah diisi oleh auditee.</li>
                                    <li>Evaluasi diri bersifat <strong>read-only</strong> untuk auditor.</li>
                                    <li>Gunakan data ini sebagai referensi saat melakukan asesmen.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanAsesmen">
                                <i class="ti ti-checkbox me-2 text-success"></i> Asesmen Auditor
                            </button>
                        </h2>
                        <div id="panduanAsesmen" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka menu <strong>Asesmen Auditor</strong>, pilih SK yang ditugaskan.</li>
                                    <li>Isi penilaian asesmen untuk setiap standar & indikator.</li>
                                    <li>Berikan skor dan komentar sesuai bukti yang ditemukan.</li>
                                    <li>Asesmen hanya bisa diisi selama SK berstatus <strong>Aktif</strong>.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanTemuanAuditor">
                                <i class="ti ti-alert-triangle me-2 text-warning"></i> Temuan Audit
                            </button>
                        </h2>
                        <div id="panduanTemuanAuditor" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Setelah asesmen, buka menu <strong>Temuan Audit</strong> atau <strong>Hasil Temuan</strong>.</li>
                                    <li>Dokumentasikan temuan ketidaksesuaian (KTS) yang ditemukan.</li>
                                    <li>Isi deskripsi temuan, bukti, dan rekomendasi perbaikan.</li>
                                    <li>Temuan ini akan dilihat oleh auditee untuk ditindaklanjuti.</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panduanLaporanAuditor">
                                <i class="ti ti-report me-2 text-danger"></i> Laporan Kinerja
                            </button>
                        </h2>
                        <div id="panduanLaporanAuditor" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                            <div class="accordion-body">
                                <ol class="mb-0">
                                    <li>Buka menu <strong>Laporan Kinerja</strong>, pilih SK terkait.</li>
                                    <li>Buat laporan kinerja audit berdasarkan temuan yang diperoleh.</li>
                                    <li>Laporan ini merangkum hasil audit secara keseluruhan.</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MENU AMI --}}
{{-- ============================================================ --}}
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="ti ti-apps ti-22px me-2 text-success"></i>
                <h5 class="card-title mb-0">Menu AMI</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @if($isAdmin)
                    <a href="{{ route('admin.ami.unit-audit.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-building me-1"></i> Unit Audit
                    </a>
                    <a href="{{ route('admin.ami.periode.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-calendar me-1"></i> Periode
                    </a>
                    <a href="{{ route('admin.ami.target-ami.index') }}" class="btn btn-outline-dark">
                        <i class="ti ti-target me-1"></i> Target AMI
                    </a>
                    @endif
                    <a href="{{ route('admin.ami.sk-auditor.index') }}" class="btn btn-outline-primary">
                        <i class="ti ti-file-certificate me-1"></i> SK Auditor
                    </a>
                    <a href="{{ route('admin.ami.evaluasi-diri.index') }}" class="btn btn-outline-info">
                        <i class="ti ti-clipboard-text me-1"></i> Evaluasi Diri
                    </a>
                    <a href="{{ route('admin.ami.asesmen.index') }}" class="btn btn-outline-success">
                        <i class="ti ti-checkbox me-1"></i> Asesmen Auditor
                    </a>
                    @if($isAdmin)
                    <a href="{{ route('admin.ami.rtm.index') }}" class="btn btn-outline-warning">
                        <i class="ti ti-gavel me-1"></i> RTM
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .ami-timeline {
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 0;
        padding: 10px 0;
    }
    .ami-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        min-width: 100px;
        max-width: 130px;
        flex: 0 0 auto;
    }
    .ami-step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
    }
    .ami-step-content {
        font-size: .85rem;
        line-height: 1.3;
    }
    .ami-step-content strong {
        display: block;
        margin-bottom: 2px;
    }
    .ami-step-connector {
        flex: 0 0 auto;
        width: 30px;
        height: 2px;
        border-top: 2px dashed #c0c0c0;
        align-self: center;
        margin-top: -28px;
    }

    @media (max-width: 768px) {
        .ami-timeline {
            flex-direction: column;
            align-items: flex-start;
        }
        .ami-step {
            flex-direction: row;
            text-align: left;
            max-width: 100%;
            min-width: auto;
            gap: 12px;
        }
        .ami-step-number {
            margin-bottom: 0;
            flex-shrink: 0;
        }
        .ami-step-connector {
            width: 2px;
            height: 20px;
            border-top: none;
            border-left: 2px dashed #c0c0c0;
            margin: 0 0 0 19px;
            align-self: auto;
        }
    }
</style>
@endpush
