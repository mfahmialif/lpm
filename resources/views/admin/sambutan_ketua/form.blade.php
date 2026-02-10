<div class="mb-3">
    <label class="form-label">Nama Ketua</label>
    <input type="text" class="form-control" name="nama_ketua" placeholder="Nama Ketua..." value="{{ old('nama_ketua', @$sambutanKetua->nama_ketua) }}" required />
</div>

<div class="mb-3">
    <label class="form-label">Foto</label>
    @if(isset($sambutanKetua) && $sambutanKetua->foto)
    <div class="mb-2">
        <img src="{{ asset('storage/image-sambutan-ketua/' . $sambutanKetua->foto) }}" alt="Foto Ketua" width="100" class="rounded">
    </div>
    @endif
    <input type="file" class="form-control" name="foto" accept="image/*" />
    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto</small>
</div>

<div class="mb-3">
    <label class="form-label">Sambutan</label>
    <textarea class="summernote" name="sambutan" required>{{ old('sambutan', @$sambutanKetua->sambutan) }}</textarea>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            placeholder: 'Isi sambutan...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endpush