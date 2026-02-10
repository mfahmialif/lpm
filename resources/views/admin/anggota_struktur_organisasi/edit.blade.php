@extends('layouts.admin.template')
@section('title', 'Edit | Anggota Struktur Organisasi')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Edit Anggota Struktur Organisasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.anggota-struktur-organisasi.update', ['anggotaStrukturOrganisasi' => $anggotaStrukturOrganisasi->id]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.anggota_struktur_organisasi.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection