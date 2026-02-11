@extends('layouts.admin.template')
@section('title', 'SK Auditor AMI')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI /</span> SK Auditor
        </h4>
    </div>
</div>
<div class="card mb-5">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <select class="select2 form-select" id="periode_id">
                    <option value="*">SEMUA PERIODE</option>
                    @foreach($periodes as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->tahun_mulai }}/{{ $p->tahun_selesai }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <select class="select2 form-select" id="unit_id">
                    <option value="*">SEMUA UNIT AUDIT</option>
                    @foreach($units as $u)
                    <option value="{{ $u->id }}">{{ $u->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-success" id="btn-export-excel">
                <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
            </button>
        </div>
    </div>
</div>
<div class="card" id="card-ami-sk">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor SK</th>
                    <th>Periode</th>
                    <th>Unit</th>
                    <th>Auditee</th>
                    <th>Ketua Auditor</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).on('click', '#new-record-button', function() {
        location.href = "{{ route('admin.ami.sk-auditor.create') }}";
    });
    $(document).on('submit', '.form-delete-record', function(e) {
        e.preventDefault();
        var nama = $(e.target).find('input[name="nama"]').val();
        Swal.fire({
            title: 'Hapus "' + nama + '"?'
            , text: "Data yang dihapus tidak dapat dikembalikan!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonText: 'Ya, hapus!'
            , cancelButtonText: 'Batal'
            , customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light'
                , cancelButton: 'btn btn-label-secondary waves-effect waves-light'
            }
            , buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "POST"
                    , url: "{{ route('admin.ami.sk-auditor.delete') }}"
                    , data: new FormData($(e.target)[0])
                    , contentType: false
                    , processData: false
                    , success: function(response) {
                        showToastr(response.type, response.type, response.message);
                        dataTable.ajax.reload(null, false);
                    }
                , });
            }
        });
    });

    $('#periode_id, #unit_id').on('change', function() {
        dataTable.ajax.reload();
    });

    $('#btn-export-excel').on('click', function() {
        var periodeId = $('#periode_id').val();
        var unitId = $('#unit_id').val();
        var url = "{{ route('admin.ami.sk-auditor.export-excel') }}";
        url += '?periode_id=' + periodeId + '&unit_id=' + unitId;
        window.location.href = url;
    });

</script>

<script>
    var dataTable = initDataTables('table-1', 'loader-ami-sk', 'card-ami-sk', "{{ $isAdmin ? 'new-record-button' : false }}", false
        , 'SK Auditor', "{{ route('admin.ami.sk-auditor.data') }}"
        , [{
                data: "nomor_sk"
                , name: "nomor_sk"
                , className: "align-middle"
            , }
            , {
                data: "periode_nama"
                , name: "periode_nama"
                , className: "align-middle"
            , }
            , {
                data: "unit_nama"
                , name: "unit_nama"
                , className: "align-middle"
            , }
            , {
                data: "auditee_names"
                , name: "auditee_names"
                , className: "align-middle"
                , orderable: false
            , }
            , {
                data: "ketua_nama"
                , name: "ketua_nama"
                , className: "align-middle"
            , }
            , {
                data: "status_badge"
                , name: "status"
                , className: "align-middle"
            , }
            , {
                data: "action"
                , name: "action"
                , className: "align-middle"
                , searchable: false
                , orderable: false
            , }
        , ]
        , ['periode_id', 'unit_id']
    );

</script>
@endpush
