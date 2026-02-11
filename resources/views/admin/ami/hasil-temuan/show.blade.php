@extends('layouts.admin.template')
@section('title', 'Detail Hasil Temuan')
@push('css')
<style>
    /* Desktop (>= md) */
    @media (min-width: 768px) {
        .text-wrap {
            white-space: normal !important;
            word-break: break-word;
            vertical-align: top;
        }
    }
</style>
@endpush
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.sk-auditor.show', $sk->id) }}">{{ $sk->nomor_sk }}</a> / <a href="{{ route('admin.ami.hasil-temuan.index', $sk->id) }}">Hasil Temuan</a> /</span>
            Detail
        </h4>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $hasil->judul }}</h5>
                @php
                $badges = [
                'kesesuaian' => 'bg-success',
                'observasi' => 'bg-info',
                'ketidaksesuaian_minor' => 'bg-warning',
                'ketidaksesuaian_mayor' => 'bg-danger',
                ];
                $badgeClass = isset($badges[$hasil->kategori]) ? $badges[$hasil->kategori] : 'bg-secondary';
                @endphp
                <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $hasil->kategori)) }}</span>
            </div>
            <div class="card-body">
                <h6 class="text-muted mb-2">Ringkasan Manajerial</h6>
                <div class="p-3 bg-light rounded mb-3">
                    {!! nl2br(e($hasil->ringkasan)) !!}
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Dibuat Oleh</small>
                        <strong>{{ $hasil->createdBy ? $hasil->createdBy->name : '-' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Tanggal Dibuat</small>
                        <strong>{{ $hasil->created_at->format('d M Y H:i') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Temuan Audit Terkait -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Temuan Audit Terkait
                    <span class="badge bg-label-primary ms-2">{{ $hasil->temuanAudits->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Temuan</th>
                                <th>Deskripsi</th>
                                <th>Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hasil->temuanAudits as $i => $temuan)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @php
                                    $tBadge = isset($badges[$temuan->jenis_temuan]) ? $badges[$temuan->jenis_temuan] : 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $tBadge }}">{{ ucfirst(str_replace('_', ' ', $temuan->jenis_temuan)) }}</span>
                                </td>
                                <td class="text-wrap">{{ $temuan->deskripsi }}</td>
                                <td class="text-wrap">{{ $temuan->rekomendasi ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Tidak ada temuan audit terkait</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi SK</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Nomor SK</small>
                    <strong>{{ $sk->nomor_sk }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Periode</small>
                    <strong>{{ $sk->periode ? $sk->periode->nama : '-' }}</strong>
                </div>
                <div class="mb-0">
                    <small class="text-muted d-block">Unit Audit</small>
                    <strong>{{ $sk->unit ? $sk->unit->nama : '-' }}</strong>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.ami.hasil-temuan.index', $sk->id) }}" class="btn btn-outline-secondary w-100">
            <i class="ti ti-arrow-left me-1"></i>Kembali ke Daftar
        </a>
    </div>
</div>
@endsection
