<div class="col-sm-12">
    <label class="form-label" for="nama">Nama <span class="text-danger">*</span></label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="nama" placeholder="Masukkan nama unit..."
            aria-label="Nama" aria-describedby="nama2" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="jenis">Jenis <span class="text-danger">*</span></label>
    <select class="form-select" name="jenis" aria-label="Jenis" required>
        <option value="">-- Pilih Jenis --</option>
        @foreach (Helper::getEnumValues('units_dokument', 'jenis') as $jenis)
        <option value="{{ $jenis }}">{{ $jenis }}</option>
        @endforeach
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label" for="fakultas">Fakultas</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="fakultas" placeholder="Masukkan nama fakultas..."
            aria-label="Fakultas" aria-describedby="fakultas2" />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="posisi">Posisi</label>
    <div class="input-group input-group-merge">
        <input type="number" class="form-control" name="posisi" placeholder="Masukkan posisi urutan..."
            aria-label="Posisi" aria-describedby="posisi2" min="1" />
    </div>
    <small class="text-muted">Urutan tampilan unit (angka lebih kecil tampil lebih dulu)</small>
</div>