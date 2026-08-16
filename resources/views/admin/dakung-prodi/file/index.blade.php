@extends('layouts.admin.template')
@section('title', 'Manage Files - Dakung Prodi')
@section('content')
    <div class="alert alert-solid-info d-flex align-items-center" role="alert">
        <span class="alert-icon rounded">
            <i class="ti ti-info-circle"></i>
        </span>
        Accordion: {{ $dakungProdiCategory->name }} ({{ $dakungProdiCategory->accreditation->prodi->nama }} - {{ $dakungProdiCategory->accreditation->year }})
    </div>

    <div class="card" id="card-dakung-prodi-file">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table table-hover" id="table-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Keterangan</th>
                        <th>Nama Asli File</th>
                        <th>File Local</th>
                        <th class="text-center">Status GDrive</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal new record -->
    <div class="offcanvas offcanvas-end" id="new-record">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Upload File</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body flex-grow-1">
            <form class="record pt-0 row g-2" id="form-new-record"
                action="{{ route('admin.dakung-prodi.file.store', $dakungProdiCategory->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.dakung-prodi.file.form')
                <div class="col-sm-12 mt-4">
                    <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal edit record -->
    <div class="offcanvas offcanvas-end" id="edit-record">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Edit File Info</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body flex-grow-1">
            <form class="record pt-0 row g-2" id="form-edit-record"
                action="{{ route('admin.dakung-prodi.file.update', $dakungProdiCategory->id) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id">
                @include('admin.dakung-prodi.file.form')
                <div class="col-sm-12 mt-4">
                    <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var offCanvasNewRecord = new bootstrap.Offcanvas($('#new-record'));
        var offCanvasEditRecord = new bootstrap.Offcanvas($('#edit-record'));

        $(document).on('click', '#new-record-button', function() {
            offCanvasNewRecord.show();
            $('#form-new-record').trigger("reset");
            $('#form-new-record #file').attr('required', 'required');
            $('#form-new-record #file-help').hide();
        });

        $(document).on('click', '.edit-record-button', function() {
            $('#form-edit-record [name="id"]').val($(this).data('id'));
            $('#form-edit-record [name="name"]').val($(this).data('name'));
            $('#form-edit-record #file').removeAttr('required');
            $('#form-edit-record #file-help').show();
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
            var name = $(e.target).find('input[name="nama"]').val();
            Swal.fire({
                title: `Hapus file ${name}?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.dakung-prodi.file.delete', $dakungProdiCategory->id) }}",
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
        var dataTable = initDataTables('table-1', 'loader-dakung-prodi-file', 'card-dakung-prodi-file', 'new-record-button', false,
            'File', "{{ route('admin.dakung-prodi.file.data', $dakungProdiCategory->id) }}",
            [{
                    data: "name",
                    name: "name",
                    className: "align-middle",
                },
                {
                    data: "original_name",
                    name: "original_name",
                    className: "align-middle",
                },
                {
                    data: "path",
                    name: "path",
                    className: "align-middle text-center",
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "upload_status",
                    name: "upload_status",
                    className: "align-middle text-center",
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "action",
                    name: "action",
                    className: "align-middle text-center",
                    searchable: false,
                    orderable: false,
                },
            ], [], false, 1
        );
    </script>
@endpush
