@extends('layouts.admin.template')
@section('title', isset($target) ? 'Edit Target AMI' : 'Buat Target AMI')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.target-ami.index') }}">Target AMI</a> /</span>
            {{ isset($target) ? 'Edit' : 'Buat Baru' }}
        </h4>
    </div>
</div>

<form action="{{ isset($target) ? route('admin.ami.target-ami.update', $target->id) : route('admin.ami.target-ami.store') }}" method="POST">
    @csrf
    @if(isset($target))
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data Target AMI</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label" for="ami_sk_auditor_id">SK Auditor <span class="text-danger">*</span></label>
                            <select class="form-select select2" id="ami_sk_auditor_id" name="ami_sk_auditor_id" required>
                                <option value="">Pilih SK Auditor</option>
                                @foreach($skAuditors as $sk)
                                <option value="{{ $sk->id }}" {{ old('ami_sk_auditor_id', $target->ami_sk_auditor_id ?? $selectedSkId ?? '') == $sk->id ? 'selected' : '' }}>
                                    {{ $sk->nomor_sk }} ({{ $sk->tanggal_sk->format('d/m/Y') }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="kode_target">Kode Target <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode_target" name="kode_target"
                                value="{{ old('kode_target', $target->kode_target ?? '') }}" required
                                placeholder="contoh: AMI-2026-01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tahun">Tahun / Siklus <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="tahun" name="tahun"
                                value="{{ old('tahun', $target->tahun ?? date('Y')) }}" required
                                min="2020" max="2099" placeholder="2026">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tanggal_mulai">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', isset($target) ? $target->tanggal_mulai->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tanggal_selesai">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                                value="{{ old('tanggal_selesai', isset($target) ? $target->tanggal_selesai->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="standar_acuan">Standar / Acuan Audit</label>
                            <textarea class="form-control" id="standar_acuan" name="standar_acuan" rows="3"
                                placeholder="Standar/acuan yang digunakan dalam audit...">{{ old('standar_acuan', $target->standar_acuan ?? '') }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="ruang_lingkup">Ruang Lingkup</label>
                            <textarea class="form-control" id="ruang_lingkup" name="ruang_lingkup" rows="3"
                                placeholder="Ruang lingkup audit (akademik, non-akademik, dll)...">{{ old('ruang_lingkup', $target->ruang_lingkup ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="ti ti-device-floppy me-1"></i>
                        {{ isset($target) ? 'Update Target AMI' : 'Simpan Target AMI' }}
                    </button>
                    <a href="{{ route('admin.ami.target-ami.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            @if(isset($target))
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="ti ti-info-circle text-muted me-2"></i>
                        <small class="text-muted">Status: <span class="badge bg-secondary">{{ ucfirst($target->status) }}</span></small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ti ti-user text-muted me-2"></i>
                        <small class="text-muted">Dibuat: {{ $target->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>
@endsection
