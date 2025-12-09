@extends('layouts.admin.template')
@section('title', 'Activity')
@section('content')
    <div class="card" id="card-activity">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table table-hover" id="table-1">
                <thead>
                    <tr>
                        <th style="width: 5px">No</th>
                        <th style="width: 100px">Slug</th>
                        <th style="width: 100px">Title</th>
                        <th style="width: 400px">Body</th>
                        <th style="width: 30px">Unit</th>
                        <th style="width: 30px">Tag</th>
                        <th style="width: 10px">Author</th>
                        <th style="width: 10px">Status</th>
                        <th style="width: 5px">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#new-record-button', function() {
            location.href = "{{ route('admin.activity.add') }}";
        });

        $(document).on('submit', '.form-delete-record', function(e) {
            e.preventDefault();
            var id = $(e.target).find('input[name="id"]').val();
            var title = $(e.target).find('input[name="title"]').val();

            Swal.fire({
                title: `Are you sure delete ${title}?`,
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
                        url: "{{ route('admin.activity.delete') }}",
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
        var dataTable = initDataTables('table-1', 'loader-activity', 'card-activity', 'new-record-button', false,
            'Activity', "{{ route('admin.activity.data') }}",
            [{
                    data: "slug",
                    name: "slug",
                },
                {
                    data: "title",
                    name: "title",
                },
                {
                    data: "body",
                    name: "body",
                },
                {
                    data: "unit",
                    name: "unit",
                },
                {
                    data: "tag",
                    name: "tag",
                },
                {
                    data: "users_name",
                    name: "users_name",
                },
                {
                    data: "status",
                    name: "status",
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

        function shareLink(link) {
            navigator.clipboard.writeText(link);
            toastr.success('Ready to copy paste to Whatsapp!!', 'Success');
        }
    </script>
@endpush
