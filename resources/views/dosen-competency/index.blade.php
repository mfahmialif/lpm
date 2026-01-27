@extends('layouts.home.template')

@section('content')
<style>
    /* Custom styling for this page content only, without affecting global theme or body background */
    .competency-card {
        background: #fff;
        border-radius: 18px;
        /* Matches radius18 class seen in other views */
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        /* Subtle shadow similar to modern cards */
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }

    .form-label {
        font-weight: 600;
        color: #111827;
        /* Dark text */
        margin-bottom: 0.5rem;
    }

    /* Modern input styling that fits white background */
    .form-control,
    .form-select {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #fff;
        border-color: #6366f1;
        /* Primary color accent */
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    /* Custom Option Items */
    .custom-option-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #fdfdfd;
        border: 1px solid #eef2f6;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .custom-option-item:hover {
        border-color: #6366f1;
        background: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .custom-option-item.selected {
        background-color: #eff6ff;
        /* Light blue tint */
        border-color: #6366f1;
    }

    /* .custom-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 0.35rem;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
        transition: all 0.2s;
        background: #fff;
    } */

    .custom-option-item.selected .custom-checkbox {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }

    .custom-checkbox::after {
        content: '✓';
        color: white;
        font-size: 0.8rem;
        display: none;
    }

    .custom-option-item.selected .custom-checkbox::after {
        display: block;
    }

    /* Autocomplete List */
    .autocomplete-list {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        padding: 0.5rem;
        margin-top: 0.5rem;
        border: 1px solid #eee;
    }

    .autocomplete-item {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .autocomplete-item:hover {
        background-color: #f3f4f6;
    }

    .autocomplete-avatar {
        width: 2.5rem;
        height: 2.5rem;
        background: #e0e7ff;
        color: #4338ca;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
        opacity: 0;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-submit {
        background: #4f46e5;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        color: white;
    }

    /* Ensure table rows are not too huge */
    .table td,
    .table th {
        vertical-align: middle;
        padding: 0.5rem 0.5rem;
        /* Standard padding */
    }

    /* Force checkbox to use native browser appearance - normal size */
    .competency-check,
    #check_all_table,
    #competency_table input[type="checkbox"] {
        -webkit-appearance: checkbox !important;
        -moz-appearance: checkbox !important;
        appearance: checkbox !important;
        width: auto !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        cursor: pointer;
        outline: none !important;
        box-shadow: none !important;
    }

    /* Remove focus outline/ring */
    #competency_table input[type="checkbox"]:focus,
    #competency_table input[type="checkbox"]:focus-visible {
        outline: none !important;
        box-shadow: none !important;
        border-color: inherit !important;
    }
</style>

<div style="height: 50px"></div>

<div class="page-content mt-100" style="margin-bottom: 100px;">
    <div class="container">

        <!-- Header Section (Simple & Clean) -->
        <div class="text-center mb-5" data-aos="fade-up">
            <h1 class="heading text-40 fw-700 mb-3">Input Kompetensi Dosen</h1>
            <p class="text-muted text-18">Kelola data kompetensi dan keahlian dosen untuk periode aktif.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="competency-card p-4 p-md-5" data-aos="fade-up" data-aos-delay="100">
                    <form id="competencyForm">
                        @csrf
                        <!-- Dosen Search Section -->
                        <div class="mb-4 position-relative">
                            <label for="dosen_input" class="form-label">
                                <i class="ti ti-user me-1 text-primary"></i> Nama Dosen
                            </label>
                            <div class="position-relative">
                                <input type="text"
                                    class="form-control form-control-lg"
                                    id="dosen_input"
                                    placeholder="Ketik nama untuk mencari..."
                                    autocomplete="off">
                                <div class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </div>
                            </div>
                            <input type="hidden" name="dosen_id" id="dosen_id">

                            <div id="dosen_list" class="autocomplete-list position-absolute w-100" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto;">
                                <!-- Items inserted via JS -->
                            </div>
                        </div>

                        <!-- Prodi Select Section -->
                        <div class="mb-5 fade-in-up" id="prodi_section" style="animation-delay: 0.1s;">
                            <label for="prodi_select" class="form-label">
                                <i class="ti ti-school me-1 text-primary"></i> Program Studi
                            </label>
                            <select class="form-select form-select-lg" id="prodi_select" name="prodi_id">
                                <option value="" selected disabled>Pilih Program Studi</option>
                                @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Competencies Section -->
                        <div id="competency_section" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between mb-3 fade-in-up" style="animation-delay: 0.2s;">
                                <label class="form-label mb-0">
                                    <i class="ti ti-list-check me-1 text-primary"></i> Daftar Kompetensi Tersedia
                                </label>
                            </div>

                            <div class="table-responsive mb-4" id="competency_container" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover table-bordered table-striped" id="competency_table" style="overflow-y: scroll">
                                    <thead class="table-light box-shadow-sticky" style="position: sticky; top: 0; z-index: 1;">
                                        <tr>
                                            <th class="text-center">
                                                <input type="checkbox" class="form-check-input" id="check_all_table">
                                            </th>
                                            <th>Nama Kompetensi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Populated via JS -->
                                    </tbody>
                                </table>
                                <div id="empty_state" class="text-center py-5 border rounded-3 bg-light d-none">
                                    <p class="text-secondary mb-0">Tidak ada kompetensi tersedia untuk program studi ini.</p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-5 fade-in-up" style="animation-delay: 0.4s;">
                                <button type="submit" class="btn btn-submit d-flex align-items-center gap-2">
                                    Simpan Data
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div id="loading_state" class="text-center py-5" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted fw-medium">Sedang memproses...</p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="liveToast" class="toast align-items-center text-white border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="ti ti-info-circle fs-4 me-2"></i>
                <span id="toast-message">Notification</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2 on Prodi Select
        $('#prodi_select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih Program Studi',
            allowClear: true
        });

        // Re-bind change event because select2 change event might trigger differently
        $('#prodi_select').on('change', function() {
            // Trigger the original logic
            // The original .on('change') handler below will catch this because Select2 triggers standard change event on the original select element.
        });

        // Ensure z-index is correct if inside modal or strictly positioned elements, 
        // though here it is in normal flow.

        let timer;
        const toast = new bootstrap.Toast(document.getElementById('liveToast'));

        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            const body = toastEl.querySelector('.toast-body');
            const icon = body.querySelector('i');

            toastEl.className = `toast align-items-center text-white border-0 shadow-lg bg-${type}`;
            document.getElementById('toast-message').innerText = message;

            // Simple icon toggle. Ideally use specific icon classes if available
            if (type === 'success') {
                icon.className = 'ti ti-circle-check fs-4 me-2';
            } else {
                icon.className = 'ti ti-alert-circle fs-4 me-2';
            }

            toast.show();
        }

        // Dosen Autocomplete
        $('#dosen_input').on('keyup', function() {
            clearTimeout(timer);
            let query = $(this).val();
            if (query.length < 3) {
                $('#dosen_list').slideUp(200);
                return;
            }

            timer = setTimeout(function() {
                // Use the public alias for DosenController::search
                let baseUrl = "{{ route('dosen-competency.search-dosen', ':id') }}";
                let url = baseUrl.replace(':id', encodeURIComponent(query));

                $.ajax({
                    url: url,
                    method: 'GET',
                    // No data object needed as param is in URL
                    success: function(response) {
                        // Response is JSON array
                        if (response.original) {
                            response = response.original;
                        } // Handle if response() wrapper is present in some Laravel versions/contexts, though typically .json() unwraps it.

                        let html = '';
                        if (response.length > 0) {
                            response.forEach(dosen => {
                                let initials = dosen.nama.substring(0, 2).toUpperCase();
                                html += `
                                <div class="autocomplete-item select-dosen" data-id="${dosen.id}" data-nama="${dosen.nama}">
                                    <div class="autocomplete-avatar">${initials}</div>
                                    <div>
                                        <div class="fw-bold text-dark">${dosen.nama}</div>
                                        <div class="small text-muted">NIDN: ${dosen.nidn ?? '-'}</div>
                                    </div>
                                </div>`;
                            });
                            $('#dosen_list').html(html).slideDown(200);
                        } else {
                            $('#dosen_list').html('<div class="p-3 text-center text-muted">Dosen tidak ditemukan</div>').slideDown(200);
                        }
                    }
                });
            }, 500);
        });

        // Select Dosen
        $(document).on('click', '.select-dosen', function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            $('#dosen_id').val(id);
            $('#dosen_input').val(nama);
            $('#dosen_list').slideUp(200);
        });

        // Toggle Lists Check
        $(document).on('click', '.custom-option-item', function(e) {
            if (e.target.type !== 'checkbox') {
                const checkbox = $(this).find('input[type="checkbox"]');
                checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
            }
        });

        $(document).on('change', '.competency-check', function() {
            const row = $(this).closest('.custom-option-item');
            if ($(this).is(':checked')) {
                row.addClass('selected');
            } else {
                row.removeClass('selected');
            }
        });

        // Load Competencies
        $('#prodi_select').on('change', function() {
            let prodiId = $(this).val();
            let dosenId = $('#dosen_id').val(); // Pass dosen ID if needed for filtering logic on server side

            if (!prodiId) return;

            // Show loading
            $('#competency_table tbody').html('<tr><td colspan="2" class="text-center py-4"><div class="spinner-border text-primary spinner-border-sm"></div> Memuat kompetensi...</td></tr>');
            $('#empty_state').addClass('d-none');
            $('#competency_table').removeClass('d-none');
            $('#competency_section').fadeIn();

            $.ajax({
                url: "{{ route('dosen-competency.get-competencies') }}",
                method: 'GET',
                data: {
                    prodi_id: prodiId,
                    dosen_id: dosenId
                },
                success: function(response) {
                    // Ensure the container is visible
                    $('#competency_container').show();

                    let html = '';
                    if (Array.isArray(response) && response.length > 0) {
                        $('#competency_table').removeClass('d-none').show();
                        $('#empty_state').addClass('d-none').hide();

                        response.forEach((item, index) => {
                            html += `
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="competency_ids[]" value="${item.prodi_competency_id}" class="form-check-input competency-check">
                                    </td>
                                    <td>${item.competency_name}</td>
                                </tr>
                            `;
                        });
                        $('#competency_table tbody').html(html);
                    } else {
                        $('#competency_table').addClass('d-none').hide();
                        $('#empty_state').removeClass('d-none').show();
                    }
                }
            });
        });

        // Check All Table
        $('#check_all_table').on('click', function() {
            const isChecked = $(this).is(':checked');
            $('.competency-check').prop('checked', isChecked);
        });

        // Uncheck "check all" if one is unchecked
        $(document).on('change', '.competency-check', function() {
            if (!$(this).is(':checked')) {
                $('#check_all_table').prop('checked', false);
            }
        });

        // Submit Form
        $('#competencyForm').on('submit', function(e) {
            e.preventDefault();

            if (!$('#dosen_id').val()) {
                showToast('Silakan pilih dosen terlebih dahulu!', 'danger');
                $('#dosen_input').focus();
                return;
            }
            if ($('.competency-check:checked').length === 0) {
                showToast('Pilih setidaknya satu kompetensi!', 'danger');
                return;
            }

            const btn = $(this).find('button[type="submit"]');
            const originalBtnText = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...');

            $.ajax({
                url: "{{ route('dosen-competency.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        showToast(response.message, 'success');
                        $('#dosen_id').val('');
                        $('#dosen_input').val('');

                        // Reset Select2
                        $('#prodi_select').val(null).trigger('change');

                        $('.custom-option-item').removeClass('selected');
                        $('.competency-check').prop('checked', false);

                        // Clear only the table body, NOT the whole container (which removes the table tag!)
                        $('#competency_table tbody').html('');
                        $('#competency_table').addClass('d-none').hide();
                        $('#empty_state').addClass('d-none').hide();

                        $('#competency_section').fadeOut();

                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                    } else {
                        showToast(response.message, 'danger');
                    }
                },
                error: function(xhr) {
                    showToast('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Server error'), 'danger');
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalBtnText);
                }
            });
        });

        // Close dropdown on click outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#dosen_input, #dosen_list').length) {
                $('#dosen_list').slideUp(200);
            }
        });
    });
</script>
@endsection