@extends('layouts.admin.template')
@section('title', 'Add | Anggota Struktur Organisasi')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Tambah Anggota Struktur Organisasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.anggota-struktur-organisasi.store') }}" method="POST">
                @csrf
                @include('admin.anggota_struktur_organisasi.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection