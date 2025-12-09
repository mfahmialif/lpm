<div class="col-sm-12">
    <label class="form-label" for="prodi_id">Prodi</label>
    <select class="select2 form-select" name="prodi_id" aria-label="Prodi ID" aria-describedby="prodi_id2" required>
        @foreach ($prodis as $prodi)
            <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
        @endforeach
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label" for="year">Year</label>
    <div class="input-group input-group-merge">
        <input type="number" class="form-control" name="year" placeholder="Type here..." aria-label="Type here..."
            aria-describedby="year2" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="name">Name</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="name" placeholder="Type here..." aria-label="Type here..."
            aria-describedby="name2" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="status">Status</label>
    <select class="select2 form-select" name="status" aria-label="Status" aria-describedby="status2" required>
        @foreach (\Helper::getEnumValues('accreditations', 'status') as $item)
            <option value="{{ $item }}">{{ $item }}</option>
        @endforeach
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label" for="result">Result</label>
    <select class="select2 form-select" name="result" aria-label="Result" aria-describedby="result2" required>
        @foreach (\Helper::getEnumValues('accreditations', 'result') as $item)
            <option value="{{ $item }}">{{ $item }}</option>
        @endforeach
    </select>
</div>
<div class="col-sm-12">
    <label class="form-label" for="result_description">Result Description</label>
    <div class="input-group input-group-merge">
        <input type="text" name="result_description" class="form-control" placeholder="Type here..."
            aria-label="Type here..." aria-describedby="result_description2" />
    </div>
</div>
