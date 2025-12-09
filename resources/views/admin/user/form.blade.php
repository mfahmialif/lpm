<div class="col-sm-12">
    <label class="form-label" for="username">Username</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="username" placeholder="Type here..." aria-label="Type here..."
            aria-describedby="username2" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="name">Name</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="name" placeholder="Type here..." aria-label="Type here..."
            aria-describedby="name2" required />
    </div>
</div>
<div class="">
    <label class="form-label">Email</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="email" placeholder="Type here.." aria-label="Type here.."
            aria-describedby="email" />
        <span class="input-group-text">@example.com</span>
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="telp">Telphone</label>
    <div class="input-group input-group-merge">
        <input type="number" class="form-control" name="telp" placeholder="Type here..." aria-label="Type here..."
            aria-describedby="telp2" required />
    </div>
</div>
<div class="col-sm-12">
    <label class="form-label" for="affiliate">Affiliate</label>
    <div class="input-group input-group-merge">
        <input type="text" class="form-control" name="affiliate" placeholder="Type here..." aria-label="Type here..."
            aria-describedby="affiliate2" required />
    </div>
</div>
@if (\Auth::user()->role == 'admin')
    <div class="">
        <label class="form-label">Role</label>
        <select class="select2 form-select" name="role">
            @foreach (\Helper::getEnumValues('users', 'role') as $item)
                <option>{{ $item }}</option>
            @endforeach
        </select>
    </div>
@endif
<div class="">
    <label class="form-label">Gender</label>
    <select class="select2 form-select" name="sex">
        @foreach (\Helper::getEnumValues('users', 'sex', ['*']) as $item)
            <option>{{ $item }}</option>
        @endforeach
    </select>
</div>
<div class="">
    <label class="form-label">Photo</label>
    <div class="d-flex align-items-end mb-3">
        <div class="preview d-flex align-items-center position-relative">
            <img class="cropped-image" alt="Cropped Preview"
                src="{{ asset('admin/assets/img/avatars/profile.png') }}" />
        </div>
        <button type="button" class="btn btn-danger ms-2 remove-image d-none">
            Remove
        </button>
    </div>
    <input class="form-control" type="file" name="upload_photo" />
    <input type="hidden" name="photo">
</div>
<div class="">
    <div class="form-password-toggle">
        <label class="form-label">Password</label>
        <div class="input-group input-group-merge">
            <input type="password" class="form-control" name="password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
        </div>
    </div>
</div>
<div class="">
    <div class="form-password-toggle">
        <label class="form-label">Confirm Password</label>
        <div class="input-group input-group-merge">
            <input type="password" class="form-control" name="confirm_password"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
        </div>
    </div>
</div>
