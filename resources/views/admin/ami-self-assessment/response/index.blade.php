@extends('layouts.admin.template')
@section('title', 'Respon Asesmen Diri')
@push('styles')
<style>
    .chat-page-wrapper {
        max-width: 900px;
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

    .chat-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .chat-header {
        background: #fff;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .chat-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .chat-header-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .chat-header-info h5 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    .chat-header-info p {
        margin: 0;
        font-size: 0.8rem;
        color: #6b7280;
    }

    .chat-header-meta {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .meta-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.7rem;
        padding: 0.25rem 0.625rem;
        border-radius: 1rem;
        font-weight: 500;
    }

    .meta-tag.tag-period {
        background: #ede9fe;
        color: #7c3aed;
    }

    .meta-tag.tag-unit {
        background: #dbeafe;
        color: #2563eb;
    }

    .meta-tag.tag-auditee {
        background: #dcfce7;
        color: #16a34a;
    }

    /* Mobile responsive */
    @media (max-width: 576px) {
        .chat-page-wrapper {
            padding: 0;
        }

        .back-link {
            margin-left: 1rem;
        }

        .chat-card {
            border-radius: 0;
        }

        .chat-header {
            padding: 0.875rem 1rem;
        }

        .chat-header-icon {
            display: none;
        }

        .chat-header-info h5 {
            font-size: 0.875rem;
            margin-bottom: 0.375rem;
        }

        .chat-header-meta {
            gap: 0.375rem;
        }

        .meta-tag {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }

        .chat-body {
            height: calc(100vh - 300px);
            padding: 1rem;
        }

        .chat-footer {
            padding: 0.75rem 1rem;
        }

        .msg-content {
            max-width: 85%;
        }
    }

    .chat-body {
        height: 450px;
        overflow-y: auto;
        padding: 1.5rem;
        background: #f8fafc;
    }

    .chat-message {
        display: flex;
        margin-bottom: 1.25rem;
        opacity: 0;
        transform: translateY(10px);
        animation: messageSlideIn 0.3s ease forwards;
    }

    @keyframes messageSlideIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chat-message.msg-self {
        justify-content: flex-end;
    }

    .chat-message.msg-other {
        justify-content: flex-start;
    }

    .msg-content {
        max-width: 75%;
    }

    .msg-sender {
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .chat-message.msg-other .msg-sender {
        color: #6366f1;
    }

    .chat-message.msg-self .msg-sender {
        color: #8b5cf6;
        justify-content: flex-end;
    }

    .admin-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #fff;
        font-size: 0.65rem;
        font-weight: 600;
        padding: 0.15rem 0.5rem;
        border-radius: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
    }

    .admin-badge i {
        font-size: 0.7rem;
    }

    .msg-bubble {
        padding: 0.875rem 1.125rem;
        border-radius: 1rem;
        position: relative;
        word-wrap: break-word;
    }

    .chat-message.msg-other .msg-bubble {
        background: #fff;
        color: #1f2937;
        border-bottom-left-radius: 0.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .chat-message.msg-self .msg-bubble {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: #fff;
        border-bottom-right-radius: 0.25rem;
        box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
    }

    .msg-text {
        font-size: 0.9rem;
        line-height: 1.5;
        margin: 0;
    }

    .msg-attachment {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.875rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        text-decoration: none;
        margin-top: 0.5rem;
        transition: all 0.2s;
    }

    .chat-message.msg-other .msg-attachment {
        background: #f1f5f9;
        color: #475569;
    }

    .chat-message.msg-other .msg-attachment:hover {
        background: #e2e8f0;
    }

    .chat-message.msg-self .msg-attachment {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .chat-message.msg-self .msg-attachment:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .msg-info {
        font-size: 0.7rem;
        margin-top: 0.375rem;
        opacity: 0.7;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chat-message.msg-self .msg-info {
        justify-content: flex-end;
    }

    .msg-actions {
        display: flex;
        gap: 0.25rem;
        margin-left: 0.5rem;
    }

    .msg-actions .btn-action {
        background: none;
        border: none;
        padding: 0.125rem 0.25rem;
        cursor: pointer;
        opacity: 0.5;
        transition: all 0.2s;
        font-size: 0.75rem;
        border-radius: 0.25rem;
    }

    .msg-actions .btn-action:hover {
        opacity: 1;
    }

    .msg-actions .btn-edit {
        color: #3b82f6;
    }

    .msg-actions .btn-edit:hover {
        background: #dbeafe;
    }

    .msg-actions .btn-delete {
        color: #ef4444;
    }

    .msg-actions .btn-delete:hover {
        background: #fee2e2;
    }

    .chat-footer {
        padding: 1rem 1.5rem;
        background: #fff;
        border-top: 1px solid #e5e7eb;
    }

    .file-preview {
        display: none;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: #fef3c7;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        font-size: 0.85rem;
        color: #92400e;
    }

    .file-preview.active {
        display: flex;
    }

    .file-preview .file-icon {
        width: 36px;
        height: 36px;
        background: #fbbf24;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #78350f;
    }

    .file-preview .file-name {
        flex: 1;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-preview .file-remove {
        width: 28px;
        height: 28px;
        border: none;
        background: #fecaca;
        color: #dc2626;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .file-preview .file-remove:hover {
        background: #fca5a5;
    }

    .input-row {
        display: flex;
        gap: 0.75rem;
        align-items: flex-end;
    }

    .input-row .btn-attach {
        width: 44px;
        height: 44px;
        border: 2px solid #e5e7eb;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: #6b7280;
    }

    .input-row .btn-attach:hover {
        border-color: #8b5cf6;
        color: #8b5cf6;
        background: #f5f3ff;
    }

    .input-row .msg-input {
        flex: 1;
        border: 2px solid #e5e7eb;
        border-radius: 1.5rem;
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        resize: none;
        outline: none;
        transition: all 0.2s;
        max-height: 120px;
    }

    .input-row .msg-input:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .input-row .btn-send {
        width: 44px;
        height: 44px;
        border: none;
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: #fff;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    }

    .input-row .btn-send:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(139, 92, 246, 0.5);
    }

    .input-row .btn-send:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .empty-chat {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        text-align: center;
        color: #9ca3af;
    }

    .empty-chat-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .empty-chat-icon i {
        font-size: 2rem;
        color: #6366f1;
    }

    .empty-chat-title {
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .empty-chat-text {
        font-size: 0.875rem;
    }

    /* Custom scrollbar */
    .chat-body::-webkit-scrollbar {
        width: 6px;
    }

    .chat-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .chat-body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Custom SweetAlert Styles */
    .swal2-popup {
        border-radius: 1rem !important;
        padding: 1.5rem !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
    }

    .swal2-title {
        font-size: 1.125rem !important;
        font-weight: 600 !important;
        color: #1f2937 !important;
        padding: 0 0 0.75rem 0 !important;
    }

    .swal2-html-container {
        font-size: 0.875rem !important;
        color: #6b7280 !important;
        margin: 0 !important;
        padding: 0 0 1rem 0 !important;
    }

    .swal2-textarea {
        border: 2px solid #e5e7eb !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.875rem !important;
        transition: all 0.2s !important;
        min-height: 80px !important;
    }

    .swal2-textarea:focus {
        border-color: #8b5cf6 !important;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1) !important;
        outline: none !important;
    }

    .swal2-actions {
        margin-top: 0.5rem !important;
        gap: 0.5rem !important;
    }

    .swal2-confirm.btn-primary {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important;
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 1.25rem !important;
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3) !important;
    }

    .swal2-confirm.btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 1.25rem !important;
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3) !important;
    }

    .swal2-cancel.btn-secondary {
        background: #f3f4f6 !important;
        color: #4b5563 !important;
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 1.25rem !important;
        font-size: 0.8rem !important;
        font-weight: 500 !important;
    }

    .swal2-cancel.btn-secondary:hover {
        background: #e5e7eb !important;
    }

    .swal2-icon {
        width: 50px !important;
        height: 50px !important;
        margin: 0 auto 0.75rem !important;
        border-width: 3px !important;
    }

    .swal2-icon .swal2-icon-content {
        font-size: 2rem !important;
    }

</style>
@endpush
@section('content')
<div class="chat-page-wrapper">
    <a href="{{ route('admin.ami-self-assessment.index') }}" class="back-link">
        <i class="ti ti-arrow-left"></i>
        Kembali ke Daftar Asesmen
    </a>

    <!-- Assessment Guide Alert -->
    @if($amiSelfAssessment->assessment_guide)
    <div class="alert alert-info mb-3 d-flex align-items-start" role="alert">
        <i class="ti ti-info-circle me-2 mt-1"></i>
        <div>
            <strong>Panduan Asesmen:</strong><br>
            {!! nl2br(e($amiSelfAssessment->assessment_guide)) !!}
        </div>
    </div>
    @endif

    <div class="chat-card">
        <!-- Header -->
        <div class="chat-header">
            <div class="chat-header-left">
                <div class="chat-header-icon">
                    <i class="ti ti-message-circle"></i>
                </div>
                <div class="chat-header-info">
                    <h5>Respon Asesmen</h5>
                    <div class="chat-header-meta">
                        <span class="meta-tag tag-period">
                            <i class="ti ti-calendar"></i>
                            {{ $amiSelfAssessment->amiPeriod->year ?? '-' }}
                        </span>
                        <span class="meta-tag tag-unit">
                            <i class="ti ti-building"></i>
                            {{ $amiSelfAssessment->prodiUnit->nama ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Body -->
        <div class="chat-body" id="chat-body">
            <div class="empty-chat" id="empty-state">
                <div class="empty-chat-icon">
                    <i class="ti ti-messages"></i>
                </div>
                <div class="empty-chat-title">Belum ada percakapan</div>
                <div class="empty-chat-text">Mulai kirim pesan untuk memulai diskusi</div>
            </div>
            <div id="messages-container"></div>
        </div>

        <!-- Footer -->
        <div class="chat-footer">
            <form id="chat-form">
                @csrf
                <div class="file-preview" id="file-preview">
                    <div class="file-icon">
                        <i class="ti ti-file"></i>
                    </div>
                    <span class="file-name" id="file-name"></span>
                    <button type="button" class="file-remove" id="file-remove">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="input-row">
                    <input type="file" id="file-input" name="attachment" style="display:none">
                    <button type="button" class="btn-attach" id="btn-attach" title="Lampirkan File">
                        <i class="ti ti-paperclip ti-md"></i>
                    </button>
                    <textarea class="msg-input" id="msg-input" name="message" rows="1" placeholder="Ketik pesan Anda..."></textarea>
                    <button type="submit" class="btn-send" id="btn-send" title="Kirim">
                        <i class="ti ti-send ti-md"></i>
                    </button>
                </div>
                <div class="text-center mt-2" style="font-size: 0.75rem; color: #6b7280;">Tekan <kbd style="background:#374151;color:#fff;padding:0.15rem 0.4rem;border-radius:0.25rem;font-size:0.7rem;font-weight:500;">Ctrl</kbd> + <kbd style="background:#374151;color:#fff;padding:0.15rem 0.4rem;border-radius:0.25rem;font-size:0.7rem;font-weight:500;">.</kbd> untuk fokus ke chat</div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        var chatBody = $('#chat-body');
        var messagesContainer = $('#messages-container');
        var emptyState = $('#empty-state');

        // Ctrl + . shortcut to focus on chat input
        $(document).on('keydown', function(e) {
            if (e.ctrlKey && e.key === '.') {
                e.preventDefault();
                $('#msg-input').focus();
            }
        });
        var chatForm = $('#chat-form');
        var msgInput = $('#msg-input');
        var fileInput = $('#file-input');
        var filePreview = $('#file-preview');
        var fileName = $('#file-name');

        loadMessages();

        $('#btn-attach').on('click', function() {
            fileInput.click();
        });

        fileInput.on('change', function() {
            var file = this.files[0];
            if (file) {
                fileName.text(file.name);
                filePreview.addClass('active');
            }
        });

        $('#file-remove').on('click', function() {
            fileInput.val('');
            filePreview.removeClass('active');
        });

        chatForm.on('submit', function(e) {
            e.preventDefault();

            var message = msgInput.val().trim();
            var file = fileInput[0].files[0];

            if (!message && !file) {
                showToastr('error', 'Error', 'Harap isi pesan atau lampirkan file');
                return;
            }

            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('message', message);
            if (file) {
                formData.append('attachment', file);
            }

            $('#btn-send').prop('disabled', true).html('<i class="ti ti-loader ti-spin ti-md"></i>');

            $.ajax({
                type: 'POST'
                , url: '{{ route("admin.ami-self-assessment.storeResponse", $amiSelfAssessment->id) }}'
                , data: formData
                , contentType: false
                , processData: false
                , success: function(response) {
                    if (response.status) {
                        appendMessage(response.data);
                        msgInput.val('');
                        fileInput.val('');
                        filePreview.removeClass('active');
                        scrollToBottom();
                    } else {
                        showToastr('error', 'Error', response.message);
                    }
                }
                , error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showToastr('error', 'Error', errorMsg);
                }
                , complete: function() {
                    $('#btn-send').prop('disabled', false).html('<i class="ti ti-send ti-md"></i>');
                }
            });
        });

        msgInput.on('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.submit();
            }
        });

        var lastMessageId = 0;
        var pollingInterval = null;

        function loadMessages() {
            $.ajax({
                type: 'GET',
                url: '{{ route("admin.ami-self-assessment.getResponses", $amiSelfAssessment->id) }}',
                success: function(response) {
                    if (response.status && response.data.length > 0) {
                        emptyState.hide();
                        for (var i = 0; i < response.data.length; i++) {
                            appendMessage(response.data[i]);
                            if (response.data[i].id > lastMessageId) {
                                lastMessageId = response.data[i].id;
                            }
                        }
                        scrollToBottom();
                    }
                    // Start polling after initial load
                    startPolling();
                }
            });
        }

        function startPolling() {
            if (pollingInterval) return;
            pollingInterval = setInterval(checkNewMessages, 3000);
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        function checkNewMessages() {
            $.ajax({
                type: 'GET',
                url: '{{ route("admin.ami-self-assessment.getResponses", $amiSelfAssessment->id) }}',
                success: function(response) {
                    if (response.status && response.data.length > 0) {
                        // Get the max ID from server
                        var serverMaxId = 0;
                        for (var i = 0; i < response.data.length; i++) {
                            if (response.data[i].id > serverMaxId) {
                                serverMaxId = response.data[i].id;
                            }
                        }
                        
                        // If server has newer messages than what we have, reload all to maintain order
                        if (serverMaxId > lastMessageId) {
                            messagesContainer.empty();
                            for (var j = 0; j < response.data.length; j++) {
                                appendMessage(response.data[j]);
                            }
                            lastMessageId = serverMaxId;
                            emptyState.hide();
                            scrollToBottom();
                        }
                    }
                }
            });
        }

        // Stop polling when user leaves the page
        $(window).on('beforeunload', function() {
            stopPolling();
        });

        var currentUserRole = '{{ auth()->user()->role }}';
        var currentUserId = {{ auth()->id() }};

        function appendMessage(msg) {
            emptyState.hide();

            var isOwn = msg.is_own;
            var msgClass = isOwn ? 'msg-self' : 'msg-other';
            var userName = msg.user.name;
            var userRole = msg.user.role || '';

            var adminBadge = '';
            if (userRole === 'admin') {
                adminBadge = '<span class="admin-badge"><i class="ti ti-shield-check"></i></span>';
            }

            var attachmentHtml = '';
            if (msg.attachment) {
                var fName = msg.attachment_name || 'Download File';
                attachmentHtml = '<a href="' + msg.attachment + '" target="_blank" class="msg-attachment">' +
                    '<i class="ti ti-download"></i>' + escapeHtml(fName) +
                    '</a>';
            }

            var textHtml = '';
            if (msg.message) {
                textHtml = '<p class="msg-text">' + escapeHtml(msg.message).replace(/\n/g, '<br>') + '</p>';
            }

            // Check if user can edit/delete this message
            var canModify = currentUserRole === 'admin' || msg.user.id === currentUserId;
            var actionsHtml = '';
            if (canModify) {
                actionsHtml = '<span class="msg-actions">' +
                    '<button type="button" class="btn-action btn-edit" data-id="' + msg.id + '" data-message="' + escapeAttr(msg.message || '') + '" title="Edit"><i class="ti ti-pencil"></i></button>' +
                    '<button type="button" class="btn-action btn-delete" data-id="' + msg.id + '" title="Hapus"><i class="ti ti-trash"></i></button>' +
                    '</span>';
            }

            var html = '<div class="chat-message ' + msgClass + '" data-msg-id="' + msg.id + '">' +
                '<div class="msg-content">' +
                '<div class="msg-sender">' + escapeHtml(userName) + adminBadge + '</div>' +
                '<div class="msg-bubble">' +
                textHtml +
                attachmentHtml +
                '</div>' +
                '<div class="msg-info">' + msg.created_at + actionsHtml + '</div>' +
                '</div>' +
                '</div>';

            messagesContainer.append(html);
        }

        // Delete message handler
        $(document).on('click', '.btn-delete', function() {
            var msgId = $(this).data('id');
            var msgElement = $('[data-msg-id="' + msgId + '"]');

            Swal.fire({
                title: 'Hapus Pesan?'
                , text: 'Pesan yang dihapus tidak dapat dikembalikan'
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonText: 'Ya, Hapus'
                , cancelButtonText: 'Batal'
                , customClass: {
                    confirmButton: 'btn btn-danger me-2'
                    , cancelButton: 'btn btn-secondary'
                }
                , buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'DELETE'
                        , url: '{{ url("admin/ami-self-assessment/response/message") }}/' + msgId
                        , data: {
                            _token: '{{ csrf_token() }}'
                        }
                        , success: function(response) {
                            if (response.status) {
                                msgElement.fadeOut(300, function() {
                                    $(this).remove();
                                    if (messagesContainer.children().length === 0) {
                                        emptyState.show();
                                    }
                                });
                                showToastr('success', 'Sukses', response.message);
                            } else {
                                showToastr('error', 'Error', response.message);
                            }
                        }
                        , error: function(xhr) {
                            var errorMsg = 'Terjadi kesalahan';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            showToastr('error', 'Error', errorMsg);
                        }
                    });
                }
            });
        });

        // Edit message handler
        $(document).on('click', '.btn-edit', function() {
            var msgId = $(this).data('id');
            var currentMessage = $(this).data('message');
            var msgElement = $('[data-msg-id="' + msgId + '"]');

            Swal.fire({
                title: 'Edit Pesan'
                , input: 'textarea'
                , inputValue: currentMessage
                , inputPlaceholder: 'Ketik pesan...'
                , showCancelButton: true
                , confirmButtonText: 'Simpan'
                , cancelButtonText: 'Batal'
                , customClass: {
                    confirmButton: 'btn btn-primary me-2'
                    , cancelButton: 'btn btn-secondary'
                }
                , buttonsStyling: false
                , inputValidator: function(value) {
                    if (!value || !value.trim()) {
                        return 'Pesan tidak boleh kosong';
                    }
                }
            }).then(function(result) {
                if (result.isConfirmed && result.value) {
                    $.ajax({
                        type: 'PUT'
                        , url: '{{ url("admin/ami-self-assessment/response/message") }}/' + msgId
                        , data: {
                            _token: '{{ csrf_token() }}'
                            , message: result.value
                        }
                        , success: function(response) {
                            if (response.status) {
                                msgElement.find('.msg-text').html(escapeHtml(response.data.message).replace(/\n/g, '<br>'));
                                msgElement.find('.btn-edit').data('message', response.data.message);
                                showToastr('success', 'Sukses', response.message);
                            } else {
                                showToastr('error', 'Error', response.message);
                            }
                        }
                        , error: function(xhr) {
                            var errorMsg = 'Terjadi kesalahan';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            showToastr('error', 'Error', errorMsg);
                        }
                    });
                }
            });
        });

        function scrollToBottom() {
            chatBody.animate({
                scrollTop: chatBody[0].scrollHeight
            }, 300);
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
    });

</script>
@endpush

