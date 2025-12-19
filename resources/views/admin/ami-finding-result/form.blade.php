<div class="mb-6">
    <label class="form-label">Kategori</label>
    <select class="select2 form-select" name="category_id" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach ($categories as $category)
        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-6">
    <label class="form-label">Prodi Unit</label>
    <select class="select2 form-select" name="prodi_unit_id" required>
        <option value="">-- Pilih Prodi Unit --</option>
        @foreach ($prodiUnits as $prodiUnit)
        <option value="{{ $prodiUnit->id }}" {{ old('prodi_unit_id') == $prodiUnit->id ? 'selected' : '' }}>{{ $prodiUnit->nama }}</option>
        @endforeach
    </select>
</div>
<div class="mb-6">
    <label class="form-label">Pertanyaan Asesmen</label>
    <input type="text" class="form-control" name="assessment_question" placeholder="Type here..." value="{{ old('assessment_question') }}" />
</div>
<div class="mb-6">
    <label class="form-label">Dokumen</label>
    @if (@$mode == 'edit' && @$amiFindingResult->document)
    <div class="mb-2">
        <a href="{{ asset('storage/documents/ami-finding-result/' . $amiFindingResult->document) }}" target="_blank" class="btn btn-sm btn-info">
            <i class="ti ti-file"></i> View Current Document
        </a>
    </div>
    @endif
    <input class="form-control" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx" />
    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX</small>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
