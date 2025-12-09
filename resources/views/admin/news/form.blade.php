<div class="mb-6">
    <label for="categories" class="form-label">Category</label>
    <input id="categories" name="categories" class="form-control" placeholder="select Units..." tabindex="-1"
        value="{{ old('categories') }}">
</div>
<div class="mb-6">
    <label class="form-label">Title</label>
    <input type="text" class="form-control" name="title" placeholder="Type here..." value="{{ old('title') }}"
        required />
</div>
<div class="mb-6">
    <label class="form-label">Date</label>
    <input type="datetime-local" class="form-control" name="published_at" placeholder="Choose date..."
        value="{{ old('published_at') }}" required />
</div>
<div class="mb-6">
    <label class="form-label">Status</label>
    <select class="select2 form-select" name="status" required>
        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
    </select>
</div>
@if (@$mode == 'edit')
    <div class="mb-6">
        <label class="form-label">Slug</label>
        <input type="text" class="form-control" name="slug" placeholder="Type here..." />
    </div>
@endif
<div class="mb-6">
    <label class="form-label">Image Banner</label>
    <div class="d-flex align-items-end mb-3">
        <div class="preview d-flex align-items-center position-relative w-auto">
            <img class="cropped-image" alt="Cropped Preview"
                src="{{ old('image_name') ?: asset('admin/assets/img/avatars/profile.png') }}" />
        </div>
        <button type="button" class="btn btn-danger ms-2 remove-image {{ old('image_name') ? '' : 'd-none' }}">
            Remove
        </button>
    </div>
    <input class="form-control" type="file" name="image" />
    <input class="form-control" type="hidden" name="image_name" value="{{ old('image_name') }}" />
</div>
<div class="mb-6">
    <label class="form-label">Body / Content News</label>
    <textarea class="summernote" name="body" value="{{ old('body') }}"></textarea>
</div>
<button type="submit" class="btn btn-primary">Submit</button>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                placeholder: 'Type here...',
                tabsize: 2,
                height: 300
            });

            $('.remove-image').on('click', function() {
                $('input[name="image"]').val('');
                $('input[name="image_name"]').val('');
                $('button.remove-image').addClass('d-none');
                $('img.cropped-image').attr('src', "{{ asset('admin/assets/img/avatars/profile.png') }}");
            });

            $('.cropped-image').on('click', function() {
                $('input[name="image"]').click();
            });

            $('input[name="image"]').on('change', function(event) {
                var input = $(this);
                var file = event.target.files[0];

                if (file) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var src = e.target.result;
                        $('img.cropped-image').attr('src', src);
                        $('button.remove-image').removeClass('d-none');

                        $('input[name="image_name"]').val(src);
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    <script>
        const categories = document.querySelector('#categories');
        const dataCategories = @json($categories->pluck('name'));
        let tagifyCategories = new Tagify(categories, {
            whitelist: dataCategories,
            maxTags: 10,
            dropdown: {
                maxItems: 20,
                classname: 'tags-inline',
                enabled: 0,
                closeOnSelect: false
            }
        });

        // restore old tags (dukung format string "a,b" maupun JSON [{"value":"a"}])
        (function restoreCategories() {
            let raw = categories.value || '';

            if (raw.trim().startsWith('[{')) {
                try {
                    const parsed = JSON.parse(raw);
                    raw = Array.isArray(parsed) ? parsed.map(o => (o && o.value ? String(o.value) : ''))
                        .join(',') : '';
                } catch (e) {
                    // biarkan raw apa adanya, fallback ke split koma
                }
            }

            const oldTags = raw.split(',')
                .map(s => s.trim())
                .filter(Boolean);

            if (oldTags.length) {
                tagifyCategories.removeAllTags();
                tagifyCategories.addTags(oldTags);
            }
        })();
    </script>
@endpush
