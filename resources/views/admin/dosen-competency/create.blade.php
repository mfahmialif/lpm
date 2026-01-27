@extends('layouts.admin.template')
@section('title', 'Tambah Kompetensi Dosen')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tambah Kompetensi Dosen</h5>
        <a href="{{ route('admin.dosen-competency.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.dosen-competency.store') }}" method="POST" class="row g-3">
            @csrf
            @include('admin.dosen-competency.form')
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <button type="reset" class="btn btn-label-secondary">Reset</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function loadCompetencies(prodiId, selectedValue = null) {
            const $select = $('[name="prodi_competency_id"]');

            if (!prodiId) {
                $select.html('<option value="">Pilih Prodi Terlebih Dahulu</option>').prop('disabled', true);
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.trigger('change');
                }
                return;
            }

            $select.html('<option value="">Loading...</option>').prop('disabled', true);
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.trigger('change');
            }

            $.ajax({
                url: "{{ url('admin/dosen-competency/get-competencies-by-prodi') }}/" + prodiId,
                type: 'GET',
                success: function(response) {
                    let options = '<option value="">Pilih Kompetensi</option>';
                    if (response.length === 0) {
                        options = '<option value="">Tidak ada kompetensi di prodi ini</option>';
                    } else {
                        response.forEach(function(item) {
                            const isSelected = selectedValue == item.id ? 'selected' : '';
                            options += `<option value="${item.id}" ${isSelected}>${item.kode_competency} - ${item.nama_competency}</option>`;
                        });
                    }
                    $select.html(options).prop('disabled', false);
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.trigger('change');
                    }
                },
                error: function() {
                    $select.html('<option value="">Error memuat data</option>');
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.trigger('change');
                    }
                }
            });
        }

        // Event Listener Change Prodi
        $('[name="prodi_id"]').on('change', function() {
            loadCompetencies($(this).val());
        });

        // Initial load if prodi_id is already selected (e.g. from flash back input)
        const initialProdiId = $('[name="prodi_id"]').val();
        if (initialProdiId) {
            loadCompetencies(initialProdiId, "{{ old('prodi_competency_id') }}");
        }
    });
</script>
@endpush