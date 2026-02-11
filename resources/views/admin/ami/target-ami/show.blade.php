@extends('layouts.admin.template')
@section('title', 'Detail Target AMI')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.target-ami.index') }}">Target AMI</a> /</span>
            Detail
        </h4>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="ti ti-check me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="ti ti-alert-circle me-1"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    {{-- Main Content --}}
    <div class="col-md-8">
        {{-- Info Target AMI --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-target me-1"></i> {{ $target->kode_target }}
                </h5>
                @php
                    $statusBadges = [
                        'draft' => 'bg-secondary',
                        'aktif' => 'bg-success',
                        'ditutup' => 'bg-primary',
                    ];
                    $badge = $statusBadges[$target->status] ?? 'bg-secondary';
                @endphp
                <span class="badge {{ $badge }} fs-6">{{ strtoupper($target->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Tahun / Siklus</label>
                        <strong>{{ $target->tahun }}</strong>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Tanggal Mulai</label>
                        <strong>{{ $target->tanggal_mulai->format('d M Y') }}</strong>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small d-block">Tanggal Selesai</label>
                        <strong>{{ $target->tanggal_selesai->format('d M Y') }}</strong>
                    </div>
                    @if($target->standar_acuan)
                    <div class="col-md-12">
                        <label class="text-muted small d-block">Standar / Acuan Audit</label>
                        <p class="mb-0">{{ $target->standar_acuan }}</p>
                    </div>
                    @endif
                    @if($target->ruang_lingkup)
                    <div class="col-md-12">
                        <label class="text-muted small d-block">Ruang Lingkup</label>
                        <p class="mb-0">{{ $target->ruang_lingkup }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Parent SK Auditor --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-file-certificate me-1"></i> SK Auditor Induk
                </h5>
            </div>
            <div class="card-body">
                @if($target->skAuditor)
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nomor SK</small>
                        <a href="{{ route('admin.ami.sk-auditor.show', $target->skAuditor->id) }}" class="fw-bold">
                            {{ $target->skAuditor->nomor_sk }}
                        </a>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Tanggal SK</small>
                        <strong>{{ $target->skAuditor->tanggal_sk->format('d M Y') }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Unit Audit</small>
                        <strong>{{ $target->skAuditor->unit ? $target->skAuditor->unit->nama : '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Periode</small>
                        <strong>{{ $target->skAuditor->periode ? $target->skAuditor->periode->nama : '-' }}</strong>
                    </div>
                </div>
                @else
                <div class="alert alert-warning mb-0">
                    <i class="ti ti-alert-triangle me-1"></i> Data SK Auditor tidak ditemukan (mungkin terhapus).
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-md-4">
        {{-- Actions --}}
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Aksi</h5></div>
            <div class="card-body">
                @if($isAdmin)
                    @if($target->status === 'draft')
                    <a href="{{ route('admin.ami.target-ami.edit', $target->id) }}" class="btn btn-warning w-100 mb-2">
                        <i class="ti ti-pencil me-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.ami.target-ami.change-status', $target->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 mb-2"
                            onclick="return confirm('Aktifkan Target AMI ini? Target AMI yang aktif akan digunakan untuk pelaksanaan AMI.')">
                            <i class="ti ti-player-play me-1"></i> Aktifkan
                        </button>
                    </form>
                    @elseif($target->status === 'aktif')
                    <form action="{{ route('admin.ami.target-ami.change-status', $target->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 mb-2"
                            onclick="return confirm('Tutup Target AMI ini?')">
                            <i class="ti ti-lock me-1"></i> Tutup Target AMI
                        </button>
                    </form>
                    @else
                    <div class="alert alert-info mb-2">
                        <i class="ti ti-lock me-1"></i> Target AMI sudah ditutup dan bersifat read-only.
                    </div>
                    @endif
                @else
                <div class="alert alert-info mb-2">
                    <i class="ti ti-eye me-1"></i> Mode baca — Anda hanya dapat melihat data Target AMI.
                </div>
                @endif

                <a href="{{ route('admin.ami.target-ami.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        {{-- Meta Info --}}
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <i class="ti ti-user text-muted me-2"></i>
                    <small class="text-muted">Dibuat oleh: {{ $target->createdBy ? $target->createdBy->name : '-' }}</small>
                </div>
                <div class="d-flex align-items-center">
                    <i class="ti ti-clock text-muted me-2"></i>
                    <small class="text-muted">{{ $target->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
