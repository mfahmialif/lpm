@extends('layouts.admin.template')
@section('title', 'Competencies')
@section('content')
<div class="card" id="card-competency">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
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
        <form class="record pt-0 row g-2" id="form-new-record" action="{{ route('admin.competency.store') }}" method="POST">
            @csrf
            @include('admin.competency.form')
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
        <form class="record pt-0 row g-2" id="form-edit-record" action="{{ route('admin.competency.update') }}"
            method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id">
            @include('admin.competency.form')
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
        offCanvasNewRecord.show();
        $('#form-new-record [name="kode"]').focus();
    });

    $(document).on('click', '.edit-record-button', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const kode = $(this).data('kode');
        const deskripsi = $(this).data('deskripsi');

        $('#form-edit-record [name="id"]').val(id);
        $('#form-edit-record [name="nama"]').val(nama);
        $('#form-edit-record [name="kode"]').val(kode);
        $('#form-edit-record [name="deskripsi"]').val(deskripsi);

        offCanvasEditRecord.show();
        $('#form-edit-record [name="kode"]').focus();
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
                    url: "{{ route('admin.competency.delete') }}",
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
    var dataTable = initDataTables('table-1', 'loader-competency', 'card-competency', 'new-record-button', false,
        'Competency', "{{ route('admin.competency.data') }}",
        [{
                data: "kode",
                name: "kode",
                className: "align-middle",
            },
            {
                data: "nama",
                name: "nama",
                className: "align-middle",
            },
            {
                data: "deskripsi",
                name: "deskripsi",
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