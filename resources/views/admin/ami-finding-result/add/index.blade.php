@extends('layouts.admin.template')
@section('title', 'Add | Hasil Temuan')
@section('content')
    <div class="mb-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-1">Form Add Hasil Temuan AMI</h5>
                    <p class="card-subtitle">Please fill out all the required fields below.</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical ti-md text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="{{ route('admin.ami-finding-result.index') }}" class="dropdown-item">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.ami-finding-result.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.ami-finding-result.form')
                </form>
            </div>
        </div>
    </div>
@endsection
