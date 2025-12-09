@extends('layouts.admin.template')
@section('title', 'Edit | Target AMI')
@section('content')
    <div class="mb-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-1">Form Edit Target AMI</h5>
                    <p class="card-subtitle">Please fill out all the required fields below to edit target.</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button"
                        id="MonthlyCampaign" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-md text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="MonthlyCampaign">
                        <a href="{{ route('admin.ami-target.index') }}" class="dropdown-item">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ami-target.update', ['amiTarget' => $amiTarget]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.ami-target.form', [
                        'mode' => 'edit',
                    ])
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var amiTarget = @json($amiTarget);

            $('[name="ami_period_id"]').val(amiTarget.ami_period_id).trigger('change');
            $('[name="prodi_id"]').val(amiTarget.prodi_id).trigger('change');
            $('[name="evaluations"]').val(amiTarget.evaluations);
            $('[name="assessment_guide"]').val(amiTarget.assessment_guide);
            $('[name="status"]').val(amiTarget.status).trigger('change');
        });
    </script>
@endpush
