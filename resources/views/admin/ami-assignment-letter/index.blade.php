@extends('layouts.admin.template')
@section('title', 'Surat Tugas AMI')
@section('content')
    <div class="card" id="card-ami-assignment-letter">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table table-hover" id="table-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Periode</th>
                        <th>Prodi</th>
                        <th>Nomor</th>
                        <th>Tanggal Penugasan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Akhir</th>
                        <th>Dokumen</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#new-record-button', function() {
            location.href = "{{ route('admin.ami-assignment-letter.add') }}";
        });

        $(document).on('submit', '.form-delete-record', function(e) {
            e.preventDefault();
            var id = $(e.target).find('input[name="id"]').val();
            var nama = $(e.target).find('input[name="nama"]').val();

            Swal.fire({
                title: `Are you sure delete ${nama}?`,
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
                        url: "{{ route('admin.ami-assignment-letter.delete') }}",
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
        var dataTable = initDataTables('table-1', 'loader-ami-assignment-letter', 'card-ami-assignment-letter', 'new-record-button', false,
            'Surat Tugas AMI', "{{ route('admin.ami-assignment-letter.data') }}",
            [{
                    data: "ami_period",
                    name: "ami_period",
                    className: "align-middle",
                },
                {
                    data: "prodi_name",
                    name: "prodi_name",
                    className: "align-middle",
                },
                {
                    data: "number",
                    name: "number",
                    className: "align-middle",
                },
                {
                    data: "assignment_date",
                    name: "assignment_date",
                    className: "align-middle",
                },
                {
                    data: "start_date",
                    name: "start_date",
                    className: "align-middle",
                },
                {
                    data: "end_date",
                    name: "end_date",
                    className: "align-middle",
                },
                {
                    data: "document",
                    name: "document",
                    className: "align-middle",
                },
                {
                    data: "status",
                    name: "status",
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
