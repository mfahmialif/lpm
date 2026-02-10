<div class="col-sm-12">
    <label class="form-label" for="kode">Kode Indikator <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="kode" placeholder="Contoh: IND-001" required />
</div>
<div class="col-sm-12">
    <label class="form-label" for="pertanyaan">Indikator <span class="text-danger">*</span></label>
    <textarea class="form-control" name="pertanyaan" rows="3" placeholder="Tuliskan pertanyaan indikator..." required></textarea>
</div>
<div class="col-sm-12">
    <label class="form-label" for="narasi_evaluasi_diri">Narasi Evaluasi Diri</label>
    <textarea class="form-control" name="narasi_evaluasi_diri" rows="5" placeholder="Tuliskan narasi evaluasi diri (panduan, pertanyaan pemandu, parameter, bukti pendukung)..."></textarea>
</div>
<div class="col-sm-6">
    <label class="form-label" for="urutan">Urutan <span class="text-danger">*</span></label>
    <input type="number" class="form-control" name="urutan" placeholder="0" min="0" required />
</div>
<div class="col-sm-6">
    <label class="form-label" for="is_active">Status</label>
    <select class="select2 form-select" name="is_active" required>
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
    </select>
</div>
<hr>
<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <h6 class="mb-0">Item Penskoran</h6>
        <small class="text-muted">Tambahkan item penskoran secara fleksibel</small>
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary btn-add-rubrik">
        <i class="ti ti-plus me-1"></i>Tambah Item
    </button>
</div>
<div class="rubrik-container">
    {{-- Rubrik items will be added dynamically --}}
</div>
