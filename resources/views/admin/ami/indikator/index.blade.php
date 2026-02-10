@extends('layouts.admin.template')
@section('title', 'Indikator - ' . $sk->nomor_sk)
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.sk-auditor.index') }}">SK Auditor</a> / <a href="{{ route('admin.ami.sk-auditor.show', $sk->id) }}">{{ $sk->nomor_sk }}</a> /</span> Indikator
        </h4>
    </div>
</div>

<div class="card" id="card-ami-indikator">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Indikator</th>
                    <th>Narasi Evaluasi Diri</th>
                    <th>Status</th>
                    <th>Rubrik</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

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
                            <tr><th width="80">Skor</th><th>Deskripsi</th></tr>
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
        for(let i=1; i<=4; i++) addRubrikItem(i, '');
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
            $('#rubrik-kode').text('[' + data.kode + ']');
            $('#rubrik-pertanyaan').text(data.pertanyaan);
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

    $(document).on('submit', '#form-new-record', function(e) {
        e.preventDefault();
        ajaxRequestDt(e, offCanvasNewRecord, dataTable);
    });

    $(document).on('submit', '#form-edit-record', function(e) {
        e.preventDefault();
        ajaxRequestDt(e, offCanvasEditRecord, dataTable);
    });

    $(document).on('submit', '.form-delete-record', function(e) {
        e.preventDefault();
        var nama = $(e.target).find('input[name="nama"]').val();
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
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.ami.indikator.delete', $sk->id) }}",
                    data: new FormData($(e.target)[0]),
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        showToastr(response.type, response.type, response.message);
                        dataTable.ajax.reload(null, false);
                    },
                });
            }
        });
    });
</script>

<script>
    var dataTable = initDataTables('table-1', 'loader-ami-indikator', 'card-ami-indikator', 'new-record-button', false,
        'Indikator AMI', "{{ route('admin.ami.indikator.data', $sk->id) }}",
        [{
                data: "kode",
                name: "kode",
                className: "align-middle",
            },
            {
                data: "pertanyaan",
                name: "pertanyaan",
                className: "align-middle",
                render: function(data) {
                    return data && data.length > 80 ? data.substring(0, 80) + '...' : data;
                }
            },
            {
                data: "narasi_evaluasi_diri",
                name: "narasi_evaluasi_diri",
                className: "align-middle",
                render: function(data) {
                    return data && data.length > 80 ? data.substring(0, 80) + '...' : data;
                }
            },
            {
                data: "status_badge",
                name: "is_active",
                className: "align-middle",
            },
            {
                data: "rubrik_count",
                name: "rubrik_count",
                className: "align-middle text-center",
                searchable: false,
                orderable: false,
            },
            {
                data: "action",
                name: "action",
                className: "align-middle",
                searchable: false,
                orderable: false,
            },
        ]
    );
</script>
@endpush
