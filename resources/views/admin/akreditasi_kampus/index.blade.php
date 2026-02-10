@extends('layouts.admin.template')
@section('title', 'Akreditasi Kampus')
@section('content')
<div class="card" id="card-akreditasi-kampus">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-akreditasi-kampus">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 20%">Perguruan Tinggi</th>
                    <th style="width: 15%">Akreditasi</th>
                    <th style="width: 15%">Tanggal SK</th>
                    <th style="width: 10%">Peringkat</th>
                    <th style="width: 15%">Kadaluarsa</th>
                    <th style="width: 10%">Status</th>
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
        location.href = "{{ route('admin.akreditasi-kampus.add') }}";
    });

    // Initialize DataTable
    var dataTable = initDataTables('table-akreditasi-kampus', 'loader-akreditasi-kampus', 'card-akreditasi-kampus', 'new-record-button', true,
        'Akreditasi Kampus', "{{ route('admin.akreditasi-kampus.data') }}",
        [{
                data: "perguruan_tinggi",
                name: "perguruan_tinggi",
            },
            {
                data: "akreditasi",
                name: "akreditasi",
            },
            {
                data: "tanggal_sk",
                name: "tanggal_sk",
            },
            {
                data: "peringkat",
                name: "peringkat",
            },
            {
                data: "kadaluarsa",
                name: "kadaluarsa",
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