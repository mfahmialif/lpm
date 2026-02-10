@extends('layouts.admin.template')
@section('title', 'Temuan Audit')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.sk-auditor.show', $sk->id) }}">{{ $sk->nomor_sk }}</a> /</span>
            Temuan Audit
        </h4>
    </div>
</div>

<div class="card" id="card-temuan">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Temuan</th>
                    <th>Deskripsi</th>
                    <th>Rekomendasi</th>
                    <th>Dibuat Oleh</th>
                    @if($canEdit)
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
        </table>
    </div>
</div>

@if($canEdit)
<!-- Modal new record -->
<div class="offcanvas offcanvas-end" id="new-record" style="width: 500px;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Tambah Temuan Audit</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-new-record" action="{{ route('admin.ami.temuan.store', $sk->id) }}" method="POST">
            @csrf
            @include('admin.ami.temuan.form')
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
        <h5 class="offcanvas-title">Edit Temuan Audit</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-edit-record" action="{{ route('admin.ami.temuan.update', $sk->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id">
            @include('admin.ami.temuan.form')
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
@push('scripts')
<script>
    @if($canEdit)
    var offCanvasNewRecord = new bootstrap.Offcanvas($('#new-record'));
    var offCanvasEditRecord = new bootstrap.Offcanvas($('#edit-record'));

    $(document).on('click', '#new-record-button', function() {
        $('#form-new-record')[0].reset();
        offCanvasNewRecord.show();
    });

    $(document).on('click', '.edit-record-button', function() {
        $('#form-edit-record [name="id"]').val($(this).data('id'));
        $('#form-edit-record [name="jenis_temuan"]').val($(this).data('jenis_temuan'));
        $('#form-edit-record [name="deskripsi"]').val($(this).data('deskripsi'));
        $('#form-edit-record [name="rekomendasi"]').val($(this).data('rekomendasi'));
        offCanvasEditRecord.show();
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
                    url: "{{ route('admin.ami.temuan.delete', $sk->id) }}",
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
    @endif
</script>

<script>
    var columns = [
        { data: "jenis_badge", name: "jenis_temuan", className: "align-middle" },
        { data: "deskripsi", name: "deskripsi", className: "align-middle", render: function(d) { return d && d.length > 80 ? d.substring(0, 80) + '...' : d; } },
        { data: "rekomendasi", name: "rekomendasi", className: "align-middle", render: function(d) { return d ? (d.length > 60 ? d.substring(0, 60) + '...' : d) : '-'; } },
        { data: "created_by_name", name: "created_by_name", className: "align-middle", searchable: false },
    ];
    @if($canEdit)
    columns.push({ data: "action", name: "action", className: "align-middle", searchable: false, orderable: false });
    @endif

    var dataTable = initDataTables('table-1', 'loader-temuan', 'card-temuan', @json($canEdit ? 'new-record-button' : false), false,
        'Temuan Audit', "{{ route('admin.ami.temuan.data', $sk->id) }}", columns
    );
</script>
@endpush
