<div class="col-sm-12">
    <label class="form-label" for="judul">Judul Hasil Temuan <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="judul" placeholder="Judul hasil temuan..." required>
</div>
<div class="col-sm-12">
    <label class="form-label" for="ringkasan">Ringkasan Manajerial <span class="text-danger">*</span></label>
    <textarea class="form-control" name="ringkasan" rows="5" placeholder="Ringkasan isu mutu..." required></textarea>
</div>
<div class="col-sm-12">
    <label class="form-label" for="kategori">Kategori Dominan <span class="text-danger">*</span></label>
    <select class="form-select" name="kategori" required>
        <option value="">-- Pilih Kategori --</option>
        <option value="kesesuaian">Kesesuaian</option>
        <option value="observasi">Observasi</option>
        <option value="ketidaksesuaian_minor">Ketidaksesuaian Minor</option>
        <option value="ketidaksesuaian_mayor">Ketidaksesuaian Mayor</option>
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label">Temuan Audit Terkait <span class="text-danger">*</span></label>
    <div class="temuan-audit-list border rounded" style="max-height: 250px; overflow-y: auto;padding: 38px !important">
        <div class="text-muted text-center py-2 loading-temuan">
            <i class="ti ti-loader ti-spin me-1"></i> Memuat temuan audit...
        </div>
    </div>
    <small class="text-muted">Centang temuan audit yang dirangkum dalam hasil temuan ini.</small>
</div>
