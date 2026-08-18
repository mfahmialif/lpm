<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $filename ?? 'Dokumen' }}</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    {{-- Client-side Document Renderers --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/docx-preview@0.3.3/dist/docx-preview.min.js"></script>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-main: #0f172a;
            --bg-panel: #1e293b;
            --border-panel: #334155;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --excel-green: #107c41;
            --word-blue: #185abd;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Top Header Toolbar */
        .preview-header {
            background: var(--bg-panel);
            color: var(--text-light);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border-bottom: 1px solid var(--border-panel);
            flex-shrink: 0;
            z-index: 50;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .btn-header-back {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            border: 1px solid var(--border-panel);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-header-back:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .file-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-excel {
            background: rgba(16, 124, 65, 0.25);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, 0.3);
        }

        .badge-word {
            background: rgba(24, 90, 189, 0.25);
            color: #60a5fa;
            border: 1px solid rgba(96, 165, 250, 0.3);
        }

        .file-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 320px;
        }

        .header-center {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            justify-content: center;
            max-width: 450px;
        }

        .search-box {
            position: relative;
            width: 100%;
        }

        .search-box input {
            width: 100%;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid var(--border-panel);
            border-radius: 8px;
            padding: 6px 12px 6px 34px;
            color: #ffffff;
            font-size: 0.85rem;
            outline: none;
            transition: border 0.2s;
        }

        .search-box input:focus {
            border-color: var(--primary);
        }

        .search-box i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }

        .btn-download {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        }

        .btn-download:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        .btn-tool {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-light);
            border: 1px solid var(--border-panel);
        }

        .btn-tool:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        /* Main Content Viewport */
        .preview-viewport {
            flex: 1;
            overflow: auto;
            position: relative;
            background: #cbd5e1;
            display: flex;
            flex-direction: column;
        }

        /* Loading Spinner State */
        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            gap: 16px;
            color: #334155;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid rgba(79, 70, 229, 0.2);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.9s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Error Banner */
        .error-container {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 30px;
            text-align: center;
            color: #475569;
        }

        .error-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 36px 40px;
            max-width: 480px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .error-card i {
            font-size: 3rem;
            color: #ef4444;
            margin-bottom: 12px;
        }

        /* Excel Table Styles */
        .excel-container {
            display: none;
            flex-direction: column;
            height: 100%;
            background: #ffffff;
            overflow: hidden;
        }

        .excel-table-wrapper {
            flex: 1;
            overflow: auto;
            background: #ffffff;
        }

        .excel-table {
            border-collapse: collapse;
            font-size: 0.875rem;
            width: 100%;
            font-family: 'Inter', sans-serif;
        }

        .excel-table th,
        .excel-table td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            white-space: nowrap;
            text-align: left;
            vertical-align: middle;
        }

        .excel-table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .excel-table thead tr:first-child th {
            background: #f1f5f9;
            font-weight: 700;
            color: #1e293b;
        }

        .excel-table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .excel-table tbody tr:hover {
            background-color: #eff6ff !important;
        }

        .excel-row-header {
            background: #f8fafc !important;
            color: #94a3b8 !important;
            font-weight: 600 !important;
            text-align: center !important;
            user-select: none;
            width: 45px;
            min-width: 45px;
        }

        /* Excel Bottom Sheet Tab Bar */
        .excel-sheets-bar {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 6px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            flex-shrink: 0;
        }

        .sheet-tab-btn {
            background: #e2e8f0;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 0.825rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .sheet-tab-btn:hover {
            background: #cbd5e1;
            color: #1e293b;
        }

        .sheet-tab-btn.active {
            background: var(--excel-green);
            color: #ffffff;
            border-color: var(--excel-green);
            box-shadow: 0 2px 6px rgba(16, 124, 65, 0.3);
        }

        .sheet-info-badge {
            margin-left: auto;
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Word Document Container */
        .docx-container {
            display: none;
            padding: 30px;
            justify-content: center;
            overflow-y: auto;
            min-height: 100%;
        }

        .docx-wrapper {
            background: transparent !important;
            padding: 0 !important;
        }

        .docx-wrapper > section.docx {
            background: #ffffff !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
            margin-bottom: 24px !important;
            border-radius: 4px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 40px !important;
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .file-title {
                max-width: 150px;
            }
            .header-center {
                display: none;
            }
            .docx-wrapper > section.docx {
                padding: 20px !important;
            }
        }
    </style>
</head>
<body>

    <!-- Header Toolbar -->
    <header class="preview-header">
        <div class="header-left">
            <button onclick="window.history.length > 1 ? window.history.back() : window.close()" class="btn-header-back" title="Kembali">
                <i class="ti ti-arrow-left"></i>
                <span class="d-none d-sm-inline">Kembali</span>
            </button>

            @php
                $isExcel = in_array(strtolower($extension ?? ''), ['xls', 'xlsx', 'csv']);
                $isWord = in_array(strtolower($extension ?? ''), ['doc', 'docx']);
            @endphp

            @if($isExcel)
                <span class="file-type-badge badge-excel"><i class="ti ti-table"></i> EXCEL</span>
            @elseif($isWord)
                <span class="file-type-badge badge-word"><i class="ti ti-file-text"></i> WORD</span>
            @else
                <span class="file-type-badge badge-word"><i class="ti ti-file"></i> DOKUMEN</span>
            @endif

            <span class="file-title" title="{{ $filename }}">{{ $filename }}</span>
        </div>

        @if($isExcel)
            <div class="header-center">
                <div class="search-box">
                    <i class="ti ti-search"></i>
                    <input type="text" id="tableSearch" placeholder="Cari dalam lembar kerja...">
                </div>
            </div>
        @elseif($isWord)
            <div class="header-center">
                <button type="button" class="btn-action btn-tool" id="zoomOut" title="Perkecil"><i class="ti ti-minus"></i></button>
                <button type="button" class="btn-action btn-tool" id="zoomReset" title="Ukuran Normal">100%</button>
                <button type="button" class="btn-action btn-tool" id="zoomIn" title="Perbesar"><i class="ti ti-plus"></i></button>
            </div>
        @endif

        <div class="header-right">
            <button onclick="window.print()" class="btn-action btn-tool" title="Cetak Dokumen">
                <i class="ti ti-printer"></i>
                <span class="d-none d-md-inline">Cetak</span>
            </button>
            <a href="{{ $downloadUrl }}" class="btn-action btn-download" title="Unduh File">
                <i class="ti ti-download"></i>
                <span>Download</span>
            </a>
        </div>
    </header>

    <!-- Main Viewport -->
    <main class="preview-viewport" id="previewViewport">
        <!-- Loading State -->
        <div class="loading-container" id="loadingState">
            <div class="spinner"></div>
            <p style="font-weight: 600; font-size: 0.95rem;">Memuat dan merender dokumen...</p>
            <span style="font-size: 0.8rem; color: #64748b;">Merender langsung di browser Anda</span>
        </div>

        <!-- Error State -->
        <div class="error-container" id="errorState">
            <div class="error-card">
                <i class="ti ti-alert-triangle"></i>
                <h3 style="margin-bottom: 8px; color: #1e293b;">Gagal Merender Dokumen</h3>
                <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 20px;">Format dokumen ini mungkin diproteksi kata sandi atau format lama. Anda dapat mengunduhnya secara langsung.</p>
                <a href="{{ $downloadUrl }}" class="btn-action btn-download" style="justify-content: center; width: 100%;">
                    <i class="ti ti-download"></i> Unduh File Sekarang
                </a>
            </div>
        </div>

        <!-- Excel Viewer -->
        <div class="excel-container" id="excelContainer">
            <div class="excel-table-wrapper" id="excelTableWrapper">
                <table class="excel-table" id="excelTable">
                    <tbody id="excelTableBody"></tbody>
                </table>
            </div>
            <div class="excel-sheets-bar" id="excelSheetsBar">
                <!-- Sheet tab buttons dynamically generated here -->
                <div class="sheet-info-badge" id="sheetInfoBadge">0 Baris</div>
            </div>
        </div>

        <!-- Word (DOCX) Viewer -->
        <div class="docx-container" id="docxContainer">
            <div id="docxTarget"></div>
        </div>
    </main>

    <script>
        const fileUrl = @json($fileUrl);
        const fallbackUrl = @json($downloadUrl);
        const fileExtension = @json(strtolower($extension ?? ''));

        const loadingState = document.getElementById('loadingState');
        const errorState = document.getElementById('errorState');
        const excelContainer = document.getElementById('excelContainer');
        const docxContainer = document.getElementById('docxContainer');

        let currentWorkbook = null;
        let activeSheetIndex = 0;
        let zoomLevel = 1.0;

        // Fetch file as ArrayBuffer with fallback support
        async function fetchDocumentData() {
            try {
                const response = await fetch(fileUrl);
                if (!response.ok) throw new Error('Primary URL failed');
                return await response.arrayBuffer();
            } catch (err) {
                console.warn('Primary fetch failed, attempting fallback URL...', err);
                const fallbackResponse = await fetch(fallbackUrl);
                if (!fallbackResponse.ok) throw new Error('Fallback URL failed');
                return await fallbackResponse.arrayBuffer();
            }
        }

        // Initialize document renderer
        async function initPreview() {
            try {
                const buffer = await fetchDocumentData();

                if (['xlsx', 'xls', 'csv'].includes(fileExtension)) {
                    renderExcel(buffer);
                } else if (['docx'].includes(fileExtension)) {
                    await renderDocx(buffer);
                } else {
                    // Try docx-preview as default for office documents
                    await renderDocx(buffer);
                }
            } catch (err) {
                console.error('Error rendering document:', err);
                loadingState.style.display = 'none';
                errorState.style.display = 'flex';
            }
        }

        // Render Excel Spreadsheet
        function renderExcel(arrayBuffer) {
            try {
                currentWorkbook = XLSX.read(arrayBuffer, { type: 'array' });
                if (!currentWorkbook || !currentWorkbook.SheetNames.length) {
                    throw new Error('Workbook is empty or invalid.');
                }

                renderSheetTabs();
                displaySheet(currentWorkbook.SheetNames[0]);

                loadingState.style.display = 'none';
                excelContainer.style.display = 'flex';

                // Setup live search
                const searchInput = document.getElementById('tableSearch');
                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        filterExcelRows(e.target.value.toLowerCase().trim());
                    });
                }
            } catch (err) {
                console.error('Excel render failed:', err);
                loadingState.style.display = 'none';
                errorState.style.display = 'flex';
            }
        }

        function renderSheetTabs() {
            const bar = document.getElementById('excelSheetsBar');
            const badge = document.getElementById('sheetInfoBadge');
            bar.innerHTML = '';

            currentWorkbook.SheetNames.forEach((name, index) => {
                const btn = document.createElement('button');
                btn.className = `sheet-tab-btn ${index === 0 ? 'active' : ''}`;
                btn.innerHTML = `<i class="ti ti-sheet"></i> ${name}`;
                btn.onclick = () => {
                    document.querySelectorAll('.sheet-tab-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    activeSheetIndex = index;
                    displaySheet(name);
                };
                bar.appendChild(btn);
            });

            bar.appendChild(badge);
        }

        function displaySheet(sheetName) {
            const worksheet = currentWorkbook.Sheets[sheetName];
            if (!worksheet) return;

            // Convert worksheet to HTML table
            const html = XLSX.utils.sheet_to_html(worksheet, { header: '', footer: '' });
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;

            const table = tempDiv.querySelector('table');
            const tbody = document.getElementById('excelTableBody');
            tbody.innerHTML = '';

            if (table) {
                const rows = table.querySelectorAll('tr');
                const rowCount = rows.length;

                rows.forEach((row, rIdx) => {
                    const tr = document.createElement('tr');
                    
                    // Add Row Number
                    const thIndex = document.createElement('th');
                    thIndex.className = 'excel-row-header';
                    thIndex.textContent = rIdx + 1;
                    tr.appendChild(thIndex);

                    row.querySelectorAll('td, th').forEach((cell) => {
                        const cellElem = (rIdx === 0) ? document.createElement('th') : document.createElement('td');
                        cellElem.innerHTML = cell.innerHTML || '&nbsp;';
                        if (cell.getAttribute('colspan')) cellElem.setAttribute('colspan', cell.getAttribute('colspan'));
                        if (cell.getAttribute('rowspan')) cellElem.setAttribute('rowspan', cell.getAttribute('rowspan'));
                        tr.appendChild(cellElem);
                    });

                    tbody.appendChild(tr);
                });

                document.getElementById('sheetInfoBadge').textContent = `${rowCount} Baris`;
            }
        }

        function filterExcelRows(query) {
            const rows = document.querySelectorAll('#excelTableBody tr');
            rows.forEach((row, idx) => {
                if (idx === 0) return; // Keep header visible
                const text = row.innerText.toLowerCase();
                row.style.display = (!query || text.includes(query)) ? '' : 'none';
            });
        }

        // Render Word DOCX Document
        async function renderDocx(arrayBuffer) {
            try {
                const container = document.getElementById('docxTarget');
                container.innerHTML = '';

                await docx.renderAsync(arrayBuffer, container, null, {
                    className: 'docx',
                    inWrapper: true,
                    ignoreWidth: false,
                    ignoreHeight: false,
                    ignoreFonts: false,
                    breakPages: true,
                    useBase64URL: true,
                    renderHeaders: true,
                    renderFooters: true,
                    renderFootnotes: true,
                    renderEndnotes: true
                });

                loadingState.style.display = 'none';
                docxContainer.style.display = 'flex';

                // Setup Zoom controls
                const zoomInBtn = document.getElementById('zoomIn');
                const zoomOutBtn = document.getElementById('zoomOut');
                const zoomResetBtn = document.getElementById('zoomReset');

                if (zoomInBtn) {
                    zoomInBtn.onclick = () => updateZoom(0.1);
                    zoomOutBtn.onclick = () => updateZoom(-0.1);
                    zoomResetBtn.onclick = () => resetZoom();
                }
            } catch (err) {
                console.error('Docx render failed:', err);
                loadingState.style.display = 'none';
                errorState.style.display = 'flex';
            }
        }

        function updateZoom(delta) {
            zoomLevel = Math.max(0.5, Math.min(2.0, zoomLevel + delta));
            applyZoom();
        }

        function resetZoom() {
            zoomLevel = 1.0;
            applyZoom();
        }

        function applyZoom() {
            const target = document.getElementById('docxTarget');
            const resetBtn = document.getElementById('zoomReset');
            if (target) {
                target.style.transform = `scale(${zoomLevel})`;
                target.style.transformOrigin = 'top center';
            }
            if (resetBtn) {
                resetBtn.textContent = `${Math.round(zoomLevel * 100)}%`;
            }
        }

        // Kick off preview on load
        document.addEventListener('DOMContentLoaded', initPreview);
    </script>
</body>
</html>
