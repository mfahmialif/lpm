@extends('layouts.admin.template')
@section('title', 'Dashboard')
@section('content')
    <div class="row g-6">
        <!-- View sales -->
        <div class="col-xl-{{ $user->role == 'admin' ? '4' : '12' }}">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-7">
                        <div class="card-body text-nowrap">
                            <h5 class="card-title mb-0">Welcome Back {{ $user->name }} 🎉</h5>
                            <p class="mb-2">to the LPM UII Dalwa Website</p>
                            <h4 class="text-primary mb-1">
                                {{ $countActivity }} Activities</h4>
                            <a href="{{ route('admin.activity.add') }}" class="btn btn-primary">Create new Activity ?</a>
                        </div>
                    </div>
                    <div class="col-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('admin/assets/img/illustrations/card-advance-sale.png') }}" height="140"
                                alt="view sales" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- View sales -->

        @if ($user->role == 'admin')
            <!-- Statistics -->
            <div class="col-xl-8 col-md-12">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Statistics</h5>
                        <small class="text-muted">LPM</small>
                    </div>
                    <div class="card-body d-flex align-items-end">
                        <div class="w-100">
                            <div class="row gy-3">
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-primary me-4 p-2">
                                            <i class="ti ti-folder-open ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $countActivity }}</h5>
                                            <small>Activity</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-info me-4 p-2"><i
                                                class="fas fa-newspaper ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $countNews }}</h5>
                                            <small>News</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-success me-4 p-2">
                                            <i class="ti ti-circle-check ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $countAccreditation }}</h5>
                                            <small>Accreditation</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge rounded bg-label-danger me-4 p-2">
                                            <i class="ti ti-user ti-lg"></i>
                                        </div>
                                        <div class="card-info">
                                            <h5 class="mb-0">{{ $countUser }}</h5>
                                            <small>Users</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics -->
        @endif


        <div class="col-xxl-12 col-12">
            <div class="card" id="card-typing">
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
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
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
