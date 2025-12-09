<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('home') }}/assets/img/favicon.png" type="image/x-icon" />

    <title>Formulir Aktivitas – LPM UII Dalwa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/summernote/summernote-bs5.css') }}" />
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />


    <style>
        :root {
            /* Default = mobile first */
            --page-bg: #e5f7f9;
            --surface: #ffffff;
            --border: #e0e0e0;
            --text: #202124;
            --muted: #5f6368;
            --accent: #3fc1c9;
            --accent-dark: #2aa6ae;
            --danger: #d93025;

            --radius: 10px;
            --width: 92vw;
            /* <= kunci responsive */
            --shadow: 0 1px 3px rgba(60, 64, 67, .3), 0 4px 8px rgba(60, 64, 67, .15);

            --hero-h: 140px;
            /* tinggi banner responsif */
            --section-pad: 16px;
            /* padding kartu */
            --title-size: 1.6rem;
            /* ukuran judul */
        }

        .dropzone {
            border-radius: 5px;
            border: 1px solid var(--border);
        }

        /* Sentuhan halus */
        .hover-card {
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .hover-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .10);
        }

        /* Naikkan skala di layar lebih lebar */
        @media (min-width:576px) {
            :root {
                --hero-h: 160px;
                --section-pad: 18px;
                --title-size: 1.8rem;
            }
        }

        @media (min-width:768px) {
            :root {
                --width: 720px;
                --hero-h: 170px;
                --radius: 12px;
                --title-size: 2rem;
            }
        }

        body {
            background: var(--page-bg);
            color: var(--text);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans";
        }

        /* ===== Banner ===== */
        .hero-wrap {
            display: flex;
            justify-content: center;
            padding-top: 24px;
        }

        .hero {
            width: 100%;
            max-width: var(--width);
            height: var(--hero-h);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            background: #dfe9ea url('{{ asset('home/assets/img/headerform.jpg') }}') center/cover no-repeat;
        }

        .hero-accent {
            width: 100%;
            max-width: var(--width);
            height: 10px;
            margin: 8px auto 0;
            background: var(--accent-dark);
            border-radius: 8px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .05);
        }

        /* ===== Kartu ===== */
        .g-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin: 14px auto 0;
            padding: 0;
            max-width: var(--width);
            overflow: hidden;
        }

        .g-section {
            padding: var(--section-pad);
        }

        .g-title h1 {
            font-size: var(--title-size);
            font-weight: 700;
            margin-bottom: .25rem;
        }

        .g-divider {
            height: 1px;
            background: var(--border);
            margin: 12px 0 4px;
        }

        .g-aux {
            display: flex;
            align-items: center;
            gap: .6rem;
            color: var(--muted);
            flex-wrap: wrap;
            /* <= supaya rapi saat sempit */
        }

        .g-aux .right {
            margin-left: auto;
            color: #8c8c8c;
        }

        .g-label {
            font-weight: 600;
            margin-bottom: 6px;
            display: inline-flex;
            gap: .35rem;
        }

        .g-required {
            color: var(--danger);
            font-weight: 700;
        }

        /* ===== Tombol ===== */
        .g-actions {
            padding: 12px 16px;
            background: #fafafa;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
            /* tombol tidak kepotong di mobile */
        }

        .btn-g {
            background: var(--accent);
            color: #fff;
            border: none;
        }

        .btn-g:hover {
            background: var(--accent-dark);
            color: #fff;
        }

        .btn-outline-g {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-outline-g:hover {
            background: rgba(63, 193, 201, .08);
            color: var(--accent);
            border-color: var(--accent);
        }

        /* ===== Input: hanya border bawah ===== */
        .form-control,
        textarea {
            border: none;
            border-bottom: 2px solid var(--border);
            border-radius: 0;
            box-shadow: none !important;
            background: transparent;
            padding-left: 0;
            padding-right: 0;
        }

        .form-control:focus,
        textarea:focus {
            border-bottom-color: var(--accent);
            box-shadow: none !important;
            background: transparent;
        }

        /* ===== Footer ===== */
        footer {
            width: 100%;
            max-width: var(--width);
            margin: 32px auto 16px;
            text-align: center;
            color: var(--muted);
            font-size: .9rem;
            padding: 0 var(--section-pad);
        }
    </style>
</head>

<body>

    <!-- Banner -->
    <div class="hero-wrap">
        <div class="hero" title="Banner"></div>
    </div>
    <div class="hero-accent"></div>

    <!-- Judul -->
    <section class="g-card g-title">
        <div class="g-section">
            <h1>{{ $activity->title }}</h1>
            <div class="g-aux mb-2 mt-2">
                <i class="bi bi-building"></i>
                <span><strong>{{ $activity->unit->pluck('name')->implode(', ') }}</strong></span>
            </div>
            <div class="g-aux mb-2">
                <i class="bi bi-tags"></i>
                <span><strong>{{ $activity->tag->pluck('name')->implode(', ') }}</strong></span>
            </div>
            <div class="g-divider"></div>
            <div class="mt-2">
                <small class="text-danger"><strong>*</strong> Menunjukkan data yang wajib diisi</small>
            </div>
        </div>
    </section>


    <!-- Undangan -->
    <section class="g-card">
        <div class="g-section">
            <label class="g-label">Undangan <span class="g-required">*</span></label>
            <form method="post" action="{{ route('addactivity.storeDokumen', ['code' => $code]) }}" class="dropzone"
                id="undangan-dz">
                @csrf
                <input type="hidden" name="tipe" value="undangan">
            </form>
            <div class="invalid-feedback">Undangan wajib diisi.</div>
        </div>

        <div id="list-undangan"></div>

    </section>

    <!-- Absensi -->
    <section class="g-card">
        <div class="g-section">
            <label class="g-label">Absensi <span class="g-required">*</span></label>
            <form method="post" action="{{ route('addactivity.storeDokumen', ['code' => $code]) }}" class="dropzone"
                id="absensi-dz">
                @csrf
                <input type="hidden" name="tipe" value="absensi">
            </form>
            <div class="invalid-feedback">Absensi wajib diisi.</div>
        </div>

        <div id="list-absensi"></div>

    </section>

    <!-- Notulen -->
    <section class="g-card">
        <div class="g-section">
            <label class="g-label">Notulen <span class="g-required">*</span></label>
            <form method="post" action="{{ route('addactivity.storeDokumen', ['code' => $code]) }}" class="dropzone"
                id="notulen-dz">
                @csrf
                <input type="hidden" name="tipe" value="notulen">
            </form>
            <div class="invalid-feedback">Notulen wajib diisi.</div>
        </div>

        <div id="list-notulen"></div>

    </section>

    <!-- Gambar -->
    <section class="g-card">
        <div class="g-section">
            <label class="g-label">Gambar <span class="g-required">*</span></label>
            <form method="post" action="{{ route('addactivity.storeDokumen', ['code' => $code]) }}" class="dropzone"
                id="gambar-dz">
                @csrf
                <input type="hidden" name="tipe" value="gambar">
            </form>
            <div class="invalid-feedback">Gambar wajib diisi.</div>
        </div>

        <div id="list-gambar"></div>

    </section>

    <!-- Isi Narasi Kegiatan -->
    <form action="{{ route('addactivity.store', ['code' => $code]) }}" method="POST">
        @csrf
        <section class="g-card">
            <div class="g-section">
                <label class="g-label">Isi Narasi Kegiatan <span class="g-required">*</span></label>
                <textarea class="summernote" name="body">{{ $activity->body }}</textarea>
                <div class="invalid-feedback">Isi Narasi Kegiatan wajib diisi.</div>
            </div>
        </section>

        <!-- Kirim -->
        <section class="g-card">
            <div class="g-actions">
                <button type="submit" class="btn btn-g w-100" id="submitBtn">Kirim</button>
            </div>
        </section>
    </form>

    <!-- Footer -->
    <footer>
        &copy; {{ date('Y') }} LPM UII Dalwa. Form ini dibuat dengan tampilan mirip Google Form.
    </footer>

    <!-- Toast -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index:1080;">
        <div id="submitToast" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Respons Anda telah terekam. Terima kasih!</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="{{ asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/assets/vendor/libs/summernote/summernote-bs5.js') }}"></script>
    <script src="{{ asset('home/assets/js/jquery.loading.min.js') }}"></script>

    @if (Session::has('success'))
        <script>
            alert("{{ Session::get('success') }}");
        </script>
    @endif
    <script>
        initDropZone('undanganDz', 'undangan');
        initDropZone('absensiDz', 'absensi');
        initDropZone('notulenDz', 'notulen');
        initDropZone('gambarDz', 'gambar');

        function initDropZone(id, tipe) {
            Dropzone.options[id] = {
                addRemoveLinks: true, // tampilkan tombol hapus
                dictRemoveFile: "Hapus",
                maxFilesize: 10, // MB
                acceptedFiles: ".doc,.docx,.pdf,image/*",
                init: function() {
                    this.on("removedfile", function(file) {
                        // panggil endpoint delete
                        $.ajax({
                            url: "{{ route('addactivity.destroyDokumen', ['code' => $code]) }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                tipe: tipe,
                                filename: file.upload.filename, // nama file dari upload
                            },
                            success: function(data) {
                                loadDocument(tipe, `#list-${tipe}`);
                            },
                            error: function(e) {
                                console.error("Gagal hapus file:", e);
                            }
                        });
                    });

                    this.on("success", function(file, response) {
                        if (response.filename) {
                            file.upload.filename = response.filename; // update ke nama baru
                        }
                        loadDocument(tipe, `#list-${tipe}`);
                    });
                }
            };
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Type here...',
                tabsize: 2,
                height: 300
            });

            loadDocument('undangan', '#list-undangan');
            loadDocument('absensi', '#list-absensi');
            loadDocument('notulen', '#list-notulen');
            loadDocument('gambar', '#list-gambar');
        });

        // Validasi ringan + toast
        const nama = document.querySelector('[name="nama"]');
        const email = document.querySelector('[name="email"]');
        const radios = document.querySelectorAll('input[name="kategori"]');
        const kategoriError = document.getElementById('kategoriError');

        document.querySelectorAll('input,textarea').forEach(el => {
            el.addEventListener('input', () => el.classList.remove('is-invalid'));
            el.addEventListener('change', () => {
                if (el.name === 'kategori') kategoriError.style.display = 'none';
            });
        });

        function loadDocument(type, element) {
            $(element).loading('start');
            $.ajax({
                type: "GET",
                url: "{{ route('addactivity.getDataDokumen', ['code' => $code]) }}",
                data: {
                    type: type
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    $(element).empty();
                    let content = `
        <div class="row g-3 row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5">
            ${response.data.length > 0
                ? response.data.map(item => `
                                                                                <div class="col">
                                                                                    <div class="card h-100 border-0 shadow-sm hover-card">
                                                                                        <div class="card-body py-2">
                                                                                            <div class="small text-muted text-truncate"
                                                                                                 title="${item.name ?? item.url.split('/').pop()}">
                                                                                                ${item.name ?? item.url.split('/').pop().substring(0, 40)}
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3 mt-auto">
                                                                                            <a href="${item.url}" target="_blank"
                                                                                                class="mb-2 btn btn-outline-primary btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                                                                                                <i class="bi bi-eye"></i>
                                                                                                Lihat File
                                                                                            </a>
                                                                                            <button onclick="destroyDocument('${item.file}','${type}')"
                                                                                                class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                                                                                                <i class="bi bi-trash"></i>
                                                                                                Hapus
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            `).join('')
                : `
                                                                                <div class="col">
                                                                                    <div class="text-muted small w-100" style="margin-left: 16px;margin-bottom:16px">
                                                                                        Belum ada dokumen.
                                                                                    </div>
                                                                                </div>
                                                                            `
            }
        </div>
    `;
                    $(element).append(content);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                },
                complete: function() {
                    $(element).loading('stop');
                }
            });
        }

        function destroyDocument(filename, type) {
            if (confirm("Apakah anda yakin ingin menghapus file ini?")) {
                $.ajax({
                    url: "{{ route('addactivity.destroyDokumen', ['code' => $code]) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        filename: filename,
                        tipe: type
                    },
                    success: function(data) {
                        loadDocument(type, `#list-${type}`);
                    },
                    error: function(e) {
                        console.error("Gagal hapus file:", e);
                    }
                });
            }
        }
    </script>
</body>

</html>
