@extends('layouts.admin.template')
@section('title', 'News')
@section('content')
    <div class="card" id="card-news">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table table-hover" id="table-1">
                <thead>
                    <tr>
                        <th style="width: 5px">No</th>
                        <th style="width: 100px">Slug</th>
                        <th style="width: 100px">Title</th>
                        <th style="width: 400px">Body</th>
                        <th style="width: 30px">Categories</th>
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
            location.href = "{{ route('admin.news.add') }}";
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
                        url: "{{ route('admin.news.delete') }}",
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
        var dataTable = initDataTables('table-1', 'loader-news', 'card-news', 'new-record-button', false,
            'News', "{{ route('admin.news.data') }}",
            [
                {
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
                    data: "categories",
                    name: "categories",
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
    </script>
@endpush
