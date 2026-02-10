@extends('layouts.admin.template')
@section('title', 'Evaluasi Diri')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI /</span> Evaluasi Diri
        </h4>
        <p class="text-muted">Pilih SK Auditor untuk mengisi atau melihat evaluasi diri.</p>
    </div>
</div>

<div class="row">
    @forelse($skList as $sk)
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">{{ $sk->unit ? $sk->unit->nama : '-' }}</h6>
                        <small class="text-muted">{{ $sk->nomor_sk }}</small>
                    </div>
                    @php
                        $badges = ['draft' => 'bg-secondary', 'aktif' => 'bg-success', 'terkunci' => 'bg-warning', 'selesai' => 'bg-primary'];
                        $badgeClass = isset($badges[$sk->status]) ? $badges[$sk->status] : 'bg-secondary';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($sk->status) }}</span>
                </div>
                <p class="mb-2">
                    <small class="text-muted">Periode:</small>
                    {{ $sk->periode ? $sk->periode->nama : '-' }}
                </p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        {{ $sk->evaluasiDiris->count() }} indikator terjawab
                    </small>
                    <a href="{{ route('admin.ami.evaluasi-diri.show', $sk->id) }}" class="btn btn-sm btn-primary">
                        <i class="ti ti-arrow-right me-1"></i>Buka
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="ti ti-clipboard-off ti-lg text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                <h6>Belum ada SK Auditor yang terkait dengan Anda</h6>
                <p class="text-muted">Hubungi admin untuk menambahkan Anda ke dalam SK Auditor.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection
