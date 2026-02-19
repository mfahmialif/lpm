@extends('layouts.admin.template')
@section('title', 'Asesmen Auditor - ' . ($sk->unit ? $sk->unit->nama : ''))
@section('content')
<style>
    .nav-grid-btn {
        width: 40px;
        height: 40px;
        margin: 4px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f8f9fa;
        color: #333;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .nav-grid-btn:hover {
        background-color: #e2e6ea;
    }
    .nav-grid-btn.active {
        background-color: #0f3460;
        color: white;
        border-color: #0f3460;
    }
    .nav-grid-btn.answered {
        background-color: #28c76f;
        color: white;
        border-color: #28c76f;
    }
    .nav-grid-btn.active.answered {
        background-color: #0d2b4e;
        border-color: #0d2b4e;
    }
    .indicator-card {
        display: none;
    }
    .indicator-card.active {
        display: block;
    }
    .rubrik-item:hover {
        background-color: #f8f9fa;
    }
    /* === Bundle Styles === */
    .bundle {
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }
    .bundle::before {
        content: '';
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 4px;
    }
    .bundle-auditee {
        background: linear-gradient(135deg, #f8f7ff 0%, #f0efff 100%);
        border: 1px solid rgba(115, 103, 240, 0.15);
        box-shadow: 0 2px 10px rgba(115, 103, 240, 0.06);
    }
    .bundle-auditee::before {
        background: linear-gradient(180deg, #7367f0, #9e95f5);
    }
    .bundle-auditor {
        background: linear-gradient(135deg, #f4fdf7 0%, #edfbf1 100%);
        border: 1px solid rgba(40, 199, 111, 0.15);
        box-shadow: 0 2px 10px rgba(40, 199, 111, 0.06);
    }
    .bundle-auditor::before {
        background: linear-gradient(180deg, #28c76f, #48da89);
    }
    .bundle-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }
    .bundle-header .bundle-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
    }
    .bundle-auditee .bundle-icon {
        background: linear-gradient(135deg, #7367f0, #9e95f5);
    }
    .bundle-auditor .bundle-icon {
        background: linear-gradient(135deg, #28c76f, #48da89);
    }
    .bundle-header .bundle-title {
        font-weight: 700;
        font-size: 0.95rem;
    }
    .bundle-auditee .bundle-title { color: #5e50ee; }
    .bundle-auditor .bundle-title { color: #1e9c50; }
    .bundle-sub {
        background: #fff;
        border-radius: 8px;
        padding: 14px 16px;
        margin-bottom: 12px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .bundle-sub:last-child { margin-bottom: 0; }
    .bundle-sub-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .bundle-auditee .bundle-sub-label { color: #7367f0; }
    .bundle-auditor .bundle-sub-label { color: #28c76f; }
    .bundle-sub-content {
        font-size: 0.92rem;
        line-height: 1.65;
        color: #4a4a4a;
    }
    .bundle-file-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        background: #f8f7ff;
        border-radius: 6px;
        border: 1px solid rgba(115, 103, 240, 0.1);
        transition: all 0.2s ease;
        text-decoration: none;
        color: #5e50ee;
        overflow: hidden;
        min-width: 0;
    }
    .bundle-file-item span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        min-width: 0;
    }
    .bundle-file-item:hover {
        background: #ece9fe;
        border-color: #7367f0;
        color: #7367f0;
        transform: translateX(3px);
    }
    .bundle-file-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: linear-gradient(135deg, #e8e6fd, #d4d0fb);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7367f0;
        font-size: 14px;
        flex-shrink: 0;
    }
    .bundle-empty {
        text-align: center;
        padding: 14px;
        color: #aaa;
        font-style: italic;
        font-size: 0.88rem;
    }
    /* Scoring Items (inside bundle-auditor) */
    .skor-rubrik-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 16px;
        background: #fff;
        border-radius: 8px;
        border: 2px solid #e8e8e8;
        cursor: pointer;
        transition: all 0.25s ease;
        margin-bottom: 8px;
    }
    .skor-rubrik-item:last-child {
        margin-bottom: 0;
    }
    .skor-rubrik-item:hover {
        border-color: #28c76f;
        background: #f9fefb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(40, 199, 111, 0.1);
    }
    .skor-rubrik-item.selected {
        border-color: #28c76f;
        background: linear-gradient(135deg, #f0fbf4, #e6f9ed);
        box-shadow: 0 2px 10px rgba(40, 199, 111, 0.12);
    }
    .skor-rubrik-item input[type="radio"] {
        width: 20px;
        height: 20px;
        margin-top: 2px;
        accent-color: #28c76f;
        flex-shrink: 0;
    }
    .skor-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.78rem;
        background: linear-gradient(135deg, #28c76f, #48da89);
        color: #fff;
    }
    .skor-rubrik-item .skor-desc {
        color: #666;
        font-size: 0.88rem;
        line-height: 1.55;
        margin-top: 4px;
    }
    .btn-clear-skor {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 14px;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        background: #fff;
        color: #999;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 10px;
    }
    .btn-clear-skor:hover {
        border-color: #ea5455;
        color: #ea5455;
        background: #fff5f5;
    }
    /* Navigation Buttons */
    .nav-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }
    .btn-nav {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.88rem;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .btn-nav-prev {
        background: #f0f0f5;
        color: #555;
    }
    .btn-nav-prev:hover:not(:disabled) {
        background: #e2e2ea;
        color: #333;
        transform: translateX(-2px);
    }
    .btn-nav-next {
        background: linear-gradient(135deg, #7367f0, #9e95f5);
        color: #fff;
    }
    .btn-nav-next:hover:not(:disabled) {
        box-shadow: 0 4px 14px rgba(115, 103, 240, 0.35);
        transform: translateX(2px);
    }
    .btn-nav-finish {
        background: linear-gradient(135deg, #28c76f, #48da89);
        color: #fff;
    }
    .btn-nav-finish:hover {
        box-shadow: 0 4px 14px rgba(40, 199, 111, 0.35);
    }
    .btn-nav:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }
    @media (max-width: 576px) {
        .nav-footer {
            flex-direction: row;
        }
        .btn-nav {
            flex: 1;
            justify-content: center;
            padding: 12px 10px;
            font-size: 0.84rem;
        }
        .btn-nav .btn-nav-text {
            display: none;
        }
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.asesmen.index') }}">Asesmen Auditor</a> /</span>
            {{ $sk->unit ? $sk->unit->nama : '-' }}
        </h4>
    </div>
</div>

<div class="row">
    <!-- Left Column: Navigation Grid -->
    <div class="col-md-3 order-md-2 mb-4">
        <div class="card sticky-top" style="top: 20px; z-index: 1;">
            <div class="card-header border-bottom">
                <h6 class="m-0">Navigasi Indikator</h6>
            </div>
            <div class="card-body pt-3">
                <div class="d-flex flex-wrap justify-content-start" id="nav-grid">
                    @foreach($indikators as $idx => $indikator)
                    <div class="nav-grid-btn" id="nav-btn-{{ $idx }}" onclick="jumpTo({{ $idx }})">
                        {{ $idx + 1 }}
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 small">
                    <div class="d-flex align-items-center mb-1">
                        <span class="badge bg-white text-dark border me-2" style="width:20px; height:20px;"> </span> Belum Dinilai
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <span class="badge bg-success me-2" style="width:20px; height:20px;"> </span> Sudah Dinilai
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge me-2" style="background-color: #0f3460; width:20px; height:20px;"> </span> Sedang Dibuka
                    </div>
                </div>
            </div>
            @if($canFinalize && $sk->status == 'aktif')
            <div class="card-footer border-top">
                <button class="btn btn-warning w-100" id="btn-finalize">
                    <i class="ti ti-lock me-1"></i>Finalisasi
                </button>
            </div>
            @endif
        </div>
        
        <div class="card mt-3">
             <div class="card-body">
                <div id="progress-text" class="fw-bold mb-1">Progress: 0/0</div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" id="progress-bar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Question Area -->
    <div class="col-md-9 order-md-1">
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                     <h5 class="card-title mb-0">{{ $sk->nomor_sk }}</h5>
                     <small class="text-muted">{{ $sk->periode ? $sk->periode->nama : '' }}</small>
                </div>
                 @if(!$canEdit)
                <div class="alert alert-info py-1 px-3 mb-0 fs-6">
                    <i class="ti ti-info-circle me-1"></i> Mode Baca
                </div>
                @endif
            </div>
        </div>

        @foreach($indikators as $idx => $indikator)
        <div class="indicator-card" id="card-{{ $idx }}">
            <div class="card mb-3">
                <div class="card-header bg-label-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">Indikator {{ $idx + 1 }}</h5>
                        <span class="badge bg-white text-primary">{{ $indikator->kode }}</span>
                    </div>
                </div>
                <div class="card-body pt-4">
                     @php
                         $evDiri = isset($evaluasiDiris[$indikator->id]) ? $evaluasiDiris[$indikator->id] : null;
                         $existing = isset($existingScores[$indikator->id]) ? $existingScores[$indikator->id] : null;
                         $currentSkor = $existing ? explode(',', $existing->skor_pilihan) : [];
                     @endphp

                     <!-- ===== BUNDLE: Isian Auditee ===== -->
                     <div class="bundle bundle-auditee">
                        <div class="bundle-header">
                            <div class="bundle-icon"><i class="ti ti-user-check"></i></div>
                            <div>
                                <div class="bundle-title">Isian Auditee</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Data evaluasi diri dari unit yang diaudit</small>
                            </div>
                        </div>

                        <!-- Pertanyaan / Naskah -->
                        <div class="bundle-sub">
                            <div class="bundle-sub-label"><i class="ti ti-notes"></i> {{ $indikator->narasi_evaluasi_diri ? 'Naskah Evaluasi Diri' : 'Pertanyaan Indikator' }}</div>
                            <div class="bundle-sub-content">
                                @if($indikator->narasi_evaluasi_diri)
                                    {!! nl2br(e($indikator->narasi_evaluasi_diri)) !!}
                                @else
                                    {{ $indikator->pertanyaan }}
                                @endif
                            </div>
                        </div>

                        <!-- Jawaban Auditee -->
                        <div class="bundle-sub">
                            <div class="bundle-sub-label"><i class="ti ti-message-dots"></i> Jawaban Auditee</div>
                            @if($evDiri && $evDiri->jawaban)
                            <div class="bundle-sub-content">{!! strip_tags($evDiri->jawaban, '<table><thead><tbody><tr><th><td><div><a><p><ul><ol><li><img><strong><em><b><i><u><br><span><h1><h2><h3><h4><h5><h6><blockquote><pre><code><hr><sub><sup>') !!}</div>
                            @else
                            <div class="bundle-empty"><i class="ti ti-clipboard-off me-1"></i>Belum ada jawaban</div>
                            @endif
                        </div>

                        <!-- Dokumen Pendukung -->
                        <div class="bundle-sub">
                            <div class="bundle-sub-label"><i class="ti ti-paperclip"></i> Dokumen Pendukung {{ $evDiri && $evDiri->files && $evDiri->files->count() > 0 ? '('.$evDiri->files->count().')' : '' }}</div>
                            @if($evDiri && $evDiri->files && $evDiri->files->count() > 0)
                            <div class="d-flex flex-column gap-2">
                                @foreach($evDiri->files as $file)
                                <a href="{{ asset($file->file_path) }}" target="_blank" class="bundle-file-item">
                                    <div class="bundle-file-icon"><i class="ti ti-file-text"></i></div>
                                    <span class="flex-grow-1 small fw-medium">{{ $file->file_name }}</span>
                                    <i class="ti ti-external-link" style="font-size: 14px; opacity: 0.5;"></i>
                                </a>
                                @endforeach
                            </div>
                            @else
                            <div class="bundle-empty"><i class="ti ti-file-off me-1"></i>Tidak ada dokumen</div>
                            @endif
                        </div>
                     </div>

                     <!-- ===== BUNDLE: Isian Auditor ===== -->
                     <div class="bundle bundle-auditor">
                        <div class="bundle-header">
                            <div class="bundle-icon"><i class="ti ti-shield-check"></i></div>
                            <div>
                                <div class="bundle-title">Isian Auditor</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Penilaian dan catatan dari tim auditor</small>
                            </div>
                        </div>

                        <!-- Indikator (Penentuan Nilai) -->
                        <div class="bundle-sub">
                            <div class="bundle-sub-label"><i class="ti ti-target"></i> Indikator Penilaian</div>
                            <div class="bundle-sub-content fw-medium">{{ $indikator->pertanyaan }}</div>
                        </div>

                        <!-- Skor -->
                        <div class="bundle-sub" style="background: transparent; border: none; padding: 0;">
                            <div class="bundle-sub-label" style="padding: 0 16px;"><i class="ti ti-star"></i> Skor Penilaian <span class="text-danger">*</span></div>
                            <div class="mt-2">
                                @foreach($indikator->rubrikSkors as $rubrik)
                                <label class="skor-rubrik-item {{ in_array($rubrik->skor, $currentSkor) ? 'selected' : '' }}">
                                    <input class="form-check-input skor-radio" 
                                        type="radio" 
                                        name="skor_{{ $indikator->id }}"
                                        value="{{ $rubrik->skor }}"
                                        data-indikator-id="{{ $indikator->id }}"
                                        data-index="{{ $idx }}"
                                        {{ in_array($rubrik->skor, $currentSkor) ? 'checked' : '' }}
                                        {{ !$canEdit ? 'disabled' : '' }}>
                                    <div class="flex-grow-1">
                                        <span class="skor-badge"><i class="ti ti-star-filled" style="font-size: 12px;"></i> Skor {{ $rubrik->skor }}</span>
                                        <div class="skor-desc">{{ $rubrik->deskripsi }}</div>
                                    </div>
                                </label>
                                @endforeach
                                @if($canEdit)
                                <button type="button" class="btn-clear-skor btn-clear-selection" data-indikator-id="{{ $indikator->id }}" data-index="{{ $idx }}">
                                    <i class="ti ti-x"></i> Batalkan Pilihan
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Catatan Asesor -->
                        <div class="bundle-sub">
                            <div class="bundle-sub-label"><i class="ti ti-writing"></i> Catatan Asesor</div>
                            <textarea class="form-control catatan-input"
                                data-indikator-id="{{ $indikator->id }}"
                                data-index="{{ $idx }}"
                                rows="3"
                                placeholder="Tuliskan catatan untuk auditee..."
                                {{ !$canEdit ? 'readonly' : '' }}>{{ $existing ? $existing->catatan_asesor : '' }}</textarea>
                        </div>
                     </div>

                </div>
                <div class="card-footer">
                    <div class="nav-footer">
                        <button type="button" class="btn-nav btn-nav-prev btn-prev" onclick="prevQuestion()" {{ $idx == 0 ? 'disabled' : '' }}>
                            <i class="ti ti-arrow-left"></i> <span class="btn-nav-text">Sebelumnya</span>
                        </button>
                        
                        @if($idx < count($indikators) - 1)
                        <button type="button" class="btn-nav btn-nav-next btn-next" onclick="nextQuestion()">
                            <span class="btn-nav-text">Selanjutnya</span> <i class="ti ti-arrow-right"></i>
                        </button>
                        @else
                        <a href="{{ route('admin.ami.asesmen.index') }}" class="btn-nav btn-nav-finish">
                            <i class="ti ti-check"></i> <span class="btn-nav-text">Selesai</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    var totalQuestions = {{ count($indikators) }};
    var currentQuestion = 0;
    var statusMap = {}; // store status: 'answered', 'empty'

    // Init Logic
    $(document).ready(function() {
        // Initialize status map
        $('.skor-radio:checked').each(function() {
            var idx = $(this).data('index');
            statusMap[idx] = 'answered';
        });
        
        // Initialize buttons
        for(var i=0; i<totalQuestions; i++) {
            updateGridButton(i);
        }

        // Show first question
        showQuestion(0);
        updateProgress();
    });

    function showQuestion(index) {
        if(index < 0 || index >= totalQuestions) return;

        $('.indicator-card').removeClass('active');
        $('#card-' + index).addClass('active');
        
        $('.nav-grid-btn').removeClass('active');
        $('#nav-btn-' + index).addClass('active');

        currentQuestion = index;
        
        $('html, body').animate({ scrollTop: 0 }, 'fast');

        $('.btn-prev').prop('disabled', false);
        $('.btn-next').prop('disabled', false);
        if(index === 0) $('.btn-prev').prop('disabled', true);
        if(index === totalQuestions - 1) $('.btn-next').prop('disabled', true);
    }

    function nextQuestion() {
        if(currentQuestion < totalQuestions - 1) {
            showQuestion(currentQuestion + 1);
        }
    }

    function prevQuestion() {
        if(currentQuestion > 0) {
            showQuestion(currentQuestion - 1);
        }
    }

    function jumpTo(index) {
        showQuestion(index);
    }

    function updateGridButton(index) {
         var btn = $('#nav-btn-' + index);
         if(statusMap[index] === 'answered') {
             btn.addClass('answered');
         } else {
             btn.removeClass('answered');
         }
    }

    function updateProgress() {
        var answeredCount = 0;
        for(var i=0; i<totalQuestions; i++) {
            if(statusMap[i] === 'answered') answeredCount++;
        }
        var percent = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
        $('#progress-bar').css('width', percent + '%');
        $('#progress-text').text('Progress: ' + answeredCount + '/' + totalQuestions);
    }

    // Save skor on radio change
    $(document).on('change', '.skor-radio', function() {
        var el = $(this);
        var indikatorId = el.data('indikator-id');
        var idx = el.data('index');
        
        // Toggle selected class
        el.closest('.bundle-auditor').find('.skor-rubrik-item').removeClass('selected');
        el.closest('.skor-rubrik-item').addClass('selected');
        
        var selectedScores = [el.val()];
        
        // Update Status
        statusMap[idx] = 'answered';
        updateGridButton(idx);
        updateProgress();
        
        var catatan = $('[data-indikator-id="' + indikatorId + '"].catatan-input').val();
        saveSkor(indikatorId, selectedScores, catatan);
    });

    // Clear selection (batalkan pilihan)
    $(document).on('click', '.btn-clear-selection', function() {
        var indikatorId = $(this).data('indikator-id');
        var idx = $(this).data('index');
        
        // Uncheck all radios for this indikator
        $('input[name="skor_' + indikatorId + '"]').prop('checked', false);
        
        // Remove selected class
        $(this).closest('.bundle-auditor').find('.skor-rubrik-item').removeClass('selected');
        
        // Update Status
        statusMap[idx] = 'empty';
        updateGridButton(idx);
        updateProgress();
        
        var catatan = $('[data-indikator-id="' + indikatorId + '"].catatan-input').val();
        saveSkor(indikatorId, [], catatan);
    });

    // Save catatan on blur
    $(document).on('blur', '.catatan-input', function() {
        var indikatorId = $(this).data('indikator-id');
        var selectedScores = [];
        var checkedRadio = $('input[name="skor_' + indikatorId + '"]:checked');
        if (checkedRadio.length > 0) {
            selectedScores.push(checkedRadio.val());
        }
        var catatan = $(this).val();

        if (selectedScores.length > 0) {
            saveSkor(indikatorId, selectedScores, catatan);
        }
    });

    function saveSkor(indikatorId, skor, catatan) {
        // Prevent saving empty attempts if just browsing? 
        // User might uncheck all, effectively removing score. We should allow keeping 'empty' state but save null?
        // Controller validation requires skor_pilihan to be array.
        // If empty, backend might fail `min:1`.
        // Let's check `AmiAsesmenController` validation: 'skor_pilihan.*' => 'integer|min:1'.
        // If array is empty, it passes 'array'? 'required|array'.
        // If empty, it might fail required.
        // If user unchecks all, we might want to delete score? 
        // Current logic: if skor empty, we just update UI to gray, but maybe not saving to backend?
        // If not saved, then on reload it comes back?
        // Let's try to save. If it fails, toastr error.
        
        $.ajax({
            url: "{{ route('admin.ami.asesmen.save-skor') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ami_sk_auditor_id: {{ $sk->id }},
                ami_indikator_id: indikatorId,
                skor_pilihan: skor,
                catatan_asesor: catatan || ''
            },
            success: function(res) {
                if (res.status) {
                    showToastr('success', 'success', 'Skor tersimpan');
                }
            }
        });
    }

    // Finalize
    $('#btn-finalize').on('click', function() {
        Swal.fire({
            title: 'Finalisasi Asesmen?',
            text: 'Setelah finalisasi, asesmen tidak dapat diubah lagi.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, finalisasi!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('admin.ami.asesmen.finalize', $sk->id) }}",
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.status) {
                            showToastr('success', 'success', res.message);
                            setTimeout(function() { window.location.href = "{{ route('admin.ami.asesmen.index') }}"; }, 1500);
                        } else {
                            showToastr('error', 'error', res.message);
                        }
                    }
                });
            }
        });
    });
</script>

@endpush
