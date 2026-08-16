@extends('layouts.admin.template')
@section('title', 'Dakung Prodi - Instrumen SABTO')
@section('content')
    <div class="card" id="card-dakung-prodi">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-3">Dakung Prodi - Instrumen SABTO</h5>
            <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                <div class="col-md-4 user_role">
                    <select id="filter_accreditation" class="form-select text-capitalize select2">
                        <option value=""> Pilih Semua Akreditasi </option>
                        @foreach ($accreditations as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->prodi->nama }} ({{ $item->year }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table table-hover" id="table-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Akreditasi</th>
                        <th>Kategori</th>
                        <th>Nama Accordion</th>
                        <th>Deskripsi</th>
                        <th>Urutan</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal new record -->
    <div class="offcanvas offcanvas-end" id="new-record">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Tambah Accordion</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body flex-grow-1">
            <form class="record pt-0 row g-2" id="form-new-record"
                action="{{ route('admin.dakung-prodi.store') }}"
                method="POST">
                @csrf
                @include('admin.dakung-prodi.form')
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
            <h5 class="offcanvas-title">Edit Accordion</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body flex-grow-1">
            <form class="record pt-0 row g-2" id="form-edit-record"
                action="{{ route('admin.dakung-prodi.update') }}"
                method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id">
                @include('admin.dakung-prodi.form')
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
            $('#form-new-record .select2').val('').trigger('change');
        });

        $(document).on('click', '.edit-record-button', function() {
            $('#form-edit-record [name="id"]').val($(this).data('id'));
            $('#form-edit-record [name="accreditation_id"]').val($(this).data('accreditation_id')).trigger('change');
            $('#form-edit-record [name="kategori"]').val($(this).data('kategori')).trigger('change');
            $('#form-edit-record [name="name"]').val($(this).data('name'));
            $('#form-edit-record [name="description"]').val($(this).data('description'));
            $('#form-edit-record [name="order_index"]').val($(this).data('order_index'));

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
                title: `Hapus accordion ${name}?`,
                text: "Semua file di dalamnya juga akan terhapus!",
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
                        url: "{{ route('admin.dakung-prodi.delete') }}",
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

        $('#filter_accreditation').on('change', function() {
            dataTable.ajax.reload();
        });
    </script>

    <script>
        var dataTable = initDataTables('table-1', 'loader-dakung-prodi', 'card-dakung-prodi', 'new-record-button', false,
            'Accordion', "{{ route('admin.dakung-prodi.data') }}",
            [{
                    data: "accreditation_info",
                    name: "accreditation_info",
                    className: "align-middle",
                    orderable: false,
                },
                {
                    data: "kategori",
                    name: "kategori",
                    className: "align-middle text-center",
                },
                {
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
                    data: "order_index",
                    name: "order_index",
                    className: "align-middle",
                },
                {
                    data: "action",
                    name: "action",
                    className: "align-middle text-center",
                    searchable: false,
                    orderable: false,
                },
            ], ['filter_accreditation'], false, 5
        );
        
        // Add name mapping for filter manually since our helper might use generic names
        dataTable.on('preXhr.dt', function ( e, settings, data ) {
            data.accreditation_id = $('#filter_accreditation').val();
        });
    </script>
@endpush
