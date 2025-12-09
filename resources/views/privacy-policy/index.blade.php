@extends('layouts.home.template')
@section('title', 'Dokumen Akreditasi - LPM UII Dalwa')
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
                    <span>lpm uii dalwa</span>
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
                    Dokumen Akreditasi
                </h2>
            </div>
            <div class="row g-4 justify-content-center mt-5">

                <div class="col-md-8 col-sm-12 ">
                    @foreach ($data as $item)
                        <!-- Sertifikat 1 -->
                        <div class="card certificate-card mb-5">

                            <iframe src="{{ $item->link_file }}" allow="autoplay"></iframe>
                            <div class="certificate-title">{{ $item->prodi_name }}</div>


                        </div>
                    @endforeach
                </div>

                <!-- Kolom kanan: kosong -->
                <div class="col-md-4 col-sm-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3">
                        <h5 class="mb-3 text-primary fw-semibold">Daftar Sertifikat</h5>
                        <div class="list-group">
                            @foreach ($listGroup as $d)
                                <a href="{{ $d->view }}" target="_blank"
                                    class="list-group-item list-group-item-action">
                                    {{ $d->prodi_name }}
                                </a>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>
            {{ $data->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection

<style>
    .certificate-card {
        position: relative;
        border: none;
        overflow: hidden;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    /* --- Tampilan default (desktop & tablet) --- */
    .certificate-card iframe {
        width: 100%;
        height: 450px;
        /* tetap seperti semula */
        border: none;
        display: block;
    }

    /* --- Judul di bawah iframe --- */
    .certificate-title {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0, 0, 0, 0.4);
        color: #fff;
        font-size: 1rem;
        text-align: center;
        font-weight: 500;
        backdrop-filter: blur(4px);
    }

    /* --- Responsif untuk HP (layar <= 576px) --- */
    @media (max-width: 576px) {
        .certificate-card iframe {
            height: 260px;
            /* ubah tinggi agar pas di layar HP */
        }

        .certificate-title {
            font-size: 0.85rem;
            padding: 0.5rem;
        }
    }
</style>
