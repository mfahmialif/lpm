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
                     <!-- Pertanyaan -->
                     <div class="mb-4">
                        <p class="fs-5 fw-medium">{{ $indikator->pertanyaan }}</p>
                    </div>

                    <!-- Evaluasi Diri Section Removed -->

                    @php
                        $existing = isset($existingScores[$indikator->id]) ? $existingScores[$indikator->id] : null;
                        $currentSkor = $existing ? explode(',', $existing->skor_pilihan) : [];
                    @endphp

                    <!-- Scoring Section -->
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-6 text-dark">Penilaian Auditor <span class="text-danger">*</span></label>
                        <div class="list-group">
                            @foreach($indikator->rubrikSkors as $rubrik)
                            <label class="list-group-item list-group-item-action rubrik-item d-flex align-items-center cursor-pointer">
                                <input class="form-check-input me-3 skor-checkbox" 
                                    type="checkbox" 
                                    name="skor_{{ $indikator->id }}[]"
                                    value="{{ $rubrik->skor }}"
                                    data-indikator-id="{{ $indikator->id }}"
                                    data-index="{{ $idx }}"
                                    {{ in_array($rubrik->skor, $currentSkor) ? 'checked' : '' }}
                                    {{ !$canEdit ? 'disabled' : '' }}>
                                <div class="flex-grow-1">
                                    <span class="badge bg-primary rounded-pill mb-1">Skor {{ $rubrik->skor }}</span>
                                    <div class="text-muted">{{ $rubrik->deskripsi }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="mb-3">
                         <label class="form-label fw-bold">Catatan Asesor</label>
                         <textarea class="form-control catatan-input"
                            data-indikator-id="{{ $indikator->id }}"
                            data-index="{{ $idx }}"
                            rows="3"
                            placeholder="Tuliskan catatan untuk auditee..."
                            {{ !$canEdit ? 'readonly' : '' }}>{{ $existing ? $existing->catatan_asesor : '' }}</textarea>
                    </div>

                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-prev" onclick="prevQuestion()" {{ $idx == 0 ? 'disabled' : '' }}>
                        <i class="ti ti-arrow-left me-1"></i> Sebelumnya
                    </button>
                    
                    @if($idx < count($indikators) - 1)
                    <button type="button" class="btn btn-primary btn-next" onclick="nextQuestion()">
                        Selanjutnya <i class="ti ti-arrow-right ms-1"></i>
                    </button>
                    @else
                    <a href="{{ route('admin.ami.asesmen.index') }}" class="btn btn-success">
                        <i class="ti ti-check me-1"></i> Selesai
                    </a>
                    @endif
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
        $('.skor-checkbox:checked').each(function() {
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

    // Save skor on checkbox change
    $(document).on('change', '.skor-checkbox', function() {
        var el = $(this);
        var indikatorId = el.data('indikator-id');
        var idx = el.data('index');
        
        var selectedScores = [];
        $('input[name="skor_' + indikatorId + '[]"]:checked').each(function() {
            selectedScores.push($(this).val());
        });
        
        // Update Status
        if(selectedScores.length > 0) {
            statusMap[idx] = 'answered';
        } else {
            statusMap[idx] = 'empty';
        }
        updateGridButton(idx);
        updateProgress();
        
        var catatan = $('[data-indikator-id="' + indikatorId + '"].catatan-input').val();
        saveSkor(indikatorId, selectedScores, catatan);
    });

    // Save catatan on blur
    $(document).on('blur', '.catatan-input', function() {
        var indikatorId = $(this).data('indikator-id');
        var selectedScores = [];
        $('input[name="skor_' + indikatorId + '[]"]:checked').each(function() {
            selectedScores.push($(this).val());
        });
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
