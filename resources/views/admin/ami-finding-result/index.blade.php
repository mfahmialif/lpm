@extends('layouts.admin.template')
@section('title', 'Hasil Temuan AMI')
@section('content')
    <div class="card" id="card-ami-finding-result">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table table-hover" id="table-1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Prodi</th>
                        <th>Pertanyaan Asesmen</th>
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
            location.href = "{{ route('admin.ami-finding-result.add') }}";
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
                        url: "{{ route('admin.ami-finding-result.delete') }}",
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

        var dataTable = initDataTables('table-1', 'loader-ami-finding-result', 'card-ami-finding-result', 'new-record-button', false,
            'Hasil Temuan AMI', "{{ route('admin.ami-finding-result.data') }}",
            [
                { data: "category_name", name: "category_name", className: "align-middle" },
                { data: "prodi_name", name: "prodi_name", className: "align-middle" },
                { data: "assessment_question", name: "assessment_question", className: "align-middle" },
                { data: "document", name: "document", className: "align-middle" },
                { data: "action", name: "action", className: "align-middle", searchable: false, orderable: false },
            ]
        );
    </script>
@endpush
