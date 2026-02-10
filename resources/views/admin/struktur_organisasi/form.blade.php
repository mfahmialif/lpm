<div class="mb-3">
    <label class="form-label">Periode LPM</label>
    <select class="form-select select2" name="periode_lpm_id" required>
        <option value="">Pilih Periode...</option>
        @foreach($periodes as $periode)
        <option value="{{ $periode->id }}" {{ old('periode_lpm_id', @$strukturOrganisasi->periode_lpm_id) == $periode->id ? 'selected' : '' }}>
            {{ $periode->dari->format('Y') }} - {{ $periode->sampai->format('Y') }} ({{ ucfirst($periode->status) }})
        </option>
        @endforeach
    </select>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Penasehat</label>
            <input type="text" class="form-control" name="penasehat" placeholder="Nama Penasehat..." value="{{ old('penasehat', @$strukturOrganisasi->penasehat) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Penanggung Jawab</label>
            <input type="text" class="form-control" name="penanggung_jawab" placeholder="Nama Penanggung Jawab..." value="{{ old('penanggung_jawab', @$strukturOrganisasi->penanggung_jawab) }}" />
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Ketua LPM</label>
            <input type="text" class="form-control" name="ketua_lpm" placeholder="Nama Ketua LPM..." value="{{ old('ketua_lpm', @$strukturOrganisasi->ketua_lpm) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Anggota</label>
            <select class="form-select" id="select-anggota" name="anggota_ids[]" multiple>
                @php
                $selectedIds = isset($strukturOrganisasi) && $strukturOrganisasi->anggota
                ? json_decode($strukturOrganisasi->anggota, true) ?? []
                : [];
                @endphp
                @foreach($anggotaList as $anggota)
                <option value="{{ $anggota->id }}"
                    {{ in_array($anggota->id, $selectedIds) ? 'selected' : '' }}>
                    {{ $anggota->nama }}
                </option>
                @endforeach
            </select>
            <small class="text-muted">Pilih beberapa anggota dari daftar yang tersedia</small>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#select-anggota').select2({
            placeholder: 'Pilih Anggota...',
            allowClear: true,
            width: '100%',
            multiple: true
        });
    });
</script>
@endpush

<hr class="my-4">
<h6 class="mb-3">Pascasarjana</h6>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">KJM Pascasarjana</label>
            <input type="text" class="form-control" name="kjm_pasca_sarjana" placeholder="Nama..." value="{{ old('kjm_pasca_sarjana', @$strukturOrganisasi->kjm_pasca_sarjana) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">GJM Prodi MPI S2</label>
            <input type="text" class="form-control" name="gjm_prodi_mpi_s2" placeholder="Nama..." value="{{ old('gjm_prodi_mpi_s2', @$strukturOrganisasi->gjm_prodi_mpi_s2) }}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">GJM Prodi PAI S2</label>
            <input type="text" class="form-control" name="gjm_prodi_pai_s2" placeholder="Nama..." value="{{ old('gjm_prodi_pai_s2', @$strukturOrganisasi->gjm_prodi_pai_s2) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">GJM Prodi PBA S2</label>
            <input type="text" class="form-control" name="gjm_prodi_pba_s2" placeholder="Nama..." value="{{ old('gjm_prodi_pba_s2', @$strukturOrganisasi->gjm_prodi_pba_s2) }}" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">GJM Prodi PAI S3</label>
            <input type="text" class="form-control" name="gjm_prodi_pai_s3" placeholder="Nama..." value="{{ old('gjm_prodi_pai_s3', @$strukturOrganisasi->gjm_prodi_pai_s3) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">GJM Prodi PBA S3</label>
            <input type="text" class="form-control" name="gjm_prodi_pba_s3" placeholder="Nama..." value="{{ old('gjm_prodi_pba_s3', @$strukturOrganisasi->gjm_prodi_pba_s3) }}" />
        </div>
    </div>
</div>

<hr class="my-4">
<h6 class="mb-3">Fakultas Syariah</h6>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">KJM Fakultas Syariah</label>
            <input type="text" class="form-control" name="kjm_fakultas_syariah" placeholder="Nama..." value="{{ old('kjm_fakultas_syariah', @$strukturOrganisasi->kjm_fakultas_syariah) }}" />
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">GJM Prodi HKI</label>
            <input type="text" class="form-control" name="gjm_prodi_hki" placeholder="Nama..." value="{{ old('gjm_prodi_hki', @$strukturOrganisasi->gjm_prodi_hki) }}" />
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">GJM Prodi ESY</label>
            <input type="text" class="form-control" name="gjm_prodi_esy" placeholder="Nama..." value="{{ old('gjm_prodi_esy', @$strukturOrganisasi->gjm_prodi_esy) }}" />
        </div>
    </div>
</div>

<hr class="my-4">
<h6 class="mb-3">Fakultas Tarbiyah</h6>
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">KJM Fakultas Tarbiyah</label>
            <input type="text" class="form-control" name="kjm_fakultas_tarbiyah" placeholder="Nama..." value="{{ old('kjm_fakultas_tarbiyah', @$strukturOrganisasi->kjm_fakultas_tarbiyah) }}" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">GJM Prodi PAI</label>
            <input type="text" class="form-control" name="gjm_prodi_pai" placeholder="Nama..." value="{{ old('gjm_prodi_pai', @$strukturOrganisasi->gjm_prodi_pai) }}" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">GJM Prodi PBA</label>
            <input type="text" class="form-control" name="gjm_prodi_pba" placeholder="Nama..." value="{{ old('gjm_prodi_pba', @$strukturOrganisasi->gjm_prodi_pba) }}" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">GJM Prodi MPI</label>
            <input type="text" class="form-control" name="gjm_prodi_mpi" placeholder="Nama..." value="{{ old('gjm_prodi_mpi', @$strukturOrganisasi->gjm_prodi_mpi) }}" />
        </div>
    </div>
</div>

<hr class="my-4">
<h6 class="mb-3">Fakultas Dakwah</h6>
<div class="row">
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">KJM Fakultas Dakwah</label>
            <input type="text" class="form-control" name="kjm_fakultas_dakwah" placeholder="Nama..." value="{{ old('kjm_fakultas_dakwah', @$strukturOrganisasi->kjm_fakultas_dakwah) }}" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">GJM Prodi KPI</label>
            <input type="text" class="form-control" name="gjm_prodi_kpi" placeholder="Nama..." value="{{ old('gjm_prodi_kpi', @$strukturOrganisasi->gjm_prodi_kpi) }}" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">GJM Prodi BKI</label>
            <input type="text" class="form-control" name="gjm_prodi_bki" placeholder="Nama..." value="{{ old('gjm_prodi_bki', @$strukturOrganisasi->gjm_prodi_bki) }}" />
        </div>
    </div>
    <div class="col-md-3">
        <div class="mb-3">
            <label class="form-label">GJM Prodi MHU</label>
            <input type="text" class="form-control" name="gjm_prodi_mhu" placeholder="Nama..." value="{{ old('gjm_prodi_mhu', @$strukturOrganisasi->gjm_prodi_mhu) }}" />
        </div>
    </div>
</div>

<hr class="my-4">
<h6 class="mb-3">Fakultas Adab</h6>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">KJM Fakultas Adab</label>
            <input type="text" class="form-control" name="kjm_fakultas_adab" placeholder="Nama..." value="{{ old('kjm_fakultas_adab', @$strukturOrganisasi->kjm_fakultas_adab) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">GJM Prodi SPI</label>
            <input type="text" class="form-control" name="gjm_prodi_spi" placeholder="Nama..." value="{{ old('gjm_prodi_spi', @$strukturOrganisasi->gjm_prodi_spi) }}" />
        </div>
    </div>
</div>