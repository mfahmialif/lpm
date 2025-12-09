@extends('layouts.admin.template')
@section('title', 'Edit | Laporan Kinerja AMI')
@section('content')
    <div class="mb-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-1">Form Edit Laporan Kinerja AMI</h5>
                    <p class="card-subtitle">Please fill out all the required fields below to edit performance report.</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button"
                        id="MonthlyCampaign" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-md text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="MonthlyCampaign">
                        <a href="{{ route('admin.ami-performance-report.index') }}" class="dropdown-item">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ami-performance-report.update', ['amiPerformanceReport' => $amiPerformanceReport]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.ami-performance-report.form', [
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
            var amiPerformanceReport = @json($amiPerformanceReport);

            $('[name="ami_period_id"]').val(amiPerformanceReport.ami_period_id).trigger('change');
            $('[name="prodi_id"]').val(amiPerformanceReport.prodi_id).trigger('change');
            $('[name="number"]').val(amiPerformanceReport.number);
            $('[name="report_date"]').val(amiPerformanceReport.report_date);
            $('[name="start_date"]').val(amiPerformanceReport.start_date);
            $('[name="end_date"]').val(amiPerformanceReport.end_date);
            $('[name="status"]').val(amiPerformanceReport.status).trigger('change');
        });
    </script>
@endpush
