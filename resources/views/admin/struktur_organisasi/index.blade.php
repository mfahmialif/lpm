@extends('layouts.admin.template')
@section('title', 'Struktur Organisasi')
@section('content')
<div class="card" id="card-struktur-organisasi">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-struktur-organisasi">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 30%">Periode</th>
                    <th style="width: 30%">Ketua LPM</th>
                    <th style="width: 25%">Penasehat</th>
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
        location.href = "{{ route('admin.struktur-organisasi.add') }}";
    });

    // Initialize DataTable
    var dataTable = initDataTables('table-struktur-organisasi', 'loader-struktur-organisasi', 'card-struktur-organisasi', 'new-record-button', true,
        'Struktur Organisasi', "{{ route('admin.struktur-organisasi.data') }}",
        [{
                data: "periode",
                name: "periode",
            },
            {
                data: "ketua_lpm",
                name: "ketua_lpm",
            },
            {
                data: "penasehat",
                name: "penasehat",
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