@extends('layouts.admin.template')
@section('title', 'Asesmen Auditor')
@section('content')
<style>
    .hover-card {
        transition: all 0.3s ease-in-out;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        border-color: #7367f0 !important;
    }
</style>

<div class="row">
    <h4 class="fw-bold py-3 mb-0">
        <span class="text-muted fw-light">AMI /</span> Asesmen Auditor
    </h4>
    <p class="text-muted">Pilih SK Auditor untuk melakukan asesmen.</p>
</div>

<div class="card mb-4">
    <div class="card-header pb-0">
        <h5 class="card-title mb-0">Filter Data</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.ami.asesmen.index') }}" method="GET">
            <div class="row align-items-end g-3">
                <div class="col-md-3">
                    <label class="form-label">Periode</label>
                    <select class="form-select select2" name="periode_id">
                        <option value="">Semua Periode</option>
                        @foreach($periodes as $p)
                        <option value="{{ $p->id }}" {{ request('periode_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Unit Audit</label>
                    <select class="form-select select2" name="unit_id">
                        <option value="">Semua Unit</option>
                        @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ request('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Urutkan</label>
                    <select class="form-select select2" name="sort">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                        <option value="nomor_asc" {{ request('sort') == 'nomor_asc' ? 'selected' : '' }}>Nomor SK (A-Z)</option>
                        <option value="nomor_desc" {{ request('sort') == 'nomor_desc' ? 'selected' : '' }}>Nomor SK (Z-A)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.ami.asesmen.index') }}" class="btn btn-label-secondary w-100">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($skList as $sk)
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card h-100 hover-card">
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
                        {{ $sk->asesmens->count() }} indikator dinilai
                    </small>
                    <a href="{{ route('admin.ami.asesmen.show', $sk->id) }}" class="btn btn-sm btn-primary">
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
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $skList->links() }}
</div>
@endsection
