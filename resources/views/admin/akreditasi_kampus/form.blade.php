<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Perguruan Tinggi</label>
            <input type="text" class="form-control" name="perguruan_tinggi" placeholder="Nama Perguruan Tinggi..." value="{{ old('perguruan_tinggi', @$akreditasiKampus->perguruan_tinggi) }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Akreditasi</label>
            <input type="text" class="form-control" name="akreditasi" placeholder="Lembaga Akreditasi..." value="{{ old('akreditasi', @$akreditasiKampus->akreditasi) }}" />
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Tanggal SK</label>
            <input type="date" class="form-control" name="tanggal_sk" value="{{ old('tanggal_sk', @$akreditasiKampus->tanggal_sk ? $akreditasiKampus->tanggal_sk->format('Y-m-d') : '') }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Peringkat</label>
            <input type="text" class="form-control" name="peringkat" placeholder="Peringkat Akreditasi..." value="{{ old('peringkat', @$akreditasiKampus->peringkat) }}" />
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Kadaluarsa</label>
            <input type="date" class="form-control" name="kadaluarsa" value="{{ old('kadaluarsa', @$akreditasiKampus->kadaluarsa ? $akreditasiKampus->kadaluarsa->format('Y-m-d') : '') }}" />
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-select" name="status" required>
                <option value="tidak" {{ old('status', @$akreditasiKampus->status) == 'tidak' ? 'selected' : '' }}>Tidak Aktif</option>
                <option value="ya" {{ old('status', @$akreditasiKampus->status) == 'ya' ? 'selected' : '' }}>Aktif</option>
            </select>
        </div>
    </div>
</div>