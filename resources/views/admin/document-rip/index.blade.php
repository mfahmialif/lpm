@extends('layouts.admin.template')
@section('title', 'Dokumen RIP')
@section('content')
<div class="card" id="card-document">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dokumen</th>
                    <th>No Surat</th>
                    <th>Perihal</th>
                    <th>Yang Mengeluarkan</th>
                    <th>Unit</th>
                    <th>Program Studi</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal new record -->
<div class="offcanvas offcanvas-end" id="new-record">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title">Tambah Dokumen RIP</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-new-record" action="{{ route('admin.document-rip.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.document-rip.form')
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
        <h5 class="offcanvas-title">Edit Dokumen RIP</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
        <form class="record pt-0 row g-2" id="form-edit-record" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id">
            @include('admin.document-rip.form')
            <div class="col-sm-12">
                <div class="mb-2" id="current-file-container" style="display: none;">
                    <label class="form-label">File Saat Ini:</label>
                    <a href="#" id="current-file-link" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-download"></i> Lihat File
                    </a>
                </div>
            </div>
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
        const no_surat = $(this).data('no_surat');
        const perihal = $(this).data('perihal');
        const yang_mengeluarkan = $(this).data('yang_mengeluarkan');
        const unit_id = $(this).data('unit_id');
        const prodi_id = $(this).data('prodi_id');
        const path = $(this).data('path');

        $('#form-edit-record').attr('action', "{{ url('admin/document-rip/update') }}/" + id);
        $('#form-edit-record [name="id"]').val(id);
        $('#form-edit-record [name="nama"]').val(nama);
        $('#form-edit-record [name="no_surat"]').val(no_surat);
        $('#form-edit-record [name="perihal"]').val(perihal);
        $('#form-edit-record [name="yang_mengeluarkan"]').val(yang_mengeluarkan);
        $('#form-edit-record [name="unit_id"]').val(unit_id);
        $('#form-edit-record [name="prodi_id"]').val(prodi_id);

        // Show current file if exists
        if (path) {
            $('#current-file-container').show();
            $('#current-file-link').attr('href', "{{ asset('storage') }}/" + path);
        } else {
            $('#current-file-container').hide();
        }

        offCanvasEditRecord.show();
        $('#form-edit-record [name="nama"]').focus();
    });

    $(document).on('submit', '#form-new-record', function(e) {
        e.preventDefault();
        ajaxRequestDtFile(e, offCanvasNewRecord, dataTable);
    });

    $(document).on('submit', '#form-edit-record', function(e) {
        e.preventDefault();
        ajaxRequestDtFile(e, offCanvasEditRecord, dataTable);
    });

    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        var nama = $(this).data('nama');

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
                    type: "DELETE",
                    url: "{{ url('admin/document-rip/destroy') }}/" + id,
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status) {
                            showToastr('success', 'Berhasil', response.message);
                            dataTable.ajax.reload(null, false);
                        } else {
                            showToastr('error', 'Gagal', response.message);
                        }
                    },
                    error: function(xhr) {
                        showToastr('error', 'Error', xhr.responseJSON?.message || 'Terjadi kesalahan');
                    }
                });
            }
        });
    });

    // Custom ajax function for file upload
    function ajaxRequestDtFile(e, offCanvas, dataTable) {
        var form = $(e.target);
        var formData = new FormData(form[0]);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();

        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Loading...');

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status !== false) {
                    showToastr('success', 'Berhasil', 'Data berhasil disimpan');
                    offCanvas.hide();
                    form[0].reset();
                    dataTable.ajax.reload(null, false);
                } else {
                    showToastr('error', 'Gagal', response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var errorMsg = Object.values(errors).flat().join('\n');
                    showToastr('error', 'Validasi Gagal', errorMsg);
                } else {
                    showToastr('error', 'Error', xhr.responseJSON?.message || 'Terjadi kesalahan');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }
</script>

<script>
    var dataTable = initDataTables('table-1', 'loader-document', 'card-document', 'new-record-button', false,
        'Dokumen RIP', "{{ route('admin.document-rip.data') }}",
        [{
                data: "nama",
                name: "nama",
                className: "align-middle",
            },
            {
                data: "no_surat",
                name: "no_surat",
                className: "align-middle",
                render: function(data) {
                    return data ? data : '<span class="badge bg-secondary">-</span>';
                }
            },
            {
                data: "perihal",
                name: "perihal",
                className: "align-middle",
                render: function(data) {
                    return data ? data : '<span class="badge bg-secondary">-</span>';
                }
            },
            {
                data: "yang_mengeluarkan",
                name: "yang_mengeluarkan",
                className: "align-middle",
                render: function(data) {
                    return data ? data : '<span class="badge bg-secondary">-</span>';
                }
            },
            {
                data: "unit_nama",
                name: "unit.nama",
                className: "align-middle",
            },
            {
                data: "prodi_nama",
                name: "prodi.nama",
                className: "align-middle",
            },
            {
                data: "file",
                name: "file",
                className: "align-middle",
                searchable: false,
                orderable: false,
            },
            {
                data: null,
                className: "align-middle",
                searchable: false,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn btn-sm btn-warning edit-record-button me-1" 
                            data-id="${row.id}" 
                            data-nama="${row.nama}" 
                            data-no_surat="${row.no_surat || ''}"
                            data-perihal="${row.perihal || ''}"
                            data-yang_mengeluarkan="${row.yang_mengeluarkan || ''}"
                            data-unit_id="${row.unit_id || ''}"
                            data-prodi_id="${row.prodi_id || ''}"
                            data-path="${row.path || ''}">
                            <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" 
                            data-id="${row.id}" 
                            data-nama="${row.nama}">
                            <i class="ti ti-trash"></i>
                        </button>
                    `;
                }
            },
        ]
    );
</script>
@endpush
