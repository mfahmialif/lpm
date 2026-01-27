@extends('layouts.admin.template')
@section('title', 'Prodi Competencies')
@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card" id="card-prodi-comp">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Program Studi</th>
                    <th>Fakultas</th>
                    <th>Jumlah Kompetensi</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var dataTable = initDataTables('table-1', 'loader-prodi-comp', 'card-prodi-comp', null, false,
        'Prodi Competency', "{{ route('admin.prodi-competency.data') }}",
        [{
                data: "nama",
                name: "nama",
                className: "align-middle",
            },
            {
                data: "fakultas",
                name: "fakultas",
                className: "align-middle",
            },
            {
                data: "competencies_count",
                name: "competencies_count",
                className: "align-middle text-center",
                searchable: false,
            },
            {
                data: "action",
                name: "action",
                className: "align-middle text-center",
                searchable: false,
                orderable: false,
            },
        ]
    );
</script>
@endpush