@extends('layouts.admin.template')
@section('title', 'Detail SK Auditor')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.sk-auditor.index') }}">SK Auditor</a> /</span> Detail
        </h4>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $sk->nomor_sk }}</h5>
                @php
                $badges = ['draft' => 'bg-secondary', 'aktif' => 'bg-success', 'terkunci' => 'bg-warning', 'selesai' => 'bg-primary'];
                $badgeClass = isset($badges[$sk->status]) ? $badges[$sk->status] : 'bg-secondary';
                @endphp
                <span class="badge {{ $badgeClass }}">{{ ucfirst($sk->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Periode</small>
                        <strong>{{ $sk->periode ? $sk->periode->nama : '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Unit Audit</small>
                        <strong>{{ $sk->unit ? $sk->unit->nama : '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Tanggal SK</small>
                        <strong>{{ $sk->tanggal_sk }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Ketua Auditor</small>
                        <strong>{{ $sk->ketuaAuditor ? $sk->ketuaAuditor->name : '-' }}</strong>
                    </div>
                    @if($sk->catatan)
                    <div class="col-12">
                        <small class="text-muted d-block">Catatan</small>
                        <p class="mb-0">{{ $sk->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.ami.target-ami.index', ['ami_sk_auditor_id' => $sk->id]) }}" class="btn btn-outline-primary">
                        <i class="ti ti-target me-1"></i>Target AMI
                    </a>
                    @if($isAdmin)
                    <a href="{{ route('admin.ami.indikator.index', $sk->id) }}" class="btn btn-outline-info">
                        <i class="ti ti-list-check me-1"></i>Indikator
                    </a>
                    @endif
                    @if($isAdmin || $isAuditee)
                    <a href="{{ route('admin.ami.evaluasi-diri.show', $sk->id) }}" class="btn btn-outline-primary">
                        <i class="ti ti-clipboard-text me-1"></i>Evaluasi Diri
                    </a>
                    @endif
                    @if($isAuditor || $isAdmin || $isAuditee)
                    <a href="{{ route('admin.ami.asesmen.show', ['skId' => $sk->id, 'readonly' => 1]) }}" class="btn btn-outline-info">
                        <i class="ti ti-eye me-1"></i>Asesmen Diri (Read Only)
                    </a>
                    @endif
                    @if($isAdmin || $isAuditor)
                    <a href="{{ route('admin.ami.asesmen.show', $sk->id) }}" class="btn btn-outline-success">
                        <i class="ti ti-checkbox me-1"></i>Asesmen Auditor
                    </a>
                    @endif
                    @if($isAdmin || $isKetua || $isAuditee || $isAuditor)
                    <a href="{{ route('admin.ami.temuan.index', $sk->id) }}" class="btn btn-outline-warning">
                        <i class="ti ti-alert-triangle me-1"></i>Temuan Audit
                    </a>
                    @endif
                    @if($isAdmin || $isAuditee || $isAuditor)
                    <a href="{{ route('admin.ami.hasil-temuan.index', $sk->id) }}" class="btn btn-outline-danger">
                        <i class="ti ti-report-analytics me-1"></i>Hasil Temuan
                    </a>
                    @endif
                    <a href="{{ route('admin.ami.laporan-kinerja.index', $sk->id) }}" class="btn btn-outline-info">
                        <i class="ti ti-report me-1"></i>Laporan Kinerja
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Tim Audit -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Tim Audit</h5>
            </div>
            <div class="card-body">
                <h6 class="text-muted mb-2">Ketua Auditor</h6>
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            {{ strtoupper(substr($sk->ketuaAuditor->name ?? '?', 0, 1)) }}
                        </span>
                    </div>
                    <span>{{ $sk->ketuaAuditor ? $sk->ketuaAuditor->name : '-' }}</span>
                </div>

                @php
                $auditors = $sk->anggotas->where('peran', 'auditor_anggota');
                $auditees = $sk->anggotas->where('peran', 'auditee');
                @endphp

                @if($auditors->count() > 0)
                <h6 class="text-muted mb-2">Auditor Anggota</h6>
                @foreach($auditors as $a)
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-success">
                            {{ strtoupper(substr($a->user->name ?? '?', 0, 1)) }}
                        </span>
                    </div>
                    <span>{{ $a->user ? $a->user->name : '-' }}</span>
                </div>
                @endforeach
                @endif

                @if($auditees->count() > 0)
                <h6 class="text-muted mt-3 mb-2">Auditee</h6>
                @foreach($auditees as $a)
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar avatar-sm me-2">
                        <span class="avatar-initial rounded-circle bg-label-warning">
                            {{ strtoupper(substr($a->user->name ?? '?', 0, 1)) }}
                        </span>
                    </div>
                    <span>{{ $a->user ? $a->user->name : '-' }}</span>
                </div>
                @endforeach
                @endif
            </div>
        </div>



        @if($isAdmin)
        <a href="{{ route('admin.ami.sk-auditor.edit', $sk->id) }}" class="btn btn-warning w-100 mb-2">
            <i class="ti ti-pencil me-1"></i>Edit SK
        </a>
        @endif
    </div>
</div>
@endsection
