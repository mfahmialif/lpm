@extends('layouts.admin.template')
@section('title', 'Edit | Activity')
@section('content')
    <div class="mb-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-1">Form Edit Activity</h5>
                    <p class="card-subtitle">Please fill out all the required fields below to add a new activity item.</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button"
                        id="MonthlyCampaign" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-md text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="MonthlyCampaign">
                        <a href="{{ url()->previous() }}" class="dropdown-item">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.activity.update', ['activity' => $activity]) }}" method="POST" id="form-edit"
                    enctype="multipart/form-data" onsubmit="disableSubmit(this)">
                    @csrf
                    @method('PUT')
                    @include('admin.activity.form', [
                        'mode' => 'edit',
                    ])
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.summernote').summernote('destroy');

            var activity = @json($activity);
            var unitId = @json($activity->unit->pluck('id'));
            var tagNames = @json($activity->tag->pluck('name'));

            tagNames = tagNames.join(', ');

            let date = new Date(activity.published_at);

            // pastikan format: YYYY-MM-DDTHH:MM
            let formatted = date.getFullYear() + "-" +
                String(date.getMonth() + 1).padStart(2, '0') + "-" +
                String(date.getDate()).padStart(2, '0') + "T" +
                String(date.getHours()).padStart(2, '0') + ":" +
                String(date.getMinutes()).padStart(2, '0');

            $('#form-edit [name="published_at"]').val(formatted);

            tagifyTag.addTags(tagNames);
            $('#form-edit [name="unit_id[]"]').val(unitId).trigger('change');
            $('#form-edit [name="title"]').val(activity.title);
            $('#form-edit [name="slug"]').val(activity.slug);
            $('#form-edit [name="status"]').val(activity.status).trigger('change');

            $('#form-edit [name="body"]').val(activity.body);

            if (activity.image) {
                $('#form-edit img.cropped-image').attr('src', "{{ asset('storage/image-activity/') }}/" + activity
                    .image);
                $('#form-edit [name="image_name"]').val(activity.image);
                $('button.remove-image').removeClass('d-none');
            }

            $('.summernote').summernote({
                placeholder: 'Type here...',
                tabsize: 2,
                height: 300
            });
        });
    </script>
@endpush
