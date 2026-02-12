@extends('layouts.admin.template')
@section('title', 'Indikator - ' . $sk->nomor_sk)
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

<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.indikator.list') }}">Indikator</a> /</span> {{ $sk->nomor_sk }}
        </h4>
        <p class="text-muted mb-0">Kelola indikator penilaian untuk SK Auditor ini.</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header pb-0">
        <h5 class="card-title mb-0">Filter Data</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.ami.indikator.index', $sk->id) }}" method="GET">
            <div class="row align-items-end g-3">
                <div class="col-md-6">
                    <label class="form-label">Pencarian</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari Kode, Pertanyaan, atau Narasi...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Urutkan</label>
                    <select class="form-select select2" name="sort">
                        <option value="urutan_asc" {{ request('sort') == 'urutan_asc' ? 'selected' : '' }}>Urutan (0-9)</option>
                        <option value="urutan_desc" {{ request('sort') == 'urutan_desc' ? 'selected' : '' }}>Urutan (9-0)</option>
                        <option value="kode_asc" {{ request('sort') == 'kode_asc' ? 'selected' : '' }}>Kode (A-Z)</option>
                        <option value="kode_desc" {{ request('sort') == 'kode_desc' ? 'selected' : '' }}>Kode (Z-A)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('admin.ami.indikator.index', $sk->id) }}" class="btn btn-label-secondary w-100">
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mb-3">
    <a href="{{ route('admin.ami.indikator.export-excel', $sk->id) }}" class="btn btn-success">
        <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
    </a>
    <button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#modal-import">
        <i class="ti ti-upload me-1"></i> Import Excel
    </button>
    <button class="btn btn-primary" type="button" id="new-record-button">
        <i class="ti ti-plus me-1"></i> Tambah Indikator
    </button>
</div>

<!-- Modal Import -->
<div class="modal fade" id="modal-import" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.ami.indikator.import-excel', $sk->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Indikator dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="ti ti-info-circle me-1"></i>
                        Download template terlebih dahulu dengan klik <strong>Export Excel</strong>, lalu isi data dan import kembali.
                        Kode indikator yang sudah ada akan dilewati.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Excel (.xlsx)</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-upload me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    @forelse($indikators as $indikator)
    <div class="col-12 mb-3">
        <div class="card hover-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-label-primary me-2">{{ $indikator->kode }}</span>
                        @if($indikator->is_active)
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt{{ $indikator->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt{{ $indikator->id }}">
                            <a class="dropdown-item edit-record-button" href="javascript:void(0);" data-id="{{ $indikator->id }}" data-kode="{{ $indikator->kode }}" data-pertanyaan="{{ $indikator->pertanyaan }}" data-narasi_evaluasi_diri="{{ $indikator->narasi_evaluasi_diri }}" data-urutan="{{ $indikator->urutan }}" data-is_active="{{ $indikator->is_active }}" data-rubrik="{{ json_encode($indikator->rubrikSkors) }}">Edit</a>
                            <a class="dropdown-item btn-detail" href="javascript:void(0);" data-id="{{ $indikator->id }}">Lihat Rubrik</a>
                            <div class="dropdown-divider"></div>
                            <form class="form-delete-record" method="POST" action="{{ route('admin.ami.indikator.delete', $sk->id) }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $indikator->id }}">
                                <input type="hidden" name="nama" value="{{ $indikator->kode }}">
                                <button type="submit" class="dropdown-item text-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>

                <h5 class="card-title mt-2">{{ $indikator->pertanyaan }}</h5>

                @if($indikator->narasi_evaluasi_diri)
                <div class="mb-3">
                    <small class="text-muted fw-bold">Narasi Evaluasi Diri:</small>
                    <p class="text-muted mb-0 small">{{ Str::limit($indikator->narasi_evaluasi_diri, 150) }}</p>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                    <small class="text-muted">Urutan: {{ $indikator->urutan }}</small>
                    <button class="btn btn-sm btn-outline-primary btn-detail" data-id="{{ $indikator->id }}">
                        <i class="ti ti-list-details me-1"></i> {{ $indikator->rubrikSkors->count() }} Rubrik, Klik untuk detail
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="ti ti-list-check ti-lg text-muted mb-3 d-block" style="font-size: 3rem;"></i>
                <h6>Belum ada Indikator</h6>
                <p class="mb-0">Silakan tambahkan indikator baru.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $indikators->links() }}
</div>

<!-- All Modals and Scripts from original file -->
<!-- Modal new record -->
<div class="offcanvas offcanvas-end" id="new-record" style="width: 500px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Tambah Indikator</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-new-record" action="{{ route('admin.ami.indikator.store', $sk->id) }}" method="POST">
            @csrf
            @include('admin.ami.indikator.form')
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal edit record -->
<div class="offcanvas offcanvas-end" id="edit-record" style="width: 500px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Edit Indikator</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-edit-record" action="{{ route('admin.ami.indikator.update', $sk->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id">
            @include('admin.ami.indikator.form')
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal detail rubrik -->
<div class="modal fade" id="modal-rubrik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Rubrik Penskoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 id="rubrik-kode" class="mb-1"></h6>
                <p id="rubrik-pertanyaan" class="text-muted mb-3"></p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="80">Skor</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody id="rubrik-tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var offCanvasNewRecord = new bootstrap.Offcanvas($('#new-record'));
    var offCanvasEditRecord = new bootstrap.Offcanvas($('#edit-record'));

    function addRubrikItem(skor = '', deskripsi = '') {
        var index = $('.rubrik-item').length + 1;
        var html = `
            <div class="rubrik-item mb-2 border p-3 rounded position-relative">
                <button type="button" class="btn btn-sm btn-icon btn-danger position-absolute top-0 end-0 m-2 btn-remove-rubrik" tabindex="-1">
                    <i class="ti ti-x"></i>
                </button>
                <div class="row g-2">
                    <div class="col-sm-2">
                        <label class="form-label">Skor</label>
                        <input type="number" class="form-control" name="rubrik_skor[]" value="${skor}" placeholder="1" required />
                    </div>
                    <div class="col-sm-10">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="rubrik_deskripsi[]" rows="2" placeholder="Deskripsi..." required>${deskripsi}</textarea>
                    </div>
                </div>
            </div>
        `;
        $('.rubrik-container').append(html);
    }

    $(document).on('click', '.btn-add-rubrik', function() {
        addRubrikItem();
    });

    $(document).on('click', '.btn-remove-rubrik', function() {
        $(this).closest('.rubrik-item').remove();
    });

    $(document).on('click', '#new-record-button', function() {
        $('#form-new-record')[0].reset();
        $('.rubrik-container').empty();
        // Add default 4 items for new record
        for (let i = 1; i <= 4; i++) addRubrikItem(i, '');
        offCanvasNewRecord.show();
    });

    $(document).on('click', '.edit-record-button', function() {
        var btn = $(this);
        $('#form-edit-record [name="id"]').val(btn.data('id'));
        $('#form-edit-record [name="kode"]').val(btn.data('kode'));
        $('#form-edit-record [name="pertanyaan"]').val(btn.data('pertanyaan'));
        $('#form-edit-record [name="narasi_evaluasi_diri"]').val(btn.data('narasi_evaluasi_diri'));
        $('#form-edit-record [name="urutan"]').val(btn.data('urutan'));
        $('#form-edit-record [name="is_active"]').val(btn.data('is_active'));

        $('.rubrik-container').empty();
        var rubrik = btn.data('rubrik');
        if (rubrik && rubrik.length > 0) {
            rubrik.forEach(function(item) {
                addRubrikItem(item.skor, item.deskripsi);
            });
        } else {
            // Fallback if no rubrik
            addRubrikItem(1, '');
        }
        offCanvasEditRecord.show();
    });

    $(document).on('click', '.btn-detail', function() {
        var id = $(this).data('id');
        $.get("{{ url('admin/ami/sk-auditor/' . $sk->id . '/indikator/rubrik') }}/" + id, function(data) {
            console.log(data);
            $('#rubrik-kode').text(`[${data.kode}]`);

            let teks = `
                <div class="rubrik-content">
                    <div class="mb-2">
                        <strong>Indikator:</strong><br>
                        ${data.pertanyaan}
                    </div>
                    <div>
                        <strong>Narasi Evaluasi Diri:</strong><br>
                        ${data.narasi_evaluasi_diri}
                    </div>
                </div>
            `;

            $('#rubrik-pertanyaan').html(teks);

            var html = '';
            if (data.rubrik_skors) {
                for (var i = 0; i < data.rubrik_skors.length; i++) {
                    html += '<tr><td class="text-center fw-bold">' + data.rubrik_skors[i].skor + '</td><td>' + data.rubrik_skors[i].deskripsi + '</td></tr>';
                }
            }
            $('#rubrik-tbody').html(html);
            $('#modal-rubrik').modal('show');
        });
    });

    // Handle Form Submit (Standard Post, not AJAX DataTable)
    /* 
       Since we removed DataTables, we can just let standard form submission happen, OR use AJAX and reload page.
       Original code used 'ajaxRequestDt' which reloaded DataTable. 
       Here we can submit normally or use AJAX and reload page.
       Let's stick to standard form submission for simplicity and reliability with file uploads/etc if changed later.
       Actually, 'indikator/form' might be simple.
    */

</script>
@endpush
