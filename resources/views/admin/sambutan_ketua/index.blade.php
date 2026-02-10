@extends('layouts.admin.template')
@section('title', 'Sambutan Ketua')
@section('content')
<div class="card" id="card-sambutan-ketua">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-sambutan-ketua">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 20%">Nama Ketua</th>
                    <th style="width: 15%">Foto</th>
                    <th style="width: 50%">Sambutan</th>
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
        location.href = "{{ route('admin.sambutan-ketua.add') }}";
    });

    // Initialize DataTable
    var dataTable = initDataTables('table-sambutan-ketua', 'loader-sambutan-ketua', 'card-sambutan-ketua', 'new-record-button', true,
        'Sambutan Ketua', "{{ route('admin.sambutan-ketua.data') }}",
        [{
                data: "nama_ketua",
                name: "nama_ketua",
            },
            {
                data: "foto",
                name: "foto",
                orderable: false,
                searchable: false,
            },
            {
                data: "sambutan",
                name: "sambutan",
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