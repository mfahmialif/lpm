@extends('layouts.admin.template')
@section('title', 'Add | Asesmen Auditor AMI')
@section('content')
    <div class="mb-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-1">Form Add Asesmen Auditor AMI</h5>
                    <p class="card-subtitle">Please fill out all the required fields below.</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button"
                        id="MonthlyCampaign" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-md text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="MonthlyCampaign">
                        <a href="{{ route('admin.ami-auditor-assessment.index') }}" class="dropdown-item">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ami-auditor-assessment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.ami-auditor-assessment.form')
                </form>
            </div>
        </div>
    </div>
@endsection
