@extends('layouts.admin.template')
@section('title', 'Kelola User - ' . $prodiUnit->nama)
@push('css')
<style>
    /* Reset and override jQuery UI autocomplete completely */
    .ui-widget.ui-autocomplete {
        position: absolute !important;
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 99999 !important;
        background: #ffffff !important;
        border: 1px solid #e9ecef !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1) !important;
        padding: 8px !important;
        margin-top: 4px !important;
        font-family: inherit !important;
    }

    /* Hide the default bullet points */
    .ui-autocomplete,
    .ui-autocomplete ul {
        list-style: none !important;
        padding-left: 0 !important;
        margin: 0 !important;
    }

    /* Remove default list styling from menu items */
    .ui-autocomplete .ui-menu-item {
        list-style: none !important;
        list-style-type: none !important;
        padding: 0 !important;
        margin: 0 !important;
        background: none !important;
        border: none !important;
    }

    /* Style the actual clickable item */
    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper,
    .ui-autocomplete .ui-menu-item-wrapper {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        padding: 10px 14px !important;
        margin: 2px 0 !important;
        cursor: pointer !important;
        font-size: 14px !important;
        font-weight: 400 !important;
        color: #566a7f !important;
        background: transparent !important;
        border: none !important;
        border-radius: 6px !important;
        transition: all 0.15s ease !important;
    }

    /* User icon before each item */
    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper::before {
        content: "\eb9e";
        font-family: "tabler-icons" !important;
        font-size: 18px;
        color: #a8b4c4;
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f4f5f7;
        border-radius: 50%;
        transition: all 0.15s ease;
    }

    /* Hover and active state */
    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper:hover,
    .ui-autocomplete .ui-menu-item-wrapper:hover,
    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper.ui-state-active,
    .ui-autocomplete .ui-menu-item-wrapper.ui-state-active,
    .ui-autocomplete .ui-state-active,
    .ui-autocomplete .ui-state-focus {
        background: #7367f0 !important;
        color: #ffffff !important;
        border: none !important;
    }

    .ui-autocomplete .ui-menu-item .ui-menu-item-wrapper:hover::before,
    .ui-autocomplete .ui-menu-item-wrapper:hover::before,
    .ui-autocomplete .ui-state-active::before,
    .ui-autocomplete .ui-state-focus::before {
        background: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }

    /* Loading indicator on input */
    #search-user.ui-autocomplete-loading {
        background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><circle cx="25" cy="25" r="20" stroke="%237367f0" stroke-width="4" fill="none" stroke-linecap="round"><animate attributeName="stroke-dasharray" dur="1.5s" repeatCount="indefinite" values="1,150;90,150;90,150"/><animate attributeName="stroke-dashoffset" dur="1.5s" repeatCount="indefinite" values="0;-35;-125"/></circle></svg>');
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 20px 20px;
        background-color: #fff;
    }

    /* Search input group */
    .search-input-group {
        display: flex;
        gap: 12px;
        align-items: stretch;
    }

    .search-input-wrapper {
        flex: 1;
        position: relative;
    }

    .search-input-wrapper .form-control {
        height: 100%;
        padding-right: 40px;
    }

    .btn-add-user {
        white-space: nowrap;
        padding: 10px 20px;
    }

    /* Helper text */
    .search-helper-text {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: #a1a5b7;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .search-input-group {
            flex-direction: column;
        }

        .btn-add-user {
            width: 100%;
            margin-top: 8px;
        }

        .ui-widget.ui-autocomplete {
            left: 0 !important;
            right: 0 !important;
            max-width: 100% !important;
        }
    }

</style>
@endpush
@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <a href="{{ route('admin.prodi-unit.index') }}" class="text-muted me-2">
                <i class="ti ti-arrow-left"></i>
            </a>
            Kelola User - {{ $prodiUnit->nama }}
        </h5>
    </div>
    <div class="card-body" id="body-search">
        <label for="search-user" class="form-label">Cari & Tambah User</label>
        <div class="search-input-group">
            <div class="search-input-wrapper">
                <input type="text" class="form-control" id="search-user" placeholder="Ketik nama, email, atau username user..." autocomplete="off">
                <input type="hidden" id="selected-user-id">
            </div>
            <select class="form-select select2" id="select-jenis" style="width: 150px; flex-shrink: 0;">
                <option value="auditor">Auditor</option>
                <option value="auditee">Auditee</option>
            </select>
            <button type="button" class="btn btn-primary btn-add-user" id="btn-add-user" disabled>
                <i class="ti ti-user-plus me-1"></i> Tambah
            </button>
        </div>
    </div>
</div>

<div class="card" id="card-users">
    <div class="card-header">
        <h5 class="mb-0">Daftar User</h5>
    </div>
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table table-hover" id="table-users">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Jenis</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var prodiUnit = @json($prodiUnit);
    var prodiUnitId = prodiUnit.id;

    // Initialize DataTable
    var dataTable = $('#table-users').DataTable({
        processing: true
        , serverSide: true
        , ajax: {
            url: "{{ route('admin.prodi-unit.users.data', ['prodiUnit' => $prodiUnit->id]) }}"
        , }
        , columns: [{
                data: null
                , name: 'no'
                , orderable: false
                , searchable: false
                , render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }
            , {
                data: 'name'
                , name: 'users.name'
            }
            , {
                data: 'username'
                , name: 'users.username'
            }
            , {
                data: 'email'
                , name: 'users.email'
            }
            , {
                data: 'jenis_badge'
                , name: 'jenis'
                , orderable: false
            }
            , {
                data: 'action'
                , name: 'action'
                , orderable: false
                , searchable: false
            }
        ]
        , order: [
            [1, 'asc']
        ]
        , dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
    , });

    // jQuery UI Autocomplete
    $('#search-user').autocomplete({
        appendTo: '#body-search'
        , source: function(request, response) {
            $.ajax({
                url: "{{ route('admin.prodi-unit.search-users') }}"
                , dataType: "json"
                , data: {
                    term: request.term
                    , prodi_unit_id: prodiUnitId
                }
                , success: function(data) {
                    response(data);
                }
            });
        }
        , select: function(event, ui) {
            $('#selected-user-id').val(ui.item.id);
            $('#btn-add-user').prop('disabled', false);
        }
        , change: function(event, ui) {
            if (!ui.item) {
                $('#selected-user-id').val('');
                $('#btn-add-user').prop('disabled', true);
            }
        }
    });

    // Clear selection on input change
    $('#search-user').on('input', function() {
        if ($('#selected-user-id').val() && $(this).val() !== $('#search-user').data('selected-name')) {
            $('#selected-user-id').val('');
            $('#btn-add-user').prop('disabled', true);
        }
    });

    // Add user button
    $('#btn-add-user').on('click', function() {
        var userId = $('#selected-user-id').val();
        if (!userId) {
            showToastr('error', 'Error', 'Silakan pilih user dari autocomplete');
            return;
        }

        $.ajax({
            type: "POST"
            , url: "{{ route('admin.prodi-unit.add-user') }}"
            , data: {
                _token: "{{ csrf_token() }}"
                , user_id: userId
                , prodi_unit_id: prodiUnitId
                , jenis: $('#select-jenis').val()
            }
            , success: function(response) {
                showToastr(response.type, response.type, response.message);
                if (response.status) {
                    $('#search-user').val('');
                    $('#selected-user-id').val('');
                    $('#btn-add-user').prop('disabled', true);
                    dataTable.ajax.reload(null, false);
                }
            }
            , error: function(xhr) {
                showToastr('error', 'Error', 'Terjadi kesalahan');
            }
        });
    });

    // Remove user
    $(document).on('submit', '.form-remove-user', function(e) {
        e.preventDefault();
        var form = $(this);
        var userId = form.find('input[name="user_id"]').val();

        Swal.fire({
            title: 'Hapus User?'
            , text: "User akan dihapus dari prodi unit ini"
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
                    , url: "{{ route('admin.prodi-unit.remove-user') }}"
                    , data: {
                        _token: "{{ csrf_token() }}"
                        , _method: "DELETE"
                        , user_id: userId
                        , prodi_unit_id: prodiUnitId
                    }
                    , success: function(response) {
                        showToastr(response.type, response.type, response.message);
                        dataTable.ajax.reload(null, false);
                    }
                    , error: function(xhr) {
                        showToastr('error', 'Error', 'Terjadi kesalahan');
                    }
                });
            }
        });
    });

</script>
@endpush
