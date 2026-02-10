@extends('layouts.admin.template')
@section('title', 'Add | Skor Akreditasi')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Tambah Skor Akreditasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.skor-akreditasi.store') }}" method="POST">
                @csrf
                @include('admin.skor_akreditasi.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection