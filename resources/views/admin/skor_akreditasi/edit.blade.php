@extends('layouts.admin.template')
@section('title', 'Edit | Skor Akreditasi')
@section('content')
<div class="mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Edit Skor Akreditasi</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.skor-akreditasi.update', ['skorAkreditasi' => $skorAkreditasi->id]) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.skor_akreditasi.form')
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection