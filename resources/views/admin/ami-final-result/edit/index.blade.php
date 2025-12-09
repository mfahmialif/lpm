@extends('layouts.admin.template')
@section('title', 'Edit | Hasil Akhir')
@section('content')
    <div class="mb-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-1">Form Edit Hasil Akhir AMI</h5>
                    <p class="card-subtitle">Please fill out all the required fields below.</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical ti-md text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="{{ route('admin.ami-final-result.index') }}" class="dropdown-item">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ami-final-result.update', ['amiFinalResult' => $amiFinalResult]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.ami-final-result.form', ['mode' => 'edit'])
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var data = @json($amiFinalResult);
            $('[name="ami_period_id"]').val(data.ami_period_id).trigger('change');
            $('[name="prodi_id"]').val(data.prodi_id).trigger('change');
            $('[name="end_score_spme"]').val(data.end_score_spme);
            $('[name="score_ikt"]').val(data.score_ikt);
            $('[name="end_score_ami"]').val(data.end_score_ami);
            $('[name="rank_ami"]').val(data.rank_ami);
            $('[name="accreditation_status"]').val(data.accreditation_status).trigger('change');
            $('[name="note"]').val(data.note);
        });
    </script>
@endpush
