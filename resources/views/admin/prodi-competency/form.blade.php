<div class="col-sm-12">
    <label class="form-label" for="prodi_nama">Program Studi</label>
    <input type="text" class="form-control" id="prodi_nama" readonly />
    <input type="hidden" name="prodi_id" id="prodi_id" />
</div>

<div class="col-sm-12 mt-3">
    <label class="form-label">Pilih Kompetensi</label>
    <div class="table-responsive border rounded p-2" style="max-height: 300px; overflow-y: auto;">
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th width="50" class="text-center">
                        <input class="form-check-input" type="checkbox" id="check-all-competencies">
                    </th>
                    <th>Kode</th>
                    <th>Nama Kompetensi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($competencies as $comp)
                <tr>
                    <td class="text-center">
                        <input class="form-check-input competency-checkbox" type="checkbox"
                            name="competency_ids[]"
                            value="{{ $comp->id }}"
                            id="comp_{{ $comp->id }}">
                    </td>
                    <td>
                        <label class="form-check-label w-100 cursor-pointer" for="comp_{{ $comp->id }}">
                            {{ $comp->kode }}
                        </label>
                    </td>
                    <td>
                        <label class="form-check-label w-100 cursor-pointer" for="comp_{{ $comp->id }}">
                            {{ $comp->nama }}
                        </label>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>