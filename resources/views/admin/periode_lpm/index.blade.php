@extends('layouts.admin.template')
@section('title', 'Periode LPM')
@section('content')
<div class="card" id="card-periode-lpm">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-periode-lpm">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 30%">Dari</th>
                    <th style="width: 30%">Sampai</th>
                    <th style="width: 25%">Status</th>
                    <th style="width: 10%">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).on('click', '#new-record-button', function() {
        location.href = "{{ route('admin.periode-lpm.add') }}";
    });

    // Initialize DataTable
    var dataTable = initDataTables('table-periode-lpm', 'loader-periode-lpm', 'card-periode-lpm', 'new-record-button', true,
        'Periode LPM', "{{ route('admin.periode-lpm.data') }}",
        [{
                data: "dari",
                name: "dari",
            },
            {
                data: "sampai",
                name: "sampai",
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