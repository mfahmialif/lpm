@extends('layouts.admin.template')
@section('title', 'Target AMI')
@section('content')
<div class="row">
    <div class="col-12 mb-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Filter</h5>
            </div>
            <div class="card-body">
                <form id="filter-form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="prodi_unit_id">Prodi Unit</label>
                                <select name="prodi_unit_id" id="prodi_unit_id" class="form-select select2 filter">
                                    @if (\Auth::user()->role === 'unit')
                                    @foreach (Auth::user()->prodiUnits as $prodiUnit)
                                    <option value="{{ $prodiUnit->id }}">
                                        {{ $prodiUnit->nama }}
                                    </option>
                                    @endforeach
                                    @else
                                    <option value="*">Semua</option>
                                    @foreach ($prodiUnits as $prodiUnit)
                                    <option value="{{ $prodiUnit->id }}">
                                        {{ $prodiUnit->nama }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card" id="card-ami-target">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Prodi</th>
                    <th>Evaluasi</th>
                    <th>Panduan Asesmen</th>
                    <th>Dokumen</th>
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
        location.href = "{{ route('admin.ami-target.add') }}";
    });

    $(document).on('submit', '.form-delete-record', function(e) {
        e.preventDefault();
        var id = $(e.target).find('input[name="id"]').val();
        var nama = $(e.target).find('input[name="nama"]').val();

        Swal.fire({
            title: `Are you sure delete ${nama}?`
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
                    , url: "{{ route('admin.ami-target.delete') }}"
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

    $(document).on('change', '.filter', function() {
        dataTable.ajax.reload(null, false);
    });

</script>

<script>
    var dataTable = initDataTables('table-1', 'loader-ami-target', 'card-ami-target', 'new-record-button', false
        , 'Target AMI', "{{ route('admin.ami-target.data') }}"
        , [{
                data: "ami_period"
                , name: "ami_period"
                , className: "align-middle"
            , }
            , {
                data: "prodi_unit_name"
                , name: "prodi_unit_name"
                , className: "align-middle"
            , }
            , {
                data: "evaluations"
                , name: "evaluations"
                , className: "align-middle"
            , }
            , {
                data: "assessment_guide"
                , name: "assessment_guide"
                , className: "align-middle"
            , }
            , {
                data: "document"
                , name: "document"
                , className: "align-middle"
            , }
            , {
                data: "status"
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
        , ["prodi_unit_id"]
    );

</script>
@endpush
