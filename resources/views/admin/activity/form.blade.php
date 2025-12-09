<div class="mb-6">
    <label class="form-label">Unit</label>
    <select id="units" class="select2 form-select" name="unit_id[]" multiple required>
        @foreach ($unit as $item)
            <option value="{{ $item->id }}" {{ in_array($item->id, old('unit_id', [])) ? 'selected' : '' }}>
                {{ $item->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-6">
    <label for="tags" class="form-label">Tag</label>
    <input id="tags" name="tags" class="form-control" placeholder="select Tags..." tabindex="-1"
        value="{{ old('tags') }}">
</div>

<div class="mb-6">
    <label class="form-label">Title</label>
    <input type="text" class="form-control" name="title" placeholder="Type here..." required
        value="{{ old('title') }}" />
</div>

<div class="mb-6">
    <label class="form-label">Date</label>
    <input type="datetime-local" class="form-control" name="published_at" placeholder="Choose date..." required
        value="{{ old('published_at') }}" />
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
    <textarea class="summernote" name="body">{{ old('body') }}</textarea>
</div>

<button type="submit" class="btn btn-primary">Submit</button>
@push('scripts')
    <script>
        $(function() {
            $('.summernote').summernote({
                placeholder: 'Type here...',
                tabsize: 2,
                height: 300
            });

            const $units = $('#units');
            if ($.fn.select2) {
                $units.select2();
            }
            const oldUnits = @json(old('unit_id', []));
            $units.val(oldUnits).trigger('change');

            const tagsInput = document.querySelector('#tags');
            const availableTags = @json($tag->pluck('name'));

            window.tagifyTag = new Tagify(tagsInput, {
                whitelist: availableTags,
                maxTags: 10,
                dropdown: {
                    maxItems: 20,
                    classname: 'tags-inline',
                    enabled: 0,
                    closeOnSelect: false
                }
            });

            // restore old tags (dukung format string "a,b" maupun JSON [{"value":"a"}])
            (function restoreOldTags() {
                let raw = tagsInput.value || '';

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
                    window.tagifyTag.removeAllTags();
                    window.tagifyTag.addTags(oldTags);
                }
            })();

            $('.remove-image').on('click', function() {
                $('input[name="image"]').val('');
                $('input[name="image_name"]').val('');
                $(this).addClass('d-none');
                $('img.cropped-image').attr('src', "{{ asset('admin/assets/img/avatars/profile.png') }}");
            });

            $('.cropped-image').on('click', function() {
                $('input[name="image"]').click();
            });

            $('input[name="image"]').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const src = ev.target.result;
                    $('img.cropped-image').attr('src', src);
                    $('.remove-image').removeClass('d-none');
                    $('input[name="image_name"]').val(src);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
