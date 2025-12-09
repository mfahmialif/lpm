@extends('layouts.home.template')
@section('title', 'Register')
@section('content')
    <!-- product-cart start -->
    <div class="product-cart-area pd-top-120 pd-bottom-120">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="pe-xl-5 pe-lg-4">
                        <div class="section-title">
                            <div class="row">
                                <h6 class="subtitle tt-uppercase">Connect</h6>
                                <h2 class="title">Ayo <span>Daftar</span></h2>
                                <p class="mb-0 mt-3">Sudah punya akun? <a class="color-base" href="{{ route('login') }}">Login disini</a>
                                </p>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form class="login-form-inner" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="single-input-inner style-border">
                                <input type="text" placeholder="Your Username" name="username"
                                    value="{{ old('username') }}" required>
                            </div>
                            <div class="single-input-inner style-border">
                                <input type="text" placeholder="Name" name="name" value="{{ old('name') }}" required>
                            </div>
                            <div class="single-input-inner style-border">
                                <input type="number" placeholder="Telphone" name="telp" value="{{ old('telp') }}" required>
                            </div>
                            <div class="single-input-inner style-border">
                                <input type="text" placeholder="Affiliate / School / University" name="affiliate" value="{{ old('affiliate') }}" required>
                            </div>
                            <div class="single-input-inner style-border">
                                <input type="text" placeholder="Email @gmail.com or etc" name="email"
                                    value="{{ old('email') }}" required>
                            </div>
                            <select name="sex"
                                style="height: 75px;
                                    background-color: #1a2430;
                                    border: 1px solid #1e2a38;
                                    border-radius: 10px;
                                    padding: 0 40px 0 20px !important;
                                    color: white;
                                    width: 100%;
                                    margin-bottom: 20px;"
                                aria-label="Default select example" required>
                                <option selected>Select Gender</option>
                                @foreach (\Helper::getEnumValues('users', 'sex', ['*']) as $item)
                                    <option>{{ $item }}</option>
                                @endforeach
                            </select>
                            <div class="single-input-inner style-border">
                                <input type="password" placeholder="Password" name="password" required>
                                <span><img src="{{ asset('assets/img/icon/18.png') }}" alt="img"></span>
                            </div>
                            <div class="single-input-inner style-border">
                                <input type="password" placeholder="Re:type Password" name="password_confirmation" required>
                                <span><img src="{{ asset('assets/img/icon/18.png') }}" alt="img"></span>
                            </div>
                            <button type="submit" class="btn btn-base tt-uppercase w-100" href="">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- product-cart end -->
@endsection
