<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Perguruan Tinggi</label>
            <input type="text" class="form-control" name="perguruan_tinggi" placeholder="Nama Perguruan Tinggi..." value="{{ old('perguruan_tinggi', @$skorAkreditasi->perguruan_tinggi) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Program Studi</label>
            <select class="form-select select2" name="prodi_id">
                <option value="">Pilih Prodi...</option>
                @foreach($prodis as $prodi)
                <option value="{{ $prodi->id }}" {{ old('prodi_id', @$skorAkreditasi->prodi_id) == $prodi->id ? 'selected' : '' }}>
                    {{ $prodi->nama }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Strata</label>
            <input type="text" class="form-control" name="strata" placeholder="Contoh: S1, S2, S3..." value="{{ old('strata', @$skorAkreditasi->strata) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Wilayah</label>
            <input type="text" class="form-control" name="wilayah" placeholder="Wilayah..." value="{{ old('wilayah', @$skorAkreditasi->wilayah) }}" />
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Nomor SK</label>
            <input type="text" class="form-control" name="no_sk" placeholder="Nomor SK..." value="{{ old('no_sk', @$skorAkreditasi->no_sk) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Tahun SK</label>
            <input type="text" class="form-control" name="tahun_sk" placeholder="Contoh: 2024..." value="{{ old('tahun_sk', @$skorAkreditasi->tahun_sk) }}" />
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Tanggal Kadaluarsa</label>
            <input type="date" class="form-control" name="tgl_kadaluarsa" value="{{ old('tgl_kadaluarsa', isset($skorAkreditasi) && $skorAkreditasi->tgl_kadaluarsa ? $skorAkreditasi->tgl_kadaluarsa->format('Y-m-d') : '') }}" />
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Peringkat</label>
            <input type="text" class="form-control" name="peringkat" placeholder="Contoh: A, B, Unggul, Baik Sekali..." value="{{ old('peringkat', @$skorAkreditasi->peringkat) }}" />
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" required>
                <option value="masih berlaku" {{ old('status', @$skorAkreditasi->status) == 'masih berlaku' ? 'selected' : '' }}>Masih Berlaku</option>
                <option value="kadaluarsa" {{ old('status', @$skorAkreditasi->status) == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label">Link Drive</label>
            <input type="url" class="form-control" name="link_drive" placeholder="https://drive.google.com/..." value="{{ old('link_drive', @$skorAkreditasi->link_drive) }}" />
            <small class="text-muted">Masukkan URL link Google Drive untuk dokumen sertifikat akreditasi (opsional)</small>
        </div>
    </div>
</div>