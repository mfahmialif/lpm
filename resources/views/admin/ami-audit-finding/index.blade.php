@extends('layouts.admin.template')
@section('title', 'Temuan Audit AMI')
@section('content')
<div class="card" id="card-ami-audit-finding">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Prodi</th>
                    <th>Auditor Ketua</th>
                    <th>Dokumen</th>
                    <th>Catatan</th>
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
        location.href = "{{ route('admin.ami-audit-finding.add') }}";
    });

    $(document).on('submit', '.form-delete-record', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?'
            , text: "You won't be able to revert this!"
            , icon: 'warning'
            , showCancelButton: true
            , confirmButtonText: 'Yes, delete it!'
            , customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light'
                , cancelButton: 'btn btn-label-secondary waves-effect waves-light'
            }
            , buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: "POST"
                    , url: "{{ route('admin.ami-audit-finding.delete') }}"
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

    var dataTable = initDataTables('table-1', 'loader-ami-audit-finding', 'card-ami-audit-finding', 'new-record-button', false
        , 'Temuan Audit AMI', "{{ route('admin.ami-audit-finding.data') }}"
        , [{
                data: "ami_period"
                , name: "ami_period"
                , className: "align-middle"
            }
            , {
                data: "prodi_name"
                , name: "prodi_name"
                , className: "align-middle"
            }
            , {
                data: "lead_auditor"
                , name: "lead_auditor"
                , className: "align-middle"
            }
            , {
                data: "document"
                , name: "document"
                , className: "align-middle"
            }
            , {
                data: "note"
                , name: "note"
                , className: "align-middle"
            }
            , {
                data: "status"
                , name: "status"
                , className: "align-middle"
            }
            , {
                data: "action"
                , name: "action"
                , className: "align-middle"
                , searchable: false
                , orderable: false
            }
        , ]
    );

</script>
@endpush
