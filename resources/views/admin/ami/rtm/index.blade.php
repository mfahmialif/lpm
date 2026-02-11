@extends('layouts.admin.template')
@section('title', 'RTM - Rapat Tinjauan Manajemen')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI /</span> RTM
        </h4>
    </div>
</div>
<div class="card" id="card-rtm">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode RTM</th>
                    <th>Tanggal</th>
                    <th>Pimpinan</th>
                    <th>Unit Audit</th>
                    <th>Jml SK</th>
                    <th>Status</th>
                    @if($isAdmin)
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    @if($isAdmin)
    $(document).on('click', '#new-record-button', function() {
        location.href = "{{ route('admin.ami.rtm.create') }}";
    });

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
                    url: "{{ route('admin.ami.rtm.delete') }}",
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
        { data: "kode_rtm", name: "kode_rtm", className: "align-middle" },
        { data: "tanggal", name: "tanggal_rtm", className: "align-middle" },
        { data: "pimpinan_nama", name: "pimpinan_nama", className: "align-middle", searchable: false },
        { data: "unit_list", name: "unit_list", className: "align-middle", orderable: false, searchable: false },
        { data: "jumlah_sk", name: "jumlah_sk", className: "align-middle text-center", orderable: false, searchable: false },
        { data: "status_badge", name: "status", className: "align-middle" },
    ];
    @if($isAdmin)
    columns.push({ data: "action", name: "action", className: "align-middle", searchable: false, orderable: false });
    @endif

    var dataTable = initDataTables('table-1', 'loader-rtm', 'card-rtm', @json($isAdmin ? 'new-record-button' : false), false,
        'RTM', "{{ route('admin.ami.rtm.data') }}", columns
    );
</script>
@endpush
