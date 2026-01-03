<div class="col-sm-12">
    <label class="form-label" for="nomor_sk">Nomor SK</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="nomor_sk" placeholder="Type here..." aria-label="Nomor SK"
            aria-describedby="nomor_sk2" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="tanggal_sk">Tanggal SK</label>
    <div class="input-group input-group-merge">
        <input type="date" class="form-control" name="tanggal_sk" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="tentang">Tentang</label>
    <div class="input-group input-group-merge">
        <textarea class="form-control" name="tentang" rows="3" placeholder="Perihal SK..." required></textarea>
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="ditetapkan_oleh">Ditetapkan Oleh</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="ditetapkan_oleh" placeholder="Contoh: Rektor" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="file_sk">File SK (PDF)</label>
    <div class="input-group input-group-merge">
        <input type="file" class="form-control" name="file_sk" accept="application/pdf" />
    </div>
    <div class="form-text">Max 5MB. Biarkan kosong jika tidak ingin mengubah file saat edit.</div>
</div>
<div class="col-sm-12">
    <div class="form-check form-switch mt-2">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1">
        <label class="form-check-label" for="is_active">Aktif</label>
    </div>
    <div class="form-text">Centang jika SK ini merupakan SK yang sedang aktif/berlaku.</div>
</div>