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

<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Menu AMI</h5></div>
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
