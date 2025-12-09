@extends('layouts.admin.template')
@section('title', 'Requirement')
@section('content')
    <input type="hidden" id="parent_id" value="{{ $parentId }}">

    {{-- <div class="alert alert-primary" role="alert">{{ $accreditation->name }}</div> --}}
    <div class="alert alert-solid-info d-flex align-items-center" role="alert">
        <span class="alert-icon rounded">
            <i class="ti ti-info-circle"></i>
        </span>
        {{ $accreditation->name }} - {{ $accreditation->prodi->nama }} ({{ $accreditation->year }})
    </div>
    @if ($requirement)
        <div class="alert alert-primary alert-dismissible" role="alert">
            <h5 class="alert-heading mb-2">Sub Requirement: {{ $requirement->code ?? $requirement->title }}</h5>
            <p class="mb-0">
                You are currently in Sub Requirement <b>{{ $requirement->code }} - {{ $requirement->title }}</b>
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card" id="card-requirement">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table table-hover" id="table-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Link</th>
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
            <form class="record pt-0 row g-2" id="form-new-record"
                action="{{ route('admin.accreditation.requirement.store', ['accreditation' => $accreditation]) }}"
                method="POST">
                @csrf
                @include('admin.accreditation.requirement.form')
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
            <form class="record pt-0 row g-2" id="form-edit-record"
                action="{{ route('admin.accreditation.requirement.update', ['accreditation' => $accreditation]) }}"
                method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id">
                @include('admin.accreditation.requirement.form')
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
            $('#form-edit-record [name="id"]').val($(this).data('id'));
            $('#form-edit-record [name="parent_id"]').val('{{ $parentId }}');
            $('#form-edit-record [name="code"]').val($(this).data('code'));
            $('#form-edit-record [name="title"]').val($(this).data('title'));
            $('#form-edit-record [name="link"]').val($(this).data('link'));
            $('#form-edit-record [name="description"]').val($(this).data('description'));

            offCanvasEditRecord.show();
            $('#form-edit-record [name="code"]').focus();
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
                        url: "{{ route('admin.accreditation.requirement.delete', ['accreditation' => $accreditation]) }}",
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
        var dataTable = initDataTables('table-1', 'loader-requirement', 'card-requirement', 'new-record-button', false,
            'Requirement', "{{ route('admin.accreditation.requirement.data', ['accreditation' => $accreditation]) }}",
            [{
                    data: "code",
                    name: "code",
                    className: "align-middle",
                },
                {
                    data: "title",
                    name: "title",
                    className: "align-middle",
                },
                {
                    data: "description",
                    name: "description",
                    className: "align-middle",
                },
                {
                    data: "link",
                    name: "link",
                    className: "align-middle",
                },
                {
                    data: "action",
                    name: "action",
                    className: "align-middle",
                    searchable: false,
                    orderable: false,
                },
            ], ['parent_id'], false, 1
        );
    </script>
@endpush
