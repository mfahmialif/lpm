@extends('layouts.admin.template')
@section('title', 'Add | Sambutan Ketua')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Tambah Sambutan Ketua</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sambutan-ketua.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.sambutan_ketua.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection