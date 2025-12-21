@extends('layouts.admin.template')
@section('title', 'Hasil Temuan AMI')
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
                                    @foreach (Auth::user()->prodiUnits->where('pivot.jenis', strtolower(Helper::getAmiMode())) as $prodiUnit)
                                    <option value="{{ $prodiUnit->id }}">
                                        {{ $prodiUnit->nama }} [{{ Helper::getAmiMode() }}]
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
<div class="card" id="card-ami-finding-result">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Prodi</th>
                    <th>Pertanyaan Asesmen</th>
                    <th>Dokumen</th>
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
        location.href = "{{ route('admin.ami-finding-result.add') }}";
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
                    , url: "{{ route('admin.ami-finding-result.delete') }}"
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

    var dataTable = initDataTables('table-1', 'loader-ami-finding-result', 'card-ami-finding-result', 'new-record-button', false
        , 'Hasil Temuan AMI', "{{ route('admin.ami-finding-result.data') }}"
        , [{
                data: "category_name"
                , name: "category_name"
                , className: "align-middle"
            }
            , {
                data: "prodi_unit_name"
                , name: "prodi_unit_name"
                , className: "align-middle"
            }
            , {
                data: "assessment_question"
                , name: "assessment_question"
                , className: "align-middle"
            }
            , {
                data: "document"
                , name: "document"
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
        , ["prodi_unit_id"]
    );

</script>
@endpush
