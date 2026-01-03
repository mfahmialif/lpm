@php
$selectedDosen = old('dosen_id', $data->dosen_id ?? '');
$selectedProdi = old('prodi_id', $data->prodiCompetency->prodi_id ?? '');
$selectedPeriode = old('periode_akademik_id', $data->periode_akademik_id ?? '');
$selectedSk = old('sk_kompetensi_id', $data->sk_kompetensi_id ?? '');

$startDate = old('tanggal_mulai', isset($data->tanggal_mulai) ? $data->tanggal_mulai->format('Y-m-d') : '');
$endDate = old('tanggal_selesai', isset($data->tanggal_selesai) ? $data->tanggal_selesai->format('Y-m-d') : '');
@endphp

<div class="col-sm-12">
    <label class="form-label" for="dosen_id">Dosen</label>
    <select class="form-select select2" name="dosen_id" id="dosen_id" required>
        <option value="">Pilih Dosen</option>
        @foreach ($dosens as $dosen)
        <option value="{{ $dosen->id }}" {{ $selectedDosen == $dosen->id ? 'selected' : '' }}>
            {{ $dosen->nama }} ({{ $dosen->nidn }})
        </option>
        @endforeach
    </select>
    @error('dosen_id')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-sm-6">
    <label class="form-label" for="prodi_id">Program Studi</label>
    <select class="form-select select2" name="prodi_id" id="prodi_id" required>
        <option value="">Pilih Prodi</option>
        @foreach ($prodis as $prodi)
        <option value="{{ $prodi->id }}" {{ $selectedProdi == $prodi->id ? 'selected' : '' }}>
            {{ $prodi->nama }}
        </option>
        @endforeach
    </select>
    @error('prodi_id')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-sm-6">
    <label class="form-label" for="prodi_competency_id">Kompetensi</label>
    <select class="form-select select2" name="prodi_competency_id" id="prodi_competency_id" required
        {{ $selectedProdi ? '' : 'disabled' }}>
        <option value="">{{ $selectedProdi ? 'Pilih Kompetensi' : 'Pilih Prodi Terlebih Dahulu' }}</option>
    </select>
    @error('prodi_competency_id')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-sm-6">
    <label class="form-label" for="periode_akademik_id">Periode Akademik</label>
    <select class="form-select select2" name="periode_akademik_id" id="periode_akademik_id" required>
        <option value="">Pilih Periode</option>
        @foreach ($periodes as $periode)
        <option value="{{ $periode->id }}"
            {{ $selectedPeriode == $periode->id ? 'selected' : ($periode->is_active && !$selectedPeriode ? 'selected' : '') }}>
            {{ $periode->nama_periode }}
        </option>
        @endforeach
    </select>
    @error('periode_akademik_id')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-sm-6">
    <label class="form-label" for="sk_kompetensi_id">SK Kompetensi</label>
    <select class="form-select select2" name="sk_kompetensi_id" id="sk_kompetensi_id" required>
        <option value="">Pilih SK</option>
        @foreach ($sks as $sk)
        <option value="{{ $sk->id }}" {{ $selectedSk == $sk->id ? 'selected' : '' }}>
            {{ $sk->nomor_sk }}
        </option>
        @endforeach
    </select>
    @error('sk_kompetensi_id')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="col-sm-6">
    <label class="form-label" for="tanggal_mulai">Tanggal Mulai</label>
    <input type="date" class="form-control" name="tanggal_mulai" value="{{ $startDate }}" />
    @error('tanggal_mulai')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>
<div class="col-sm-6">
    <label class="form-label" for="tanggal_selesai">Tanggal Selesai</label>
    <input type="date" class="form-control" name="tanggal_selesai" value="{{ $endDate }}" />
    @error('tanggal_selesai')
    <small class="text-danger">{{ $message }}</small>
    @enderror
</div>