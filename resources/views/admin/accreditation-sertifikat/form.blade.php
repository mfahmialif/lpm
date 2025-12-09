<div class="col-sm-12">
    <label class="form-label" for="prodi_id">Prodi</label>
    <select class="select2 form-select" name="prodi_id" aria-label="Prodi ID" aria-describedby="prodi_id2" required>
        @foreach ($prodi as $prodi)
            <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
        @endforeach
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label" for="view">Link preview(embed)</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="link_file" placeholder="Type here..." aria-label="Type here..."
            aria-describedby="link_file2" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="view">Link View(embed)</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="view" placeholder="Type here..." aria-label="Type here..."
            aria-describedby="view2" required />
    </div>
</div>
