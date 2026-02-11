@extends('layouts.admin.template')
@section('title', 'Detail RTM - ' . $rtm->kode_rtm)
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.rtm.index') }}">RTM</a> /</span>
            {{ $rtm->kode_rtm }}
        </h4>
    </div>
</div>

<div class="row">
    {{-- Left: Main Content --}}
    <div class="col-md-8">
        {{-- RTM Info Card --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $rtm->kode_rtm }}</h5>
                @php
                    $badges = ['draft' => 'bg-secondary', 'sah' => 'bg-success', 'ditutup' => 'bg-primary'];
                    $badgeClass = isset($badges[$rtm->status]) ? $badges[$rtm->status] : 'bg-secondary';
                @endphp
                <span class="badge {{ $badgeClass }} fs-6">{{ strtoupper($rtm->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tanggal RTM</small>
                        <strong>{{ $rtm->tanggal_rtm->format('d F Y') }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Pimpinan</small>
                        <strong>{{ $rtm->pimpinan ? $rtm->pimpinan->name : '-' }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Dibuat Oleh</small>
                        <strong>{{ $rtm->createdBy ? $rtm->createdBy->name : '-' }}</strong>
                    </div>
                    @if($rtm->catatan_umum)
                    <div class="col-12">
                        <small class="text-muted d-block">Catatan Umum</small>
                        <p class="mb-0">{{ $rtm->catatan_umum }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- SK Auditor yang Dibahas --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-file-certificate me-1"></i> SK Auditor yang Dibahas
                    <span class="badge bg-label-primary ms-1">{{ $rtm->skAuditors->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nomor SK</th>
                                <th>Unit Audit</th>
                                <th>Periode</th>
                                <th>Jml Hasil Temuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rtm->skAuditors as $sk)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.ami.sk-auditor.show', $sk->id) }}">
                                        {{ $sk->nomor_sk }}
                                    </a>
                                </td>
                                <td>{{ $sk->unit ? $sk->unit->nama : '-' }}</td>
                                <td>{{ $sk->periode ? $sk->periode->nama : '-' }}</td>
                                <td>
                                    <span class="badge bg-label-warning">
                                        {{ $rtm->rtmTemuans->where('ami_sk_auditor_id', $sk->id)->count() }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Keputusan RTM per Hasil Temuan --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ti ti-gavel me-1"></i> Keputusan RTM
                    <span class="badge bg-label-warning ms-1">{{ $rtm->rtmTemuans->count() }} hasil temuan</span>
                </h5>
            </div>
            <div class="card-body">
                @if($rtm->rtmTemuans->isEmpty())
                <div class="alert alert-info mb-0">
                    <i class="ti ti-info-circle me-1"></i> Belum ada hasil temuan dari SK yang terkait.
                </div>
                @else
                    @php $grouped = $rtm->rtmTemuans->groupBy('ami_sk_auditor_id'); @endphp
                    @foreach($grouped as $skId => $temuans)
                        @php $skInfo = $temuans->first()->skAuditor; @endphp
                        <h6 class="text-primary mb-3 mt-{{ $loop->first ? '0' : '4' }}">
                            <i class="ti ti-building me-1"></i>
                            {{ $skInfo && $skInfo->unit ? $skInfo->unit->nama : 'Unit -' }}
                            <small class="text-muted">({{ $skInfo ? $skInfo->nomor_sk : '-' }})</small>
                        </h6>

                        @foreach($temuans as $rt)
                        <div class="card border shadow-none mb-3" id="temuan-card-{{ $rt->id }}">
                            <div class="card-body">
                                {{-- Hasil Temuan Info (read-only) --}}
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        @php
                                            $kategoriBadges = [
                                                'kesesuaian' => 'bg-success',
                                                'observasi' => 'bg-info',
                                                'ketidaksesuaian_minor' => 'bg-warning',
                                                'ketidaksesuaian_mayor' => 'bg-danger',
                                            ];
                                            $kb = $kategoriBadges[$rt->hasilTemuan->kategori ?? ''] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $kb }}">
                                            {{ ucfirst(str_replace('_', ' ', $rt->hasilTemuan->kategori ?? '-')) }}
                                        </span>
                                    </div>
                                    @php
                                        $tlBadges = ['open' => 'bg-label-danger', 'on_progress' => 'bg-label-warning', 'selesai' => 'bg-label-success'];
                                        $tlBadge = $tlBadges[$rt->status_tindak_lanjut] ?? 'bg-label-secondary';
                                    @endphp
                                    <span class="badge {{ $tlBadge }} status-tl-badge" data-id="{{ $rt->id }}">
                                        {{ strtoupper(str_replace('_', ' ', $rt->status_tindak_lanjut)) }}
                                    </span>
                                </div>

                                <h6 class="mb-1">{{ $rt->hasilTemuan->judul ?? '-' }}</h6>
                                <p class="mb-2 text-muted">{{ $rt->hasilTemuan->ringkasan ?? '-' }}</p>

                                <hr>

                                {{-- Keputusan RTM (editable if draft) --}}
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Keputusan Manajemen</label>
                                        @if($canEdit)
                                        <textarea class="form-control keputusan-input" rows="2"
                                            data-rtm-temuan-id="{{ $rt->id }}" data-field="keputusan"
                                            placeholder="Keputusan manajemen...">{{ $rt->keputusan }}</textarea>
                                        @else
                                        <p class="mb-0">{{ $rt->keputusan ?: '-' }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Rencana Tindak Lanjut</label>
                                        @if($canEdit)
                                        <textarea class="form-control keputusan-input" rows="2"
                                            data-rtm-temuan-id="{{ $rt->id }}" data-field="rencana_tindak_lanjut"
                                            placeholder="Rencana tindak lanjut...">{{ $rt->rencana_tindak_lanjut }}</textarea>
                                        @else
                                        <p class="mb-0">{{ $rt->rencana_tindak_lanjut ?: '-' }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Penanggung Jawab</label>
                                        @if($canEdit)
                                        <select class="select2 form-select keputusan-select" data-rtm-temuan-id="{{ $rt->id }}" data-field="penanggung_jawab_id">
                                            <option value="">-- Pilih PJ --</option>
                                            @foreach($users as $u)
                                            <option value="{{ $u->id }}" {{ $rt->penanggung_jawab_id == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @else
                                        <p class="mb-0">{{ $rt->penanggungJawab ? $rt->penanggungJawab->name : '-' }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Target Selesai</label>
                                        @if($canEdit)
                                        <input type="date" class="form-control keputusan-input"
                                            data-rtm-temuan-id="{{ $rt->id }}" data-field="target_selesai"
                                            value="{{ $rt->target_selesai ? $rt->target_selesai->format('Y-m-d') : '' }}">
                                        @else
                                        <p class="mb-0">{{ $rt->target_selesai ? $rt->target_selesai->format('d/m/Y') : '-' }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Status TL</label>
                                        @if($isAdmin && $rtm->status !== 'ditutup')
                                        <select class="select2 form-select status-tl-select" data-rtm-temuan-id="{{ $rt->id }}">
                                            <option value="open" {{ $rt->status_tindak_lanjut == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="on_progress" {{ $rt->status_tindak_lanjut == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                            <option value="selesai" {{ $rt->status_tindak_lanjut == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                        @else
                                        <p class="mb-0">{{ strtoupper(str_replace('_', ' ', $rt->status_tindak_lanjut)) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Actions --}}
    <div class="col-md-4">
        {{-- Status & Actions --}}
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Aksi</h5></div>
            <div class="card-body">
                @if($isAdmin)
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status RTM</label>
                    <select class="select2 form-select" id="rtm-status-select">
                        <option value="draft" {{ $rtm->status == 'draft' ? 'selected' : '' }}>DRAFT</option>
                        <option value="sah" {{ $rtm->status == 'sah' ? 'selected' : '' }}>SAH</option>
                        <option value="ditutup" {{ $rtm->status == 'ditutup' ? 'selected' : '' }}>DITUTUP</option>
                    </select>
                </div>

                @if($rtm->status === 'draft')
                <a href="{{ route('admin.ami.rtm.edit', $rtm->id) }}" class="btn btn-warning w-100 mb-2">
                    <i class="ti ti-pencil me-1"></i> Edit RTM
                </a>
                @endif
                @endif

                <a href="{{ route('admin.ami.rtm.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar RTM
                </a>
            </div>
        </div>

        {{-- Summary --}}
        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Ringkasan</h5></div>
            <div class="card-body">
                @php
                    $totalTemuan = $rtm->rtmTemuans->count(); // total hasil temuan
                    $completedKeputusan = $rtm->rtmTemuans->filter(fn($t) => $t->keputusan && $t->penanggung_jawab_id && $t->target_selesai)->count();
                    $openTl = $rtm->rtmTemuans->where('status_tindak_lanjut', 'open')->count();
                    $progressTl = $rtm->rtmTemuans->where('status_tindak_lanjut', 'on_progress')->count();
                    $selesaiTl = $rtm->rtmTemuans->where('status_tindak_lanjut', 'selesai')->count();
                @endphp
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Hasil Temuan</span>
                    <strong>{{ $totalTemuan }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Keputusan Lengkap</span>
                    <strong class="{{ $completedKeputusan == $totalTemuan && $totalTemuan > 0 ? 'text-success' : 'text-warning' }}">
                        {{ $completedKeputusan }}/{{ $totalTemuan }}
                    </strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-1">
                    <span><span class="badge bg-label-danger">Open</span></span>
                    <strong>{{ $openTl }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span><span class="badge bg-label-warning">On Progress</span></span>
                    <strong>{{ $progressTl }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span><span class="badge bg-label-success">Selesai</span></span>
                    <strong>{{ $selesaiTl }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-save keputusan fields on blur
    $(document).on('blur', '.keputusan-input', function() {
        var el = $(this);
        var rtmTemuanId = el.data('rtm-temuan-id');
        var field = el.data('field');
        var value = el.val();

        var data = {
            _token: '{{ csrf_token() }}',
            rtm_temuan_id: rtmTemuanId
        };
        data[field] = value;

        $.ajax({
            url: "{{ route('admin.ami.rtm.save-keputusan', $rtm->id) }}",
            type: 'POST',
            data: data,
            success: function(res) {
                if (res.status) {
                    showToastr('success', 'success', res.message);
                }
            },
            error: function(xhr) {
                showToastr('error', 'error', xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan');
            }
        });
    });

    // Auto-save select fields on change
    $(document).on('change', '.keputusan-select', function() {
        var el = $(this);
        var rtmTemuanId = el.data('rtm-temuan-id');
        var field = el.data('field');
        var value = el.val();

        var data = {
            _token: '{{ csrf_token() }}',
            rtm_temuan_id: rtmTemuanId
        };
        data[field] = value;

        $.ajax({
            url: "{{ route('admin.ami.rtm.save-keputusan', $rtm->id) }}",
            type: 'POST',
            data: data,
            success: function(res) {
                if (res.status) {
                    showToastr('success', 'success', res.message);
                }
            }
        });
    });

    // Status Tindak Lanjut change
    $(document).on('change', '.status-tl-select', function() {
        var el = $(this);
        var rtmTemuanId = el.data('rtm-temuan-id');
        var value = el.val();

        $.ajax({
            url: "{{ route('admin.ami.rtm.update-status-tl', $rtm->id) }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                rtm_temuan_id: rtmTemuanId,
                status_tindak_lanjut: value
            },
            success: function(res) {
                if (res.status) {
                    showToastr('success', 'success', res.message);
                    // Update badge
                    var badgeMap = { 'open': 'bg-label-danger', 'on_progress': 'bg-label-warning', 'selesai': 'bg-label-success' };
                    var badge = $('.status-tl-badge[data-id="' + rtmTemuanId + '"]');
                    badge.removeClass('bg-label-danger bg-label-warning bg-label-success')
                        .addClass(badgeMap[value])
                        .text(value.replace('_', ' ').toUpperCase());
                }
            }
        });
    });

    // RTM Status change
    $(document).on('change', '#rtm-status-select', function() {
        var value = $(this).val();
        Swal.fire({
            title: 'Ubah Status RTM?',
            text: 'Status akan diubah menjadi ' + value.toUpperCase(),
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, ubah!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect waves-light'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('admin.ami.rtm.change-status', $rtm->id) }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: value
                    },
                    success: function(res) {
                        if (res.status) {
                            showToastr('success', 'success', res.message);
                            setTimeout(function() { location.reload(); }, 1000);
                        }
                    },
                    error: function(xhr) {
                        showToastr('error', 'error', xhr.responseJSON ? xhr.responseJSON.message : 'Gagal mengubah status');
                    }
                });
            } else {
                // Revert dropdown to current value
                $('#rtm-status-select').val('{{ $rtm->status }}');
            }
        });
    });
</script>
@endpush
