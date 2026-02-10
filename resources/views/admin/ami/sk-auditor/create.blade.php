@extends('layouts.admin.template')
@section('title', 'Tambah SK Auditor')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.sk-auditor.index') }}">SK Auditor</a> /</span> Tambah
        </h4>
    </div>
</div>

<form action="{{ route('admin.ami.sk-auditor.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Data SK Auditor</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Periode AMI <span class="text-danger">*</span></label>
                            <select class="select2 form-select" name="ami_periode_id" required>
                                <option value="">-- Pilih Periode --</option>
                                @foreach($periodes as $p)
                                <option value="{{ $p->id }}" {{ old('ami_periode_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Audit <span class="text-danger">*</span></label>
                            <select class="select2 form-select" name="unit_id" required>
                                <option value="">-- Pilih Unit --</option>
                                @foreach($units as $u)
                                <option value="{{ $u->id }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor SK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nomor_sk" value="{{ old('nomor_sk') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal SK <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_sk" value="{{ old('tanggal_sk') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="select2 form-select" name="status" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="terkunci" {{ old('status') == 'terkunci' ? 'selected' : '' }}>Terkunci</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Tim Audit</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Ketua Auditor <span class="text-danger">*</span></label>
                        <select class="select2 form-select" name="auditor_ketua_id" required>
                            <option value="">-- Pilih Ketua --</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('auditor_ketua_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Auditor Anggota</label>
                        <select class="select2 form-select" name="auditor_anggota[]" multiple>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="mb-3">
                        <label class="form-label">Auditee</label>
                        <select class="select2 form-select" name="auditee[]" multiple>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-check me-1"></i>Simpan SK Auditor
            </button>
        </div>
    </div>
</form>
@endsection
