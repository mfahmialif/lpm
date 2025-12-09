@extends('layouts.admin.template')
@section('title', 'Tag')
@section('content')
    <div class="card" id="card-tag">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table table-hover" id="table-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
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
            <form class="record pt-0 row g-2" id="form-new-record" action="{{ route('admin.tag.store') }}" method="POST">
                @csrf
                @include('admin.tag.form')
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
            <form class="record pt-0 row g-2" id="form-edit-record" action="{{ route('admin.tag.update') }}"
                method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id">
                @include('admin.tag.form')
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
            // $('#form-new-record [name="name"]').val('');
            // $('#form-new-record [name="description"]').val('');

            offCanvasNewRecord.show();
            $('#form-new-record [name="name"]').focus();
        });

        $(document).on('click', '.edit-record-button', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const description = $(this).data('description');

            $('#form-edit-record [name="id"]').val(id);
            $('#form-edit-record [name="name"]').val(name);
            $('#form-edit-record [name="description"]').val(description);

            offCanvasEditRecord.show();
            $('#form-edit-record [name="name"]').focus();
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
            var name = $(e.target).find('input[name="name"]').val();

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
                        url: "{{ route('admin.tag.delete') }}",
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
        var dataTable = initDataTables('table-1', 'loader-tag', 'card-tag', 'new-record-button', false,
            'Tag', "{{ route('admin.tag.data') }}",
            [{
                    data: "name",
                    name: "name",
                    className: "align-middle",
                },
                {
                    data: "description",
                    name: "description",
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
