<div class="col-sm-12">
    <label class="form-label" for="nama">Nama Periode <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="nama" placeholder="Contoh: Periode AMI 2025/2026" required />
</div>
<div class="col-sm-6">
    <label class="form-label" for="tahun_mulai">Tahun Mulai <span class="text-danger">*</span></label>
    <input type="number" class="form-control" name="tahun_mulai" placeholder="2025" min="2000" max="2100" required />
</div>
<div class="col-sm-6">
    <label class="form-label" for="tahun_selesai">Tahun Selesai <span class="text-danger">*</span></label>
    <input type="number" class="form-control" name="tahun_selesai" placeholder="2026" min="2000" max="2100" required />
</div>
<div class="col-sm-12">
    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
    <select class="form-select select2" name="status" required>
        <option value="draft">Draft</option>
        <option value="aktif">Aktif</option>
        <option value="selesai">Selesai</option>
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label" for="deskripsi">Deskripsi</label>
    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Deskripsi periode..."></textarea>
</div>
