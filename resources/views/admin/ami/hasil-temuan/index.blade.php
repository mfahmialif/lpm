@extends('layouts.admin.template')
@section('title', 'Hasil Temuan')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.sk-auditor.show', $sk->id) }}">{{ $sk->nomor_sk }}</a> /</span>
            Hasil Temuan
        </h4>
    </div>
</div>

@if($canEdit)
<div class="mb-3">
    <a href="{{ route('admin.ami.hasil-temuan.create', $sk->id) }}" class="btn btn-primary">
        <i class="ti ti-plus me-1"></i>Tambah Hasil Temuan
    </a>
</div>
@endif

<div class="card" id="card-hasil-temuan">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Ringkasan</th>
                    <th>Temuan Terkait</th>
                    <th>Dibuat Oleh</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    @if($canDelete)
    $(document).on('submit', '.form-delete-record', function(e) {
        e.preventDefault();
        var nama = $(e.target).find('input[name="nama"]').val();
        Swal.fire({
            title: 'Hapus "' + nama + '"?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect waves-light'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.ami.hasil-temuan.delete', $sk->id) }}",
                    data: new FormData($(e.target)[0]),
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        showToastr(response.type, response.type, response.message);
                        dataTable.ajax.reload(null, false);
                    },
                });
            }
        });
    });
    @endif
</script>

<script>

    var columns = [
        { data: "judul", name: "judul", className: "align-middle" },
        { data: "kategori_badge", name: "kategori", className: "align-middle" },
        { data: "ringkasan_short", name: "ringkasan", className: "align-middle" },
        { data: "temuan_count", name: "temuan_count", className: "align-middle", searchable: false, orderable: false },
        { data: "created_by_name", name: "created_by_name", className: "align-middle", searchable: false },
        { data: "action", name: "action", className: "align-middle", searchable: false, orderable: false },
    ];

    var dataTable = initDataTables('table-1', 'loader-hasil-temuan', 'card-hasil-temuan', false, false,
        'Hasil Temuan', "{{ route('admin.ami.hasil-temuan.data', $sk->id) }}", columns
    );
</script>
@endpush
