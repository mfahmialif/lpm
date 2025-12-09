@extends('layouts.home.template')
@section('title', 'Accreditation - LPM UII Dalwa')
@section('content')

    <div class="multicolumn mt-100 section-padding">
        <div class="container">
            <div class="multicolumn-header section-headings">
                <div class="subheading text-20 subheading-bg aos-init aos-animate" data-aos="fade-up">
                    <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                        viewBox="0 0 14 14" fill="none">
                        <g clip-path="url(#clip0_9088_4143)">
                            <path
                                d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                fill="CurrentColor"></path>
                        </g>
                        <defs>
                            <clipPath>
                                <rect width="14" height="14" fill="CurrentColor"></rect>
                            </clipPath>
                        </defs>
                    </svg>
                    <span>Accreditation Documents</span>
                    <svg class="icon icon-14" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                        viewBox="0 0 14 14" fill="none">
                        <g clip-path="url(#clip0_9088_4143)">
                            <path
                                d="M8.71401 5.28599C11.7514 5.4205 14 5.9412 14 7C14 8.0588 11.7514 8.5795 8.71401 8.71401C8.5795 11.7514 8.0588 14 7 14C5.9412 14 5.4205 11.7514 5.28599 8.71401C2.2486 8.5795 -1.33117e-07 8.0588 0 7C4.62818e-08 5.94119 2.2486 5.4205 5.28599 5.28599C5.4205 2.2486 5.9412 0 7 0C8.0588 0 8.5795 2.2486 8.71401 5.28599Z"
                                fill="CurrentColor"></path>
                        </g>
                        <defs>
                            <clipPath>
                                <rect width="14" height="14" fill="CurrentColor"></rect>
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <h2 class="heading text-50 aos-init aos-animate" data-aos="fade-up">
                    Accreditation Documents
                </h2>
            </div>

            <input type="text" id="searchReq" class="form-control my-3" placeholder="Cari kode atau judul dokumen...">

            <div class="d-flex gap-2">
                <button id="expandAll" class="btn btn-sm btn-primary">Expand All</button>
                <button id="collapseAll" class="btn btn-sm btn-secondary">Collapse All</button>
            </div>


            <div class="multicolumn-inner mt-4">
                {{-- Root requirements --}}
                @if ($requirements->count())
                    @include('components.requirement-tree', ['requirements' => $requirements])
                @else
                    <div class="alert alert-info mb-0">
                        Belum ada dokumennya.
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById("searchReq");

            // ambil semua item accordion
            const items = document.querySelectorAll(".accordion-item");

            searchInput.addEventListener("input", function() {
                const q = this.value.toLowerCase().trim();

                items.forEach(item => {
                    const text = item.innerText.toLowerCase();

                    if (!q) {
                        // kalau pencarian kosong → tampilkan semua
                        item.style.display = "";
                    } else {
                        // cocokkan text dalam item dengan search query
                        item.style.display = text.includes(q) ? "" : "none";
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const expandAllBtn = document.getElementById("expandAll");
            const collapseAllBtn = document.getElementById("collapseAll");

            const collapses = document.querySelectorAll(".accordion-collapse");

            // Tambahan: tombol panah rotate
            const toggleButtons = document.querySelectorAll(".req-toggle-btn[aria-expanded]");

            expandAllBtn.addEventListener("click", () => {
                collapses.forEach(el => {
                    el.classList.add("show");
                    el.style.height = "auto"; // agar terbuka penuh
                });

                toggleButtons.forEach(btn => {
                    btn.setAttribute("aria-expanded", "true");
                });
            });

            collapseAllBtn.addEventListener("click", () => {
                collapses.forEach(el => {
                    el.classList.remove("show");
                    el.style.height = "0"; // tutup rapat
                });

                toggleButtons.forEach(btn => {
                    btn.setAttribute("aria-expanded", "false");
                });
            });
        });
    </script>
@endpush
