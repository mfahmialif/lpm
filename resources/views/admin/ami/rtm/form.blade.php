@extends('layouts.admin.template')
@section('title', isset($rtm) ? 'Edit RTM' : 'Buat RTM Baru')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.rtm.index') }}">RTM</a> /</span>
            {{ isset($rtm) ? 'Edit' : 'Buat Baru' }}
        </h4>
    </div>
</div>

<form action="{{ isset($rtm) ? route('admin.ami.rtm.update', $rtm->id) : route('admin.ami.rtm.store') }}" method="POST">
    @csrf
    @if(isset($rtm))
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Data RTM</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="kode_rtm">Kode RTM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode_rtm" name="kode_rtm"
                                value="{{ old('kode_rtm', $rtm->kode_rtm ?? '') }}" required
                                placeholder="contoh: RTM-2026-001">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tanggal_rtm">Tanggal RTM <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_rtm" name="tanggal_rtm"
                                value="{{ old('tanggal_rtm', isset($rtm) ? $rtm->tanggal_rtm->format('Y-m-d') : '') }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="pimpinan_id">Pimpinan RTM</label>
                            <select class="select2 form-select" id="pimpinan_id" name="pimpinan_id">
                                <option value="">-- Pilih Pimpinan --</option>
                                @foreach($users as $u)
                                <option value="{{ $u->id }}"
                                    {{ old('pimpinan_id', $rtm->pimpinan_id ?? '') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="catatan_umum">Catatan Umum</label>
                            <textarea class="form-control" id="catatan_umum" name="catatan_umum" rows="3"
                                placeholder="Catatan umum RTM...">{{ old('catatan_umum', $rtm->catatan_umum ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">SK Auditor <span class="text-danger">*</span></h5>
                    <small class="text-muted">Pilih SK yang akan dibahas dalam RTM ini</small>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @foreach($skList as $sk)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="sk_ids[]"
                            value="{{ $sk->id }}" id="sk-{{ $sk->id }}"
                            {{ in_array($sk->id, old('sk_ids', $selectedSkIds ?? [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="sk-{{ $sk->id }}">
                            <strong>{{ $sk->nomor_sk }}</strong><br>
                            <small class="text-muted">
                                {{ $sk->unit ? $sk->unit->nama : '-' }}
                                · {{ $sk->periode ? $sk->periode->nama : '-' }}
                            </small>
                        </label>
                    </div>
                    @endforeach
                    @if($skList->isEmpty())
                    <p class="text-muted mb-0">Belum ada SK Auditor yang tersedia.</p>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-2">
                <i class="ti ti-device-floppy me-1"></i> {{ isset($rtm) ? 'Update RTM' : 'Simpan RTM' }}
            </button>
            <a href="{{ route('admin.ami.rtm.index') }}" class="btn btn-outline-secondary w-100">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</form>
@endsection
