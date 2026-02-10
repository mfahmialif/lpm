@extends('layouts.admin.template')
@section('title', 'Add | Akreditasi Kampus')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Tambah Akreditasi Kampus</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.akreditasi-kampus.store') }}" method="POST">
                @csrf
                @include('admin.akreditasi_kampus.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.akreditasi-kampus.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection