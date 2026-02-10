@extends('layouts.home.template')
@section('title', 'Skor Akreditasi - LPM UII Dalwa')
@section('content')

<div style="height: 50px"></div>
<div class="page-content mt-100" style="margin-bottom: 100px">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-11">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h1 class="heading text-48 fw-bold mb-3">Skor Akreditasi</h1>
                    <p class="text text-18 text-muted">Data Akreditasi Program Studi UII Dalwa</p>
                </div>

                <!-- Akreditasi Institusi -->
                @if (isset($akreditasiKampus) && $akreditasiKampus->count() > 0)
                <div class="card border-0 shadow-sm radius18 mb-5" data-aos="fade-up">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h4 class="mb-0 fw-bold">Akreditasi Institusi</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%">No</th>
                                        <th>Perguruan Tinggi</th>
                                        <th>Akreditasi</th>
                                        <th class="text-center">Tanggal SK</th>
                                        <th class="text-center">Peringkat</th>
                                        <th class="text-center">Kadaluarsa</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($akreditasiKampus as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $item->perguruan_tinggi ?? '-' }}</td>
                                        <td>{{ $item->akreditasi ?? '-' }}</td>
                                        <td class="text-center">
                                            {{ $item->tanggal_sk ? $item->tanggal_sk->format('d M Y') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->peringkat)
                                            <span
                                                class="badge bg-primary px-3 py-2">{{ $item->peringkat }}</span>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $item->kadaluarsa ? $item->kadaluarsa->format('d M Y') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == 'ya')
                                            <span class="badge bg-success">Aktif</span>
                                            @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <h4 class="mb-3 fw-bold px-1" data-aos="fade-up">Akreditasi Program Studi</h4>

                @if ($skorAkreditasis->count() > 0)
                <div class="card border-0 shadow-sm radius18" data-aos="fade-up">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%">No</th>
                                        <th>Perguruan Tinggi</th>
                                        <th>Program Studi</th>
                                        <th class="text-center">Strata</th>
                                        <th>No SK</th>
                                        <th class="text-center">Peringkat</th>
                                        <th class="text-center">Tahun SK</th>
                                        <th class="text-center">Tgl Kadaluarsa</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Sertifikat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($skorAkreditasis as $index => $skor)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $skor->perguruan_tinggi ?? '-' }}</td>
                                        <td>{{ $skor->prodi ? $skor->prodi->nama : '-' }}</td>
                                        <td class="text-center">{{ $skor->strata ?? '-' }}</td>
                                        <td>{{ $skor->no_sk ?? '-' }}</td>
                                        <td class="text-center">
                                            @if ($skor->peringkat)
                                            <span
                                                class="badge bg-primary px-3 py-2">{{ $skor->peringkat }}</span>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $skor->tahun_sk ?? '-' }}</td>
                                        <td class="text-center">
                                            {{ $skor->tgl_kadaluarsa ? $skor->tgl_kadaluarsa->format('d M Y') : '-' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($skor->status == 'masih berlaku')
                                            <span class="badge bg-success">Masih Berlaku</span>
                                            @else
                                            <span class="badge bg-danger">Kadaluarsa</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($skor->link_drive)
                                            <a href="{{ $skor->link_drive }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-1"></i>Unduh
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Summary Stats --}}
                <div class="row mt-4" data-aos="fade-up">
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm radius18 h-100">
                            <div class="card-body text-center p-4">
                                <div class="display-4 fw-bold text-primary mb-2">
                                    {{ $skorAkreditasis->count() }}
                                </div>
                                <p class="text-muted mb-0">Total Akreditasi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm radius18 h-100">
                            <div class="card-body text-center p-4">
                                <div class="display-4 fw-bold text-success mb-2">
                                    {{ $skorAkreditasis->where('status', 'masih berlaku')->count() }}
                                </div>
                                <p class="text-muted mb-0">Masih Berlaku</p>
                            </div>
                        </div>
                    </div>
                    @php
                    $totalAkreditasi = $skorAkreditasis->count();
                    $totalMasihBerlaku = $skorAkreditasis->where('status', 'masih berlaku')->count();
                    $totalKadaluarsa = $skorAkreditasis->where('status', 'kadaluarsa')->count();
                    $total = $totalKadaluarsa + $kampus;
                    @endphp
                    <div class="col-md-4 mb-3">
                        <div class="card border-0 shadow-sm radius18 h-100">
                            <div class="card-body text-center p-4">
                                <div class="display-4 fw-bold text-danger mb-2">
                                    {{ $total }}
                                </div>
                                <p class="text-muted mb-0">Kadaluarsa</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-info text-center" data-aos="fade-up">
                    Data Skor Akreditasi belum tersedia.
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection