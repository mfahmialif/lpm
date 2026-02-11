@extends('layouts.admin.template')
@section('title', 'Target AMI')
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">AMI /</span> Target AMI
            </h4>
            <p class="text-muted mb-0">Kelola Target Audit Mutu Internal</p>
        </div>
        <div>
            @if(isset($sk))
            <a href="{{ route('admin.ami.sk-auditor.show', $sk->id) }}" class="btn btn-secondary me-2">
                <i class="ti ti-arrow-left me-1"></i> Kembali ke SK
            </a>
            @endif
            @if($isAdmin)
            <a href="{{ route('admin.ami.target-ami.create', isset($sk) ? ['ami_sk_auditor_id' => $sk->id] : []) }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Tambah Target
            </a>
            @endif
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header pb-0">
        <h5 class="card-title mb-0">Filter Data</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.ami.target-ami.index') }}" method="GET">
            @if(isset($sk))
            <input type="hidden" name="ami_sk_auditor_id" value="{{ $sk->id }}">
            @endif
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
                        <option value="nomor_asc" {{ request('sort') == 'nomor_asc' ? 'selected' : '' }}>Kode Target (A-Z)</option>
                        <option value="nomor_desc" {{ request('sort') == 'nomor_desc' ? 'selected' : '' }}>Kode Target (Z-A)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.ami.target-ami.index', isset($sk) ? ['ami_sk_auditor_id' => $sk->id] : []) }}" class="btn btn-label-secondary w-100">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse($targets as $target)
    <div class="col-md-6 col-xl-4 mb-4">
        <div class="card h-100 hover-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">{{ $target->kode_target }}</h6>
                        <small class="text-muted">
                            @if($target->skAuditor)
                                SK: {{ $target->skAuditor->nomor_sk }}
                            @else
                                <span class="text-warning">Belum ada SK</span>
                            @endif
                        </small>
                    </div>
                    <div class="d-flex align-items-center">
                        @php
                            $badges = ['draft' => 'bg-secondary', 'aktif' => 'bg-success', 'ditutup' => 'bg-primary'];
                            $badgeClass = isset($badges[$target->status]) ? $badges[$target->status] : 'bg-secondary';
                        @endphp
                        <span class="badge {{ $badgeClass }} me-2">{{ ucfirst($target->status) }}</span>
                        
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt{{ $target->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt{{ $target->id }}">
                                <a class="dropdown-item" href="{{ route('admin.ami.target-ami.show', $target->id) }}">Detail</a>
                                @if($isAdmin)
                                @if($target->status === 'draft')
                                <a class="dropdown-item" href="{{ route('admin.ami.target-ami.edit', $target->id) }}">Edit</a>
                                @endif
                                @if($target->canBeDeleted())
                                <a class="dropdown-item text-danger delete-record" href="javascript:void(0);" data-id="{{ $target->id }}" data-name="{{ $target->kode_target }}">Hapus</a>
                                @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <p class="mb-2">
                    <small class="text-muted">Periode:</small><br>
                    @if($target->tanggal_mulai && $target->tanggal_selesai)
                        {{ $target->tanggal_mulai->format('d M Y') }} - {{ $target->tanggal_selesai->format('d M Y') }}
                    @else
                        -
                    @endif
                </p>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Tahun: {{ $target->tahun }}
                    </small>
                    <a href="{{ route('admin.ami.target-ami.show', $target->id) }}" class="btn btn-sm btn-primary">
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
                <i class="ti ti-target ti-lg text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                <h6>Belum ada Target AMI</h6>
                @if(isset($sk))
                <p class="mb-0">untuk SK Auditor ini.</p>
                @endif
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $targets->links() }}
</div>

@if($isAdmin)
<form id="delete-form" action="{{ route('admin.ami.target-ami.delete') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id" id="delete-id">
    <input type="hidden" name="nama" id="delete-nama">
</form>
@endif
@endsection

@push('scripts')
@if($isAdmin)
<script>
    $(document).on('click', '.delete-record', function() {
        var id = $(this).data('id');
        var nama = $(this).data('name');
        
        Swal.fire({
            title: 'Hapus "' + nama + '"?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect waves-light'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $('#delete-id').val(id);
                $('#delete-nama').val(nama);
                
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.ami.target-ami.delete') }}",
                    data: $('#delete-form').serialize(),
                    success: function(response) {
                        showToastr(response.type, response.type, response.message);
                        if(response.status) {
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        showToastr('error', 'Error', 'Terjadi kesalahan saat menghapus data');
                    }
                });
            }
        });
    });
</script>
@endif
@endpush
