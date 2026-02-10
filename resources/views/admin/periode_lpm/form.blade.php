<div class="mb-3">
    <label class="form-label">Dari</label>
    <input type="date" class="form-control" name="dari" value="{{ old('dari', isset($periodeLpm) ? $periodeLpm->dari->format('Y-m-d') : '') }}" required />
</div>

<div class="mb-3">
    <label class="form-label">Sampai</label>
    <input type="date" class="form-control" name="sampai" value="{{ old('sampai', isset($periodeLpm) ? $periodeLpm->sampai->format('Y-m-d') : '') }}" required />
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select class="form-select" name="status" required>
        <option value="tidak" {{ old('status', @$periodeLpm->status) == 'tidak' ? 'selected' : '' }}>Tidak Aktif</option>
        <option value="aktif" {{ old('status', @$periodeLpm->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
    </select>
</div>