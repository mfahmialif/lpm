@extends('layouts.admin.template')
@section('title', 'Hasil Akhir AMI')
@section('content')
    <div class="card" id="card-ami-final-result">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table table-hover" id="table-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Periode</th>
                        <th>Prodi</th>
                        <th>Skor SPME</th>
                        <th>Skor IKT</th>
                        <th>Skor AMI</th>
                        <th>Peringkat</th>
                        <th>Status Akreditasi</th>
                        <th>Dokumen</th>
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
            location.href = "{{ route('admin.ami-final-result.add') }}";
        });
        $(document).on('submit', '.form-delete-record', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
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
                        url: "{{ route('admin.ami-final-result.delete') }}",
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
        var dataTable = initDataTables('table-1', 'loader-ami-final-result', 'card-ami-final-result', 'new-record-button', false,
            'Hasil Akhir AMI', "{{ route('admin.ami-final-result.data') }}",
            [
                { data: "ami_period", name: "ami_period", className: "align-middle" },
                { data: "prodi_name", name: "prodi_name", className: "align-middle" },
                { data: "end_score_spme", name: "end_score_spme", className: "align-middle" },
                { data: "score_ikt", name: "score_ikt", className: "align-middle" },
                { data: "end_score_ami", name: "end_score_ami", className: "align-middle" },
                { data: "rank_ami", name: "rank_ami", className: "align-middle" },
                { data: "accreditation_status", name: "accreditation_status", className: "align-middle" },
                { data: "document", name: "document", className: "align-middle" },
                { data: "action", name: "action", className: "align-middle", searchable: false, orderable: false },
            ]
        );
    </script>
@endpush
