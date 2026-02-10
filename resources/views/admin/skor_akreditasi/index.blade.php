@extends('layouts.admin.template')
@section('title', 'Skor Akreditasi')
@section('content')
<div class="card" id="card-skor-akreditasi">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-skor-akreditasi">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th>Perguruan Tinggi</th>
                    <th>Prodi</th>
                    <th>Peringkat</th>
                    <th>No SK</th>
                    <th>Tgl Kadaluarsa</th>
                    <th>Status</th>
                    <th>Link Drive</th>
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
        location.href = "{{ route('admin.skor-akreditasi.add') }}";
    });

    // Initialize DataTable
    var dataTable = initDataTables('table-skor-akreditasi', 'loader-skor-akreditasi', 'card-skor-akreditasi', 'new-record-button', true,
        'Skor Akreditasi', "{{ route('admin.skor-akreditasi.data') }}",
        [{
                data: "perguruan_tinggi",
                name: "perguruan_tinggi",
            },
            {
                data: "prodi_nama",
                name: "prodi_nama",
            },
            {
                data: "peringkat",
                name: "peringkat",
            },
            {
                data: "no_sk",
                name: "no_sk",
            },
            {
                data: "tgl_kadaluarsa_formatted",
                name: "tgl_kadaluarsa",
            },
            {
                data: "status_badge",
                name: "status",
            },
            {
                data: "link_drive_button",
                name: "link_drive",
                searchable: false,
                orderable: false,
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