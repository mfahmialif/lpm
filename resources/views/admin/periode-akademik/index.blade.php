@extends('layouts.admin.template')
@section('title', 'Periode Akademik')
@section('content')
<div class="card" id="card-periode">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
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
        <form class="record pt-0 row g-2" id="form-new-record" action="{{ route('admin.periode-akademik.store') }}" method="POST">
            @csrf
            @include('admin.periode-akademik.form')
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
        <form class="record pt-0 row g-2" id="form-edit-record" action="{{ route('admin.periode-akademik.update') }}"
            method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id">
            @include('admin.periode-akademik.form')
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
        $('#form-new-record')[0].reset(); // Reset form agar checkbox tidak nyangkut
        offCanvasNewRecord.show();
        $('#form-new-record [name="nama_periode"]').focus();
    });

    $(document).on('click', '.edit-record-button', function() {
        const id = $(this).data('id');
        const nama_periode = $(this).data('nama_periode');
        const tanggal_mulai = $(this).data('tanggal_mulai');
        const tanggal_selesai = $(this).data('tanggal_selesai');
        const is_active = $(this).data('is_active');

        $('#form-edit-record [name="id"]').val(id);
        $('#form-edit-record [name="nama_periode"]').val(nama_periode);
        $('#form-edit-record [name="tanggal_mulai"]').val(tanggal_mulai);
        $('#form-edit-record [name="tanggal_selesai"]').val(tanggal_selesai);

        if (is_active == 1 || is_active == true) {
            $('#form-edit-record [name="is_active"]').prop('checked', true);
        } else {
            $('#form-edit-record [name="is_active"]').prop('checked', false);
        }

        offCanvasEditRecord.show();
        $('#form-edit-record [name="nama_periode"]').focus();
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
                    url: "{{ route('admin.periode-akademik.delete') }}",
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
    var dataTable = initDataTables('table-1', 'loader-periode', 'card-periode', 'new-record-button', false,
        'Periode Akademik', "{{ route('admin.periode-akademik.data') }}",
        [{
                data: "nama_periode",
                name: "nama_periode",
                className: "align-middle",
            },
            {
                data: "tanggal_mulai",
                name: "tanggal_mulai",
                className: "align-middle",
            },
            {
                data: "tanggal_selesai",
                name: "tanggal_selesai",
                className: "align-middle",
            },
            {
                data: "is_active",
                name: "is_active",
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