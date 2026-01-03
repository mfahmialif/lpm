@extends('layouts.admin.template')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kelola Kompetensi Program Studi</h5>
                <a href="{{ route('admin.prodi-competency.index') }}" class="btn btn-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.prodi-competency.update', $prodi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" for="prodi_navigator">Program Studi</label>
                        <select class="form-select" id="prodi_navigator">
                            @foreach($prodis as $p)
                            <option value="{{ $p->id }}" {{ $p->id == $prodi->id ? 'selected' : '' }}>
                                {{ $p->nama }} - {{ $p->fakultas }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text">Pilih Program Studi untuk berpindah halaman kelola.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Kompetensi</label>
                        <div class="table-responsive border rounded p-2" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th width="50" class="text-center">
                                            <input class="form-check-input" type="checkbox" id="check-all-competencies">
                                        </th>
                                        <th>Nama Kompetensi</th>
                                        <th>Prodi Pengguna</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($competencies as $comp)
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input competency-checkbox" type="checkbox"
                                                name="competency_ids[]"
                                                value="{{ $comp->id }}"
                                                id="comp_{{ $comp->id }}"
                                                {{ in_array($comp->id, $existingCompetencies) ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <label class="form-check-label w-100 cursor-pointer" for="comp_{{ $comp->id }}">
                                                {{ $comp->nama }}
                                            </label>
                                        </td>
                                        <td>
                                            @forelse($comp->prodis as $p)
                                            <span class="badge bg-label-{{ $p->id == $prodi->id ? 'success' : 'secondary' }} mb-1">
                                                {{ $p->nama }}
                                            </span>
                                            @empty
                                            <span class="text-muted small">- Belum ada -</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-text">Centang kompetensi yang ingin diasosiasikan dengan Prodi ini.</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('check-all-competencies').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.competency-checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });

    document.getElementById('prodi_navigator').addEventListener('change', function() {
        window.location.href = "{{ route('admin.prodi-competency.edit', '') }}/" + this.value;
    });
</script>
@endpush
@endsection