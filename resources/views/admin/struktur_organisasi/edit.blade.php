@extends('layouts.admin.template')
@section('title', 'Edit | Struktur Organisasi')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Edit Struktur Organisasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.struktur-organisasi.update', ['strukturOrganisasi' => $strukturOrganisasi->id]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.struktur_organisasi.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection