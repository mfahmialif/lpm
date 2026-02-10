@extends('layouts.admin.template')
@section('title', 'Unit')
@section('content')
<div class="card" id="card-unit">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Fakultas</th>
                    <th>Jenjang</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal new record -->
<div class="offcanvas offcanvas-end" id="new-record">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Tambah Unit</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-new-record" action="{{ route('admin.unit.store') }}" method="POST">
            @csrf
            @include('admin.unit.form')
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal edit record -->
<div class="offcanvas offcanvas-end" id="edit-record">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Edit Unit</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-edit-record" action="{{ route('admin.unit.update') }}"
            method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id">
            @include('admin.unit.form')
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            </div>
        </form>
    </div>
</div>
<!--/ DataTable with Buttons -->
@endsection
@push('scripts')
<script>
    var offCanvasNewRecord = new bootstrap.Offcanvas($('#new-record'));
    var offCanvasEditRecord = new bootstrap.Offcanvas($('#edit-record'));

    $(document).on('click', '#new-record-button', function() {
        $('#form-new-record')[0].reset();
        offCanvasNewRecord.show();
        $('#form-new-record [name="nama"]').focus();
    });

    $(document).on('click', '.edit-record-button', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const jenis = $(this).data('jenis');
        const fakultas = $(this).data('fakultas');
        const jenjang = $(this).data('jenjang');

        $('#form-edit-record [name="id"]').val(id);
        $('#form-edit-record [name="nama"]').val(nama);
        $('#form-edit-record [name="jenis"]').val(jenis);
        $('#form-edit-record [name="fakultas"]').val(fakultas);
        $('#form-edit-record [name="jenjang"]').val(jenjang);

        offCanvasEditRecord.show();
        $('#form-edit-record [name="nama"]').focus();
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
        var id = $(e.target).find('input[name="id"]').val();
        var nama = $(e.target).find('input[name="nama"]').val();

        Swal.fire({
            title: `Hapus "${nama}"?`,
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
                    url: "{{ route('admin.unit.delete') }}",
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
    var dataTable = initDataTables('table-1', 'loader-unit', 'card-unit', 'new-record-button', false,
        'Unit', "{{ route('admin.unit.data') }}",
        [{
                data: "nama",
                name: "nama",
                className: "align-middle",
            },
            {
                data: "jenis_badge",
                name: "jenis",
                className: "align-middle",
            },
            {
                data: "fakultas",
                name: "fakultas",
                className: "align-middle",
                render: function(data) {
                    return data ? data : '<span class="badge bg-secondary">-</span>';
                }
            },
            {
                data: "jenjang_badge",
                name: "jenjang",
                className: "align-middle",
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