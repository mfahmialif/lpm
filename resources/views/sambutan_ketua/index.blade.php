@extends('layouts.home.template')
@section('title', 'Sambutan Ketua - LPM UII Dalwa')
@section('content')

<div style="height: 50px"></div>
<div class="page-content mt-100" style="margin-bottom: 100px">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h1 class="heading text-48 fw-bold mb-3">Sambutan Ketua</h1>
                    <p class="text text-18 text-muted">Lembaga Penjaminan Mutu (LPM) UII Dalwa</p>
                </div>

                <div class="card border-0 shadow-sm radius18" data-aos="fade-up">
                    <div class="card-body p-5">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <!-- Chairman's Image -->
                            @if(isset($sambutan) && $sambutan->foto)
                            <img src="{{ asset('storage/image-sambutan-ketua/' . $sambutan->foto) }}"
                                alt="Foto Ketua"
                                class="rounded-circle mb-3 shadow-sm"
                                style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3 text-secondary" style="width: 120px; height: 120px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="content-text text-18">
                            @if(isset($sambutan) && $sambutan->sambutan)
                            {!! $sambutan->sambutan !!}
                            @else
                            <div class="alert alert-info text-center">
                                Sambutan Ketua belum ditambahkan.
                            </div>
                            @endif
                        </div>

                        <div class="signature mt-5 pt-4 text-center">
                            <p class="text text-18 fw-bold mb-1">Ketua Lembaga Penjaminan Mutu</p>
                            <div class="my-4" style="height: 60px;">
                                <!-- Space for signature image if needed -->
                            </div>
                            <p class="text text-18 fw-bold">
                                {{ isset($sambutan) ? $sambutan->nama_ketua : '' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection