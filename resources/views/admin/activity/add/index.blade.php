@extends('layouts.admin.template')
@section('title', 'Add | Activity')
@section('content')
    <div class="mb-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-1">Form Add Activity</h5>
                    <p class="card-subtitle">Please fill out all the required fields below to add a new activity item.</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button"
                        id="MonthlyCampaign" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-md text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="MonthlyCampaign">
                        <a href="{{ url()->previous() }}" class="dropdown-item">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.activity.store') }}" id="form-add" method="POST" enctype="multipart/form-data" onsubmit="disableSubmit(this)">
                    @csrf
                    @include('admin.activity.form')
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let now = new Date();

        // format: YYYY-MM-DDTHH:MM
        let formatted = now.getFullYear() + "-" +
            String(now.getMonth() + 1).padStart(2, '0') + "-" +
            String(now.getDate()).padStart(2, '0') + "T" +
            String(now.getHours()).padStart(2, '0') + ":" +
            String(now.getMinutes()).padStart(2, '0');

        $('#form-add [name="published_at"]').val(formatted);

        $('#form-add').submit(function(e) {
            $('#form-add button[type="submit"]').attr('disabled', true);
            $('#form-add button[type="submit"]').html('Proses...');
        });
    </script>
@endpush
