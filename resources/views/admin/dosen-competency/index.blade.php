@extends('layouts.admin.template')
@section('title', 'Kompetensi Dosen')
@section('content')
<div class="card" id="card-dosen-comp">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6 col-12">
                <h5 class="mb-0 mb-md-0">Daftar Kompetensi Dosen</h5>
            </div>
            <div class="col-md-6 col-12">
                <div class="d-flex gap-2 justify-content-md-end justify-content-start mt-2 mt-md-0">
                    <button type="button" class="btn btn-success flex-fill flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="ti ti-file-import me-1"></i> 
                        <span class="d-none d-sm-inline">Import Excel</span>
                        <span class="d-sm-none">Import</span>
                    </button>
                    <a href="{{ route('admin.dosen-competency.add') }}" class="btn btn-primary flex-fill flex-md-grow-0">
                        <i class="ti ti-plus me-1"></i> 
                        <span class="d-none d-sm-inline">Tambah Data</span>
                        <span class="d-sm-none">Tambah</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Display Import Errors -->
    @if(session('import_errors'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            <h6 class="alert-heading mb-2">
                <i class="ti ti-alert-triangle me-2"></i>Detail Error Import
            </h6>
            <ul class="mb-0">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Kompetensi Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.dosen-competency.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File Excel (.xlsx, .xls, .csv)</label>
                            <input class="form-control" type="file" id="file" name="file" required>
                        </div>
                        <div class="alert alert-info">
                            <small>
                                <strong>Format Kolom:</strong><br>
                                kode_dosen, kode_kompetensi, nama_prodi
                            </small>
                            <div class="mt-2">
                                <a href="{{ route('admin.dosen-competency.export-template') }}" class="btn btn-sm btn-info">
                                    <i class="ti ti-download me-1"></i> Download Template
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Import Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Dosen</th>
                    <th>Kompetensi</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th>SK</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).on('submit', '.form-delete-record', function(e) {
        e.preventDefault();
        var form = $(this);
        var name = form.find('input[name="nama"]').val();

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
                    url: form.attr('action'),
                    data: form.serialize(),
                    success: function(response) {
                        showToastr(response.type, response.type, response.message);
                        dataTable.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        showToastr('error', 'Error', 'Gagal menghapus data');
                    }
                });
            }
        });
    });

    var dataTable = initDataTables('table-1', 'loader-dosen-comp', 'card-dosen-comp', null, false,
        'Kompetensi Dosen', "{{ route('admin.dosen-competency.data') }}",
        [{
                data: "dosen",
                name: "dosen.nama",
                className: "align-middle",
            },
            {
                data: "kompetensi",
                name: "kompetensi",
                className: "align-middle",
                searchable: false,
            },
            {
                data: "periode",
                name: "periodeAkademik.nama_periode",
                className: "align-middle",
            },
            {
                data: "status",
                name: "status",
                className: "align-middle",
            },
            {
                data: "sk",
                name: "skKompetensi.nomor_sk",
                className: "align-middle",
            },
            {
                data: "action",
                name: "action",
                className: "align-middle text-center",
                searchable: false,
                orderable: false,
            },
        ]
    );
</script>
@endpush