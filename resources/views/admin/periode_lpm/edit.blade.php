@extends('layouts.admin.template')
@section('title', 'Edit | Periode LPM')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Edit Periode LPM</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.periode-lpm.update', ['periodeLpm' => $periodeLpm->id]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.periode_lpm.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection