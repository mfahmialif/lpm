<div class="mb-6">
    <label class="form-label">Periode AMI</label>
    <select class="select2 form-select" name="ami_period_id" required>
        <option value="">-- Pilih Periode --</option>
        @foreach ($periods as $period)
            <option value="{{ $period->id }}" {{ old('ami_period_id') == $period->id ? 'selected' : '' }}>{{ $period->year }}</option>
        @endforeach
    </select>
</div>
<div class="mb-6">
    <label class="form-label">Prodi</label>
    <select class="select2 form-select" name="prodi_id" required>
        <option value="">-- Pilih Prodi --</option>
        @foreach ($prodis as $prodi)
            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
        @endforeach
    </select>
</div>
<div class="mb-6">
    <label class="form-label">Skor Akhir SPME</label>
    <input type="text" class="form-control" name="end_score_spme" placeholder="Type here..." value="{{ old('end_score_spme') }}" />
</div>
<div class="mb-6">
    <label class="form-label">Skor IKT</label>
    <input type="text" class="form-control" name="score_ikt" placeholder="Type here..." value="{{ old('score_ikt') }}" />
</div>
<div class="mb-6">
    <label class="form-label">Skor Akhir AMI</label>
    <input type="text" class="form-control" name="end_score_ami" placeholder="Type here..." value="{{ old('end_score_ami') }}" />
</div>
<div class="mb-6">
    <label class="form-label">Peringkat AMI</label>
    <input type="text" class="form-control" name="rank_ami" placeholder="Type here..." value="{{ old('rank_ami') }}" />
</div>
<div class="mb-6">
    <label class="form-label">Status Akreditasi</label>
    <select class="select2 form-select" name="accreditation_status" required>
        <option value="A" {{ old('accreditation_status') === 'A' ? 'selected' : '' }}>A</option>
        <option value="B" {{ old('accreditation_status') === 'B' ? 'selected' : '' }}>B</option>
        <option value="C" {{ old('accreditation_status') === 'C' ? 'selected' : '' }}>C</option>
        <option value="Not Accredited" {{ old('accreditation_status') === 'Not Accredited' ? 'selected' : '' }}>Not Accredited</option>
    </select>
</div>
<div class="mb-6">
    <label class="form-label">Dokumen</label>
    @if (@$mode == 'edit' && @$amiFinalResult->document)
        <div class="mb-2">
            <a href="{{ asset('storage/documents/ami-final-result/' . $amiFinalResult->document) }}" target="_blank" class="btn btn-sm btn-info">
                <i class="ti ti-file"></i> View Current Document
            </a>
        </div>
    @endif
    <input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx" />
    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX</small>
</div>
<div class="mb-6">
    <label class="form-label">Catatan</label>
    <textarea class="form-control" name="note" rows="4" placeholder="Type here...">{{ old('note') }}</textarea>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
