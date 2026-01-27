@extends('layouts.admin.template')
@section('title', 'SK Kompetensi')
@section('content')
<div class="card" id="card-sk">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor SK</th>
                    <th>Tanggal</th>
                    <th>Tentang</th>
                    <th>File</th>
                    <th>Ditetapkan Oleh</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal new record -->
<div class="offcanvas offcanvas-end" id="new-record">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">New Record</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-new-record" action="{{ route('admin.sk-kompetensi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.sk-kompetensi.form')
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
        <h5 class="offcanvas-title">Edit Record</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-edit-record" action="{{ route('admin.sk-kompetensi.update') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id">
            @include('admin.sk-kompetensi.form')
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
        $('#form-new-record [name="nomor_sk"]').focus();
    });

    $(document).on('click', '.edit-record-button', function() {
        const id = $(this).data('id');
        const nomor_sk = $(this).data('nomor_sk');
        const tanggal_sk = $(this).data('tanggal_sk');
        const tentang = $(this).data('tentang');
        const ditetapkan_oleh = $(this).data('ditetapkan_oleh');
        const is_active = $(this).data('is_active');

        $('#form-edit-record [name="id"]').val(id);
        $('#form-edit-record [name="nomor_sk"]').val(nomor_sk);
        $('#form-edit-record [name="tanggal_sk"]').val(tanggal_sk);
        $('#form-edit-record [name="tentang"]').val(tentang);
        $('#form-edit-record [name="ditetapkan_oleh"]').val(ditetapkan_oleh);
        $('#form-edit-record [name="is_active"]').prop('checked', is_active == 1);

        // clear file input
        $('#form-edit-record [name="file_sk"]').val('');

        offCanvasEditRecord.show();
        $('#form-edit-record [name="nomor_sk"]').focus();
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
        var name = $(e.target).find('input[name="nama"]').val();

        Swal.fire({
            title: `Are you sure delete ${name}?`,
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect waves-light'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.sk-kompetensi.delete') }}",
                    data: new FormData($(e.target)[0]),
                    // use [0] because inner swal so there are has 2 target, cant use currentTarget
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        showToastr(response.type, response.type, response
                            .message);
                        dataTable.ajax.reload(null, false);
                    },
                });
            }
        });
    });
</script>

<script>
    var dataTable = initDataTables('table-1', 'loader-sk', 'card-sk', 'new-record-button', false,
        'SK Kompetensi', "{{ route('admin.sk-kompetensi.data') }}",
        [{
                data: "nomor_sk",
                name: "nomor_sk",
                className: "align-middle",
            },
            {
                data: "tanggal_sk",
                name: "tanggal_sk",
                className: "align-middle",
            },
            {
                data: "tentang",
                name: "tentang",
                className: "align-middle",
            },
            {
                data: "file_sk",
                name: "file_sk",
                className: "align-middle",
                searchable: false,
                orderable: false,
            },
            {
                data: "ditetapkan_oleh",
                name: "ditetapkan_oleh",
                className: "align-middle",
            },
            {
                data: "is_active",
                name: "is_active",
                className: "align-middle text-center",
                searchable: false,
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