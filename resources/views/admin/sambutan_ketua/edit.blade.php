@extends('layouts.admin.template')
@section('title', 'Edit | Sambutan Ketua')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Edit Sambutan Ketua</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sambutan-ketua.update', ['sambutanKetua' => $sambutanKetua->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.sambutan_ketua.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection