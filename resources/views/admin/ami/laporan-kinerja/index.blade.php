@extends('layouts.admin.template')
@section('title', 'Laporan Kinerja')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.sk-auditor.show', $sk->id) }}">{{ $sk->nomor_sk }}</a> /</span>
            Laporan Kinerja
        </h4>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Laporan Kinerja Unit — {{ $sk->unit ? $sk->unit->nama : '-' }}</h5>
    </div>
    <div class="card-body">
        @if(!$canEdit)
        <div class="alert alert-info mb-3">
            <i class="ti ti-eye me-1"></i> Mode baca — Anda hanya dapat melihat data Laporan Kinerja.
        </div>
        @endif
        <form action="{{ route('admin.ami.laporan-kinerja.store', $sk->id) }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Ringkasan Kinerja @if($canEdit)<span class="text-danger">*</span>@endif</label>
                    <textarea class="form-control" name="ringkasan" rows="5" placeholder="Tuliskan ringkasan kinerja unit..." {{ $canEdit ? 'required' : 'readonly' }}>{{ $laporan ? $laporan->ringkasan : '' }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Rencana Tindak Lanjut</label>
                    <textarea class="form-control" name="rencana_tindak_lanjut" rows="4" placeholder="Rencana tindak lanjut..." {{ !$canEdit ? 'readonly' : '' }}>{{ $laporan ? $laporan->rencana_tindak_lanjut : '' }}</textarea>
                </div>
                @if($canEdit)
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-1"></i>{{ $laporan ? 'Update' : 'Simpan' }} Laporan
                    </button>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
