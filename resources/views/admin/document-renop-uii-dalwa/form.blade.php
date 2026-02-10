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
    <label class="form-label" for="unit_id">Unit</label>
    <select class="form-select" name="unit_id" id="unit_id" aria-label="Unit">
        <option value="" data-jenis="">-- Pilih Unit --</option>
        @foreach($units as $unit)
        <option value="{{ $unit->id }}" data-jenis="{{ $unit->jenis }}">{{ $unit->nama }}</option>
        @endforeach
    </select>
</div>
<div class="col-sm-12" id="prodi_wrapper" style="display: none;">
    <label class="form-label" for="prodi_id">Program Studi</label>
    <select class="form-select" name="prodi_id" id="prodi_id" aria-label="Program Studi">
        <option value="">-- Pilih Program Studi --</option>
        @foreach($prodis as $prodi)
        <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
        @endforeach
    </select>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const unitSelect = document.getElementById('unit_id');
    const prodiWrapper = document.getElementById('prodi_wrapper');
    const prodiSelect = document.getElementById('prodi_id');
    
    function toggleProdiVisibility() {
        const selectedOption = unitSelect.options[unitSelect.selectedIndex];
        const jenis = selectedOption.getAttribute('data-jenis');
        
        if (jenis === 'Prodi') {
            prodiWrapper.style.display = 'block';
        } else {
            prodiWrapper.style.display = 'none';
            prodiSelect.value = ''; // Reset prodi selection when hidden
        }
    }
    
    unitSelect.addEventListener('change', toggleProdiVisibility);
    
    // Initial check on page load (for edit mode)
    toggleProdiVisibility();
});
</script>
<div class="col-sm-12">
    <label class="form-label" for="file">Upload File</label>
    <div class="input-group input-group-merge">
        <input type="file" class="form-control" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx"
            aria-label="Upload File" aria-describedby="file2" />
    </div>
    <small class="text-muted">Format: PDF, DOC, DOCX, XLS, XLSX. Max: 10MB</small>
</div>