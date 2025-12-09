<div class="mb-6">
    <label class="form-label">Periode AMI</label>
    <select class="select2 form-select" name="ami_period_id" required>
        <option value="">-- Pilih Periode --</option>
        @foreach ($periods as $period)
            <option value="{{ $period->id }}" {{ old('ami_period_id') == $period->id ? 'selected' : '' }}>
                {{ $period->year }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-6">
    <label class="form-label">Prodi</label>
    <select class="select2 form-select" name="prodi_id" required>
        <option value="">-- Pilih Prodi --</option>
        @foreach ($prodis as $prodi)
            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                {{ $prodi->nama }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-6">
    <label class="form-label">Panduan Asesmen</label>
    <textarea class="form-control" name="assessment_guide" rows="4" placeholder="Type here...">{{ old('assessment_guide') }}</textarea>
</div>
<div class="mb-6">
    <label class="form-label">Nama Auditee</label>
    <input type="text" class="form-control" name="auditee_name" placeholder="Type here..." value="{{ old('auditee_name') }}" />
</div>
<div class="mb-6">
    <label class="form-label">Catatan</label>
    <textarea class="form-control" name="note" rows="4" placeholder="Type here...">{{ old('note') }}</textarea>
</div>
<div class="mb-6">
    <label class="form-label">Dokumen</label>
    @if (@$mode == 'edit' && @$amiAuditorAssessment->document)
        <div class="mb-2">
            <a href="{{ asset('storage/documents/ami-auditor-assessment/' . $amiAuditorAssessment->document) }}" target="_blank" class="btn btn-sm btn-info">
                <i class="ti ti-file"></i> View Current Document
            </a>
        </div>
    @endif
    <input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx" />
    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX</small>
</div>
<div class="mb-6">
    <label class="form-label">Status</label>
    <select class="select2 form-select" name="status" required>
        <option value="y" {{ old('status') === 'y' ? 'selected' : '' }}>Active</option>
        <option value="n" {{ old('status') === 'n' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
