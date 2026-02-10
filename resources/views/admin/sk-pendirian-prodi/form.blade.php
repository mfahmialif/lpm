<div class="col-sm-12">
    <label class="form-label" for="nama">Nama Dokumen <span class="text-danger">*</span></label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="nama" placeholder="Masukkan nama dokumen..."
            aria-label="Nama Dokumen" aria-describedby="nama2" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="no_surat">No Surat</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="no_surat" placeholder="Masukkan nomor surat..."
            aria-label="No Surat" aria-describedby="no_surat2" />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="perihal">Perihal</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="perihal" placeholder="Masukkan perihal..."
            aria-label="Perihal" aria-describedby="perihal2" />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="yang_mengeluarkan">Yang Mengeluarkan</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="yang_mengeluarkan" placeholder="Masukkan yang mengeluarkan..."
            aria-label="Yang Mengeluarkan" aria-describedby="yang_mengeluarkan2" />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="status">Status</label>
    <select class="form-select" name="status">
        <option value="">-- Pilih Status --</option>
        <option value="acc">ACC</option>
        <option value="tolak">Tolak</option>
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label" for="file">Upload File</label>
    <div class="input-group input-group-merge">
        <input type="file" class="form-control" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx"
            aria-label="Upload File" aria-describedby="file2" />
    </div>
    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX. Max: 10MB</small>
</div>