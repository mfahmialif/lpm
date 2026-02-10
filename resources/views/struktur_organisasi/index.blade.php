@extends('layouts.home.template')
@section('title', 'Struktur Organisasi - LPM UII Dalwa')
@section('content')

<div style="height: 50px"></div>
<div class="page-content mt-100" style="margin-bottom: 100px">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h1 class="heading text-48 fw-bold mb-3">Struktur Organisasi</h1>
                    <p class="text text-18 text-muted">Lembaga Penjaminan Mutu (LPM) UII Dalwa</p>
                    @if($aktivPeriode)
                    <span class="badge bg-primary">Periode {{ $aktivPeriode->dari->format('Y') }} - {{ $aktivPeriode->sampai->format('Y') }}</span>
                    @endif
                </div>

                @if($struktur)
                <div class="card border-0 shadow-sm radius18 mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 text-primary">Pimpinan LPM</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @if($struktur->penasehat)
                                    <tr>
                                        <td class="fw-bold" style="width: 40%">Penasehat</td>
                                        <td>{{ $struktur->penasehat }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->penanggung_jawab)
                                    <tr>
                                        <td class="fw-bold">Penanggung Jawab</td>
                                        <td>{{ $struktur->penanggung_jawab }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->ketua_lpm)
                                    <tr>
                                        <td class="fw-bold">Ketua LPM</td>
                                        <td>{{ $struktur->ketua_lpm }}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($anggotaNames))
                                    <tr>
                                        <td class="fw-bold">Anggota</td>
                                        <td>
                                            <ul class="list-unstyled mb-0">
                                                @foreach($anggotaNames as $nama)
                                                <li>{{ $nama }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm radius18 mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 text-primary">Pascasarjana</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @if($struktur->kjm_pasca_sarjana)
                                    <tr>
                                        <td class="fw-bold" style="width: 40%">KJM Pascasarjana</td>
                                        <td>{{ $struktur->kjm_pasca_sarjana }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_mpi_s2)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi MPI S2</td>
                                        <td>{{ $struktur->gjm_prodi_mpi_s2 }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_pai_s2)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi PAI S2</td>
                                        <td>{{ $struktur->gjm_prodi_pai_s2 }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_pba_s2)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi PBA S2</td>
                                        <td>{{ $struktur->gjm_prodi_pba_s2 }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_pai_s3)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi PAI S3</td>
                                        <td>{{ $struktur->gjm_prodi_pai_s3 }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_pba_s3)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi PBA S3</td>
                                        <td>{{ $struktur->gjm_prodi_pba_s3 }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm radius18 mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 text-primary">Fakultas Syariah</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @if($struktur->kjm_fakultas_syariah)
                                    <tr>
                                        <td class="fw-bold" style="width: 40%">KJM Fakultas Syariah</td>
                                        <td>{{ $struktur->kjm_fakultas_syariah }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_hki)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi HKI</td>
                                        <td>{{ $struktur->gjm_prodi_hki }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_esy)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi ESY</td>
                                        <td>{{ $struktur->gjm_prodi_esy }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm radius18 mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 text-primary">Fakultas Tarbiyah</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @if($struktur->kjm_fakultas_tarbiyah)
                                    <tr>
                                        <td class="fw-bold" style="width: 40%">KJM Fakultas Tarbiyah</td>
                                        <td>{{ $struktur->kjm_fakultas_tarbiyah }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_pai)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi PAI</td>
                                        <td>{{ $struktur->gjm_prodi_pai }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_pba)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi PBA</td>
                                        <td>{{ $struktur->gjm_prodi_pba }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_mpi)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi MPI</td>
                                        <td>{{ $struktur->gjm_prodi_mpi }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm radius18 mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 text-primary">Fakultas Dakwah</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @if($struktur->kjm_fakultas_dakwah)
                                    <tr>
                                        <td class="fw-bold" style="width: 40%">KJM Fakultas Dakwah</td>
                                        <td>{{ $struktur->kjm_fakultas_dakwah }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_kpi)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi KPI</td>
                                        <td>{{ $struktur->gjm_prodi_kpi }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_bki)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi BKI</td>
                                        <td>{{ $struktur->gjm_prodi_bki }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_mhu)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi MHU</td>
                                        <td>{{ $struktur->gjm_prodi_mhu }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm radius18 mb-4" data-aos="fade-up">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 text-primary">Fakultas Adab</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    @if($struktur->kjm_fakultas_adab)
                                    <tr>
                                        <td class="fw-bold" style="width: 40%">KJM Fakultas Adab</td>
                                        <td>{{ $struktur->kjm_fakultas_adab }}</td>
                                    </tr>
                                    @endif
                                    @if($struktur->gjm_prodi_spi)
                                    <tr>
                                        <td class="fw-bold">GJM Prodi SPI</td>
                                        <td>{{ $struktur->gjm_prodi_spi }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-info text-center" data-aos="fade-up">
                    Data Struktur Organisasi untuk periode aktif belum tersedia.
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection