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
        background: linear-gradient(135deg, #7367f0, #9e95f5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
    }
    .bundle-header .bundle-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #5e50ee;
    }
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
        color: #7367f0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
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
    .bundle-file-upload {
        margin-top: 10px;
        padding: 12px;
        border: 2px dashed rgba(115, 103, 240, 0.2);
        border-radius: 8px;
        background: #faf9ff;
        transition: border-color 0.2s;
    }
    .bundle-file-upload:hover {
        border-color: rgba(115, 103, 240, 0.4);
    }
    .btn-delete-file-elegant {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        background: #fff;
        color: #bbb;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .btn-delete-file-elegant:hover {
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
        text-decoration: none;
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
    /* Summernote fullscreen z-index fix */
    .note-editor.note-frame.fullscreen {
        z-index: 99999 !important;
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
                     @php
                         $existing = isset($existingAnswers[$indikator->id]) ? $existingAnswers[$indikator->id] : null;
                         $files = isset($existingFiles[$indikator->id]) ? $existingFiles[$indikator->id] : collect();
                     @endphp

                     <!-- ===== BUNDLE: Isian Auditee ===== -->
                     <div class="bundle bundle-auditee">
                        <div class="bundle-header">
                            <div class="bundle-icon"><i class="ti ti-clipboard-check"></i></div>
                            <div>
                                <div class="bundle-title">Isian Evaluasi Diri</div>
                                <small class="text-muted" style="font-size: 0.75rem;">Lengkapi jawaban dan dokumen pendukung</small>
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

                        <!-- Jawaban -->
                        <div class="bundle-sub">
                            <div class="bundle-sub-label"><i class="ti ti-message-dots"></i> Jawaban Evaluasi Diri <span class="text-danger">*</span></div>
                            <textarea class="summernote-jawaban jawaban-input"
                                data-index="{{ $idx }}"
                                data-indikator-id="{{ $indikator->id }}"
                                >{{ $existing ? $existing->jawaban : '' }}</textarea>
                        </div>

                        <!-- Dokumen Pendukung -->
                        <div class="bundle-sub">
                            <div class="bundle-sub-label"><i class="ti ti-paperclip"></i> Dokumen Pendukung {{ $files->count() > 0 ? '('.$files->count().')' : '' }}</div>
                            <div id="file-list-{{ $indikator->id }}">
                                @if($files->count() > 0)
                                <div class="d-flex flex-column gap-2 mb-2">
                                    @foreach($files as $file)
                                    <div class="bundle-file-item" id="file-item-{{ $file->id }}">
                                        <div class="bundle-file-icon"><i class="ti ti-file-text"></i></div>
                                        <a href="{{ asset($file->file_path) }}" target="_blank" class="flex-grow-1 small fw-medium text-decoration-none" style="color: #5e50ee; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $file->file_name }}</a>
                                        <i class="ti ti-external-link" style="font-size: 14px; opacity: 0.4;"></i>
                                        @if($canEdit)
                                        <button type="button" class="btn-delete-file-elegant btn-delete-file" data-file-id="{{ $file->id }}">
                                            <i class="ti ti-trash" style="font-size: 13px;"></i>
                                        </button>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="bundle-empty"><i class="ti ti-file-off me-1"></i>Belum ada dokumen</div>
                                @endif
                            </div>
                            @if($canEdit)
                            <div class="bundle-file-upload">
                                <input type="file" class="form-control form-control-sm file-upload-input"
                                    data-indikator-id="{{ $indikator->id }}" data-sk-id="{{ $sk->id }}" multiple>
                                <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;"><i class="ti ti-info-circle me-1"></i>Upload dokumen pendukung (PDF, DOC, JPG, PNG)</small>
                            </div>
                            @endif
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
                        <a href="{{ route('admin.ami.evaluasi-diri.index') }}" class="btn-nav btn-nav-finish">
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
        // Initialize summernote editors
        $('.summernote-jawaban').each(function() {
            var $el = $(this);
            $el.summernote({
                placeholder: 'Tuliskan jawaban evaluasi diri...',
                tabsize: 2,
                height: 200,
                codeviewFilter: false,
                codeviewIframeFilter: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ],
                callbacks: {
                    onBlur: function() {
                        var $textarea = $(this);
                        var indikatorId = $textarea.data('indikator-id');
                        var idx = $textarea.data('index');
                        var jawaban = $textarea.summernote('code');
                        var plainText = $textarea.summernote('isEmpty') ? '' : jawaban;

                        statusMap[idx] = plainText ? 'answered' : 'empty';
                        updateGridButton(idx);
                        updateProgress();

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
                    }
                }
            });

            @if(!$canEdit)
            $el.summernote('disable');
            @endif
        });

        // Initialize status map based on existing values
        $('.jawaban-input').each(function() {
            var idx = $(this).data('index');
            var isEmpty = $(this).summernote('isEmpty');
            statusMap[idx] = !isEmpty ? 'answered' : 'empty';
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

    // (Summernote onBlur handles auto-save - see init above)

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
