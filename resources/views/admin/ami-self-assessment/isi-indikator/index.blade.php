@extends('layouts.admin.template')
@section('title', 'Isi Skor Indikator')
@push('styles')
<style>
    .isi-indikator-wrapper {
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

    .isi-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .isi-header {
        background: #fff;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .isi-header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .isi-header-info h5 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    .isi-header-info p {
        margin: 0;
        font-size: 0.8rem;
        color: #6b7280;
    }

    .isi-body {
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

    .scores-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .score-checkbox-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .score-checkbox-item:hover {
        border-color: #22c55e;
        background: #f0fdf4;
    }

    .score-checkbox-item.checked {
        border-color: #22c55e;
        background: #f0fdf4;
        box-shadow: 0 2px 4px rgba(34, 197, 94, 0.1);
    }

    .score-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 1rem;
        border-radius: 0.25rem;
        border: 2px solid #d1d5db;
        cursor: pointer;
        accent-color: #22c55e;
    }

    .score-badge {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        margin-right: 0.75rem;
    }

    .score-description {
        flex: 1;
        font-size: 0.95rem;
        color: #374151;
    }

    .btn-save-selection {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
        margin-top: 1.5rem;
        box-shadow: 0 4px 6px rgba(22, 163, 74, 0.2);
    }

    .btn-save-selection:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 8px rgba(22, 163, 74, 0.25);
    }
    
    .btn-save-selection:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #9ca3af;
        background: #f9fafb;
        border-radius: 0.5rem;
    }
</style>
@endpush
@section('content')
<div class="isi-indikator-wrapper">
    <a href="{{ route('admin.ami-self-assessment.index') }}" class="back-link">
        <i class="ti ti-arrow-left"></i>
        Kembali ke Daftar Asesmen
    </a>

    <div class="isi-card">
        <div class="isi-header">
            <div class="isi-header-icon">
                <i class="ti ti-checkbox"></i>
            </div>
            <div class="isi-header-info">
                <h5>Isi Skor Indikator</h5>
                <p>{{ $amiSelfAssessment->prodiUnit->nama ?? '-' }} - {{ $amiSelfAssessment->amiPeriod->year ?? '-' }}</p>
            </div>
        </div>

        <div class="isi-body">
            @if($indicator)
                <div class="section-title"><i class="ti ti-target"></i> Indikator</div>
                <div class="indicator-display">
                    <span class="indicator-text">{{ $indicator->indicator }}</span>
                </div>

                <div class="section-title mt-4"><i class="ti ti-list-check"></i> Pilih Skor (Bisa lebih dari satu)</div>
                <form id="form-selection">
                    @csrf
                    <div class="scores-list">
                        @if($scores->count() > 0)
                            @foreach($scores as $score)
                            <label class="score-checkbox-item {{ in_array($score->id, $selectedScoreIds) ? 'checked' : '' }}">
                                <input type="checkbox" name="score_ids[]" value="{{ $score->id }}" class="score-checkbox"
                                       {{ in_array($score->id, $selectedScoreIds) ? 'checked' : '' }}>
                                <div class="score-badge">{{ $score->score }}</div>
                                <div class="score-description">{{ $score->description }}</div>
                            </label>
                            @endforeach
                        @else
                            <div class="empty-state">
                                Tidak ada penskoran yang tersedia untuk indikator ini.
                            </div>
                        @endif
                    </div>

                    @if($scores->count() > 0)
                    <button type="submit" class="btn-save-selection" id="btn-save-selection">
                        <i class="ti ti-check"></i> Simpan Pilihan
                    </button>
                    @endif
                </form>
            @else
                <div class="empty-state">
                    <i class="ti ti-alert-circle mb-2" style="font-size: 2rem;"></i><br>
                    Belum ada indikator yang ditetapkan untuk asesmen ini.<br>
                    Silakan tetapkan indikator terlebih dahulu melalui menu "Indikator".
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle class on checkbox change
        $('.score-checkbox').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).closest('.score-checkbox-item').addClass('checked');
            } else {
                $(this).closest('.score-checkbox-item').removeClass('checked');
            }
        });

        // Handle form submission
        $('#form-selection').on('submit', function(e) {
            e.preventDefault();

            $('#btn-save-selection').prop('disabled', true).html('<i class="ti ti-loader ti-spin"></i> Menyimpan...');

            $.ajax({
                type: 'POST',
                url: '{{ route("admin.ami-self-assessment.storeIsiIndikator", $amiSelfAssessment->id) }}',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status) {
                        showToastr('success', 'Sukses', response.message);
                    } else {
                        showToastr('error', 'Error', response.message);
                    }
                },
                error: function(xhr) {
                    showToastr('error', 'Error', xhr.responseJSON?.message || 'Terjadi kesalahan');
                },
                complete: function() {
                    $('#btn-save-selection').prop('disabled', false).html('<i class="ti ti-check"></i> Simpan Pilihan');
                }
            });
        });
    });
</script>
@endpush

