<div class="col-sm-12">
    <label class="form-label" for="accreditation_id">Accreditation</label>
    <select name="accreditation_id" id="accreditation_id" class="form-select select2" required>
        <option value="">Pilih Akreditasi</option>
        @foreach ($accreditations as $item)
            <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->prodi->nama }} ({{ $item->year }})</option>
        @endforeach
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label" for="kategori">Kategori</label>
    <select name="kategori" id="kategori" class="form-select select2">
        <option value="">Pilih Kategori</option>
        <option value="LKPS">LKPS</option>
        <option value="LED">LED</option>
        <option value="LKPT">LKPT</option>
        <option value="DKPS">DKPS</option>
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label" for="name">Nama Accordion / Instrumen</label>
    <input type="text" id="name" class="form-control" name="name" placeholder="Tabel 1-1.1 Dosen Penghitung Rasio..." required />
</div>
<div class="col-sm-12">
    <label class="form-label" for="description">Deskripsi (Opsional)</label>
    <textarea id="description" class="form-control" name="description" rows="3" placeholder="Penjelasan singkat mengenai instrumen ini"></textarea>
</div>
<div class="col-sm-12">
    <label class="form-label" for="order_index">Urutan (Opsional)</label>
    <input type="number" id="order_index" class="form-control" name="order_index" placeholder="1" />
</div>
