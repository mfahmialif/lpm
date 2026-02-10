@extends('layouts.admin.template')
@section('title', 'Evaluasi Diri - ' . ($sk->unit ? $sk->unit->nama : ''))
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
        background-color: #0d2b4e; /* Darker blue mixed with green implication */
        border-color: #0d2b4e;
    }
    .indicator-card {
        display: none;
    }
    .indicator-card.active {
        display: block;
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">AMI / <a href="{{ route('admin.ami.evaluasi-diri.index') }}">Evaluasi Diri</a> /</span>
            {{ $sk->unit ? $sk->unit->nama : '-' }}
        </h4>
    </div>
</div>

<div class="row">
    <!-- Left Column: Navigation Grid -->
    <div class="col-md-3 order-md-2 mb-4">
        <div class="card sticky-top" style="top: 20px; z-index: 1;">
            <div class="card-header border-bottom">
                <h6 class="m-0">Navigasi Soal</h6>
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
                        <span class="badge bg-white text-dark border me-2" style="width:20px; height:20px;"> </span> Belum Dijawab
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <span class="badge bg-success me-2" style="width:20px; height:20px;"> </span> Sudah Dijawab
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge me-2" style="background-color: #0f3460; width:20px; height:20px;"> </span> Sedang Dibuka
                    </div>
                </div>
            </div>
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
                     <div class="mb-4">
                        @if($indikator->narasi_evaluasi_diri)
                            {!! nl2br(e($indikator->narasi_evaluasi_diri)) !!}
                        @else
                            <p class="fs-5">{{ $indikator->pertanyaan }}</p>
                        @endif
                    </div>

                    @php
                        $existing = isset($existingAnswers[$indikator->id]) ? $existingAnswers[$indikator->id] : null;
                    @endphp

                    <div class="mb-4">
                        <label class="form-label fw-bold">Jawaban Evaluasi Diri <span class="text-danger">*</span></label>
                        <textarea class="form-control jawaban-input"
                            data-index="{{ $idx }}"
                            data-indikator-id="{{ $indikator->id }}"
                            rows="6"
                            placeholder="Tuliskan jawaban evaluasi diri..."
                            {{ !$canEdit ? 'readonly' : '' }}>{{ $existing ? $existing->jawaban : '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Dokumen Pendukung</label>
                        @php
                            $files = isset($existingFiles[$indikator->id]) ? $existingFiles[$indikator->id] : collect();
                        @endphp
                        
                        <div id="file-list-{{ $indikator->id }}" class="mb-2">
                            @if($files->count() > 0)
                            <ul class="list-group">
                                @foreach($files as $file)
                                <li class="list-group-item d-flex justify-content-between align-items-center" id="file-item-{{ $file->id }}">
                                    <a href="{{ asset($file->file_path) }}" target="_blank">
                                        <i class="ti ti-file me-1"></i>{{ $file->file_name }}
                                    </a>
                                    @if($canEdit)
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete-file" data-file-id="{{ $file->id }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>

                        @if($canEdit)
                        <input type="file" class="form-control file-upload-input"
                            data-indikator-id="{{ $indikator->id }}" data-sk-id="{{ $sk->id }}" multiple>
                        <small class="text-muted">Upload dokumen pendukung (PDF, DOC, JPG, PNG)</small>
                        @endif
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
                    <a href="{{ route('admin.ami.evaluasi-diri.index') }}" class="btn btn-success">
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
        // Initialize status map based on existing values
        $('.jawaban-input').each(function() {
            var idx = $(this).data('index');
            var val = $(this).val();
            statusMap[idx] = val && val.trim() !== '' ? 'answered' : 'empty';
            updateGridButton(idx);
        });

        // Show first question
        showQuestion(0);
        updateProgress();
    });

    function showQuestion(index) {
        if(index < 0 || index >= totalQuestions) return;

        // Hide all cards
        $('.indicator-card').removeClass('active');
        // Show target card
        $('#card-' + index).addClass('active');
        
        // Update nav buttons
        $('.nav-grid-btn').removeClass('active');
        $('#nav-btn-' + index).addClass('active');

        currentQuestion = index;
        
        // Scroll to top of question to ensure visibility
        $('html, body').animate({ scrollTop: 0 }, 'fast');

        // Update button disabled states
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

    // Auto-save jawaban on blur
    $(document).on('blur', '.jawaban-input', function() {
        var el = $(this);
        var indikatorId = el.data('indikator-id');
        var idx = el.data('index');
        var jawaban = el.val();
        
        // Update local status immediately for UI feedback
        statusMap[idx] = jawaban && jawaban.trim() !== '' ? 'answered' : 'empty';
        updateGridButton(idx);
        updateProgress();

        // Always save, even if empty (to allow clearing answer)
        $.ajax({
            url: "{{ route('admin.ami.evaluasi-diri.save-jawaban') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ami_sk_auditor_id: {{ $sk->id }},
                ami_indikator_id: indikatorId,
                jawaban: jawaban
            },
            success: function(res) {
                if (res.status) {
                    showToastr('success', 'success', 'Jawaban tersimpan');
                }
            }
        });
    });

    // File upload
    $(document).on('change', '.file-upload-input', function() {
        var el = $(this);
        var formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('ami_sk_auditor_id', {{ $sk->id }});
        formData.append('ami_indikator_id', el.data('indikator-id'));
        
        var files = el[0].files;
        for (var i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }

        $.ajax({
            url: "{{ route('admin.ami.evaluasi-diri.upload-file') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                if (res.status) {
                    showToastr('success', 'success', 'File berhasil diupload');
                     
                    // Clear input
                    el.val('');

                    // Construct new list items
                    var listContainer = $('#file-list-' + el.data('indikator-id'));
                    var ul = listContainer.find('ul');
                     
                    // If no ul exists yet, create one
                    if(ul.length === 0) {
                        listContainer.html('<ul class="list-group mb-2"></ul>');
                        ul = listContainer.find('ul');
                    }

                    // Append files
                    if (res.files && res.files.length > 0) {
                        res.files.forEach(function(file) {
                            // Use base asset url
                            var assetUrl = "{{ asset('') }}" + file.file_path;
                            
                            var li = `
                                <li class="list-group-item d-flex justify-content-between align-items-center" id="file-item-${file.id}">
                                    <a href="${assetUrl}" target="_blank">
                                        <i class="ti ti-file me-1"></i>${file.file_name}
                                    </a>
                                    @if($canEdit)
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-delete-file" data-file-id="${file.id}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                    @endif
                                </li>
                            `;
                            ul.append(li);
                        });
                    } else {
                        // Fallback if no files returned (e.g. older controller version) but status is true
                        location.reload();
                    }
                } else {
                    showToastr('error', 'error', res.message);
                }
            }
        });
    });

    // Delete file
    $(document).on('click', '.btn-delete-file', function() {
        var fileId = $(this).data('file-id');
        if(!confirm('Hapus file ini?')) return;
        $.ajax({
            url: "{{ route('admin.ami.evaluasi-diri.delete-file') }}",
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}', file_id: fileId },
            success: function(res) {
                if (res.status) {
                    showToastr('success', 'success', 'File dihapus');
                    $('#file-item-' + fileId).fadeOut(function() { 
                        $(this).remove(); 
                    });
                }
            }
        });
    });
</script>
@endpush
