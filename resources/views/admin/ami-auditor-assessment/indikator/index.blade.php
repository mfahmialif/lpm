@extends('layouts.admin.template')
@section('title', 'Indikator & Penskoran')
@push('styles')
<style>
    .indicator-wrapper {
        max-width: 800px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 1rem;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #4f46e5;
    }

    .indicator-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .indicator-header {
        background: #fff;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .indicator-header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .indicator-header-info h5 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    .indicator-header-info p {
        margin: 0;
        font-size: 0.8rem;
        color: #6b7280;
    }

    .indicator-body {
        padding: 1.5rem;
    }

    .section-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .indicator-form {
        background: #fef3c7;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        border: 1px solid #fcd34d;
    }

    .indicator-input {
        width: 100%;
        border: 2px solid #fbbf24;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s;
        background: #fff;
    }

    .indicator-input:focus {
        outline: none;
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    .btn-save-indicator {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-save-indicator:hover {
        transform: translateY(-1px);
    }

    .indicator-display {
        background: #fef3c7;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        border: 1px solid #fcd34d;
    }

    .indicator-text {
        font-size: 0.95rem;
        color: #92400e;
        font-weight: 500;
    }

    .btn-edit-indicator {
        background: none;
        border: none;
        color: #d97706;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .score-form {
        background: #f0fdf4;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid #86efac;
    }

    .score-input-group {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .score-number {
        width: 70px;
        border: 2px solid #22c55e;
        border-radius: 0.5rem;
        padding: 0.5rem;
        font-size: 0.9rem;
        text-align: center;
        background: #fff;
    }

    .score-desc {
        flex: 1;
        border: 2px solid #22c55e;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        background: #fff;
    }

    .score-number:focus, .score-desc:focus {
        outline: none;
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
    }

    .btn-add-score {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        cursor: pointer;
    }

    .scores-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .score-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
    }

    .score-badge {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .score-description {
        flex: 1;
        font-size: 0.9rem;
        color: #374151;
    }

    .score-actions {
        display: flex;
        gap: 0.25rem;
    }

    .btn-edit-score, .btn-delete-score {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        opacity: 0.6;
        transition: opacity 0.2s;
    }

    .btn-edit-score { color: #3b82f6; }
    .btn-delete-score { color: #ef4444; }
    .btn-edit-score:hover, .btn-delete-score:hover { opacity: 1; }

    .empty-scores {
        text-align: center;
        padding: 2rem 1rem;
        color: #9ca3af;
    }

    .no-indicator-msg {
        text-align: center;
        padding: 2rem;
        color: #9ca3af;
        background: #f9fafb;
        border-radius: 0.5rem;
    }
</style>
@endpush
@section('content')
<div class="indicator-wrapper">
    <a href="{{ route('admin.ami-auditor-assessment.index') }}" class="back-link">
        <i class="ti ti-arrow-left"></i>
        Kembali ke Daftar Asesmen
    </a>

    <div class="indicator-card">
        <div class="indicator-header">
            <div class="indicator-header-icon">
                <i class="ti ti-chart-bar"></i>
            </div>
            <div class="indicator-header-info">
                <h5>Indikator & Penskoran</h5>
                <p>{{ $amiAuditorAssessment->prodiUnit->nama ?? '-' }} - {{ $amiAuditorAssessment->amiPeriod->year ?? '-' }}</p>
            </div>
        </div>

        <div class="indicator-body">
            <!-- Indicator Section -->
            <div class="section-title"><i class="ti ti-target"></i> Indikator</div>
            
            <div id="indicator-section">
                <div class="indicator-display" id="indicator-display" style="{{ $indicator ? '' : 'display:none;' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="indicator-text" id="indicator-text">{{ $indicator->indicator ?? '' }}</span>
                        <button type="button" class="btn-edit-indicator" id="btn-edit-indicator">
                            <i class="ti ti-pencil"></i> Edit
                        </button>
                    </div>
                </div>
                
                <div class="indicator-form" id="indicator-form" style="{{ $indicator ? 'display:none;' : '' }}">
                    <form id="form-indicator" class="d-flex gap-2">
                        <input type="text" id="indicator-input" class="indicator-input" 
                               placeholder="Masukkan deskripsi indikator..." 
                               value="{{ $indicator->indicator ?? '' }}">
                        <button type="submit" class="btn-save-indicator" id="btn-save-indicator">
                            <i class="ti ti-check"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Scores Section -->
            <div id="scores-section" style="{{ $indicator ? '' : 'display:none;' }}">
                <div class="section-title mt-4"><i class="ti ti-list-numbers"></i> Penskoran</div>
                
                <div class="score-form">
                    <form id="form-score" class="score-input-group">
                        <input type="number" id="score-number" class="score-number" placeholder="Skor" min="1">
                        <input type="text" id="score-desc" class="score-desc" placeholder="Deskripsi penskoran...">
                        <button type="submit" class="btn-add-score" id="btn-add-score">
                            <i class="ti ti-plus"></i>
                        </button>
                    </form>
                </div>

                <div class="scores-list" id="scores-list">
                    <div class="empty-scores" id="empty-scores" style="{{ count($scores) > 0 ? 'display:none;' : '' }}">
                        Belum ada penskoran
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var indicatorId = {{ $indicator ? $indicator->id : 'null' }};
        var indicatorDisplay = $('#indicator-display');
        var indicatorForm = $('#indicator-form');
        var indicatorText = $('#indicator-text');
        var indicatorInput = $('#indicator-input');
        var scoresSection = $('#scores-section');
        var scoresList = $('#scores-list');
        var emptyScores = $('#empty-scores');

        // Load existing scores
        var existingScores = @json($scores);
        existingScores.forEach(function(score) {
            appendScore(score);
        });

        // Edit indicator button
        $('#btn-edit-indicator').on('click', function() {
            indicatorDisplay.hide();
            indicatorForm.show();
            indicatorInput.focus();
        });

        // Save indicator
        $('#form-indicator').on('submit', function(e) {
            e.preventDefault();
            var text = indicatorInput.val().trim();
            if (!text) {
                showToastr('error', 'Error', 'Indikator tidak boleh kosong');
                return;
            }

            $('#btn-save-indicator').prop('disabled', true).html('<i class="ti ti-loader ti-spin"></i> Menyimpan...');

            $.ajax({
                type: 'POST',
                url: '{{ route("admin.ami-auditor-assessment.storeIndicator", $amiAuditorAssessment->id) }}',
                data: { _token: '{{ csrf_token() }}', indicator: text },
                success: function(response) {
                    if (response.status) {
                        indicatorId = response.data.id;
                        indicatorText.text(response.data.indicator);
                        indicatorForm.hide();
                        indicatorDisplay.show();
                        scoresSection.show();
                        showToastr('success', 'Sukses', response.message);
                    } else {
                        showToastr('error', 'Error', response.message);
                    }
                },
                error: function(xhr) {
                    showToastr('error', 'Error', xhr.responseJSON?.message || 'Terjadi kesalahan');
                },
                complete: function() {
                    $('#btn-save-indicator').prop('disabled', false).html('<i class="ti ti-check"></i> Simpan');
                }
            });
        });

        // Add score
        $('#form-score').on('submit', function(e) {
            e.preventDefault();
            var scoreNum = $('#score-number').val();
            var scoreDesc = $('#score-desc').val().trim();

            if (!scoreNum || !scoreDesc) {
                showToastr('error', 'Error', 'Skor dan deskripsi harus diisi');
                return;
            }

            if (!indicatorId) {
                showToastr('error', 'Error', 'Simpan indikator terlebih dahulu');
                return;
            }

            $('#btn-add-score').prop('disabled', true).html('<i class="ti ti-loader ti-spin"></i>');

            $.ajax({
                type: 'POST',
                url: '{{ url("admin/ami-auditor-assessment/indikator/score") }}/' + indicatorId,
                data: { _token: '{{ csrf_token() }}', score: scoreNum, description: scoreDesc },
                success: function(response) {
                    if (response.status) {
                        appendScore(response.data);
                        $('#score-number').val('');
                        $('#score-desc').val('');
                        showToastr('success', 'Sukses', response.message);
                    } else {
                        showToastr('error', 'Error', response.message);
                    }
                },
                error: function(xhr) {
                    showToastr('error', 'Error', xhr.responseJSON?.message || 'Terjadi kesalahan');
                },
                complete: function() {
                    $('#btn-add-score').prop('disabled', false).html('<i class="ti ti-plus"></i>');
                }
            });
        });

        function appendScore(score) {
            emptyScores.hide();
            var html = '<div class="score-item" data-score-id="' + score.id + '">' +
                '<div class="score-badge">' + score.score + '</div>' +
                '<div class="score-description">' + escapeHtml(score.description) + '</div>' +
                '<div class="score-actions">' +
                    '<button type="button" class="btn-edit-score" data-id="' + score.id + '" data-score="' + score.score + '" data-desc="' + escapeAttr(score.description) + '"><i class="ti ti-pencil"></i></button>' +
                    '<button type="button" class="btn-delete-score" data-id="' + score.id + '"><i class="ti ti-trash"></i></button>' +
                '</div>' +
            '</div>';
            scoresList.append(html);
        }

        function escapeHtml(text) {
            if (!text) return '';
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function escapeAttr(text) {
            if (!text) return '';
            return text.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        // Delete score
        $(document).on('click', '.btn-delete-score', function() {
            var scoreId = $(this).data('id');
            var scoreElement = $('[data-score-id="' + scoreId + '"]');

            Swal.fire({
                title: 'Hapus Penskoran?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: { confirmButton: 'btn btn-danger me-2', cancelButton: 'btn btn-secondary' },
                buttonsStyling: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return $.ajax({
                        type: 'DELETE',
                        url: '{{ url("admin/ami-auditor-assessment/indikator/score") }}/' + scoreId,
                        data: { _token: '{{ csrf_token() }}' }
                    });
                },
                allowOutsideClick: function() { return !Swal.isLoading(); }
            }).then(function(result) {
                if (result.isConfirmed && result.value.status) {
                    scoreElement.fadeOut(300, function() {
                        $(this).remove();
                        if (scoresList.children('.score-item').length === 0) {
                            emptyScores.show();
                        }
                    });
                    showToastr('success', 'Sukses', result.value.message);
                }
            }).catch(function(xhr) {
                showToastr('error', 'Error', xhr.responseJSON?.message || 'Terjadi kesalahan');
            });
        });

        // Edit score
        $(document).on('click', '.btn-edit-score', function() {
            var scoreId = $(this).data('id');
            var currentScore = $(this).data('score');
            var currentDesc = $(this).data('desc');
            var scoreElement = $('[data-score-id="' + scoreId + '"]');

            Swal.fire({
                title: 'Edit Penskoran',
                html: '<input id="swal-score" class="swal2-input" type="number" placeholder="Skor" value="' + currentScore + '">' +
                      '<input id="swal-desc" class="swal2-input" type="text" placeholder="Deskripsi" value="' + currentDesc + '">',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                customClass: { confirmButton: 'btn btn-primary me-2', cancelButton: 'btn btn-secondary' },
                buttonsStyling: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    var s = $('#swal-score').val();
                    var d = $('#swal-desc').val().trim();
                    if (!s || !d) {
                        Swal.showValidationMessage('Skor dan deskripsi harus diisi');
                        return false;
                    }
                    return $.ajax({
                        type: 'PUT',
                        url: '{{ url("admin/ami-auditor-assessment/indikator/score") }}/' + scoreId,
                        data: { _token: '{{ csrf_token() }}', score: s, description: d }
                    });
                },
                allowOutsideClick: function() { return !Swal.isLoading(); }
            }).then(function(result) {
                if (result.isConfirmed && result.value.status) {
                    scoreElement.find('.score-badge').text(result.value.data.score);
                    scoreElement.find('.score-description').text(result.value.data.description);
                    scoreElement.find('.btn-edit-score').data('score', result.value.data.score).data('desc', result.value.data.description);
                    showToastr('success', 'Sukses', result.value.message);
                }
            }).catch(function(xhr) {
                showToastr('error', 'Error', xhr.responseJSON?.message || 'Terjadi kesalahan');
            });
        });
    });
</script>
@endpush
