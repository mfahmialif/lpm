@extends('layouts.admin.template')
@section('title', 'Anggota Struktur Organisasi')
@section('content')
<div class="card" id="card-anggota-struktur-organisasi">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-anggota-struktur-organisasi">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 85%">Nama Anggota</th>
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
        location.href = "{{ route('admin.anggota-struktur-organisasi.add') }}";
    });

    // Initialize DataTable
    var dataTable = initDataTables('table-anggota-struktur-organisasi', 'loader-anggota-struktur-organisasi', 'card-anggota-struktur-organisasi', 'new-record-button', true,
        'Anggota', "{{ route('admin.anggota-struktur-organisasi.data') }}",
        [{
                data: "nama",
                name: "nama",
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