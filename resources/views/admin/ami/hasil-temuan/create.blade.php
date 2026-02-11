@extends('layouts.admin.template')
@section('title', 'Tambah Hasil Temuan')
@push('css')
<style>
    .temuan-item {
        overflow: hidden;
    }
    .temuan-item .temuan-desc {
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }
    #modal-deskripsi, #modal-rekomendasi {
        word-break: break-word;
        overflow-wrap: break-word;
        white-space: pre-wrap;
    }
</style>
@endpush
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.sk-auditor.show', $sk->id) }}">{{ $sk->nomor_sk }}</a> / <a href="{{ route('admin.ami.hasil-temuan.index', $sk->id) }}">Hasil Temuan</a> /</span>
            Tambah
        </h4>
    </div>
</div>

<form action="{{ route('admin.ami.hasil-temuan.store', $sk->id) }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Data Hasil Temuan</h5></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Judul Hasil Temuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul" value="{{ old('judul') }}" placeholder="Judul hasil temuan..." required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Kategori Dominan <span class="text-danger">*</span></label>
                            <select class="form-select select2" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="kesesuaian" {{ old('kategori') == 'kesesuaian' ? 'selected' : '' }}>Kesesuaian</option>
                                <option value="observasi" {{ old('kategori') == 'observasi' ? 'selected' : '' }}>Observasi</option>
                                <option value="ketidaksesuaian_minor" {{ old('kategori') == 'ketidaksesuaian_minor' ? 'selected' : '' }}>Ketidaksesuaian Minor</option>
                                <option value="ketidaksesuaian_mayor" {{ old('kategori') == 'ketidaksesuaian_mayor' ? 'selected' : '' }}>Ketidaksesuaian Mayor</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ringkasan Manajerial <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="ringkasan" rows="6" placeholder="Ringkasan isu mutu..." required>{{ old('ringkasan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Temuan Audit Terkait</h5>
                    <span class="badge bg-label-primary">{{ $temuanAudits->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div style="max-height: 400px; overflow-y: auto;">
                        @forelse($temuanAudits as $temuan)
                        @php
                            $badges = [
                                'kesesuaian' => 'bg-success',
                                'observasi' => 'bg-info',
                                'ketidaksesuaian_minor' => 'bg-warning',
                                'ketidaksesuaian_mayor' => 'bg-danger',
                            ];
                            $badgeClass = $badges[$temuan->jenis_temuan] ?? 'bg-secondary';
                            $checked = is_array(old('temuan_audit_ids')) && in_array($temuan->id, old('temuan_audit_ids'));
                        @endphp
                        <div class="temuan-item p-3 border-bottom">
                            <div class="d-flex align-items-center mb-2">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="temuan_audit_ids[]" value="{{ $temuan->id }}" id="temuan_{{ $temuan->id }}" {{ $checked ? 'checked' : '' }}>
                                    <label class="form-check-label" for="temuan_{{ $temuan->id }}">
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $temuan->jenis_temuan)) }}</span>
                                    </label>
                                </div>
                            </div>
                            <small class="temuan-desc text-muted d-block mb-2 ps-4">{{ Str::limit($temuan->deskripsi, 100) }}</small>
                            <div class="ps-4">
                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-2 btn-detail-temuan"
                                    data-bs-toggle="modal" data-bs-target="#modalDetailTemuan"
                                    data-jenis="{{ $temuan->jenis_temuan }}"
                                    data-deskripsi="{{ e($temuan->deskripsi) }}"
                                    data-rekomendasi="{{ e($temuan->rekomendasi) }}"
                                    data-created="{{ $temuan->created_at->format('d M Y H:i') }}">
                                    <i class="ti ti-eye ti-xs me-1"></i><small>Detail</small>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="ti ti-alert-circle ti-lg d-block mb-2"></i>
                            Belum ada Temuan Audit untuk SK ini.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-check me-1"></i>Simpan Hasil Temuan
            </button>
        </div>
    </div>
</form>

<!-- Modal Detail Temuan Audit -->
<div class="modal fade" id="modalDetailTemuan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Temuan Audit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Jenis Temuan</small>
                    <span id="modal-jenis-badge"></span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Deskripsi</small>
                    <div class="p-3 bg-light rounded">
                        <p id="modal-deskripsi" class="mb-0"></p>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Rekomendasi</small>
                    <div class="p-3 bg-light rounded">
                        <p id="modal-rekomendasi" class="mb-0"></p>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block mb-1">Tanggal Dibuat</small>
                    <strong id="modal-created"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('click', '.btn-detail-temuan', function() {
        var $btn = $(this);
        var jenis = $btn.data('jenis');
        var badges = {
            'kesesuaian': 'bg-success'
            , 'observasi': 'bg-info'
            , 'ketidaksesuaian_minor': 'bg-warning'
            , 'ketidaksesuaian_mayor': 'bg-danger'
        };
        var badgeClass = badges[jenis] || 'bg-secondary';
        $('#modal-jenis-badge').html('<span class="badge ' + badgeClass + '">' + jenis.replace(/_/g, ' ') + '</span>');
        $('#modal-deskripsi').text($btn.data('deskripsi'));
        $('#modal-rekomendasi').text($btn.data('rekomendasi') || '-');
        $('#modal-created').text($btn.data('created'));
    });
</script>
@endpush
