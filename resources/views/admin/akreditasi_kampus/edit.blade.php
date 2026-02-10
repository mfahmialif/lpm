@extends('layouts.admin.template')
@section('title', 'Edit | Akreditasi Kampus')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Edit Akreditasi Kampus</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.akreditasi-kampus.update', ['akreditasiKampus' => $akreditasiKampus->id]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.akreditasi_kampus.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.akreditasi-kampus.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection